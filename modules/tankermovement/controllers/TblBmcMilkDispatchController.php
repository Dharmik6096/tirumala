<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblBmcMilkDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use app\modules\configuration\models\TblConfigMapping;
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

/**
 * TblBmcMilkDispatchController implements the CRUD actions for TblBmcMilkDispatch model.
 */
class TblBmcMilkDispatchController extends \app\controllers\ChildController {

    public $freeAccessActions = ['purchase-detail', 'transaction-form', 'transaction-detail', 'destination-code-list', 'check-trip', 'view-config', 'get-trip-code', 'change-trip-code'];

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
        $searchModel->config_for = 'BMC_DISPATCH';
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
    public function actionCreate($id = '') {
        $model = new TblBmcMilkDispatch();
        if ($id != '') {
            $model = $this->findModel($id);
        } else {
            $model->scenario = 'create';
        }
        $this->setCode($model);
        $txn_model = new TblBmcMilkDispatchTxn();
        if ($model->load(Yii::$app->request->post()) && $txn_model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->from_date = date('Y-m-d', strtotime($model->from_date)) . ' ' . \Yii::$app->general->getshift($model->from_shift_code);
            $model->to_date = date('Y-m-d', strtotime($model->to_date)) . ' ' . \Yii::$app->general->getshift($model->to_shift_code);
            $model->transaction_date = date('Y-m-d', strtotime($model->transaction_date));
            $txn_model->from_datetime = $model->from_date;
            $txn_model->to_datetime = $model->to_date;
            $txn_model->bmc_code = $model->bmc_code;
            $txn_model->union_code = $model->union_code;
            $txn_model->bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
            $txn_model->scenario = 'create';
            if ($txn_model->validate()) {
                $saveModel = [];
                $new_rec = FALSE;

                if (empty($model->bmc_milk_dispatch_code)) {
                    $new_rec = TRUE;
                    $model->originating_org_code = $model->union_code;
                    $model->bmc_milk_dispatch_code = Yii::$app->general->getUuid();
                    $model->challan_no = $model->getChallanNo(); //$model->trip_code . '/' . $model->bmc_code . '/1';
                    $model->driver_name = $model->vehicleCode->driver_name;
                    $model->driver_contact_no = $model->vehicleCode->driver_contact_no;
                    $model->vehicle_in_time = $model->transaction_date . ' ' . $model->vehicle_in_time;
                    $model->vehicle_out_time = $model->transaction_date . ' ' . $model->vehicle_out_time;
                    $tripModel = new TblVehicleTrip();
                    $tripModel->trip_code = $model->trip_code;
                    $tripModel = $tripModel->getTripData();
                    if (!empty($tripModel)) {
                        $tripModel->scenario = 'closetrip';
                        $tripModel->trip_status = 'open';
                        $saveModel[] = $tripModel;
                    }
                    $trip_detail = TblVehicleTripDetail::find()
                                    ->where(['trip_code' => $model->trip_code])
                                    ->andWhere(['lower(source_org_type)' => 'bmc', 'source_org_code' => $model->bmc_code])
                                    ->andWhere(['IS', 'challan_no', NULL])
                                    ->orderBy(['created_at' => SORT_ASC])->one();
                    if (!empty($trip_detail)) {
                        $trip_detail->challan_no = $model->challan_no;
                        $saveModel[] = $trip_detail;
                    }
                    $saveModel[] = $model;
                }
                $txn_model->attributes = $model->attributes;
                $txn_model->bmc_milk_dispatch_txn_code = Yii::$app->general->getTransactionCode($txn_model, $model->bmc_milk_dispatch_code);
                $txn_model->qty_mode = Yii::$app->general->getUnionConfiguration($txn_model->union_code, 'dispatch_qty_mode', 'BMC');
                $conversion_const = Yii::$app->general->getUnionConfiguration($txn_model->union_code, 'ltr_to_kg_constant', 'BMC');
                $txn_model->converted_qty_mode = $txn_model->qty_mode == 1 ? 0 : 1;
                $txn_model->converted_qty = $txn_model->qty_mode == 1 ? $txn_model->dispatch_qty / $conversion_const : $txn_model->dispatch_qty * $conversion_const;
                $stock_model = new TblBmcDispatchStock();
                $stock_model->attributes = $txn_model->attributes;
                $stock_model->to_date = $model->to_date;
                $stock_model->to_shift_code = ($model->to_shift_code == 1) ? 2 : 1;
                $stock_model->transaction_date = $model->transaction_date;
                $stock_model->closing_bal = $txn_model->dispatch_qty;
                $stock_data = $stock_model->getStockEntry();
                if (!empty($stock_data)) {
                    $stock_model = $stock_data;
                    $stock_model->qty_diff = $txn_model->qty_diff;
                    $stock_model->qty_diff_type_code = $txn_model->qty_diff_type_code;
                    $stock_model->balance_qty = $txn_model->balance_qty;
                    $stock_model->closing_bal = $stock_data->closing_bal + $txn_model->dispatch_qty;
                } else {
                    $stock_model->bmc_dispatch_stock_code = Yii::$app->general->getPrimaryCode($stock_model);
                    $stock_model->purchase_qty = $txn_model->purchase_qty;
                    $stock_model->opening_bal = $txn_model->opening_bal;
                }
                $saveModel[] = $txn_model;
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
                $transaction = $this->generalModel->saveTransaction($saveModel, ['BMC Milk Dispatch', 'create']);
                if ($transaction != 'customRedirect' && $new_rec) {
                    $model->bmc_milk_dispatch_code = '';
                } else if ($transaction == 'customRedirect') {
                    return $this->redirect(['create', 'id' => $model->bmc_milk_dispatch_code]);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'txn_model' => $txn_model,
        ]);
    }

    public function setCode($model) {
        $plants = explode(',', $_SESSION['Plant']);
        $mccs = explode(',', $_SESSION['MCC']);
        $bmcs = explode(',', $_SESSION['BMC']);
        count($plants) == 1 ? $model->plant_code = $plants[0] : '';
        count($mccs) == 1 ? $model->mcc_plant_code = $mccs[0] : '';
        count($bmcs) == 1 ? $model->bmc_code = $bmcs[0] : '';
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

    public function actionPurchaseDetail() {
        $from_datetime = date('Y-m-d', strtotime(Yii::$app->request->get('from_date'))) . ' ' . \Yii::$app->general->getshift(Yii::$app->request->get('from_shift'));
        $to_datetime = date('Y-m-d', strtotime(Yii::$app->request->get('to_date'))) . ' ' . \Yii::$app->general->getshift(Yii::$app->request->get('to_shift'));
        $bmc_code = Yii::$app->request->get('bmc_code');
        $query = \Yii::$app->db->createCommand("{CALL sp_portal_bmc_purchase_detail (:bmc_code,:from_datetime,:to_datetime)}")
                ->bindValue(':from_datetime', $from_datetime)
                ->bindValue(':to_datetime', $to_datetime)
                ->bindValue(':bmc_code', $bmc_code);
        $result = $query->queryAll();
        return $this->renderAjax('_purchase_detail', [
                    'result' => $result,
        ]);
    }

    public function actionTransactionForm() {
        $union_code = Yii::$app->request->get('union_code');
        $config = new TblConfig();
        $config->config_for = 'BMC';
        $config->process_name = 'BMC_DISPATCH';
        $config->config_type = 'CONTROL';
        $config_mapping = new TblConfigTxnResult();
        $config_list = $config->getOrgConfigList($config->config_for, Yii::$app->request->get('bmc_code'));
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
        ]);
    }

    public function actionDestinationCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                if (strtolower($parents[0]) == 'bmc') {
                    $model = new TblDcsBmc();
                    $data = $model->getBMCList('');
                } else if (strtolower($parents[0]) == 'plant') {
                    $model = new TblPlant();
                    $data = $model->getPlantList($parents[1]);
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

    public function actionFetchFromDate() {
        $stock_date = TblBmcDispatchStock::find()
                ->where(['bmc_code' => $_GET['bmc_code']])
                ->orderBy(['to_date' => SORT_DESC])
                ->one();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!empty($stock_date->to_date)) {
            $dispatch_date = Yii::$app->formatter->asDate($stock_date->to_date, 'dd-MM-Y');
            return ['dispatch_date' => $dispatch_date];
        }
    }

}
