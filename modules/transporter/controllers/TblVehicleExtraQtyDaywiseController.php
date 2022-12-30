<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleExtraQtyDaywise;
use app\modules\transporter\models\TblVehicleExtraQtyDaywiseSearch;
use app\modules\transporter\models\TblVehicleExtraQtyDaywiseHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblVehicleExtraQtyDaywiseController implements the CRUD actions for TblVehicleExtraQtyDaywise model.
 */
class TblVehicleExtraQtyDaywiseController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleExtraQtyDaywise models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleExtraQtyDaywiseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleExtraQtyDaywise model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleExtraQtyDaywise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleExtraQtyDaywise();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->date = !empty($this->model->date) ? date('Y-m-d', strtotime($this->model->date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Extra Qty', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleExtraQtyDaywise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleExtraQtyDaywiseHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $this->model->load(Yii::$app->request->post());
            $this->model->date = !empty($this->model->date) ? date('Y-m-d', strtotime($this->model->date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Vehicle Extra Qty', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVehicleExtraQtyDaywise model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleExtraQtyDaywise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleExtraQtyDaywise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleExtraQtyDaywise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
