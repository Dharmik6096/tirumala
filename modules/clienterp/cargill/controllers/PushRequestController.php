<?php

namespace app\modules\clienterp\cargill\controllers;

use app\modules\organisation\models\TblPlant;
use Yii;
use app\modules\product\models\TblPlantDispatch;
use app\modules\product\models\TblPlantDispatchTxn;
use DateTime;

class PushRequestController extends PushMasterController {

    public function actionInventoryPlantDispatch() {
        $request = Yii::$app->request->getRawBody();
        $requestTimestamp = date('Y-m-d H:i:s');
        $plant_data = [];
        $bmc_data = [];
        try {
            $errors = [];
            $save_model = [];
            $is_valid_data = TRUE;
            $plantdisModel = new TblPlantDispatch();
            $plantdisModel->scenario = 'clienterp_cargill';
            $plantdisModel->setAttributes($request);
            $plantdisModel->bmc_code = $request['to_location'];
            $dispatch_date = DateTime::createFromFormat('d/m/Y', $plantdisModel->dispatch_date);
            if ($dispatch_date === false) {
                $plantdisModel->dispatch_date = '-';
            } else {
                $plantdisModel->dispatch_date = $dispatch_date->format('Y-m-d');
            }
            $document_date = DateTime::createFromFormat('d/m/Y', $plantdisModel->document_date);
            if ($document_date === false) {
                $plantdisModel->document_date = '-';
            } else {
                $plantdisModel->document_date = $document_date->format('Y-m-d');
            }
            $bmc_data = $plantdisModel->bmcCode;
            if ($plantdisModel->validate()) {
                $plant_data = $plantdisModel->plantCode;
                if (!empty($plant_data) && $plant_data->ref_code == $request['plant_code']) {
                    $plantdisModel->originating_org_code = $plantdisModel->union_code;
                    $plantdisModel->created_by = 'api';
                    $save_model[] = $plantdisModel;
                    $plantdisModel->plant_dispatch_code = Yii::$app->general->getCodeAutoIncrement($plantdisModel);
                    $disptxModel = new TblPlantDispatchTxn();
                    $disptxModel->plant_dispatch_txn_code = Yii::$app->general->getCodeAutoIncrement($disptxModel);
                    $i = 0;
                    foreach ($request['transactions'] as $requestData) {
                        $txModel = new TblPlantDispatchTxn();
                        $txModel->scenario = 'clienterp_cargill';
                        $txModel->attributes = $plantdisModel->attributes;
                        $txModel->setAttributes($requestData);
                        $txModel->product_code = $requestData['itemCode'];
                        $txModel->sap_batch_no = $requestData['batchNo'];
                        $txModel->plant_dispatch_txn_code = $disptxModel->plant_dispatch_txn_code + $i;
                        if ($txModel->validate()) {
                            $txModel->grn_missing_qty = $txModel->qty;
                            $txModel->amount = ($txModel->qty) * ($txModel->rate);
                            $save_model[] = $txModel;
                        } else {
                            $is_valid_data = FALSE;
                            $errors = $txModel->getErrors();
                            break;
                        }
                        $i++;
                    }
                } else {
                    $is_valid_data = FALSE;
                    $errors['plant_code'][] = 'Plant Is Invalid.';
                }
            } else {
                $is_valid_data = FALSE;
                $errors = $plantdisModel->getErrors();
            }
            if (!empty($errors)) {
                $error_list = [];
                foreach ($errors as $e) {
                    $error_list = array_merge($error_list, $e);
                }
                $this->response->setStatusCode($this->eiplResponseCode->validationFail);
                $this->response->setMessage($error_list);
            } else if ($is_valid_data && !empty($save_model)) {
                $transaction = $this->generalModel->saveTransaction($save_model, ['Plant Dispatch', 'create']);
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                if ($transaction == 'customRedirect') {
                    $this->response->setData(['txnRefCode' => $plantdisModel->plant_dispatch_code], FALSE);
                } else {
                    $this->response->setStatusCode($this->eiplResponseCode->statusError);
                }
                $this->response->setMessage([$msg]);
            }
        } catch (\Throwable $ex) {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Error While Process Request.']);
        }
        if (empty($plant_data) && !empty($plantdisModel->getErrors())) {
            $plant_data = TblPlant::find()->where(['sap_vendor_code' => $request['plant_code']])->one();
        }
        $responseTimestamp = date('Y-m-d H:i:s');
        $this->setLogData($request, $this->response, $requestTimestamp, $responseTimestamp, $plant_data, $bmc_data);
        return $this->response;
    }
    
    public function setLogData($request, $response, $requestTimestamp, $responseTimestamp, $plantDetail, $bmcData) {
        // $plantDetail = !empty($request['plant_code']) ? TblPlant::find()->where(['or',['plant_code' => $request['plant_code']], ['ref_code' => $request['plant_code']], ['sap_vendor_code' => $request['plant_code']]])->one() : [];
        $statusCode = $response->getStatusCode();
        $dispatch_date = '';
        if(!empty($request['dispatch_date'])){
            $dispatch_date = DateTime::createFromFormat('d/m/Y', $request['dispatch_date']);
            $dispatch_date = $dispatch_date->format('Y-m-d');
        }
        $document_date = '';
        if(!empty($request['document_date'])){
            $document_date = DateTime::createFromFormat('d/m/Y', $request['document_date']);
            $document_date = $document_date->format('Y-m-d');
        }
        $logData = [
            'union_code' => !empty($plantDetail['union_code']) ? $plantDetail['union_code'] : '',
            'plant_code' => !empty($plantDetail['plant_code']) ? $plantDetail['plant_code'] : '',
            'mcc_plant_code' => !empty($bmcData['mcc_plant_code']) ? $bmcData['mcc_plant_code'] : '',
            'bmc_code' => !empty($bmcData['bmc_code']) ? $bmcData['bmc_code'] : '',
            'request_desc' => 'inventory plant dispatch',
            'txn_type' => 'cargill',
            'date1' => $document_date,
            'date2' => $dispatch_date,
            'desc1' => !empty($request['document_no']) ? $request['document_no'] : '',
            'desc2' => !empty($request['remarks']) ? $request['remarks'] : '',
            'status_response' => $statusCode == 200 ? 'SUCCESS' : 'ERROR',
            'status_code' => $statusCode,
            'status_message' => is_array($response->message) ? json_encode($response->message) : $response->message,            
            'request_header' => !empty(Yii::$app->request->getHeaders()) ? json_encode(Yii::$app->request->getHeaders()->toArray()) : '',
        ];
        $response->saveRequestResponseLog($request, $response, $requestTimestamp, $responseTimestamp, $logData);
    }


}
