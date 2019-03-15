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
            $json_array_key = $value['json_array_key'];
            $sp_param = [];
            $end_date = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime("-5 days", strtotime($end_date)));
            $sp_param[] = '001';
            $sp_param[] = $start_date;
            $sp_param[] = $end_date;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
			if (!empty($output)) {
                $modelName = $value['model_name'];
                $model_name = Yii::$app->path->define($modelName);
                $model = new $model_name();
                $updateKey = $value['update_key'];
                $updateKeys = explode(':', $updateKey);
                $sapKey = $updateKeys[0];
                $modelKey = isset($updateKeys[1]) ? $updateKeys[1] : $updateKeys[0];
                $update_ids = array_column($output, $sapKey);
                $model->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], [$modelKey => $update_ids]);
                $body[$json_array_key] = $output;
                $body = json_encode($body);
				//var_dump($body);die;
//            $body['milkcollection'][] = ['uuid' => '004fdcee-58c7-4ef9-bc53-6c7992240676',
//            'transaction_date' => '19.09.2018',
//            'shift' => 'M',
//            'mcc_code' => '1016',
//            'vlcc_code' => '0010160121',
//            'farmer_code' => '0001',
//            'milk_type' => 'C',
//            'qty' => '15.00',
//            'fat' => '5.5',
//            'snf' => '6.5',
//            'water' => '0.0',
//            'rtpl' => '20.00',
//            'amount' => '300.00',
//            'sampletime' => '19.09.2018 17:29:00',
//            'autoflag' => '1'];	
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
//                $response = '{"MCC_Milk_Collection_Response":{"MCC_Milk_Collection":[{"uuid":"239d4c6c-d0e7-4ffb-aed5-0484006cdd36","status":"SUCCESS","desc":"GR Successful"},{"uuid":"166470cb-9211-41bb-832a-d10cb8dcd391","status":"ERROR","desc":"GR Successful"}]}}';
                $responseData = json_decode($response);
                foreach ($responseData as $resp_data) {
                    foreach ($resp_data as $resp) {
                        foreach ($resp as $key => $value) {
                            $status = (isset($value->status) && strtolower($value->status) == 'success') ? 2 : 3;
                            $model = new $model_name();
                            $model = $model->find()->where([$modelKey => $value->$sapKey])->one();
                            if (!empty($model)) {
                                $model->scenario = 'post_sap_data';
                                $model->data_post_status = $status;
                                $model->resp_status = !empty($value->status) ? $value->status : NULL;
                                $model->resp_desc = !empty($value->desc) ? $value->desc : NULL;
                                $model->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function setDataKey() {
        $data = [
            /*'milkCollection' => [
              'key' => 'MilkCollectionData',
              'sp_name' => 'sp_vendor_milk_coll_data',
              'json_array_key' => 'milkcollection',
              'model_name' => 'TblMilkCollection',
              'update_key' => 'uuid:data_post_id'
              ],*/
            'bmcCollection' => [
                'key' => 'MCCMilkCollection',
                'sp_name' => 'sp_vendor_bmc_coll_data',
                'json_array_key' => 'mccmilkcollection',
                'model_name' => 'TblBmcCollection',
                'update_key' => 'uuid:data_post_id'
            ]
                /* 'member' => [
                  'key' => 'ProducerMaster',
                  'sp_name' => 'sp_vendor_member_data',
                  'json_array_key' => 'producermaster',
                  'model_name' => 'TblMember',
                  'update_key' => 'uuid:data_post_id'
                  ] */
        ];
        return $data;
    }

}
