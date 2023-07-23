<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblTask;
use app\modules\tms\models\TblTaskSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\tms\models\TblTaskActivity;

/**
 * TblTaskController implements the CRUD actions for TblTask model.
 */
class TblTaskController extends ChildController {

    /**
     * Lists all TblTask models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTask model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblTask();
        $model->scenario = 'addTask';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $activity_array = [];

            $activity_model = new TblTaskActivity();
            $activity_model->attributes = $model->attributes;
            $activity_model->module_type = $model->task_performed_for;

            if ($model->task_performed_for == 'DCS') {
                foreach ($model->dcs_code as $dcs) {
                    $activity_model->module_code = $dcs;
                    $activity_model->route_code = $activity_model->dcsCode->route_code;
                    $activity_array[] = clone $activity_model;
                }
            } else {
                $activity_model->module_code = ($activity_model->module_type == 'PLANT') ? $model->plant_code : $model->bmc_code;
                $activity_array[] = $activity_model;
            }

            $saveModel = [];
            $auto_key_config = [];
            $this->prepareTaskList($model, $activity_array, $saveModel, $auto_key_config);

            $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($saveModel, ['Task', 'create'], $auto_key_config);
            if ($transaction == 'customRedirect') {
                $this->redirect(['index']);
            }
        }
        $this->model = $model;
        $this->viewFile = 'create';
        return $this->customRender();
    }

    /**
     * Finds the TblTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function prepareTaskList($model, $activity_array, &$saveModel, &$auto_key_config) {
        $i = 0;
        if (in_array($model->repeat_interval, [1, 2])) {
            $day_list = \Yii::$app->DayHelper->GetYeardays($model->start_date, $model->end_date);
            if (!empty($day_list)) {
                foreach ($day_list as $day => $dates) {
                    $add_task = FALSE;
                    if ($model->repeat_interval == 1) {
                        $add_task = TRUE;
                    } else if (in_array($day, $model->week_days)) {
                        $add_task = TRUE;
                    }
                    if ($add_task) {
                        foreach ($dates as $date) {
                            $task_model = clone $model;
                            $task_model->task_datetime = $date;
                            $this->prepareTaskSave($task_model, $activity_array, $saveModel, $auto_key_config, $i);
                        }
                    }
                }
            } else {
                $model->task_datetime = date('Y-m-d', strtotime($model->start_date));
                $this->prepareTaskSave($model, $activity_array, $saveModel, $auto_key_config, $i);
            }
        } else {
            $model->end_date = NULL;
            $model->task_datetime = date('Y-m-d', strtotime($model->start_date));
            $this->prepareTaskSave($model, $activity_array, $saveModel, $auto_key_config, $i);
        }
    }

    private function prepareTaskSave($model, $activity_array, &$saveModel, &$auto_key_config, &$i) {
        $saveModel[$i] = $model;
        $task_id = $i;
        $i++;
        foreach ($activity_array as $activity) {
            $activity->task_datetime = $model->task_datetime;
            $auto_key_config[$i] = ['self_key' => 'task_code', 'parent_key' => 'task_code', 'parent_index' => $task_id];
            $saveModel[$i] = clone $activity;
            $i++;
        }
    }

}
