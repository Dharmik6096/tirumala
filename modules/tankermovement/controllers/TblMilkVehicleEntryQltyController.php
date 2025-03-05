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

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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
        $searchModel->load(\Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, FALSE);
        $searchModel->parsing_no = TblVehicleTrip::find()->alias('vt')->joinWith('vehicleCode vm')->where(['vt.trip_code' => $searchModel->trip_code, 'vt.is_active' => 1])->select('vm.parsing_no')->scalar();
        $dataProviderCount = $dataProvider->getCount();
        return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderCount' => $dataProviderCount,
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
        $res = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $saveModel = [];
            $milkVehicleEntryQltyData = $this->findModel($model->chamber_no);
            $historyModel = new TblMilkVehicleEntryQltyHistory();
            Yii::$app->operation->history($milkVehicleEntryQltyData, $historyModel, UPDATE);
            $saveModel[] = $historyModel;
            foreach (['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'] as $attr) {
                $milkVehicleEntryQltyData->$attr = $model->$attr;
            }
            $milkVehicleEntryQltyData->status = 'done';
            $milkVehicleEntryQltyData->status_datetime = date('Y-m-d H:i:s');

            $saveModel[] = $milkVehicleEntryQltyData;
            $query = $model->find()->where(['!=', 'status', 'discarded'])->andWhere(['not in', 'milk_vehicle_entry_qlty_code', $milkVehicleEntryQltyData->milk_vehicle_entry_qlty_code]);
            $totalCount = $query->count();
            $doneCount = $query->andWhere(['status' => 'done'])->count();
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

            $transaction = $this->generalModel->saveTransaction($saveModel, ['Tanker Milk Quality', 'edit']);
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
        $historyModel = new TblMilkVehicleEntryQltyHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $saveModel[] = $historyModel;
        foreach (['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'] as $attribute) {
            $this->model->$attribute = 0;
        }
        $this->model->status = 'pending';
        $this->model->status_datetime = date('Y-m-d H:i:s');
        $saveModel[] = $this->model;

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

        $transaction = $this->generalModel->saveTransaction($saveModel, ['Tanker Milk Quality', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Tanker Milk Quality Reset Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Tanker Milk Quality Not Reset.'];
        }

        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
