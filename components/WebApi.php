<?php

namespace app\components;

use yii;
use GuzzleHttp;
use GuzzleHttp\RequestOptions;
use app\models\TblPortalDataPostLog;

class WebApi {

    public $serverUrl = 'http://52.32.190.89/tpi/eipl/';
    public $authentication = [
        'user' => ['userName' => 'eipl', 'password' => 'eipl123']
    ];
    public $apiurl = '';
    public $body = [];
    public $vendor_code = 'STELLAPPS';
    public $header_info = [];

    public function POSTDATA() {
        if ($this->authentication) {
            $this->body = array_merge($this->authentication, $this->body);
        }
        return $this->PHPCURL();
    }

    public function GuzzleCURL() {
        $url = $this->serverUrl . $this->apiurl;
        $client = new GuzzleHttp\Client();
        $postData = [
            RequestOptions::JSON => $this->body
        ];
        $resp = $client->request('POST', $url, $postData);
        return $resp->getBody();
    }

    public function PHPCURL() {
        $url = $this->serverUrl . $this->apiurl;
        $data = json_encode($this->body);
        $main_header = array("Content-Type: application/json", "Content-length: " . strlen($data));
        $header = array_merge($main_header, $this->header_info);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $log_model = new TblPortalDataPostLog();
        $log_model->status = (isset($res->msg) && $res->msg == 'Success!') ? 1 : 0;
        $log_model->vendor_code = $this->vendor_code;
        $log_model->url = $url;
        $log_model->request = $data;
        $log_model->response = $result;
        $log_model->save();
        return $res;
    }

}
