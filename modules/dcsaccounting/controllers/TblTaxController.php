<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblTax;
use app\modules\dcsaccounting\models\TblTaxHistory;
use app\modules\dcsaccounting\models\TblTaxSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsaccounting\models\TblBasicTax;
use app\modules\dcsaccounting\models\TblTaxDetail;
use app\modules\dcsaccounting\models\TblTaxDetailHistory;
use app\modules\dcsaccounting\models\TblTaxDepends;
use app\modules\dcsaccounting\models\TblTaxDependsHistory;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\dcsaccounting\models\TblLedgerMappingTaxDetailSearch;
use app\modules\dcsaccounting\models\TblLedgerMappingTaxDetail;
use app\modules\dcsaccounting\models\TblLedgerMappingTaxDetailHistory;

/**
 * TblTaxController implements the CRUD actions for TblTax model.
 */
class TblTaxController extends \app\controllers\ChildController {

    /**
     * Lists all TblTax models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTaxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTax model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTax model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblTax();
        $this->viewFile = 'create';
        $basicTax = new TblBasicTax();
        $grossAmountId = $basicTax->getGrossAmount();

        if (empty($grossAmountId)) {
            Yii::$app->display->message(true, 'Please add basic tax Gross Amount.', 'info');
            return $this->redirect(['index']);
        }
        if ($this->model->load(Yii::$app->request->post())) {

            $this->model->tax_name = ucwords($this->model->tax_name);
            $this->model->tax_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $mapList = [];
            $mapList = $this->addTaxDetail($this->model, $grossAmountId);

            $transaction = $this->generalModel->saveTransaction([$this->model], $mapList, ['Tax', 'create']);

            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    private function addTaxDetail($taxModel, $basicTaxCode) {

        $model = new TblTaxDetail();
        $model->tax_detail_code = Yii::$app->general->getCodeAutoIncrement($model);
        $model->tax_code = $taxModel->tax_code;
        $model->tax_group_code = $taxModel->tax_group_code;
        $model->basic_tax_code = $basicTaxCode->basic_tax_code;
        $model->percentage = 100;
        $model->type = 0;
        $model->is_active = 1;
        $model->union_code = $taxModel->union_code;

        $mapList[] = $model;

        $taxDependModel = new TblTaxDepends();
        $taxDependModel->tax_depends_code = Yii::$app->general->getCodeAutoIncrement($taxDependModel);
        $taxDependModel->tax_detail_code = $model->tax_detail_code;
        $taxDependModel->steps = 1;
        $taxDependModel->is_active = 1;
        $taxDependModel->union_code = $taxModel->union_code;
        $mapList[] = $taxDependModel;

        return $mapList;
    }

    /**
     * Updates an existing TblTax model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblTaxHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->tax_name = ucwords($this->model->tax_name);
            $master = [];
            $master[] = $this->model;
            $history = [];
            $history[] = $historyModel;
            $details = TblTaxDetail::find()->where(['tax_code' => $this->model->tax_code])->all();
            foreach ($details as $detail) {
                $dependsHistory = new TblTaxDependsHistory();
                Yii::$app->operation->history($detail, $dependsHistory, 'UPDATE');
                $history[] = $dependsHistory;
                $detail->tax_group_code = $this->model->tax_group_code;
                $master[] = $detail;
            }
            $transaction = $this->generalModel->saveTransaction($master, $history, ['Tax', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblTax model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $record = ['status' => 'error', 'msg' => 'No Record Selected'];
        if (!empty($_POST['id'])) {

            $master = [];
            $saveModel = [];
            $tax_code = Yii::$app->request->post('id');
            $this->model = $this->findModel($tax_code);
            if ($this->model) {
                $historyModel = new TblTaxHistory();
                Yii::$app->operation->history($this->model, $historyModel, DELETE);
                $master[] = $historyModel->save(FALSE);
                $details = TblTaxDetail::find()->where(['tax_code' => $tax_code])->all();
                foreach ($details as $key => $id) {
                    $detailHistory = new TblTaxDetailHistory();
                    Yii::$app->operation->history($id, $detailHistory, DELETE);
                    $saveModel[] = $details[$key];
                    $saveModel[] = $detailHistory;
                    $depends = TblTaxDepends::find()->where(['tax_detail_code' => $id['tax_detail_code']])->all();
                    foreach ($depends as $key => $value) {
                        $dependsHistory = new TblTaxDependsHistory();
                        Yii::$app->operation->history($value, $dependsHistory, DELETE);
                        $saveModel[] = $value;
                        $saveModel[] = $dependsHistory;
                    }
                }

                $saveModel[] = $this->model;
                $saveModel[] = $historyModel;
                $record = $this->generalModel->deleteTransaction($saveModel);
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblTax model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTax the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTax::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTaxLedgerMapping($id) {
        $searchModel = new TblLedgerMappingTaxDetailSearch();
        $model = new TblLedgerMappingTaxDetail();
        $dataProvider = $searchModel->mappingSearch(Yii::$app->request->queryParams, $id);

        if (Yii::$app->request->post()) {
            $incCount = 0;
            $save_model = [];
            $historyModel = [];
            $postData = Yii::$app->request->post()['TblTaxDetail'];

            foreach ($postData as $taxDetailCode => $data) {

                $mapping_model = TblLedgerMappingTaxDetail::find()->where(['tax_detail_code' => $taxDetailCode])->one();
                $isChange = false;

                if ($mapping_model) {
                    if ($mapping_model->ledger_code != $data['ledger_code']) {
                        $isChange = true;
                    }
                } else {
                    if (!empty($data['ledger_code'])) {
                        $mapping_model = new TblLedgerMappingTaxDetail();
                        $incCount++;
                        $mapping_model->ledger_mapping_tax_detail_code = Yii::$app->general->getCodeAutoIncrement($mapping_model, $incCount);
                        $mapping_model->tax_detail_code = $taxDetailCode;
                        $mapping_model->union_code = $data['union_code'];
                    }
                }

                if ($mapping_model && ($mapping_model->isNewRecord || $isChange)) {
                    if ($isChange) {
                        $mapingHistory = new TblLedgerMappingTaxDetailHistory();
                        Yii::$app->operation->history($mapping_model, $mapingHistory, 'UPDATE');
                        $historyModel[] = $mapingHistory;
                    }
                    $mapping_model->ledger_code = $data['ledger_code'];
                    $save_model[] = $mapping_model;
                }
            }
            if (!empty($save_model)) {
                $transaction = $this->generalModel->saveTransaction($save_model, $historyModel, ['Tax Detail Ledger Mapping', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
                Yii::$app->session->setFlash('success', ['type' => 'error', 'message' => 'No changes detected. Please update at least one ledger mapping before saving.']);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('mapping', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

}
