<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblLocationWiseKmDetail;
use app\modules\transporter\models\TblLocationWiseKmDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\transporter\models\TblLocationWiseKmDetailHistory;

/**
 * TblLocationWiseKmDetailController implements the CRUD actions for TblLocationWiseKmDetail model.
 */
class TblLocationWiseKmDetailController extends \app\controllers\ChildController {

    /**
     * Lists all TblLocationWiseKmDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLocationWiseKmDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblLocationWiseKmDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($from_type, $from_dest, $to_type, $to_dest) {
        $searchModel = new TblLocationWiseKmDetailSearch();
        $searchModel->from_type = $from_type;
        $searchModel->from_dest = $from_dest;
        $searchModel->to_type = $to_type;
        $searchModel->to_dest = $to_dest;
        $dataProvider = $searchModel->detailsearch(Yii::$app->request->queryParams);
        return $this->render('_detail_view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblLocationWiseKmDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblLocationWiseKmDetail();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = empty($this->model->wef_date) ? NULL : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Location Wise Km', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblLocationWiseKmDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblLocationWiseKmDetailHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Location Wise Km', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblLocationWiseKmDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblLocationWiseKmDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblLocationWiseKmDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLocationWiseKmDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
