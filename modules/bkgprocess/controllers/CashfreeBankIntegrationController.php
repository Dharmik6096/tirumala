<?php

namespace app\modules\bkgprocess\controllers;

use app\components\WebApi;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\payment\models\TblPaymentTransaction;
use Yii;
use yii\base\Controller;
use yii\db\Expression;

class CashfreeBankIntegrationController extends Controller {

    public $freeAccessActions = ['upload-payment-data-bulk', 'get-transaction-status-bulk'];

    public function actionUploadPaymentDataBulk() {
        
    }

    public function actionGetTransactionStatusBulk() {
        $bankPaymentLogData = TblBankPaymentLog::find()->alias('bl')->select(['bl.union_bank_payment_code','bl.file_path as TransactionID', 'ba.payment_url', 'bl.file_name', 'bl.union_code'])
                        ->innerJoin('tbl_union_bank_payment as ubp', 'ubp.union_bank_payment_code = bl.union_bank_payment_code')
                        ->innerJoin('tbl_bank_api_detail ba', 'ubp.union_bank_payment_code= ba.union_bank_payment_code')
                        ->where(['bl.status' => 2, 'ba.is_active' => 1, 'ubp.is_active' => 1, 'UPPER(ubp.integration_mode)' => 'API'])
                        ->andWhere(['NOT', ['bl.statuss' => 4, 'bl.status'=>3]])
                        ->groupBy(['bl.file_path','bl.file_name', 'bl.union_bank_payment_code', 'bl.union_code', 'ba.payment_url', 'ba.reverse_check_url'])
                        ->limit(5)->asArray()->all();
       
        if(!empty($bankPaymentLogData)){
            foreach ($bankPaymentLogData as $data) {
                $base_url = \Yii::$app->params['bank_verification']['payment_url']; // $data['payment_url'];
                $body = array(
                    'batch_transfer_id' => $data['file_name']
                );
                $api = new WebApi();
                $api->header_info['Content-Type'] = 'application/json';
                $api->header_info['Content-length'] = strlen(json_encode($body));
                $api->header_info['x-client-id'] = \Yii::$app->params['bank_verification']['header']['x-client-id'];
                $api->header_info['x-client-secret'] = \Yii::$app->params['bank_verification']['header']['x-client-secret'];
                $api->is_header_merge = false;
                $api->return_actual = true;
                $api->serverUrl = $base_url;
                $api->body = $body;
                try {
                    $result = $api->GuzzleCURL('GET');
                    echo '<pre>';
                    print_r($result);
                    echo '</pre>';
                    die;
                    $httpCode = $result->getStatusCode();
                    $response = $result->getBody()->getContents();
                    $response = !empty($response) ? json_decode($response) : [];
                    $this->ReverseUpdate($response, $data);
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    $responseData = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents()) : '';
                    $response = $responseData->error;
                }
            }
        }
    }

    public function ReverseUpdate($response, $data) {
        if (in_array(strtolower($response['status']), ['successful', 'failed'])) {
            $paymentTransaction = new TblPaymentTransaction();
            Yii::$app->db->createCommand()
                    ->update('tbl_payment_transaction', [
                        'disburse_amount' => new Expression("CASE WHEN '" . strtolower($response['status']) . "' != lower('Successful') AND '" . $response['statusCode'] . "' != '000' THEN disburse_amount ELSE final_amount END"),
                        'status' => new Expression("CASE WHEN '" . strtolower($response['status']) . "' != lower('Successful')AND '" . $response['statusCode'] . "'!= '000' THEN status ELSE 'Disburse' END"),
                        'is_file' => '2',
                        'response_datetime' => date('Y-m-d H:i:s'),
                        'bank_status' => $response['status'],
                        'response_msg' => $response['statusDescription'],
                        'utr_no' => $response['replyID'],
                            ], 'payment_transaction_code = \'' . $response['txnID'] . '\' and  union_bank_payment_code =\'' . $data['union_bank_payment_code'] . '\' and file_name =\'' . $data['file_name'] . '\' and is_file = 1 and union_code= \'' . $data['union_code'] . '\'')
                    ->execute();
            $paymentTransaction->updateReverseStatus($response);
            Yii::$app->db->createCommand()->update('tbl_bank_payment_log', [
                        'status' => 4,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => 'CRON'], ['file_path' => $response['txnID'], 'file_name' => $data['file_name'], 'status' => 2, 'union_bank_payment_code' => $data['union_bank_payment_code']])
                    ->execute();
        }
        return;
    }

}
