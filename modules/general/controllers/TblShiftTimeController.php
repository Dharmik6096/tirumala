<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblShiftTime;
use app\modules\general\models\TblShiftTimeSearch;
use app\modules\general\models\TblShiftTimeHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
/**
 * TblShiftTimeController implements the CRUD actions for TblShiftTime model.
 */
class TblShiftTimeController extends \app\controllers\ChildController
{
    
    /**
     * Lists all TblShiftTime models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblShiftTimeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblShiftTime model.
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
     * Creates a new TblShiftTime model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
       $this->model = new TblShiftTime();
        $this->viewFile = 'create';
        
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date=Yii::$app->formatter->asDate($this->model->wef_date,DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Shift Time', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblShiftTime model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {

            $historyModel = new TblShiftTimeHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);

            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Shift Time', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblShiftTime model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_shift_time',  Yii::$app->request->post('id'), 'shift_time_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblShiftTimeHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblShiftTime model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblShiftTime the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblShiftTime::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    private function setModel() {
        $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);        
    }
}
