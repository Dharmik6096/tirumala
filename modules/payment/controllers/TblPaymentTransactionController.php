<?php

namespace app\modules\payment\controllers;

use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\payment\models\TblPaymentTransaction;
use Yii;
use app\modules\payment\models\TblPaymentTransactionApproval;
use app\modules\payment\models\TblPaymentTransactionHistory;
use app\modules\payment\models\TblPaymentTransactionSearch;
use yii\data\ArrayDataProvider;

/**
 * TblPaymentTransactionController implements the CRUD actions for TblPaymentTransaction model.
 */
class TblPaymentTransactionController extends \app\controllers\ChildController {

    /**
     * Lists all TblPaymentTransaction models.
     * @return mixed
     */
    public function actionRejectReinitiate()
    {
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post()['TblPaymentTransaction'];
            $selectCodes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
            $maxCode = (int) TblPaymentTransaction::find()->max('payment_transaction_code') ?: 0;
            $saveModel = [];
            $approvalMap = [];
            $count = 1;
            foreach ($selectCodes as $key => $value) {
                [$paymentTransactionCode, $bmcCode, $type, $fromDate, $toDate] = explode('###', $value);
                $existingTransaction = TblPaymentTransaction::find()->where(['payment_transaction_code' => $paymentTransactionCode, 'bank_status' => ['FAILED']])->one();
                if (!empty($existingTransaction)) {
                    $historyModel = new TblPaymentTransactionHistory();
                    Yii::$app->operation->history($existingTransaction, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $newTransaction = new TblPaymentTransaction();
                    $newTransaction->attributes = array_merge(
                        $existingTransaction->attributes,
                        $postData[$paymentTransactionCode] ? $postData[$paymentTransactionCode] : []
                    );
                    $newTransaction->bank_status = NULL;
                    $newTransaction->payment_transaction_code = $maxCode + $count;
                    $newTransaction->ref_payment_transaction_code = $existingTransaction->payment_transaction_code;
                    $newTransaction->is_file = 0;
                    $newTransaction->is_approved = 1;
                    $newTransaction->approved_at = NULL;
                    $newTransaction->pick_datetime = NULL;
                    $newTransaction->response_datetime = NULL;
                    $newTransaction->response_msg = NULL;
                    unset($newTransaction->created_at, $newTransaction->updated_at, $newTransaction->created_by, $newTransaction->updated_by);
                    $existingTransaction->bank_status = 'REINITIATED';
                    $saveModel[] = $existingTransaction;
                    $existingTransaction->updatePaymentMasterData($existingTransaction, $newTransaction, $saveModel);
                    $config = Yii::$app->general->getUnionConfiguration($existingTransaction->union_code, 'payment_disburse_with_workflow', 'PORTAL');
                    if ($config == 1) {
                        $approvalKey  = $bmcCode . '-' . $type . '-' . $fromDate . '-' . $toDate;
                        if (!isset($approvalMap[$approvalKey])) {
                            $approvalModel = new TblPaymentTransactionApproval();
                            $approvalModel->attributes = $existingTransaction->attributes;
                            $approvalModel->payment_transaction_approval_code = Yii::$app->general->getUuid();
                            $approvalModel->bmc_code = $bmcCode;
                            $approvalModel->customer_type = $type;
                            unset($approvalModel->created_at, $approvalModel->updated_at, $approvalModel->created_by, $approvalModel->updated_by);
                            $approvalMap[$approvalKey] = $approvalModel;
                            $saveModel[] = $approvalModel;
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setApprovalData($existingTransaction->union_code, 'tbl_payment_transaction_approval', $approvalModel->payment_transaction_approval_code, $saveModel, $approval_stages);
                        } else {
                            $approvalMap[$approvalKey]->total_amount = bcadd($approvalMap[$approvalKey]->total_amount, $existingTransaction->total_amount,2);
                            $approvalMap[$approvalKey]->total_deduction = bcadd($approvalMap[$approvalKey]->total_deduction, $existingTransaction->total_deduction,2);
                            $approvalMap[$approvalKey]->final_amount = bcadd($approvalMap[$approvalKey]->final_amount, $existingTransaction->final_amount,2);
                            $approvalMap[$approvalKey]->qty = bcadd($approvalMap[$approvalKey]->qty, $existingTransaction->qty,2);
                            $approvalMap[$approvalKey]->kg_fat = bcadd($approvalMap[$approvalKey]->kg_fat, $existingTransaction->kg_fat,2);
                            $approvalMap[$approvalKey]->kg_snf = bcadd($approvalMap[$approvalKey]->kg_snf, $existingTransaction->kg_snf,2);
                        }

                        $approvalMap[$approvalKey]->avg_fat = bcmul(bcdiv($approvalMap[$approvalKey]->kg_fat, $approvalMap[$approvalKey]->qty, 4), '100', 2);
                        $approvalMap[$approvalKey]->avg_snf = bcmul(bcdiv($approvalMap[$approvalKey]->kg_snf, $approvalMap[$approvalKey]->qty, 4), '100', 2);
                        $approvalMap[$approvalKey]->avg_rate = bcdiv($approvalMap[$approvalKey]->total_amount, $approvalMap[$approvalKey]->qty, 2);
                        $newTransaction->payment_transaction_approval_code = $approvalMap[$approvalKey]->payment_transaction_approval_code;
                        $newTransaction->is_approved = 0;
                    }
                    $saveModel[] = $newTransaction;
                }
                $count++;
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Payment Transaction Reinitiate Successfully', 'info']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['reject-reinitiate']);
            }
        }
        $searchModel = new TblPaymentTransactionSearch();
        $dataProvider = $searchModel->searchRejectReinitiate(Yii::$app->request->queryParams);
        if (!empty($dataProvider->getModels())) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $dataProvider->getModels(),
                'pagination' => FALSE,
            ]);
        }
        return $this->render('reject_reinitiate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'reinitiate'
        ]);
    }
}
