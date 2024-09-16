<?php
namespace app\commands;

use app\components\WebApi;
use app\modules\clienterp\components\EiplResponse;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;
use DateTime;
use Yii;

class CargilljobController extends \yii\console\Controller {
    public $ids = '';
    public $update_ids = [];
    public $model = '';
    public $url = '';
    public $base_url = '';
    public $end_point = '';
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
                    $date = date('Y-m-d H:i:s');
                    $updateData = ['status' => 1, 'updated_at' => $date, 'pick_datetime' => $date, 'cron_pick_datetime' => $date];
                    $this->model->updateStatus($updateData, $this->ids);
                    foreach($output as $milkVehical){
                        $httpCode = '';
                        $header = '';
                        $response = '';
                        try {
                            $this->ids = $milkVehical['VINV..Invoice_Number'];
                            $body = $milkVehical;
                            unset($body['union_code'], $body['plant_code'], $body['mcc_plant_code'], $body['bmc_code']);
                            $body['GridIn_1_3'] = json_decode($body['GridIn_1_3']);
                            $requestTimestamp = date('Y-m-d H:i:s');
                            $api = new WebApi();
                            $api->return_actual = true;
                            $api->serverUrl = $this->base_url;
                            $api->apiurl = $this->end_point;
                            $api->body = $body;
                            $authentication = Yii::$app->params['clienterp_authentication']['cargill']['authentication'];
                            $api->header_info['Authorization'] = "Basic " . base64_encode($authentication);
                            $this->data = json_encode($body);
                            $result = $api->GuzzleCURL();
                            $httpCode = $result->getStatusCode();
                            $response = $result->getBody()->getContents();
                            $responseTimestamp = date('Y-m-d H:i:s');
                            if ($httpCode == 200) {
                                $updateData = ['status' => 2, 'updated_at' => $responseTimestamp, 'response_datetime' => $responseTimestamp, 'response_msg' => 'Milk reciept send successfully'];    
                            } else {
                                $updateData = ['status' => 3, 'updated_at' => $responseTimestamp, 'response_datetime' => $responseTimestamp, 'response_msg' => json_encode($response)];
                            }
                            $this->model->updateStatus($updateData, $this->ids);
                            $this->setLogData($milkVehical, $response, $requestTimestamp, $responseTimestamp, $this->data, $httpCode, $header);
                        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
                            $response = $ex->hasResponse() ? $ex->getResponse()->getBody()->getContents() : $ex->getMessage();
                            $httpCode = $ex->hasResponse() ? $ex->getResponse()->getStatusCode() : 408;
                            $responseTimestamp = date('Y-m-d H:i:s');                     
                            $updateData = [
                                'status' => 3, 
                                'updated_at' => $responseTimestamp, 
                                'response_datetime' => $responseTimestamp, 
                                'response_msg' => json_encode($response)
                            ];                          
                            $this->model->updateStatus($updateData, $this->ids);
                            $this->setLogData($milkVehical, $response, $requestTimestamp, $responseTimestamp, $this->data, $httpCode, $header);
                        } catch (\Throwable $ex) {
                            if(!empty($this->ids)){
                                $response = $ex->getMessage();
                                $date = date('Y-m-d H:i:s');
                                $updateData = ['status' => 3, 'updated_at' => $date, 'response_datetime' => $date, 'response_msg' => json_encode($response)];
                                $this->model->updateStatus($updateData, $this->ids);
                                $httpCode = 500;
                                $responseTimestamp = date('Y-m-d H:i:s');
                                $this->setLogData($milkVehical, $response, $requestTimestamp, $responseTimestamp, $this->data, $httpCode, $header);
                            }
                        }
                    }
                }
            } catch (\Throwable $ex) {
                if(!empty($this->ids)){
                    $response = $ex->getMessage();
                    $date = date('Y-m-d H:i:s');
                    $updateData = ['status' => 3, 'updated_at' => $date, 'response_datetime' => $date, 'response_msg' => json_encode($response)];
                    $this->model->updateStatus($updateData, $this->update_ids);
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
            'date1' => !empty($request['Order_Date']) ? DateTime::createFromFormat('d/m/Y', $request['Order_Date'])->format('Y-m-d') : '',
            'desc1' => !empty($request['Long_Address_Number_ALKY']) ? $request['Long_Address_Number_ALKY'] : '',
            'desc2' => !empty($request['VINV..Invoice_Number']) ? $request['VINV..Invoice_Number'] : '',
            'status_code' => $httpCode,
            'status_message' => !empty($response->jde__simpleMessage) ? json_encode($response->jde__simpleMessage) : '',
            'status_response' => !empty($response->jde__status) ? $response->jde__status : 'ERROR',
            'request_header' => !empty($header) ? json_encode($header) : '',
            'request_url' => $this->url,
            'end_point' => $this->end_point,
        ];        
        $this->response->logData = $logData;
        $this->response->saveRequestResponseLog($requestJson, $response, $requestTimestamp, $responseTimestamp);
    }
}
?>
