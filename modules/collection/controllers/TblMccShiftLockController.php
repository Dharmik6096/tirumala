<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMccShiftLock;
use app\modules\collection\models\TblMccShiftLockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblBmcCollectionSearch;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\collection\models\TblMccShiftLockHistory;
use app\modules\collection\models\TblMccShiftLockStaging;

/**
 * TblMccShiftLockController implements the CRUD actions for TblMccShiftLock model.
 */
class TblMccShiftLockController extends \app\controllers\ChildController {

    /**
     * Lists all TblMccShiftLock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcCollectionSearch();
        $searchModel->scenario = 'shiftLock';
        $dataProvider = $searchModel->shiftlocksearch(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccShiftLock model.
     * @param string $id
     * @return mixed
     */
    public function actionView($mcc = '', $date = '', $shift = '') {
        $this->model = new TblMccShiftLock();
        $model = $this->model->find()->where(['mcc_plant_code' => $mcc, 'cast(date_time_of_collection as date)' => $date, 'shift_code' => $shift])->one();
        $searchModel = new TblMccShiftLockSearch();
        $searchModel->shift_lock_code = $model->shift_lock_code;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMccShiftLock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMccShiftLock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->shift_lock_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMccShiftLock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->shift_lock_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMccShiftLock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMccShiftLock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMccShiftLock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccShiftLock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLockData() {
        $union = Yii::$app->request->get()['union'];
        $plant = Yii::$app->request->get()['plant'];
        $mcc = Yii::$app->request->get()['mcc'];
        $date = Yii::$app->request->get()['date'];
        $shift = Yii::$app->request->get()['shift'];
        $qty = Yii::$app->request->get()['qty'];
        $fat = Yii::$app->request->get()['fat'];
        $snf = Yii::$app->request->get()['snf'];
        $amount = Yii::$app->request->get()['amount'];
        $saveModel = [];
        if (!empty($mcc)) {
            $this->model = new TblMccShiftLock();
            $this->model->union_code = $union;
            $this->model->plant_code = $plant;
            $this->model->mcc_plant_code = $mcc;
            $this->model->date_time_of_collection = $date;
            $this->model->shift_code = $shift;
            $this->model->qty = $qty;
            $this->model->avg_fat = $fat;
            $this->model->avg_snf = $snf;
            $this->model->amount = $amount;
            $existData = $this->model->getExistData();
            if (!empty($existData)) {
                $this->model = $this->findModel($existData->shift_lock_code);
                $historyModel = new TblMccShiftLockHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
            } else {
                $this->model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            }
            $this->model->data_lock = 1;

            $staging = new TblMccShiftLockStaging();
            $attribute = $this->model->attributes;
            $staging->setAttributes($attribute);
            $stagingData = $staging->find()->where(['shift_lock_code' => $this->model->shift_lock_code])->one();

            if (!empty($stagingData)) {
                $stagingData->setAttributes($attribute);
                $stagingData->data_post_status = 0;
                $stagingData->picked_datetime = NULL;
                $stagingData->response_datetime = NULL;
                $stagingData->resp_status = NULL;
                $stagingData->resp_desc = NULL;
                $saveModel[] = $stagingData;
            } else {
                $ConcateDate = date('d', strtotime($date)) . '_' . date('m', strtotime($date)) . '_' . date('y', strtotime($date));
                $ConcateShift = $shift == 1 ? 'M' : 'E';
                $staging->staging_code = $this->model->mcc_plant_code . $ConcateDate . $ConcateShift;
                $saveModel[] = $staging;
            }
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'DATA LOCK Successfully.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'DATA Not LOCK Successfully.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionUnlock() {
        $union = Yii::$app->request->get()['union'];
        $plant = Yii::$app->request->get()['plant'];
        $mcc = Yii::$app->request->get()['mcc'];
        $date = Yii::$app->request->get()['date'];
        $shift = Yii::$app->request->get()['shift'];
        $qty = Yii::$app->request->get()['qty'];
        $fat = Yii::$app->request->get()['fat'];
        $snf = Yii::$app->request->get()['snf'];
        $amount = Yii::$app->request->get()['amount'];
        $saveModel = [];
        if (!empty($mcc)) {
            $this->model = new TblMccShiftLock();
            $this->model->union_code = $union;
            $this->model->plant_code = $plant;
            $this->model->mcc_plant_code = $mcc;
            $this->model->date_time_of_collection = $date;
            $this->model->shift_code = $shift;
            $this->model->qty = $qty;
            $this->model->avg_fat = $fat;
            $this->model->avg_snf = $snf;
            $this->model->amount = $amount;
            $existData = $this->model->getExistData();
            if (!empty($existData)) {
                $this->model = $this->findModel($existData->shift_lock_code);
                $historyModel = new TblMccShiftLockHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
            } else {
                $this->model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            }
            $this->model->data_lock = 0;
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'DATA UN-LOCK Successfully.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'DATA Not UN-LOCK Successfully.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
