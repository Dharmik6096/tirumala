<?php

namespace app\modules\androiddpu\controllers;

use yii\web\Controller;
//use app\modules\vendorapi\controllers\RestController;
use Yii;
use ReflectionClass;
use DateTime;
use app\modules\vendorapi\models\TblVendorApiData;
use webvimark\modules\UserManagement\models\User;
use app\modules\vendorapi\Vendorapi;
use app\models\TblUserOrganizationMapping;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationDetails;

/**
 * Default controller for the `vendorapi` module
 */
class AndroidDpuController extends RestController {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionRegister() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($content['organization_type'])) {
                $type = $content['organization_type'];
                $detail_type = '';
                $code = $data['organization_code'];
                if ($type == 'AMCS') {
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $model_data = $model->getData();
                } else if ($type == 'BMC') {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $code;
                    $detail_type = 'bmc';
                    $model_data = $model->bmcData();
                }
//                var_dump(Yii::$app->security->generateRandomString(20));die;
                if (!empty($model_data)) {
                    $contact_data = Yii::$app->general->getDefaultContactDetail($code, $detail_type);
                    if (!empty($contact_data) && $contact_data->mobile_no == $content['mobile_no']) {
                        $master = [];
                        $andoidIdModel = new TblAndroidInstallation();
                        $andoidIdModel->android_installation_id = $andoidIdModel->getCode();
                        $andoidIdModel->organization_code = $data['organization_code'];
                        $andoidIdModel->organization_type = $content['organization_type'];
                        $master[] = $andoidIdModel;
                        $andoidIdDetailModel = new TblAndroidInstallationDetails();
                        $andoidIdDetailModel->android_installation_id = $andoidIdModel->android_installation_id;
                        $andoidIdDetailModel->mobile_no = $content['mobile_no'];
                        $andoidIdDetailModel->hash_key = Yii::$app->security->generateRandomString(20);
                        $andoidIdDetailModel->otp_code = 1234;
                        $andoidIdDetailModel->is_active = 0;
                        $andoidIdDetailModel->is_expired = 0;
                        $andoidIdDetailModel->device_id = $data['device_id'];
                        $master[] = $andoidIdDetailModel;
                        $transaction = $this->generalModel->saveTransaction($master, ['app registration', 'create']);
                        if ($transaction !== 'customRedirect') {
                            return FALSE;
                        }
                        $res_data['token'] = $andoidIdDetailModel->hash_key;
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionVerification() {
        $res_data = [];
        $data = $this->post_data;
        $content = !empty($data['content']) ? $data['content'] : [];
        $model = new TblAndroidInstallationDetails();
        $model->hash_key = $data['token'];
        $model->otp_code = $content['otp_code'];
        $model = $model->getData();
        if (!empty($model)) {
            $model->is_active = 1;
            $transaction = $this->generalModel->saveTransaction([$model], ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $res_data['token'] = $model->hash_key;
            $res_data['organization_code'] = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_code');
            $res_data['organization_type'] = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
            ;
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
