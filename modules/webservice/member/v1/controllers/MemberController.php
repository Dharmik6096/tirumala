<?php

namespace app\modules\webservice\member\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\models\TblAppActivation;
use app\modules\dcsoperation\models\TblMember;
use Yii;

class MemberController extends ChildController {

    public function actionRegister() {
        $model = new TblMember();
        $appModel = new TblAppActivation();
        $model->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $encryptedmobile = Yii::$app->general->encryptData($appModel->mobile_no);
        $encryptedmobile = $appModel->mobile_no;
        $model->member_code = $this->post_data['member_code'];
        if ($this->post_data['type'] == 2) {
            $mdata = $model->memberInfo($encryptedmobile);
        }
        $data = [];
        if (isset($mdata) && count($mdata) == 1) {
            $master = [];
            $appModel->imei_no = $this->post_data['imei'];
            $appModel->code = $mdata[0]->member_code;
            $appModel->otp_code = rand(1000, 9999);
            $appModel->otp_code = 1234;
            $appModel->hash_key = Yii::$app->security->generateRandomString(20);
            $appModel->orignating_timestamp = date('Y-m-d H:i:s');
            $message = 'Dear Your OTP Pin is ' . $appModel->otp_code . '.Enter this pin to login your account.';
            
            $mobile = '91' . $appModel->mobile_no;
            $send = Yii::$app->bsmartsms->sendSmsPOST($mobile, $message);
            $sent = json_decode($send);
            $res = $sent->results;
            $status = '';
            foreach ($res as $result) {
                $status = $result->status;
            }
            
            // $result = Yii::$app->general->sendsms($appModel->mobile_no, $message);
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
        $model = new TblMember();
        $appModel = new TblAppActivation();
        $model->setAttributes($this->post_data);
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
            $model->member_code = $appModel->code;
            $memberdata = $model->getmember();
            if ($memberdata) {
                $data['url'] = '';
                $data['MemberCode'] = $appModel->code;
                $data['identity_code'] = $memberdata->dcs_code;
            }
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionInitialization() {
        $model = new TblMember();
        $appModel = new TblAppActivation();
        $model->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->hash_key = $this->post_data['token'];
        $appModel->imei_no = $this->post_data['imei'];
        $appModel->code = $this->post_data['member_code'];
        $encryptedmobile = Yii::$app->general->encryptData($appModel->mobile_no);
        $encryptedmobile = $appModel->mobile_no;
        $model->member_code = $this->post_data['member_code'];
        $data = $model->memberInfo($encryptedmobile);
        if (isset($data) && count($data) == 1) {
            if ($appModel->getActiveRecord() == 0) {
                $appModel->type = 1;
                $appModel->code = $data[0]->member_code;
                $appModel->otp_code = rand(1000, 9999);
                $appModel->orignating_timestamp = date('Y-m-d H:i:s');
                $appModel->sms_sent = 1;
                $appModel->sms_log = NULL;
                $appModel->is_delete = 0;
                $appModel->is_active = 1;
                $appModel->is_expired = 0;
                $transaction = $this->generalModel->saveTransaction([$appModel], ['app verification', 'create']);
                if ($transaction !== 'customRedirect') {
                    return FALSE;
                }
            }
        } else {
            return $this->response;
        }
        $member_data = $model->getmember($data[0]->member_code);
        $merge_data = array_merge((array) $member_data->attributes, (array) $data[0]->attributes);
        //   $attachment = Yii::$app->general->getAttachmentApi($member_data->tableSchema->name, $member_data->member_code);
        //   if (!empty($attachment)) {
        $merge_data['member_img'] = '';
        //  }
        $merge_data['dcs_name'] = $member_data->dcsCode->dcs_name;
        $merge_data['dcs_local'] = '';
        $merge_data['member_local'] = '';
        $merge_data['language_code'] = '';
        $merge_data['language_name'] = '';
        //$language = Yii::$app->general->getforeignkey($member_data->dcsCode->stateCode, 'language_code');
        $language = '';
        if ($language != '') {
            $merge_data['dcs_local'] = Yii::$app->general->getLocalName('TblDcsLocal', 'dcs_code', $member_data->dcsCode->dcs_code, $language);
            $merge_data['member_local'] = Yii::$app->general->getLocalName('TblMemberLocal', 'member_code', $member_data->member_code, $language);
            $merge_data['language_code'] = $language;
            $merge_data['language_name'] = $member_data->dcsCode->stateCode->languageCode->language_name;
        }
        $this->response['data'] = $merge_data;
        return $this->response;
    }

}
