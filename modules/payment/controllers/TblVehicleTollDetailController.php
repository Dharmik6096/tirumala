<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblVehicleTollDetail;
use app\modules\payment\models\TblVehicleTollDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblVehicleTollDetailHistory;

/**
 * TblVehicleTollDetailController implements the CRUD actions for TblVehicleTollDetail model.
 */
class TblVehicleTollDetailController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleTollDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleTollDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleTollDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleTollDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleTollDetail();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->dispatch_date = empty($this->model->dispatch_date) ? NULL : Yii::$app->formatter->asDate($this->model->dispatch_date, DATE_FORMAT);
//            $this->model->parsing_no = Yii::$app->general->getforeignkey($this->model->vehicleCode, 'parsing_no');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Toll Detail', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleTollDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleTollDetailHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->dispatch_date = ($this->model->dispatch_date == '') ? null : Yii::$app->formatter->asDate($this->model->dispatch_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Vehicle Toll Detail', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVehicleTollDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleTollDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleTollDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleTollDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
