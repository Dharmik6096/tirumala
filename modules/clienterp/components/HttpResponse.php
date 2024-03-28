<?php

namespace app\modules\webservice\components;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class HttpResponse extends \yii\base\Component {

    public $response = [
        'status' => '',
        'error' => ['code' => '', 'message' => []],
        'data' => [],
    ];
    public $apply_camel_case = TRUE;

    public function BindResponse($response) {
        if (!empty($response['data'])) {
            $this->response['data'] = ($this->apply_camel_case) ? $this->underscoreToCamelCase($response['data']) : $response['data'];
            $this->response['error']['message'] = $response['message'];
        } else {
            $this->response['error']['message'] = ['Data Not Available'];
            $this->response['data'] = new \StdClass();
        }
        $this->response['status'] = 'success';
        $this->response['error']['code'] = Yii::$app->response->statusCode;
        return $this->response;
    }

    public function &underscoreToCamelCase(&$res_data) {
        if (is_array($res_data)) {
            $res_data = array_combine(array_map(function($str) {
                        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
                    }, array_keys($res_data)), array_values($res_data));
            foreach ($res_data as $key => $val) {
                if (is_array($res_data[$key])) {
                    $arr1 = array_combine(array_map(function($str) {
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
