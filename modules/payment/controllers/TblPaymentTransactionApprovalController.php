<?php

namespace app\modules\payment\controllers;

use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\payment\models\TblPaymentTransaction;
use Yii;
use app\modules\payment\models\TblPaymentTransactionApproval;
use app\modules\payment\models\TblPaymentTransactionApprovalSearch;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\payment\models\TblPaymentTransactionApprovalHistory;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

/**
 * TblPaymentTransactionApprovalController implements the CRUD actions for TblPaymentTransactionApproval model.
 */
class TblPaymentTransactionApprovalController extends \app\controllers\ChildController {

    /**
     * Lists all TblPaymentTransactionApproval models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPaymentTransactionApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPendingApproval() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $paymentTransactionApprovalCodes = [];
                $remarks = Yii::$app->request->post('remarks');
                $selecteddata = Yii::$app->request->post('selection');
                $process_status = Yii::$app->request->post('process_flag');
                foreach ($selecteddata as $key => $value) {
                    $status = '';
                    $this->model = TblProcessApproval::findOne($value);
                    $this->model->scenario = 'approve';
                    $historyModel = new TblProcessApprovalHistory();
                    Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                    $saveModel[] = $historyModel;
                    $this->model->status = ($process_status == 'approve') ? 1 : 2;
                    $this->model->remarks = $remarks;
                    if (!empty($saveModel)) {
                        $this->model->ApprovalList($this->model, $saveModel, $status);
                        $transactionModel = TblPaymentTransactionApproval::findOne($this->model->process_code);
                        $transactionHistoryModel = new TblPaymentTransactionApprovalHistory();
                        Yii::$app->operation->history($transactionModel, $transactionHistoryModel, 'UPDATE');
                        $paymentTransactionApprovalCodes[] = $transactionModel->payment_transaction_approval_code;
                        $saveModel[] = $transactionHistoryModel;
                        $transactionModel->approval_status = $status;
                        $transactionModel->status_date = date('Y-m-d H:i:s');
                        $transactionModel->status_by = \Yii::$app->user->identity->user_code;
                        $transactionModel->remarks = $remarks;
                        $saveModel[] = $transactionModel;
                    }
                }
                if (!empty($saveModel)) {
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Payment Transaction Approve Successfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        if (strtolower($status) == 'approve' || strtolower($status) == 'reject') {
                            $is_approved = (strtolower($status) == 'approve') ? 1 : 2;
                            $transationModel = new TblPaymentTransaction();
                            $condition = ['payment_transaction_approval_code' => $paymentTransactionApprovalCodes];
                            $updateData = ['is_approved' => $is_approved, 'approved_at' => date('Y-m-d H:i:s')];
                            $transationModel->updateStatus($condition, $updateData);
                        }
                        return $this->redirect(['pending-approval']);
                    }
                }
            }
        }
        $searchModel = new TblPaymentTransactionApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        if (!empty($dataProvider->getModels())) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $dataProvider->getModels(),
                'pagination' => FALSE,
            ]);
        }

        return $this->render('pending_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'type' => 'approval'
        ]);
    }

    /**
     * Displays a single TblPaymentTransactionApproval model.
     * @param string $id
     * @return mixed
     */
    public function actionView($payment_transaction_approval_code) {
        $this->model = $this->findModel($payment_transaction_approval_code);
        $transaction = new TblPaymentTransaction();
        $dataProvider = new ActiveDataProvider([
            'query' => $transaction->find()->where(['payment_transaction_approval_code' => $payment_transaction_approval_code]),
        ]);
        $approval = new TblProcessApproval();
        $approvalDataProvider = new ActiveDataProvider([
            'query' => $approval->find()->where(['process_code' => $payment_transaction_approval_code]),
        ]);
        return $this->render('view', [
                    'model' => $this->model,
                    'dataProvider' => $dataProvider,
                    'transaction' => $transaction,
                    'approvalDataProvider' => $approvalDataProvider,
                    'approval' => $approval
        ]);
    }

    /**
     * Finds the TblPaymentTransactionApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblPaymentTransactionApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPaymentTransactionApproval::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPendingReinitiate() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $paymentTransactionApprovalCodes = [];
                $remarks = Yii::$app->request->post('remarks');
                $selecteddata = Yii::$app->request->post('selection');
                $union_code = explode(',', Yii::$app->session->get('Unions'));
                $config = Yii::$app->general->getUnionConfiguration($union_code, 'payment_disburse_with_workflow', 'PORTAL');
                $status = 'Pending';
                foreach ($selecteddata as $key => $value) {
                    $model = TblPaymentTransactionApproval::findOne($value);
                    if (!empty($model)) {
                        $transactionHistoryModel = new TblPaymentTransactionApprovalHistory();
                        Yii::$app->operation->history($model, $transactionHistoryModel, 'UPDATE');
                        $saveModel[] = $transactionHistoryModel;
                        $model->approval_status = 'Approve';
                        $model->remarks = $remarks;
                        $model->status_date = date('Y-m-d H:i:s');
                        $model->status_by = \Yii::$app->user->identity->user_code;
                        if ($config == 1) {
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setApprovalData($model->union_code, 'tbl_payment_transaction_approval', $value, $saveModel, $approval_stages);
                            $model->approval_status = empty($approval_stages) ? 'Approve' : 'Pending';
                        }
                        $status = $model->approval_status;
                        $saveModel[] = $model;
                        $paymentTransactionApprovalCodes[] = $value;
                    }
                }
                if (!empty($saveModel)) {
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Payment Transaction Reinitiate Successfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        if (strtolower($status) == 'approve' || strtolower($status) == 'pending') {
                            $is_approved = (strtolower($status) == 'approve') ? 1 : 0;
                            $transationModel = new TblPaymentTransaction();
                            $condition = ['payment_transaction_approval_code' => $paymentTransactionApprovalCodes];
                            $updateData = ['is_approved' => $is_approved, 'approved_at' => date('Y-m-d H:i:s')];
                            $transationModel->updateStatus($condition, $updateData);
                        }
                        return $this->redirect(['pending-reinitiate']);
                    }
                }
            }
        }
        $searchModel = new TblPaymentTransactionApprovalSearch();
        $dataProvider = $searchModel->searchReinitiate(Yii::$app->request->queryParams, true);
        if (!empty($dataProvider->getModels())) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $dataProvider->getModels(),
                'pagination' => FALSE,
            ]);
        }

        return $this->render('pending_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'type' => 'reinitiate'
        ]);
    }

}
