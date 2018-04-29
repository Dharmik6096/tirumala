<?php

namespace app\modules\globalmaster\controllers;

use Yii;
use app\modules\globalmaster\models\TblVehicleType;
use app\modules\globalmaster\models\TblVehicleTypeSearch;
use app\modules\globalmaster\models\TblVehicleTypeHistory;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblVehicleTypeController implements the CRUD actions for TblVehicleType model.
 */
class TblVehicleTypeController extends ChildController
{
       /**
     * Lists all TblVehicleType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVehicleTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleType model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblVehicleType();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->vehicle_type_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Type', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();        
    }

    /**
     * Updates an existing TblVehicleType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleTypeHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction =$this->generalModel->saveTransaction([$this->model, $historyModel], ['Vehicle type', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();        
    }

    /**
     * Deletes an existing TblVehicleType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
       $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_vehicle_type', Yii::$app->request->post('id'), 'vehicle_type_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblVehicleTypeHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVehicleType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVehicleType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
