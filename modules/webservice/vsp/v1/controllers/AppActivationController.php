<?php

namespace app\modules\webservice\vsp\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\models\TblAppActivation;
use app\modules\installation\models\InstallationIdentity;
use app\modules\webservice\vsp\v1\models\Society;
use app\modules\webservice\vsp\v1\models\Member;
use Yii;

class AppActivationController extends ChildController {

    public function actionRegister() {
        $societyModel = new Society();
        $appModel = new TblAppActivation();
        $societyModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->code = $this->post_data['dcs_code'];
        $encryptedmobile = Yii::$app->general->encryptData($appModel->mobile_no);
        if ($this->post_data['type'] == 1) {
            $appModel = $appModel->activationData($encryptedmobile);
        } else {
            $appModel = [];
        }
        $data = [];
        if (!empty($appModel)) {
            $master = [];
            $appModel->setAttributes($this->post_data);
            $appModel->imei_no = $this->post_data['imei'];
            $appModel->otp_code = rand(1000, 9999);
            $appModel->hash_key = Yii::$app->security->generateRandomString(20);
            $appModel->orignating_timestamp = date('Y-m-d H:i:s');
            $message = 'Dear Your OTP Pin is ' . $appModel->otp_code . '.Enter this pin to login your account.';

            $mobile = '91' . $appModel->mobile_no;
            $send = Yii::$app->bsmartsms->sendSmsPOST($mobile, $message);
            $sent = json_decode($send);
            $res = $sent->results;
            foreach ($res as $result) {
                $status = $result->status;
            }
            $appModel->sms_sent = 1;
            $appModel->sms_log = $status;
            $appModel->is_delete = 0;
            $appModel->is_active = 0;
            $appModel->is_expired = 0;
            $master[] = $appModel;
            foreach ($appModel->getOldRecord($encryptedmobile) as $appData) {
                $appData->is_expired = 1;
                $appData->expired_datetime = date('Y-m-d H:i:s');
                $master[] = $appData;
            }
            $transaction = $this->generalModel->saveTransaction($master, ['app registration', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $data['token'] = $appModel->hash_key;
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionVerification() {
        $societyModel = new Society();
        $appModel = new TblAppActivation();
        $societyModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->imei_no = $this->post_data['imei'];
        $appModel = $appModel->activationInfo($this->post_data);
        $data = [];
        if (!empty($appModel)) {
            $appModel->is_active = 1;
            $appModel->updated_at = date('Y-m-d H:i:s');
            $transaction = $this->generalModel->saveTransaction([$appModel], ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $societyData = $societyModel->societyData();
            $data = ['token' => $this->post_data['token']];
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionInitialization() {
        $societyModel = new Society();
        $appModel = new TblAppActivation();
        $societyModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->hash_key = $this->post_data['token'];
        $appModel->imei_no = $this->post_data['imei'];
        $appModel->code = $this->post_data['dcs_code'];
        $encryptedmobile = Yii::$app->general->encryptData($appModel->mobile_no);
        $data = $societyModel->societyData();
        if (isset($data) && count($data) == 1) {
            
        } else {
            return $this->response;
        }
        $dcsInfo = $societyModel->dcsInfo($encryptedmobile);
        $data = $dcsInfo->attributes;
        $data['union_name'] = $dcsInfo->unionCode->union_name;
        $data['village_name'] = $dcsInfo->villageCode->village_name;

        $app_data = $appModel->activationDetail();
        if (!empty($app_data)) {
            $data['c_rate_code'] = $app_data['c_rate_code_a'];
            $data['b_rate_code'] = $app_data['b_rate_code_a'];
            $data['m_rate_code'] = $app_data['m_rate_code_a'];
            $data['m_id'] = $app_data['m_id_a'];
            $this->response['data'] = $data;
            return $this->response;
        } else {
            $message[] = 'Authentication Failed.';
            Yii::$app->apiError->error($message, 'error');
            return FALSE;
        }
    }

    public function actionAcknowledgement() {
        $appModel = new TblAppActivation();
        $code = $this->post_data['content'];
        foreach ($code as $field_name => $val) {
            $field = $field_name;
            $value = $val;
        }
        $appModel->setAttributes($this->post_data);
        $appModel->hash_key = $this->post_data['token'];
        $appModel->imei_no = $this->post_data['imei'];
        $appModel->code = $this->post_data['dcs_code'];

        $data = $appModel->activationDetail();
        $a_field = $field . '_a';
        $data->$a_field = $value;
        if ($data->save()) {
            $response = ['message' => 'Data Updated Successfully'];
            $this->response['data'] = $response;
        } else {
            $response = ['message' => 'Data not Updated Successfully'];
            $this->response['data'] = $response;
        }
    }

}
