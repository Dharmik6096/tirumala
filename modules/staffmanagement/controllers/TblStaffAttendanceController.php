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
        $modelSave = [];
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->staff_attendance_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->leave_from = !empty($this->model->leave_from) ? date('Y-m-d', strtotime($this->model->leave_from)) : NULL;
            $this->model->leave_to = !empty($this->model->leave_to) ? date('Y-m-d', strtotime($this->model->leave_to)) : NULL;
            if ($this->model->lwp_type == '0') {
                $this->model->leave_to = $this->model->leave_from;
            }
            if ($this->model->validate()) {
                if (date('Y-m', strtotime($this->model->leave_from)) != date('Y-m', strtotime($this->model->leave_to))) {
                    $this->setModel($this->model, $modelSave);
                } else {
                    $modelSave[] = $this->model;
                }
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Staff Attendance', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
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
            $model->leave_from = !empty($model->leave_from) ? date('Y-m-d', strtotime($model->leave_from)) : NULL;
            $model->leave_to = !empty($model->leave_to) ? date('Y-m-d', strtotime($model->leave_to)) : NULL;
            if ($model->lwp_type == '0') {
                $model->leave_to = $model->leave_from;
            }
            if ($model->validate()) {
                if (date('Y-m', strtotime($model->leave_from)) != date('Y-m', strtotime($model->leave_to))) {
                    $this->setModel($model, $master);
                } else {
                    $master[] = $model;
                }
                $transaction = $this->generalModel->saveTransaction($master, ['Staff Attendance', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
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

    public function setModel($modelData, &$saveModel) {
        $model = new TblStaffAttendance();
        $model->attributes = $modelData->attributes;
        $model->staff_attendance_code = (string) Yii::$app->general->getCodeAutoIncrement($model);
        $model->leave_to = date("Y-m-t", strtotime($modelData->leave_from));
        $model->leave_count = Yii::$app->general->getDateDifference($modelData->leave_from, $model->leave_to) + 1;
        $saveModel[] = $model;
        $modelTwo = new TblStaffAttendance();
        $modelTwo->attributes = $modelData->attributes;
        $modelTwo->staff_attendance_code = (string) Yii::$app->general->getCodeAutoIncrement($modelTwo, 2);
        $modelTwo->leave_from = date('Y-m-01', strtotime($modelData->leave_to));
        $modelTwo->leave_count = Yii::$app->general->getDateDifference($modelTwo->leave_from, $modelData->leave_to) + 1;
        $saveModel[] = $modelTwo;
    }

}
