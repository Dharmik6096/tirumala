<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblVehicleTripDetailSearch;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxnSearch;
use app\modules\tankermovement\models\TblBmcDispatchConsolidated;
use app\modules\tankermovement\models\TblBmcDispatchConsolidatedTxn;
use app\modules\tankermovement\models\TblPartyMaster;
use app\modules\tankermovement\models\TblVehicleQaInspection;
use app\modules\tankermovement\models\TblVehicleQaInspectionHistory;
use app\modules\tankermovement\models\TblVehicleTripDetail;
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
            if (count($bmc_array) > 2) {
                $sl_detail = explode('#', $bmc_array[0]);
                $sl_code = $sl_detail[0];
                $sl_type = !empty($sl_detail[1]) ? $sl_detail[1] : 'bmc';

                $el_detail = explode('#', $bmc_array[count($bmc_array) - 1]);
                $el_code = $el_detail[0];
                $el_type = !empty($el_detail[1]) ? $el_detail[1] : 'bmc';

                $is_valid_trip = ($sl_type == 'plant' && $el_type == 'plant') ? TRUE : FALSE;
                if ($this->model->validate() && $is_valid_trip) {
                    $this->model->plant_code = $sl_code;
                    $fl_detail = explode('#', $bmc_array[1]);
                    $this->model->fl_code = $fl_detail[0];
                    $this->model->fl_type = !empty($fl_detail[1]) ? $fl_detail[1] : 'bmc';
                    if ($this->model->fl_type == 'bmc') {
                        $this->model->bmc_code = $this->model->fl_code;
                        $this->model->mcc_plant_code = $this->model->bmcCode->bmc_code;
                    } else {
                        $this->model->bmc_code = NULL;
                        $this->model->mcc_plant_code = NULL;
                    }
                }
            }
            if (!$is_valid_trip) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Vehicle Trip Must be Start and End at Plant.'
                ]);
            }
            $this->model->trip_mode = 'offline';
            $this->model->trip_for = ($type == 'party') ? 'salesparty' : 'bmcdispatch';
            $this->model->trip_sub_status = 'generated';
            $this->model->sub_status_time = date('Y-m-d H:i:s');
            if ($is_valid_trip) {
                $result = $this->model->setModel();
                if ($result[0]) {
                    $validate = TRUE;
                    $save_model = $result[1];
                    if (!empty($save_model) && $this->model->fl_type == 'plant') {
                        $save_model[0]->plant_code = $this->model->fl_code;
                    }
                    // $lastIndex = count($bmc_array) - 2;
                    foreach ($bmc_array as $key => $bmc) {
                        $trip_detai = new TblVehicleTripDetail();
                        if ($key == 0) {
                            continue;
                        }
                        // if ($lastIndex == $key) {
                        //     $trip_detai->is_last_destination = 1;
                        // }
                        $sloc_detail = explode('#', $bmc);
                        if (!empty($bmc_array[$key + 1])) {
                            $dloc_detail = explode('#', $bmc_array[$key + 1]);
                            $trip_detai->destination_code = $dloc_detail[0];
                            $trip_detai->destination_type = !empty($dloc_detail[1]) ? $dloc_detail[1] : 'bmc';
                        } else {
                            $trip_detai->is_last_destination = 1;
                        }

                        $trip_detai->source_org_code = $sloc_detail[0];
                        $trip_detai->source_org_type = !empty($sloc_detail[1]) ? $sloc_detail[1] : 'bmc';

                        $trip_detai->originating_org_code = $this->model->union_code;
                        $trip_detai->vehicle_trip_code = $this->model->vehicle_trip_code;
                        $trip_detai->vehicle_code = $this->model->vehicle_code;
                        $trip_detai->transaction_datetime = date('Y-m-d H:i:s');
                        $trip_detai->trip_code = $this->model->trip_code;
                        $trip_detai->vehicle_trip_detail_code = $trip_detai->vehicle_trip_code . 'T' . ($key + 2);
                        $trip_detai->scenario = 'on_crete_trip';
                        if (!$trip_detai->validate()) {
                            $validate = FALSE;
                            $errors = $trip_detai->getErrors();
                            if (isset($errors['destination_code'])) {
                                $this->model->addError('bmc_code', $errors['destination_code'][0]);
                            }
                        }
                        $save_model[] = $trip_detai;
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
                            Yii::$app->general->setVehicleTripTrackingDetail($save_model[0], $remarks);
                            $tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'tanker_movement_with_trip_sub_status', 'PORTAL');
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
        $this->model->bmc_code = $bmc_array;
        return $this->customRender();
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
        $transaction = $this->generalModel->saveTransaction([$tripModel, $historyModel], ['Vehicle Trip Status', 'edit']);
        $msg = Yii::$app->getSession()->getFlash('success')['message'];
        if ($transaction == 'customRedirect') {
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

                $trip = new TblVehicleTripDetail();
                $data = $trip->getOpenTripList($parents[0], $parents[1], $parents[2], $tripCode);
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
            $vehicleData = TblVehicleMaster::find()->select(['driver_name', 'driver_contact_no'])->where(['vehicle_code' => $postData['vehicle_code']])->one();
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
            if (!empty($postData['arrival_time']) && $actionType == 'gate-in') {
                $tripDetail->arrival_time = $trip->sub_status_time = date('Y-m-d H:i:s', strtotime($postData['arrival_time']));
                $trip->trip_sub_status = $tripDetail->is_last_destination ? 'plant_lot_pending' : 'get_in';
                $remarks = $tripDetail->in_remarks;
            } elseif (!empty($postData['departure_time']) && $actionType == 'gate-out') {
                $tripDetail->departure_time = $trip->sub_status_time = date('Y-m-d H:i:s', strtotime($postData['departure_time']));
                if (substr($vehicle_trip_detail_code, -2) == 'T1' && !empty($tripDetail->departure_time)) {
                    $tripDetail->arrival_time = $tripDetail->departure_time;
                }
                $remarks = $tripDetail->out_remarks;
                $trip->trip_sub_status = 'get_out';
            }
            if ($tripDetail->validate()) {
                $models = [$tripDetail, $trip];
                $transaction = $this->generalModel->saveTransaction($models, ['Trip Detail', 'edit']);
                if ($transaction == 'customRedirect') {
                    $response = Yii::$app->general->getColumnName($tripDetail->source_org_type);
                    if (!empty($response['rel'])) {
                        $sourceData = $tripDetail->{$response['rel'] . 'Source'};
                        $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']} . '-' . $remarks;
                    }
                    Yii::$app->general->setVehicleTripTrackingDetail($trip, $remarks);
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
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMap($trip_code) {
        $tripTrack = TblVehicleTripTracking::find()->where(['trip_code' => $trip_code])->all();
        return $this->render('_map', [
                    'tripTrack' => $tripTrack,
        ]);
    }

}
