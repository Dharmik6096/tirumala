<?php
namespace app\commands;

use app\components\WebApi;
use app\modules\clienterp\components\EiplResponse;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;
use Yii;

class CargilljobController extends \yii\console\Controller {
    public $ids = '';
    public $update_ids = [];
    public $model = '';
    public $url = '';
    public $base_url = '';
    public $end_point = '';
    public $challan_no = '';
    public $data = '';
    public function actionMilkReceiptSend(){
        $this->url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_milk_receipt_url'];
        $this->base_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'];
        $this->end_point = str_replace($this->base_url, '', $this->url);
        $this->model = new TblMilkVehicleEntryTransaction();
        $i = 0;
        while ($i < 1) {
            sleep(2);
            try {
                $output = \Yii::$app->general->getSpData('sp_approved_milk_vehicle_entries', []);
                if(!empty($output)){
                    $this->ids = array_column($output,'VINV..Invoice_Number');
                    $this->update_ids = $this->ids;
                    $this->challan_no = array_column($output,'challan_no');
                    $date = date('Y-m-d H:i:s');
                    $updateData = ['status' => 1, 'updated_at' => $date, 'pick_datetime' => $date, 'cron_pick_datetime' => $date];
                    $this->model->updateStatus($updateData, $this->ids, $this->challan_no);
                    foreach($output as $milkVehical){
                        $httpCode = '';
                        $header = '';
                        $response = '';
                        $responseTimestamp = '';
                        try {
                            $this->ids = $milkVehical['VINV..Invoice_Number'];
                            $this->challan_no = $milkVehical['challan_no'];
                            $body = $milkVehical;
                            unset($body['union_code'], $body['plant_code'], $body['mcc_plant_code'], $body['bmc_code'], $body['challan_no'], $body['milk_vehicle_entry_code']);
                            $body['GridIn_1_3'] = json_decode($body['GridIn_1_3']);
                            $requestTimestamp = date('Y-m-d H:i:s');
                            $api = new WebApi();
                            $api->serverUrl = $this->base_url;
                            $api->apiurl = $this->end_point;
                            $api->body = $body;
                            $api->authentication = Yii::$app->params['clienterp_authentication']['cargill']['authentication'];
                            $this->data = json_encode($body);
                            $response = $api->GuzzlePostData();
                            $responseTimestamp = date('Y-m-d H:i:s');
                            if ($response == false) {
                                $updateData = ['status' => 3, 'updated_at' => $responseTimestamp, 'response_datetime' => $responseTimestamp, 'response_msg' => $response];
                            } else {
                                $updateData = ['status' => 2, 'updated_at' => $responseTimestamp, 'response_datetime' => $responseTimestamp, 'response_msg' => 'Milk reciept send successfully'];
                            }
                            $this->model->updateStatus($updateData, $this->ids, $this->challan_no);
                            $this->setLogData($milkVehical, $response, $requestTimestamp, $responseTimestamp, $this->data, $httpCode, $header);
                        }  catch (\Throwable $ex) {
                            if(!empty($this->ids)){
                                $msg = substr($ex->getMessage(), 0, 254);
                                $date = date('Y-m-d H:i:s');
                                $updateData = ['status' => 3, 'updated_at' => $date, 'response_datetime' => $date, 'response_msg' => $msg];
                                $this->model->updateStatus($updateData, $this->ids, $this->challan_no);
                                $response = $ex->getMessage();
                                $this->setLogData($milkVehical, $response, $requestTimestamp, $responseTimestamp, $this->data, $httpCode, $header);
                            }
                        }
                    }
                }
            } catch (\Throwable $ex) {
                if(!empty($this->ids)){
                    $msg = substr($ex->getMessage(), 0, 254);
                    $date = date('Y-m-d H:i:s');
                    $updateData = ['status' => 3, 'updated_at' => $date, 'response_datetime' => $date, 'response_msg' => $msg];
                    $this->model->updateStatus($updateData, $this->update_ids, $this->challan_no);
                }
            }
        }
    }

    public function setLogData($request, $response, $requestTimestamp, $responseTimestamp, $requestJson, $httpCode, $header) {
        $this->response = new EiplResponse();
        $logData = [
            'union_code' => !empty($request['union_code']) ? $request['union_code'] : '',
            'plant_code' => !empty($request['plant_code']) ? $request['plant_code'] : '',
            'mcc_plant_code' => !empty($request['mcc_plant_code']) ? $request['mcc_plant_code'] : '',
            'bmc_code' => !empty($request['bmc_code']) ? $request['bmc_code'] : '',
            'request_desc' => 'milk receipt',
            'txn_type' => 'eipl',
            'date1' => !empty($request['Order_Date']) ? $request['Order_Date'] : '',
            'desc1' => !empty($request['Long_Address_Number_ALKY']) ? $request['Long_Address_Number_ALKY'] : '',
            'desc2' => !empty($request['VINV..Invoice_Number']) ? $request['VINV..Invoice_Number'] : '',
            'status_code' => $httpCode,
            'status_message' => !empty($response->jde__simpleMessage) ? json_encode($response->jde__simpleMessage) : '',
            'status_response' => !empty($response->jde__status) ? $response->jde__status : '',
            'request_header' => !empty($header) ? json_encode($header) : '',
            'request_url' => $this->url,
            'end_point' => $this->end_point,
        ];        
        $this->response->logData = $logData;
        $this->response->saveRequestResponseLog($requestJson, $response, $requestTimestamp, $responseTimestamp);
    }
}
?>
