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

/**
 * TblMilkVehicleEntryController implements the CRUD actions for TblMilkVehicleEntry model.
 */
class TblMilkVehicleEntryController extends \app\controllers\ChildController {

    public $freeAccessActions = ['transaction-detail'];

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
        if (Yii::$app->request->post()) {
            $update = FALSE;
            $this->model->load(Yii::$app->request->post());
            $masterPost = Yii::$app->request->post()['TblMilkVehicleEntry'];
            $trPost = Yii::$app->request->post()['TblMilkVehicleEntryTransaction'];

            $this->model->setAttributes($masterPost);
            $txn_model->setAttributes($trPost);
            if ($this->model->validate() && $txn_model->validate()) {
                if (!empty($masterPost['milk_vehicle_entry_code'])) {
                    $this->model = $this->findModel($masterPost['milk_vehicle_entry_code']);
                    $this->model->load(Yii::$app->request->post());
                } else {
                    $this->model->milk_vehicle_entry_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
                    $this->model->grn_no = Yii::$app->session->get('financialYear') . '/' . $this->model->trip_code . '/1';
                    $tripModel = new TblVehicleTrip();
                    $tripModel->trip_code = $this->model->trip_code;
                    $tripExist = $tripModel->getTripData();
                    $tripModel = $tripExist;
                    $tripModel->grn_no = $this->model->grn_no;
                    $tripModel->trip_status = 'closed';
                    $modelSave[] = $tripModel;
                }
                if (!empty($trPost['milk_vehicle_entry_transaction_code'])) {
                    $txnExist = TblMilkVehicleEntryTransaction::findOne($trPost['milk_vehicle_entry_transaction_code']);
                    $this->model->load(Yii::$app->request->post());
                    $historyModel = new TblMilkVehicleEntryTransactionHistory();
                    Yii::$app->operation->history($txnExist, $historyModel, UPDATE);
                    $modelSave[] = $historyModel;
                    $txn_model = $txnExist;
                    $txn_model->load(Yii::$app->request->post());
                    $update = TRUE;
                } else {
                    $txn_model->milk_vehicle_entry_transaction_code = (string) Yii::$app->general->getCodeAutoIncrement($txn_model);
                    $txn_model->milk_vehicle_entry_code = $this->model->milk_vehicle_entry_code;
                    $txn_model->grn_no = $this->model->grn_no;
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
        } else {
            return $this->render('create', [
                        'model' => $this->model, 'txn_model' => $txn_model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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
        $existData = TblBmcMilkDispatch::find()->where(['trip_code' => Yii::$app->request->get('trip_code')])->all();
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
                $data['status'] = 'success';
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData, 'source' => $sourceName, 'dest' => $destName]; //$this->renderAjax('_collection', ['model' => $model, 'modelData' => $modelData, 'type' => 'edit']);
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
        $response = [];
        $response['status'] = 'success';
        $response['data'] = '';
        $destName = '';
        $challan = Yii::$app->request->get('challan_no');
        $trip = Yii::$app->request->get('trip_code');
        $data = TblBmcMilkDispatch::find()->where(['challan_no' => $challan, 'trip_code' => $trip])->one();
        $sourceName = Yii::$app->general->getforeignkey($data->bmcCode, 'bmc_name');
        $rel = Yii::$app->general->getDestRelation($data->destination_type);
        $att = strtolower($data->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($data->destination_type) == 'vendor' ? 'customer_name' : 'name');
        if (!empty($rel)) {
            $destName = Yii::$app->general->getforeignkey($data->{$rel . 'Dest'}, $att);
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return [$response['status'], 'data' => $data, 'source' => $sourceName, 'dest' => $destName];
    }

}
