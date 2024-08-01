<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblMilkVehicleEntry;
use app\modules\tankermovement\models\TblMilkVehicleEntrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransactionSearch;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use kartik\widgets\ActiveForm;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransactionHistory;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblVehicleTripDetailHistory;
use app\modules\tankermovement\models\TblMilkVehicleEntryHistory;
use yii\data\ArrayDataProvider;
use app\modules\configuration\models\TblConfig;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblConfigTxnResultSearch;
use yii\helpers\ArrayHelper;
use app\modules\clienterp\components\EiplResponse;

/**
 * TblMilkVehicleEntryController implements the CRUD actions for TblMilkVehicleEntry model.
 */
class TblMilkVehicleEntryController extends \app\controllers\ChildController {

    public $freeAccessActions = ['transaction-detail', 'get-trip-code', 'change-trip-code', 'transaction-form', 'view-config'];

    /**
     * Lists all TblMilkVehicleEntry models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkVehicleEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkVehicleEntry model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblMilkVehicleEntryTransactionSearch();
        $searchModel->milk_vehicle_entry_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewConfig($id) {
        $searchModel = new TblConfigTxnResultSearch();
        $searchModel->ref_code = $id;
        $searchModel->config_for = 'PLANT_RECEIPT';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderAjax('config-view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMilkVehicleEntry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkVehicleEntry();
        $searchModel = new TblMilkVehicleEntryTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->viewFile = 'create';
        $modelSave = [];
        $txn_model = new TblMilkVehicleEntryTransaction();
        $bmc_user = (!empty($_SESSION['BMC']) && count(explode(',', $_SESSION['BMC'])) == 1) ? 'BMC' : '';
        $this->setCode($this->model);
        if (Yii::$app->request->post()) {
            $update = FALSE;
            $this->model->load(Yii::$app->request->post());
            $masterPost = Yii::$app->request->post()['TblMilkVehicleEntry'];
            $trPost = Yii::$app->request->post()['TblMilkVehicleEntryTransaction'];

            $this->model->setAttributes($masterPost);
            $txn_model->setAttributes($trPost);
            if (!empty($masterPost['milk_vehicle_entry_code'])) {
                $this->model = $this->findModel($masterPost['milk_vehicle_entry_code']);
                $txn_model->entry_type = Yii::$app->request->post()['entry_type'];
            }
            if ($this->model->validate() && $txn_model->validate()) {
                $this->model->vehicle_entry_date = $this->model->receipt_datetime;
                $this->model->receipt_datetime = date('Y-m-d', strtotime($this->model->receipt_datetime)) . ' ' . \Yii::$app->general->getshift($this->model->receipt_shift_code);
                if (!empty($masterPost['milk_vehicle_entry_code'])) {
                    $this->model->load(Yii::$app->request->post());
                } else {
                    $this->model->originating_org_code = $this->model->union_code;
                    $this->model->milk_vehicle_entry_code = Yii::$app->general->getPrimaryCode($this->model);
                    $this->model->grn_no = Yii::$app->session->get('financialYear') . '/' . $this->model->trip_code . '/1';
                    if (!empty($this->model->trip_code)) {
                        $tripModel = new TblVehicleTrip();
                        $tripModel->trip_code = $this->model->trip_code;
                        $tripExist = $tripModel->getTripData();
                        $tripModel = $tripExist;
                        $tripModel->scenario = 'closetrip';
                        $tripModel->grn_no = $this->model->grn_no;
                        $tripModel->trip_status = 'closed';
                        $modelSave[] = $tripModel;
                        /*  $tripDetailModel = new TblVehicleTripDetail();
                          $last_trip = $tripDetailModel->getLastTrip($this->model->trip_code);
                          $trhistoryModel = new TblVehicleTripDetailHistory();
                          Yii::$app->operation->history($last_trip, $trhistoryModel, UPDATE);
                          $modelSave[] = $trhistoryModel;
                          $tripDetailModel = $last_trip;
                          $tripDetailModel->source_org_code = $last_trip->destination_code;
                          $tripDetailModel->source_org_type = $last_trip->destination_type;
                          $tripDetailModel->destination_code = !empty($this->model->customer_code) ? $this->model->customer_code : $this->model->plant_code;
                          $tripDetailModel->destination_type = !empty($this->model->customer_type) ? $this->model->customer_type : 'PLANT';
                          $modelSave[] = $tripDetailModel; */
                    }
                }
                if (!empty($trPost['milk_vehicle_entry_transaction_code'])) {
                    $txnExist = TblMilkVehicleEntryTransaction::findOne($trPost['milk_vehicle_entry_transaction_code']);
                    $this->model->load(Yii::$app->request->post());
                    $historyModel = new TblMilkVehicleEntryTransactionHistory();
                    Yii::$app->operation->history($txnExist, $historyModel, UPDATE);
                    $modelSave[] = $historyModel;
                    $txn_model = $txnExist;
                    $txn_model->load(Yii::$app->request->post());
                    $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
                    foreach ($config_data as $data) {
                        $config_model = TblConfigTxnResult::find()->where(['ref_code' => $txn_model->milk_vehicle_entry_transaction_code, 'config_code' => $data['config_code'], 'config_for' => 'PLANT_RECEIPT'])->one();
                        if (!empty($config_model)) {
                            $config_model->config_result = $data['config_result'];
                        }
                        $modelSave[] = $config_model;
                    }
                    $update = TRUE;
                } else {
                    $txn_model->milk_vehicle_entry_transaction_code = Yii::$app->general->getTransactionCode($txn_model, $this->model->milk_vehicle_entry_code);
                    $txn_model->milk_vehicle_entry_code = $this->model->milk_vehicle_entry_code;
                    $txn_model->grn_no = $this->model->grn_no;
                    $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
                    $cnt = 1;
                    foreach ($config_data as $data) {
                        $config_model = new TblConfigTxnResult();
                        $config_model->attributes = $txn_model->attributes;
                        $config_model->attributes = $data;
                        $config_model->config_for = 'PLANT_RECEIPT';
                        $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                        $config_model->ref_code = $txn_model->milk_vehicle_entry_transaction_code;
                        $config_model->union_code = $this->model->union_code;
                        $config_model->plant_code = $this->model->plant_code;
                        $modelSave[] = $config_model;
                        $cnt++;
                    }
                }
                $this->model->vehicle_entry_date = !empty($this->model->vehicle_entry_date) ? date('Y-m-d', strtotime($this->model->vehicle_entry_date)) : '';
                $modelSave[] = $this->model;

                $txn_model->vehicle_entry_chamber_date = $this->model->vehicle_entry_date;
                $modelSave[] = $txn_model;
                if ($txn_model->validate()) {
                    $transaction = $this->generalModel->saveTransaction($modelSave, ['Milk Vehicle Entry', ($update) ? 'edit' : 'create']);
                    $key = $this->model->milk_vehicle_entry_code;
                    if ($transaction == 'customRedirect') {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'msg' => $msg, 'milk_vehicle_entry_code' => $key];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg, 'milk_vehicle_entry_code' => $key];
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode(ActiveForm::validate($txn_model));
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model, $txn_model));
            }
        }
        return $this->render('create', [
                    'model' => $this->model, 'txn_model' => $txn_model,
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
                    'bmc_user' => $bmc_user,
        ]);
    }

    public function setCode($model) {
        if (!empty($_SESSION['BMC'])) {
            $bmcs = explode(',', $_SESSION['BMC']);
            if (count($bmcs) == 1) {
                $model->receipt_at = 'BMC';
                $this->model->receipt_at_code = $bmcs[0];
            }
        }
    }

    /**
     * Updates an existing TblMilkVehicleEntry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->milk_vehicle_entry_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMilkVehicleEntry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkVehicleEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMilkVehicleEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkVehicleEntry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDispatchDetail() {
          $existData = TblBmcMilkDispatch::find()
                        ->alias('bmd')
                        ->select(['bmd.challan_no','bmd.from_date','bmd.from_shift_code','bmd.to_date','bmd.to_shift_code','bmd.vehicle_code','bmd.vehicle_in_time','bmd.vehicle_out_time','bmd.bmc_milk_dispatch_code', 'gross_weight' => 'sum(a.dispatch_qty)'])
                        ->join('INNER JOIN', 'tbl_bmc_milk_dispatch_txn a', 'a.bmc_milk_dispatch_code=bmd.bmc_milk_dispatch_code')
                        ->where(['trip_code' => Yii::$app->request->get('trip_code')])
                        ->groupBy(['bmd.challan_no','bmd.from_date','bmd.from_shift_code','bmd.to_date','bmd.to_shift_code','bmd.vehicle_code','bmd.vehicle_in_time','bmd.vehicle_out_time','bmd.bmc_milk_dispatch_code'])
                        ->all();

        return $this->renderAjax('_dispatch_detail', [
                    'existData' => $existData,
        ]);
    }

    public function actionListGrid() {
        $searchModel = new TblMilkVehicleEntryTransactionSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblMilkVehicleEntry'));
        $dataProvider = $searchModel->search([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdateTransaction() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        $config_data = [];
        $sourceName = '';
        $destName = '';
        if (!empty($_POST['milk_vehicle_entry_transaction_code'])) {
            $modelData = TblMilkVehicleEntryTransaction::findOne($_POST['milk_vehicle_entry_transaction_code']);
            if (!empty($modelData)) {
                $model = $modelData;
                if ($model->entry_type == 'INDIVIDUAL') {
                    $sourceName = Yii::$app->general->getforeignkey($model->bmcCodeSource, 'bmc_name');
                    $rel = Yii::$app->general->getDestRelation($model->destination_type);
                    $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');
                    if (!empty($rel)) {
                        $destName = Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
                    }
                }
                $config_data = TblConfigTxnResult::find()->select(['config_code', 'config_result'])->where(['ref_code' => $_POST['milk_vehicle_entry_transaction_code'], 'config_for' => 'PLANT_RECEIPT'])->asArray()->all();
                $config_data = ArrayHelper::map($config_data, 'config_code', 'config_result');
                $data['status'] = 'success';
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData, 'source' => $sourceName, 'dest' => $destName, 'configData' => $config_data]; //$this->renderAjax('_collection', ['model' => $model, 'modelData' => $modelData, 'type' => 'edit']);
    }

    public function actionVehicleDetail() {
        $response = [];
        $response['status'] = 'success';
        $response['data'] = '';
        $trip = Yii::$app->request->get('trip_code');
        $this->model = new TblMilkVehicleEntry();
        $this->model->trip_code = $trip;
        $tripModel = new TblVehicleTrip();
        $tripModel->vehicle_code = Yii::$app->general->getforeignkey($this->model->tripCode, 'vehicle_code');
        $vehicle = Yii::$app->general->getforeignkey($tripModel->vehicleCode, 'parsing_no');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return [$response['status'], 'code' => $tripModel->vehicle_code, 'name' => $vehicle];
    }

    public function actionSetFields() {
        $response = ['status' => 'success', 'data' => ''];
        $destName = $sourceName = '';
        $challan = Yii::$app->request->get('challan_no');
        $trip = Yii::$app->request->get('trip_code');
        $data = TblBmcMilkDispatch::find()->where(['challan_no' => $challan, 'trip_code' => $trip])->one();
        if (!empty($data)) {
            $destinationType = strtolower($data->destination_type);
            $DestRel = Yii::$app->general->getDestRelation($destinationType);
            $DestAtt = ($destinationType == 'bmc') ? 'bmc_name' : (($destinationType == 'party') ? 'party_name' : (($destinationType == 'vendor') ? 'customer_name' : 'name'));
            if (!empty($DestRel)) {
                $destName = Yii::$app->general->getforeignkey($data->{$DestRel . 'Dest'}, $DestAtt);
            }

            $sourceType = strtolower($data->source_org_type);
            $SourceRel = Yii::$app->general->getDestRelation($sourceType);
            $SourceAtt = ($sourceType == 'bmc') ? 'bmc_name' : (($sourceType == 'party') ? 'party_name' : (($sourceType == 'vendor') ? 'customer_name' : 'name'));

            if (!empty($SourceRel)) {
                $sourceName = Yii::$app->general->getforeignkey($data->{$SourceRel . 'Source'}, $SourceAtt);
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return [$response['status'], 'data' => $data, 'source' => $sourceName, 'dest' => $destName];
    }

    public function actionEditTripDetail() {
        $searchModel = new TblMilkVehicleEntrySearch();
        $searchModel->scenario = 'changeTrip';
        $dataProvider = new ArrayDataProvider([
            'allModels' => $searchModel->searchEditTrip(Yii::$app->request->queryParams),
            'pagination' => FALSE
        ]);
        return $this->render('edit_trip', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetTripCode() {
        $id = \Yii::$app->request->post()['milk_vehicle_entry_code'];
        $model = $this->findModel($id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!empty($model)) {
            $trip = new TblVehicleTripDetail();
            $data = $trip->getOpenTripList('receipt', '', $model->vehicle_entry_date, 'alltrip');
            return ['status' => 'success', 'res' => $data];
        }
        return ['status' => 'error', 'res' => []];
    }

    public function actionChangeTripCode() {
        $id = \Yii::$app->request->post()['milk_vehicle_entry_code'];
        $trip_code = \Yii::$app->request->post()['trip_code'];
        $model = $this->findModel($id);
        $msg = '';
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!empty($model)) {
            if ($model->trip_code != $trip_code) {
                $historyModel = new TblMilkVehicleEntryHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
                $model->trip_code = $trip_code;
                $trip_data = $model->tripCodeAll;
                if (!empty($trip_data)) {
                    $model->vehicle_code = $trip_data->vehicle_code;
                    $transaction = $this->generalModel->saveTransaction([$historyModel, $model], ['Trip Code', 'edit']);
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    if ($transaction == 'customRedirect') {
                        return ['status' => 'success', 'parsing_no' => $model->vehicleCode->parsing_no];
                    }
                } else {
                    $msg = 'Invalid Trip Code.';
                }
            } else {
                return ['status' => 'success', 'parsing_no' => $model->vehicleCode->parsing_no];
            }
        } else {
            $msg = 'Receipt Detail Not Found.';
        }
        return ['status' => 'error', 'msg' => $msg];
    }

    public function actionTransactionForm() {
        $union_code = Yii::$app->request->get('union_code');
        $config = new TblConfig();
        $config->config_for = Yii::$app->request->get('receipt_at');
        $config->process_name = 'PLANT_RECEIPT';
        $config->config_type = 'CONTROL';
        $config_mapping = new TblConfigTxnResult();
        $config_list = $config->getOrgConfigList($config->config_for, Yii::$app->request->get('receipt_at_code'));
        $txn_model = new TblMilkVehicleEntryTransaction();
        return $this->renderAjax('_from_transaction', [
                    'txn_model' => $txn_model,
                    'config' => $config_mapping,
                    'config_list' => $config_list,
        ]);
    }

    public function actionAbc(){
        $url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_milk_receipt_url'];
        $milkVehicalData = TblMilkVehicleEntry::find()->where(['approval_status' => 'Approve'])->all();
        if(!empty($milkVehicalData)){
            foreach($milkVehicalData as $milkVehical){
                $httpCode = '';
                $milkVehicalTxn = $milkVehical->getTransactionRecord($milkVehical->milk_vehicle_entry_code);
                $jsonArray = array(
                    'Long_Address_Number_ALKY' => $milkVehical->trip_code,
                    "Branch_Plant" => $milkVehical->plant_code,
                    "Order_Date" => $milkVehical->vehicle_entry_date,
                    "KCOO..Order_Company" => $milkVehical->union_code,
                    "VINV..Invoice_Number" => $milkVehical->grn_no,
                    "GridIn_1_3" => $milkVehicalTxn,
                );
                $data = json_encode($jsonArray);
                $header = array(
                    "Content-Type: application/json", 
                    "Content-length: " . strlen($data),
                    //'Host: <calculated when request is sent>',
                    'User-Agent: PostmanRuntime/7.39.0',
                    'Accept: */*',
                    'Accept-Encoding: gzip, deflate, br',
                    'Connection: keep-alive'
                );
                $requestTimestamp = date('Y-m-d H:i:s');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                if (false) {
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                }  // Skip SSL Verification
                $response = curl_exec($ch);
                if ($response === false) {
                    // echo 'cURL Error: ' . curl_error($ch);
                } else {
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    // echo 'HTTP Response Code: ' . $httpCode;
                }
                curl_close($ch);
                $responseTimestamp = date('Y-m-d H:i:s');
                $this->setLogData($milkVehical, $response, $requestTimestamp, $responseTimestamp, $data, $httpCode);
            }
        }
    }

    public function setLogData($request, $response, $requestTimestamp, $responseTimestamp, $requestJson, $httpCode) {
        $this->response = new EiplResponse();
        $logData = [
            'union_code' => !empty($request['union_code']) ? $request['union_code'] : '',
            'plant_code' => !empty($request['plant_code']) ? $request['plant_code'] : '',
            'mcc_plant_code' => !empty($request['mcc_plant_code']) ? $request['mcc_plant_code'] : '',
            'bmc_code' => !empty($request['bmc_code']) ? $request['bmc_code'] : '',
            'request_desc' => '',
            'txn_type' => 'eipl',
            'date1' => !empty($request['vehicle_entry_date']) ? $request['vehicle_entry_date'] : '',
            // 'date2' => !empty($request['dispatch_date']) ? $request['dispatch_date'] : '',
            'desc1' => !empty($request['trip_code']) ? $request['trip_code'] : '',
            'desc2' => !empty($request['grn_no']) ? $request['grn_no'] : '',
            'status_code' => $httpCode,
            'status_message' => !empty($response->jde__simpleMessage) ? json_encode($response->jde__simpleMessage) : '',
        ];        
        $this->response->logData = $logData;
        $this->response->saveRequestResponseLog($requestJson, $response, $requestTimestamp, $responseTimestamp);
    }
    
}
