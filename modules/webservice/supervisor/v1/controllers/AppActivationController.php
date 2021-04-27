<?php

namespace app\modules\webservice\supervisor\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\models\TblAppActivation;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;
use Yii;
use app\modules\sms\models\TblAlertTemplate;

class AppActivationController extends ChildController {

    public function actionRegister() {
        $model = new User();
        $model->setAttributes($this->post_data);
        $model->setAttributes($this->post_data['content']);
        $model_data = $model->getUserData();
        $data = [];

        if (!empty($model_data)) {
            $mapping_model = new TblUserOrganizationMapping();
            $mapping_model->user_id = $model_data->id;
            $mapping_data = $mapping_model->getUserMapping();

            if (!empty($mapping_data)) {
//                $union_code = substr($mapping_data[0]->organization_code, 0, 3);
                $appModel = new TblAppActivation();
                $appModel->setAttributes($this->post_data);
                $appModel->setAttributes($this->post_data['content']);
                $appModel->imei_no = $this->post_data['imei'];
                $appModel->type = $this->post_data['type'];
                $appModel->device_id = $this->post_data['device_id'];
                $appModel->code = $model_data->id;
//                $appModel->identity_code = NULL; //$union_code;
                $appModel->mobile_no = $model_data->mobile_no;
                $appModel->otp_code = 1234;
//                $appModel->otp_code = rand(1000, 9999);
                $appModel->hash_key = Yii::$app->security->generateRandomString(20);
                $appModel->orignating_timestamp = date('Y-m-d H:i:s');
//                $templateModel = new TblAlertTemplate();
//                $templateData = $templateModel->getTemplateData('supervisor_app_otp');
//                $message = str_replace('{otp}', $appModel->otp_code, $templateData->message);

//                $message = 'Dear Your OTP Pin is ' . $appModel->otp_code . '.Enter this pin to login your account.';
//                $result = Yii::$app->general->sendsms($appModel->mobile_no, $message,$templateData->header_info);
                // $result = '';
                $appModel->sms_sent = 1;
                $appModel->is_delete = 0;
                $appModel->is_active = 0;
                $appModel->is_expired = 0;
                $appModel->sms_log = ''; //$result;
                $transaction = $this->generalModel->saveTransaction([$appModel], ['app registration', 'create']);
                if ($transaction !== 'customRedirect') {
                    return FALSE;
                }
                $data['token'] = $appModel->hash_key;
            }
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionVerification() {
        $appModel = new TblAppActivation();
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->imei_no = $this->post_data['imei'];
        $appModel = $appModel->activationInfo($this->post_data);
        $data = [];
        if (!empty($appModel)) {
            $appModel->is_active = 1;
            $transaction = $this->generalModel->saveTransaction([$appModel], ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $data['token'] = $appModel->hash_key;
            $data['user_code'] = $appModel->code;
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionInitialization() {
        $appModel = new TblAppActivation();
        $appModel->setAttributes($this->post_data);
        $appModel->setAttributes($this->post_data['content']);
        $appModel->imei_no = $this->post_data['imei'];
        $data = [];
        $api_list = [];
        $appModelData = $appModel->getActiveRecordData($this->post_data);

        if (!empty($appModelData)) {
//            $master_service_model = new TblAppMasterService();
//            $master_service_data = $master_service_model->getAllData();
//            if (!empty($master_service_data)) {
//                foreach ($master_service_data as $maser_service) {
//                    $api_list[] = $maser_service->request_url;
//                   
//                }
//            }
            $data['user_name'] = Yii::$app->general->getforeignkey($appModelData->userCode, 'name');
//            $data['user_type'] = Yii::$app->general->getforeignkey($appModelData->userCode, 'vendor_type');
//            $data['department'] = Yii::$app->general->getforeignkey($appModelData->userCode, 'department_code');
//            $data['designation'] = Yii::$app->general->getforeignkey($appModelData->userCode, 'designation_code');
//            $data['master_api'] = $api_list;
        }
        return $this->response['data'] = $data;
    }

}
