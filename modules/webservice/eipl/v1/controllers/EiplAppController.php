<?php

namespace app\modules\webservice\eipl\v1\controllers;

use Yii;
use app\modules\webservice\eipl\controllers\MasterController;
use app\modules\webservice\eipl\models\TblIdentityMaster;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\webservice\eipl\models\TblEiplAppLoginHistory;
use app\modules\webservice\eipl\models\TblAppOrganizationMapping;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\webservice\eipl\v1\V1;
//use app\modules\webservice\eipl\models\TblUserAppScheduler;
use app\modules\webservice\eipl\models\TblEiplAppLoginTemp;
use app\modules\dcsoperation\models\TblShift;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\sms\models\TblApiMaster;
use app\modules\usermanagement\models\TblEiplAppMenuActionsMapping;

class EiplAppController extends MasterController {

    public function actionLogin() {
        $modelSave = [];
        $model = new TblEiplAppLogin();
        $model->attributes = Yii::$app->request->getRawBody();
        $model->app_type = empty($model->app_type) ? 1 : $model->app_type;
        if ($model->login_type == 'DRIVER') {
            $detail = $model->DriverMobileNoDetail()->asArray()->all();
        } else {
            $detail = $model->MobileNoDetail();
        }
        //login_type = MEMBER,DCS,BMC,MCC,PLANT,UNION,ROUTE,DRIVER
        if (!empty($model->login_type)) {
            $isValid = FALSE;
            foreach ($detail as $key => $subArr) {
                if ($model->login_type == $detail[$key]['login_type']) {
                    $isValid = TRUE;
                }
            }
        } else {
            $isValid = TRUE;
        }
        if ($isValid && !empty($detail)) {
            $temp_model = new TblEiplAppLoginTemp();
            $temp_model->attributes = $model->attributes;
            $temp_model->union_code = $detail[0]['union_code'];
            $temp_model->otp_code = "1234";
            if (!YII_ENV_DEV) {
                $LiveOTPforHOMobileApp = Yii::$app->general->getUnionConfiguration($temp_model->union_code, 'liveotp_for_ho_mobile_app', 'PORTAL');
                $temp_model->otp_code = !empty($LiveOTPforHOMobileApp) ? rand(1000, 9999) : "1234";
            }
            $modelSave[] = $temp_model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['app registration', 'create']);
            if ($transaction == 'customRedirect') {
                if (!YII_ENV_DEV && !empty($LiveOTPforHOMobileApp)) {
                    $templateModel = new TblAlertTemplate();
                    $templateData = $templateModel->getTemplateData('eipl_app_otp', 'SMS', $temp_model->union_code);
                    $apiMasterRecord = TblApiMaster::find()->select('api_master_id')->where(['receiver_type' => 'SMS', 'union_code' => $temp_model->union_code, 'is_active' => 1])->one();
                    if (!empty($templateData) && !empty($apiMasterRecord)) {
                        $message = str_replace('{otp}', $temp_model->otp_code, $templateData->message);
                        $sms_data = [];
                        $sms_data['refecence_code'] = (string) $temp_model->app_login_id;
                        $sms_data['module_type'] = 'app_activation';
                        Yii::$app->general->saveAlertNotification($temp_model->mobile_no, $message, $sms_data, true, $templateData->header_info, $apiMasterRecord->api_master_id);
                    }
                }
                foreach ($detail as $key => $subArr) {
                    unset($detail[$key]['master_type']);
                    unset($detail[$key]['master_code']);
                    unset($detail[$key]['module_type']);
//                    unset($detail[$key]['department']);
                }
                $this->response->setData($detail);
                $this->response->setMessage(['OTP Sent successfully and it will be valid for only 5 min.']);
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

    public function actionVerifyOtp() {
        $modelSave = [];
        $content = Yii::$app->request->getRawBody();
        $temp_model = new TblEiplAppLoginTemp();
        $temp_model->setAttributes($content);
        $temp_model->app_type = empty($temp_model->app_type) ? 1 : $temp_model->app_type;
        $temp_model = $temp_model->activationInfo();
        if (!empty($temp_model)) {
            $model = new TblEiplAppLogin();
            $model->attributes = $temp_model->attributes;
            $model->setAttributes($content);
            if ($model->login_type == 'MEMBER') {
                $query = $model->memberMobileDetail();
            } elseif ($model->login_type == 'DRIVER') {
                $query = $model->DriverMobileNoDetail();
            } else {
                $query = $model->orgMobileDetail();
            }
            $detail = $query->asArray()->all();
            if (!empty($detail) && (count($detail) == 1 || $model->login_type == 'DRIVER')) {
                $exist = $model->getLogin();
                if (!empty($exist)) {
                    if ($exist->is_block == 1) {
                        $this->response->setStatusCode($this->eiplResponseCode->statusError);
                        $this->response->setMessage(['Your Login is Blocked.']);
                        return $this->response;
                    }
                    $model = $exist;
                    $historyModel = new TblEiplAppLoginHistory();
                    Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                    $modelSave[] = $historyModel;
                    $model->setAttributes($content);
                }
                $model->setAttributes($detail[0]);
                $model->access_token = Yii::$app->getSecurity()->generateRandomString(32);
                $model->is_active = 1;
                $model->is_expired = 0;
                $modelSave[] = $model;
                $transaction = $this->generalModel->saveDeleteTransaction($modelSave, [], [$temp_model], ['app verification', 'create']);

                if ($transaction == 'customRedirect') {
                    $data = [];
                    $data['access_token'] = $model->access_token;
                    $this->response->setData($data);
                } else {
                    $this->response->setStatusCode($this->eiplResponseCode->statusError);
                    $this->response->setMessage(['Unable to verify OTP.']);
                }
            } else {
                $this->response->setStatusCode($this->eiplResponseCode->statusError);
                $this->response->setMessage(['Details does not match with master data.']);
            }
        } else {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Invalid OTP.']);
        }
        return $this->response;
    }

    public function actionInitialization() {
        $countVar = 500;
        $resp = [];
        $union = [];
        $plant = [];
        $mcc = [];
        $bmc = [];
        $route = [];
        $dcs = [];
        $member = [];
        $interval = 0;
        $allowScheduler = 0;
        $morningStartTime = '';
        $morningEndTime = '';
        $eveningStartTime = '';
        $eveningEndTime = '';
        $serverDateTime = date('Y-m-d H:i:s');
        $identity = Yii::$app->eiplapp->identity;
        if (!empty($identity)) {
            if ($identity->is_block == 1) {
                $this->response->setStatusCode($this->eiplResponseCode->statusError);
                $this->response->setMessage(['Your Login is Blocked.']);
                return $this->response;
            }
            $model = new TblEiplAppLogin();
            $model->mobile_no = Yii::$app->eiplapp->identity->mobile_no;
            $model->app_type = empty(Yii::$app->eiplapp->identity->app_type) ? 1 : Yii::$app->eiplapp->identity->app_type;
            $model->module_code = $identity->module_code;
            if ($identity->login_type == 'MEMBER') {
                $query = $model->memberMobileDetail();
            } elseif ($identity->login_type == 'DRIVER') {
                $query = $model->DriverMobileNoDetail();
            } else {
                $query = $model->orgMobileDetail();
            }
            $detail = $query->asArray()->all();
            if (!empty($detail) && (count($detail) == 1 || $identity->login_type == 'DRIVER')) {
                $identity->attributes = $detail[0];
                $content = Yii::$app->request->getRawBody();
                $identity->version_no = !empty($content['version_no']) ? $content['version_no'] : $identity->version_no;
                if ($identity->login_type != 'DRIVER') {
                    if ($identity->login_type != 'MEMBER' && !empty($identity->loginOrg)) {
                        $identity->login_type = $identity->loginOrg[0]->organization_type;
                    }
                }
                $transaction = $this->generalModel->saveTransaction([$identity], ['app login', 'edit']);
                if ($transaction == 'customRedirect') {
                    if ($model->login_type != 'DRIVER') {
                        $masterCode = [];
                        if ($identity->login_type != 'MEMBER' && !empty(Yii::$app->eiplapp->identity->loginOrg)) {
                            // if (empty($identity->master_code)) {
                            $appOrgMap = new TblAppOrganizationMapping();
                            $appOrgMap->mobile_no = $identity->mobile_no;
                            $masterCode = $appOrgMap->getData();
                        } else {
                            $masterCode[] = $identity->master_code;
                        }
                        $masterType = $identity->login_type;
                        $union_url = 'union/master';
                        $plant_url = 'plant/master';
                        $mcc_url = 'mcc/master';
                        $bmc_url = 'bmc/master';
                        $dcs_url = 'dcs/master';
                        $member_url = 'member/master';
                        $route_url = 'route/master';
                        switch ($masterType) {
                            case 'UNION':
                                $union = $this->getOrgInfo($masterCode, $masterType, $union_url);
                                break;
                            case 'PLANT':
                                $plant = $this->getOrgInfo($masterCode, $masterType, $plant_url);
                                $unionCode = ArrayHelper::map($plant, 'union_code', 'union_code');
                                $union = $this->getOrgInfo($unionCode, 'UNION', $union_url);
                                break;
                            case 'MCC':
                                $mcc = $this->getOrgInfo($masterCode, $masterType, $mcc_url);
                                $plantCode = ArrayHelper::map($mcc, 'plant_code', 'plant_code');
                                $plant = $this->getOrgInfo($plantCode, 'PLANT', $plant_url);
                                $unionCode = ArrayHelper::map($plant, 'union_code', 'union_code');
                                $union = $this->getOrgInfo($unionCode, 'UNION', $union_url);
                                break;
                            case 'BMC':
                                $bmc = $this->getOrgInfo($masterCode, $masterType, $bmc_url);
                                $mccCode = ArrayHelper::map($bmc, 'mcc_plant_code', 'mcc_plant_code');
                                $mcc = $this->getOrgInfo($mccCode, 'MCC', $mcc_url);
                                $plantCode = ArrayHelper::map($mcc, 'plant_code', 'plant_code');
                                $plant = $this->getOrgInfo($plantCode, 'PLANT', $plant_url);
                                $unionCode = ArrayHelper::map($plant, 'union_code', 'union_code');
                                $union = $this->getOrgInfo($unionCode, 'UNION', $union_url);
                                break;
                            case 'ROUTE':
                                $route = $this->getOrgInfo($masterCode, $masterType, $route_url);
                                $routeCode = ArrayHelper::map($route, 'route_code', 'route_code');
                                $dcs = $this->getOrgInfo($routeCode, 'ROUTE', $dcs_url);
                                $bmcCode = ArrayHelper::map($dcs, 'bmc_code', 'bmc_code');
                                $bmc = $this->getOrgInfo($bmcCode, 'BMC', $bmc_url);
                                $mccCode = ArrayHelper::map($bmc, 'mcc_plant_code', 'mcc_plant_code');
                                $mcc = $this->getOrgInfo($mccCode, 'MCC', $mcc_url);
                                $plantCode = ArrayHelper::map($mcc, 'plant_code', 'plant_code');
                                $plant = $this->getOrgInfo($plantCode, 'PLANT', $plant_url);
                                $unionCode = ArrayHelper::map($plant, 'union_code', 'union_code');
                                $union = $this->getOrgInfo($unionCode, 'UNION', $union_url);
                                break;
                            case 'DCS':
                                $dcs = $this->getOrgInfo($masterCode, $masterType, $dcs_url);
                                $bmcCode = ArrayHelper::map($dcs, 'bmc_code', 'bmc_code');
                                $bmc = $this->getOrgInfo($bmcCode, 'BMC', $bmc_url);
                                $mccCode = ArrayHelper::map($bmc, 'mcc_plant_code', 'mcc_plant_code');
                                $mcc = $this->getOrgInfo($mccCode, 'MCC', $mcc_url);
                                $plantCode = ArrayHelper::map($mcc, 'plant_code', 'plant_code');
                                $plant = $this->getOrgInfo($plantCode, 'PLANT', $plant_url);
                                $unionCode = ArrayHelper::map($plant, 'union_code', 'union_code');
                                $union = $this->getOrgInfo($unionCode, 'UNION', $union_url);
                                break;
                            case 'MEMBER':
                                $member = $this->getOrgInfo($masterCode, $masterType, $member_url);
                                $dcsCode = ArrayHelper::map($member, 'dcs_code', 'dcs_code');
                                $dcs = $this->getOrgInfo($dcsCode, 'DCS', $dcs_url);
                                $bmcCode = ArrayHelper::map($dcs, 'bmc_code', 'bmc_code');
                                $bmc = $this->getOrgInfo($bmcCode, 'BMC', $bmc_url);
                                $mccCode = ArrayHelper::map($bmc, 'mcc_plant_code', 'mcc_plant_code');
                                $mcc = $this->getOrgInfo($mccCode, 'MCC', $mcc_url);
                                $plantCode = ArrayHelper::map($mcc, 'plant_code', 'plant_code');
                                $plant = $this->getOrgInfo($plantCode, 'PLANT', $plant_url);
                                $unionCode = ArrayHelper::map($plant, 'union_code', 'union_code');
                                $union = $this->getOrgInfo($unionCode, 'UNION', $union_url);
                                break;
                        }
                    }

//            $schedulerModel = new TblUserAppScheduler();
//            $schedulerModel->user_type = $masterType;
//            $schedulerModelData = $schedulerModel->getRecord();
//            if (!empty($schedulerModelData)) {
//                $interval = $schedulerModelData->interval;
//                $allowScheduler = (int) $schedulerModelData->allow_scheduler;
//                $morningStartTime = $schedulerModelData->m_start_time;
//                $morningEndTime = $schedulerModelData->m_end_time;
//                $eveningStartTime = $schedulerModelData->e_start_time;
//                $eveningEndTime = $schedulerModelData->e_end_time;
//            }
                    $profile_data = [];
                    if ($identity->login_type == 'DRIVER') {
                        $profile_data['name'] = $detail[0]['module_name'];
                    } else {
                        $profile_data['name'] = (strtoupper($identity->login_type) == 'MEMBER') ? Yii::$app->general->getforeignkey($identity->masterDetail, 'member_name') : (Yii::$app->general->getforeignkey($identity->masterDetail, 'firstname') . ' ' . Yii::$app->general->getforeignkey($identity->masterDetail, 'lastname') . ' ' . Yii::$app->general->getforeignkey($identity->masterDetail, 'surname'));
                    }
                    $profile_data['user_type'] = $identity->login_type;
                    $profile_data['mobile_no'] = $identity->mobile_no;
                    $profile_data['email'] = ($identity->login_type != 'DRIVER') ? Yii::$app->general->getforeignkey($identity->masterDetail, 'email') : '';
//                    $profile_data['department'] = Yii::$app->general->getforeignkey($identity->departmentCode, 'department');
                    $company_detail = [];
                    $company_detail['union'] = count($union) == 1 ? stripcslashes($union[0]['union_name'] . '\n' . $union[0]['union_code']) : '';
                    $company_detail['plant'] = count($plant) == 1 ? stripcslashes($plant[0]['plant_name'] . '\n' . $plant[0]['plant_code']) : '';
                    $company_detail['mcc'] = count($mcc) == 1 ? stripcslashes($mcc[0]['mcc_plant_name'] . '\n' . $mcc[0]['mcc_plant_code']) : '';
                    $company_detail['bmc'] = count($bmc) == 1 ? stripcslashes($bmc[0]['bmc_name'] . '\n' . $bmc[0]['bmc_code']) : '';
                    $company_detail['route'] = count($route) == 1 ? stripcslashes($route[0]['route_name'] . '\n' . $route[0]['route_code']) : '';
                    $company_detail['dcs'] = count($dcs) == 1 ? stripcslashes($dcs[0]['dcs_name'] . '\n' . $dcs[0]['dcs_code']) : '';
                    $company_detail['member'] = count($member) == 1 ? stripcslashes($member[0]['member_name'] . '\n' . $member[0]['member_code']) : '';
                    $profile_data['company_detail'] = $company_detail;
                    $resp['user_profile'] = $profile_data;
                    $resp['server_date_time'] = $serverDateTime;
                    $resp['login_type'] = $identity->login_type;
                    $resp['union'] = count($union) > $countVar ? [] : $union;
                    $resp['plant'] = count($plant) > $countVar ? [] : $plant;
                    $resp['mcc'] = count($mcc) > $countVar ? [] : $mcc;
                    $resp['bmc'] = count($bmc) > $countVar ? [] : $bmc;
                    $resp['dcs'] = count($dcs) > $countVar ? [] : $dcs;
                    $resp['route'] = count($route) > $countVar ? [] : $route;
                    $resp['member'] = count($member) > $countVar ? [] : $member;
                    $resp['gender'] = Yii::$app->dropdown->getTableData('gender');
                    $resp['relation'] = Yii::$app->dropdown->getTableData('relation');
                    $ShiftModel = new TblShift();
                    $resp['shiftDetail'] = $ShiftModel->getShiftData();
                    $response[] = $resp;
                    $this->response->setData($response);
//        $resp['allow_scheduler'] = $allowScheduler;
//        $resp['interval'] = $interval;
//        $resp['morning_start_time'] = $morningStartTime;
//        $resp['morning_end_time'] = $morningEndTime;
//        $resp['evening_start_time'] = $eveningStartTime;
//        $resp['evening_end_time'] = $eveningEndTime;
                } else {
                    $this->response->setStatusCode($this->eiplResponseCode->statusError);
                    $this->response->setMessage(['Unable to update Login Type.']);
                }
            } else {
                $identity->is_active = 0;
                $transaction = $this->generalModel->saveTransaction([$identity], ['app login', 'edit']);
                $this->response->setStatusCode($this->eiplResponseCode->statusError);
                $this->response->setMessage(['Mobile No. Not Found/Duplicate.']);
            }
        } else {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Invalid Login.']);
        }
        return $this->response;
    }

    private function getOrgInfo($masterCode, $masterType, $api) {
        $req_data = [];
        $req_data['organization_code'] = $masterCode;
        $req_data['organization_type'] = $masterType;
        $data = V1::getLabels($api);
        $sp_name = $data['sp'];
        $param = !empty($data['param']) ? explode('#', $data['param']) : [];
        foreach ($param as $value) {
            $array_val = explode(':', $value);
            $param_val = !empty($array_val[1]) ? $array_val[1] : (isset($req_data[$value]) ? $req_data[$value] : NULL);
            $param_val = is_array($param_val) ? (',' . implode(',', $param_val) . ',') : $param_val;
            $sp_param[] = $param_val;
        }
        return \Yii::$app->general->getSpData($sp_name, $sp_param);
    }

    public function actionAppMenu() {
        $identity = Yii::$app->eiplapp->identity;
        if (empty($identity)) {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Invalid Access.']);
            return $this->response;
        }

        $appType = $identity->app_type;
        $loginType = $identity->login_type;
        $department = $identity->department;
        $menuList = TblEiplAppMenuActionsMapping::find()
                ->alias('m')
                ->select(['a.action_code', 'a.action_name', 'a.service_url', 'a.description', 'a.parent_code', 'a.sequence_no'])
                ->innerJoin('tbl_eipl_app_menu_actions a', 'a.action_code = m.action_code')
                ->where(['m.app_type' => $appType, 'm.login_type' => $loginType, 'm.department' => $department, 'a.is_active' => 1])
                ->orderBy(['a.sequence_no' => SORT_ASC])
                ->asArray()
                ->all();
        $this->response->setData($menuList);
        return $this->response;
    }
}
