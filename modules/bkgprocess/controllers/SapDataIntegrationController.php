<?php

namespace app\modules\bkgprocess\controllers;

use yii;
use app\controllers\ChildController;
use yii\helpers\Url;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\components\WebApi;
use app\modules\collection\models\TblMccShiftLockStaging;

class SapDataIntegrationController extends ChildController {

    public $freeAccessActions = ['send-sap-data'];
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

    public function actionSendSapData() {
        $api = new WebApi();
        $api->serverUrl = 'https://login.microsoftonline.com/2c11ed1f-0dff-46b9-94e9-8cbe83717417/oauth2/token';
        $api->authentication = FALSE;
//        $bodyData = [
//            'grant_type' => 'client_credentials',
//            'client_id' => '20e569c1-4277-462b-b32c-01fc8516b4a8',
//            'client_secret' => '4wD7Q~o~2sb6uJY77edU7GBNPHDHFs0KFCxz3',
//            'resource' => 'https://mmd-test.sandbox.operations.dynamics.com'
//        ];
        $bodyData = [
            'grant_type' => 'client_credentials',
            'client_id' => '1ebf2967-31cd-48aa-a95d-47872d9c1660',
            'client_secret' => 'wSz7Q~.xR387Nc4bpNLaIQeJucLqv7.4a0Z5b',
            'resource' => 'https://mmd-prd.operations.dynamics.com'
        ];
        $api->body = json_encode($bodyData);
        $api->header_info = ['Cookie: buid=0.ASoAH-0RLP8NuUaU6Yy-g3F0FxUAAAAAAAAAwAAAAAAAAAAqAAA.AQABAAEAAAD--DLA3VO7QrddgJg7WevrvVFNQrKzy_CROckT6gVKweNqD3cIE_2e6sZvqDLziOl8pO63n7RLdlIlGAuwxD62Enrb1pzwLrCCeemK4klCumlwbqCg2J9DH0skWUTDYnkgAA; esctx=AQABAAAAAAD--DLA3VO7QrddgJg7Wevr6ffEbthd6xIgF9p_ALeJBUHpIFF8fjoU4RbhU6__vXSrMIrFkvh22Pkix_9Le-2mYya7B8dKnuUP_rbwfpzClwoS9Ky7NfwLUT_ZHQKSykQ94qj7dGTJP5ADjUi---2djJon1PEOjTv7Y6o3MstQTGOfn3_UANRXj-6c84mvRDIgAA; x-ms-gateway-slice=estsfd; stsservicecookie=estsfd; fpc=AmO0_u8SW0RBlh7R1d1Hu_TyqFelAQAAACCE-NgOAAAAMek5pAEAAACJhPjYDgAAAA'];

        $response = $api->SapDataIntegration();
        $responseData = json_decode(json_encode($response), true);

        if (!empty($responseData['token_type']) && !empty($responseData['resource']) && !empty($responseData['access_token'])) {
            $shiftLock = new TblMccShiftLockStaging();
            $shiftLockData = $shiftLock->getLockShift(10);

            foreach ($shiftLockData as $key => $value) {
                $body = [];
                $loopData = [];
                $loopDetailData = [];
                $value->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], ['staging_code' => $value->staging_code]);

                $loopData['orderNumber'] = $value->staging_code;
                $loopData['vendorAccount'] = 'VADD00099';
                $loopData['orderDate'] = date('m-d-Y', strtotime($value->date_time_of_collection));
                $loopData['companyId'] = '';
                $loopData['sourceSystem'] = '';

                $loopDetailData['itemNumber'] = 'RM000001';
                $loopDetailData['quantity'] = $value->qty;
                $loopDetailData['lineAmount'] = $value->amount;
                $loopDetailData['locationId'] = '';
                $loopDetailData['FAT'] = $value->avg_fat;
                $loopDetailData['SNF'] = $value->avg_snf;

                $body['purchaseOrderHeaderRequest'] = $loopData;
                $body['purchaseOrderLineRequestList']['list'][] = $loopDetailData;
                $postData = [];
                $postData = json_encode($body);

                $tokenType = $responseData['token_type'];
                $token = $responseData['access_token'];
                $resource = $responseData['resource'];
                $request_url = $resource . '/api/services/TECServiceGroup/TECServices/savePurchaseOrder';

                $api->serverUrl = $request_url;
                $api->header_info = ['Authorization: ' . $tokenType . ' ' . $token];
                $api->authentication = FALSE;
                $api->body = $postData;

                $response = $api->ExchangeData();
                $responseData = json_decode(json_encode($response), false);

                if (!empty($responseData)) {
                    $status = $responseData->status;
                    $resp_desc = $responseData->statusDescription;
                    $value->updateAll(['data_post_status' => 2, 'resp_status' => $status, 'resp_desc' => $resp_desc, 'response_datetime' => date('Y-m-d H:i:s'), 'x_col1' => $responseData->guidD365, 'x_col2' => $responseData->fnoOrderNumber], ['staging_code' => $value->staging_code]);
                }
            }
        }
    }

}
