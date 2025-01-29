<?php

namespace app\modules\bkgprocess\controllers;

use app\components\WebApi;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\payment\models\TblMemberPayment;
use app\modules\payment\models\TblPaymentTransaction;
use Yii;
use yii\base\Controller;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class CashfreeBankIntegrationController extends Controller {

    public $freeAccessActions = ['upload-payment-data-bulk', 'get-transaction-status-bulk'];

    public function actionUploadPaymentDataBulk() {
        try {
            $transactionGroup = TblPaymentTransaction::find()->alias('pt')
                    ->select([
                        'pt.union_code',
                        'pt.file_name',
                        'ba.payment_url',
                        'dbd.bank_account_no',
                        'pt.union_bank_payment_code',
                        'pt.type'
                    ])
                    ->innerJoin('tbl_bmc as bmc', 'bmc.bmc_code = pt.bmc_code')
                    ->innerJoin('tbl_union_bank_payment as ubp', 'ubp.union_bank_payment_code = pt.union_bank_payment_code')
                    ->innerJoin('tbl_debit_bank_detail AS dbd', 'dbd.union_bank_payment_code = pt.union_bank_payment_code AND dbd.module_code = bmc.mcc_plant_code AND dbd.module_name = \'mcc\'')
                    ->innerJoin('tbl_bank_api_detail as ba', 'ubp.union_bank_payment_code = ba.union_bank_payment_code')
                    ->where([
                        'pt.is_file' => 0,
                        'UPPER(ubp.integration_mode)' => 'API',
                        'ubp.is_active' => 1,
                        'ba.is_active' => 1,
                        'dbd.is_active' => 1,
                        'pt.is_approved' => 1
                    ])
                    ->andWhere(['NOT', ['ISNULL(pt.file_name, \'\')' => '']])
                    ->groupBy(['pt.union_code', 'pt.file_name', 'ba.payment_url', 'dbd.bank_account_no', 'pt.union_bank_payment_code', 'pt.type'])
                    ->limit(1)
                    ->asArray()
                    ->all();
            foreach ($transactionGroup as $batch) {
                $bank_log = new TblBankPaymentLog();
                $bank_log->union_code = $batch['union_code'];
                $bank_log->file_path = $batch['file_name'];
                $bank_log->file_name = $batch['file_name'];
                $bank_log->status = 1; //created
                $bank_log->payment_date = date('Y-m-d');
                $bank_log->payment_for = $batch['type'];
                $bank_log->created_by = 'CRON';
                $bank_log->created_at = date('Y-m-d H:i:s');
                $bank_log->union_bank_payment_code = $batch['union_bank_payment_code'];
                $bank_log->save();

                $api = new WebApi();
                $api->header_info['Content-Type'] = 'application/json';
                $api->header_info['x-client-id'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-id'];
                $api->header_info['x-client-secret'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-secret'];
                $api->header_info['x-api-version'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-api-version'];
                $api->is_header_merge = false;
                $api->return_actual = true;
                $api->serverUrl = $batch['payment_url'];
                $body = array(
                    'batch_transfer_id' => $batch['file_name']
                );
                $transfers = [];
                $paymentData = TblPaymentTransaction::find()
                        ->alias('pt')
                        ->select([
                            'bv.beneficiary_id as beneficiary_id',
                            'pt.payment_transaction_code as payment_transaction_code',
                            'pt.final_amount as final_amount',
                        ])
                        ->innerJoin('tbl_bank_verification as bv', 'bv.customer_code=pt.code and lower(bv.customer_type) = lower(pt.type) and bv.bank_account_no=pt.bank_account_no and bv.ifsc=pt.ifsc and UPPER(bv.res_beneficiary_status)=\'VERIFIED\'')
                        ->where([
                            'pt.file_name' => $batch['file_name'],
                            'pt.union_bank_payment_code' => $batch['union_bank_payment_code'],
                            'pt.is_file' => 0,
                            'pt.is_approved' => 1
                        ])
                        ->asArray()
                        ->all();
                $paymenttransactioncodeList = ArrayHelper::getColumn($paymentData, 'payment_transaction_code');
                $condition = ['payment_transaction_code' => $paymenttransactioncodeList, 'is_file' => 0];
                $updateData = ['is_file' => 1, 'pick_datetime' => date('Y-m-d H:i:s')];
                TblPaymentTransaction::updateAll($updateData, $condition);

                foreach ($paymentData as $payment) {
                    $trf = [];
                    $trf['transfer_id'] = $payment['payment_transaction_code'];
                    $trf['transfer_amount'] = $payment['final_amount'];
                    $trf['transfer_mode'] = 'banktransfer';
                    $trf['beneficiary_details']['beneficiary_id'] = $payment['beneficiary_id'];
                    $trf['fundsource_id'] = $batch['bank_account_no'];
                    $transfers[] = $trf;
                }

                if (!empty($transfers)) {
                    $body['transfers'] = $transfers;
                    $api->header_info['Content-length'] = strlen(json_encode($body));
                    $api->body = $body;
                    try {
                        $result = $api->GuzzleCURL();
                        $httpCode = $result->getStatusCode();
                        $response = $result->getBody()->getContents();
                        $response = !empty($response) ? json_decode($response) : [];
                        $bank_log->status = 2;
                        $bank_log->file_status = 'success';
                        $bank_log->utr_ref_no = isset($response->cf_batch_transfer_id) ? $response->cf_batch_transfer_id : NULL;
                        $bank_log->file_status_desc = isset($response->status) ? $response->status : NULL;
                        $bank_log->file_status_code = $httpCode;
                        $bank_log->no_of_txn = count($transfers);
                        $bank_log->updated_at = date('Y-m-d H:i:s');
                        $bank_log->save();
                    } catch (\Throwable $ex) {
                        $bank_log->status = 3;
                        $bank_log->file_status = 'API Failure';
                        $bank_log->file_status_desc = substr($ex->getMessage(), 0, 250);
                        $bank_log->no_of_txn = count($transfers);
                        $bank_log->updated_at = date('Y-m-d H:i:s');
                        $bank_log->save();

                        Yii::$app->db->createCommand()
                                ->update('tbl_payment_transaction', [
                                    'is_file' => '3',
                                    'response_datetime' => date('Y-m-d H:i:s'),
                                    'response_msg' => 'API Failure'], 'union_bank_payment_code =\'' . $batch['union_bank_payment_code'] . '\' and file_name =\'' . $batch['file_name'] . '\' and is_file = 1 and union_code= \'' . $batch['union_code'] . '\'')
                                ->execute();
                        // var_dump($ex);
                    }
                }
            }
        } catch (\Throwable $ex) {
            // var_dump($ex);
        }
    }

    public function actionGetTransactionStatusBulk() {
        $bankPaymentLogData = TblBankPaymentLog::find()->alias('bl')->select(['bl.union_bank_payment_code', 'ba.payment_url', 'bl.file_name', 'bl.union_code'])
                        ->innerJoin('tbl_union_bank_payment as ubp', 'ubp.union_bank_payment_code = bl.union_bank_payment_code')
                        ->innerJoin('tbl_bank_api_detail ba', 'ubp.union_bank_payment_code= ba.union_bank_payment_code')
                        ->where(['bl.status' => 2, 'ba.is_active' => 1, 'ubp.is_active' => 1, 'UPPER(ubp.integration_mode)' => 'API'])
                        ->andWhere(['NOT', ['bl.status' => 4, 'bl.status' => 3]])
                        ->limit(5)->asArray()->all();
        if (!empty($bankPaymentLogData)) {
            foreach ($bankPaymentLogData as $data) {
                $base_url = $data['payment_url'];
                $body = array();
                $api = new WebApi();
                $api->header_info['x-api-version'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-api-version'];
                $api->header_info['x-client-id'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-id'];
                $api->header_info['x-client-secret'] = \Yii::$app->params['cashfree_bank_integration']['header']['x-client-secret'];
                $api->is_header_merge = false;
                $api->return_actual = true;
                $api->body = $body;
                $api->serverUrl = $base_url;
                $queryParams = http_build_query([
                    'batch_transfer_id' => $data['file_name'],
                ]);
                $api->apiurl = '?' . $queryParams;
                try {
                    $result = $api->GuzzleCURL('GET');
                    $httpCode = $result->getStatusCode();
                    $response = $result->getBody()->getContents();
                    $response = !empty($response) ? json_decode($response) : [];
                    $this->ReverseUpdate($response, $data);
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    $responseData = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents()) : '';
                    Yii::$app->db->createCommand()->update('tbl_bank_payment_log', [
                                'status' => 3,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'file_error_code' => !empty($responseData->code) ? $responseData->code : '',
                                'file_error_desc' => !empty($responseData->message) ? $responseData->message : '',
                                'updated_by' => 'CRON'], ['file_name' => $data['file_name'], 'status' => 2, 'union_bank_payment_code' => $data['union_bank_payment_code']])
                            ->execute();

                    Yii::$app->db->createCommand()
                            ->update('tbl_payment_transaction', [
                                'is_file' => '2',
                                'response_datetime' => date('Y-m-d H:i:s'),
                                'bank_status' => 'FAILED',
                                'response_msg' => !empty($responseData->message) ? $responseData->message : '',
                                'updated_at' => date('Y-m-d H:i:s')
                                    ], 'union_bank_payment_code =\'' . $data['union_bank_payment_code'] . '\' and file_name =\'' . $data['file_name'] . '\' and is_file = 1 and union_code= \'' . $data['union_code'] . '\'')
                            ->execute();
                }
            }
        }
    }

    public function ReverseUpdate($response, $data) {
        $response = (array) $response;
        if (!empty($response['transfers'])) {
            $response['transfers'] = (array) $response['transfers'];
            $is_status_update = true;
            $statusCode = [
                'FAILED', 'MANUALLY_REJECTED', 'REJECTED', 'REVERSED', 'SUCCESS'
            ];
            $statusCodeList = ArrayHelper::getColumn($response['transfers'], 'status');
            $result = array_diff($statusCodeList, $statusCode);
            if (!empty($result)) {
                $is_status_update = false;
            }
            foreach ($response['transfers'] as $res) {
                $res = (array) $res;
                if (in_array($res['status'], $statusCode)) {
                    Yii::$app->db->createCommand()
                            ->update('tbl_payment_transaction', [
                                'disburse_amount' => new Expression("CASE WHEN '" . strtolower($res['status']) . "' != lower('success') THEN disburse_amount ELSE final_amount END"),
                                'status' => new Expression("CASE WHEN '" . strtolower($res['status']) . "' != lower('success') THEN status ELSE 'Disburse' END"),
                                'is_file' => '2',
                                'response_datetime' => date('Y-m-d H:i:s'),
                                'bank_status' => (strtolower($res['status']) == 'success') ? 'SUCCESSFUL' : 'FAILED',
                                'response_msg' => $res['status_code'],
                                'updated_at' => date('Y-m-d H:i:s'),
                                'utr_no' => !empty($res['transafer_utr']) ? $res['transafer_utr'] : '',
                                    ], 'payment_transaction_code = \'' . $res['transfer_id'] . '\' and  union_bank_payment_code =\'' . $data['union_bank_payment_code'] . '\' and file_name =\'' . $response['batch_transfer_id'] . '\' and is_file = 1 and union_code= \'' . $data['union_code'] . '\'')
                            ->execute();
                    $this->updateReverseStatus($res);
                } else {
                    Yii::$app->db->createCommand()
                            ->update('tbl_payment_transaction', [
                                'response_datetime' => date('Y-m-d H:i:s'),
                                'response_msg' => $res['status_code'],
                                'updated_at' => date('Y-m-d H:i:s'),
                                'utr_no' => !empty($res['transafer_utr']) ? $res['transafer_utr'] : '',
                                    ], 'payment_transaction_code = \'' . $res['transfer_id'] . '\' and  union_bank_payment_code =\'' . $data['union_bank_payment_code'] . '\' and file_name =\'' . $response['batch_transfer_id'] . '\' and is_file = 1 and union_code= \'' . $data['union_code'] . '\'')
                            ->execute();
                }
            }
            if ($is_status_update) {
                Yii::$app->db->createCommand()->update('tbl_bank_payment_log', [
                            'status' => 4,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => 'CRON'
                                ], ['file_name' => $response['batch_transfer_id'], 'status' => 2, 'union_bank_payment_code' => $data['union_bank_payment_code']])
                        ->execute();
            }
        }
        return;
    }

    public function updateReverseStatus($response) {
        $data = TblPaymentTransaction::find()->where(['payment_transaction_code' => $response['transfer_id']])->one();
        if (!empty($data)) {
            $table = (strtolower($data->type) == 'member') ? 'tbl_member_payment' : 'tbl_vsp_payment';
            $amountColumnName = (strtolower($data->type) == 'member') ? 'final_amount' : 'final_pay';
            $statusColumn = (strtolower($data->type) == 'member') ? 'payment_status' : 'status';
            $date = (strtolower($data->type) == 'member') ? date('Y-m-d H:i:s') : date('Y-m-d');
            $summaryUpadte = (strtolower($data->type) == 'member') ? true : false;
            Yii::$app->db->createCommand()
                    ->update($table, [
                        'disburse_amount' => new Expression("CASE WHEN '" . strtolower($response['status']) . "' != lower('success') THEN disburse_amount ELSE " . $amountColumnName . " END"),
                        '' . $statusColumn . '' => new Expression("CASE WHEN '" . strtolower($response['status']) . "' != lower('success') THEN " . $statusColumn . " ELSE 'Disburse' END"),
                        'disburse_date' => $date,
                        'bank_status' => (strtolower($response['status']) == 'success') ? 'SUCCESSFUL' : 'FAILED',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'utr_no' => !empty($response['transafer_utr']) ? $response['transafer_utr'] : '',
                            ], 'payment_transaction_code = \'' . $response['transfer_id'] . '\' and lower(' . $statusColumn . ')=\'sent\'')
                    ->execute();
            if ($summaryUpadte) {
                $memberPaymentModel = new TblMemberPayment();
                $disburseCount = $memberPaymentModel->find()->select('count(*) as count,payment_status')
                                ->where(['union_code' => $data->union_code, 'payment_cycle_code' => $data->dcs_payment_cycle_code, 'dcs_code' => $data->dcs_code])
                                ->groupBy(['payment_status'])->asArray()->all();
                $count = count($disburseCount);
                if ($count == 1 && strtolower($disburseCount[0]['payment_status']) == 'disburse') {
                    Yii::$app->db->createCommand()
                            ->update('tbl_member_payment_summary', [
                                'disburse_amount' => new Expression('final_amount'),
                                'disburse_date' => $date,
                                'payment_status' => 'Disburse',
                                    ], ['union_code' => $data->union_code, 'payment_cycle_code' => $data->dcs_payment_cycle_code, 'dcs_code' => $data->dcs_code, 'LOWER(payment_status)' => "sent"])
                            ->execute();
                }
            }
        }
        return;
    }

}
