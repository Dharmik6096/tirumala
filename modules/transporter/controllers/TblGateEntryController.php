<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblGateEntry;
use app\modules\transporter\models\TblGateEntrySearch;
use app\modules\transporter\models\TblGateEntryHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblGateEntryController implements the CRUD actions for TblGateEntry model.
 */
class TblGateEntryController extends ChildController {

    /**
     * Lists all TblGateEntry models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblGateEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblGateEntry model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblGateEntry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblGateEntry();
        $searchModel = new TblGateEntrySearch();
        $searchModel->date_time_of_collection = date('Y-m-d');
        $searchModel->shift_code = 1;
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $this->model->date_time_of_collection = date('d-m-Y');
        $this->model->shift_code = 1;
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Gate Entry';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $update = FALSE;
            $this->model->load(Yii::$app->request->post());
            if (!empty(Yii::$app->request->post()['TblGateEntry']['gate_entry_code'])) {
                $this->model = $this->findModel(Yii::$app->request->post()['TblGateEntry']['gate_entry_code']);
                $historyModel = new TblGateEntryHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $modelSave[] = $historyModel;
                $this->model->load(Yii::$app->request->post());
                $update = TRUE;
            }
            $this->model->date_time_of_collection = !empty($this->model->date_time_of_collection) ? date('Y-m-d', strtotime($this->model->date_time_of_collection)) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            if ($this->model->validate()) {
                $modelSave[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblGateEntry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->gate_entry_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblGateEntry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblGateEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblGateEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblGateEntry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblGateEntrySearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblGateEntry'));
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

}
