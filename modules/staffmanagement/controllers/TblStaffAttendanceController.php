<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffAttendance;
use app\modules\staffmanagement\models\TblStaffAttendanceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\staffmanagement\models\TblStaffAttendanceHistory;

/**
 * TblStaffAttendanceController implements the CRUD actions for TblStaffAttendance model.
 */
class TblStaffAttendanceController extends \app\controllers\ChildController {

    /**
     * Lists all TblStaffAttendance models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffAttendanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffAttendance model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblStaffAttendance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblStaffAttendance();
        $this->viewFile = 'create';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->staff_attendance_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->lwp_date = !empty($this->model->lwp_date) ? date('Y-m-d', strtotime($this->model->lwp_date)) : NULL;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Staff Attendance', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblStaffAttendance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblStaffAttendanceHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $model->load(Yii::$app->request->post());
            $model->lwp_date = !empty($model->lwp_date) ? date('Y-m-d', strtotime($model->lwp_date)) : NULL;
            $master[] = $model;
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Attendance', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblStaffAttendance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffAttendance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblStaffAttendance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffAttendance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
