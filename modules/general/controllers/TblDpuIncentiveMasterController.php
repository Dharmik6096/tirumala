<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblDpuIncentiveMaster;
use app\modules\general\models\TblDpuIncentiveMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\general\models\TblDpuIncentiveMasterHistory;
use app\modules\general\models\TblCollectionIncentiveDeduction;
use app\modules\general\models\TblCollectionIncentiveDeductionSearch;
use app\modules\general\models\TblCollectionIncentiveDeductionHistory;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * TblDpuIncentiveMasterController implements the CRUD actions for TblDpuIncentiveMaster model.
 */
class TblDpuIncentiveMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblDpuIncentiveMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDpuIncentiveMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblDpuIncentiveMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDpuIncentiveMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->from_date = date('Y-m-d');
            $this->model->to_date = date('Y-m-d');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Dpu Incentive Master', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDpuIncentiveMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $detailModel = new TblCollectionIncentiveDeduction();
        $detailModel->from_date = !empty($model->from_date) ? $model->from_date : date('Y-m-d');
        $detailModel->to_date = !empty($model->to_date) ? $model->to_date : date('Y-m-d');
        $model->bmc_code = Yii::$app->general->getforeignkey($model->dcsCode, 'bmc_code');
        $model->mcc_plant_code = Yii::$app->general->getforeignkey($model->dcsCode, 'mcc_plant_code');
        $model->plant_code = Yii::$app->general->getforeignkey($model->dcsCode, 'plant_code');

        $modelSave = [];
        if (Yii::$app->request->post()) {
            $historyModel = new TblDpuIncentiveMasterHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $modelSave[] = $historyModel;
            if (isset(Yii::$app->request->post()['TblCollectionIncentiveDeduction']['incentive_deduction_id'])) {
                $detail_code = Yii::$app->request->post()['TblCollectionIncentiveDeduction']['incentive_deduction_id'];
                if (!empty($detail_code)) {
                    $detailModel = TblCollectionIncentiveDeduction::findOne($detail_code);
                    $historyModel = new TblCollectionIncentiveDeductionHistory();
                    Yii::$app->operation->history($detailModel, $historyModel, 'UPDATE');
                    $modelSave[] = $historyModel;
                }
            }
            $model->load(Yii::$app->request->post());
            $detailModel->load(Yii::$app->request->post());
            $detailModel->scenario = 'create';
            $detailModel->from_date = !empty($detailModel->from_date) ? date('Y-m-d', strtotime($detailModel->from_date)) : date('Y-m-d');
            $detailModel->to_date = !empty($detailModel->to_date) ? date('Y-m-d', strtotime($detailModel->to_date)) : date('Y-m-d');
            $detailModel->dcs_code = $model->dcs_code;
            $model->from_date = $detailModel->from_date;
            $model->to_date = $detailModel->to_date;
            $model->scenario = 'update';
            if ($model->validate() && $detailModel->validate()) {
                $modelSave[] = $detailModel;
                $modelSave[] = $model;
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Dpu Incentive', 'edit']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
            } else {
                $record = array_merge(ActiveForm::validate($model), ActiveForm::validate($detailModel));
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
        $searchModel = new TblCollectionIncentiveDeductionSearch();
        $searchModel->attributes = $model->attributes;
        $dataProvider = $searchModel->search([]);

        return $this->render('update', [
                    'model' => $model,
                    'detailModel' => $detailModel,
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblDpuIncentiveMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDpuIncentiveMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDpuIncentiveMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblCollectionIncentiveDeductionSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblCollectionIncentiveDeduction'));
        $searchModel->setAttributes(Yii::$app->request->get('TblDpuIncentiveMaster'));
        $searchModel->from_date = !empty($searchModel->from_date) ? date('Y-m-d', strtotime($searchModel->from_date)) : '';
        $searchModel->to_date = !empty($searchModel->to_date) ? date('Y-m-d', strtotime($searchModel->to_date)) : '';
        $dataProvider = $searchModel->search([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdateDetail() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['incentive_deduction_id'])) {
            $Data = TblCollectionIncentiveDeduction::findOne($_POST['incentive_deduction_id']);
            if (!empty($Data)) {
                $Data->from_date = date('d-m-Y', strtotime($Data->from_date));
                $Data->to_date = date('d-m-Y', strtotime($Data->to_date));
                $modelData = $Data->attributes;
                $data['status'] = 'success';
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

}
