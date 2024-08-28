<?php

namespace app\modules\clienterp\components;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\clienterp\models\TblClientErpApiLog;

class EiplResponse {

    public $statusCode;
    public $message = [];
    public $data = [];
    public $logData = [];

    /**
     *
     * @return mixed
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     *
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     *
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     *
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
    }

    /**
     *
     * @param mixed $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     *
     * @param mixed $data
     */
    public function setData($data, $apply_camel_case = TRUE) {
        $this->data = ($apply_camel_case) ? $this->underscoreToCamelCase($data) : $data;
    }

    public function underscoreToCamelCase(&$res_data) {
        if (is_array($res_data)) {
            $res_data = array_combine(array_map(function ($str) {
                        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
                    }, array_keys($res_data)), array_values($res_data));
            foreach ($res_data as $key => $val) {
                if (is_array($res_data[$key])) {
                    $arr1 = array_combine(array_map(function ($str) {
                                return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
                            }, array_keys($res_data[$key])), array_values($res_data[$key]));
                    $res_data[$key] = $arr1;
                    $this->underscoreToCamelCase($res_data[$key]);
                }
            }
            return $res_data;
        }
        return $res_data;
    }

    public function saveRequestResponseLog($request, $response, $requestTimestamp, $responseTimestamp, $requestJson = ''){
        $logModel = new TblClientErpApiLog();
        if(!empty($requestJson)){
            $request_payload = $requestJson;
            $logModel->setAttributes($this->logData->attributes);
        } else {
            $request_payload = json_encode($request);
            $logModel->setAttributes($this->logData);
        }
        $logModel->request_payload = $request_payload;
        $logModel->response_payload = json_encode($response);
        $logModel->request_timestamp = $requestTimestamp;
        $logModel->response_timestamp = $responseTimestamp;
        // $logModel->status_response = ($logModel->status_code >= 200 && $logModel->status_code < 300) ? 'success' : 'error';
        $logModel->status_message = $logModel->status_message;
        $logModel->save();
        unset($response->logData);
    }

}
