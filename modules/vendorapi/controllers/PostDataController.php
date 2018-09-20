<?php

namespace app\modules\vendorapi\controllers;

use Yii;
use app\components\WebApi;

/**
 * Default controller for the `vendorapi` module
 */
class PostDataController extends \yii\web\Controller {

    public function actionIndex() {
        $body['data'][] = [ 'uuid' => '004fdcee-58c7-4ef9-bc53-6c7992240676',
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

        $body['data'][] = [ 'uuid' => '004fdcee-58c7-4ef9-bc53-6c7902240676',
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
        $api->serverUrl = 'http://rspodevapdb.hec.rsplgroup.com:50000/XISOAPAdapter/MessageServlet?channel=';
        $api->apiurl = ':BC_Everest:CC_Everest_MilkCollectionData_Rest_Sender';
        $api->authentication = FALSE;
        $api->body = $body;
        if ($api->POSTDATA()->msg == 'Success!') {
            
        }
        die;










        $milk_coll_model = new TblMilkCollection();
        $milk_coll_data = $milk_coll_model->getMilkCollData();
        $milk_coll_codes = array_column($milk_coll_data, 'milk_collection_code');
        $update = $milk_coll_model->updateMilkColl($milk_coll_codes);
        foreach ($milk_coll_data as $milk_coll) {
            $milk_coll->scenario = 'portal_data_post';
            try {
                $body = [];
                $body['metadata'] = $metadata;
                $body['collectionEntryList'][] = $collectionEntryList;
                $api = new WebApi();
                $api->apiurl = 'tmccs/farmercollections';
                $api->body = $body;
                if ($api->POSTDATA()->msg == 'Success!') {
                    $milk_coll->data_post_status = 2;
                } else {
                    $milk_coll->data_post_status = 3;
                }
                $milk_coll->save(FALSE);
            } catch (\Exception $e) {
                $milk_coll->data_post_status = 3;
                $milk_coll->save(FALSE);
            }
        }
    }

}
