<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblMilkVehicleEntryQltyMerge;
use app\modules\tankermovement\models\TblMilkVehicleEntryQltyMergeSearch;
use app\modules\tankermovement\models\TblMilkVehicleEntryQltyMergeHistory;
use yii\web\NotFoundHttpException;
use app\modules\configuration\models\TblConfig;
use app\modules\configuration\models\TblMilkQualityParamRange;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblConfigTxnResultHistory;
use app\modules\tankermovement\models\TblVehicleTrip;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMilkVehicleEntryQltyMergeController implements the CRUD actions for TblMilkVehicleEntryQltyMerge model.
 */
class TblMilkVehicleEntryQltyMergeController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkVehicleEntryQltyMerge models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkVehicleEntryQltyMergeSearch();
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
     * Displays a single TblMilkVehicleEntryQltyMerge model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkVehicleEntryQltyMerge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMilkVehicleEntryQltyMerge();
        $model->load(\Yii::$app->request->get());
        $searchModel = new TblMilkVehicleEntryQltyMergeSearch();
        $searchModel->scenario = 'update';
        $searchModel->load(\Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, FALSE);
        $searchModel->parsing_no = TblVehicleTrip::find()->alias('vt')->joinWith('vehicleCode vm')->where(['vt.trip_code' => $searchModel->trip_code, 'vt.is_active' => 1])->select('vm.parsing_no')->scalar();
        $dataProviderCount = $dataProvider->getCount();
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
            $model->union_code = $searchModel->union_code;
            $model->plant_code = $trip_model['source_org_code'];
            $model->GetClrInput();
        }
        return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderCount' => $dataProviderCount,
                    'config' => $config_mapping,
                    'config_list' => $config_list,
                    'trip_model' => $trip_model
        ]);
    }

    /**
     * Finds the TblMilkVehicleEntryQltyMerge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkVehicleEntryQltyMerge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkVehicleEntryQltyMerge::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionQltySubmit() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new TblMilkVehicleEntryQltyMerge();
        $res = [];
        $saveModel = [];
        $auto_key_config = [];
        $postData = Yii::$app->request->post();
        $model->vehicle_code = $postData['vehicle'];
        $model->plant_code = $postData['plant'];
        $model->trip_code = $postData['trip'];
        $model->union_code = $postData['union'];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->x_col1 = Yii::$app->general->getUuid();
            $saveModel[] = $model;
            $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
            $cnt = 1;
            foreach ($config_data as $data) {
                $config_model = new TblConfigTxnResult();
                $config_model->attributes = $model->attributes;
                $config_model->attributes = $data;
                $config_model->config_for = 'PLANT_QUALITY_RECEIPT';
                $config_model->ref_table = 'tbl_milk_vehicle_entry_qlty_merge';
                $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                $saveModel[] = $config_model;
                $auto_key_config['TblConfigTxnResult'][] = ['self_key' => 'ref_code', 'parent_key' => 'milk_vehicle_entry_qlty_merge_code', 'parent_index' => 0];
                $cnt++;
            }
            $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Tanker Milk Quality', 'create'], $auto_key_config);
            if ($transaction == 'customRedirect') {
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
        $saveModel = [];
        $deleteModel = [];
        $historyModel = new TblMilkVehicleEntryQltyMergeHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $configTxnData = TblConfigTxnResult::find()->where(['ref_code' => (string) $id, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty_merge'])->all();
        foreach ($configTxnData as $key => $id) {
            $configTxnHistoryModel = new TblConfigTxnResultHistory();
            Yii::$app->operation->history($id, $configTxnHistoryModel, DELETE);
            $deleteModel[] = $id;
            $saveModel[] = $configTxnHistoryModel;
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Milk Quality', 'delete']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Tanker Milk Quality Delete Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Tanker Milk Quality Not Delete.'];
        }

        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionCalculateClr() {
        $fat = (float) Yii::$app->request->post('fat');
        $snf = (float) Yii::$app->request->post('snf');
        $clr = (float) Yii::$app->request->post('clr');
        $union = Yii::$app->request->post('union_code');
        $org_code = Yii::$app->request->post('plantCode');
        $is_clr_input = Yii::$app->request->post('is_clr_input');
        $chamberNo = Yii::$app->request->post('chamberNo');

        if (($milkVehicleEntryQltyData = TblMilkVehicleEntryQltyMerge::findOne($chamberNo)) !== null) {
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

}
