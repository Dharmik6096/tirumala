<?php

namespace app\modules\androiddpu\v4\controllers;

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
use app\modules\androiddpu\controllers\RestController;
use app\modules\organisation\models\TblMccPlant;
use app\modules\configuration\models\TblUnionConfigResult;
use yii\helpers\ArrayHelper;
use app\modules\usermanagement\models\TblAmcsAppMenuMapping;
use app\modules\configuration\models\TblMilkCollectionConfig;
use app\modules\webservice\eipl\models\TblAppOrganizationMapping;
use app\modules\installation\models\TblUserDownloadAck;
use app\modules\installation\models\TblUserAndroid;
use app\modules\installation\models\TblUserRoleMapping;
use app\modules\installation\models\TblRole;
use app\modules\organisation\models\TblAllowDcsManualCollectionRange;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblDeviceMasterMapping;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblDcsDeactive;
use app\modules\organisation\models\TblCustomerDeactive;
use app\modules\dcsoperation\models\TblMemberDeactive;

/**
 * Default controller for the `vendorapi` module
 */
class AndroidDpuController extends \app\modules\androiddpu\v3\controllers\AndroidDpuController
{

    public function actionRegister()
    {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code'])) {
                $type = $data['organization_type'];
                $detail_type = '';
                $code = $data['organization_code'];
                $deviceMaster = new TblDeviceMasterMapping();
                if ($type == 'VLC') {
                    $model = new TblDcs();
                    $model->dcs_code = $code;
                    $detail_type = 'society';
                    $model_data = $model->getData(TRUE);
                    $code = !empty($model_data) ? $model_data[0]->dcs_code : $code;
                } else if ($type == 'BMC') {
                    $model = new TblDcsBmc();
                    $model->bmc_code = $code;
                    $detail_type = 'bmc';
                    $model_data = $model->bmcData(TRUE);
                    $code = !empty($model_data) ? $model_data[0]->bmc_code : $code;
                } else if ($type == 'MCC') {
                    $model = new TblMccPlant();
                    $model->mcc_plant_code = $code;
                    $detail_type = 'mccPlant';
                    $model_data = $model->getData(TRUE);
                    $code = !empty($model_data) ? $model_data[0]->mcc_plant_code : $code;
                }
                if (!empty($model_data)) {
                    $deviceMapping = $deviceMaster->getDeviceMapping($data['device_id']);
                    $amcsDeviceMappingValidate = Yii::$app->general->getUnionConfiguration($model_data[0]->union_code, 'amcs_device_mapping_validate', $type);
                    if ($amcsDeviceMappingValidate != 1 || (!empty($deviceMapping) && $deviceMapping->applicability_code == $code)) {
                        $contact_data = Yii::$app->general->getDefaultContactDetail($code, $detail_type);
                        // Start: Change is temporary for d2d development which need to be changed after procution: Hardik - 30-10-2020
                        $hasDetails = false;
                        if (!empty($contact_data) && $contact_data->mobile_no == $content['mobile_no']) {
                            $hasDetails = true;
                        } else if (!empty($data['d2d_request'])) {
                            $appOrgModel = new TblAppOrganizationMapping();
                            $appOrgModel->mobile_no = $content['mobile_no'];
                            $appOrgModel->organization_type = 'DCS';
                            $appOrgModelData = $appOrgModel->getActiveData();
                            if (count($appOrgModelData) == 1 && $appOrgModelData[0]->organization_code == $code) {
                                $hasDetails = true;
                            }
                        }
                        if ($hasDetails) {
                            // END: Change is temporary for d2d development which need to be changed after procution: Hardik - 30-10-2020
                            // if (!empty($contact_data) && $contact_data->mobile_no == $content['mobile_no']) {
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
                            $andoidIdDetailModel->version_no = !empty($content['version_no']) ? $content['version_no'] : NULL;
                            $andoidIdDetailModel->d2d_request = !empty($data['d2d_request']) ? $data['d2d_request'] : 0;
                            $andoidIdDetailModelData = $andoidIdDetailModel->getActiveCount();
                            //if (!empty($andoidIdDetailModelData)) {
                            //   $res_data['message'] = 'Mobile Number already registered.';
                            //} else {
                            $unionData = TblUnions::find()->where(['union_code' => $model_data[0]->union_code, 'is_active' => 1])->one();
                            $eiplCode = !empty($unionData->eipl_code) ? ($unionData->eipl_code) : '';

                            if ($eiplCode == 'PRABHAT' && !empty($model_data[0]->password)) {
                                $andoidIdDetailModel->otp_code = $model_data[0]->password;
                            } else {
                                $andoidIdDetailModel->otp_code = 1234;
                            }
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
                    } else {
                        $this->response['error']['code'] = '401';
                        $this->response['status'] = 'error';
                        $this->response['error']['message'] = ['This device is not allowed to use for selected ' . $type];
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionVerification()
    {
        $res_data = [];
        $saveModel = [];
        $sendNotificaton = FALSE;
        $data = $this->post_data;
        $content = !empty($data['content']) ? $data['content'] : [];
        $model = new TblAndroidInstallationDetails();
        $model->hash_key = $data['token'];
        $model->otp_code = $content['otp_code'];
        $model->imei_no = $data['imei'];
        $model = $model->getData();
        $msgstr = 'OTP';
        if (strlen($content['otp_code']) > 4) {
            $msgstr = 'Password';
        }
        if (!empty($model)) {
            $model->is_active = 1;
            $model->sync_key = rand(1000, 9999);
            $model->sync_active = 1;

            // Start: Change is temporary for d2d development which need to be changed after procution: Hardik - 30-10-2020
            $org_code = !empty($data['organization_code']) ? $data['organization_code'] : '';
            $org_type = !empty($data['organization_type']) ? $data['organization_type'] : '';
            if (!empty($model->d2d_request) && $org_type == 'VLC') {
                $dcsModel = new TblDcs();
                $dcsModel->dcs_code = $org_code;
                $dcsModel->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'is_name_request' => 1], ['dcs_code' => $dcsModel->dcs_code]);
            }
            // END: Change is temporary for d2d development which need to be changed after procution: Hardik - 30-10-2020
            $saveModel[] = $model;
            $ackModel = new TblUserDownloadAck();
            $orgDetail = $ackModel->getOrgDetail($org_type, $org_code);

            $ackModel->hash_key = $model->hash_key;
            $ackModel->device_id = $data['device_id'];
            $existAck = $ackModel->getExistData($org_type);
            if (!empty($existAck)) {
                foreach ($existAck as $exist) {
                    $ackModel->updateAll(['download_pending' => 3], ['ack_id' => $exist['ack_id']]);
                }
            }
            $androidUsr = new TblUserAndroid();
            $ActiveUser = $androidUsr->getExistData($org_type, $ackModel);
            $orgCode = $org_type == 'VLC' ? $ackModel->dcs_code : ($org_type == 'BMC' ? $ackModel->bmc_code : $ackModel->mcc_plant_code);
            //CHECK MAIN USER EXIST
            $username = [$orgCode, $orgCode . '01'];
            $user = $androidUsr->getMainExistData($org_type, $ackModel, $username);
            if (!empty($user)) {
                if (!empty($ActiveUser)) {
                    foreach ($ActiveUser as $usrData) {
                        $usrAckModel = new TblUserDownloadAck();
                        $usrAckModel->attributes = $ackModel->attributes;
                        $usrAckModel->user_code = $usrData->user_code;
                        $usrAckModel->download_pending = 1;
                        $saveModel[] = $usrAckModel;
                    }
                }
            } else {
                $sendNotificaton = TRUE;
                //SUPERVISOR USER
                $orgCode = $org_type == 'VLC' ? $ackModel->dcs_code : ($org_type == 'BMC' ? $ackModel->bmc_code : $ackModel->mcc_plant_code);
                $androidUsr = new TblUserAndroid();
                $androidUsr->attributes = $ackModel->attributes;
                $androidUsr->scenario = 'installation';
                $contact = $androidUsr->getContactDetails($org_type, $ackModel);
                $androidUsr->user_code = Yii::$app->general->getCodeAutoIncrement($androidUsr);
                $androidUsr->name = !empty($contact) ? $contact->firstname : $org_type;
                $androidUsr->username = $orgCode . '01';
                $androidUsr->password = Yii::$app->general->generateRandomString();
                $androidUsr->mobile_no = !empty($contact) ? $contact->mobile_no : '';
                $androidUsr->email = !empty($contact) ? $contact->email : '';
                $saveModel[] = $androidUsr;
                $usrAckModel = new TblUserDownloadAck();
                $usrAckModel->attributes = $ackModel->attributes;
                $usrAckModel->user_code = $androidUsr->user_code;
                $usrAckModel->download_pending = 1;
                $saveModel[] = $usrAckModel;
                $roleModel = new TblRole();
                $roleDetails = $roleModel->getRoleDetails($org_type, 'SUPERVISOR');
                if (!empty($roleDetails)) {
                    $usrRole = new TblUserRoleMapping();
                    $usrRole->user_code = $androidUsr->user_code;
                    $usrRole->role_code = $roleDetails->role_code;
                    $existRoleMap = $usrRole::find()->where(['user_code' => $usrRole->user_code, 'role_code' => $usrRole->role_code])->one();
                    if (empty($existRoleMap)) {
                        $saveModel[] = $usrRole;
                    }
                }
                //ADMIN USER
                $androidUser = new TblUserAndroid();
                $androidUser->attributes = $ackModel->attributes;
                $androidUser->scenario = 'installation';
                $androidUser->user_code = $usrAckModel->user_code + 1;
                $androidUser->name = $org_type == 'VLC' ? 'VLC Admin' : ($org_type == 'BMC' ? 'BMC Admin' : 'BMC Admin');
                $androidUser->username = $orgCode;
                $androidUser->password = 'am' . $orgCode . 'cs';
                $androidUser->mobile_no = '0000000000';
                $saveModel[] = $androidUser;
                $userAckModel = new TblUserDownloadAck();
                $userAckModel->attributes = $ackModel->attributes;
                $userAckModel->user_code = $androidUser->user_code;
                $userAckModel->download_pending = 1;
                $saveModel[] = $userAckModel;
                $roleMapDetails = $roleModel->getRoleDetails($org_type, 'ADMIN');
                if (!empty($roleMapDetails)) {
                    $usrRole = new TblUserRoleMapping();
                    $usrRole->user_code = $androidUser->user_code;
                    $usrRole->role_code = $roleMapDetails->role_code;
                    $existRoleMap = $usrRole::find()->where(['user_code' => $usrRole->user_code, 'role_code' => $usrRole->role_code])->one();
                    if (empty($existRoleMap)) {
                        $saveModel[] = $usrRole;
                    }
                }
            }

            $transaction = $this->generalModel->saveTransaction($saveModel, ['app verification', 'create']);
            if ($transaction !== 'customRedirect') {
                return FALSE;
            } elseif ($sendNotificaton) {
                $union = Yii::$app->general->getforeignkey($androidUsr->unionCode, 'union_name');
                $username = $androidUsr->username;
                $pass = (!empty($androidUsr->password) && Yii::$app->general->decryptData($androidUsr->password) !== FALSE) ? Yii::$app->general->decryptData($androidUsr->password) : $androidUsr->password;
                //                $message = 'Welcome to ' . Yii::$app->general->getforeignkey($androidUsr->unionCode, 'union_name') . ',' . PHP_EOL . ' Your user name is ' . $androidUsr->username . ' and password is ' . $pass . ' to login in AMCS application.';
                $sms_data = [];
                $templateModel = new TblAlertTemplate();
                $templateData = $templateModel->getTemplateData('android_dpu', 'SMS', $androidUsr->union_code);
                if (!empty($templateData)) {
                    $arrFrom = array("{union}", "{username}", "{password}");
                    $arrTo = array($union, $username, $pass);
                    $word = $templateData->message;
                    $message = str_replace($arrFrom, $arrTo, $word);

                    if (YII_ENV_DEV) {
                    } else {
                        $contentId = !empty($templateData->api_master_id) ? $templateData->api_master_id : '1';
                        Yii::$app->general->saveAlertNotification($androidUsr->mobile_no, $message, $sms_data, TRUE, $templateData->header_info, $contentId);
                    }
                }
            }
            $androidDpuModel = new TblAndroidInstallationDetails();
            $androidDpuModel->android_installation_details_id = $model->android_installation_details_id;
            $androidDpuModel->android_installation_id = $model->android_installation_id;
            $detailsId = $androidDpuModel->getRecords();
            $androidDpuModel->updateAll(['sync_active' => 0], ['android_installation_id' => $model->android_installation_id, 'android_installation_details_id' => $detailsId]);
            $res_data['message'] = $msgstr . ' Verified.';
            $res_data['sync_key'] = (string) $model->sync_key;
            $this->getParentDetails($res_data, $data);
        } else {
            $res_data['message'] = $msgstr . ' Not Verified.';
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function getParentDetails(&$res_data, $data)
    {
        $code = $data['organization_code'];
        $type = $data['organization_type'];
        $parent_code = '';
        $parent_type = '';
        $parent_name = '';
        if ($type == 'VLC') {
            $model = new TblDcs();
            $model->dcs_code = $code;
            $detail_type = 'society';
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $parent_code = $model_data->bmc_code;
                $parent_type = 'BMC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->bmcCode, 'bmc_name');
            }
        } else if ($type == 'BMC') {
            $model = new TblDcsBmc();
            $model->bmc_code = $code;
            $detail_type = 'bmc';
            $bmc_code[] = $code;
            $model_data = $model->singleBmcData();
            if (!empty($model_data)) {
                $parent_code = $model_data->mcc_plant_code;
                $parent_type = 'MCC';
                $parent_name = Yii::$app->general->getforeignkey($model_data->tblMccPlant, 'name');
            }
        } else if ($type == 'MCC') {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $code;
            $detail_type = 'mccPlant';
            $mcc_plant_code[] = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $parent_code = $model_data->plant_code;
                $parent_type = 'PLANT';
                $parent_name = Yii::$app->general->getforeignkey($model_data->plantCode, 'name');
            }
        }
        $res_data['parent_type'] = $parent_type;
        $res_data['parent_code'] = $parent_code;
        $res_data['parent_name'] = $parent_name;
    }

    public function actionInitialization()
    {
        $db_file = 'everest_amcs_user_module.db';
        $db_file_version = '';
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['db_version'])) {
            $db_file_version = 'everest_amcs_' . $data['db_version'] . '.db';
            $db_file = file_exists(Yii::$app->basePath . '/installation-identity/' . $db_file_version) ? $db_file_version : $db_file;
        }
        $model = new TblAndroidInstallationDetails();
        $id_model = $model->getActiveData($data);
        if (!empty($id_model)) {
            $org_code = $data['organization_code'];
            $org_type = $data['organization_type'];
            if (!empty($org_code)) {
                $orgDetail = $this->getOrgDetail($org_type, $org_code);
                $dcs_code = $orgDetail['dcs_code'];
                $bmc_code = $orgDetail['bmc_code'];
                $mcc_plant_code = $orgDetail['mcc_plant_code'];
                $plant_code = $orgDetail['plant_code'];
                $union_code = $orgDetail['union_code'];
                $model_data = $orgDetail['model_data'];
                if (!empty($model_data)) {
                    if (empty($id_model['d2d_request'])) {
                        $file = $org_type . '_' . $org_code . '_' . date('Y.m.d_H.i.s');
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
                    }
                    $transaction = $this->generalModel->saveTransaction([$id_model], ['app initialization', 'create']);
                    $response = false;
                    if ($transaction == 'customRedirect') {
                        $response = false;
                        if (!empty($id_model['d2d_request'])) {
                            $model = new TblDcs();
                            $model->dcs_code = $org_code;
                            $detail_type = 'society';
                            $model_data = $model->getData();
                            $res_data['orgDetails']['union_code'] = str_replace("'", "", $orgDetail['union_code']);
                            $res_data['orgDetails']['plant_code'] = str_replace("'", "", $orgDetail['plant_code']);
                            $res_data['orgDetails']['mcc_plant_code'] = str_replace("'", "", $orgDetail['mcc_plant_code']);
                            $res_data['orgDetails']['bmc_code'] = str_replace("'", "", $orgDetail['bmc_code']);
                            $res_data['orgDetails']['dcs_code'] = str_replace("'", "", $orgDetail['dcs_code']);
                            $res_data['dcsInfo'] = $model_data;
                        } else {
                            $response = \Yii::$app->sqlite->createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $org_code, $org_type, $union_code);
                        }

                        if ($response) {
                            $res_data['db_path'] = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . $id_model->db_path;
                            /* commenting dcsSentboxGenerate as impact already in generate identity logic */
                            // $this->dcsSentboxGenerate($data);
                        } else {
                            $res_data['db_path'] = NULL;
                        }
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionStartUp()
    {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['organization_code']) && !empty($data['organization_type'])) {
            $org_code = $data['organization_code'];
            $org_type = $data['organization_type'];
            $orgDetail = $this->getOrgDetail($org_type, $org_code, FALSE);
            $model_data = $orgDetail['model_data'];
            if (!empty($model_data)) {
                $deviceMaster = new TblDeviceMasterMapping();
                $deviceMapping = $deviceMaster->getDeviceMapping($data['device_id']);
                $amcsDeviceMappingValidate = Yii::$app->general->getUnionConfiguration($model_data->union_code, 'amcs_device_mapping_validate', $org_type);
                if ($amcsDeviceMappingValidate != 1 || (!empty($deviceMapping) && $deviceMapping->applicability_code == $org_code)) {
                    $model = new TblAndroidInstallationDetails();
                    $id_model = $model->getActiveData($data);
                    if (!empty($id_model)) {
                        $mobileNo = !empty($id_model->mobile_no) ? $id_model->mobile_no : '';
                        $detailType = '';
                        $res_data['config'] = [];
                        $res_data['collectionConfig'] = [];
                        $res_data['rate'] = [];
                        $res_data['rate']['mPurchaseRateCode'] = "";
                        $res_data['rate']['mPurchaseRateCodeBlock'] = "";
                        $res_data['rate']['ePurchaseRateCode'] = "";
                        $res_data['rate']['ePurchaseRateCodeBlock'] = "";
                        $res_data['rate']['memberApplicableRate'] = "";
                        $res_data['rate']['bmcApplicableRate'] = "";
                        $res_data['memberDownload'] = FALSE;
                        $res_data['welcomeMessage'] = 'Welcome to Everest Instruments Pvt. Ltd.';
                        $res_data['shift_timing'] = [];
                        $res_data['shift_time_exceed'] = [];
                        $mcc_bmc_config = TRUE;
                        $MappedMilkType = [];
                        $dcs_code = $orgDetail['dcs_code'];
                        $bmc_code = $orgDetail['bmc_code'];
                        $mcc_plant_code = $orgDetail['mcc_plant_code'];
                        $plant_code = $orgDetail['plant_code'];
                        $union_code = $orgDetail['union_code'];
                        if ($org_type == 'VLC') {
                            $rangeModel = new TblAllowDcsManualCollectionRange();
                            $detailType = 'society';
                            $mcc_bmc_config = FALSE;
                            $current_rate_detail = Yii::$app->general->getSpData('sp_app_amcs_v2_current_rate_detail', [$org_code]);
                            if (!empty($current_rate_detail)) {
                                $purchase_rate_code = (string) $model_data->member_rate_code;
                                $res_data['rate']['mPurchaseRateCode'] = $current_rate_detail[0]['m_rate_code'];
                                $res_data['rate']['mPurchaseRateCodeBlock'] = !empty($purchase_rate_code) ? $purchase_rate_code : $current_rate_detail[0]['m_rate_block'];
                                $res_data['rate']['ePurchaseRateCode'] = $current_rate_detail[0]['e_rate_code'];
                                $res_data['rate']['ePurchaseRateCodeBlock'] = !empty($purchase_rate_code) ? $purchase_rate_code : $current_rate_detail[0]['e_rate_block'];
                            }
                            $collection_status = Yii::$app->general->getSpData('sp_society_collection_status', [$org_code]);
                            $collection_status = empty($collection_status) ? $model_data->is_active : $collection_status[0]['collection_status'];
                            $res_data['memberDownload'] = (bool) $model_data->is_name_request;
                            $res_data['config']['collectionBlock'] = !(bool) $collection_status;
                            $res_data['config']['dcsBlock'] = !(bool) $model_data->is_active;
                            $res_data['config']['dispatchMandate'] = $model_data->is_dispatch_mandate > 0 ? true : false; //(bool) $model_data->is_dispatch_mandate;
                            $res_data['config']['weightManual'] = (bool) $rangeModel->getManualData($model_data, 'is_weight_manual'); // $model_data->is_weight_manual;
                            $res_data['config']['qualityManual'] = (bool) $rangeModel->getManualData($model_data, 'is_quality_manual'); //$model_data->is_quality_manual;
                            $config = $model_data->dpuIncentiveMaster;
                            if (!empty($config)) {
                                $att = $config->attributes;
                                $res_data['collectionConfig']['m_start_time'] = $att['m_start_time'];
                                $res_data['collectionConfig']['m_cutoff_time'] = $att['m_cutoff_time'];
                                $res_data['collectionConfig']['m_lock_time'] = $att['m_lock_time'];
                                $res_data['collectionConfig']['e_start_time'] = $att['e_start_time'];
                                $res_data['collectionConfig']['e_cutoff_time'] = $att['e_cutoff_time'];
                                $res_data['collectionConfig']['e_lock_time'] = $att['e_lock_time'];
                                $res_data['collectionConfig']['inc_rate'] = $att['inc_rate'];
                                $res_data['collectionConfig']['inc_deduction'] = $att['inc_deduction'];
                            }
                            $MappedMilkType = $model_data->tblDcsMilkType;
                            $collectionIncentive = $model_data->collectionIncentive;
                            $output = \Yii::$app->general->getSpData('portal_sp_device_config', [$model_data->union_code, 'DCS', $org_code]);
                        } else if ($org_type == 'BMC') {
                            $detailType = 'bmc';
                            $MappedMilkType = $model_data->tblBmcMilkType;
                            $collectionIncentive = [];
                            $output = \Yii::$app->general->getSpData('portal_sp_device_config', [$model_data->union_code, 'BMC', $org_code]);
                            $dcs = TblDcs::find()->where(['bmc_code' => $model_data->bmc_code, 'is_bmc' => 1, 'is_name_request' => 1])->one();
                            $res_data['memberDownload'] = !empty($dcs) ? (bool) $dcs->is_name_request : FALSE;
                        } else if ($org_type == 'MCC') {
                            $detailType = 'mccPlant';
                            $MappedMilkType = $model_data->tblMccMilkType;
                            $collectionIncentive = [];
                        }
                        if ($mcc_bmc_config) {
                            //$res_data['memberDownload'] = FALSE;
                            $res_data['config']['collectionBlock'] = FALSE;
                            $res_data['config']['dcsBlock'] = FALSE;
                            $res_data['config']['dispatchMandate'] = FALSE;
                            $res_data['config']['weightManual'] = (bool) $model_data->is_weight_manual;
                            $res_data['config']['qualityManual'] = (bool) $model_data->is_quality_manual;
                            $res_data['rate']['mPurchaseRateCode'] = "";
                            $res_data['rate']['mPurchaseRateCodeBlock'] = "";
                            $res_data['rate']['ePurchaseRateCode'] = "";
                            $res_data['rate']['ePurchaseRateCodeBlock'] = "";
                            $res_data['rate']['mPurchaseRateCodeBmc'] = "";
                            $res_data['rate']['mPurchaseRateCodeBlockBmc'] = "";
                            $res_data['rate']['ePurchaseRateCodeBmc'] = "";
                            $res_data['rate']['ePurchaseRateCodeBlockBmc'] = "";
                            $res_data['collectionConfig']['m_start_time'] = "";
                            $res_data['collectionConfig']['m_cutoff_time'] = "";
                            $res_data['collectionConfig']['m_lock_time'] = "";
                            $res_data['collectionConfig']['e_start_time'] = "";
                            $res_data['collectionConfig']['e_cutoff_time'] = "";
                            $res_data['collectionConfig']['e_lock_time'] = "";
                            $res_data['collectionConfig']['inc_rate'] = "";
                            $res_data['collectionConfig']['inc_deduction'] = "";
                        }
                        $animalType = [];
                        $milkTypeRate = [];
                        foreach ($MappedMilkType as $milktype) {
                            $min_fat = $min_snf = $min_clr = $max_fat = $max_snf = $max_clr = 0.0;
                            $milktype->app_type = $org_type;
                            $rate = isset($milktype->rtpl) && !empty($milktype->rtpl) ? $milktype->rtpl : 0;
                            $rate_range = $milktype->rateChartRange;
                            if (!empty($rate_range)) {
                                $min_fat = $rate_range->min_fat;
                                $max_fat = $rate_range->max_fat;
                                $min_snf = $rate_range->min_snf;
                                $max_snf = $rate_range->max_snf;
                                $min_clr = $rate_range->min_clr;
                                $max_clr = $rate_range->max_clr;
                            }
                            $animalType[] = [
                                'milk_type_code' => $milktype->milk_type_code,
                                'milk_type_name' => $milktype->milkTypeCode->animal_type_name,
                                'min_fat' => $min_fat,
                                'max_fat' => $max_fat,
                                'min_snf' => $min_snf,
                                'max_snf' => $max_snf,
                                'min_clr' => $min_clr,
                                'max_clr' => $max_clr
                            ];
                            $milkTypeRate[] = [
                                'milk_type_code' => $milktype->milk_type_code,
                                'milk_type_name' => $milktype->milkTypeCode->animal_type_name,
                                'rtpl' => $rate
                            ];
                        }
                        $IncentiveDeduction = [];
                        foreach ($collectionIncentive as $incentive) {
                            $IncentiveDeduction[] = [
                                'from_time' => $incentive->from_time,
                                'to_time' => $incentive->to_time,
                                'scheme_type' => $incentive->scheme_type,
                                'shift_code' => $incentive->shift_code,
                                'amount' => $incentive->amount,
                                'from_date' => $incentive->from_date,
                                'to_date' => $incentive->to_date,
                            ];
                        }
                        $res_data['collectionConfig']['allowedMilkType'] = $animalType;
                        $res_data['collectionConfig']['milkTypeRate'] = $milkTypeRate;
                        $res_data['collectionConfig']['collectionIncentiveDeduction'] = $IncentiveDeduction;
                        $qualityParamConfig = [];
                        $fat = 6.5;
                        $snf = 9.0;
                        $lrClr = 0;
                        $lr2Clr = 0;

                        $collConfigModel = new TblMilkCollectionConfig();
                        $collConfigModel->union_code = $union_code;
                        $collConfigModelData = $collConfigModel->getData();
                        if (!empty($configData)) {
                            $lrClr = $collConfigModelData->lr1_for_clr;
                            $lr2Clr = $collConfigModelData->lr2_for_clr;
                        }
                        $clr = ($snf - ($fat * $lrClr) - $lr2Clr) * 4;
                        $clr = $clr < 0 ? 0 : round($clr, 1);
                        $qualityParamConfig['fat'] = $fat;
                        $qualityParamConfig['snf'] = $snf;
                        $qualityParamConfig['clr'] = $clr;
                        $res_data['collectionConfig']['qualityParam'] = $qualityParamConfig;
                        $model = new TblUnionConfigResult();
                        $model->union_code = $model_data->union_code;
                        $model->config_for = $org_type;

                        if (!empty($output)) {
                            foreach ($output as $d) {
                                $res_data['deviceConfig'][$d['device_config_key']] = $d['config_type_code'];
                            }
                        }

                        foreach ($model->getConfigList() as $d) {
                            $res_data['config'][$d['config_key']] = $d['config_result_key'];
                        }

                        $res_data['welcomeMessage'] = 'Welcome to ' . $model_data->unionCode->union_name . '.';
                        $dcs_code = ',' . implode(',', $dcs_code) . ',';
                        $bmc_code = ',' . implode(',', $bmc_code) . ',';
                        $mcc_plant_code = ',' . implode(',', $mcc_plant_code) . ',';
                        $plant_code = ',' . implode(',', $plant_code) . ',';
                        $is_member_rate = isset($res_data['config']['required_member_rate']) ? $res_data['config']['required_member_rate'] : '1';
                        if ($is_member_rate == '1') {
                            $member_rate = Yii::$app->general->getSpData('sp_app_amcs_v2_pending_rate_detail_member', [$dcs_code, $id_model->device_id, $id_model->hash_key]);
                            if (!empty($member_rate)) {
                                $res_data['rate']['memberApplicableRate'] = implode(',', array_column($member_rate, 'purchase_rate_code'));
                            }
                        }

                        $downloadRateAtBmc = true;
                        if (isset($res_data['config']['download_rate_at_bmc']) && $res_data['config']['download_rate_at_bmc'] == '0') {
                            $downloadRateAtBmc = false;
                        }
                        if ($downloadRateAtBmc && $mcc_bmc_config) {
                            $bmc_rate = Yii::$app->general->getSpData('sp_app_amcs_v2_pending_rate_detail_bmc', [$plant_code, $mcc_plant_code, $bmc_code, $dcs_code, $id_model->device_id, $id_model->hash_key]);
                            if (!empty($bmc_rate)) {
                                $res_data['rate']['bmcApplicableRate'] = implode(',', array_column($bmc_rate, 'purchase_rate_code'));
                            }
                        }

                        $shiftTimigData = Yii::$app->general->getSpData('sp_app_amcs_v2_collection_shift_time', [$org_type, $org_code]);
                        if (!empty($shiftTimigData)) {
                            $res_data['shift_timing'] = $shiftTimigData;
                        }
                        $shiftTimeExceedData = Yii::$app->general->getSpData('sp_app_amcs_v4_collection_shift_time_exceed', [$org_type, $org_code]);
                        if (!empty($shiftTimeExceedData)) {
                            $res_data['shift_time_exceed'] = $shiftTimeExceedData;
                        }
                        $model = new TblAmcsAppMenuMapping();
                        $model->union_code = $model_data->union_code;
                        $model->application_type = $org_type;
                        $menu_mapping = $model->getMenuMapping();
                        $res_data['menu_mapping'] = implode(',', $menu_mapping);
                        $res_data['is_surveyor'] = '0';
                        $contact_data = Yii::$app->general->getDefaultContactDetail($org_code, $detailType);
                        // Start: Change is temporary for d2d development which need to be changed after procution: Hardik - 30-10-2020
                        if (!empty($contact_data) && $contact_data->mobile_no == $mobileNo) {
                            $res_data['is_surveyor'] = !empty($contact_data->department) && strtolower($contact_data->department) == 'surveyor' ? '1' : '0';
                        } else if (!empty($id_model->d2d_request)) {
                            $appOrgModel = new TblAppOrganizationMapping();
                            $appOrgModel->mobile_no = $mobileNo;
                            $appOrgModel->organization_type = 'DCS';
                            $appOrgModelData = $appOrgModel->getActiveData();
                            if (count($appOrgModelData) == 1 && $appOrgModelData[0]->organization_code == $org_code) {
                                $loginUserData = $appOrgModelData[0];
                                $contactDetails = $loginUserData->tblContactDetails;
                                $res_data['is_surveyor'] = !empty($contactDetails) && !empty($contactDetails->department) && strtolower($contactDetails->department) == 'surveyor' ? '1' : '0';
                            }
                        }
                        // END: Change is temporary for d2d development which need to be changed after procution: Hardik - 30-10-2020
                        // if (!empty($contact_data)) {
                        //    $res_data['is_surveyor'] = !empty($contact_data->department) && strtolower($contact_data->department) == 'surveyor' ? '1' : '0';
                        // }
                    }
                } else {
                    $this->response['error']['code'] = '401';
                    $this->response['status'] = 'error';
                    $this->response['error']['message'] = ['This device is not allowed to use for selected ' . $data['organization_type']];
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionAppLockConfig()
    {
        $appLockConfig = [];
        $data = $this->post_data;
        if (!empty($data['device_id'])) {
            $controls = [];
            $controls['device_id'] = $data['device_id'];
            $appLockConfigData = Yii::$app->general->getSpData('sp_app_v3_app_lock_config', $controls);
            if (!empty($appLockConfigData)) {
                foreach ($appLockConfigData as $key => $appLockConfigD) {
                    $k = $appLockConfigD['config_key_value'];
                    $appLockConfig[$k] = (bool) $appLockConfigD['result'];
                }
            }
        }
        $this->response['data'] = $appLockConfig;
        return $this->response;
    }

    public function DcsSentboxGenerate($post)
    {
        $device = $post['device_id'];
        $model = new TblDcsDeactive();
        $deactiveData = $model->getDeactiveRecords(false, $post);
        $this->setSentBox($post, $model, $device, $deactiveData, 'dcs_deactive_code', 'TblDcs', 'dcs_code', 0, 1, 2, 3);

        $CustModel = new TblCustomerDeactive();
        $deactiveData = $CustModel->getDeactiveRecords(false, $post);
        $this->setSentBox($post, $CustModel, $device, $deactiveData, 'customer_deactive_code', 'TblCustomerMaster', 'customer_code', 0, 1, 2, 3);

        $MemberModel = new TblMemberDeactive();
        $deactiveData = $MemberModel->getDeactiveRecords(false, $post, '');
        $this->setSentBox($post, $MemberModel, $device, $deactiveData, 'member_deactive_code', 'TblMember', 'member_code', 0, 1, 2, 3);
    }

    public function setSentBox($post, $model, $device, $data, $key, $masterModel, $f_key, $status, $u_status, $success, $error)
    {
        if (!empty($data)) {
            $ids = array_map(function ($e) use ($key) {
                return $e->{$key};
            }, $data);
            // $update = $model->updateFileStatus($ids, $u_status);
            foreach ($data as $row) {
                $model_name = Yii::$app->path->define($masterModel);
                $modelMaster = new $model_name();
                $existData = $modelMaster::find()->where([$f_key => $row->{$f_key}])->one();
                if (!empty($existData)) {
                    $existData->is_active = $status;
                    $sentboxArray = [];
                    $encrypt = $modelMaster->encryptModel($existData->attributes);
                    $existData->setAttributes($encrypt);
                    // if (!empty($existData->customer_type)) {
                    //     $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $existData->bmc_code);
                    // } else {
                    //     $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $existData->dcs_code);
                    // }
                    $array = [];
                    $array['code'] = $post['organization_code'];
                    $array['type'] = $post['organization_type'];
                    $sentboxArray[] = $array;

                    foreach ($sentboxArray as $sent) {
                        $flag = 'UPDATE';
                        $sentbox = $this->sentboxModel($sent['code'], $sent['type'], $existData->union_code, $device);
                        if (!($sentbox->setSentbox($existData, $flag))) {
                            $row->data_post_status = $error;
                            $row->response_datetime = date('Y-m-d H:i:s');
                            $row->resp_desc = 'SentBox Entry is not Generated';
                            $row->save(FALSE);
                        } else {
                            $row->data_post_status = $success;
                            $row->response_datetime = date('Y-m-d H:i:s');
                            $row->resp_desc = 'Sentbox Generated';
                            $row->save(FALSE);
                        }
                    }
                }
            }
        }
    }

    private function sentboxModel($code, $type, $union, $device)
    {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $union;
        $sentbox->dest_org_type = $type;
        $sentbox->device_id = $device;
        return $sentbox;
    }

    public function actionSendOtp()
    {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            $androidUsr = TblUserAndroid::find()->where(['user_code' => $content['user_code'], 'is_active' => 1])->one();
            if (!empty($androidUsr)) {
                if (YII_ENV_DEV) {
                    $otp = 1234;
                } else {
                    $otp = rand(1000, 9999);
                }
                $res_data['otp'] = $otp;
                $sms_data = [];
                $templateModel = new TblAlertTemplate();
                $templateData = $templateModel->getTemplateData('app_password_reset', 'SMS', $androidUsr->union_code);
                if (!empty($templateData)) {
                    $arrFrom = array("{OTP}");
                    $arrTo = array($otp);
                    $word = $templateData->message;
                    $message = str_replace($arrFrom, $arrTo, $word);
                    if (YII_ENV_DEV) {
                    } else {
                        $contentId = !empty($templateData->api_master_id) ? $templateData->api_master_id : '1';
                        Yii::$app->general->saveAlertNotification($androidUsr->mobile_no, $message, $sms_data, TRUE, $templateData->header_info, $contentId);
                    }
                    $res_data['message'] = 'OTP send successfully';
                }
            } else {
                $this->response['error']['message'] = ['User not found.'];
            }
            $this->response['data'] = $res_data;
            return $this->response;
        }
    }

    public function actionChangePassword()
    {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            $androidUsr = TblUserAndroid::find()->where(['user_code' => $content['user_code'], 'is_active' => 1])->one();
            if (!empty($androidUsr)) {
                if ($content['password'] == $content['repeat_password']) {
                    $androidUsr->password = $content['password'];
                    $androidUsr->repeat_password = $content['repeat_password'];
                    $res_data['message'] = "Password updated sucessfully.";
                    if (!$androidUsr->save()) {
                        $this->response['error']['message'] = ['Password not updated sucessfully.'];
                        $res_data['message'] = '';
                    }
                } else {
                    $this->response['error']['message'] = ['Password and Repeat password does not match.'];
                }
            } else {
                $this->response['error']['message'] = ['User not found.'];
            }
            $this->response['data'] = $res_data;
            return $this->response;
        }
    }
}
