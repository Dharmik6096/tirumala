<?php

namespace app\modules\hardwareconfigutation\controllers;

use Yii;
use app\modules\hardwareconfigutation\models\TblUnionConfig;
use app\modules\hardwareconfigutation\models\TblUnionConfigSearch;
use app\modules\hardwareconfigutation\models\TblUnionConfigHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblUnionConfigController implements the CRUD actions for TblUnionConfig model.
 */
class TblUnionConfigController extends \app\controllers\ChildController
{

    /**
     * Lists all TblUnionConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblUnionConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblUnionConfig model.
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
     * Creates a new TblUnionConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblUnionConfig();
        $this->viewFile = 'create';
        
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->union_config_code = $this->model->getCode();
            $this->model->union_code = Yii::$app->session->get('organizations_code');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['general union configuration', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        } 
        return $this->customRender();
    }

    /**
     * Updates an existing TblUnionConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblUnionConfigHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['general union configuration', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        } 
        return $this->customRender();
    }

    /**
     * Deletes an existing TblUnionConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_union_config', '', '','','', Yii::$app->request->post('id'), 'union_config_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel=new TblUnionConfigHistory();
            Yii::$app->operation->history($this->model,$historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblUnionConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblUnionConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblUnionConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
