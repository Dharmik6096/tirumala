<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblDcsClosing;
use app\modules\collection\models\TblDcsClosingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblDcsClosingHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblDcsClosingController implements the CRUD actions for TblDcsClosing model.
 */
class TblDcsClosingController extends \app\controllers\ChildController {

    /**
     * Lists all TblDcsClosing models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsClosingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsClosing model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDcsClosing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDcsClosing();
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->dcs_closing_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->transaction_date = !empty($this->model->transaction_date) ? date('Y-m-d H:i:s', strtotime($this->model->transaction_date)) : NULL;
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift_code);

            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['DCS Closing', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblDcsClosing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsClosingHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->transaction_date = !empty($this->model->transaction_date) ? date('Y-m-d H:i:s', strtotime($this->model->transaction_date)) : NULL;
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift_code);

            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['DCS Closing', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblDcsClosing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblDcsClosingHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblDcsClosing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsClosing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsClosing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
