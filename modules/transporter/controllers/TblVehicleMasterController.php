<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\transporter\models\TblVehicleMasterSearch;
use app\modules\transporter\models\TblVehicleMasterHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\transporter\models\TblTransporter;
use app\modules\transporter\models\TblVehicleWiseQtyFlag;
use app\modules\document\controllers\TblAttachmentController;
use app\modules\transporter\models\TblVehicleCompartmentDetail;
use app\modules\transporter\models\TblVehicleCompartmentDetailSearch;
use app\modules\transporter\models\TblVehicleCompartmentDetailHistory;
use yii\helpers\Url;

/**
 * TblVehicleMasterController implements the CRUD actions for TblVehicleMaster model.
 */
class TblVehicleMasterController extends \app\controllers\ChildController {

    public $freeAccessActions = ['depend-vehicles', 'get-chamber-list', 'vehicle-open-list', 'get-vehicle-transpoter', 'get-vehicle-detail', 'get-vehicle-list'];

    /**
     * Lists all TblVehicleMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblVehicleMasterSearch();
        $searchModel->vehicle_code = $id;
        $dataProvider = $searchModel->searchwiseflag(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVehicleMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleMaster();
        $this->viewFile = 'create';
        $saveModel = [];
        Yii::$app->default->getDefaults($this->model);
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->setModel($this->model);
            $this->model->vehicle_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $saveModel[] = $this->model;

            if (in_array($this->model->vehicle_use_type, [1, 2])) {
                $this->model->scenario = 'vehicleWiseFlag';
                $childModel = new TblVehicleWiseQtyFlag();
                $childModel->vehicle_wise_qty_flag_code = Yii::$app->general->getCodeAutoIncrement($childModel);
                $childModel->vehicle_code = $this->model->vehicle_code;
                $childModel->qty_flag = $this->model->billing_qty_flag;
                $childModel->wef_date = ($this->model->flag_wef_date) ? Yii::$app->formatter->asDate($this->model->flag_wef_date, DATE_FORMAT) : NULL;
                $saveModel[] = $childModel;
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Vehicle', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $childModel = new TblVehicleWiseQtyFlag();
        $flagData = $childModel->getExistingData($this->model);
        if (!empty($flagData)) {
            $this->model->billing_qty_flag = $flagData->qty_flag;
            $this->model->flag_wef_date = $flagData->wef_date;
        }
        $saveModel = [];
        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $saveModel[] = $this->model;
            $saveModel[] = $historyModel;
            if (in_array($this->model->vehicle_use_type, [1, 2])) {
                $this->model->scenario = 'vehicleWiseFlag';
                $childModel = new TblVehicleWiseQtyFlag();
                $childModel->vehicle_code = $this->model->vehicle_code;
                $childModel->qty_flag = $this->model->billing_qty_flag;
                $childModel->wef_date = ($this->model->flag_wef_date) ? Yii::$app->formatter->asDate($this->model->flag_wef_date, DATE_FORMAT) : NULL;
                $flagData = $childModel->getExistingFlag();
                if (empty($flagData)) {
                    $childModel->vehicle_wise_qty_flag_code = Yii::$app->general->getCodeAutoIncrement($childModel);
                    $saveModel[] = $childModel;
                }
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Vehicle', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVehicleMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_vehicle_master', Yii::$app->request->post('id'), 'vehicle_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblVehicleMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVehicleMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVehicleMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->vehicle_code]);
    }

    private function setModel() {
        $this->model->driver_name = ucwords($this->model->driver_name);
        $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
        $this->model->expiry_date = ($this->model->expiry_date == '') ? null : Yii::$app->formatter->asDate($this->model->expiry_date, DATE_FORMAT);
        $this->model->licence_expiry_date = ($this->model->licence_expiry_date == '') ? null : Yii::$app->formatter->asDate($this->model->licence_expiry_date, DATE_FORMAT);
    }

    public function actionDependVehicles() {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $transporter_code = $parents[0];
                //$out = self::getSubCatList($cat_id); 
                $vehicles = new TblVehicleMaster();
                $out = $vehicles->allVehicle($parents);

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionBillingType() {
        $tr_code = Yii::$app->request->post('transporter_code');
        $model = new TblVehicleMaster();
        $model->transporter_code = $tr_code;
        $data = Yii::$app->general->getforeignkey($model->transporter, 'billing_type_code');
        if (!empty($data)) {
            return Json::encode(['status' => 'success', 'data' => $data]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionVehicleActivate($id) {
        $this->model = $this->findModel($id);
        $HistoryModel = new TblVehicleMasterHistory();
        Yii::$app->operation->history($this->model, $HistoryModel, UPDATE);
        $this->model->scenario = 'activation';
        $this->model->is_active = 1;
        $transaction = $this->generalModel->saveTransaction([$this->model, $HistoryModel], ['Vehicle', 'edit']);
        if ($transaction == 'customRedirect') {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Vehicle Activated successfully.']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not Activate. Please try again.']);
        }
        return $this->redirect(['index']);
    }

    public function actionVehicleDeactivate($id) {
        $this->model = $this->findModel($id);
        $HistoryModel = new TblVehicleMasterHistory();
        Yii::$app->operation->history($this->model, $HistoryModel, UPDATE);
        $this->model->scenario = 'activation';
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $HistoryModel], ['Vehicle', 'edit']);
        if ($transaction == 'customRedirect') {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Vehicle Deactivated Successfully.']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not Deactivated. Please try again.']);
        }
        return $this->redirect(['index']);
    }

    public function actionVehicleDocumentUpload($id) {
        $model = $this->findModel($id);
        $module_code = $model->vehicle_code;
        $module_name = 'tbl_vehicle_master';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('vehicle', $id, $model, $module_code, $module_name);
    }

    public function actionGetChamberList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $this->model = new TblVehicleCompartmentDetail();
                $this->model->vehicle_code = $parents[0];
                $data = $this->model->getChamberList();
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
            }
        }
        return Json::encode(['output' => $out, 'selected' => '']);
    }

    public function actionCompartmentDetail($id) {
        $model = $this->findModel($id);
        $comp_detail_model = new TblVehicleCompartmentDetail();
        if (Yii::$app->request->post()) {
            $comp_detail_model->load(Yii::$app->request->post());
            $comp_detail_model->vehicle_code = $model->vehicle_code;
            $comp_detail_model->union_code = $model->union_code;
            if ($comp_detail_model->validate() && empty($comp_detail_model->getErrors())) {
                $transaction = $this->generalModel->saveTransaction([$comp_detail_model], ['Compartment', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(Url::previous());
                }
            }
        }
        $detailsearchModel = new TblVehicleCompartmentDetailSearch();
        $detailsearchModel->vehicle_code = $id;
        $detaildataProvider = $detailsearchModel->search(Yii::$app->request->queryParams);
        return Yii::$app->controller->render('compartment_detail', [
                    'model' => $model,
                    'comp_detail_model' => $comp_detail_model,
                    'detailsearchModel' => $detailsearchModel,
                    'detaildataProvider' => $detaildataProvider,
        ]);
    }

    public function actionDeleteCompartment() {
        $comp_detail_model = TblVehicleCompartmentDetail::find()->where(['vehicle_compartment_detail_code' => Yii::$app->request->post('id')])->one();
        $deleteModel = [];
        $saveModel = [];
        $historyModel = new TblVehicleCompartmentDetailHistory();
        Yii::$app->operation->history($comp_detail_model, $historyModel, DELETE);
        $deleteModel[] = $comp_detail_model;
        $saveModel[] = $historyModel;
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(Url::previous());
        }
    }

    public function actionGetVehicleTranspoter() {
        $status = 'error';
        $vehicleData = [];
        $postData = Yii::$app->request->post();
        if (!empty($postData['vehicle_code'])) {
            $vehicleData = TblVehicleMaster::find()->select(['transporter_code'])->where(['vehicle_code' => $postData['vehicle_code']])->one();
            $status = 'success';
        }
        $record = ['status' => $status, 'data' => $vehicleData];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionVehicleOpenList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2])) {
                $milkReceipt = !empty($parents[3]) && ($parents[3] == 'receipt') ? TRUE : FALSE;
                $unionCode = $parents[1];
                $transaction_date = (isset($parents[4]) && !empty($parents[4])) ? date('Y-m-d', strtotime($parents[4])) : date('Y-m-d');
                $vehicleTripDetailModel = new TblVehicleMaster();
                $list = $vehicleTripDetailModel->getVehicleMaster($unionCode, $parents[2], $parents[0], $milkReceipt, $transaction_date);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
            }
        }
        return Json::encode(['output' => '', 'selected' => []]);
    }
    
    public function actionGetVehicleList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && (!empty($parents[1]) || $parents[1] == '0')) {
                $this->model = new TblVehicleMaster();
                $this->model->union_code = $parents[0];
                if (in_array($parents[1], ['cleaning_inspection', 'qa_inspection'])) {
                    $subStatus = $parents[1] == 'cleaning_inspection' ? 'cleaning_pending' : 'qa_pending';
                    $data = $this->model->getClosedVehicleList($subStatus);
                } else {
                    $this->model->vehicle_use_type = $parents[1];
                    $data = $this->model->getVehicleList();
                }
                foreach ($data as $key => $val) {
                    $out[] = ['id' => $key, 'name' => $val];
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
            $vehicleData = TblVehicleMaster::find()->select(['transporter_code'])->where(['vehicle_code' => $postData['vehicle_code']])->one();
            $status = 'success';
        }
        $record = ['status' => $status, 'data' => $vehicleData];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionVehicleForEligibleTrip() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $vehicleTripDetailModel = new TblVehicleMaster();
                $list = $vehicleTripDetailModel->getVehicleListForEligibleTrip($parents[0]);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
            }
        }
        return Json::encode(['output' => '', 'selected' => []]);
    }

}
