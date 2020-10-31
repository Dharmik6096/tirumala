<?php

namespace app\modules\bkgprocess\controllers;

use yii;
use app\controllers\ChildController;
use app\modules\bkgprocess\Bkgprocess;
use yii\helpers\Url;
use PHPExcel;
use app\modules\bkgprocess\models\TblDataExchangeConfig;
use app\components\WebApi;

class DataExchangeController extends ChildController {

    public $freeAccessActions = ['data-exchange'];
    public $errorPath = '';

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
                $model->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], [$modelKey => $update_ids]);

                $postData = !empty($output) ? true : false;
                if (!empty($json_array_key)) {
                    $body[$json_array_key] = $output;
                } else {
                    $body = !empty($output[0]) ? $output[0] : [];
                }
                if (!empty(Yii::$app->params['data_exchange_vendor_code'])) {
                    $body['code'] = Yii::$app->params['data_exchange_vendor_code'];
                }
                $postData = [];
                $postData['params'] = $body;
                $postData = json_encode($postData);
                $api = new WebApi();
                $api->serverUrl = $value['request_url'];
                $api->authentication = FALSE;
                $api->vendor_code = 'EIPLMDPL';
                $api->body = $postData;

                $response = $api->ExchangeData();
                $responseData = json_decode(json_encode($response), true);
                $loopData = [];
                if (empty($json_array_key) && !empty($responseData['result'])) {
                    $loopData = $responseData['result'];
                } else if (!empty($responseData['result']['data'])) {
                    $loopData = !empty($responseData['result']['data']);
                }
                if (!empty($loopData)) {
                    foreach ($loopData as $resp_data) {
                        $status = (isset($resp_data['status']) && (strtolower($resp_data['status']) == '200')) ? 2 : 3;
                        $model = new $model_name();
                        $updateValue = !empty($resp_data[$updateKey]) ? $resp_data[$updateKey] : '';
                        $model = $model->find()->where([$modelKey => $updateValue])->one();

                        if (!empty($model)) {
                            $model->scenario = 'data_exchange';
                            $model->data_post_status = $status;
                            $model->resp_status = !empty($resp_data['status']) ? $resp_data['status'] : NULL;
                            $model->resp_desc = !empty($resp_data['msg']) ? $resp_data['msg'] : NULL;
                            $model->response_datetime = date('Y-m-d H:i:s');
                            $model->save();
                        }
                    }
                }
            }
        }
    }

}
