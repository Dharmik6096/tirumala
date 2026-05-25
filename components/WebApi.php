<?php

namespace app\components;

use yii;
use GuzzleHttp;
use GuzzleHttp\RequestOptions;
use app\models\TblPortalDataPostLog;

class WebApi {

    public $serverUrl = 'http://52.32.190.89/tpi/eipl/';
    public $timeout = 120; // default timeout in seconds
    public $authentication = [
        'user' => ['userName' => 'eipl', 'password' => 'eipl123']
    ];
    public $apiurl = '';
    public $body = [];
    public $vendor_code = 'EIPL';
    public $header_info = [];
    public $return_actual = FALSE;
    public $is_header_merge = TRUE;

    public function POSTDATA() {
        if ($this->authentication) {
            $this->body = array_merge($this->authentication, $this->body);
        }
        return $this->PHPCURL();
        //  return $this->GuzzleCURL();
    }

    public function GuzzleCURL($method = 'POST') {
        $url = $this->serverUrl . $this->apiurl;
        $client = new GuzzleHttp\Client();
        $data = json_encode($this->body);
        $main_header = array("Content-Type: application/json", "Content-length: " . strlen($data));
        $header = $this->header_info;
        if ($this->is_header_merge) {
            $header = array_merge($main_header, $this->header_info);
        }
        //var_dump($header);die;
        $postData = [
            RequestOptions::HEADERS => $header
        ];
        if ($method == 'POST') {
            $postData = [
                RequestOptions::JSON => $this->body,
                RequestOptions::HEADERS => $header,
                RequestOptions::TIMEOUT => $this->timeout
            ];
        }
        $resp = null;
        $log_model = new TblPortalDataPostLog();
        $log_model->created_at = date('Y-m-d H:i:s');
        $log_model->vendor_code = $this->vendor_code;
        $log_model->url = $url;
        $log_model->request = $data;
        try {
            $resp = $client->request($method, $url, $postData);
            $log_model->response = json_encode($resp->getBody()->getContents());
            $resp->getBody()->rewind();
            $log_model->save();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseData = '';
            if (!empty($e->getResponse())) {
                $responseData = json_decode($e->getResponse()->getBody()->getContents());
                $e->getResponse()->getBody()->rewind();
            }
            $log_model->response = !empty($responseData->message) ? $responseData->message : '';
            $log_model->save();
            throw $e;
        } catch (\Throwable $ex) {
            $log_model->response = !empty($ex->getMessage()) ? $ex->getMessage() : '';
            $log_model->save();
        }

        if ($this->return_actual) {
            return $resp;
        }
        return $resp->getBody();
    }

//    public function GuzzleCURL() {
//        $url = $this->serverUrl . $this->apiurl;
//        $client = new GuzzleHttp\Client();
//        $postData = [
//            RequestOptions::JSON => $this->body
//        ];
//        $resp = $client->request('POST', $url, $postData);
//        return $resp->getBody();
//    }

    public function PHPCURL() {
        $log_model = new TblPortalDataPostLog();
        $log_model->created_at = date('Y-m-d H:i:s');
        $url = $this->serverUrl . $this->apiurl;
        $data = $this->body;
        $main_header = array("Content-Type: application/json", "Content-length: " . strlen($data));
        $header = array_merge($main_header, $this->header_info);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($this->return_actual) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }  // Skip SSL Verification
        curl_setopt($ch, CURLOPT_CAINFO, 'C:\Everest\Apache2454\conf\sapcerts\cacert.pem');
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $log_model->status = (isset($res->msg) && $res->msg == 'Success!') ? 1 : 0;
        $log_model->vendor_code = $this->vendor_code;
        $log_model->url = $url;
        $log_model->request = $data;
        $log_model->response = $result;
        $log_model->updated_at = date('Y-m-d H:i:s');
        $log_model->save();
        if ($this->return_actual) {
            return $result;
        }
        return $res;
    }

    public function ExchangeData() {
        if ($this->authentication) {
            $this->body = array_merge($this->authentication, $this->body);
        }
        return $this->ExchangeDataCurl();
        //  return $this->GuzzleCURL();
    }

    public function ExchangeDataCurl() {
        try {
            $log_model = new TblPortalDataPostLog();
            $log_model->created_at = date('Y-m-d H:i:s');
            $log_model->vendor_code = $this->vendor_code;
            $url = $this->serverUrl . $this->apiurl;
            $data = $this->body;
            $log_model->request = $data;
            $log_model->url = $url;
            $main_header = array("Content-Type: application/json", "Content-length: " . strlen($data));
            $header = array_merge($main_header, $this->header_info);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if ($this->return_actual) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            }  // Skip SSL Verification
            //  curl_setopt($ch, CURLOPT_CAINFO, 'C:\Users\nifadmin\Downloads\cacert.pem');
            $result = curl_exec($ch);
            if ($result === false) {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            }
            curl_close($ch);
//        var_dump($result);
//        die;
            $res = json_decode($result);
            $log_model->status = (isset($res->msg) && $res->msg == 'Success!') ? 1 : 0;
            $log_model->response = $result;
            $log_model->updated_at = date('Y-m-d H:i:s');
            $log_model->save();
            if ($this->return_actual) {
                return $result;
            }
            return $res;
        } catch (\Throwable $ex) {
            $log_model->response = substr($ex->getMessage(), 0, 250);
            $log_model->updated_at = date('Y-m-d H:i:s');
            $log_model->save();
        }
    }

    public function SapDataIntegration() {
        if ($this->authentication) {
            $this->body = array_merge($this->authentication, $this->body);
        }
        return $this->SapDataIntegrationCurl();
        //  return $this->GuzzleCURL();
    }

    public function SapDataIntegrationCurl() {
        $url = $this->serverUrl;
        $data = $this->body;
        $main_header = array("Content-Type: multipart/form-data");
        $header = array_merge($main_header, $this->header_info);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_decode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($this->return_actual) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }  // Skip SSL Verification
        //  curl_setopt($ch, CURLOPT_CAINFO, 'C:\Users\nifadmin\Downloads\cacert.pem');
        $result = curl_exec($ch);
        if ($result === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
//        var_dump($result);
//        die;
        $res = json_decode($result);
//        $log_model = new TblPortalDataPostLog();
//        $log_model->status = (isset($res->msg) && $res->msg == 'Success!') ? 1 : 0;
//        $log_model->vendor_code = $this->vendor_code;
//        $log_model->url = $url;
//        $log_model->request = $data;
//        $log_model->response = $result;
//        $log_model->save();
//        if ($this->return_actual) {
//            return $result;
//        }
        return $res;
    }

}
