<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblRecoveryParamDetail;
use app\modules\transporter\models\TblRecoveryParamDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\transporter\models\TblRecoveryParamDetailHistory;

/**
 * TblRecoveryParamDetailController implements the CRUD actions for TblRecoveryParamDetail model.
 */
class TblRecoveryParamDetailController extends \app\controllers\ChildController {

    /**
     * Lists all TblRecoveryParamDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblRecoveryParamDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblRecoveryParamDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblRecoveryParamDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblRecoveryParamDetail();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['FAT/SNF Recovery Param', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblRecoveryParamDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblRecoveryParamDetailHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['FAT/SNF Recovery Param', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblRecoveryParamDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblRecoveryParamDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblRecoveryParamDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblRecoveryParamDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
