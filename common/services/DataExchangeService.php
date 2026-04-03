<?php

namespace common\services;

use Yii;
use app\components\WebApi;
use app\models\TblPortalDataPostLog;
use app\modules\bkgprocess\models\TblDataExchangeConfig;

class DataExchangeService {

    public function processComfedCollection() {
        try {
            $configModel = new TblDataExchangeConfig();
            $configModel->api_type = 'COMFED';
            $configs = $configModel->getDataExchangeConfig(1);
            $config = $configs[0];
            $records = \Yii::$app->general->getSpData($config->sp_name, [], FALSE);
            if (empty($records)) {
                return false;
            }

            if (!empty($records)) {
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $futureDate = $currentDate + (60 * $config->interval);
                $formatDate = date("Y-m-d H:i:s", $futureDate);
                $config->updateAll(['last_execution' => date('Y-m-d H:i:s'), 'next_execution' => $formatDate], ['data_exchange_code' => $config->data_exchange_code]);

                $groups = [];
                foreach ($records as $record) {
                    $eventId = $record['eventId'];
                    if (!isset($groups[$eventId])) {
                        $groups[$eventId] = [
                            'metadata' => [
                                "dcsNo" => (string) $record['dcsNo'],
                                "collectionDate" => (string) $record['collectionDate'],
                                "shift" => (string) $record['shift'],
                                "entity" => "COLLECTION",
                                "eventId" => (string) $record['eventId']
                            ],
                            'collectionEntryList' => []
                        ];
                    }

                    $groups[$eventId]['collectionEntryList'][] = [
                        "fat" => (float) $record['fat'],
                        "snf" => (float) $record['snf'],
                        "rate" => (float) $record['rate'],
                        "kgQty" => (float) $record['kgQty'],
                        "ltrQty" => (float) $record['ltrQty'],
                        "amount" => (float) $record['amount'],
                        "status" => (string) $record['status'],
                        "frNo" => (string) $record['frNo'],
                        "milkType" => $record['milkType'],
                        "collectionTime" => $record['collectionTime'],
                        "transactionId" => (string) $record['transactionId'],
                        "eventType" => $record['eventType']
                    ];
                }

                foreach ($groups as $eventId => $group) {
                    try {
                        $body = [
                            'metadata' => $group['metadata'],
                            'data' => [
                                'collectionEntryList' => $group['collectionEntryList']
                            ]
                        ];

                        $api = new WebApi();
                        $api->vendor_code = $eventId;
                        $api->serverUrl = $config->request_url;
                        $api->authentication = false;
                        $api->header_info = ["Authorization: Bearer " . $config->authentication_key];
                        $api->body = json_encode($body);

                        $response = $api->ExchangeData();
                        $responseData = json_decode($response, true);
           
                        $respStatus = 3;
                        $respMsg = 'Response Not Parsed.';
                        if (isset($responseData['status']) && $responseData['status'] == 'success') {
                            $data_post_status = 2;
                            $respStatus = $responseData['status'];
                            $respMsg = $responseData['data']['message'];
                            $status = 1;
                        } else {
                            $data_post_status = 3;
                            $respStatus = $responseData['status'];
                            $respMsg = $responseData['message'];
                            $status = 0;
                        }
                        $sp_res_param = [$eventId, $data_post_status, $respStatus, $respMsg];
                        $records = \Yii::$app->general->getSpData('sp_data_exchange_log_update_comfed', $sp_res_param, TRUE);
                    } catch (\Throwable $e) {
                        try {
                            $sp_res_param = [$eventId, 3, 'Error', substr($e->getMessage(), 0, 250)];
                            $records = \Yii::$app->general->getSpData('sp_data_exchange_log_update_comfed', $sp_res_param, TRUE);
                        } catch (\Throwable $e) {
                            
                        }
                    }
                }
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            try {
                $sp_res_param = [$eventId, 3, 'Error', substr($e->getMessage(), 0, 250)];
                $records = \Yii::$app->general->getSpData('sp_data_exchange_log_update_comfed', $sp_res_param, TRUE);
            } catch (\Throwable $e) {
                
            }
            return false;
        }
    }

}
