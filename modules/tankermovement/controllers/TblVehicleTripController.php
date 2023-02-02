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
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblVehicleTripHistory;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblVehicleTripController implements the CRUD actions for TblVehicleTrip model.
 */
class TblVehicleTripController extends \app\controllers\ChildController {

    public $freeAccessActions = ['open-trip-list'];

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
        $this->model->transaction_date = date('Y-m-d');
        $this->viewFile = 'create';
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
                $this->model->bmc_code = $bmc_array;
            } else {
                $this->model->bmc_code = NULL;
            }
            $bmc_array = $this->model->bmc_code;
            if (!empty($bmc_array)) {
                $this->model->bmc_code = $bmc_array[0];
                $this->model->mcc_plant_code = $this->model->bmcCode->bmc_code;
            }
            $this->model->trip_mode = 'offline';
            if ($this->model->validate()) {
                $result = $this->model->setModel();
                if ($result[0]) {
                    $validate = TRUE;
                    $save_model = $result[1];
                    foreach ($bmc_array as $key => $bmc) {
                        $trip_detai = new TblVehicleTripDetail();
                        if ($key == count($bmc_array) - 1) {
                            $trip_detai->source_org_code = $bmc;
                            $trip_detai->source_org_type = 'bmc';
                            $trip_detai->destination_code = $this->model->dest_plant_code;
                            $trip_detai->destination_type = 'plant';
                        } else {
                            $trip_detai->source_org_code = $bmc;
                            $trip_detai->source_org_type = 'bmc';
                            $trip_detai->destination_code = $bmc_array[$key + 1];
                            $trip_detai->destination_type = 'bmc';
                        }
                        $trip_detai->originating_org_code = $this->model->union_code;
                        $trip_detai->vehicle_trip_code = $this->model->vehicle_trip_code;
                        $trip_detai->vehicle_code = $this->model->vehicle_code;
                        $trip_detai->transaction_datetime = date('Y-m-d H:i:s');
                        $trip_detai->trip_code = $this->model->trip_code;
                        $trip_detai->arrival_time = date('Y-m-d H:i:s');
                        $trip_detai->vehicle_trip_detail_code = $trip_detai->vehicle_trip_code . 'T' . ($key + 2);
                        if (!$trip_detai->validate()) {
                            $validate = FALSE;
                            $errors = $trip_detai->getErrors();
                            if (isset($errors['destination_code'])) {
                                $this->model->addError('bmc_code', $errors['destination_code'][0]);
                            }
                        }
                        $save_model[] = $trip_detai;
                    }
                    if ($validate) {
                        $transaction = $this->generalModel->saveTransaction($save_model, ['Vehicle Trip with Trip No. ' . $result[2]['trip_code'], 'create']);
                        if ($transaction == 'customRedirect') {
                            if ($result[2]['inspection_require']) {
                                return $this->redirect(['/tankermovement/tbl-bmc-dispatch-inspection/create',
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
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Dispatch Detail not found.']);
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

}
