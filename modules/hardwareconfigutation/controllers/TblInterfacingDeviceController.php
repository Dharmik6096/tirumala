<?php

namespace app\modules\hardwareconfigutation\controllers;

use Yii;
use app\modules\hardwareconfigutation\models\TblInterfacingDevice;
use app\modules\hardwareconfigutation\models\TblInterfacingDeviceSearch;
use app\modules\hardwareconfigutation\models\TblInterfacingDeviceHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblInterfacingDeviceController implements the CRUD actions for TblInterfacingDevice model.
 */
class TblInterfacingDeviceController extends \app\controllers\ChildController
{
    /**
     * Lists all TblInterfacingDevice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblInterfacingDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblInterfacingDevice model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblInterfacingDevice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblInterfacingDevice();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->device_name = ucwords($this->model->device_name);
            $this->model->interfacing_device_code = $this->model->getCode();
            $this->model->union_code = Yii::$app->session->get('organizations_code');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Interface Device', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblInterfacingDevice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblInterfacingDeviceHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->device_name = ucwords($this->model->device_name);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Interface Device', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        } 
        return $this->customRender();
    }

    /**
     * Deletes an existing TblInterfacingDevice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_interfacing_device', '', '','','', Yii::$app->request->post('id'), 'interfacing_device_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel=new TblInterfacingDeviceHistory();
            Yii::$app->operation->history($this->model,$historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblInterfacingDevice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblInterfacingDevice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblInterfacingDevice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
