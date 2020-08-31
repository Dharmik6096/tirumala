<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleTransporterHeadMapping;
use app\modules\transporter\models\TblVehicleTransporterHeadMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\transporter\models\TblVehicleTransporterHeadMappingHistory;

/**
 * TblVehicleTransporterHeadMappingController implements the CRUD actions for TblVehicleTransporterHeadMapping model.
 */
class TblVehicleTransporterHeadMappingController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleTransporterHeadMapping models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleTransporterHeadMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleTransporterHeadMapping model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleTransporterHeadMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($vehicle_code = '', $tr_code = '') {
        $this->model = new TblVehicleTransporterHeadMapping();
        $this->model->scenario = 'create';
        $this->model->vehicle_code = $vehicle_code;
        $this->model->transporter_code = $tr_code;
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Transporter Head', 'create']);
            if ($transaction == 'customRender') {
                return $this->{$transaction}();
            } else {
                if ($vehicle_code != '') {
                    return $this->redirect(['/transporter/tbl-vehicle-master/index']);
                } else {
                    return $this->redirect(['/transporter/tbl-vehicle-transporter-head-mapping/index']);
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleTransporterHeadMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleTransporterHeadMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Vehicle Transporter Head', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVehicleTransporterHeadMapping model.
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
        if (($model = TblVehicleTransporterHeadMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
