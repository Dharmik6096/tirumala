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

/**
 * TblVehicleMasterController implements the CRUD actions for TblVehicleMaster model.
 */
class TblVehicleMasterController extends \app\controllers\ChildController {

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

}
