<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleExtraKmDaywise;
use app\modules\transporter\models\TblVehicleExtraKmDaywiseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\transporter\models\TblVehicleExtraKmDaywiseHistory;

/**
 * TblVehicleExtraKmDaywiseController implements the CRUD actions for TblVehicleExtraKmDaywise model.
 */
class TblVehicleExtraKmDaywiseController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleExtraKmDaywise models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleExtraKmDaywiseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleExtraKmDaywise model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleExtraKmDaywise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleExtraKmDaywise();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->date = !empty($this->model->date) ? date('Y-m-d', strtotime($this->model->date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Extra Km', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleExtraKmDaywise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleExtraKmDaywiseHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $this->model->load(Yii::$app->request->post());
            $this->model->date = !empty($this->model->date) ? date('Y-m-d', strtotime($this->model->date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Vehicle Extra Km', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVehicleExtraKmDaywise model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleExtraKmDaywise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleExtraKmDaywise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleExtraKmDaywise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
