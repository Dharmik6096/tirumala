<?php

namespace app\modules\bkgprocess\controllers;

use yii;
use app\controllers\ChildController;
use app\modules\bkgprocess\Bkgprocess;
use yii\helpers\Url;
use PHPExcel;
use app\modules\bkgprocess\models\TblDataExchangeConfig;
use app\components\WebApi;
use app\modules\clienterp\models\TblDataExchangeLog;
use DOMDocument;
use SoapClient;
use app\models\TblPortalDataPostLog;

class DataExchangeController extends ChildController {

    public $freeAccessActions = ['data-exchange'];
    public $errorPath = '';
    private $toEncrypt = ['Mdob', 'Ndob', 'Adharno', 'Fdob'];

    public function init() {
        parent::init();
//$this->errorPath = Yii::$app->params['FTPDirPath'] . 'ErrorLogs/FTP';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
        ];
    }

    public function actionDataExchange() {
        $configModel = new TblDataExchangeConfig();
        $data = $configModel->getDataExchangeConfig();
        $this->postData($data);
    }

    public function postData($data) {
        foreach ($data as $key => $value) {
            $update_ids = [];
            $body = [];
            $sp_name = $value['sp_name'];
            $json_array_key = !empty($value['json_key']) ? $value['json_key'] : '';
            $sp_param = [];
            $sp_param[] = $value['union_code'];
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            if (!empty($output)) {
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $futureDate = $currentDate + (60 * $value['interval']);
                $formatDate = date("Y-m-d H:i:s", $futureDate);
                $value->updateAll(['last_execution' => date('Y-m-d H:i:s'), 'next_execution' => $formatDate], ['data_exchange_code' => $value['data_exchange_code']]);

                $modelName = $value['tbl_name'];
                $model_name = Yii::$app->path->define($modelName);
                $model = new $model_name();
                $modelKey = $value['update_key'];
                $updateKey = $value['update_key_with'];
                $update_ids = array_column($output, $updateKey);
                $json_array_key = $value['json_key'] ?? '';
                $apiType = !empty($value['api_type']) ? strtoupper($value['api_type']) : '';
                $authentication_key = $value['authentication_key'] ?? '';
                $authData = json_decode($authentication_key, true);
                $body_auth = $authData['body'] ?? [];
                if (!empty($apiType) && $apiType == 'XML') {
                    $headers = [
                        'Content-Type: application/soap+xml;charset=UTF-8',
                        'Cookie: sap-usercontext=sap-client=100',
                    ];
                    if (!empty($authData['header']) && is_array($authData['header'])) {
                        foreach ($authData['header'] as $key => $val) {
                            $headers[] = $key . ': ' . $val;
                        }
                    }
                    $context = stream_context_create([
                        'http' => [
                            'header' => implode("\r\n", $headers)
                        ]
                    ]);
                    $client = new SoapClient(null, [
                        'location' => $value['request_url'],
                        'uri' => 'urn:sap-com:document:sap:rfc:functions',
                        'trace' => 1,
                        'exceptions' => true,
                        'soap_version' => SOAP_1_2,
                        'cache_wsdl' => WSDL_CACHE_NONE,
                        'stream_context' => $context,
                    ]);

                    $postData = $this->generateSoapXml($output, $value);

                    $response = '';
                    $log_model = new TblPortalDataPostLog();
                    $log_model->created_at = date('Y-m-d H:i:s');
                    $log_model->vendor_code = 'EIPL';
                    $log_model->url = $value['request_url'];
                    $log_model->request = $postData;

                    try {
                        $response = $client->__doRequest($postData, $value['request_url'], '', SOAP_1_2, false);
                        $status = 2;
                        $log_model->status = 1;
                        $log_model->response = $response;
                        $log_model->updated_at = date('Y-m-d H:i:s');
                        $log_model->save();
                    } catch (\Throwable $ex) {
                        $status = 3;
                        $log_model->status = 0;
                        $log_model->response = "SOAP Error: " . htmlspecialchars($ex->getMessage());
                        $log_model->updated_at = date('Y-m-d H:i:s');
                        $log_model->save();
                    }
                } else {
                    $model->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], [$modelKey => $update_ids]);

                    $postData = !empty($output) ? true : false;
                    if (!empty($json_array_key)) {
                        $body[$json_array_key] = $output;
                    } else {
                        $body = !empty($output[0]) ? $output[0] : [];
                    }
                    $postData = [];
                    if (!empty(Yii::$app->params['data_exchange_vendor_code'])) {
                        $body['code'] = Yii::$app->params['data_exchange_vendor_code'];
                        $postData['params'] = $body;
                    } else {
                        $postData = $body;
                    }
                    $postData = json_encode($postData);

                    $api = new WebApi();
                    $api->serverUrl = $value['request_url'];
                    $api->authentication = FALSE;
                    $api->vendor_code = !empty($body['code']) ? $body['code'] : '';
                    $api->body = $postData;

                    $response = $api->ExchangeData();
                }

                if ($value['api_type'] == 'XML' && !empty($response)) {
                    $this->processXmlResponse($response, $sp_name, $value, $output, $postData);
                } else {
                    $responseData = json_decode(json_encode($response), true);
                    $loopData = [];
                    if (empty($json_array_key) && !empty($responseData['result'])) {
                        $loopData[] = $responseData['result'];
                    } else if (!empty($responseData['result']['data'])) {
                        $loopData = !empty($responseData['result']['data']) ? $responseData['result']['data'] : [];
                    } else if (empty(Yii::$app->params['data_exchange_vendor_code']) && !empty($responseData['data'])) {
                        $loopData = $responseData['data'];
                    }
                    if (!empty($loopData)) {
                        foreach ($loopData as $resp_data) {
                            $status = (isset($resp_data['status']) && (strtolower($resp_data['status']) == '200')) ? 2 : 3;
                            $resp_status = !empty($resp_data['status']) ? $resp_data['status'] : NULL;
                            $resp_desc = !empty($resp_data['msg']) ? $resp_data['msg'] : NULL;
                            $updateValue = !empty($resp_data[$updateKey]) ? $resp_data[$updateKey] : '';
                            $model = new $model_name();
                            $model->updateAll(['data_post_status' => $status, 'resp_status' => $resp_status, 'resp_desc' => $resp_desc, 'response_datetime' => date('Y-m-d H:i:s')], [$modelKey => $updateValue]);
                        }
                    }
                }
            }
        }
    }

    private function processXmlResponse($soapResponse, $sp_name, $exchangeData, $output, $request) {
        $status = 0;
        $xml = simplexml_load_string($soapResponse);
        $namespaces = $xml->getNamespaces(true);
        foreach ($namespaces as $prefix => $uri) {
            $xml->registerXPathNamespace($prefix, $uri);
        }
        $updateKeys = explode(',', $exchangeData['update_key']);
        $resParamKeys = explode(',', $exchangeData['res_param_keys']);

        foreach ($xml->xpath('//env:Body//item') as $item) {
            $itemData = [];
            foreach ($item as $child) {
                $itemData[$child->getName()] = (string) $child;
            }
            $whereParts = array_map(function ($key) use ($itemData) {
                return isset($itemData[$key]) ? $itemData[$key] : '';
            }, $updateKeys);
            $whereKey = implode('-', $whereParts);

            $resParams = [];
            $status = 0;
            foreach ($resParamKeys as $key) {
                $resParams[] = $itemData[$key] ?? '';
                if ($key == 'Type' && isset($itemData[$key])) {
                    $status = ($itemData[$key] == 'S') ? 2 : 3;
                }
            }
            foreach ($output as $data) {
                $sp_res_param = array_merge([$whereKey], [$data['process_name'], $data['process_code']], [$status], $resParams);
                \Yii::$app->general->getSpData('sp_data_exchange_log_update', $sp_res_param, true);
            }
        }
    }

    private function generateSoapXml($data, $value) {
        $tags = !empty($value['json_key']) ? explode(',', $value['json_key']) : [];
        if (empty($tags)) {
            return '';
        }
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $envelope = $doc->createElementNS(Yii::$app->params['data_exchange_url'], 'soap:Envelope');
        $envelope->setAttribute('xmlns:soap', Yii::$app->params['data_exchange_url']);
        $envelope->setAttribute('xmlns:urn', 'urn:sap-com:document:sap:rfc:functions');
        $doc->appendChild($envelope);

        $envelope->appendChild($doc->createElement('soap:Header'));
        $body = $doc->createElement('soap:Body');
        $envelope->appendChild($body);

        $parent = $body;
        $no_of_tags = count($tags) - 1;
        foreach ($tags as $key => $tagName) {
            if ($key != $no_of_tags) {
                $element = $doc->createElement($tagName);
                $parent->appendChild($element);
                $parent = $element;
            }
        }
        $lastTag = end($tags);
        foreach ($data as $itemData) {
            unset($itemData['eiplCode'], $itemData['process_name'], $itemData['process_code']);
            $item = $doc->createElement($lastTag);
            foreach ($itemData as $key => $value) {
                if (!empty($value) && in_array($key, $this->toEncrypt)) {
                    $decryptedData = Yii::$app->general->decryptData($value);
                    $value = $decryptedData !== false ? $decryptedData : $value;
                }
                $child = $doc->createElement($key);
                $child->appendChild($doc->createTextNode($value ?? ''));
                $item->appendChild($child);
            }

            $parent->appendChild($item);
        }
        $xml = $doc->saveXML();
        return $xml;
    }

}
