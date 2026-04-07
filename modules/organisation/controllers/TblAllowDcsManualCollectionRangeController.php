<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblAllowDcsManualCollectionRange;
use app\modules\organisation\models\TblAllowDcsManualCollectionRangeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblAllowDcsManualCollectionRangeHistory;

/**
 * TblAllowDcsManualCollectionRangeController implements the CRUD actions for TblAllowDcsManualCollectionRange model.
 */
class TblAllowDcsManualCollectionRangeController extends \app\controllers\ChildController {

    /**
     * Lists all TblAllowDcsManualCollectionRange models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblAllowDcsManualCollectionRange;
        $searchModel = new TblAllowDcsManualCollectionRangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAllowDcsManualCollectionRange model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblAllowDcsManualCollectionRange model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAllowDcsManualCollectionRange();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->manual_collection_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $fromShift = $this->model->from_shift == 1 ? ' 00:01:00.000' : ' 14:00:00.000';
            $toShift = $this->model->to_shift == 1 ? ' 13:59:00.000' : ' 23:59:00.000';
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . $fromShift;
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . $toShift;
            $this->model->status = '2';
            $this->model->request_type = '1';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['DCS Manual Collection Range', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblAllowDcsManualCollectionRange model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblAllowDcsManualCollectionRangeHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $this->model->load(Yii::$app->request->post());
            $fromShift = $this->model->from_shift == 1 ? ' 00:01:00.000' : ' 14:00:00.000';
            $toShift = $this->model->to_shift == 1 ? ' 13:59:00.000' : ' 23:59:00.000';
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . $fromShift;
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . $toShift;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['DCS Manual Collection Range', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
//        return $this->customRender();
    }

    /**
     * Deletes an existing TblAllowDcsManualCollectionRange model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblAllowDcsManualCollectionRange model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblAllowDcsManualCollectionRange the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAllowDcsManualCollectionRange::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateStatus() {
        $msg = 'Invalid request';
        $type = 'error';
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $status = Yii::$app->request->post('status');
            $remark = Yii::$app->request->post('remark');

            $model = TblAllowDcsManualCollectionRange::findOne($id);

            if ($model !== null) {
                $model->scenario = 'updateStatus';
                $model->status = $status;
                $model->remark = $remark;

                if ($model->save()) {
                    $msg = 'Update status successfully';
                    $type = 'success';
                } else {
                    $msg = 'Update status failed';
                }
            } else {
                $msg = 'Model not found';
            }
        }
        Yii::$app->getSession()->setFlash('success', ['type' => $type,
            'message' => $msg]);
        return;
    }

}
