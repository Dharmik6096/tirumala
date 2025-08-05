<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblVehicleCleaningInspection;
use app\modules\tankermovement\models\TblVehicleCleaningInspectionSearch;
use yii\web\NotFoundHttpException;
use app\modules\configuration\models\TblConfig;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripHistory;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblVehicleCleaningInspectionHistory;
use app\modules\tankermovement\models\TblConfigTxnResultHistory;

/**
 * TblVehicleCleaningInspectionController implements the CRUD actions for TblVehicleCleaningInspection model.
 */
class TblVehicleCleaningInspectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleCleaningInspection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleCleaningInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'VEHICLE_CLEANING_INSPECTION';
        $config->config_type = 'CONTROL';
        $config_list = $config->getControlConfigList();
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Creates a new TblVehicleCleaningInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblVehicleCleaningInspection();
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'VEHICLE_CLEANING_INSPECTION';
        $config->config_type = 'CONTROL';
        $config_mapping = new TblConfigTxnResult();
        $config_list = $config->getConfigList();
        $saveModel = [];
        $auto_key_config = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->transaction_datetime = date('Y-m-d H:i:s');
            $saveModel[] = $model;

            $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
            $cnt = 1;
            foreach ($config_data as $data) {
                $config_model = new TblConfigTxnResult();
                $config_model->attributes = $model->attributes;
                $config_model->attributes = $data;
                $config_model->config_for = 'VEHICLE_CLEANING_INSPECTION';
                $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                $saveModel[] = $config_model;
                $auto_key_config['TblConfigTxnResult'][] = ['self_key' => 'ref_code', 'parent_key' => 'vehicle_cleaning_inspection_code', 'parent_index' => 0];
                $cnt++;
            }
            if (!empty($model->trip_code) && !empty($model->vehicle_code)) {
                $tripDetail = TblVehicleTrip::find()->where(['trip_code' => $model->trip_code, 'vehicle_code' => $model->vehicle_code, 'is_active' => 1, 'trip_sub_status' => 'cleaning_pending', 'trip_status' => 'closed'])->one();
                $historyModel = new TblVehicleTripHistory();
                Yii::$app->operation->history($tripDetail, $historyModel, UPDATE);
                $tripDetail->sub_status_time = date('Y-m-d H:i:s');
                $tripDetail->trip_sub_status = 'qa_pending';
                $saveModel[] = $historyModel;
                $saveModel[] = $tripDetail;
            }
            $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Vehicle Cleaning', 'create'], $auto_key_config);
            if ($transaction == 'customRedirect') {
                if (!empty($model->trip_code)) {
                    $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
                    Yii::$app->general->setVehicleTripTrackingDetail($tripDetail, $trackingDetail, $model->remarks);
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'config' => $config_mapping,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Deletes an existing TblVehicleCleaningInspection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $saveModel = [];
        $deleteModel = [];
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblVehicleCleaningInspectionHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => Yii::$app->request->post('id'), 'config_for' => 'VEHICLE_CLEANING_INSPECTION'])->all();
        foreach ($configTxnData as $key => $id) {
            $configTxnHistoryModel = new TblConfigTxnResultHistory();
            Yii::$app->operation->history($id, $configTxnHistoryModel, DELETE);
            $deleteModel[] = $id;
            $saveModel[] = $configTxnHistoryModel;
        }

        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Vehicle Cleaning Inspection', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the TblVehicleCleaningInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleCleaningInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleCleaningInspection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
