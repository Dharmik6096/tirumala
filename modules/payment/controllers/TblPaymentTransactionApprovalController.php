<?php

namespace app\modules\payment\controllers;

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
class TblPaymentTransactionApprovalController extends \app\controllers\ChildController
{

    /**
     * Lists all TblPaymentTransactionApproval models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblPaymentTransactionApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPendingApproval()
    {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $paymentTransactionApprovalCodes = [];
                $remarks = Yii::$app->request->post('remarks');
                $selecteddata = Yii::$app->request->post('selection');
                foreach ($selecteddata as $key => $value) {
                    $status = '';
                    $this->model = TblProcessApproval::findOne($value);
                    $this->model->scenario = 'approve';
                    $historyModel = new TblProcessApprovalHistory();
                    Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                    $saveModel[] = $historyModel;
                    $this->model->status = 1;
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
                if(!empty($saveModel)){
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Payment Transaction Approve Successfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        if(strtolower($status) == 'approve'){
                            $transationModel = new TblPaymentTransaction();
                            $condition = ['payment_transaction_approval_code' => $paymentTransactionApprovalCodes];
                            $updateData = ['is_approved' => 1, 'approved_at' => date('Y-m-d H:i:s')];
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
        ]);
    }

    /**
     * Displays a single TblPaymentTransactionApproval model.
     * @param string $id
     * @return mixed
     */
    public function actionView($payment_transaction_approval_code)
    {
        $this->model = $this->findModel($payment_transaction_approval_code);
        $transaction = new TblPaymentTransaction();
        $dataProvider = new ActiveDataProvider([
            'query' => $transaction->find()->where(['payment_transaction_approval_code' => $payment_transaction_approval_code]),
        ]);
        return $this->render('view', [
            'model' => $this->model,
            'dataProvider' => $dataProvider,
            'transaction' => $transaction
        ]);
    }

    /**
     * Finds the TblPaymentTransactionApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblPaymentTransactionApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblPaymentTransactionApproval::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
