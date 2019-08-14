<?php

namespace app\modules\webservice\components;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class EiplResponse {

    public $statusCode;
    public $message = [];
    public $data = [];

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
    public function setData($data) {
        $this->data = $this->underscoreToCamelCase($data);
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

}