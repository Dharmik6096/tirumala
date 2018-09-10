<?php

namespace app\modules\vendorapi\components;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class HttpResponse extends \yii\base\Component {

    public $response = [];
    public $apply_camel_case = TRUE;

    public function BindResponse($response) {
        $resp = [];
        if (!empty($response['success_codes'])) {
            foreach ($response['success_codes'] as $success_codes) {
                $err_resp = [];
                $err_resp['code'] = '200';
                $err_resp['master_key'] = $success_codes;
                $err_resp['message'] = 'Successfully Saved!';
                $resp[] = $err_resp;
            }
        }
        if (!empty($response['error_codes'])) {
            foreach ($response['error_codes'] as $error_codes) {
                $err_resp = [];
                $err_resp['code'] = '501';
                $err_resp['master_key'] = $error_codes;
                $err_resp['message'] = 'Unable to save!';
                $resp[] = $err_resp;
            }
        }
        $this->response['response'] = $resp;
        return $this->response;
    }

}
