<?php

namespace app\modules\soap\controllers;

use Yii;
use SoapClient;
use stdClass;
use SimpleXMLElement;

class TransactionDataController extends \app\modules\soap\controllers\DefaultController {

    public function actionIndex() {
        $data = $this->setDataKey();
        foreach ($data as $api_key => $api_config) {
            $this->postData([$api_key => $api_config]);
        }
    }

    public function setDataKey($param = NULL) {
        $data = [
            'MilkCollectionData' => [
                'sp_name' => 'sp_vendor_prabhat_milk_coll_data',
                'model_name' => 'TblMilkCollection',
                'update_key' => 'milk_collection_code',
                'result_key' => 'MilkCollectionDataResult',
            ],
            'CFSalesData' => [
                'sp_name' => 'sp_vendor_prabhat_product_sale_data',
                'model_name' => 'TblProductSaleDetails',
                'update_key' => 'sale_detail_code',
                'result_key' => 'CFSalesDataResult',
            ],
            'AdvanceFromPM' => [
                'sp_name' => 'sp_vendor_prabhat_loan_product_sale_data',
                'model_name' => 'TblLoanProductSaleDetails',
                'update_key' => 'sale_detail_code',
                'result_key' => 'AdvanceFromPMResult',
            ],
        ];
        return isset($data[$param]) ? [$param => $data[$param]] : $data;
    }

    public function postData($data) {
        foreach ($data as $key => $value) {
            foreach ($this->union_array as $union_code) {
                $update_ids = [];
                $body = [];
                $sp_name = $value['sp_name'];
                $sp_param = [];
                $end_date = date('Y-m-d');
                $start_date = date('Y-m-d', strtotime("-1 days", strtotime($end_date)));
                $sp_param[] = $union_code;
                $sp_param[] = $start_date;
                $sp_param[] = $end_date;
                $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
                if (!empty($output)) {
                    try {
                        $modelName = $value['model_name'];
                        $model_name = Yii::$app->path->define($modelName);
                        $model = new $model_name();
                        $updateKey = $value['update_key'];
                        $updateKeys = explode(':', $updateKey);
                        $sapKey = $updateKeys[0];
                        $modelKey = isset($updateKeys[1]) ? $updateKeys[1] : $updateKeys[0];
                        $update_ids = array_column($output, $sapKey);
                        $model->updateAll(['send_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], [$modelKey => $update_ids]);
                        $coll_data = array_column($output, 'line');
                        $coll_line = implode('$', $coll_data);
                        $params = new stdClass();
                        $params->CollData = $coll_line;
                        $client = new SoapClient(Yii::$app->params['soap_api_url'], ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
                        $result = $client->{$key}($params);
                        if ($result->{$value['result_key']} == 'Details Saved successfully') {
                            $send_status = 2;
                        } else {
                            $send_status = 3;
                        }
                        $model->updateAll(['send_status' => $send_status, 'resp_desc' => $result->{$value['result_key']}, 'response_datetime' => date('Y-m-d H:i:s')], [$modelKey => $update_ids]);
                    } catch (\Throwable $ex) {
                        $this->createCpLogFile('', $ex->xdebug_message, $key);
                        $model->updateAll(['send_status' => 0, 'resp_desc' => 'exception', 'response_datetime' => date('Y-m-d H:i:s')], [$modelKey => $update_ids]);
                    }
                }
            }
        }
    }

}
