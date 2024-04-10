<?php

namespace app\modules\androiddpu\v4\controllers;

use Yii;
use app\modules\androiddpu\controllers\RestController;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationDetails;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblRouteMapping;

class EspAppController extends RestController {

    public function actionRegister() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code'])) {
                $type = $data['organization_type'];
                $detail_type = '';
                $code = $data['organization_code'];
                if ($type == 'VLC') {
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $model_data = $model->getData(TRUE);
                    $code = !empty($model_data) ? $model_data[0]->dcs_code : $code;
                } else if ($type == 'ROUTE') {
                    $model = new TblRouteMapping();
                    $model->route_code = $code;
                    $detail_type = 'routeMapping';
                    $model_data = $model->getRouteData(TRUE);
                    $code = !empty($model_data) ? $model_data[0]->route_code : $code;
                }
                if (!empty($model_data)) {
                    $contact_data = Yii::$app->general->getDefaultContactDetail($code, $detail_type);
                    if (!empty($contact_data) && $contact_data->mobile_no == $content['mobile_no']) {
                        $master = [];
                        $andoidIdModel = new TblAndroidInstallation();
                        $andoidIdModel->organization_code = $code;
                        $andoidIdModel->organization_type = $data['organization_type'];
                        $andoidIdModelData = $andoidIdModel->getData();
                        if (!empty($andoidIdModelData)) {
                            $andoidIdModel = $andoidIdModelData;
                        } else {
                            $andoidIdModel->android_installation_id = $andoidIdModel->getCode();
                        }
                        $master[] = $andoidIdModel;
                        $andoidIdDetailModel = new TblAndroidInstallationDetails();
                        $andoidIdDetailModel->android_installation_id = $andoidIdModel->android_installation_id;
                        $andoidIdDetailModel->device_id = $data['device_id'];
                        $andoidIdDetailModel->imei_no = $data['imei'];
                        $andoidIdDetailModel->mobile_no = $content['mobile_no'];
                        $andoidIdDetailModel->version_no = $data['version_no'];
                        $andoidIdDetailModel->device_type = 'ESP';
                        $andoidIdDetailModel->otp_code = 1234;
                        $andoidIdDetailModel->hash_key = Yii::$app->security->generateRandomString(20);
                        $andoidIdDetailModel->is_active = 0;
                        $andoidIdDetailModel->is_expired = 0;
                        $master[] = $andoidIdDetailModel;
                        $transaction = $this->generalModel->saveTransaction($master, ['app registration', 'create']);
                        if ($transaction !== 'customRedirect') {
                            return FALSE;
                        }
                        $res_data['token'] = $andoidIdDetailModel->hash_key;
                        $res_data['org_pk_code'] = $code;
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionVerification() {
        $res_data = [];
        $saveModel = [];
        $data = $this->post_data;
        $content = !empty($data['content']) ? $data['content'] : [];
        $model = new TblAndroidInstallationDetails();
        $model->hash_key = $data['token'];
        $model->otp_code = $content['otp_code'];
        $model->imei_no = $data['imei'];
        $model = $model->getData();
        $msgstr = 'OTP';

        if (!empty($model)) {
            $model->is_active = 1;
            $model->sync_key = rand(1000, 9999);
            $model->sync_active = 1;

            $org_code = !empty($data['organization_code']) ? $data['organization_code'] : '';
            $org_type = !empty($data['organization_type']) ? $data['organization_type'] : '';

            $dcsModel = new TblDcs();
            if ($org_type == 'VLC') {
                $dcsModel->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'is_name_request' => 1], ['dcs_code' => $org_code]);
            } elseif ($org_type == 'ROUTE') {
                $dcsModel->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'is_name_request' => 1], ['route_code' => $org_code]);
            }

            $saveModel[] = $model;

            $transaction = $this->generalModel->saveTransaction($saveModel, ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $androidDpuModel = new TblAndroidInstallationDetails();
            $androidDpuModel->android_installation_details_id = $model->android_installation_details_id;
            $androidDpuModel->android_installation_id = $model->android_installation_id;
            $detailsId = $androidDpuModel->getRecords();
            $androidDpuModel->updateAll(['sync_active' => 0], ['android_installation_id' => $model->android_installation_id, 'android_installation_details_id' => $detailsId, 'device_type' => 'ESP']);
            $res_data['message'] = $msgstr . ' Verified.';
            $res_data['sync_key'] = (string) $model->sync_key;
            $this->getParentDetails($res_data, $data);
        } else {
            $res_data['message'] = $msgstr . ' Not Verified.';
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function getParentDetails(&$res_data, $data) {
        $code = $data['organization_code'];
        $type = $data['organization_type'];
        $parent_code = '';
        $parent_type = '';
        $parent_name = '';
        if ($type == 'VLC') {
            $model = new TblDcs();
            $model->dcs_code = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $parent_code = $model_data->bmc_code;
                $parent_type = 'BMC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->bmcCode, 'bmc_name');
            }
        } else if ($type == 'ROUTE') {
            $model = new TblRouteMapping();
            $model->route_code = $code;
            $model_data = $model->getRouteData();
            if (!empty($model_data)) {
                $parent_code = $model_data->to_dest;
                $parent_type = 'BMC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->dcsBmcCode, 'bmc_name');
            }
        }
        $res_data['parent_type'] = $parent_type;
        $res_data['parent_code'] = $parent_code;
        $res_data['parent_name'] = $parent_name;
    }

    public function actionInitialization() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['organization_code']) && !empty($data['organization_type'])) {
            $controls = [];
            $controls['organization_type'] = $data['organization_type'];
            $controls['organization_code'] = $data['organization_code'];
            $res_data = Yii::$app->general->getSpData('sp_app_esp_v4_dcs_master', $controls);
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
