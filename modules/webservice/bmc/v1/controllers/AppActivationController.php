<?php

namespace app\modules\webservice\bmc\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\models\TblAppActivation;
use app\modules\installation\models\InstallationIdentity;
use app\modules\organisation\models\TblDcsBmc;
use Yii;

class AppActivationController extends ChildController {

    public function actionRegister() {
        $bmcModel = new TblDcsBmc();
        $appModel = new TblAppActivation();
        $bmcModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->code = $this->post_data['bmc_code'];
        $encryptedmobile = Yii::$app->general->encryptData($appModel->mobile_no);
        if ($this->post_data['type'] == 3) {
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
        $bmcModel = new TblDcsBmc();
        $appModel = new TblAppActivation();
        $bmcModel->setAttributes($this->post_data);
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
            $bmcData = $bmcModel->bmcData();
            $data = ['token' => $this->post_data['token']];
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionInitialization() {
        $bmcModel = new TblDcsBmc();
        $appModel = new TblAppActivation();
        $bmcModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->hash_key = $this->post_data['token'];
        $appModel->imei_no = $this->post_data['imei'];
        $appModel->code = $this->post_data['bmc_code'];
        $encryptedmobile = Yii::$app->general->encryptData($appModel->mobile_no);
        $bmcModel->bmc_code = $this->post_data['bmc_code'];
        $data = $bmcModel->bmcData();
        if (isset($data) && count($data) == 1) {
            
        } else {
            return $this->response;
        }
        $bmcInfo = $bmcModel->bmcInfo();
        $data = $bmcInfo->attributes;
        $detail = Yii::$app->general->getDefaultContactDetail($data['bmc_code'], 'bmc');
        $data['mobile_no'] = '';
        if (!empty($detail)) {
            $data['mobile_no'] = $detail->mobile_no;
        }
        $data['union_name'] = Yii::$app->general->getforeignkey($bmcInfo->unionCode, 'union_name');
        $data['village_name'] = Yii::$app->general->getforeignkey($bmcInfo->villageCode, 'village_name');
        $data['hamlet_name'] = Yii::$app->general->getforeignkey($bmcInfo->hamletCode, 'hamlet_name');
        $data['mcc_plant_name'] = Yii::$app->general->getforeignkey($bmcInfo->tblMccPlant, 'name');
        $data['bmc_type_name'] = Yii::$app->general->getforeignkey($bmcInfo->tblBmcType, 'bmc_type_name');
        $data['state_name'] = Yii::$app->general->getforeignkey($bmcInfo->stateCode, 'state_name');
        $data['district_name'] = Yii::$app->general->getforeignkey($bmcInfo->districtCode, 'district_name');
        $data['sub_district_name'] = Yii::$app->general->getforeignkey($bmcInfo->subDistrictCode, 'sub_district_name');
        $data['manufacturer_name'] = Yii::$app->general->getforeignkey($bmcInfo->manufacturerCode, 'manufacturer_name');
        $data['animal_type_name'] = Yii::$app->general->getforeignkey($bmcInfo->bmcMilkType, 'animal_type_name');

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

}
