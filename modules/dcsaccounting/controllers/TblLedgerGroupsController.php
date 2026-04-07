<?php

namespace app\modules\dcsaccounting\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\dcsaccounting\models\TblLedgerGroups;
use app\modules\dcsaccounting\models\TblLedgerGroupsSearch;
use app\modules\dcsaccounting\models\TblLedgerGroupsHistory;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblLedgerGroupsController implements the CRUD actions for TblLedgerGroups model.
 */
class TblLedgerGroupsController extends ChildController {

    /**
     * Lists all TblLedgerGroups models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerGroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblLedgerGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblLedgerGroups();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $this->model->ledger_group_code = (String) Yii::$app->general->getCodeAutoIncrement($this->model);
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Ledger Groups', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblLedgerGroups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
            $historyModel = new TblLedgerGroupsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Ledger Groups', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblLedgerGroups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblLedgerGroupsHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblLedgerGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblLedgerGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerGroups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
