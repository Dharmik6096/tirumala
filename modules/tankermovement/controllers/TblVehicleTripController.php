<?php

namespace app\modules\tankermovement\controllers;

use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlantConversionVendorMapping;
use Yii;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripSearch;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblVehicleTripDetailSearch;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxnSearch;
use app\modules\tankermovement\models\TblBmcDispatchConsolidated;
use app\modules\tankermovement\models\TblBmcDispatchConsolidatedTxn;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblBmcMilkDispatchHistory;
use app\modules\tankermovement\models\TblVehicleQaInspection;
use app\modules\tankermovement\models\TblVehicleQaInspectionHistory;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblVehicleTripDetailHistory;
use app\modules\tankermovement\models\TblVehicleTripHistory;
use app\modules\transporter\models\TblVehicleMaster;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\tankermovement\models\TblVehicleTripTracking;

/**
 * TblVehicleTripController implements the CRUD actions for TblVehicleTrip model.
 */
class TblVehicleTripController extends \app\controllers\ChildController {

    public $freeAccessActions = ['open-trip-list', 'open-trip-detail-list', 'get-vehicle-detail'];

    /**
     * Lists all TblVehicleTrip models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleTripSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleTrip model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblVehicleTripDetailSearch();
        $searchModel->vehicle_trip_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVehicleTrip model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleTrip();
        $this->model->scenario = 'createTrip';
        $this->model->transaction_date = date('Y-m-d');
        $this->viewFile = 'create';
        $type = Yii::$app->request->get('type');
        $this->model->type = !empty($type) ? $type : 'normal';
        $bmc_array = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $is_auto_trip = $this->model->is_auto_trip;
            if (isset(Yii::$app->request->post()['selected_bmc_seq'])) {
                $bmc_string = Yii::$app->request->post()['selected_bmc_seq'];
                $bmc_detail = explode(':::', $bmc_string);
                unset($bmc_detail[count($bmc_detail) - 1]);
                foreach ($bmc_detail as $k => $v) {
                    $bmc_index = explode('~~~', $v);
                    $bmc_array[$bmc_index[0]] = $bmc_index[1];
                }
                ksort($bmc_array);
                $bmc_array_sort = [];
                foreach ($bmc_array as $a) {
                    $bmc_array_sort[] = $a;
                }
                $this->model->bmc_code = $bmc_array_sort;
            } else {
                $this->model->bmc_code = NULL;
            }
            $bmc_array = $this->model->bmc_code;
            $is_valid_trip = FALSE;
            if (count($bmc_array) > 2 || ($is_auto_trip && count($bmc_array) > 1)) {
                $sl_detail = explode('#', $bmc_array[0]);
                $sl_code = $sl_detail[0];
                $sl_type = !empty($sl_detail[1]) ? $sl_detail[1] : 'bmc';

                $el_detail = explode('#', $bmc_array[count($bmc_array) - 1]);
                $el_code = $el_detail[0];
                $el_type = !empty($el_detail[1]) ? $el_detail[1] : 'bmc';

                $is_valid_trip = ($sl_type == 'plant' && ($is_auto_trip || $el_type == 'plant')) ? TRUE : FALSE;
                if ($this->model->validate() && $is_valid_trip) {
                    $this->model->plant_code = $sl_code;
                    $fl_detail = explode('#', $bmc_array[1]);
                    $this->model->fl_code = $fl_detail[0];
                    $this->model->fl_type = !empty($fl_detail[1]) ? $fl_detail[1] : 'bmc';
                    if ($this->model->fl_type == 'bmc') {
                        $this->model->bmc_code = $this->model->fl_code;
                        $this->model->mcc_plant_code = $this->model->bmcCode->mcc_plant_code;
                    } else {
                        $this->model->bmc_code = NULL;
                        $this->model->mcc_plant_code = NULL;
                    }
                }
            }
            if (!$is_valid_trip && !$is_auto_trip) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Vehicle trip must be start and end at plant and you must select at least one more location.'
                ]);
            } else if (!$is_valid_trip && $is_auto_trip) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Vehicle trip must start at a plant and you must select at least one more location.'
                ]);
            }
            if (empty($this->model->getErrors())) {
                $this->model->trip_mode = 'offline';
                $this->model->trip_for = ($type == 'party') ? 'salesparty' : 'bmcdispatch';
                $this->model->trip_sub_status = 'generated';
                $this->model->sub_status_time = date('Y-m-d H:i:s');
                if ($is_valid_trip) {
                    $sequence_no = 1;
                    $result = $this->model->setModel();
                    if ($result[0]) {
                        $validate = TRUE;
                        $save_model = $result[1];
                        if (!empty($save_model) && $this->model->fl_type == 'plant') {
                            $save_model[0]->plant_code = $this->model->fl_code;
                        }
                        $cnt = 2;
                        foreach ($bmc_array as $key => $bmc) {
                            if ($key == 0) {
                                continue;
                            }
                            $sequence_no++;
                            $sloc_detail = explode('#', $bmc);
                            $is_virtual_location = 0;
                            if(!empty($sloc_detail[2])){
                                $mappedModel = new TblPlantConversionVendorMapping();
                                $mappedPlant = $mappedModel->getMappedPlant($sloc_detail[0]);
                                if(!empty($mappedPlant)){
                                    $is_virtual_location = 1;
                                    $this->setTripDetail($save_model, $bmc_array, $key, $this->model, $sloc_detail, $validate, $is_auto_trip, $sequence_no, $cnt, $is_virtual_location, $mappedPlant);
                                    $sloc_detail[0] = $mappedPlant->plant_code;
                                    $sloc_detail[1] = 'plant';
                                    $is_virtual_location = 2;
                                    $sequence_no++;
                                } else {
                                    $validate = FALSE;
                                    $this->model->addError('bmc_code', 'Conversion party not mapped with plant');
                                }
                            }
                            $this->setTripDetail($save_model, $bmc_array, $key, $this->model, $sloc_detail, $validate, $is_auto_trip, $sequence_no, $cnt, $is_virtual_location);
                        }
                        $qaModel = new TblVehicleQaInspection();
                        $qaRecords = $qaModel->getVehicleQaInpection($this->model->vehicle_code);
                        if (!empty($qaRecords)) {
                            foreach ($qaRecords as $qa) {
                                $historyModel = new TblVehicleQaInspectionHistory();
                                Yii::$app->operation->history($qa, $historyModel, UPDATE);
                                $qa->status = 'closed';
                                $save_model[] = $historyModel;
                                $save_model[] = $qa;
                            }
                        }
                        if ($validate) {
                            $transaction = $this->generalModel->saveTransaction($save_model, ['Vehicle Trip with Trip No. ' . $result[2]['trip_code'], 'create']);
                            if ($transaction == 'customRedirect') {
                                $response = Yii::$app->general->getColumnName($save_model[1]->source_org_type);
                                $remarks = '';
                                if (!empty($response['rel'])) {
                                    $sourceData = $save_model[1]->{$response['rel'] . 'Source'};
                                    $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']};
                                }
                                $tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'tanker_movement_with_trip_sub_status', 'PORTAL');
                                if ($tankerMovementWithTripSubStatus) {
                                    $tripModel = clone $save_model[0];
                                    if (!empty($qaRecords)) {
                                        $tripModel->sub_status_time = $qaRecords[0]->transaction_datetime;
                                        $tripModel->trip_sub_status = 'quality_checked';
                                        $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
                                        Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $trackingDetail, $remarks);
                                    }
                                }
                                $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
                                Yii::$app->general->setVehicleTripTrackingDetail($save_model[0], $trackingDetail, $remarks);

                                if (!$tankerMovementWithTripSubStatus && $result[2]['inspection_require']) {
                                    return $this->redirect([
                                                '/tankermovement/tbl-bmc-dispatch-inspection/create',
                                                'trip_code' => $result[2]['trip_code'],
                                                'vehicle_trip_detail_code' => $result[2]['vehicle_trip_detail_code']
                                    ]);
                                } else {
                                    return $this->{$transaction}();
                                }
                            }
                        }
                    }
                }
            }
        }
        if (empty($bmc_array)) {
            $this->model->is_auto_trip = 1;
        }
        if (empty($bmc_array)) {
            $bmcModel = new TblDcsBmc();
            $plantCodes = $bmcModel->getBmcPlantList();
            if(!empty($plantCodes)){
                $plant = [];
                $plant[] = $plantCodes[0]['plant_code'];
                if(!empty(Yii::$app->session->get('Plant')) && count(explode(',', Yii::$app->session->get('Plant'))) == 1){
                    $plant[] = Yii::$app->session->get('Plant');
                    $bmc_array[] = Yii::$app->session->get('Plant').'#plant';
                }
                $this->model->plant_code = $plant;
            }
        }
        $this->model->bmc_code = $bmc_array;
        return $this->customRender();
    }

    public function setTripDetail(&$save_model, $bmc_array, $key, &$model, &$sloc_detail, &$validate, &$is_auto_trip, &$sequence_no, &$cnt, $is_virtual_location, $mappedPlant = []) {
        $trip_detai = new TblVehicleTripDetail();
        $trip_detai->source_org_code = $sloc_detail[0];
        $trip_detai->source_org_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';
        if(empty($mappedPlant)){
            if (!empty($bmc_array[$key + 1])) {
                $dloc_detail = explode('#', $bmc_array[$key + 1]);
                $trip_detai->destination_code = $dloc_detail[0];
                $trip_detai->destination_type = !empty($dloc_detail[1]) ? $dloc_detail[1] : 'bmc';
            } else {
                if (!$is_auto_trip) {
                    $trip_detai->is_last_destination = 1;
                }
            }
        } else {
            $trip_detai->destination_code = $mappedPlant['plant_code'];
            $trip_detai->destination_type = 'plant';
        }
        $trip_detai->is_virtual_location = $is_virtual_location;
        $trip_detai->originating_org_code = $model->union_code;
        $trip_detai->vehicle_trip_code = $model->vehicle_trip_code;
        $trip_detai->vehicle_code = $model->vehicle_code;
        $trip_detai->transaction_datetime = date('Y-m-d H:i:s');
        $trip_detai->trip_code = $model->trip_code;
        $trip_detai->vehicle_trip_detail_code = $trip_detai->vehicle_trip_code . 'T' . $cnt;
        $trip_detai->sequence_no = $sequence_no;
        $trip_detai->scenario = 'on_crete_trip';
        if (!$trip_detai->validate()) {
            $validate = FALSE;
            $errors = $trip_detai->getErrors();
            if (isset($errors['destination_code'])) {
                $model->addError('bmc_code', $errors['destination_code'][0]);
            }
        }
        $save_model[] = $trip_detai;
        $cnt++;
    }

    public function actionGenerateChallan($id) {
        $model = new TblVehicleTrip();
        $model->vehicle_trip_code = $id;
        $model = $model->dispatchConsolidatedSummary();
        if (!empty($model)) {
            $searchModel = new TblBmcMilkDispatchTxnSearch();
            $searchModel->trip_code = $model->trip_code;
            $dataProvider = $searchModel->searchTripDetail();
            if (count($dataProvider->getModels()) > 0) {
                if (Yii::$app->request->post()) {
                    $saveModel = [];
                    $dispact_consolidate = new TblBmcDispatchConsolidated();
                    $dispact_consolidate->bmc_dispatch_consolidated_code = Yii::$app->general->getPrimaryCode($dispact_consolidate);
                    $dispact_consolidate->attributes = $model->attributes;
                    $dispact_consolidate->kg_fat = $model->kg_fat;
                    $dispact_consolidate->kg_snf = $model->kg_snf;
                    $dispact_consolidate->total_qty = $model->total_qty;
                    $dispact_consolidate->rejection_count = $model->rejected_count;
                    $saveModel[] = $dispact_consolidate;
                    $cnt = 1;
                    foreach ($dataProvider->getModels() as $txn) {
                        $dispact_txn = new TblBmcDispatchConsolidatedTxn();
                        $dispact_txn->attributes = $txn->attributes;
                        $dispact_txn->bmc_dispatch_consolidated_txn_code = $dispact_consolidate->bmc_dispatch_consolidated_code . 'T' . $cnt;
                        $dispact_txn->created_at = $dispact_txn->created_by = $dispact_txn->updated_at = $dispact_txn->updated_by = NULL;
                        $dispact_txn->originating_type = $dispact_txn->originating_org_code = $dispact_txn->originating_org_type = NULL;
                        $dispact_txn->challan_no = !empty($txn->bmcMilkDispatchCode) ? $txn->bmcMilkDispatchCode->challan_no : NULL;
                        $dispact_txn->sample_no = !empty($txn->sampleBottleNo) ? $txn->sampleBottleNo->config_result : NULL;
                        $saveModel[] = $dispact_txn;
                        $cnt++;
                    }
                    $model->scenario = 'closetrip';
                    $model->trip_status = 'tankerfull';
                    $saveModel[] = $model;
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Consolidate Challan with Challan No. ' . $dispact_consolidate->bmc_dispatch_consolidated_code, 'create']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    }
                }
                return $this->render('generate_challan', [
                            'model' => $model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Dispatch Detail not found.'
                ]);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionPrintChallan($id) {
        $controls = [];
        $controls['p_trip_code'] = $id;
        $controls['p_report_name'] = 'Consolidate Challan';
        $this->printDocument($controls, 'reportpath', 'filename', 'pdf');
    }

    /**
     * Finds the TblVehicleTrip model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVehicleTrip the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleTrip::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCloseTrip($id) {
        $tripModel = $this->findModel($id);
        $historyModel = new TblVehicleTripHistory();
        Yii::$app->operation->history($tripModel, $historyModel, UPDATE);
        $tripModel->scenario = 'closetrip';
        $tripModel->trip_status = 'closed';
        $tripModel->trip_sub_status = 'cleaning_pending';
        $transaction = $this->generalModel->saveTransaction([$tripModel, $historyModel], ['Vehicle Trip Status', 'edit']);
        $msg = Yii::$app->getSession()->getFlash('success')['message'];
        if ($transaction == 'customRedirect') {
            $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
            Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $trackingDetail, 'Trip Close Forcefully');
            $record = ['status' => 'success', 'msg' => $msg];
        } else {
            $record = ['status' => 'error', 'msg' => $msg];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionInactiveTrip($id) {
        $saveModel = [];
        $tripModel = $this->findModel($id);
        $historyModel = new TblVehicleTripHistory();
        Yii::$app->operation->history($tripModel, $historyModel, UPDATE);
        $tripModel->scenario = 'closetrip';
        $tripModel->trip_status = 'closed';
        $tripModel->trip_sub_status = 'cleaning_pending';
        $tripModel->is_active = 0;
        $saveModel[] = $historyModel;
        $saveModel[] = $tripModel;
        $tripDetail = TblVehicleTripDetail::find()->where(['vehicle_trip_code' => $id])->all();
        foreach ($tripDetail as $detail) {
            $detail->is_active = 0;
            $saveModel[] = $detail;
        }
        $transaction = $this->generalModel->saveTransaction($saveModel, ['Vehicle Trip In-Active', 'edit']);
        $msg = Yii::$app->getSession()->getFlash('success')['message'];
        if ($transaction == 'customRedirect') {
            $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
            Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $trackingDetail, 'Trip Close Forcefully');
            $record = ['status' => 'success', 'msg' => $msg];
        } else {
            $record = ['status' => 'error', 'msg' => $msg];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionOpenTripList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2])) {
                $tripCode = isset($parents[3]) ? $parents[3] : '';
                $type = isset($parents[4]) ? $parents[4] : '';
                $tankerMovementWithTripSubStatus = isset($parents[5]) ? $parents[5] : '';

                $trip = new TblVehicleTripDetail();
                $data = $trip->getOpenTripList($parents[1], $parents[0], $parents[2], $tripCode, $type, $tankerMovementWithTripSubStatus);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetVehicleDetail() {
        $status = 'error';
        $vehicleData = [];
        $postData = Yii::$app->request->post();
        if (!empty($postData['vehicle_code'])) {
            $vehicleData = TblVehicleMaster::find()->alias('vm')
                ->select(['vm.driver_name', 'vm.driver_contact_no', 'vm.transporter_code', 'count(vcd.compartment_no) as compartment_no', 'sum(vcd.capacity) as capacity'])
                ->join('INNER JOIN', 'tbl_vehicle_compartment_detail as vcd', 'vcd.vehicle_code = vm.vehicle_code')
                ->where(['vm.vehicle_code' => $postData['vehicle_code']])
                ->groupBy(['vm.driver_name', 'vm.driver_contact_no', 'vm.transporter_code'])
                ->asArray()
                ->one();
            $status = 'success';
        }
        $record = ['status' => $status, 'data' => $vehicleData];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionGateProcess($vehicle_trip_detail_code = '', $actionType = '') {
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post('TblVehicleTripDetail');
            $vehicle_trip_detail_code = $postData['vehicle_trip_detail_code'];
        }
        $tripDetail = TblVehicleTripDetail::findOne(['vehicle_trip_detail_code' => $vehicle_trip_detail_code]);
        $trip = TblVehicleTrip::findOne(['vehicle_trip_code' => $tripDetail->vehicle_trip_code]);
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $actionType = Yii::$app->request->post('actionType');
            $tripDetail->load(Yii::$app->request->post());
            $tripDetail->scenario = $actionType;
            $remarks = '';
            $visibility_status = 3;
            if (!empty($postData['arrival_time']) && $actionType == 'gate-in') {
                $postedArrival = strtotime($postData['arrival_time']);
                if ($tripDetail->is_virtual_location == 1) {
                    $tripDetail->departure_time = $departure = date('Y-m-d H:i:s', $postedArrival + 1);
                    $nextTripDetail = TblVehicleTripDetail::find()
                            ->where(['vehicle_trip_code' => $tripDetail->vehicle_trip_code])
                            ->andWhere(['>', 'sequence_no', $tripDetail->sequence_no])
                            ->orderBy(['sequence_no' => SORT_ASC])
                            ->one();
                    if ($nextTripDetail) {
                        $nextTripDetail->scenario = $actionType;
                        $nextTripDetail->arrival_time = date('Y-m-d H:i:s', strtotime($departure) + 1);
                        $trip->sub_status_time = $nextTripDetail->arrival_time;
                        $trip->trip_sub_status = $nextTripDetail->is_last_destination ? 'plant_lot_pending' : 'gate_in';
                        $visibility_status = $nextTripDetail->is_last_destination ? 1 : 2;
                    }
                } else {
                    $tripDetail->arrival_time = $trip->sub_status_time = date('Y-m-d H:i:s', $postedArrival);
                    $trip->trip_sub_status = $tripDetail->is_last_destination ? 'plant_lot_pending' : 'gate_in';
                    $visibility_status = $tripDetail->is_last_destination ? 1 : 2;
                }
                $remarks = $tripDetail->in_remarks;
            } elseif (!empty($postData['departure_time']) && $actionType == 'gate-out') {
                $tripDetail->departure_time = $trip->sub_status_time = date('Y-m-d H:i:s', strtotime($postData['departure_time']));
                $visibility_status = 0;
                if (empty($tripDetail->arrival_time) && !empty($tripDetail->departure_time)) {
                    $tripDetail->arrival_time = date('Y-m-d H:i:s', strtotime($tripDetail->departure_time) - 1);
                    $visibility_status = 1;
                }
                $remarks = $tripDetail->out_remarks;
                $trip->trip_sub_status = 'gate_out';
            }

            if ($tripDetail->validate()) {
                $models = [$tripDetail, $trip];
                if (isset($nextTripDetail)) {
                    $models[] = $nextTripDetail;
                }
                $transaction = $this->generalModel->saveTransaction($models, ['Trip Detail', 'edit']);
                if ($transaction == 'customRedirect') {
                    $response = Yii::$app->general->getColumnName($tripDetail->source_org_type);
                    if (!empty($response['rel'])) {
                        $sourceData = $tripDetail->{$response['rel'] . 'Source'};
                        $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']} . '-' . $remarks;
                    }
                    $trackingDetail = ['visibility_status' => $visibility_status, 'module_code' => $tripDetail->vehicle_trip_detail_code, 'module_type' => 'tbl_vehicle_trip_detail'];
                    Yii::$app->general->setVehicleTripTrackingDetail($trip, $trackingDetail, $remarks);
                    return ['status' => 'success', 'msg' => 'Trip processed successfully.'];
                } else {
                    return ['status' => 'error', 'msg' => Yii::$app->getSession()->getFlash('success')['message']];
                }
            } else {
                return ['status' => 'error', 'errors' => $tripDetail->getErrors()];
            }
        }
        return $this->renderAjax('_trip_detail', [
                    'trip' => $trip,
                    'tripDetail' => $tripDetail,
                    'actionType' => $actionType
        ]);
    }

    public function actionOpenTripDetailList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && ($parents[1] == 'milk_entry_qlty' || $parents[1] == 'milk_entry_qlty_merge' || !empty($parents[2]))) {
                $trip = new TblVehicleTripDetail();

                if ($parents[1] != 'cleaning_inspection' || $parents[1] != 'qa_inspection') {
                    $parents[2] = isset($parents[2]) ? $parents[2] : '';
                }
                $data = $trip->getOpenTripDetailList($parents[0], $parents[1], $parents[2]);
                $data = ArrayHelper::map($data, 'trip_code', $parents[1] == 'milk_entry_qlty' ? function ($tripData) {
                            return $tripData['parsing_no'] . ' (' . $tripData['trip_code'] . ')';
                        } : 'trip_code');
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMap($trip_code) {
        $tripTrack = TblVehicleTripTracking::find()->where(['trip_code' => $trip_code])->orderBy(['sub_status_time' => SORT_ASC])->all();
        $parsingNo = '';
        if (!empty($tripTrack) && isset($tripTrack[0]['vehicle_code'])) {
            $parsingNo = TblVehicleMaster::find()->where(['vehicle_code' => $tripTrack[0]['vehicle_code']])->one();
        }
        return $this->render('_map', [
                    'tripTrack' => $tripTrack,
                    'parsingNo' => $parsingNo,
        ]);
    }

    public function actionUpdate($id) {

        $this->model = TblVehicleTrip::find()
                ->with(['vehicleCode.transporter', 'vehicleTripDetailCode'])
                ->where(['vehicle_trip_code' => $id])
                ->one();

        if (!empty($this->model)) {
            $this->model->transporter_code = !empty($this->model->vehicleCode) ? $this->model->vehicleCode->transporter->transporter_code : '';
        }


        $type = Yii::$app->request->get('type');
        $this->model->type = !empty($type) ? $type : 'normal';
        $vehicleTripDetails = $this->model->vehicleTripDetailCode ?? [];

        $sourceBmc = array_map(function($item) {
            if (!empty($item->source_org_code) && !empty($item->source_org_type) && $item->is_virtual_location != 2) {
                if($item->source_org_type == 'bmc'){
                    return $item->source_org_code;
                } else if($item->is_virtual_location == 1){
                    return $item->source_org_code . '#' . strtolower($item->source_org_type) .'#conversion_vendor';
                } else {
                    return $item->source_org_code . '#' . strtolower($item->source_org_type);
                }
            }
            return null;
        }, $vehicleTripDetails);

        $this->model->bmc_code = array_values($sourceBmc);
        $saveModel = [];
        $deleteModel = [];
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $tripDetailData = TblVehicleTripDetail::find()->where(['vehicle_trip_code' => $this->model->vehicle_trip_code])->andWhere(['IS NOT', 'arrival_time', null])->one();
            $is_auto_trip = $this->model->is_auto_trip;
            if (isset(Yii::$app->request->post()['selected_bmc_seq'])) {
                $bmc_string = Yii::$app->request->post()['selected_bmc_seq'];
                $bmc_detail = explode(':::', $bmc_string);
                foreach ($bmc_detail as $k => $v) {
                    $bmc_index = explode('~~~', $v);
                    $bmc_array[$bmc_index[0]] = $bmc_index[1];
                }
                ksort($bmc_array);
                $bmc_array_sort = [];
                foreach ($bmc_array as $a) {
                    $bmc_array_sort[] = $a;
                }
                $this->model->bmc_code = $bmc_array_sort;
            } else {
                $this->model->bmc_code = NULL;
            }
            $bmc_array = $this->model->bmc_code;
            $validate = true;

            $is_valid_trip = FALSE;
            if (!empty($tripDetailData) || count($bmc_array) > 2 || ($is_auto_trip && count($bmc_array) > 1)) {
                $sl_detail = explode('#', $bmc_array[0]);
                $sl_code = $sl_detail[0];
                $sl_type = !empty($sl_detail[1]) ? $sl_detail[1] : 'bmc';

                $el_detail = explode('#', $bmc_array[count($bmc_array) - 1]);
                $el_code = $el_detail[0];
                $el_type = !empty($el_detail[1]) ? $el_detail[1] : 'bmc';

                $is_valid_trip = ((!empty($tripDetailData) || $sl_type == 'plant') && ($is_auto_trip || $el_type == 'plant')) ? TRUE : FALSE;
            }
            if (!$is_valid_trip && !$is_auto_trip) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Vehicle trip must be start and end at plant and you must select at least one more location.'
                ]);
            } else if (!$is_valid_trip && $is_auto_trip) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Vehicle trip must start at a plant and you must select at least one more location.'
                ]);
            }

            if ($is_valid_trip && $this->model->validate()) {
                $deleteDetails = TblVehicleTripDetail::find()
                        ->where(['trip_code' => $this->model->trip_code, 'arrival_time' => null, 'departure_time' => null])
                        ->all();

                if (!empty($deleteDetails)) {
                    foreach ($deleteDetails as $delete) {
                        $historyModel = new TblVehicleTripDetailHistory();
                        Yii::$app->operation->history($delete, $historyModel, DELETE);
                        $saveModel[] = $historyModel;
                        $deleteModel[] = $delete;
                    }
                }
                $lastArrivalDetail = TblVehicleTripDetail::find()
                        ->where(['trip_code' => $this->model->trip_code])
                        ->andWhere(['departure_time' => null])
                        ->andWhere(['is not', 'arrival_time', null])
                        ->one();
                $lastDetail = '';
                if (!empty($lastArrivalDetail)) {
                    $historyModel = new TblVehicleTripDetailHistory();
                    Yii::$app->operation->history($lastArrivalDetail, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $sequence_no = $lastArrivalDetail->sequence_no + 1;
                } else {
                    $lastDetail = TblVehicleTripDetail::find()
                            ->where(['trip_code' => $this->model->trip_code])
                            ->andWhere(['is not', 'departure_time', null])
                            ->andWhere(['is not', 'arrival_time', null])
                            ->orderBy(['sequence_no' => SORT_DESC])
                            ->one();
                    if (!empty($lastDetail)) {
                        $historyModel = new TblVehicleTripDetailHistory();
                        Yii::$app->operation->history($lastDetail, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;
                        $sequence_no = $lastDetail->sequence_no + 1;
                    } else {
                        $sequence_no = 1;
                    }
                }
                $detailModel = new TblVehicleTripDetail();
                $maxNumber = $detailModel->getMaxCode($this->model->vehicle_trip_code);
                foreach ($bmc_array as $key => $bmc) {
                    $trip_detail = new TblVehicleTripDetail();

                    $sloc_detail = explode('#', $bmc);
                    if (!empty($bmc_array[$key + 1])) {
                        $dloc_detail = explode('#', $bmc_array[$key + 1]);
                        $trip_detail->destination_code = $dloc_detail[0];
                        $trip_detail->destination_type = !empty($dloc_detail[1]) ? $dloc_detail[1] : 'bmc';
                    } else {
                        if (!$is_auto_trip || (isset($sloc_detail[1]) && $sloc_detail[1] == 'plant')) {
                            $trip_detail->is_last_destination = 1;
                        }
                    }

                    if ($key == 0 && !empty($lastArrivalDetail)) {
                        $lastArrivalDetail->destination_code = $sloc_detail[0];
                        $lastArrivalDetail->destination_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';
                        $saveModel[] = $lastArrivalDetail;
                    } else if ($key == 0 && !empty($lastDetail)) {
                        $bmcMilkDispatchData = TblBmcMilkDispatch::find()
                                ->where(['trip_code' => $this->model->trip_code, 'source_org_code' => $lastDetail->source_org_code, 'source_org_type' => $lastDetail->source_org_type, 'destination_code' => $lastDetail->destination_code, 'destination_type' => $lastDetail->destination_type])
                                ->orderBy(['created_at' => SORT_DESC])
                                ->one();
                        if (!empty($bmcMilkDispatchData)) {
                            $historyModel = new TblBmcMilkDispatchHistory();
                            Yii::$app->operation->history($bmcMilkDispatchData, $historyModel, UPDATE);
                            $saveModel[] = $historyModel;
                            $bmcMilkDispatchData->scenario = 'tripUpdate';
                            $bmcMilkDispatchData->destination_code = $sloc_detail[0];
                            $bmcMilkDispatchData->destination_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';
                            $bmcMilkDispatchData->is_last_destination = $lastDetail->is_last_destination;
                            $saveModel[] = $bmcMilkDispatchData;
                        }
                        $lastDetail->destination_code = $sloc_detail[0];
                        $lastDetail->destination_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';
                        $saveModel[] = $lastDetail;
                    }

                    if(!empty($sloc_detail[2])){
                        $mappedModel = new TblPlantConversionVendorMapping();
                        $mappedPlant = $mappedModel->getMappedPlant($sloc_detail[0]);
                        if(!empty($mappedPlant)){
                            $virtul_trip_detail = new TblVehicleTripDetail();
                            $virtul_trip_detail->is_virtual_location = 1;
                            $virtul_trip_detail->source_org_code = $sloc_detail[0];
                            $virtul_trip_detail->source_org_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';
                            $virtul_trip_detail->destination_code = $mappedPlant['plant_code'];
                            $virtul_trip_detail->destination_type = 'plant';
                            $virtul_trip_detail->originating_org_code = $this->model->union_code;
                            $virtul_trip_detail->vehicle_trip_code = $this->model->vehicle_trip_code;
                            $virtul_trip_detail->vehicle_code = $this->model->vehicle_code;
                            $virtul_trip_detail->transaction_datetime = date('Y-m-d H:i:s');
                            $virtul_trip_detail->trip_code = $this->model->trip_code;
                            $virtul_trip_detail->vehicle_trip_detail_code = $virtul_trip_detail->vehicle_trip_code . 'T' . $maxNumber;
                            $virtul_trip_detail->sequence_no = $sequence_no;
                            $virtul_trip_detail->scenario = 'on_crete_trip';
                            $saveModel[] = $virtul_trip_detail;
                            if (!$virtul_trip_detail->validate()) {
                                $validate = FALSE;
                                $errors = $virtul_trip_detail->getErrors();
                                if (isset($errors['destination_code'])) {
                                    $this->model->addError('bmc_code', $errors['destination_code'][0]);
                                }
                            }
                            $maxNumber++;
                            $sequence_no++;

                            $sloc_detail[0] = $mappedPlant->plant_code;
                            $sloc_detail[1] = 'plant';
                            $trip_detail->is_virtual_location = 2;
                        } else {
                            $validate = FALSE;
                            $this->model->addError('bmc_code', 'Conversion party not mapped with plant');
                        }
                    }

                    $trip_detail->source_org_code = $sloc_detail[0];
                    $trip_detail->source_org_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';

                    $trip_detail->originating_org_code = $this->model->union_code;
                    $trip_detail->vehicle_trip_code = $this->model->vehicle_trip_code;
                    $trip_detail->vehicle_code = $this->model->vehicle_code;
                    $trip_detail->transaction_datetime = date('Y-m-d H:i:s');
                    $trip_detail->trip_code = $this->model->trip_code;
                    $trip_detail->vehicle_trip_detail_code = $trip_detail->vehicle_trip_code . 'T' . $maxNumber;
                    $trip_detail->sequence_no = $sequence_no;
                    $trip_detail->scenario = 'on_crete_trip';
                    $saveModel[] = $trip_detail;
                    if (!$trip_detail->validate()) {
                        $validate = FALSE;
                        $errors = $trip_detail->getErrors();
                        if (isset($errors['destination_code'])) {
                            $this->model->addError('bmc_code', $errors['destination_code'][0]);
                        }
                    }
                    $maxNumber++;
                    $sequence_no++;
                }
                if ($validate) {
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Vehicle Trip', 'edit']);
                    if ($transaction !== FALSE) {
                        return $this->{$transaction}();
                    }
                }
            }
        }
        $combined_array = [$this->model->plant_code];
        foreach ($this->model->bmc_code as $code) {
            if (strpos($code, '#plant') !== false) {
                $plant_code_from_bmc = str_replace('#plant', '', $code);
                if ($this->model->plant_code != $plant_code_from_bmc) {
                    $combined_array[] = $plant_code_from_bmc;
                }
            }
        }
        $this->model->plant_code = $combined_array;
        return $this->customRender();
    }

    public function actionUpdateWithParty($id) {

        $this->model = TblVehicleTrip::find()
                ->with(['vehicleCode.transporter', 'vehicleTripDetailCode'])
                ->where(['vehicle_trip_code' => $id])
                ->one();

        if (!empty($this->model)) {
            $this->model->transporter_code = !empty($this->model->vehicleCode) ? $this->model->vehicleCode->transporter->transporter_code : '';
        }


        $type = Yii::$app->request->get('type');
        $this->model->type = !empty($type) ? $type : 'normal';
        $vehicleTripDetails = $this->model->vehicleTripDetailCode ?? [];

        $sourceBmc = array_map(function($item) {
            if (!empty($item->source_org_code) && !empty($item->source_org_type) && $item->is_virtual_location != 2) {
                if($item->source_org_type == 'bmc'){
                    return $item->source_org_code;
                } else if($item->is_virtual_location == 1){
                    return $item->source_org_code . '#' . strtolower($item->source_org_type) .'#conversion_vendor';
                } else {
                    return $item->source_org_code . '#' . strtolower($item->source_org_type);
                }
            }
            return null;
        }, $vehicleTripDetails);

        $this->model->bmc_code = array_values($sourceBmc);
        $this->viewFile = 'update';
        return $this->customRender();
    }
    
    public function actionVerticalChart($trip_code) {
        $tripTrack = TblVehicleTripTracking::find()
                ->where(['trip_code' => $trip_code])
                ->andWhere(['not', ['visibility_status' => 3]])
                ->orderBy(['sub_status_time' => SORT_ASC])
                ->all();

        $parsingNo = '';
        if (!empty($tripTrack) && isset($tripTrack[0]['vehicle_code'])) {
            $parsingNo = TblVehicleMaster::find()->where(['vehicle_code' => $tripTrack[0]['vehicle_code']])->one();
        }
        return $this->render('_vertical_chart', [
                    'tripTrack' => $tripTrack,
                    'parsingNo' => $parsingNo,
        ]);
    }

}
