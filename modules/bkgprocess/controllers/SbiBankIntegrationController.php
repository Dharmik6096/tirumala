<?php

namespace app\modules\bkgprocess\controllers;

use yii\web\Controller;
use app\modules\payment\models\TblPaymentTransaction;
use DOMDocument;

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
                        header('Content-Type: application/xml');
                        header("Content-Disposition: attachment; filename=\"{$transValue['FileID']}.xml\"");
                        echo $dom->saveXML();
                        die;
                }
            }
        }
    }
}
