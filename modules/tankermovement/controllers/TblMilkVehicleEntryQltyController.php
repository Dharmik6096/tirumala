<?php

namespace app\modules\tankermovement\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\tankermovement\models\TblMilkVehicleEntryQlty;
use app\modules\tankermovement\models\TblMilkVehicleEntryQltyHistory;
use app\modules\tankermovement\models\TblMilkVehicleEntryQltySearch;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripHistory;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use app\modules\configuration\models\TblConfig;
use app\modules\configuration\models\TblMilkQualityParamRange;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblConfigTxnResultHistory;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;

/**
 * TblMilkVehicleEntryQltyController implements the CRUD actions for TblMilkVehicleEntryQlty model.
 */
class TblMilkVehicleEntryQltyController extends ChildController {

    /**
     * Lists all TblMilkVehicleEntryQlty models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkVehicleEntryQltySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'PLANT_QUALITY_RECEIPT';
        $config->config_type = 'CONTROL';
        $config_list = $config->getControlConfigList();
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Displays a single TblMilkVehicleEntryQlty model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkVehicleEntryQlty model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMilkVehicleEntryQlty();
        $model->load(\Yii::$app->request->get());
        $searchModel = new TblMilkVehicleEntryQltySearch();
        $searchModel->scenario = 'update';
        Yii::$app->default->getDefaults($model);
        $searchModel->load(\Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, FALSE);
        $searchModel->parsing_no = TblVehicleTrip::find()->alias('vt')->joinWith('vehicleCode vm')->where(['vt.trip_code' => $searchModel->trip_code, 'vt.is_active' => 1])->select('vm.parsing_no')->scalar();
        $dataProviderCount = $dataProvider->getCount();
        if ($dataProviderCount > 0) {
            $dataProviderModel = $dataProvider->getModels()[0];
            $model->union_code = $dataProviderModel['union_code'];
            $model->plant_code = $dataProviderModel['plant_code'];
            $model->GetClrInput();
        }
        $trip_code = $searchModel->trip_code;
        $trip_model = $model->getPlant($trip_code);
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'PLANT_QUALITY_RECEIPT';
        $config->config_type = 'CONTROL';
        $config_mapping = new TblConfigTxnResult();
        $config_list = [];
        if ($trip_model && !empty($trip_model['source_org_code'])) {
            $config_list = $config->getOrgConfigList($config->config_for, $trip_model['source_org_code']);
        }
        return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderCount' => $dataProviderCount,
                    'config' => $config_mapping,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Finds the TblMilkVehicleEntryQlty model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkVehicleEntryQlty the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkVehicleEntryQlty::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionQltySubmit() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new TblMilkVehicleEntryQlty();
        $model->scenario = 'qltySubmit';
        $res = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $saveModel = [];
            $deleteModel = [];
            $milkVehicleEntryQltyData = $this->findModel($model->chamber_no);

            $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => (string) $model->chamber_no, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty'])->all();
            foreach ($configTxnData as $key => $configData) {
                $configTxnHistoryModel = new TblConfigTxnResultHistory();
                Yii::$app->operation->history($configData, $configTxnHistoryModel, UPDATE);
                $deleteModel[] = $configData;
                $saveModel[] = $configTxnHistoryModel;
            }

            $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
            $cnt = 1;
            foreach ($config_data as $data) {
                $config_model = new TblConfigTxnResult();
                $config_model->attributes = $milkVehicleEntryQltyData->attributes;
                $config_model->attributes = $data;
                $config_model->config_for = 'PLANT_QUALITY_RECEIPT';
                $config_model->ref_table = 'tbl_milk_vehicle_entry_qlty';
                $config_model->ref_code = $milkVehicleEntryQltyData->milk_vehicle_entry_qlty_code;
                $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                $saveModel[] = $config_model;
                $cnt++;
            }

            $historyModel = new TblMilkVehicleEntryQltyHistory();
            Yii::$app->operation->history($milkVehicleEntryQltyData, $historyModel, UPDATE);
            $saveModel[] = $historyModel;
            foreach (['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity', 'tested_by', 'verified_by', 'sample_datetime', 'record_status'] as $attr) {
                $milkVehicleEntryQltyData->$attr = $model->$attr;
            }
            $milkVehicleEntryQltyData->status = 'done';
            $milkVehicleEntryQltyData->is_qty_only = $milkVehicleEntryQltyData->is_pending_merge = $milkVehicleEntryQltyData->is_approved = 1;
            $milkVehicleEntryQltyData->status_datetime = date('Y-m-d H:i:s');

            $milkVehicleEntryTxnModel = new TblMilkVehicleEntryTransaction();
            $milkVehicleEntryTxnModel->updateMilkVehicleEntryTxnWithHistory($milkVehicleEntryQltyData, $saveModel);

            $saveModel[] = $milkVehicleEntryQltyData;
            $query = $model->find()->where(['!=', 'status', 'discarded'])->andWhere(['trip_code' => $milkVehicleEntryQltyData->trip_code])->andWhere(['not in', 'milk_vehicle_entry_qlty_code', $milkVehicleEntryQltyData->milk_vehicle_entry_qlty_code]);
            $totalCount = $query->count();
            $doneCount = $query->andWhere(['status' => 'done'])->count();
            $vehicleTripData = NULL;
            if ($totalCount == $doneCount) {
                $tripModel = new TblVehicleTrip();
                $vehicleTripData = $tripModel->find()->where(['trip_code' => $milkVehicleEntryQltyData->trip_code, 'trip_status' => ['open', 'tankerfull'], 'is_active' => 1])->one();
                $vehicleTriphistoryModel = new TblVehicleTripHistory();
                Yii::$app->operation->history($vehicleTripData, $vehicleTriphistoryModel, UPDATE);
                $saveModel[] = $vehicleTriphistoryModel;
                $vehicleTripData->trip_sub_status = 'plant_lot_quality_done';
                $vehicleTripData->sub_status_time = date('Y-m-d H:i:s');
                $saveModel[] = $vehicleTripData;
            }

            $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Milk Lot Quality', 'edit']);
            if ($transaction == 'customRedirect') {
                $plantData = $milkVehicleEntryQltyData->plantCode;
                $remarks = '';
                if (!empty($plantData)) {
                    $remarks = $plantData->ref_code . '-' . $plantData->name;
                }
                $trackingDetail = ['visibility_status' => 3, 'module_code' => null, 'module_type' => null];
                Yii::$app->general->setVehicleTripTrackingDetail($vehicleTripData, $trackingDetail, $remarks);
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $res = ['status' => 'success', 'msg' => $msg];
            } else {
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $res = ['status' => 'error', 'msg' => $msg];
            }
        } else {
            $res = ['status' => 'error', 'msg' => $model->getErrors()];
        }

        return Json::encode($res);
    }

    public function actionResetQlty($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'resetQlty';

        $saveModel = [];
        $deleteModel = [];
        $historyModel = new TblMilkVehicleEntryQltyHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $saveModel[] = $historyModel;
        foreach (['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity', 'is_pending_merge', 'is_approved'] as $attribute) {
            $this->model->$attribute = 0;
        }
        $this->model->status = 'pending';
        $this->model->status_datetime = date('Y-m-d H:i:s');
        $this->model->tested_by = $this->model->verified_by = $this->model->sample_datetime = $this->model->record_status = NULL;
        $saveModel[] = $this->model;

        $milkVehicleEntryTxnModel = new TblMilkVehicleEntryTransaction();
        $milkVehicleEntryTxnModel->updateMilkVehicleEntryTxnWithHistory($this->model, $saveModel);

        $vehicleTripData = NULL;
        $pendingCount = $this->model->find()->where(['union_code' => $this->model->union_code, 'trip_code' => $this->model->trip_code, 'status' => 'pending'])->andWhere(['not in', 'chamber_no', $this->model->chamber_no])->count();
        if ($pendingCount == 0) {
            $tripModel = new TblVehicleTrip();
            $vehicleTripData = $tripModel->find()->where(['trip_code' => $this->model->trip_code, 'trip_status' => ['open', 'tankerfull']])->one();
            $vehicleTriphistoryModel = new TblVehicleTripHistory();
            Yii::$app->operation->history($vehicleTripData, $vehicleTriphistoryModel, UPDATE);
            $saveModel[] = $vehicleTriphistoryModel;
            $vehicleTripData->trip_sub_status = 'plant_lot_pending';
            $vehicleTripData->sub_status_time = date('Y-m-d H:i:s');
            $saveModel[] = $vehicleTripData;
        }

        $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => (string) $id, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty'])->all();
        foreach ($configTxnData as $key => $id) {
            $configTxnHistoryModel = new TblConfigTxnResultHistory();
            Yii::$app->operation->history($id, $configTxnHistoryModel, DELETE);
            $deleteModel[] = $id;
            $saveModel[] = $configTxnHistoryModel;
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Milk Lot Quality', 'delete']);
        if ($transaction == 'customRedirect') {
            $plantData = $this->model->plantCode;
            $remarks = '';
            if (!empty($plantData)) {
                $remarks = $plantData->ref_code . '-' . $plantData->name;
            }
            $trackingDetail = ['visibility_status' => 3, 'module_code' => null, 'module_type' => null];
            Yii::$app->general->setVehicleTripTrackingDetail($vehicleTripData, $trackingDetail, $remarks);
            $record = ['status' => 'success', 'msg' => 'Tanker Milk Lot Quality Reset Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Tanker Milk Lot Quality Not Reset.'];
        }

        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (strtolower($model->status) == 'pending') {
            Yii::$app->default->getDefaults($model);
        } else {
            $model->sample_time = date('H:i:s', strtotime($model->sample_datetime));
        }
        $config = new TblConfig();
        $config->config_for = 'PLANT';
        $config->process_name = 'PLANT_QUALITY_RECEIPT';
        $config->config_type = 'CONTROL';
        $config_list = [];
        if ($model && !empty($model->plant_code)) {
            $config_list = $config->getOrgConfigList($config->config_for, $model->plant_code);
            $model->union_code = $model->union_code;
            $model->plant_code = $model->plant_code;
            $model->GetClrInput();
        }
        $config_mapping = new TblConfigTxnResult();
        $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => (string) $id, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty'])->all();
        return $this->render('update', [
                    'model' => $model,
                    'config' => $config_mapping,
                    'config_list' => $config_list,
                    'configTxnData' => $configTxnData
        ]);
    }

    public function actionQltyUpdate() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new TblMilkVehicleEntryQlty();
        $model->milk_vehicle_entry_qlty_code = Yii::$app->request->post()['milk_vehicle_entry_qlty_code'];
        $model->scenario = 'update';
        $res = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $saveModel = [];
            $deleteModel = [];
            $milkVehicleEntryQltyData = $this->findModel($model->milk_vehicle_entry_qlty_code);

            $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => (string) $model->milk_vehicle_entry_qlty_code, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty'])->all();
            foreach ($configTxnData as $key => $data) {
                $configTxnHistoryModel = new TblConfigTxnResultHistory();
                Yii::$app->operation->history($data, $configTxnHistoryModel, UPDATE);
                $deleteModel[] = $data;
                $saveModel[] = $configTxnHistoryModel;
            }
            $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];

            $cnt = 1;
            foreach ($config_data as $data) {
                $config_model = new TblConfigTxnResult();
                $config_model->attributes = $milkVehicleEntryQltyData->attributes;
                $config_model->attributes = $data;
                $config_model->config_for = 'PLANT_QUALITY_RECEIPT';
                $config_model->ref_table = 'tbl_milk_vehicle_entry_qlty';
                $config_model->ref_code = $milkVehicleEntryQltyData->milk_vehicle_entry_qlty_code;
                $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                $saveModel[] = $config_model;
                $cnt++;
            }

            $historyModel = new TblMilkVehicleEntryQltyHistory();
            Yii::$app->operation->history($milkVehicleEntryQltyData, $historyModel, UPDATE);
            $saveModel[] = $historyModel;
            foreach (['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity', 'tested_by', 'verified_by', 'sample_datetime', 'record_status'] as $attr) {
                $milkVehicleEntryQltyData->$attr = $model->$attr;
            }
            $milkVehicleEntryQltyData->status = 'done';
            $milkVehicleEntryQltyData->is_qty_only = $milkVehicleEntryQltyData->is_pending_merge = $milkVehicleEntryQltyData->is_approved = 1;
            $milkVehicleEntryQltyData->status_datetime = date('Y-m-d H:i:s');

            $milkVehicleEntryTxnModel = new TblMilkVehicleEntryTransaction();
            $milkVehicleEntryTxnModel->updateMilkVehicleEntryTxnWithHistory($milkVehicleEntryQltyData, $saveModel);

            $saveModel[] = $milkVehicleEntryQltyData;
            $query = $model->find()->where(['!=', 'status', 'discarded'])->andWhere(['trip_code' => $milkVehicleEntryQltyData->trip_code])->andWhere(['not in', 'milk_vehicle_entry_qlty_code', $milkVehicleEntryQltyData->milk_vehicle_entry_qlty_code]);
            $totalCount = $query->count();
            $doneCount = $query->andWhere(['status' => 'done'])->count();
            $vehicleTripData = NULL;
            if ($totalCount == $doneCount) {
                $tripModel = new TblVehicleTrip();
                $vehicleTripData = $tripModel->find()->where(['trip_code' => $milkVehicleEntryQltyData->trip_code, 'trip_status' => ['open', 'tankerfull'], 'is_active' => 1])->one();
                $vehicleTriphistoryModel = new TblVehicleTripHistory();
                Yii::$app->operation->history($vehicleTripData, $vehicleTriphistoryModel, UPDATE);
                $saveModel[] = $vehicleTriphistoryModel;
                $vehicleTripData->trip_sub_status = 'plant_lot_quality_done';
                $vehicleTripData->sub_status_time = date('Y-m-d H:i:s');
                $saveModel[] = $vehicleTripData;
            }
            $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Milk Lot Quality', 'edit']);
            if ($transaction == 'customRedirect') {
                $plantData = $milkVehicleEntryQltyData->plantCode;
                $remarks = '';
                if (!empty($plantData)) {
                    $remarks = $plantData->ref_code . '-' . $plantData->name;
                }
                $trackingDetail = ['visibility_status' => 3, 'module_code' => null, 'module_type' => null];
                Yii::$app->general->setVehicleTripTrackingDetail($vehicleTripData, $trackingDetail, $remarks);
                return $this->redirect(['index']);
            } else {
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $res = ['status' => 'error', 'msg' => $msg];
            }
        } else {
            $res = ['status' => 'error', 'msg' => $model->getErrors()];
        }

        return Json::encode($res);
    }

    public function actionCalculateClr() {
        $fat = (float) Yii::$app->request->post('fat');
        $snf = (float) Yii::$app->request->post('snf');
        $clr = (float) Yii::$app->request->post('clr');
        $union = Yii::$app->request->post('union_code');
        $org_code = Yii::$app->request->post('plantCode');
        $is_clr_input = Yii::$app->request->post('is_clr_input');
        $chamberNo = Yii::$app->request->post('chamberNo');
        if (($milkVehicleEntryQltyData = TblMilkVehicleEntryQlty::findOne($chamberNo)) !== null) {
            $bmcMilkDispatchData = TblBmcMilkDispatch::find()
                    ->select(['tbl_bmc_milk_dispatch.plant_code', 'tbl_bmc_milk_dispatch.bmc_code'])
                    ->joinWith(['bmcMilkDispatchTxnCode'])
                    ->where(['tbl_bmc_milk_dispatch.union_code' => $union, 'tbl_bmc_milk_dispatch.trip_code' => $milkVehicleEntryQltyData->trip_code, 'tbl_bmc_milk_dispatch_txn.chamber_no' => $milkVehicleEntryQltyData->chamber_no])
                    ->orderBy(['tbl_bmc_milk_dispatch.created_at' => SORT_DESC])
                    ->one();
        } else {
            $bmcMilkDispatchData = [];
        }
        if (!empty($bmcMilkDispatchData)) {
            if (!empty($bmcMilkDispatchData->bmc_code)) {
                $config = 'BMC_DISPATCH_CONFIG';
                $orgType = 'BMC';
                $orgCode = $bmcMilkDispatchData->bmc_code;
            } else {
                $config = 'PLANT_DISPATCH_CONFIG';
                $orgType = 'PLANT';
                $orgCode = $bmcMilkDispatchData->plant_code;
            }
        } else {
            $config = 'PLANT_RECEIPT_CONFIG';
            $orgType = 'PLANT';
            $orgCode = $org_code;
        }

        $result = Yii::$app->general->calculateData($config, $union, $orgCode, $fat, $snf, $clr, $orgType, $is_clr_input);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode(['status' => 'success', 'data' => $result['clr']]);
    }

    public function actionGetQualityParamRange() {
        $model = new TblMilkQualityParamRange();
        $model->union_code = Yii::$app->request->post('union');
        $model->process_name = 'PLANT_MILK_RECEIPT';
        $model->org_type = 'PLANT';
        $model->org_code = Yii::$app->request->post('plantCode');
        $data = $model->getminMaxQualityRange();
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => !empty($data) ? 'success' : 'error', 'data' => !empty($data) ? $data : []]);
    }

    public function actionGetQualityData() {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $config_list = TblConfigTxnResult::find()->select(['config_code', 'config_result'])->where(['ref_code' => (string) Yii::$app->request->post('id'), 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty'])->all();
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        if (!empty($model) || !empty($config_list)) {
            $model->sample_time = !empty($model->sample_datetime) ? date('H:i', strtotime($model->sample_datetime)) : date('H:i');
            $model->sample_datetime = !empty($model->sample_datetime) ? date('d-m-Y', strtotime($model->sample_datetime)) : date('d-m-Y');
            return Json::encode(['status' => 'success', 'data' => ['model' => $model, 'config_list' => $config_list, 'sample_time' => $model->sample_time]]);
        } else {
            return Json::encode(['status' => 'error', 'data' => []]);
        }
    }

}
