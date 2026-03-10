<?php

namespace app\modules\androiddpu\components;

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
            $this->response['data'] = $response['data'];
            $this->response['error']['message'] = !empty($response['error']['message']) ? $response['error']['message'] : ($response['message'] ?? []);
        } else {
            $parsed_url = parse_url($_SERVER['REQUEST_URI']);
            $endpoint = basename($parsed_url['path']);
            $resp = $this->getResponseType($endpoint);
            $this->response['error']['message'] = !empty($response['error']['message']) ? $response['error']['message'] : ['Data Not Available'];
            $this->response['data'] = $resp['response_type'];
        }
        $this->response['status'] = !empty($response['status']) ? $response['status'] : 'success';
        $this->response['error']['code'] = !empty($response['error']['code']) ? $response['error']['code'] : Yii::$app->response->statusCode;
        return $this->response;
    }

    public function getResponseType($endpoint){
        $default['response_type'] = new \StdClass();
        $label = [
            'product-stock' => [
                'response_type' => [],
            ],
        ];
        return isset($label[$endpoint]) ? $label[$endpoint] : $default;
    }

}
