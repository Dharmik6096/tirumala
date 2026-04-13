<?php

namespace app\modules\geo\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\geo\models\TblProject;
use app\modules\geo\models\TblProjectHistory;
use app\modules\geo\models\TblProjectSearch;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TblProjectController implements the CRUD actions for TblProject model.
 */
class TblProjectController extends ChildController {

    /**
     * Lists all TblProject models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblProject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblProject();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->project_name = ucwords($this->model->project_name);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Project', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', ['model' => $this->model]);
    }

    /**
     * Updates an existing TblProject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $historyModel = new TblProjectHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->project_name = ucwords($this->model->project_name);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Project', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', ['model' => $this->model]);
    }

    /**
     * Deletes an existing TblProject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblProjectHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblProject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblProject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
