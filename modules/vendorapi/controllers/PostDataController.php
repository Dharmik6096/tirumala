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
            //$output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $body['milkcollection'][] = ['uuid' => '004fdcee-58c7-4ef9-bc53-6c7992240676',
            'transaction_date' => '19.09.2018',
            'shift' => 'M',
            'mcc_code' => '1016',
            'vlcc_code' => '0010160121',
            'farmer_code' => '0001',
            'milk_type' => 'C',
            'qty' => '15.00',
            'fat' => '5.5',
            'snf' => '6.5',
            'water' => '0.0',
            'rtpl' => '20.00',
            'amount' => '300.00',
            'sampletime' => '19.09.2018 17:29:00',
            'autoflag' => '1'];	
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
                'key' => 'MCCMilkCollection',
                'sp_name' => 'sp_vendor_bmc_coll_data'
            ],
            'member' => [
                'key' => 'ProducerMaster',
                'sp_name' => 'sp_vendor_member_data'
            ]
        ];
        return $data;
    }

}
