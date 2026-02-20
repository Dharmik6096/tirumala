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
use yii\widgets\ActiveForm;

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
        $client_code = \Yii::$app->session->get('eiplCode');
        if (Yii::$app->request->post()) {
            $update = FALSE;
            $this->model->load(Yii::$app->request->post());
            if (!empty(Yii::$app->request->post()['TblGateEntry']['gate_entry_code'])) {
                $post_data = $this->model;
                $this->model = $this->findModel(Yii::$app->request->post()['TblGateEntry']['gate_entry_code']);
                $historyModel = new TblGateEntryHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $modelSave[] = $historyModel;
                $this->model->actual_arrival_time = $post_data->actual_arrival_time;
                $this->model->no_of_filled_can = $post_data->no_of_filled_can;
                $this->model->no_of_empty_can = $post_data->no_of_empty_can;
                $update = TRUE;
                $type = 'edit';
            } else {
                $this->model->date_time_of_collection = !empty($this->model->date_time_of_collection) ? date('Y-m-d', strtotime($this->model->date_time_of_collection)) : '';
                $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            }
            if ($this->model->validate()) {
                $actual_arrival_time = date("H:i", strtotime($this->model->actual_arrival_time));
                $define_arrival_time = !empty($this->model->define_arrival_time) ? date("H:i", strtotime('+' . (empty($this->model->grace_time) ? 0 : (int) $this->model->grace_time) . ' minutes', strtotime($this->model->define_arrival_time))) : $actual_arrival_time;
                $late_by_time = (strtotime($actual_arrival_time) - strtotime($define_arrival_time)) / 60;
                $this->model->late_by_time = ($late_by_time > 0) ? $late_by_time : 0;
                if (in_array($client_code, ['UMANG', 'MOTHER'])) {
                    $this->model->transporter_code = $this->model->vehicleCode->transporter_code;
                } else {
                    $this->model->transporter_code = Yii::$app->general->getforeignkey($this->model->vehicleName, 'transporter_code');
                }
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
        if (!empty($model)) {
            $record = ['status' => 'success', 'route_code' => $model->route_code, 'actual_arrival_time' => $model->actual_arrival_time, 'no_of_filled_can' => $model->no_of_filled_can, 'no_of_empty_can' => $model->no_of_empty_can, 'vehicle_code' => $model->vehicle_code];
        } else {
            $record = ['status' => 'error'];
        }
        return Json::encode($record);
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

    public function actionPrintGatePass($id) {
        $controls = [];
        $controls['p_gate_entry_code'] = $id;
        $controls['p_report_name'] = 'GatePass-' . $id;
        $this->printDocument($controls, 'vsp/GatePass', 'GatePass-' . $id, 'pdf');
    }

    public function actionGetOutEntry($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblGateEntryHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->scenario = 'getOut';
        $this->model->status = 1;
        $this->model->status_time = date('Y-m-d H:i:s');
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Gate Entry', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Gate Out Entry created Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Gate Out Entry Not created Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
