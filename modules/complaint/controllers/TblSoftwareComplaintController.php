<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblSoftwareComplaint;
use app\modules\complaint\models\TblSoftwareComplaintSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\complaint\models\TblSoftwareComplaintTxnSearch;
use app\modules\complaint\models\TblSoftwareComplaintTxn;
use app\modules\complaint\models\TblSoftwareComplaintHistory;
use app\modules\complaint\models\TblSoftwareComplaintTxnHistory;

/**
 * TblSoftwareComplaintController implements the CRUD actions for TblSoftwareComplaint model.
 */
class TblSoftwareComplaintController extends \app\controllers\ChildController {

    /**
     * Lists all TblSoftwareComplaint models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSoftwareComplaintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSoftwareComplaint model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblSoftwareComplaintTxnSearch();
        $searchModel->complaint_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblSoftwareComplaint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSoftwareComplaint();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->scenario = 'create';
            $this->model->complaint_code = \Yii::$app->general->getPrimaryCode($this->model);
            $this->setModel($this->model);
            $this->model->attachment = Yii::$app->request->post('attachment');
            $this->model->product_code = '0000';
            $this->model->product_name = 'AMUL AMCS SOFTWARE';
            $this->model->complaint_status = '1';
            $this->model->service_call_no = $this->model->getServiceCall($this->model);

            $transaction = $this->generalModel->saveTransaction([$this->model], ['Software Complaint', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSoftwareComplaint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSoftwareComplaintHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Software Complaint', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblSoftwareComplaint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblSoftwareComplaint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSoftwareComplaint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSoftwareComplaint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUploadFile() {

        $path = Yii::$app->basePath . '/web/uploads/software-complaint-docs/';
        Yii::$app->general->checkDirectory($path, '0777');
        $this->model = new TblSoftwareComplaint();

        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $fn = $this->model->uploadFile($file);
            if ($file->saveAs($path . $fn)) {
                $record = ['status' => 'success', 'msg' => $fn];
            } else {
                $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionRemove() {
        $old_attachment = Yii::$app->request->post('value');
        $path = Yii::$app->params['software_complaint_dir_path'];
        if (!empty($old_attachment)) {
            if (file_exists($path . $old_attachment)) {
                if ($old_attachment != Yii::$app->request->post('old_value')) {
                    unlink($path . $old_attachment);
                }
                return 1;
            }
        } else {
            return 0;
        }
    }

    private function setModel() {
        $this->model->complaint_date = ($this->model->complaint_date == '') ? null : Yii::$app->formatter->asDate($this->model->complaint_date, DATE_FORMAT);
    }

    public function actionAssignComplaint($id) {
        $model = $this->findModel($id);
        $txnModel = new TblSoftwareComplaintTxn();
        $saveModel = [];
        $txnModel->scenario = 'assign';
        if ($txnModel->load(Yii::$app->request->post()) && $txnModel->validate()) {
            $txnModel->complaint_code = $id;
            $txnModel->complaint_txn_code = Yii::$app->general->getTransactionCode($txnModel, $model->complaint_code);
            $txnModel->assign_date = ($txnModel->assign_date == '') ? null : Yii::$app->formatter->asDate($txnModel->assign_date, DATE_FORMAT);
            $txnModel->attachment = Yii::$app->request->post('attachment');
            $txnModel->complaint_status = '2';
            $saveModel[] = $txnModel;

            $existTxn = TblSoftwareComplaintTxn::find()->where(['complaint_code' => $id])->one();
            $status = !empty($existTxn) ? 3 : 2;
            $historyModel = new TblSoftwareComplaintHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $saveModel[] = $historyModel;
            $model->assign_to = $txnModel->assign_to;
            $model->assign_date = $txnModel->assign_date;
            $model->assign_time = $txnModel->assign_time;
            $model->complaint_status = $status;
            $saveModel[] = $model;

            $transaction = $this->generalModel->saveTransaction($saveModel, ['Software Complaint Assign', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        $searchModel = new TblSoftwareComplaintTxnSearch();
        $searchModel->complaint_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('assign_complaint', [
                    'model' => $model,
                    'txnModel' => $txnModel,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResolveComplaint($id) {
        $model = $this->findModel($id);
        $user = \Yii::$app->session->get('UserCode');
        $showUserdd = $model->created_by == $user ? TRUE : FALSE;
        if ($showUserdd) {
            $model->scenario = 'createdUser';
        } else {
            $model->scenario = 'resolve';
        }

        $historyModel = new TblSoftwareComplaintHistory();
        Yii::$app->operation->history($model, $historyModel, 'UPDATE');
        $txnModel = new TblSoftwareComplaintTxn();
        $saveModel = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $saveModel[] = $historyModel;
            $model->complaint_status = 4;
            $model->resolve_date = ($model->resolve_date == '') ? null : Yii::$app->formatter->asDate($model->resolve_date, DATE_FORMAT);


            $existTxn = TblSoftwareComplaintTxn::find()
                    ->where(['complaint_code' => $id, 'assign_to' => $model->assign_to])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->one();
            if (!empty($existTxn)) {
                $txnHistoryModel = new TblSoftwareComplaintTxnHistory();
                Yii::$app->operation->history($existTxn, $txnHistoryModel, 'UPDATE');
                $saveModel[] = $txnHistoryModel;
                $existTxn->complaint_status = 4;
                $existTxn->attachment = !empty(Yii::$app->request->post('attachment')) ? Yii::$app->request->post('attachment') : '';
                $existTxn->load(Yii::$app->request->post());
                $saveModel[] = $existTxn;
            } else {
                $txnModel = new TblSoftwareComplaintTxn();
                $txnModel->load(Yii::$app->request->post());
                $txnModel->complaint_code = $id;
                $txnModel->complaint_txn_code = Yii::$app->general->getTransactionCode($txnModel, $model->complaint_code);
                $txnModel->complaint_status = 4;
                $txnModel->assign_to = $model->assign_to;
                $txnModel->assign_date = $model->resolve_date;
                $txnModel->assign_time = date('H:s');
                $txnModel->attachment = Yii::$app->request->post('attachment');
                $saveModel[] = $txnModel;

                $model->assign_time = $txnModel->assign_time;
            }

            $saveModel[] = $model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Software Complaint Resolve', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        $searchModel = new TblSoftwareComplaintTxnSearch();
        $searchModel->complaint_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('resolve_complaint', [
                    'model' => $model,
                    'txnModel' => $txnModel,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'showUserdd' => $showUserdd,
        ]);
    }

    public function actionServiceBill($id) {
        \Yii::$app->pdf->generatePdfAMULServiceCall($id);
    }

}
