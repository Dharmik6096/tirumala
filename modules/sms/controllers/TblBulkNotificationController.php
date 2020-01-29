<?php

namespace app\modules\sms\controllers;

use Yii;
use app\modules\sms\models\TblBulkNotification;
use app\modules\sms\models\TblBulkNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\sms\models\TblApiMaster;

/**
 * TblBulkNotificationController implements the CRUD actions for TblBulkNotification model.
 */
class TblBulkNotificationController extends \app\controllers\ChildController {

    /**
     * Lists all TblBulkNotification models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBulkNotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBulkNotification model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBulkNotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBulkNotification();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->receiver_type = 'APP_NOTIFICATION';
            $this->model->content_id = Yii::$app->general->getforeignkey($this->model->apiMaster, 'api_master_id');
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Bulk Notification', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblBulkNotification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
//            $historyModel = new TblBulkNotificationHistory();
//            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Customer Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBulkNotification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBulkNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBulkNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBulkNotification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
