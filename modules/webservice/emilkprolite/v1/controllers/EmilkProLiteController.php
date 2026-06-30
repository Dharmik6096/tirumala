<?php

namespace app\modules\webservice\emilkprolite\v1\controllers;

use Yii;
use app\modules\webservice\eipl\models\TblIdentityMaster;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\webservice\eipl\models\TblEiplAppLoginHistory;
use app\modules\webservice\eipl\models\TblAppOrganizationMapping;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\emilkprolite\controllers\MasterController;
use app\modules\sms\models\TblAlertTemplate;

class EmilkProLiteController extends MasterController {

    public function actionLogin() {
        echo "test";
        die;
        $modelSave = [];
        $model = new TblEiplAppLogin();
        $model->attributes = Yii::$app->request->getRawBody();
        $model->app_type = 1;
        $detail = $model->MobileNoDetail();
        if (!empty($detail)) {
            $temp_model = new TblEiplAppLoginTemp();
            $temp_model->attributes = $model->attributes;
            $temp_model->otp_code = rand(1000, 9999);
            $modelSave[] = $temp_model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['app registration', 'create']);
            if ($transaction == 'customRedirect') {
                $message1 = 'Dear Your OTP Pin is ' . $temp_model->otp_code . '.Enter this pin to login your account.';
                $sms_data = [];
                $sms_data['refecence_code'] = (string) $temp_model->app_login_id;
                $sms_data['module_type'] = 'app_activation';
                $templateModel = new TblAlertTemplate();
                $templateData = $templateModel->getTemplateData('emilk_pro_lite_otp');
                if (!empty($templateData)) {
                    $message = str_replace('{otp}', $temp_model->otp_code, $templateData->message);
                    $contentId = !empty($templateData->api_master_id) ? $templateData->api_master_id : '1';
                    Yii::$app->general->saveAlertNotification($temp_model->mobile_no, $message, $sms_data, true, $templateData->header_info, $contentId);
                    foreach ($detail as $key => $subArr) {
                        unset($detail[$key]['master_type']);
                        unset($detail[$key]['master_code']);
                        unset($detail[$key]['module_type']);
                        unset($detail[$key]['department']);
                    }
                    $this->response->setData($detail);
                    $this->response->setMessage(['OTP Sent successfully and it will be valid for only 5 min.']);
                }
            } else {
                $this->response->setStatusCode($this->eiplResponseCode->statusError);
                $this->response->setMessage(['Unable to Login.']);
            }
        } else {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Invalid Mobile No.']);
        }
        return $this->response;
    }

}
