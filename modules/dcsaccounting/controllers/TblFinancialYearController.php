<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblFinancialYear;
use app\modules\dcsaccounting\models\TblFinancialYearSearch;
use app\modules\dcsaccounting\models\TblFinancialYearHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblFinancialYearController implements the CRUD actions for TblFinancialYear model.
 */
class TblFinancialYearController extends \app\controllers\ChildController
{
  
    /**
     * Lists all TblFinancialYear models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblFinancialYearSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblFinancialYear model.
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
     * Creates a new TblFinancialYear model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblFinancialYear();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->id = Yii::$app->general->getCodeAutoIncrement($this->model);
            if($this->model->validate()){
                $this->model->starting_date=Yii::$app->formatter->asDate($this->model->starting_date,DATE_FORMAT);
                $this->model->ending_date=Yii::$app->formatter->asDate($this->model->ending_date,DATE_FORMAT);

                $transaction = $this->generalModel->saveTransaction([$this->model], ['Financial Year', 'create']);
                if ($transaction !== FALSE) {
                    if($transaction=='customRender'){
                        $this->model->starting_date=date('d-m-Y', strtotime($this->model->starting_date));
                        $this->model->ending_date=date('d-m-Y', strtotime($this->model->ending_date));
                    }
                    return $this->{$transaction}();
            }
           }
        }
        
        return $this->customRender();
    }

    /**
     * Updates an existing TblFinancialYear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            
            $historyModel = new TblFinancialYearHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            
            $this->model->load(Yii::$app->request->post());
            if($this->model->validate()){
                $this->model->starting_date=Yii::$app->formatter->asDate($this->model->starting_date,DATE_FORMAT);
                $this->model->ending_date=Yii::$app->formatter->asDate($this->model->ending_date,DATE_FORMAT);
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Financial Year', 'edit']);
                if ($transaction !== FALSE) {
                    if($transaction=='customRender'){
                        $this->model->starting_date=date('d-m-Y', strtotime($this->model->starting_date));
                        $this->model->ending_date=date('d-m-Y', strtotime($this->model->ending_date));
                    }
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblFinancialYear model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_dcs_accounting', ['tbl_financial_year', '', '','','', Yii::$app->request->post('id'), 'id']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel=new TblFinancialYearHistory();
            Yii::$app->operation->history($this->model,$historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblFinancialYear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblFinancialYear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblFinancialYear::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
