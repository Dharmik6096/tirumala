<?php

namespace app\modules\hardwareconfigutation\controllers;

use Yii;
use app\modules\hardwareconfigutation\models\TblInterfacingDeviceMapping;
use app\modules\hardwareconfigutation\models\TblInterfacingDeviceMappingHistory;
use app\modules\hardwareconfigutation\models\TblInterfacingDeviceMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblInterfacingDeviceMappingController implements the CRUD actions for TblInterfacingDeviceMapping model.
 */
class TblInterfacingDeviceMappingController extends \app\controllers\ChildController {

    /**
     * Lists all TblInterfacingDeviceMapping models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblInterfacingDeviceMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblInterfacingDeviceMapping model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblInterfacingDeviceMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblInterfacingDeviceMapping();
        $this->model = new TblInterfacingDeviceMapping();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->interfacing_device_mapping_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Device Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblInterfacingDeviceMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblInterfacingDeviceMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Device Mapping', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblInterfacingDeviceMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        if (Yii::$app->request->post()) {
            $historyModel = new TblInterfacingDeviceMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $this->model->load(Yii::$app->request->post());
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblInterfacingDeviceMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblInterfacingDeviceMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblInterfacingDeviceMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
