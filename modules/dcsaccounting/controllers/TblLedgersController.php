<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgers;
use app\modules\dcsaccounting\models\TblLedgersSearch;
use app\modules\dcsaccounting\models\TblLedgersHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\dcsaccounting\models\TblLedgerSubLedgersMappingSearch;

/**
 * TblLedgersController implements the CRUD actions for TblLedgers model.
 */
class TblLedgersController extends ChildController {

    /**
     * Lists all TblLedgers models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblLedgers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblLedgers();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $this->model->ledger_code = (String) Yii::$app->general->getCodeAutoIncrement($this->model);
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Ledgers', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblLedgers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
            $historyModel = new TblLedgersHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Ledgers', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblLedgers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblLedgersHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Displays a single TblLedgers model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblLedgerSubLedgersMappingSearch();
        $searchModel->ledger_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
