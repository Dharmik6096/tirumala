<?php

namespace app\modules\vendorapi\controllers;

use Yii;
use app\components\WebApi;

/**
 * Default controller for the `vendorapi` module
 */
class PostDataController extends \yii\web\Controller {

    public function actionIndex() {
        $data = $this->setDataKey();
        foreach ($data as $key => $value) {
            $key = $value['key'];
            $sp_name = $value['sp_name'];
            $sp_param = [];
            $sp_param[] = '004';
            $sp_param[] = '2018-05-24';
            $sp_param[] = '2018-05-26';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $body['data'] = $output;
            $api = new WebApi();
            $api->serverUrl = Yii::$app->params['namaste_collection_url'] . $key;
            $api->authentication = FALSE;
            $api->vendor_code = 'NAMASTEINDIA';
            $api->body = $body;
            $header_array = Yii::$app->params['namaste_api_header'];
            foreach ($header_array as $key => $value) {
                $api->header_info[] = $key . ': ' . $value;
            }
            $response = $api->POSTDATA();
            var_dump($response);
        }
    }

    public function setDataKey() {
        $data = [
            'milkCollection' => [
                'key' => 'MilkCollectionData',
                'sp_name' => 'sp_vendor_milk_coll_data'
            ],
            'bmcCollection' => [
                'key' => 'MilkCollectionData',
                'sp_name' => 'sp_vendor_bmc_coll_data'
            ],
            'member' => [
                'key' => 'MilkCollectionData',
                'sp_name' => 'sp_vendor_member_data'
            ]
        ];
        return $data;
    }

}
