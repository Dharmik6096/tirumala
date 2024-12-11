<?php

namespace app\modules\organisation\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\organisation\models\TblOrganizationLatlong;
use app\modules\organisation\models\TblOrganizationLatlongHistory;
use app\modules\organisation\models\TblOrganizationLatlongSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblOrganizationLatlongController implements the CRUD actions for TblOrganizationLatlong model.
 */
class TblOrganizationLatlongController extends ChildController
{
    /**
     * Lists all TblOrganizationLatlong models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblOrganizationLatlongSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblOrganizationLatlong model.
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
     * Creates a new TblOrganizationLatlong model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblOrganizationLatlong();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->scenario = 'create';
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Organization lat long', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblOrganizationLatlong model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblOrganizationLatlongHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Organization lat long', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblOrganizationLatlong model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblOrganizationLatlongHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionChangeStatus($id) {
        $message = 'Activated';
        $this->model = $this->findModel($id);
        $historyModel = new TblOrganizationLatlongHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        if($this->model->is_active == 0){
            $this->model->is_active = 1;
        } else {
            $message = 'Deactivated';
            $this->model->is_active = 0;
        }
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Organization lat long', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Organization lat long '.$message.' Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Organization lat long Not '.$message];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblOrganizationLatlong model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOrganizationLatlong the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblOrganizationLatlong::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
