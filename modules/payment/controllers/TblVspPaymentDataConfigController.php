<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblVspPaymentDataConfig;
use app\modules\payment\models\TblVspPaymentDataConfigSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblVspPaymentDataConfigHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblVspPaymentDataConfigController implements the CRUD actions for TblVspPaymentDataConfig model.
 */
class TblVspPaymentDataConfigController extends ChildController {

    /**
     * Lists all TblVspPaymentDataConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVspPaymentDataConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVspPaymentDataConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVspPaymentDataConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVspPaymentDataConfig();
        $saveModel = [];
        $this->viewFile = 'create';
        $saveModel[] = $this->model;
        if ($this->model->load(\Yii::$app->request->post())) {
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Data Consider in Vsp Payment', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVspPaymentDataConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $saveModel = [];
        if (Yii::$app->request->post()) {
            $historyModel = new TblVspPaymentDataConfigHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $saveModel[] = $historyModel;
            $this->model->load(Yii::$app->request->post());
            $this->model->date_time_of_collection = Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->shift_code);
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Data Consider in Vsp Payment', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVspPaymentDataConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {

        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblVspPaymentDataConfigHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVspPaymentDataConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVspPaymentDataConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVspPaymentDataConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
