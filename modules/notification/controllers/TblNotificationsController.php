<?php

namespace app\modules\notification\controllers;

use Yii;
use app\modules\notification\models\TblNotifications;
use app\modules\notification\models\TblNotificationsSearch;
use app\modules\notification\models\TblNotificationsHistory;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;
use app\controllers\ChildController;

/**
 * TblNotificationsController implements the CRUD actions for TblNotifications model.
 */
class TblNotificationsController extends ChildController {
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblNotifications models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblNotificationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblNotifications model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblNotifications model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblNotifications();
        $this->viewFile = 'create';
        $validate = 1;
        if ($this->model->load(Yii::$app->request->post())) {
                $this->setModel();
                $transaction = $this->generalModel->saveTransaction([$this->model], ['notification', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblNotifications model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
                $historyModel = new TblNotificationsHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $historyModel->notification_id = $id;
                $this->model->load(Yii::$app->request->post());
                $this->setModel();
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Notification', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblNotifications model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_notifications', Yii::$app->request->post('id'), 'id']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblNotificationsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }
    
    private function setModel() {
        $this->model->publish_date = ($this->model->publish_date == '') ? null : Yii::$app->formatter->asDate($this->model->publish_date, DATE_FORMAT);
    }

    /**
     * Finds the TblNotifications model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblNotifications the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblNotifications::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
