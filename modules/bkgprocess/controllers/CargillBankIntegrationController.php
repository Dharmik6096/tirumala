<?php

namespace app\modules\bkgprocess\controllers;

use app\modules\payment\models\TblBankPaymentLog;
use app\modules\payment\models\TblPaymentTransaction;
use linslin\yii2\curl\Curl;
use yii;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\components\SBISecurity;
use app\modules\webservice\models\TblSbiApiLog;
use app\components\WebApi;
use yii\db\Expression;

class CargillBankIntegrationController extends Controller {

    public $freeAccessActions = ['upload-payment-data', 'get-transaction-status'];
    public $errorPath = '';

    public function init() {
        parent::init();
    }

    public function actionUploadPaymentData() {

        $master_data = [];
        $paymentTransaction = new TblPaymentTransaction();
        $paymentTransactiondata = $paymentTransaction->getCargillPendingData();
        if (!empty(($paymentTransactiondata))) {
            $paymenttransactioncodeList = ArrayHelper::getColumn($paymentTransactiondata, 'payment_transaction_code');
            $condition = ['payment_transaction_code' => $paymenttransactioncodeList, 'is_file' => 0];
            $updateData = ['is_file' => 1, 'pick_datetime' => date('Y-m-d H:i:s')];
            $paymentTransaction->updateStatus($condition, $updateData);
            foreach ($paymentTransactiondata as $payment) {
                $fileName = $payment['file_name'];
                try {
                    $type = !empty($payment['corporate_code']) ? $payment['corporate_code'] : 'SLIPS';
                    $bank_log = new TblBankPaymentLog();
                    $bank_log->union_code = $payment['union_code'];
                    $bank_log->file_path = $payment['TransactionID'];
                    $bank_log->file_name = $fileName;
                    $bank_log->status = 1; //created
                    $bank_log->payment_date = date('Y-m-d');
                    $bank_log->payment_for = $payment['type'];
                    $bank_log->created_by = 'CRON';
                    $bank_log->created_at = date('Y-m-d H:i:s');
                    $bank_log->union_bank_payment_code = $payment['union_bank_payment_code'];
                    $bank_log->save();
                    $body = $this->prepareJson($payment);
                    $url = $payment['payment_url'];
                    $main_header = array("Content-Type: application/json");
                    $api = new WebApi();
                    $api->serverUrl = $payment['payment_url'];
                    $api->body = $body;
                    $api->return_actual = TRUE;
                    $api->header_info = $main_header;
                    $curl = $api->ExchangeDataCurl();

                    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
                        $response = json_decode($curl, true);
                        if (strtolower($type) != 'slips') {
                            $this->ReverseUpdate($response, $data);
                        } else {
                            $condition = ['file_path' => $payment['TransactionID'], 'union_bank_payment_code' => $payment['union_bank_payment_code'], 'file_name' => $fileName, 'status' => 1];
                            $updateData = ['status' => 2, 'file_status' => 'success', 'file_status_desc' => 'transaction sent to bank'];
                            $bank_log->updateStatus($condition, $updateData);
                        }
                    } else {
                        $condition = ['file_path' => $payment['TransactionID'], 'union_bank_payment_code' => $payment['union_bank_payment_code'], 'file_name' => $fileName, 'status' => 1];
                        $updateData = ['status' => 3, 'file_status' => 'API Failure', 'file_status_desc' => 'transaction pending'];
                        $bank_log->updateStatus($condition, $updateData);
                        Yii::$app->db->createCommand()
                                ->update('tbl_payment_transaction', [
                                    'is_file' => '0',
                                    'response_datetime' => date('Y-m-d H:i:s'),
                                    'response_msg' => 'API Failure'], 'payment_transaction_code = \'' . $payment['TransactionID'] . '\' and  union_bank_payment_code =\'' . $payment['union_bank_payment_code'] . '\' and file_name =\'' . $fileName . '\' and is_file = 1 and union_code= \'' . $payment['union_code'] . '\'')
                                ->execute();
                    }
                } catch (\Throwable $ex) {
                    // $logData->save(false);
//                    echo '<pre>';
//                    print_r($ex);
//                    die;
                    return;
                }
            }
        }
    }

    public function actionGetTransactionStatus() {

        $bankPayment = new TblBankPaymentLog();
        $bankPaymentData = $bankPayment->getDataForMIS();
        $params = yii::$app->params['CARGILL_BANK_INTEGRATION'];
        foreach ($bankPaymentData as $data) {
            if (!empty($params)) {
                try {
                    $matser = [];
                    $master['SecurityToken'] = $params['security_token'];
                    $master['sessionID'] = $params['session_id'];
                    $master['TransactionID'] = $data['TransactionID']; //payment_transaction_code stored in file_path column 
                    $request_body = json_encode($master);
                    $url = $data['reverse_check_url'];
                    $main_header = array("Content-Type: application/json");
                    $api = new WebApi();
                    $api->serverUrl = $url;
                    $api->body = $request_body;
                    $api->return_actual = TRUE;
                    $api->header_info = $main_header;
                    $curl = $api->ExchangeDataCurl();

                    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
                        $response = json_decode($curl, true);
                        $this->ReverseUpdate($response, $data);
                    }
                } catch (\Throwable $ex) {
                    //$logData->save(false);
                    // var_dump($ex);
                    return;
                }
            }
        }
    }

    public function ReverseUpdate($response, $data) {

        $paymentTransaction = new TblPaymentTransaction();
        Yii::$app->db->createCommand()
                ->update('tbl_payment_transaction', [
                    'disburse_amount' => new Expression("CASE WHEN '" . strtolower($response['Status']) . "' != lower('Successful') AND " . $response['StatusCode'] . "!= \'000\' THEN disburse_amount ELSE final_amount END"),
                    'status' => new Expression("CASE WHEN '" . strtolower($response['Status']) . "' != lower('Successful')AND " . $response['StatusCode'] . "!= \'000\' THEN status ELSE 'Disburse' END"),
                    'is_file' => '2',
                    'response_datetime' => date('Y-m-d H:i:s'),
                    'bank_status' => $response['Status'],
                    'response_msg' => $response['StatusDescription'],
                        ], 'payment_transaction_code = \'' . $response['TransactionID'] . '\' and  union_bank_payment_code =\'' . $data['union_bank_payment_code'] . '\' and file_name =\'' . $data['file_name'] . '\' and is_file = 1 and union_code= \'' . $data['union_code'] . '\'')
                ->execute();
        $paymentTransaction->updateReverseStatus($response);
        if (strtolower($response['Status']) == 'successful') {
            Yii::$app->db->createCommand()->update('tbl_bank_payment_log', [
                        'status' => 4,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => 'CRON'], ['file_path' => $response['TransactionID'], 'file_name' => $data['file_name'], 'status' => 2, 'union_bank_payment_code' => $data['union_bank_payment_code']])
                    ->execute();
        }
    }

    public function prepareJson($payment) {

        $params = yii::$app->params['CARGILL_BANK_INTEGRATION'];
        $body = [];
        $body['SecurityToken'] = $params['security_token'];
        $body['SessionID'] = $params['session_id'];
        $body['TransactionID'] = $payment['TransactionID'];
        $body['currencyCode'] = '144';
        $body['BenBankCode'] = $payment['BenBankCode'];
        $body['BenBranchCode'] = $payment['BenBranchCode'];
        $body['BenAccNo'] = $payment['BenAccNo'];
        $body['BenAccName'] = $payment['BenAccName'];
        $body['TxnCode'] = $payment['TxnCode'];
        $body['TxnAmount'] = $payment['TxnAmount'];
        $body['DebitBankCode'] = $payment['DebitBankCode'];
        $body['DebitAccountNo'] = $payment['DebitAccountNo'];
        $body['DebitAccName'] = $payment['DebitAccName'];
        $body['ValueDate'] = $payment['ValueDate'];
        $body['TransactionType'] = $payment['TransactionType'];
        $request_json = json_encode($body);
        return $request_json;
    }
}
