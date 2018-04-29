<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMemberClassification;
use app\modules\dcsoperation\models\TblMemberClassificationSearch;
use app\modules\dcsoperation\models\TblMemberClassificationHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMemberClassificationController implements the CRUD actions for TblMemberClassification model.
 */
class TblMemberClassificationController extends \app\controllers\ChildController
{

    /**
     * Lists all TblMemberClassification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMemberClassificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberClassification model.
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
     * Creates a new TblMemberClassification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblMemberClassification();
        $this->viewFile = 'create';
        
        if ($this->model->load(Yii::$app->request->post()) ) {
            $this->model->member_classification_code = $this->model->getCode();
            $this->model->member_classification_name = ucwords($this->model->member_classification_name);
            $localModelList = [];
            $transaction = $this->generalModel->saveTransaction([$this->model], $localModelList, ['member classification', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    
    

    /**
     * Updates an existing TblMemberClassification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        

        if (Yii::$app->request->post()) {
            $historyModel = new TblMemberClassificationHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->member_classification_name = ucwords($this->model->member_classification_name);
            
            $localList = [];
            
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $localList, ['member classification', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblMemberClassification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_member_classification', Yii::$app->request->post('id'), 'member_classification_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $localHistory = new TblMemberClassificationHistory();
            Yii::$app->operation->history($this->model, $localHistory, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $localHistory]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblMemberClassification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMemberClassification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMemberClassification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
