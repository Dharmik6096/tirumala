<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblBmcMilkDispatchSearch;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\configuration\models\TblConfig;
use yii\helpers\Json;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblCustomerMaster;
use yii\web\Response;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxnSearch;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblConfigTxnResultSearch;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblBmcMilkDispatchHistory;
use yii\data\ArrayDataProvider;
use app\modules\tankermovement\models\TblBmcDispatchInspection;
use app\modules\tankermovement\models\TblPartyMaster;
use app\components\ActiveForm;
use app\modules\configuration\models\TblMilkQualityParamRange;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxnHistory;
use app\modules\tankermovement\models\TblConfigTxnResultHistory;
use app\modules\transporter\models\TblVehicleCompartmentDetail;
use yii\helpers\ArrayHelper;

/**
 * TblBmcMilkDispatchController implements the CRUD actions for TblBmcMilkDispatch model.
 */
class TblBmcMilkDispatchController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-date-purchase-details', 'transaction-form', 'transaction-detail', 'destination-code-list', 'check-trip', 'view-config', 'get-trip-code', 'change-trip-code', 'calculate-clr', 'get-clr-input', 'get-quality-param-range', 'total-vehicle-capacity'];

    /**
     * Lists all TblBmcMilkDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcMilkDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcMilkDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblBmcMilkDispatchTxnSearch();
        $searchModel->bmc_milk_dispatch_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewConfig($id) {
        $searchModel = new TblConfigTxnResultSearch();
        $searchModel->ref_code = $id;
        $searchModel->config_for = ['BMC_DISPATCH', 'PLANT_DISPATCH'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderAjax('config-view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblBmcMilkDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = '', $tripGenerateBtn = FALSE, $txnEdit = FALSE) {
        $model = new TblBmcMilkDispatch();
        if ($id != '') {
            $model = $this->findModel($id);
        } else {
            $model->scenario = 'create';
            $model->transaction_date = date('Y-m-d');
        }
        $txn_model = new TblBmcMilkDispatchTxn();
        if ($model->load(Yii::$app->request->post()) && $txn_model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->from_date = date('Y-m-d', strtotime($model->from_date)) . ' ' . \Yii::$app->general->getshift($model->from_shift_code);
            $model->to_date = date('Y-m-d', strtotime($model->to_date)) . ' ' . \Yii::$app->general->getshift($model->to_shift_code);
            $model->transaction_date = date('Y-m-d', strtotime($model->transaction_date));
            $model->source_org_code = $model->bmc_code;
            $model->source_org_type = 'bmc';
            $txn_model->from_datetime = $model->from_date;
            $txn_model->to_datetime = $model->to_date;
            $txn_model->bmc_code = $model->bmc_code;
            $txn_model->union_code = $model->union_code;
            $txn_model->bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
            $txn_model->trip_code = $model->trip_code;
            $txn_model->vehicle_code = $model->vehicle_code;
            $type = 'create';
            if ($txnEdit) {
                $txn_model->scenario = 'update';
                $type = 'edit';
            } else {
                $txn_model->scenario = 'create';
            }
            $validation = TRUE;
            if ($txn_model->validate()) {
                $saveModel = [];
                $deleteModel = [];
                $new_rec = FALSE;

                if (empty($model->bmc_milk_dispatch_code)) {
                    $new_rec = TRUE;
                    $model->originating_org_code = $model->union_code;
                    $model->bmc_milk_dispatch_code = Yii::$app->general->getUuid();
                    $model->challan_no = $model->getChallanNo(); //$model->trip_code . '/' . $model->bmc_code . '/1';
                    $model->vehicle_in_time = $model->transaction_date . ' ' . $model->vehicle_in_time;
                    $model->vehicle_out_time = $model->transaction_date . ' ' . $model->vehicle_out_time;
                    $model->x_col1 = Yii::$app->general->getUuid();
                    $tripModel = new TblVehicleTrip();
                    $tripModel->trip_code = $model->trip_code;
                    $tripModel = $tripModel->getTripData();
                    if (!empty($tripModel)) {
                        $tripModel->trip_status = $model->is_last_destination == 1 ? 'tankerfull' : 'open';
                        $model->driver_name = $tripModel->driver_name;
                        $model->driver_contact_no = $tripModel->mobile_no;
                        $saveModel[] = $tripModel;
                        $tripModel->addTripRoute($saveModel, $deleteModel, $model->challan_no, 'bmc', $model->bmc_code, $model->destination_type, $model->destination_code, $validation, $model->is_last_destination);
                        if (!$validation) {
                            $model->bmc_milk_dispatch_code = '';
                            $model->addError('destination_code', "Conversion party not mapped with plant");
                        }
                    }
                    $saveModel[] = $model;
                }
                $txn_model->attributes = $model->attributes;
                if (!$txnEdit) {
                    $txn_model->test_report_no = $txn_model->generateTestReportNo();
                    $txn_model->x_col1 = Yii::$app->general->getUuid();
                    $txn_model->bmc_milk_dispatch_txn_code = Yii::$app->general->getTransactionCode($txn_model, $model->bmc_milk_dispatch_code);
                }
                $txn_model->qty_mode = Yii::$app->general->getUnionConfiguration($txn_model->union_code, 'dispatch_qty_mode', 'BMC');
                $conversion_const = Yii::$app->general->getUnionConfiguration($txn_model->union_code, 'ltr_to_kg_constant', 'BMC');
                $txn_model->converted_qty_mode = $txn_model->qty_mode == 1 ? 0 : 1;
                $txn_model->converted_qty = $txn_model->qty_mode == 1 ? $txn_model->dispatch_qty / $conversion_const : $txn_model->dispatch_qty * $conversion_const;

                $stock_model = new TblBmcDispatchStock();
                $stock_model->attributes = $txn_model->attributes;
                $stock_model->to_date = $model->to_date;
                $stock_model->to_shift_code = $model->to_shift_code;
                $stock_model->from_date = $model->from_date;
                $stock_model->from_shift_code = $model->from_shift_code;
                if (!empty($txn_model->from_date_tr)) {
                    $stock_model->from_date_tr = date('Y-m-d H:i:s', strtotime($txn_model->from_date_tr));
                }
                $stock_model->transaction_date = $model->transaction_date;
                $stock_model->closing_bal = $txn_model->dispatch_qty;
                $stock_data = $stock_model->getStockEntry();
                if (!empty($stock_data) && strtolower($stock_data->type) == 'dispatch') {
                    $stock_model = $stock_data;
                    $stock_model->qty_diff = $txn_model->qty_diff;
                    $stock_model->qty_diff_type_code = $txn_model->qty_diff_type_code;
                    $stock_model->balance_qty = $txn_model->balance_qty;
                    if ($txnEdit) {
                        $stock_model->closing_bal = ($stock_data->closing_bal + $txn_model->dispatch_qty) - $txn_model->original_dispatch_qty;
                    } else {
                        $stock_model->closing_bal = $stock_data->closing_bal + $txn_model->dispatch_qty;
                    }
                } else {
                    $stock_model->x_col1 = Yii::$app->general->getUuid();
                    $stock_model->bmc_dispatch_stock_code = Yii::$app->general->getPrimaryCode($stock_model);
                    $stock_model->purchase_qty = $txn_model->purchase_qty;
                    $stock_model->opening_bal = $txn_model->opening_bal;
                }

                $bmcMilkDispatchTxnData = [];
                if ($txnEdit) {
                    $bmcMilkDispatchTxnData = TblBmcMilkDispatchTxn::findOne($txn_model->bmc_milk_dispatch_txn_code);
                    $txnHistoryModel = new TblBmcMilkDispatchTxnHistory();
                    Yii::$app->operation->history($bmcMilkDispatchTxnData, $txnHistoryModel, UPDATE);
                    $saveModel[] = $txnHistoryModel;
                    $bmcMilkDispatchTxnData->attributes = $txn_model->attributes;
                    $saveModel[] = $bmcMilkDispatchTxnData;
                    $configTxnDeleteData = TblConfigTxnResult::find()->where(['ref_code' => (string) $txn_model->bmc_milk_dispatch_txn_code, 'config_for' => 'BMC_DISPATCH', 'union_code' => $bmcMilkDispatchTxnData->union_code, 'bmc_code' => $bmcMilkDispatchTxnData->bmc_code])->all();
                    foreach ($configTxnDeleteData as $key => $id) {
                        $configTxnHistoryModel = new TblConfigTxnResultHistory();
                        Yii::$app->operation->history($id, $configTxnHistoryModel, DELETE);
                        $deleteModel[] = $id;
                        $saveModel[] = $configTxnHistoryModel;
                    }
                } else {
                    $saveModel[] = $txn_model;
                }
                $saveModel[] = $stock_model;
                $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
                $cnt = 1;
                foreach ($config_data as $data) {
                    $config_model = new TblConfigTxnResult();
                    $config_model->attributes = $txn_model->attributes;
                    $config_model->attributes = $data;
                    $config_model->config_for = 'BMC_DISPATCH';
                    $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                    $config_model->ref_code = $txn_model->bmc_milk_dispatch_txn_code;
                    $config_detail = $config_model->configCode;
                    $auto_reject = isset(Yii::$app->session->get('unionConfig')[$model->union_code]['bmc_dispatch_auto_reject']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['bmc_dispatch_auto_reject'] : '0';
                    if ($auto_reject == '1' && $config_detail->is_adulteration == 1 && $config_detail->check_value != '') {
                        if (in_array($config_detail->control_type, ['RADIO', 'DROPDOWN']) && (int) $config_detail->check_value != (int) $config_model->config_result) {
                            $txn_model->is_rejected = 1;
                        } else if ($config_detail->control_type == 'TEXT' && (int) $config_model->config_result > (int) $config_detail->check_value) {
                            $txn_model->is_rejected = 1;
                        }
                    }
                    $saveModel[] = $config_model;
                    $cnt++;
                }
                if ($validation) {
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['BMC Milk Dispatch', $type]);
                    if ($transaction != 'customRedirect' && $new_rec) {
                        $model->bmc_milk_dispatch_code = '';
                    } else if ($transaction == 'customRedirect') {
                        return $this->redirect(['create', 'id' => $model->bmc_milk_dispatch_code, 'txnEdit' => $txnEdit]);
                    }
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'txn_model' => $txn_model,
                    'tripGenerateBtn' => $tripGenerateBtn,
                    'txnEdit' => $txnEdit,
        ]);
    }

    public function actionGenerateAutoTrip() {
        if (Yii::$app->request->get()) {
            $data = Yii::$app->request->get();
            if (!empty($data['bmcValue'])) {
                $config_text = 'BMC';
                $bmc_plant_value = $data['bmcValue'];
            } else {
                $config_text = 'PLANT';
                $bmc_plant_value = $data['plantValue'];
            }
            $bmcDispatchInspectionModel = new TblBmcDispatchInspection();
            $config = new TblConfig();
            $config->config_for = $config_text;
            $config->process_name = 'BMC_DISPATCH_INSPECTION';
            $config->config_type = 'CONTROL';
            $config_mapping = new TblConfigTxnResult();
            $config_list = $config->getOrgConfigList($config->config_for, $bmc_plant_value);
            return $this->renderAjax('trip-auto-generate', [
                        'data' => $data,
                        'bmcDispatchInspectionModel' => $bmcDispatchInspectionModel,
                        'config' => $config_mapping,
                        'config_list' => $config_list
            ]);
        }

        if (Yii::$app->request->post()) {
            $data = !empty(Yii::$app->request->post()['TblBmcDispatchInspection']) ? Yii::$app->request->post()['TblBmcDispatchInspection'] : [];
            $configData = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
            $this->model = new TblVehicleTrip();
            $this->model->scenario = 'autogeneratetrip';
            $this->model->transaction_date = date('Y-m-d', strtotime($data['inspection_date']));
            $this->model->vehicle_code = $data['vehicle_code'];
            $this->model->union_code = $data['union_code'];
            $this->model->plant_code = $data['plant_code'];
            if (!empty($data['bmc_code']) && !empty($data['mcc_plant_code'])) {
                $this->model->bmc_code = $data['bmc_code'];
                $this->model->mcc_plant_code = $data['mcc_plant_code'];
            } else {
                $this->model->fl_code = $this->model->plant_code;
                $this->model->fl_type = 'plant';
                $this->model->bmc_code = NULL;
                $this->model->mcc_plant_code = NULL;
            }
            $this->model->trip_mode = 'offline';
            $this->model->trip_status = 'generated';
            $this->model->trip_for = 'bmcdispatch';
            $this->model->is_active = '1';
            $this->model->generateAutoTrip = TRUE;
            $result = $this->model->setModel();
            $save_model = $result[1];

            $dispatch_inspection = new TblBmcDispatchInspection();
            if (empty($data['bmc_code'])) {
                $dispatch_inspection->scenario = 'generate_auto_trip';
            }
            $dispatch_inspection->attributes = $this->model->attributes;
            $dispatch_inspection->inspection_date = $this->model->transaction_date;
            $dispatch_inspection->shift_code = $data['shift_code'];
            $dispatch_inspection->remarks = $data['remarks'];
            $dispatch_inspection->bmc_dispatch_inspection_code = Yii::$app->general->getPrimaryCode($dispatch_inspection);
            $save_model[] = $dispatch_inspection;

            $cnt = 1;
            foreach ($configData as $data) {
                $config_model = new TblConfigTxnResult();
                $config_model->attributes = $dispatch_inspection->attributes;
                $config_model->attributes = $data;
                $config_model->config_for = 'BMC_DISPATCH_INSPECTION';
                $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                $config_model->ref_code = $dispatch_inspection->bmc_dispatch_inspection_code;
                $config_detail = $config_model->configCode;
                $save_model[] = $config_model;
                $cnt++;
            }
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction($save_model, ['Trip', 'create']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
    }

    /**
     * Updates an existing TblBmcMilkDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bmc_milk_dispatch_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionCheckTrip() {
        //  $from_datetime = date('Y-m-d', strtotime(Yii::$app->request->get('from_date'))) . ' ' . \Yii::$app->general->getshift(Yii::$app->request->get('from_shift'));
        //  $to_datetime = date('Y-m-d', strtotime(Yii::$app->request->get('to_date'))) . ' ' . \Yii::$app->general->getshift(Yii::$app->request->get('to_shift'));
        $model = new TblBmcMilkDispatch();
        $model->from_date = Yii::$app->request->get('from_date');
        $model->to_date = Yii::$app->request->get('to_date');
        $model->from_shift_code = Yii::$app->request->get('from_shift');
        $model->to_shift_code = Yii::$app->request->get('to_shift');
        $model->bmc_code = Yii::$app->request->get('bmc_code');
        $model->vehicle_code = Yii::$app->request->get('vehicle_code');
        $response = $model->getTripDetail();
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionTransactionForm() {
        $union_code = Yii::$app->request->get('union_code');
        $config = new TblConfig();
        $config->config_for = Yii::$app->request->get('org_type');
        $configCode = Yii::$app->request->get('org_code');
        $config->process_name = Yii::$app->request->get('process_name');
        $config->config_type = 'CONTROL';
        $config_mapping = new TblConfigTxnResult();
        $config_list = $config->getOrgConfigList($config->config_for, $configCode);
        $auto_reject = isset(Yii::$app->session->get('unionConfig')[$union_code]['bmc_dispatch_auto_reject']) ? Yii::$app->session->get('unionConfig')[$union_code]['bmc_dispatch_auto_reject'] : '0';
        $txn_model = new TblBmcMilkDispatchTxn();
        return $this->renderAjax('_from_transaction', [
                    'txn_model' => $txn_model,
                    'config' => $config_mapping,
                    'config_list' => $config_list,
                    'auto_reject' => $auto_reject
        ]);
    }

    public function actionTransactionDetail() {
        $searchModel = new TblBmcMilkDispatchTxnSearch();
        $searchModel->bmc_milk_dispatch_code = Yii::$app->request->get('bmc_milk_dispatch_code');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderAjax('_transaction_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isVisible' => (!empty(Yii::$app->request->get('form_type')) && Yii::$app->request->get('form_type') == 'plant') ? FALSE : TRUE,
                    'txnEdit' => Yii::$app->request->get('txnEdit'),
        ]);
    }

    public function actionDestinationCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                if (strtolower($parents[0]) == 'bmc') {
                    $RLS = (isset($parents[3]) && !empty($parents[3])) ? 'FALSE' : 'TRUE';
                    $model = new TblDcsBmc();
                    $data = $model->getBMCList('', $RLS);
                } else if (strtolower($parents[0]) == 'plant') {
                    $RLS = (isset($parents[3]) && !empty($parents[3])) ? 'FALSE' : 'TRUE';
                    $type = (isset($parents[5]) && !empty($parents[5])) ? $parents[5] : '';
                    $model = new TblPlant();
                    $data = $model->getPlantList($parents[1], $RLS, [], false, $type);
                } else if (strtolower($parents[0]) == 'party') {
                    $partyType = (isset($parents[4]) && !empty($parents[4])) ? $parents[4] : '';
                    $model = new TblPartyMaster();
                    $data = $model->getPartyList($parents[1], 'TRUE', [], true, $partyType);
                } else {
                    $model = new TblCustomerMaster();
                    $data = $model->getCustomerCodeList($parents[2], $parents[0], $parents[1]);
                }
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Finds the TblBmcMilkDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBmcMilkDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcMilkDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditTripDetail() {
        $searchModel = new TblBmcMilkDispatchSearch();
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
        $id = \Yii::$app->request->post()['bmc_milk_dispatch_code'];
        $model = $this->findModel($id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!empty($model)) {
            $trip = new TblVehicleTripDetail();
            $data = $trip->getOpenTripList($model->bmc_code, '', $model->transaction_date, 'alltrip');
            return ['status' => 'success', 'res' => $data];
        }
        return ['status' => 'error', 'res' => []];
    }

    public function actionChangeTripCode() {
        $id = \Yii::$app->request->post()['bmc_milk_dispatch_code'];
        $trip_code = \Yii::$app->request->post()['trip_code'];
        $model = $this->findModel($id);
        $msg = '';
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!empty($model)) {
            if ($model->trip_code != $trip_code) {
                $historyModel = new TblBmcMilkDispatchHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
                $model->trip_code = $trip_code;
                $trip_data = $model->tripCode;
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
            $msg = 'Dispatch Detail Not Found.';
        }
        return ['status' => 'error', 'msg' => $msg];
    }

    public function actionCreatePlantDispatch($id = '', $tripGenerateBtn = FALSE, $txnEdit = FALSE) {
        $model = new TblBmcMilkDispatch();
        if ($id != '') {
            $model = $this->findModel($id);
        } else {
            $model->transaction_date = date('Y-m-d');
        }
        $model->scenario = 'createPlantDispatch';
        $txn_model = new TblBmcMilkDispatchTxn();
        $txn_model->scenario = 'createPlantDispatch';
        if ($model->load(Yii::$app->request->post()) && $txn_model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->from_date = date('Y-m-d', strtotime($model->from_date)) . ' ' . \Yii::$app->general->getshift($model->from_shift_code);
            $model->to_date = date('Y-m-d', strtotime($model->to_date)) . ' ' . \Yii::$app->general->getshift($model->to_shift_code);
            $model->transaction_date = date('Y-m-d', strtotime($model->transaction_date));
            $model->source_org_code = $model->plant_code;
            $model->source_org_type = 'plant';
            $txn_model->from_datetime = $model->from_date;
            $txn_model->to_datetime = $model->to_date;
            $txn_model->union_code = $model->union_code;
            $txn_model->bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
            $txn_model->vehicle_code = $model->vehicle_code;
            $type = 'create';
            if ($txnEdit) {
                $type = 'edit';
            }
            $validation = TRUE;
            if ($txn_model->validate()) {
                $saveModel = [];
                $deleteModel = [];
                $new_rec = FALSE;

                if (empty($model->bmc_milk_dispatch_code)) {
                    $new_rec = TRUE;
                    $model->originating_org_code = $model->union_code;
                    $model->bmc_milk_dispatch_code = Yii::$app->general->getUuid();
                    $model->challan_no = $model->getChallanNo(); //$model->trip_code . '/' . $model->plant_code . '/1';
                    $model->vehicle_in_time = $model->transaction_date . ' ' . $model->vehicle_in_time;
                    $model->vehicle_out_time = $model->transaction_date . ' ' . date('H:i:s');
                    $tripModel = new TblVehicleTrip();
                    $tripModel->trip_code = $model->trip_code;
                    $tripModel = $tripModel->getTripData();
                    if (!empty($tripModel)) {
                        $tripModel->trip_status = $model->is_last_destination == 1 ? 'tankerfull' : 'open';
                        $model->driver_name = $tripModel->driver_name;
                        $model->driver_contact_no = $tripModel->mobile_no;
                        $saveModel[] = $tripModel;
                        $tripModel->addTripRoute($saveModel, $deleteModel, $model->challan_no, 'plant', $model->plant_code, $model->destination_type, $model->destination_code, $validation, $model->is_last_destination);
                        if (!$validation) {
                            $model->bmc_milk_dispatch_code = '';
                            $model->addError('destination_code', "Conversion party not mapped with plant");
                        }
                    }
                    $tripModel->scenario = 'closetrip';
                    $saveModel[] = $model;
                }
                $txn_model->attributes = $model->attributes;
                if (!$txnEdit) {
                    $txn_model->test_report_no = $txn_model->generateTestReportNo();
                    $txn_model->bmc_milk_dispatch_txn_code = Yii::$app->general->getTransactionCode($txn_model, $model->bmc_milk_dispatch_code);
                }
                $txn_model->qty_mode = Yii::$app->general->getUnionConfiguration($txn_model->union_code, 'dispatch_qty_mode', 'PLANT');
                $conversion_const = Yii::$app->general->getUnionConfiguration($txn_model->union_code, 'ltr_to_kg_constant', 'PLANT');
                $conversion_const = !empty($conversion_const) ? $conversion_const : 1;
                $txn_model->converted_qty_mode = $txn_model->qty_mode == 1 ? 0 : 1;
                $txn_model->converted_qty = $txn_model->qty_mode == 1 ? $txn_model->dispatch_qty / $conversion_const : $txn_model->dispatch_qty * $conversion_const;
                if ($txnEdit) {
                    $bmcMilkDispatchTxnData = TblBmcMilkDispatchTxn::findOne($txn_model->bmc_milk_dispatch_txn_code);
                    $bmcMilkDispatchTxnData->scenario = 'createPlantDispatchUpdate';
                    $txnHistoryModel = new TblBmcMilkDispatchTxnHistory();
                    Yii::$app->operation->history($bmcMilkDispatchTxnData, $txnHistoryModel, UPDATE);
                    $saveModel[] = $txnHistoryModel;
                    $bmcMilkDispatchTxnData->attributes = $txn_model->attributes;
                    $saveModel[] = $bmcMilkDispatchTxnData;
                    $configTxnDeleteData = TblConfigTxnResult::find()->where(['ref_code' => (string) $txn_model->bmc_milk_dispatch_txn_code, 'config_for' => 'PLANT_DISPATCH', 'union_code' => $bmcMilkDispatchTxnData->union_code])->all();
                    foreach ($configTxnDeleteData as $key => $id) {
                        $configTxnHistoryModel = new TblConfigTxnResultHistory();
                        Yii::$app->operation->history($id, $configTxnHistoryModel, DELETE);
                        $deleteModel[] = $id;
                        $saveModel[] = $configTxnHistoryModel;
                    }
                } else {
                    $saveModel[] = $txn_model;
                }

                $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
                $cnt = 1;
                foreach ($config_data as $data) {
                    $config_model = new TblConfigTxnResult();
                    $config_model->attributes = $txn_model->attributes;
                    $config_model->attributes = $data;
                    $config_model->config_for = 'PLANT_DISPATCH';
                    $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                    $config_model->ref_code = $txn_model->bmc_milk_dispatch_txn_code;
                    $config_detail = $config_model->configCode;
                    $auto_reject = !empty(Yii::$app->session->get('unionConfig')[$model->union_code]['bmc_dispatch_auto_reject']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['bmc_dispatch_auto_reject'] : '0';
                    if ($auto_reject == '1' && $config_detail->is_adulteration == 1 && $config_detail->check_value != '') {
                        if (in_array($config_detail->control_type, ['RADIO', 'DROPDOWN']) && (int) $config_detail->check_value != (int) $config_model->config_result) {
                            $txn_model->is_rejected = 1;
                        } else if ($config_detail->control_type == 'TEXT' && (int) $config_model->config_result > (int) $config_detail->check_value) {
                            $txn_model->is_rejected = 1;
                        }
                    }
                    $saveModel[] = $config_model;
                    $cnt++;
                }
                if ($validation) {
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['PLANT Milk Dispatch', $type]);
                    if ($transaction != 'customRedirect' && $new_rec) {
                        $model->bmc_milk_dispatch_code = '';
                    } else if ($transaction == 'customRedirect') {
                        return $this->redirect(['create-plant-dispatch', 'id' => $model->bmc_milk_dispatch_code, 'txnEdit' => $txnEdit]);
                    }
                }
            }
        }
        return $this->render('plant_create', [
                    'model' => $model,
                    'txn_model' => $txn_model,
                    'tripGenerateBtn' => $tripGenerateBtn,
                    'txnEdit' => $txnEdit,
        ]);
    }

    public function actionChamberCapacityDetails() {
        $bmcMilkDispatchTxn = new TblBmcMilkDispatchTxn();
        $bmcMilkDispatchTxn->trip_code = \Yii::$app->request->post()['trip_code'];
        $bmcMilkDispatchTxn->vehicle_code = \Yii::$app->request->post()['vehicle_code'];
        $compartmentWiseDispatchData = $bmcMilkDispatchTxn->getCompartmentWiseDispatchData();
        if (!empty($compartmentWiseDispatchData)) {
            $response = ['status' => 'success', 'chamber_wise_data' => $compartmentWiseDispatchData, 'msg' => 'Chamber Capacity Not Found'];
        } else {
            $response = ['status' => 'error', 'msg' => 'Chamber Capacity Not Found'];
        }
        return Json::encode($response);
    }

    public function actionVehicleTripDetail() {
        $vehicleTripDetail = new TblVehicleTripDetail();
        $vehicleTripDetail->trip_code = \Yii::$app->request->post()['trip_code'];
        $vehicleTripDetail->source_org_code = \Yii::$app->request->post()['source_org_code'];
        $vehicleTripDetail->source_org_type = \Yii::$app->request->post()['source_org_type'];
        $tankerMovementWithTripSubStatus = \Yii::$app->request->post()['tankerMovementWithTripSubStatus'];
        $data = $vehicleTripDetail->getTripDetails($tankerMovementWithTripSubStatus);
        if (!empty($data)) {
            $response = ['status' => 'success', 'data' => $data, 'currentTime' => date('H:i')];
        } else {
            $response = ['status' => 'error', 'data' => [], 'currentTime' => date('H:i')];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionChallan($id) {
        $controls = [];
        $controls['p_bmc_milk_dispatch_code'] = $id;
        $controls['p_report_name'] = 'Tanker Dispatch Challan';
        $reportPath = 'vsp/TankerDispatchChallan';
        if (Yii::$app->session->get('eiplCode') == 'DODLA') {
            $reportPath = 'vsp/DispatchChallan';
        }
        $this->printDocument($controls, $reportPath, 'TankerDispatchChallan', 'pdf');
    }

    public function actionGetClrInput() {
        $unionCode = Yii::$app->request->post('union_code');
        $orgCode = Yii::$app->request->post('orgCode');
        $field = Yii::$app->request->post('field');
        $for = Yii::$app->request->post('for');
        $isClrInput = Yii::$app->general->getCheckBmcConfiguration($unionCode, 'is_clr_input', $orgCode, $field, $for);
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
        $org_type = Yii::$app->request->post('orgType');
        $org_code = Yii::$app->request->post('orgCode');
        $is_clr_input = Yii::$app->request->post('is_clr_input');
        $processName = Yii::$app->request->post('processName');
        $result = Yii::$app->general->calculateData($processName, $union, $org_code, $fat, $snf, $clr, $org_type, $is_clr_input);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode(['status' => 'success', 'data' => $result['clr']]);
    }

    public function actionGetDatePurchaseDetails() {
        $bmcMilkDispatch = new TblBmcMilkDispatch();
        $bmcMilkDispatch->bmc_code = Yii::$app->request->post('bmcCode');
        $data = $bmcMilkDispatch->getFromDateToDate();
        $result = $this->renderAjax('_purchase_detail', [
            'result' => $data['stock_data'],
            'stock_detail' => $data['stock_detail']
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode(['data' => $data, 'result' => $result]);
    }

    public function actionGetQualityParamRange() {
        $model = new TblMilkQualityParamRange();
        $model->union_code = Yii::$app->request->post('union');
        $model->process_name = Yii::$app->request->post('processName');
        $model->org_type = Yii::$app->request->post('orgType');
        $model->org_code = Yii::$app->request->post('orgCode');
        $model->animal_type_code = Yii::$app->request->post('milkTypeCode');
        $data = $model->getQualityRange();
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => !empty($data) ? 'success' : 'error', 'data' => !empty($data) ? $data : []]);
    }

    public function actionUpdateTransaction() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $status = 'error';
        $modelData = [];
        if (!empty($_POST['bmc_milk_dispatch_txn_code'])) {
            $modelData = TblBmcMilkDispatchTxn::findOne($_POST['bmc_milk_dispatch_txn_code']);
            if (!empty($modelData)) {
                $configData = TblConfigTxnResult::find()
                        ->select(['config_code', 'config_result'])
                        ->where(['ref_code' => $_POST['bmc_milk_dispatch_txn_code'], 'config_for' => ['BMC_DISPATCH', 'PLANT_DISPATCH']])
                        ->asArray()
                        ->all();
                $config_data = ArrayHelper::map($configData, 'config_code', 'config_result');
                $status = 'success';
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['status' => $status, 'modelData' => $modelData, 'configData' => $config_data];
    }

    public function actionTotalVehicleCapacity() {
        $totalVehicleCapacity = TblVehicleCompartmentDetail::find()->where(['vehicle_code' => Yii::$app->request->get('vehicle_code')])->sum('capacity');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode(['totalVehicleCapacity' => $totalVehicleCapacity ?? 0]);
    }

}
