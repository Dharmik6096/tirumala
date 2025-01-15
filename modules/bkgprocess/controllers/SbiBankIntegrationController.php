<?php

namespace app\modules\bkgprocess\controllers;

use app\components\EncryptionHelper;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use yii\web\Controller;
use app\modules\payment\models\TblPaymentTransaction;
use app\modules\payment\models\TblUnionBankPayment;
use DOMDocument;
use Yii;

class SbiBankIntegrationController extends Controller
{
    public $freeAccessActions = ['generate-xml-payment-data'];

    public function actionGenerateXmlPaymentData()
    {
        $paymentTransaction = new TblPaymentTransaction();
        $transactionGroup = $paymentTransaction->getTransactionPendingData();
        if (!empty($transactionGroup)) {
            foreach ($transactionGroup as $transValue) {
                $transactions = $paymentTransaction->getTransactionData($transValue['FileID']);
                if ($transactions) {
                    $ftpDetails = new TblUnionBankPayment();
                    $ftpDetails->setAttributes($transValue);

                    $union_code = $transValue['union_code'];
                    unset($transValue['union_code'], $transValue['ftp_type'], $transValue['ftp_host'], $transValue['ftp_username'], $transValue['ftp_password'], $transValue['ftp_port'], $transValue['ftp_path']);

                    $fileName = $transValue['FileID'].'.xml';
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->formatOutput = true;

                    $filePaymentData = $dom->createElement('FilePaymentData');
                    $dom->appendChild($filePaymentData);

                    $requestPayload = $dom->createElement('RequestPayload');
                    $filePaymentData->appendChild($requestPayload);

                    $fileHdr = $dom->createElement('FileHdr');
                    $requestPayload->appendChild($fileHdr);

                    foreach($transValue as $tkey => $tvalue){
                        $fileHdr->appendChild($dom->createElement($tkey, $tvalue));
                    }

                    $pmtInf = $dom->createElement('PmtInf');
                    $requestPayload->appendChild($pmtInf);

                    foreach ($transactions as $transaction) {
                        
                        $cdtTrnInf = $dom->createElement('CdtTrnInf');
                        $pmtInf->appendChild($cdtTrnInf);

                        foreach ($transaction as $key => $value) {
                            if ($key !== 'FileName') {
                                $cdtTrnInf->appendChild($dom->createElement($key, $value));
                            }
                        }
                    }
                    $bank_integration_xml_config = \Yii::$app->params['bank_integration_xml_config'];
                    $filePaymentData->appendChild($dom->createElement('Signature', !empty($bank_integration_xml_config) ? $bank_integration_xml_config['signature'] : ''));
                    $folderPath = Yii::getAlias('@webroot') . Yii::$app->params['payment_xml_upload'];
                    if (!is_dir($folderPath)) {
                        Yii::$app->general->CreateDirectory($folderPath);
                    }
                    $filePath = $folderPath . $fileName;
                    $dom->save($filePath);
                    try {
                        $encryptionResult = EncryptionHelper::encrypt($filePath, true);
                        if ($encryptionResult) {
                            $condition = ['file_name' => $transValue['FileID'], 'is_file' => 0];
                            $updateData = ['is_file' => 1, 'pick_datetime' => date('Y-m-d H:i:s')];
                            $paymentTransaction->updateStatus($condition, $updateData);

                            $ftp = new TblFtpTxnLog();
                            $transValue['union_code'] = $union_code;
                            $saveLog = $ftp->saveLogData($transValue, $filePath, $fileName, 1, true, false, false, true, '', false, $ftpDetails);
                            if($saveLog != false){
                                $condition = ['file_name' => $transValue['FileID'], 'is_file' => 1];
                                $updateData = ['is_file' => 2, 'response_datetime' => date('Y-m-d H:i:s')];
                                $paymentTransaction->updateStatus($condition, $updateData);
                            }
                        }
                    } catch (\Throwable $ex) {
                        $condition = ['file_name' => $transValue['FileID'], 'is_file' => 1];
                        $updateData = ['is_file' => 3, 'response_datetime' => date('Y-m-d H:i:s'), 'response_msg' => $ex->getMessage()];
                        $paymentTransaction->updateStatus($condition, $updateData);
                        var_dump($ex->getMessage());
                    }
                }
            }
        }
    }
}
