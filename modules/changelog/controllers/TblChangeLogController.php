<?php

namespace app\modules\changelog\controllers;

use Yii;
use app\modules\changelog\models\TblChangeLog;
use app\modules\changelog\models\TblChangeLogSearch;
use app\modules\changelog\models\TblChangeLogHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblChangeLogController implements the CRUD actions for TblChangeLog model.
 */
class TblChangeLogController extends \app\controllers\ChildController
{
    /**
     * Lists all TblChangeLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblChangeLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblChangeLog model.
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
     * Creates a new TblChangeLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblChangeLog();
        $this->viewFile = 'create';
        
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Change Log', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblChangeLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */  
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {

            $historyModel = new TblChangeLogHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Change Log', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblChangeLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_change_log',  Yii::$app->request->post('id'), 'log_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblChangeLogHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblChangeLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblChangeLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblChangeLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
