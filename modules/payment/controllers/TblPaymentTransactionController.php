<?php

namespace app\modules\payment\controllers;

use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\payment\models\TblPaymentTransaction;
use Yii;
use app\modules\payment\models\TblPaymentTransactionApproval;
use app\modules\payment\models\TblPaymentTransactionHistory;
use app\modules\payment\models\TblPaymentTransactionSearch;
use Faker\Provider\Uuid;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

/**
 * TblPaymentTransactionController implements the CRUD actions for TblPaymentTransaction model.
 */
class TblPaymentTransactionController extends \app\controllers\ChildController {

    /**
     * Lists all TblPaymentTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post()['TblPaymentTransaction'];
            $selectCodes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
            $maxCode = (int) TblPaymentTransaction::find()->max('payment_transaction_code') ?: 0;
            $saveModel = [];
            $approvalMap = [];
            $count = 1;
            foreach ($selectCodes as $key => $value) {
                [$paymentTransactionCode, $bmcCode, $type] = explode('###', $value);
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
                    $existingTransaction->bank_status = 'REINITIATED';
                    $saveModel[] = $existingTransaction;
                    $config = Yii::$app->general->getUnionConfiguration($existingTransaction->union_code, 'payment_disburse_with_workflow', 'PORTAL');
                    if ($config == 1) {
                        $approvalKey  = $bmcCode . '-' . $type;
                        if (!isset($approvalMap[$approvalKey])) {
                            $approvalModel = new TblPaymentTransactionApproval();
                            $approvalModel->attributes = $existingTransaction->attributes;
                            $approvalModel->payment_transaction_approval_code = Yii::$app->general->getUuid();
                            $approvalModel->bmc_code = $bmcCode;
                            $approvalModel->customer_type = $type;
                            $approvalMap[$approvalKey] = $approvalModel;
                            $saveModel[] = $approvalModel;
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setApprovalData($existingTransaction->union_code, 'tbl_payment_transaction_approval', $approvalModel->payment_transaction_approval_code, $saveModel, $approval_stages);
                        } else {
                            $approvalMap[$approvalKey]->total_amount += $existingTransaction->total_amount;
                            $approvalMap[$approvalKey]->total_deduction += $existingTransaction->total_deduction;
                            $approvalMap[$approvalKey]->final_amount += $existingTransaction->final_amount;
                            $approvalMap[$approvalKey]->qty += $existingTransaction->qty;
                            $approvalMap[$approvalKey]->avg_fat += $existingTransaction->avg_fat;
                            $approvalMap[$approvalKey]->avg_snf += $existingTransaction->avg_snf;
                            $approvalMap[$approvalKey]->kg_fat += $existingTransaction->kg_fat;
                            $approvalMap[$approvalKey]->kg_snf += $existingTransaction->kg_snf;
                        }
                        $approvalMap[$approvalKey]->avg_fat = ($approvalMap[$approvalKey]->kg_fat / $approvalMap[$approvalKey]->qty)*100;
                        $approvalMap[$approvalKey]->avg_snf = ($approvalMap[$approvalKey]->kg_snf / $approvalMap[$approvalKey]->qty)*100;
                        $approvalMap[$approvalKey]->avg_rate = $approvalMap[$approvalKey]->total_amount/$approvalMap[$approvalKey]->qty;
                        $newTransaction->payment_transaction_approval_code = $approvalMap[$approvalKey]->payment_transaction_approval_code;
                        $newTransaction->is_approved = 0;
                    }
                    $saveModel[] = $newTransaction;
                }
                $count++;
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Payment Transaction Reinitiate Successfully', 'info']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
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
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'reinitiate'
        ]);
    }
}
