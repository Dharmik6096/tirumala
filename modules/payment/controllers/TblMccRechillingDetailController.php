<?php

namespace app\modules\payment\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\payment\models\TblMccRechillingDetail;
use app\modules\payment\models\TblMccRechillingDetailHistory;
use app\modules\payment\models\TblMccRechillingDetailSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMccRechillingDetailController implements the CRUD actions for TblMccRechillingDetail model.
 */
class TblMccRechillingDetailController extends ChildController {

    /**
     * Lists all TblMccRechillingDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccRechillingDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMccRechillingDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMccRechillingDetail();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->chilling_date = date('Y-m-d', strtotime($this->model->chilling_date));
            $this->model->chilling_date = $this->model->chilling_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Mcc Rechilling Detail', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMccRechillingDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblMccRechillingDetailHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->chilling_date = date('Y-m-d', strtotime($this->model->chilling_date));
            $this->model->chilling_date = $this->model->chilling_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);            
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Mcc Rechilling Detail', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblMccRechillingDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblMccRechillingDetailHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblMccRechillingDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMccRechillingDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccRechillingDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
