<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcDispatchInspection;
use app\modules\tankermovement\models\TblBmcDispatchInspectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblConfig;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripHistory;

/**
 * TblBmcDispatchInspectionController implements the CRUD actions for TblBmcDispatchInspection model.
 */
class TblBmcDispatchInspectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcDispatchInspection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcDispatchInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $config = new TblConfig();
        $config->config_for = 'BMC';
        $config->process_name = 'BMC_DISPATCH_INSPECTION';
        $config->config_type = 'CONTROL';
        $config_list = $config->getControlConfigList();
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Creates a new TblBmcDispatchInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($trip_code, $vehicle_trip_detail_code) {
        $model = new TblBmcDispatchInspection();
        $model->trip_code = $trip_code;
        $model->vehicle_trip_detail_code = $vehicle_trip_detail_code;
        $trip_model = $model->tripCode;
        $trip_detail = $model->tripDetailCode;

        if (!empty($trip_model) && !empty($trip_detail)) {
            if (!empty($trip_model->bmc_code)) {
                $model->bmc_code = $trip_detail->destination_code;
                $org_detail = $model->bmcCode;
                $model->union_code = $org_detail->union_code;
                $model->plant_code = $org_detail->plant_code;
                $model->mcc_plant_code = $org_detail->mcc_plant_code;
                $config_text = 'BMC';
                $bmc_plant_value = $trip_model->bmc_code;
            } else {
                $model->scenario = 'generate_auto_trip';
                $model->union_code = $trip_model->union_code;
                $model->plant_code = $trip_model->plant_code;
                $config_text = 'PLANT';
                $bmc_plant_value = $trip_model->plant_code;
            }
            $model->inspection_date = $trip_model->transaction_date;
            $model->trip_code = $trip_model->trip_code;
            $model->vehicle_code = $trip_model->vehicle_code;
            
            $config = new TblConfig();
            $config->config_for = $config_text;
            $config->process_name = 'BMC_DISPATCH_INSPECTION';
            $config->config_type = 'CONTROL';
            $config_mapping = new TblConfigTxnResult();
            $config_list = $config->getOrgConfigList($config->config_for, $bmc_plant_value);
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->inspection_date = $trip_model->transaction_date;
                $model->bmc_dispatch_inspection_code = Yii::$app->general->getPrimaryCode($model);
                $saveModel = [];
                $saveModel[] = $model;
                $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
                $cnt = 1;
                foreach ($config_data as $data) {
                    $config_model = new TblConfigTxnResult();
                    $config_model->attributes = $model->attributes;
                    $config_model->attributes = $data;
                    $config_model->config_for = 'BMC_DISPATCH_INSPECTION';
                    $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                    $config_model->ref_code = $model->bmc_dispatch_inspection_code;
                    $config_detail = $config_model->configCode;
                    $saveModel[] = $config_model;
                    $cnt++;
                }
                $tripModel = TblVehicleTrip::find()->where(['vehicle_trip_code' => $trip_detail->vehicle_trip_code])->one();
                if(!empty($tripModel)){
                    $historyModel = new TblVehicleTripHistory();
                    Yii::$app->operation->history($tripModel, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $tripModel->trip_sub_status = 'quality_checked';
                    $tripModel->sub_status_time = date('Y-m-d H:i:s');
                    $saveModel[] = $tripModel;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Inspection', 'create']);
                if ($transaction == 'customRedirect') {
                    $response = Yii::$app->general->getColumnName($trip_detail->source_org_type);
                    $remarks = $model->remarks;
                    if(!empty($response['rel'])){
                        $sourceData = $trip_detail->{$response['rel'] . 'Source'};
                        $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']}.'-'.$model->remarks;
                    }
                    $trackingDetail = ['visibility_status' => 3, 'module_code' => null, 'module_type' => null];
                    Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $trackingDetail, $remarks);
                    $this->redirect(['index']);
                }
            }
            return $this->render('create', [
                        'model' => $model,
                        'config' => $config_mapping,
                        'config_list' => $config_list
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the TblBmcDispatchInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBmcDispatchInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcDispatchInspection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
