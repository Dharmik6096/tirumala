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

class EiplAppController extends MasterController {

    public function actionVerifyIdentity() {
        $model = new TblIdentityMaster();
        $model->attributes = Yii::$app->request->getRawBody();
        $identity = $model->getIdentity();
        if (empty($identity)) {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Invalid Activation Code.']);
        } else {
            $this->response->setData($identity);
        }
        return $this->response;
    }

    public function actionLogin() {
        $modelSave = [];
        $model = new TblEiplAppLogin();
        $model->attributes = Yii::$app->request->getRawBody();
        $model->app_type = 1;
        $detail = $model->CheckMobileNo();
        if (!empty($detail) && count($detail) == 1) {
            $exist = $model->getLogin();
            if (!empty($exist)) {
                $model = $exist;
                $historyModel = new TblEiplAppLoginHistory();
                Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                $modelSave[] = $historyModel;
            } else {
                $model->is_active = 0;
                $model->is_expired = 0;
                $model->access_token = NULL;
            }
            $model->attributes = $detail[0];
             $model->otp_code = 1234;
//            $model->otp_code = rand(1000, 9999);
            if (!empty($model->loginOrg)) {
                $model->login_type = $model->loginOrg[0]->organization_type;
                $model->master_code = NULL;
            }
            $modelSave[] = $model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['app registration', 'create']);
            if ($transaction == 'customRedirect') {
                $message = 'Dear Your OTP Pin is ' . $model->otp_code . '.Enter this pin to login your account.';
                $sms_data = [];
                $sms_data['refecence_code'] = (string) $model->app_login_id;
                $sms_data['module_type'] = 'app_activation';
//                Yii::$app->general->saveAlertNotification($model->mobile_no, $message, $sms_data, true);
                $this->response->setMessage(['OTP Sent Successfuly.']);
            }
        } else {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Invalid Mobile No.']);
        }
        return $this->response;
    }

    public function actionVerifyOtp() {
        $modelSave = [];
        $model = new TblEiplAppLogin();
        $model->setAttributes(Yii::$app->request->getRawBody());
        $model->app_type = 1;
        $model = $model->activationInfo();
        if (!empty($model)) {
            $historyModel = new TblEiplAppLoginHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $modelSave[] = $historyModel;
            $model->access_token = Yii::$app->getSecurity()->generateRandomString(32);
            $model->is_active = 1;
            $model->is_expired = 0;
            $jsonData = Yii::$app->request->getRawBody();
            $model->device_id = $jsonData['device_id'];
            $model->imei_no = $jsonData['imei_no'];
            $modelSave[] = $model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['app verification', 'create']);
            if ($transaction == 'customRedirect') {
                $data = [];
                $data['access_token'] = $model->access_token;
                $this->response->setData($data);
            }
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
        $identity = Yii::$app->eiplapp->identity;
        if (!empty($identity)) {
            $masterCode = [];
            if (!empty(Yii::$app->eiplapp->identity->loginOrg)) {
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
        $resp['login_type'] = $identity->login_type;
        $resp['union'] = count($union) > $countVar ? [] : $union;
        $resp['plant'] = count($plant) > $countVar ? [] : $plant;
        $resp['mcc'] = count($mcc) > $countVar ? [] : $mcc;
        $resp['bmc'] = count($bmc) > $countVar ? [] : $bmc;
        $resp['dcs'] = count($dcs) > $countVar ? [] : $dcs;
        $resp['route'] = count($route) > $countVar ? [] : $route;
        $resp['member'] = count($member) > $countVar ? [] : $member;
        $response[] = $resp;
        $this->response->setData($response);
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

}
