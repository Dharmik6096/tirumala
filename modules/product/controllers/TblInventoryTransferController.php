<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblInventoryTransfer;
use app\modules\product\models\TblInventoryTransferTxn;
use app\modules\product\models\TblInventoryTransferHistory;
use app\modules\product\models\TblInventoryTransferSearch;
use app\modules\product\models\TblInventoryTransferTxnSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblInventoryTransferController implements the CRUD actions for TblInventoryTransfer model.
 */
class TblInventoryTransferController extends \app\controllers\ChildController {

    /**
     * Lists all TblInventoryTransfer models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblInventoryTransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblInventoryTransfer model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblInventoryTransferTxn();
        $searchModel->inventory_transfer_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblInventoryTransfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblInventoryTransfer();
        $searchModel = new TblInventoryTransferTxnSearch();
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->get());
        $txModel = new TblInventoryTransferTxn();
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Inventory Transfer';
        $type = 'create';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->inventory_transfer_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->inventory_transfer_date = !empty($this->model->inventory_transfer_date) ? date('Y-m-d', strtotime($this->model->inventory_transfer_date)) : '';

            $txModel->load(Yii::$app->request->post());
            $txModel->inventory_transfer_txn_code = (string) Yii::$app->general->getCodeAutoIncrement($txModel);
            $txModel->inventory_transfer_code = $this->model->inventory_transfer_code;


            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                array_push($modelSave, $this->model);
                array_push($modelSave, $txModel);
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                foreach ($txModel->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                return Json::encode($err);
            }
        } else {

            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'txModel' => $txModel,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblInventoryTransfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->inventory_transfer_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblInventoryTransfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $this->model = $this->findModel($id);
        $master = [];
        if (!empty($this->model)) {
            $historyModel = new TblInventoryTransferHistory();
            $model = new TblInventoryTransferTxn();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblInventoryTransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblInventoryTransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblInventoryTransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblInventoryTransferTxnSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblInventoryTransferTxn'));
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

}
