<?php

namespace common\services;

use Throwable;
use Yii;
use app\components\WebApi;
use app\modules\bkgprocess\models\TblDataExchangeConfig;

class DataExchangeService {

    public function processComfedCollection() {
        try {
            $configModel = new TblDataExchangeConfig();
            $configModel->api_type = 'COMFED';
            $configs = $configModel->getDataExchangeConfig();

            if (empty($configs)) {
                return false;
            }
            foreach ($configs as $config) {
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $futureDate = $currentDate + (60 * $config->interval);
                $formatDate = date("Y-m-d H:i:s", $futureDate);
                $config->updateAll(['last_execution' => date('Y-m-d H:i:s'), 'next_execution' => $formatDate], ['data_exchange_code' => $config->data_exchange_code]);

                if ($config->data_method == 'farmerSyncDcsWise') {
                    $executionStatus = $this->handleFarmerSync($config);
                } else if ($config->data_method == 'memberCollectionComfed') {
                    $executionStatus = $this->handleCollectionData($config);
                } else if ($config->data_method == 'localSaleComfed') {
                    $executionStatus = $this->handleLocalSaleData($config);
                }
            }
            return $executionStatus;
        } catch (Throwable $e) {
            Yii::error("Comfed Process Error: " . $e->getMessage());
            return false;
        }
    }

    public function handleCollectionData($config) {
        try {
            $records = Yii::$app->general->getSpData($config->sp_name, [], FALSE);
            if (empty($records)) {
                return false;
            }

            if (!empty($records)) {
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

                        $responseData = $api->ExchangeData();

                        $respStatus = 3;
                        $respMsg = 'Response Not Parsed.';
                        if (isset($responseData->status) && isset($responseData->data) && isset($responseData->data->status) && isset($responseData->data->message) && $responseData->status == 'success') {
                            $data_post_status = 2;
                            $respStatus = $responseData->data->status;
                            $respMsg = $responseData->data->message;
                        } else if (isset($responseData->message) && isset($responseData->statusCode)) {
                            $data_post_status = 3;
                            $respStatus = $responseData->statusCode;
                            $respMsg = $responseData->message;
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

    public function handleFarmerSync($config) {
        try {
            $params = [];
            $records = Yii::$app->general->getSpData($config->sp_name, $params, FALSE);
            if (empty($records)) {
                return false;
            }
            foreach ($records as $record) {
                $farmerCollection = [];
                $sapVendorCode = isset($record['sap_vendor_code']) ? $record['sap_vendor_code'] : null;
                $dcsCode = isset($record['dcs_code']) ? $record['dcs_code'] : null;
                try {
                    $api = new WebApi();
                    $api->serverUrl = $config->request_url;
                    $api->authentication = false;
                    $api->header_info = ["Authorization: Bearer " . $config->authentication_key];
                    $api->body = json_encode(["dcsNo" => (string) $sapVendorCode]);

                    $responseData = $api->ExchangeData();
                    \Yii::info("Comfed-farmer-sync : WebApi Status: " . $responseData->status);
                    if (isset($responseData->status) && $responseData->status == 'success' && !empty($responseData->data)) {
                        foreach ($responseData->data as $farmer) {
                            $frNo = $farmer->frNo;
                            $frNoLastDigits = substr($frNo, -4);
                            $generatedFarmerCode = $dcsCode . $frNoLastDigits;

                            $farmerCollection[] = [
                                'MemberCode' => $generatedFarmerCode,
                                'MemberName' => $farmer->frName,
                                'MobileNo' => $farmer->frPhoneNo,
                                'SapVendorCode' => $farmer->frNo
                            ];
                            \Yii::info("Comfed-farmer-sync : Farmer MemberCode : " . $generatedFarmerCode);
                        }
                        if (!empty($farmerCollection)) {
                            $farmerData = [];
                            $farmerData[] = json_encode($farmerCollection);
                            Yii::$app->general->getSpData('sp_data_exchange_update_member_info', $farmerData, TRUE);
                            \Yii::info("Comfed-farmer-sync : Farmer Member Update Successfully");
                        }
                    }
                } catch (Throwable $e) {
                    \Yii::info("Comfed-farmer-sync : API Error for SAP Code [{$sapVendorCode}]: " . $e->getMessage());
                    continue;
                }
            }
            return true;
        } catch (Throwable $e) {
            \Yii::info("Comfed-farmer-sync : Farmer Sync Fatal Error: " . $e->getMessage());
            return false;
        }
    }

    public function handleLocalSaleData($config) {
        try {
            $records = Yii::$app->general->getSpData($config->sp_name, [], false);
            if (empty($records)) {
                return false;
            }

            $eventId = !empty($records[0]['event_id']) ? $records[0]['event_id'] : '';
            $floats = ['lsd_by_cash', 'lsd_by_credit', 'lsd_grand_total', 'quantity', 'standard_rate'];
            $payload = array_map(function ($record) use ($floats) {
                unset($record['event_id']);
                foreach ($record as $key => $val) {
                    if (in_array($key, $floats)) {
                        $record[$key] = (float) $val;
                    } elseif ($key === 'lsd_is_active') {
                        $record[$key] = (bool) $val;
                    } else {
                        $record[$key] = (string) $val;
                    }
                }
                return $record;
            }, $records);

            $api = new WebApi();
            $api->vendor_code = $eventId;
            $api->serverUrl = $config->request_url;
            $api->authentication = false;
            $api->header_info = ["Authorization: Bearer " . $config->authentication_key];
            $api->body = json_encode(["data" => $payload]);
            $responseData = $api->ExchangeData();

            $respStatus = $data_post_status = 3;
            $respMsg = 'Response Not Parsed.';
            if (isset($responseData->status) && $responseData->status == 'success' && isset($responseData->data->status) && isset($responseData->data->message)) {
                $data_post_status = 2;
                $respStatus = $responseData->data->status;
                $respMsg = $responseData->data->message;
            } else if (isset($responseData->message) && isset($responseData->statusCode)) {
                $data_post_status = 3;
                $respStatus = $responseData->statusCode;
                $respMsg = $responseData->message;
            }

            Yii::$app->general->getSpData('sp_data_exchange_log_update_comfed', [$eventId, $data_post_status, $respStatus, substr($respMsg, 0, 250), 'product_sale_transaction'], true);
            return true;
        } catch (Throwable $e) {
            try {
                Yii::$app->general->getSpData('sp_data_exchange_log_update_comfed', [$eventId, 3, 'Error', substr($e->getMessage(), 0, 250), 'product_sale_transaction'], true);
            } catch (Throwable $e) {
                
            }
            return false;
        }
    }
}
