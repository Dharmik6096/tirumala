<?php

namespace app\modules\vendorapi\components;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class HttpResponse extends \yii\base\Component {

    public $response = [];
    public $apply_camel_case = TRUE;
    public $response_master_key = 'master_key';
    public $response_main_array_key = 'Master_Response';
    public $response_inner_array_key = 'Response';

    public function BindResponse($response) {
        $resp = [];
        if (!empty($response['success_codes'])) {
            foreach ($response['success_codes'] as $success_codes) {
                $err_resp = [];
                $err_resp['status'] = "200";
                $err_resp[$this->response_master_key] = $success_codes;
                $err_resp['desc'] = 'Successfully Saved!';
                $resp[] = $err_resp;
            }
        }
        if (!empty($response['error_codes'])) {
            foreach ($response['error_codes'] as $error_codes) {
                $err_resp = [];
                $err_resp['status'] = "501";
                $err_resp[$this->response_master_key] = $error_codes;
                $err_resp['desc'] = 'Unable to save!';
                $resp[] = $err_resp;
            }
        }
        $this->response[$this->response_main_array_key][$this->response_inner_array_key] = $resp;
        return $this->response;
    }

}
