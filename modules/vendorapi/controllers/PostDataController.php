<?php

namespace app\modules\vendorapi\controllers;

use Yii;
use app\components\WebApi;

/**
 * Default controller for the `vendorapi` module
 */
class PostDataController extends \yii\web\Controller {

    public function actionIndex() {
        $body['data'][] = ['uuid' => '004fdcee-58c7-4ef9-bc53-6c7992240676',
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

        $body['data'][] = ['uuid' => '004fdcee-58c7-4ef9-bc53-6c7902240676',
            'transaction_date' => '19.09.2018',
            'shift' => 'M',
            'mcc_code' => '1016',
            'vlcc_code' => '0010160121',
            'farmer_code' => '0002',
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
        $api->serverUrl = Yii::$app->params['namaste_collection_url'];
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
