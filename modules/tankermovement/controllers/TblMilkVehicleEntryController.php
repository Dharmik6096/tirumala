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
use kartik\widgets\ActiveForm;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransactionHistory;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblMilkVehicleEntryHistory;
use yii\data\ArrayDataProvider;
use app\modules\configuration\models\TblConfig;
use app\modules\configuration\models\TblMilkQualityParamRange;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblConfigTxnResultSearch;
use yii\helpers\ArrayHelper;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\general\models\TblProcessApprovalSearch;
use app\modules\tankermovement\models\TblMilkVehicleEntryQlty;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransactionReject;
use app\modules\tankermovement\models\TblMilkVehicleEntryReject;

/**
 * TblMilkVehicleEntryController implements the CRUD actions for TblMilkVehicleEntry model.
 */
class TblMilkVehicleEntryController extends \app\controllers\ChildController {

    public $freeAccessActions = ['transaction-detail', 'get-trip-code', 'change-trip-code', 'transaction-form', 'view-config', 'get-clr-input', 'calculate-clr', 'get-quality-param-range'];

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
        $searchModel->scenario = 'view';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $approvalModel = new TblProcessApprovalSearch();
        $approvalModel->process_code = $id;
        $approvalDataProvider = $approvalModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'approvalModel' => $approvalModel,
                    'approvalDataProvider' => $approvalDataProvider,
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
        $txn_model->gross_weight_time = date('H:i');
        $txn_model->tare_weight_time = date('H:i');
        $this->model->arrival_time = date('H:i');
        $this->model->receipt_shift_code = (date('G') < 12) ? 1 : 2;
        $this->model->receipt_datetime = date('Y-m-d');
        $bmc_user = (!empty($_SESSION['BMC']) && count(explode(',', $_SESSION['BMC'])) == 1) ? 'BMC' : '';
        $this->setCode($this->model);
        if (Yii::$app->request->post()) {
            $update = FALSE;
            $tripModel = NULL;
            $this->model->load(Yii::$app->request->post());
            $masterPost = Yii::$app->request->post()['TblMilkVehicleEntry'];
            $trPost = Yii::$app->request->post()['TblMilkVehicleEntryTransaction'];

            $this->model->setAttributes($masterPost);
            $txn_model->setAttributes($trPost);
            if (!empty($masterPost['milk_vehicle_entry_code'])) {
                $this->model = $this->findModel($masterPost['milk_vehicle_entry_code']);
                $txn_model->entry_type = Yii::$app->request->post()['entry_type'];
            }
            $txn_model->trip_code = $this->model->trip_code;
            $txn_model->union_code = $this->model->union_code;
            $txn_model->receipt_at = $this->model->receipt_at;
            $txn_model->receipt_at_code = $this->model->receipt_at_code;
            if ($this->model->validate() && $txn_model->validate()) {
                $this->model->vehicle_entry_date = $this->model->receipt_datetime;
                $this->model->receipt_datetime = date('Y-m-d', strtotime($this->model->receipt_datetime)) . ' ' . \Yii::$app->general->getshift($this->model->receipt_shift_code);
                if (!empty($masterPost['milk_vehicle_entry_code'])) {
                    $this->model->load(Yii::$app->request->post());
                } else {
                    $this->model->x_col1 = Yii::$app->general->getUuid();
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
                        $tripModel->trip_sub_status = 'cleaning_pending';
                        $tripModel->sub_status_time = date('Y-m-d H:i:s');
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
                    $txn_model->x_col1 = Yii::$app->general->getUuid();
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
                if (empty($masterPost['milk_vehicle_entry_code'])) {
                    $this->model->gross_weight = $txn_model->gross_weight;
                }
                $this->model->tare_weight = $txn_model->tare_weight;
                $this->model->tare_weight_time = $txn_model->tare_weight_time;
                $this->model->qty = number_format((float) $this->model->gross_weight - (float) $this->model->tare_weight, 2, '.', '');
                $txn_model->tare_weight_time = date('Y-m-d') . ' ' . $txn_model->tare_weight_time;
                $txn_model->gross_weight_time = date('Y-m-d') . ' ' . $txn_model->gross_weight_time;
                if (empty($txn_model->destination_code) || empty($txn_model->destination_type) || empty($txn_model->source_org_code) || empty($txn_model->source_org_type)) {
                    $txn_model->setData($this->model);
                }
                $modelSave[] = $this->model;

                $txn_model->vehicle_entry_chamber_date = $this->model->vehicle_entry_date;
                $modelSave[] = $txn_model;
                if ($txn_model->validate()) {
                    $modelStages = new TblApprovalStagesDetail();
                    $modelStages->setApprovalData($this->model->union_code, 'tbl_milk_vehicle_entry', $this->model->milk_vehicle_entry_code, $modelSave, $approval_stages);
                    $this->model->approval_status = 'Pending';
                    $transaction = $this->generalModel->saveTransaction($modelSave, ['Milk Vehicle Entry', ($update) ? 'edit' : 'create']);
                    $key = $this->model->milk_vehicle_entry_code;
                    if ($transaction == 'customRedirect') {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'msg' => $msg, 'milk_vehicle_entry_code' => $key, 'gross_weight' => $this->model->gross_weight, 'tare_weight' => $this->model->tare_weight, 'tare_weight_time' => $this->model->tare_weight_time];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg, 'milk_vehicle_entry_code' => $key, 'gross_weight' => $this->model->gross_weight, 'tare_weight' => $this->model->tare_weight, 'tare_weight_time' => $this->model->tare_weight_time];
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
        if (!empty($_SESSION['UserType'])) {
            if (($_SESSION['UserType'] == 6) && !empty($_SESSION['BMC'])) {
                $bmcs = explode(',', $_SESSION['BMC']);
                if (count($bmcs) == 1) {
                    $model->receipt_at = 'BMC';
                    $this->model->receipt_at_code = $bmcs[0];
                }
            } else if (($_SESSION['UserType'] == 4) && !empty($_SESSION['Plant'])) {
                $plants = explode(',', $_SESSION['Plant']);
                if (count($plants) == 1) {
                    $model->receipt_at = 'PLANT';
                    $model->receipt_at_code = $plants[0];
                }
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
                ->select(['bmd.challan_no', 'bmd.from_date', 'bmd.from_shift_code', 'bmd.to_date', 'bmd.to_shift_code', 'bmd.vehicle_code', 'bmd.vehicle_in_time', 'bmd.vehicle_out_time', 'bmd.bmc_milk_dispatch_code', 'gross_weight' => 'sum(a.dispatch_qty)', 'a.fat', 'a.snf', 's.shift as from_shift', 'ts.shift as to_shift', 'vm.parsing_no'])
                ->join('INNER JOIN', 'tbl_bmc_milk_dispatch_txn a', 'a.bmc_milk_dispatch_code=bmd.bmc_milk_dispatch_code')
                ->join('LEFT JOIN', 'tbl_shift s', 's.id=bmd.from_shift_code')
                ->join('LEFT JOIN', 'tbl_shift ts', 'ts.id=bmd.to_shift_code')
                ->join('LEFT JOIN', 'tbl_vehicle_master vm', 'vm.vehicle_code=bmd.vehicle_code')
                ->where(['trip_code' => Yii::$app->request->get('trip_code')])
                ->groupBy(['bmd.challan_no', 'bmd.from_date', 'bmd.from_shift_code', 'bmd.to_date', 'bmd.to_shift_code', 'bmd.vehicle_code', 'bmd.vehicle_in_time', 'bmd.vehicle_out_time', 'bmd.bmc_milk_dispatch_code', 'a.fat', 'a.snf', 's.shift', 'ts.shift', 'vm.parsing_no'])
                ->asArray()
                ->all();

        return $this->renderAjax('_dispatch_detail', [
                    'existData' => $existData,
        ]);
    }

    public function actionListGrid() {
        $searchModel = new TblMilkVehicleEntryTransactionSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblMilkVehicleEntry'));
        $searchModel->scenario = 'view';
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
                $modelData->gross_weight_time = date('H:i', strtotime($modelData->gross_weight_time));
                $modelData->tare_weight_time = date('H:i', strtotime($modelData->tare_weight_time));
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

    public function actionBulkApproval() {
        $milkVehicleEntryModel = new TblMilkVehicleEntry();
        $searchModel = new TblMilkVehicleEntrySearch();
        $dataProvider = $searchModel->approvalSearch(Yii::$app->request->queryParams, TRUE);

        $selection = Yii::$app->request->post('selection');
        if (Yii::$app->request->post() && !empty($selection)) {
            $succCount = 0;
            $errorCount = 0;
            $receipt_error = '';
            $message = '';
            $milkVehiclepostData = Yii::$app->request->post()['TblMilkVehicleEntry'];
            $remarks = Yii::$app->request->post()['remarks'];
            $postData = Yii::$app->request->post();
            $operation = $postData['operation'];
            foreach ($selection as $value) {
                $model = TblProcessApproval::findOne($value);
                $model->scenario = 'approve';
                $saveModel = [];
                $deleteModel = [];
                $approvalHistoryModel = new TblProcessApprovalHistory();
                Yii::$app->operation->history($model, $approvalHistoryModel, 'UPDATE');
                $saveModel[] = $approvalHistoryModel;
                $isApprove = $operation == 'approve';
                $model->status = $isApprove ? 1 : 2;
                $historyFlag = $isApprove ? 'UPDATE' : 'DELETE';
                $model->remarks = $remarks . $milkVehiclepostData[$value]['approval_remarks'];
                $saveModel[] = $model;
                if (!empty($saveModel)) {
                    if ($operation != 'reject') {
                        $model->ApprovalList($model, $saveModel, $status);
                    } else {
                        $milkVehicleEntryReject = new TblMilkVehicleEntryReject();
                        $milkVehicleEntryReject->milk_vehicle_entry_reject_code = Yii::$app->general->getCodeAutoIncrement($milkVehicleEntryReject);
                        $model->RejectList($model, $saveModel, $status, $milkVehicleEntryReject->milk_vehicle_entry_reject_code);
                    }
                    $receiptModel = $this->findModel($model->process_code);
                    $historyModel = new TblMilkVehicleEntryHistory();
                    Yii::$app->operation->history($receiptModel, $historyModel, $historyFlag);
                    $saveModel[] = $historyModel;
                    $receiptModel->approval_status = $status;
                    $receiptModel->approval_remarks = $remarks . $milkVehiclepostData[$value]['approval_remarks'];
                    $saveModel[] = $receiptModel;

                    if ($receiptModel->validate()) {
                        if (strtolower($status) == 'approve' && strtolower($receiptModel->approval_status) == 'approve') {
                            $receiptModel->approved_at = date('Y-m-d H:i:s');
                            $receiptModel->approved_by = Yii::$app->session['UserCode'];
                            $saveModel[] = $receiptModel;
                        } else if (strtolower($status) == 'reject' && strtolower($receiptModel->approval_status) == 'reject') {
                            $milkVehicleEntryReject->setAttributes($receiptModel->attributes);
                            $saveModel[] = $milkVehicleEntryReject;

                            $milkVehicleEntryTransactionData = TblMilkVehicleEntryTransaction::find()
                                    ->where(['milk_vehicle_entry_code' => $model->process_code])
                                    ->all();

                            foreach ($milkVehicleEntryTransactionData as $transaction) {
                                $milkVehicleEntryTransactionReject = new TblMilkVehicleEntryTransactionReject();
                                $milkVehicleEntryTransactionReject->setAttributes($transaction->attributes);
                                $saveModel[] = $milkVehicleEntryTransactionReject;

                                $transactionHistoryModel = new TblMilkVehicleEntryTransactionHistory();
                                Yii::$app->operation->history($transaction, $transactionHistoryModel, $historyFlag);
                                $saveModel[] = $historyModel;

                                $deleteModel[] = $transaction;
                            }
                            $tripModel = new TblVehicleTrip();
                            $tripModel->trip_code = $receiptModel->trip_code;
                            $tripModel = $tripModel->getClosedtripData();
                            $tripModel->scenario = 'closetrip';
                            $tripModel->grn_no = NULL;
                            $tripModel->trip_status = 'open';
                            $saveModel[] = $tripModel;
                            $deleteModel[] = $receiptModel;
                        }
                        $succCount++;
                    } else {
                        $errorCount++;
                        foreach ($receiptModel->getErrors() as $errorkey => $value) {
                            $message = $value;
                        }
                    }
                    if (!empty($message)) {
                        foreach ($message as $msg) {
                            $receipt_error .= $msg;
                        }
                    }
                    if (empty($receipt_error)) {
                        $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Milk Receipt Approval', 'edit']);
                    } else {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $receipt_error . ' in Milk Receipt']);
                        return $this->redirect(['bulk-approval']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Milk Receipt already approved by other user.']);
                    return $this->redirect(['bulk-approval']);
                }
            }
            $msg = $operation == 'approve' ? ('Milk Receipt approved successfully. <br /> Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount) : ('Provisional Member rejected successfully.  <br />Rejected count : ' . $succCount . '<br />Not Rejected count : ' . $errorCount);
            Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => $msg]);
            $getData = Yii::$app->request->queryParams;
            if (!empty($getData['TblMilkVehicleEntrySearch'])) {
                return $this->redirect(['bulk-approval', 'TblMilkVehicleEntrySearch' => $getData['TblMilkVehicleEntrySearch']]);
            } else {
                return $this->redirect(['bulk-approval']);
            }
        }
        return $this->render('bulk_approve', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'milkVehicleEntryModel' => $milkVehicleEntryModel,
        ]);
    }

    public function actionTripSubStatus() {
        $milkVehicleEntryQlty = new TblMilkVehicleEntryQlty();
        $milkVehicleEntryQlty->union_code = \Yii::$app->request->post()['union_code'];
        $milkVehicleEntryQlty->trip_code = \Yii::$app->request->post()['trip_code'];
        $milkVehicleEntryQlty->plant_code = \Yii::$app->request->post()['receipt_at_code'];
        $milkVehicleEntryQltyData = $milkVehicleEntryQlty->getMilkVehicleEntryQlty();
        if ($milkVehicleEntryQltyData['success']) {
            $response = ['status' => 'success', 'record_data' => $milkVehicleEntryQltyData['record_data'], 'lotQltyData' => $milkVehicleEntryQltyData['lotQltyData']];
        } else if ($milkVehicleEntryQltyData['validation']) {
            $response = ['status' => 'error', 'msg' => 'Quality not Done or exceeded time limit for selected trip.', 'validation' => TRUE];
        } else {
            $response = ['status' => 'error', 'validation' => FALSE];
        }
        return Json::encode($response);
    }

    public function actionVehicleTripDetail() {
        $vehicleTripDetailModel = new TblVehicleTripDetail();
        $vehicleTripDetailModel->trip_code = $_REQUEST['trip_code'];
        $tripDetailData = $vehicleTripDetailModel->getTripData();
        if (!empty($tripDetailData)) {
            $record = ['status' => 'success', 'data' => $tripDetailData];
        } else {
            $record = ['status' => 'error', 'data' => []];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionGetClrInput() {
        $unionCode = Yii::$app->request->post('union_code');
        $receiptAtCode = Yii::$app->request->post('receiptAtCode');
        $isClrInput = Yii::$app->general->getCheckBmcConfiguration($unionCode, 'is_clr_input', $receiptAtCode, 'PLANT', 'PLANT_RECEIPT_CONFIG');
        if ($isClrInput == '') {
            $isClrInput = Yii::$app->general->getUnionConfiguration($unionCode, 'is_clr_input', 'PORTAL');
        }
        $response = ['status' => 'success', 'data' => ($isClrInput != '') ? $isClrInput : 0];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionCalculateClr() {
        $fat = (float) Yii::$app->request->post('fat');
        $snf = (float) Yii::$app->request->post('snf');
        $clr = (float) Yii::$app->request->post('clr');
        $union = Yii::$app->request->post('union_code');
        $org_code = Yii::$app->request->post('receiptAtCode');
        $is_clr_input = Yii::$app->request->post('is_clr_input');

        $result = Yii::$app->general->calculateData('PLANT_RECEIPT_CONFIG', $union, $org_code, $fat, $snf, $clr, 'PLANT', $is_clr_input);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode(['status' => 'success', 'data' => $result['clr']]);
    }

    public function actionGetQualityParamRange() {
        $model = new TblMilkQualityParamRange();
        $model->union_code = Yii::$app->request->post('union');
        $model->process_name = 'PLANT_MILK_RECEIPT';
        $model->org_type = 'PLANT';
        $model->org_code = Yii::$app->request->post('receiptAtCode');
        $model->animal_type_code = Yii::$app->request->post('milkTypeCode');
        $data = $model->getQualityRange();
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => !empty($data) ? 'success' : 'error', 'data' => !empty($data) ? $data : []]);
    }

}
