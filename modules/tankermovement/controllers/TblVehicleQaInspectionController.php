<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblVehicleQaInspection;
use app\modules\tankermovement\models\TblVehicleQaInspectionSearch;
use yii\web\NotFoundHttpException;
use app\modules\configuration\models\TblConfig;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripHistory;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblVehicleQaInspectionHistory;
use app\modules\tankermovement\models\TblConfigTxnResultHistory;

/**
 * TblVehicleQaInspectionController implements the CRUD actions for TblVehicleQaInspection model.
 */
class TblVehicleQaInspectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleQaInspection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleQaInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'VEHICLE_QA_INSPECTION';
        $config->config_type = 'CONTROL';
        $config_list = $config->getControlConfigList();
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Creates a new TblVehicleQaInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblVehicleQaInspection();
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'VEHICLE_QA_INSPECTION';
        $config->config_type = 'CONTROL';
        $config_mapping = new TblConfigTxnResult();
        $config_list = $config->getConfigList();
        $saveModel = [];
        $auto_key_config = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->transaction_datetime = date('Y-m-d H:i:s');
            $qaInspectionData = $model->find()->where(['vehicle_code' => $model->vehicle_code])->andWhere(['<>', 'status', 'closed'])->all();
            if (!empty($qaInspectionData)) {
                foreach ($qaInspectionData as $key => $inspectionData) {
                    $historyModel = new TblVehicleQaInspectionHistory();
                    Yii::$app->operation->history($inspectionData, $historyModel, UPDATE);
                    $inspectionData->status = 'closed';
                    $saveModel[] = $historyModel;
                    $saveModel[] = $inspectionData;
                }
            } else {
                $model->status = 'pending';
            }
            $saveModel[] = $model;

            $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
            $cnt = 1;
            foreach ($config_data as $data) {
                $config_model = new TblConfigTxnResult();
                $config_model->attributes = $model->attributes;
                $config_model->attributes = $data;
                $config_model->config_for = 'VEHICLE_QA_INSPECTION';
                $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                $saveModel[] = $config_model;
                $auto_key_config['TblConfigTxnResult'][] = ['self_key' => 'ref_code', 'parent_key' => 'vehicle_qa_inspection_code', 'parent_index' => 0];
                $cnt++;
            }
            if (!empty($model->trip_code) && !empty($model->vehicle_code)) {
                $tripDetail = TblVehicleTrip::find()->where(['trip_code' => $model->trip_code, 'vehicle_code' => $model->vehicle_code, 'is_active' => 1])->one();
                $historyModel = new TblVehicleTripHistory();
                Yii::$app->operation->history($tripDetail, $historyModel, UPDATE);
                $tripDetail->sub_status_time = date('Y-m-d H:i:s');
                $tripDetail->trip_sub_status = 'tanker_qualified';
                $saveModel[] = $historyModel;
                $saveModel[] = $tripDetail;
            }
            $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Vehicle QA', 'create'], $auto_key_config);
            if ($transaction == 'customRedirect') {
                $this->redirect(['index']);
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'config' => $config_mapping,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Deletes an existing TblVehicleQaInspection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $saveModel = [];
        $deleteModel = [];
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblVehicleQaInspectionHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => Yii::$app->request->post('id'), 'config_for' => 'VEHICLE_QA_INSPECTION'])->all();
        foreach ($configTxnData as $key => $id) {
            $configTxnHistoryModel = new TblConfigTxnResultHistory();
            Yii::$app->operation->history($id, $configTxnHistoryModel, DELETE);
            $deleteModel[] = $id;
            $saveModel[] = $configTxnHistoryModel;
        }

        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Vehicle QA Inspection', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the TblVehicleQaInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleQaInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleQaInspection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
