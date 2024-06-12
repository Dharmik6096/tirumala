<?php

namespace app\modules\payment\controllers;

use app\modules\payment\models\TblPaymentTransaction;
use Yii;
use app\modules\payment\models\TblPaymentTransactionApproval;
use app\modules\payment\models\TblPaymentTransactionApprovalSearch;
use app\components\Model;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\payment\models\TblPaymentTransactionApprovalHistory;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

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
        $searchModel = new TblPaymentTransactionApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        $detailModel = $dataProvider->getModels();
        if (Yii::$app->request->post()) {
            $status = '';
            Model::loadMultiple($detailModel, Yii::$app->request->post());
            $modelSave = [];
            $paymentTransactionApprovalCodes = [];
            foreach ($detailModel as $detail) {
                if(!empty($detail->process_approval_code)){
                    $model = TblProcessApproval::findOne($detail->process_approval_code);
                    $model->scenario = 'approve';
                    $approvalHistoryModel = new TblProcessApprovalHistory();
                    Yii::$app->operation->history($model, $approvalHistoryModel, 'UPDATE');
                    $modelSave[] = $approvalHistoryModel;
                    $model->status = 1;
                    if ($model->validate()) {
                        if (!empty($modelSave)) {
                            $paymentTransactionApprovalCodes[] = $detail->payment_transaction_approval_code;
                            $model->ApprovalList($model, $modelSave, $status);
                            $historyModel = new TblPaymentTransactionApprovalHistory();
                            Yii::$app->operation->history($detail, $historyModel, 'UPDATE');
                            $modelSave[] = $historyModel;
                            $detail->approval_status = $status;
                            $modelSave[] = $detail;
                        }
                    }
                }
            }
            if(!empty($modelSave)){
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Payment Transaction Approve Successfully', 'info']);
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
        if (!empty($detailModel)) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $detailModel,
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
