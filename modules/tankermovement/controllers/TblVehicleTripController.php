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

/**
 * TblVehicleTripController implements the CRUD actions for TblVehicleTrip model.
 */
class TblVehicleTripController extends \app\controllers\ChildController {

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

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->trip_mode = 'offline';
            $result = $this->model->setModel();
            if ($result[0]) {
                $transaction = $this->generalModel->saveTransaction($result[1], ['Vehicle Trip with Trip No. ' . $result[2]['trip_code'], 'create']);
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
                    $dispact_consolidate->kf_snf = $model->kg_snf;
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

}
