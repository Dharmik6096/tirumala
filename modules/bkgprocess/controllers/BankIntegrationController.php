<?php

namespace app\modules\bkgprocess\controllers;

use app\controllers\ChildController;
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

class BankIntegrationController extends ChildController {

    public $freeAccessActions = ['upload-payment-data', 'get-transaction-status'];
    public $errorPath = '';
    private $security;

    public function init() {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    public function actionUploadPaymentData() {

        $master_data = [];
        $paymentTransaction = new TblPaymentTransaction();
        $paymentTransactiondata = $paymentTransaction->getPendingData();
        if (!empty(($paymentTransactiondata))) {
            $fileList = ArrayHelper::getColumn($paymentTransactiondata, 'file_name');
            $unionBankList = ArrayHelper::getColumn($paymentTransactiondata, 'union_bank_payment_code');
            $condition = ['union_bank_payment_code' => $unionBankList, 'file_name' => $fileList, 'is_file' => 0];
            $updateData = ['is_file' => 1, 'pick_datetime' => date('Y-m-d H:i:s')];
            $paymentTransaction->updateStatus($condition, $updateData);
            foreach ($paymentTransactiondata as $payment) {
                $fileName = $payment['file_name'];
                try {
                    $params = yii::$app->params['CARGILL_BANK_INTEGRATION'];
                    $result = $paymentTransaction->getCargillMemberPaymentData($fileName, $payment['bank_account_no'], $payment['bank_code'], $payment['branch_code'], $payment['account_holder_name'], $params['session_id'], $params['security_token'], $params['sec_no']);

                    foreach ($result as $transaction) {

                        $bank_log = new TblBankPaymentLog();
                        $bank_log->union_code = $payment['union_code'];
                        $bank_log->payment_transaction_code = $transaction['TransactionId'];
                        $bank_log->file_path = $fileName;
                        $bank_log->file_name = $fileName;
                        $bank_log->status = 1; //created
                        $bank_log->payment_date = date('Y-m-d');
                        $bank_log->payment_for = 'member';
                        $bank_log->created_by = 'CRON';
                        $bank_log->created_at = date('Y-m-d H:i:s');
                        $bank_log->union_bank_payment_code = $payment['union_bank_payment_code'];
                        $bank_log->save();
                        $body = json_encode($transaction);
                        $url = $payment['payment_url'];
                        $main_header = array("Content-Type: application/json");
                        $api = new \app\components\WebApi();
                        $api->serverUrl = $payment['payment_url'];
                        $api->body = $body;
                        $api->return_actual = TRUE;
                        $api->header_info = $main_header;
                        $curl = $api->ExchangeDataCurl();

                        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
                             $condition = ['payment_transaction_code'=>$transaction['TransactionId'],'union_bank_payment_code' => $payment['union_bank_payment_code'], 'file_name' => $fileName, 'status' => 1];
                             $updateData = ['status' => 2, 'file_status'=>'success','file_status_desc'=>'transaction sent to bank'];
                            $bank_log->updateStatus($condition, $updateData);
                        } else {
                            $condition = ['payment_transaction_code'=>$transaction['TransactionId'],'union_bank_payment_code' => $payment['union_bank_payment_code'], 'file_name' => $fileName, 'status' => 1];
                            $updateData = ['status' => 3, 'file_status'=>'API Failure','file_status_desc'=>'transaction pending'];
                            $bank_log->updateStatus($condition, $updateData);
                            Yii::$app->db->createCommand()
                                    ->update('tbl_payment_transaction', [
                                        'is_file' => '0',
                                        'response_datetime' => date('Y-m-d H:i:s'),
                                        'response_msg' => 'API Failure'],
                                           'payment_transaction_code = \''.$transaction['TransactionId'].'\' and  union_bank_payment_code =\'' . $payment['union_bank_payment_code'] . '\' and file_name =\'' . $fileName . '\' and is_file = 1 and union_code= \'' . $payment['union_code'] . '\'')
                                    ->execute();
                        }
                    }
                } catch (\Throwable $ex) {
                    // $logData->save(false);
//                    var_dump($ex);
                    return;
                }
            }
        }
    }

    public function actionGetTransactionStatus() {

        $paymentTransaction = new TblBankPaymentLog();
        $paymentTransactionData = $paymentTransaction->getDataForMIS();
        foreach ($paymentTransactionData as $data) {
            if (!empty($token)) {
                try {
                    $matser = [];
                    $master['CustomerId'] = $data['corporate_code'];
                    $master['PayloadRefId'] = $data['file_name'];
                    $request = [];
                    $request['MISRequest'] = $this->security->RSAEncryption(json_encode($master));
                    $request_body = json_encode($request);
                    $url = $data['reverse_check_url'];
                    if ($curl['response_code'] == 200) {
                        $ack = json_decode($curl['response_body'], true);
                        $logData->enc_response = json_encode($ack);
                        // $ack = json_decode('{"MISResponse":"JlzmiUgpvuaw9MS/9G3HtIa5U0jznTYKUAkxuXRXjsHDTyBAiBJCz54saRp/UbUV82sTkk2Si7MjRub4u/6L2HJn/lLqBuiKEd5ZogOdlD5heFZwQQa4H0C0BDzemZFMcDGPkQO+Em3b03z90Z3izGedgaSofIl+/wV3cddUc9Ois/r1yvuFEGwjjakhx0eKTagaQ8OjSST3n+SX5PyhAX/67OPKLUZk/WbQKo0KDOjDLvo8oVLWAtZRZNwdB71gdGxB0bOr/2PfNHjTk3k7A2pRXmakguk4fMS+zZIn","SessionKey":"E1ab8hxpHK+UQm4GXhvfXGsSp3XI0wnPbGlsbBUrgkXafx4mpJbLYFkwmYH/ec/SdEnwMd0Zpq1me056xFcr5bWX7UwAnTWMwqdqf6ZNPJmXB+i2ErHq7Gc1XgsnZrPGZNWsGUnMbyE1iSyHfcYYvisTH+Zt25+TGa7A1ohi+kXEZg3GL0wzhWYzNt08huklOOilRwqn0zhHKh18ghAiwk4nJRamCbwVzfFzvd2BedJXbkE0LEtpZohRn6zL0cVHJ6DYLuDNMpP+o2VICNs1ljXlfb2W317gOuPPjo7EfrZxSqi5yHDVjFkQHzMYQtZpg+XgnJVRnD6OEJk+P8kshw=="}', true);
                        $response = $this->security->decrypt($ack['MISResponse'], $this->security->RSADecryption($ack['SessionKey']));
                        $logData->plain_response = $response;
                        $mis_response = json_decode($response, true);
                        //  $mis_response = json_decode('{"CustomerId":"28xxxx","PayloadRefId":"42342565625","MISData":[{"PaymentReferenceNo":"XXXXX","UTR":"SBIN0012345","ProcessedDate":"31-07-2019","Status":"success","Reason":""},{"PaymentReferenceNo":"XXXXX","UTR":"SBIN0012345","ProcessedDate":"31-07-2019","Status":"success","Reason":""}, { "PaymentReferenceNo":"XXXXX","UTR":"SBIN0012345","ProcessedDate":"31-07-2019","Status":"success","Reason":""}]}', true);
                        $file_name = $mis_response['PayloadRefId'];
                        $union_bank_payment_code = $data['union_bank_payment_code'];
                        foreach ($mis_response['MISData'] as $response) {
                            if ($response['PaymentReferenceNo'] != '') {
                                Yii::$app->db->createCommand()
                                        ->update('tbl_payment_transaction', [
                                            'disburse_amount' => new \yii\db\Expression("CASE WHEN '" . strtolower($response['Status']) . "' != lower('Success') THEN 0 ELSE final_amount END"),
                                            'response_datetime' => date('Y-m-d H:i:s'),
                                            'utr_no' => $response['UTR'],
                                            'process_date' => $response['ProcessedDate'],
                                            'bank_status' => $response['Status'],
                                            'response_msg' => $response['Reason']],
                                                'payment_transaction_code = \'' . $response['PaymentReferenceNo'] . '\' and union_bank_payment_code =' . $union_bank_payment_code . ' and file_name =\'' . $file_name . '\' and is_file = 1 and ( bank_status = \'Pending\' or ISNULL(bank_status,\'\')=\'\') ')
                                        ->execute();
                            }
                        }

                        $response_count = count($mis_response['MISData']);
                        $transaction_count = TblPaymentTransaction::find()->where('file_name = \'' . $file_name . '\' and ( bank_status !=\'Pending\' OR ISNULL(bank_status,\'\')!= \'\')')->count();
                        if ($response_count == $transaction_count) {
                            Yii::$app->db->createCommand()->update('tbl_bank_payment_log', [
                                        'status' => 4,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        'updated_by' => 'CRON'],
                                            ['file_name' => $file_name, 'status' => 2, 'union_bank_payment_code' => $data['union_bank_payment_code']])
                                    ->execute();
                        }
                    }
                } catch (\Throwable $ex) {
                    //$logData->save(false);
                    // var_dump($ex);
                    return;
                } finally {
                    $logData->save(false);
                }
            }
        }
    }
}
