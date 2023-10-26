<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\tankermovement\models\TblPaymentHead;
use app\modules\tankermovement\models\TblPaymentHeadSearch;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblPaymentHeadHistory;

/**
 * TblPaymentHeadController implements the CRUD actions for TblTransporterPaymentHead model.
 */
class TblPaymentHeadController extends \app\controllers\ChildController {

    public $freeAccessActions = ['payment-head-list'];

    /**
     * Lists all TblTransporterPaymentHead models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPaymentHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTransporterPaymentHead model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTransporterPaymentHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblPaymentHead();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->payment_head_for = 'PARTY';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Payment Head', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblTransporterPaymentHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblPaymentHeadHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Payment Head', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblTransporterPaymentHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeactivatePaymentHead($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblPaymentHeadHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Payment Head', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Payment Head Deactivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Payment Head Not Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblTransporterPaymentHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTransporterPaymentHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPaymentHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
