<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblPaymentHeadTransaction;
use app\modules\tankermovement\models\TblPaymentHeadTransactionSearch;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblPaymentHeadTransactionHistory;

/**
 * TblPaymentHeadTransactionController implements the CRUD actions for TblVehicleTransporterHeadMapping model.
 */
class TblPaymentHeadTransactionController extends \app\controllers\ChildController {

    /**
     * Lists all TblPaymentHeadTransaction models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPaymentHeadTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblPaymentHeadTransaction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblPaymentHeadTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblPaymentHeadTransaction();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->applicable_for = 'PARTY';
            $this->model->payment_head_type = Yii::$app->general->getforeignkey($this->model->paymentHeadType, 'payment_head_type');
            $this->model->union_code = Yii::$app->general->getforeignkey($this->model->paymentHeadType, 'union_code');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Payment Transporter Head', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblPaymentHeadTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblPaymentHeadTransactionHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Payment Transporter Head', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblPaymentHeadTransaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleTransporterHeadMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleTransporterHeadMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPaymentHeadTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
