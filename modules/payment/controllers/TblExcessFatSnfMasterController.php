<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblExcessFatSnfMaster;
use app\modules\payment\models\TblExcessFatSnfMasterSearch;
use app\modules\payment\models\TblExcessFatSnfMasterHistory;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * TblExcessFatSnfMasterController implements the CRUD actions for TblExcessFatSnfMaster model.
 */
class TblExcessFatSnfMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblExcessFatSnfMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblExcessFatSnfMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblExcessFatSnfMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblExcessFatSnfMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Excess Fat Snf', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblExcessFatSnfMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->from_shift = Yii::$app->controls->view_datetime($this->model->from_date, 'php:H:i:s') == '06:00:00' ? 1 : 2;
        $this->model->to_shift = Yii::$app->controls->view_datetime($this->model->to_date, 'php:H:i:s') == '06:00:00' ? 1 : 2;
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $historyModel = new TblExcessFatSnfMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Excess Fat Snf', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblExcessFatSnfMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblExcessFatSnfMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblExcessFatSnfMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeactivate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'deactivate';
        $masterModel = new TblExcessFatSnfMasterHistory();
        Yii::$app->operation->history($this->model, $masterModel, UPDATE);
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $masterModel], ['Excess Fat Snf Master', 'edit']);
        if ($transaction !== FALSE) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'deactivated successfully.']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not deactivate. Please try again.']);
        }

        $this->redirect(Url::previous());
    }

}
