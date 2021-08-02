<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMccShiftLockStaging;
use app\modules\collection\models\TblMccShiftLockStagingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblMccShiftLockStagingHistory;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\collection\models\TblMccShiftLockSearch;

/**
 * TblMccShiftLockStagingController implements the CRUD actions for TblMccShiftLockStaging model.
 */
class TblMccShiftLockStagingController extends \app\controllers\ChildController {

    /**
     * Lists all TblMccShiftLockStaging models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccShiftLockStagingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccShiftLockStaging model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new TblMccShiftLockStaging model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMccShiftLockStaging();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staging_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMccShiftLockStaging model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staging_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMccShiftLockStaging model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMccShiftLockStaging model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMccShiftLockStaging the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccShiftLockStaging::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRePushData() {
        $id = Yii::$app->request->get()['id'];
        $saveModel = [];
        if (!empty($id)) {
            $this->model = new TblMccShiftLockStaging();
            $stagingData = $this->model->findOne($id);
            if (!empty($stagingData)) {
                $historyModel = new TblMccShiftLockStagingHistory();
                Yii::$app->operation->history($stagingData, $historyModel, 'UPDATE');
                $saveModel[] = $historyModel;
                $stagingData->data_post_status = 0;
                $stagingData->picked_datetime = NULL;
                $stagingData->response_datetime = NULL;
                $stagingData->resp_status = NULL;
                $stagingData->resp_desc = NULL;
                $saveModel[] = $stagingData;
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);

            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'DATA RE-PUSH Successfully.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'DATA Not RE-PUSH Successfully.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
