<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkTransfer;
use app\modules\collection\models\TblMilkTransferSearch;
use yii\web\NotFoundHttpException;
use app\modules\collection\models\TblMilkTransferHistory;

/**
 * TblMilkTransferController implements the CRUD actions for TblMilkTransfer model.
 */
class TblMilkTransferController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkTransfer models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkTransferSearch();
        $searchModel->scenario = 'indexSearch';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkTransfer model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkTransfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkTransfer();
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $this->model->milk_transfer_code = Yii::$app->general->getUuid();
            $key = $this->model->transfer_type == 1 ? 'IN' : 'OW';
            $bmc = $this->model->transfer_type == 1 ? $this->model->destination_code : $this->model->source_code;
            $date = date('dmY');
            $ranmd = rand(100, 999);
            $this->model->transaction_id = $key . $bmc . $date . $ranmd;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Transfer', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model
        ]);
    }

    /**
     * Updates an existing TblMilkTransfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblMilkTransferHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Milk Transfer', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblMilkTransfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkTransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMilkTransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkTransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function setModel(&$model) {
        $model->from_date = !empty($model->from_date) ? date('Y-m-d', strtotime($model->from_date)) : '';
        $model->from_date = $model->from_date . ' ' . \Yii::$app->general->getshift($model->from_shift);
        $model->to_date = !empty($model->to_date) ? date('Y-m-d', strtotime($model->to_date)) : '';
        $model->to_date = $model->to_date . ' ' . \Yii::$app->general->getshift($model->to_shift);
        $model->transaction_datetime = !empty($model->transaction_datetime) ? date('Y-m-d', strtotime($model->transaction_datetime)) : '';
        $model->transaction_datetime = $model->transaction_datetime . ' ' . \Yii::$app->general->getshift($model->shift_code);
    }

}
