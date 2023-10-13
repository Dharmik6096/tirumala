<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblBmcDispatchStockSearch;
use app\modules\tankermovement\models\TblBmcDispatchStockHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use kartik\form\ActiveForm;

/**
 * TblBmcDispatchStockController implements the CRUD actions for TblBmcDispatchStock model.
 */
class TblBmcDispatchStockController extends \app\controllers\ChildController {

    public $freeAccessActions = ['purchase-detail'];

    /**
     * Lists all TblBmcDispatchStock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcDispatchStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcDispatchStock model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcDispatchStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBmcDispatchStock();
        $this->viewFile = 'create';
        $this->setCode($this->model);
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->bmc_dispatch_stock_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->to_date = ($this->model->to_date) ? Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) : '';
            $this->model->to_date = $this->model->to_date . ' ' . \Yii::$app->general->getshift($this->model->to_shift_code);
            $this->model->from_date = ($this->model->from_date) ? Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) : '';
            $this->model->from_date = $this->model->from_date . ' ' . \Yii::$app->general->getshift($this->model->from_shift_code);
            $this->model->type = 'physical';
            $this->model->scenario = 'create';

            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['BMC Dispatch Stock', 'create']);
                if ($transaction == 'customRedirect') {
                    $searchModel = new TblBmcDispatchStockSearch();
                    $searchModel->setAttributes(Yii::$app->request->get('TblBmcDispatchStock'));
                    $searchModel->union_code = $this->model->union_code;
                    $searchModel->plant_code = $this->model->plant_code;
                    $searchModel->mcc_plant_code = $this->model->mcc_plant_code;
                    $searchModel->bmc_code = $this->model->bmc_code;
                    $searchModel->to_date = $this->model->to_date;
                    $searchModel->to_shift_code = $this->model->to_shift_code;
                    $dataProvider = $searchModel->searchBmcStockDetail([$searchModel->union_code, $searchModel->plant_code, $searchModel->mcc_plant_code, $searchModel->bmc_code, $searchModel->to_date, $searchModel->to_shift_code]);
                    $dataHtml = $this->renderAjax('_transaction_detail', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'data' => $dataHtml];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    public function actionPurchaseDetail() {
        $from_datetime = date('Y-m-d', strtotime(Yii::$app->request->get('from_date'))) . ' ' . \Yii::$app->general->getshift(Yii::$app->request->get('from_shift'));
        $to_datetime = date('Y-m-d', strtotime(Yii::$app->request->get('to_date'))) . ' ' . \Yii::$app->general->getshift(Yii::$app->request->get('to_shift'));
        $bmc_code = Yii::$app->request->get('bmc_code');
        $query = \Yii::$app->db->createCommand("{CALL sp_portal_bmc_purchase_detail (:bmc_code,:from_datetime,:to_datetime)}")
                ->bindValue(':from_datetime', $from_datetime)
                ->bindValue(':to_datetime', $to_datetime)
                ->bindValue(':bmc_code', $bmc_code);
        $result = $query->queryAll();
        $stock_detail = [];

        foreach ($result as $r) {
            $key = $r['bmc_silos_info_code'] . '_' . $r['animal_type_code'] . '_' . $r['milk_quality_type_code'];
            if (empty($stock_detail[$key])) {
                $stock_detail[$key]['previous_qty'] = 0;
                $stock_detail[$key]['purchase_qty'] = 0;
            }
            $stock_detail[$key]['previous_qty'] = $stock_detail[$key]['previous_qty'] + $r['previous_qty'];
            $stock_detail[$key]['purchase_qty'] = $stock_detail[$key]['purchase_qty'] + $r['purchase_qty'];
        }
        return $this->renderAjax('_purchase_detail', [
                    'result' => $result,
                    'stock_detail' => $stock_detail
        ]);
    }

    public function setCode($model) {
        $plants = explode(',', $_SESSION['Plant']);
        $mccs = explode(',', $_SESSION['MCC']);
        $bmcs = explode(',', $_SESSION['BMC']);
        count($plants) == 1 ? $model->plant_code = $plants[0] : '';
        count($mccs) == 1 ? $model->mcc_plant_code = $mccs[0] : '';
        if (count($bmcs) == 1) {
            $model->bmc_code = $bmcs[0];
            $stock_date = TblBmcDispatchStock::find()->where(['bmc_code' => $model->bmc_code])->orderBy(['to_date' => SORT_DESC])->one();
            if (!empty($stock_date)) {
                $dispatch_date = ($stock_date->type == 'physical') ? date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date))) . '.000000' : $stock_date->to_date;
                $converted_time = date("H:i:s", strtotime($dispatch_date));
                if ($converted_time == "06:00:00") {
                    $model->from_shift_code = 1;
                } elseif ($converted_time == "18:00:00") {
                    $model->from_shift_code = 2;
                }
                $model->from_date = $dispatch_date;
            }
        }
    }

    /**
     * Updates an existing TblBmcDispatchStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblBmcDispatchStockHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['BMC Dispatch Stock', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBmcDispatchStock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBmcDispatchStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBmcDispatchStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcDispatchStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
