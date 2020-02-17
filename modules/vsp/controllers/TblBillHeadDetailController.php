<?php

namespace app\modules\vsp\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Json;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\vsp\models\TblBillHeadDetail;
use app\modules\vsp\models\TblBillHeadDetailSearch;
use app\modules\vsp\models\TblBillHeadInstallment;
use app\modules\organisation\models\TblDcs;
use app\modules\vsp\models\TblBillHead;

/**
 * TblBillHeadDetailController implements the CRUD actions for TblBillHeadDetail model.
 */
class TblBillHeadDetailController extends ChildController {

    /**
     * Lists all TblBillHeadDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBillHeadDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBillHeadDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBillHeadDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBillHeadDetail();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {

            $this->model->bill_head_detail_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->is_active = 1;
            $no = ($this->model->no_installment <= 0) ? 1 : $this->model->no_installment;
            $cycleModel = new \app\modules\payment\models\TblDcsPaymentCycle();
            $installment = [];
            $cycle = $this->model->payment_cycle_code;
            for ($i = 0; $i < $no; $i++) {
                $instModel = new TblBillHeadInstallment();
                $instModel->bill_head_detail_code = $this->model->bill_head_detail_code;
                $instModel->bill_head_code = $this->model->bill_head_code;
                $instModel->dcs_code = $this->model->dcs_code;
                $instModel->installement_cycle = ($i + 1);
                $instModel->installment_amount = ($this->model->amount / $no);
                $instModel->dcs_payment_cycle_code = $cycle;
                array_push($installment, $instModel);
                if ($this->model->no_installment > 1) {
                    $cycle = $cycleModel->getNextCycleCode($instModel->dcs_payment_cycle_code, $this->model->dcs_code);
                }
            }
            $transaction = $this->generalModel->saveTransaction([$this->model], $installment, ['Bill Head Detail', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /*
     * Insertion for BillHeadDetail for multiple society
     * Developed By : Roshani Shah
     * Date : 24/10/2018
     */

    public function actionSocietyBulkInsert() {

        $this->viewFile = 'society_bulk';
        $this->model = new TblBillHeadDetail();
        if (!empty(Yii::$app->request->post()) && $this->model->load(Yii::$app->request->post())) {

            $main = [];
            $installment = [];
            if (!empty(Yii::$app->request->post()['dcs_code'])) {
                foreach (Yii::$app->request->post()['dcs_code'] as $key => $dcs) {
                    $model = new TblBillHeadDetail();
                    $model->load(Yii::$app->request->post());
                    $model->is_active = 1;
                    $model->dcs_code = $dcs;
                    $code = Yii::$app->general->getCodeAutoIncrement($this->model);
                    $model->bill_head_detail_code = ($code + $key);


                    $no = 1;
                    $cycleModel = new \app\modules\payment\models\TblDcsPaymentCycle();
                    array_push($main, $model);
                    $cycle = $model->payment_cycle_code;
                    for ($i = 0; $i < $no; $i++) {
                        $instModel = new TblBillHeadInstallment();
                        $instModel->bill_head_detail_code = $model->bill_head_detail_code;
                        $instModel->bill_head_code = $model->bill_head_code;
                        $instModel->dcs_code = $model->dcs_code;
                        $instModel->installement_cycle = ($i + 1);
                        $instModel->installment_amount = ($model->amount / $no);
                        $instModel->dcs_payment_cycle_code = $cycle;
                        array_push($installment, $instModel);
                        if ($this->model->no_installment > 1) {
                            $cycle = $cycleModel->getNextCycleCode($instModel->dcs_payment_cycle_code, $model->dcs_code);
                        }
                    }
                }
                $transaction = $this->generalModel->saveTransaction($main, $installment, ['Bill Head Detail', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            } else {
                $this->model->addError('dcs_code', Yii::t('app', 'You must select atleast one society.'));
            }
        }
        return $this->customRender();
    }

    public function actionSocietyList() {
        $dcsModel = new TblDcs();
        $dcs_list = $dcsModel->headWiseDcs(Yii::$app->request->post('payment_cycle_code'), Yii::$app->request->post('bill_head_code'));
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $dcs_list]);
    }

    /**
     * Updates an existing TblBillHeadDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bill_head_detail_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblBillHeadDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBillHeadDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBillHeadDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBillHeadDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSocietyWiseTransaction() {
        $this->viewFile = 'society_transaction';
        $this->model = new TblBillHeadDetail();
        if (!empty(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            if (!empty($data['amount'])) {
                $main = [];
                $installment = [];

                foreach ($data['amount'] as $key => $amount) {
                    if ($amount > 0) {
                        if (empty($data['bill_head_detail'][$key])) {
                            $this->addData($data, $key, $main, $installment);                           
                        } else {
                            $editModel = $this->findModel($data['bill_head_detail'][$key]);
                            if ($editModel->amount != $amount) {
                                $historyDetail = new \app\modules\vsp\models\TblBillHeadDetailHistory();
                                Yii::$app->operation->history($editModel, $historyDetail, UPDATE);
                                array_push($main, $historyDetail);
                                $editModel->amount=$amount;
                                array_push($main, $editModel);
                                $instModel = new TblBillHeadInstallment();
                                foreach ($instModel->getData($data['bill_head_detail'][$key]) AS $in) {
                                    $instModel = TblBillHeadInstallment::findOne($in['bill_head_installment_code']);
                                    $historyInstallments = new \app\modules\vsp\models\TblBillHeadInstallmentHistory();
                                    Yii::$app->operation->history($instModel, $historyInstallments, DELETE);
                                    array_push($installment, $historyInstallments);
                                    array_push($installment, $instModel);
                                }
                                $this->addInstallment($editModel, $installment);
                            }
                        }
                    }
                }
//                var_dump($main);
//                var_dump($installment);
                $transaction = $this->generalModel->saveTransaction($main, $installment, ['Bill Head Detail', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
            $this->model->addError('amount', Yii::t('app', 'You have to Add atleast one Amount.'));
        }
        return $this->customRender();
    }

    private function addData($data, $key, &$main, &$installment) {
        $model = new TblBillHeadDetail();
        $model->load($data);
        $model->bill_head_code = $data['bill_head'][$key];
        $model->amount = $data['amount'][$key];
        $model->is_active = 1;
        $model->no_installment=1;
        $code = Yii::$app->general->getCodeAutoIncrement($model);
        $model->bill_head_detail_code = ($code + $key);
        array_push($main, $model);
        $this->addInstallment($model, $installment);
        
    }
    
    private function addInstallment($model,&$installment){
        $instModel = new TblBillHeadInstallment();
        $instModel->bill_head_detail_code = $model->bill_head_detail_code;
        $instModel->bill_head_code = $model->bill_head_code;
        $instModel->dcs_code = $model->dcs_code;
        $instModel->installement_cycle = 1;
        $instModel->installment_amount = $model->amount;
        $instModel->dcs_payment_cycle_code = $model->payment_cycle_code;
        array_push($installment, $instModel);
    }

    public function actionTransactionBillHead() {
        $model = new TblBillHeadDetail();
        $bill_head_model = new TblBillHead();
        $bill_head_data = $bill_head_model->getAllBillHead(Yii::$app->request->post('dcs_code'));
        $bill_head_code = \yii\helpers\ArrayHelper::getColumn($bill_head_data, function($element) {
                    return $element['bill_head_code'];
                });
        $detail_data = $model->getData(Yii::$app->request->post('dcs_code'), Yii::$app->request->post('payment_cycle_code'), $bill_head_code);
        return $this->renderAjax('_society_trn_bill_head', ['model' => $model, 'bill_head_model' => $bill_head_model, 'bill_head_data' => $bill_head_data, 'detail_data' => $detail_data]);
    }

    public function actionBillHeadType() {
        $headModel = new TblBillHead();
        $type = $headModel->billHeadType(Yii::$app->request->post('bill_head_code'));
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $type]);
    }

}
