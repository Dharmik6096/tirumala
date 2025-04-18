<?php

namespace app\modules\webservice\exchangeutility\v1\controllers;

use Yii;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationDetails;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\androiddpu\controllers\RestController;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\syncutility\models\TblInbox;
use app\modules\syncutility\models\TblSyncLog;
use app\models\TblEiplUserOrganizationMapping;
use app\modules\usermanagement\models\TblEiplAppUser;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblMccPlant;

class AppActivationController extends RestController {

    public function actionRegister() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
//            $contNewModel = new TblContactDetails();
//            $contNewModel->mobile_no = $content['mobile_no'];
//            $newModelData = $contNewModel->getContactDetailsRecord();
            $contNewModel = new TblEiplAppUser();
            $contNewModel->mobile_no = $content['mobile_no'];
            $newModelData = $contNewModel->getUserData();
            if (!empty($newModelData)) {

                $mapModel = new TblEiplUserOrganizationMapping();
                $mapModel->user_id = $newModelData->eipl_app_user_code;
                $mapModelData = $mapModel->getUserOrgs($mapModel->user_id);

                if (!empty($mapModelData) && !empty($mapModelData[0]['organization_type'])) {
                    $master = [];
                    $andoidIdModel = new TblAndroidInstallation();
                    $andoidIdModel->organization_code = $newModelData->eipl_app_user_code; //$data['organization_code'];
                    $andoidIdModel->organization_type = 'user';
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
                    $andoidIdDetailModel->version_no = !empty($content['version_no']) ? $content['version_no'] : NULL;
                    $andoidIdDetailModelData = $andoidIdDetailModel->getActiveCount();
//                        if (!empty($andoidIdDetailModelData)) {
//                            $res_data['message'] = 'Mobile Number already registered.';
//                        } else {
                    $andoidIdDetailModel->hash_key = Yii::$app->security->generateRandomString(20);
                    $andoidIdDetailModel->otp_code = 1234;
                    $andoidIdDetailModel->is_active = 0;
                    $andoidIdDetailModel->is_expired = 0;
                    $master[] = $andoidIdDetailModel;
                    $transaction = $this->generalModel->saveTransaction($master, ['app registration', 'create']);
                    if ($transaction !== 'customRedirect') {
                        return FALSE;
                    }
                    $res_data['token'] = $andoidIdDetailModel->hash_key;
                    $res_data['organization_code'] = $newModelData->eipl_app_user_code;
                    $res_data['organization_type'] = 'user';
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
        $model->imei_no = $data['imei'];
        $model = $model->getData();
        if (!empty($model)) {
            $model->is_active = 1;
            $transaction = $this->generalModel->saveTransaction([$model], ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            }
            $res_data['message'] = 'OTP Verified.';
//            $res_data['organization_code'] = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_code');
//            $res_data['organization_type'] = Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
        } else {
            $res_data['message'] = 'OTP Not Verified.';
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionInitialization() {
        $res_data = [];
        $data = $this->post_data;
        $model = new TblAndroidInstallationDetails();
        $id_model = $model->getActiveData($data);
        if (!empty($id_model)) {
            $userCode = $data['organization_code'];
            $mapModel = new TblEiplUserOrganizationMapping();
            $mapModel->user_id = $data['organization_code'];
            $mapModelData = $mapModel->getUserOrgs($mapModel->user_id);
            $org_code = [];
            if (!empty($mapModelData) && !empty($mapModelData[0]['organization_type'])) {
                $org_type = $mapModelData[0]['organization_type'];
                foreach ($mapModelData as $mapModelD) {
                    $org_code[] = $mapModelD['organization_code'];
                }
            }

            if (!empty($org_code)) {
                $type = $org_type;
                $detail_type = '';
                $code = $org_code;
                $dcs_code = [];
                $bmc_code = [];
                $mcc_code = [];
                $plant_code = [];
                $union_code = '';
                if ($type == 'VLC' || $type == 'DCS') {
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $dcs_code = $code;
                    $model_data = $model->getData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $bmc_code[] = $model_data->bmc_code;
                        $mcc_code[] = Yii::$app->general->getforeignkey($model_data->bmcCode, 'mcc_plant_code');
                        $plant_code[] = Yii::$app->general->getmultiforeignkey($model_data->bmcCode, ['tblMccPlant'], 'plant_code');
                    }
                } else if ($type == 'BMC') {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $code;
                    $detail_type = 'bmc';
                    $bmc_code = $code;
                    $model_data = $model->getAllBmcData();
                    foreach ($model_data as $model_d) {
                        $union_code = $model_d->union_code;
                        $mccCode = $model_d->mcc_plant_code;
                        if (!in_array($mccCode, $mcc_code)) {
                            $mcc_code[] = $mccCode;
                        }
                        $plant = Yii::$app->general->getforeignkey($model_d->tblMccPlant, 'plant_code');
                        if (!in_array($plant, $plant_code)) {
                            $plant_code[] = $plant;
                        }
                        $dcsCodes = $model_d->dcsCodes;
                        foreach ($dcsCodes as $dcsCode) {
                            $dcs_code[] = $dcsCode->dcs_code;
                        }
                    }
                } else if ($type == 'MCC') {
                    $model = new TblMccPlant();
                    $model->mcc_plant_code = $code;
                    $detail_type = 'mccPlant';
                    $mcc_code = $code;
                    $model_data = $model->getData();
                    if (!empty($model_data)) {
                        $union_code = $model_data->union_code;
                        $plant_code[] = $model_data->plant_code;
                    }
                    $bmcCodes = $model->bmcCodes;
                    foreach ($bmcCodes as $bmcCode) {
                        $bmc_code[] = $bmcCode->bmc_code;
                        $dcsCodes = $bmcCode->dcsCodes;
                        foreach ($dcsCodes as $dcsCode) {
                            $dcs_code[] = $dcsCode->dcs_code;
                        }
                    }
                }
                if (!empty($model_data)) {

                    $db_file = 'everest_utility.db';
                    $file = $type . '_' . $userCode . '_' . date('Y.m.d_H.i.s');
                    $fileName = $file . '.db';
                    $id_model->db_path = '/installation-identity/' . $file . '.db';
                    $FolderPath = Yii::$app->basePath . '/installation-identity/';
                    //                $zipfolder = Yii::$app->basePath . '/installation-identity/' . $file;
                    if (!is_dir($FolderPath)) {
                        $oldmask = umask(0);
                        mkdir($FolderPath, 0777, TRUE);
                        umask($oldmask);
                    }
                    copy($FolderPath . $db_file, $FolderPath . $fileName);
                    \Yii::$app->sqlite->_path = $FolderPath;
                    \Yii::$app->sqlite->_organisation_code = $org_code;
                    \Yii::$app->sqlite->_organisation_type = $org_type;
                    $transaction = $this->generalModel->saveTransaction([$id_model], ['app initialization', 'create']);
                    if ($transaction == 'customRedirect') {
                        $dcs_code = implode('\',\'', $dcs_code);
                        $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
                        $bmc_code = implode('\',\'', $bmc_code);
                        $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
                        $mcc_code = implode('\',\'', $mcc_code);
                        $mcc_code = !empty($mcc_code) ? '\'' . $mcc_code . '\'' : $mcc_code;
                        $plant_code = implode('\',\'', $plant_code);
                        $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
                        \Yii::$app->sqlite->createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_code, $plant_code, $code, $type, $union_code);
                        $res_data['db_path'] = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . $id_model->db_path;
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionStartUp() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['organization_code']) && !empty($data['organization_type'])) {
            $userCode = $data['organization_code'];
            $mapModel = new TblEiplUserOrganizationMapping();
            $mapModel->user_id = $data['organization_code'];
            $mapModelData = $mapModel->getUserOrgs($mapModel->user_id);

            $eipAppUser = new TblEiplAppUser();
            $eipAppUser->eipl_app_user_code = $mapModel->user_id;
            $eipAppUserData = $eipAppUser->findOne($mapModel->user_id);
            $userDetails = [];
            $userDetails['user_code'] = '';
            $userDetails['user_name'] = '';
            $userDetails['mobile_no'] = '';
            $userDetails['department'] = '';
            if (!empty($eipAppUserData)) {
                $userDetails['user_code'] = $eipAppUserData->eipl_app_user_code;
                $userDetails['user_name'] = $eipAppUserData->name;
                $userDetails['mobile_no'] = $eipAppUserData->mobile_no;
                $userDetails['department'] = Yii::$app->general->getforeignkey($eipAppUserData->departmentCode, 'department');
            }

            $org_code = [];
            $org_type = '';
            if (!empty($mapModelData) && !empty($mapModelData[0]['organization_type'])) {
                $org_type = $mapModelData[0]['organization_type'];
                foreach ($mapModelData as $mapModelD) {
                    $org_code[] = $mapModelD['organization_code'];
                }
                $res_data['org_codes'] = $org_code;
                $res_data['user_details'] = $userDetails;
            } else {
                $this->response['error']['code'] = '401';
                $this->response['status'] = 'error';
                $this->response['error']['message'] = ['This device is not allowed to '];
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionInbox() {
        $res_data = [];
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $data = $this->post_data;
//        $sync_active_model = $this->syncActiveRecord($data);
//        if (!empty($sync_active_model)) {
        if (true) {
            if (!empty($data['content'])) {
                foreach ($data['content'] as $transaction_data) {
                    $request = new HttpRequest();
                    $transaction_data = $request->camelCaseToUnderscore($transaction_data);
                    $model = new TblInbox();
                    $model->setAttributes($transaction_data);
                    $model->sync_timestamp = date('Y-m-d H:i:s');
                    $modelData = $model->findOne($model->uuid);
                    $syncModel = new TblSyncLog();
                    $syncModel->uuid = $model->uuid;
                    $syncModelData = $syncModel->findOne($syncModel->uuid);

                    if (!empty($modelData) || !empty($syncModelData)) {
                        $success_id[] = $transaction_data['uuid'];
                    } else {
                        $transaction = $this->generalModel->saveTransaction([$model], ['transactional data', 'create']);
                        if ($transaction == 'customRedirect') {
                            $message = 'Successfully Saved!';
                            $success_id[] = $transaction_data['uuid'];
                        } else {
                            $error_id[] = $transaction_data['uuid'];
                        }
                    }
                }
            }
        }
        $res_data['success_id'] = implode(',', $success_id);
        $res_data['error_id'] = implode(',', $error_id);
        $this->response['message'] = [$message];
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
