<?php

namespace app\modules\webservice\controllers;

use Yii;
use app\models\GeneralModel;
use app\modules\installation\models\InstallationIdentity;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;
use app\models\IdentityMaster;

/**
 * Default controller for the `restservices` module
 */
class ChildController extends RestController {

    protected $generalModel;
    public $replace_array = [];

    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
    }

    public function replaceColoumn($model) {
        foreach ($this->replace_array['replace_coloumn'] as $key => $val) {
            if (array_key_exists($key, $model)) {
                $model[$val] = $model[$key];
                unset($model[$key]);
            }
        }
        return $model;
    }

    public function addColoumn($model) {
        foreach ($this->replace_array['add_coloumn'] as $key => $val) {
            $model[$key] = $val;
        }
        return $model;
    }

    public function getOrgCodes($content) {
        $data = [];
        $model = new User();
        $model->setAttributes($content);
        $identityModel = new IdentityMaster();
        $identity = $identityModel->getIdentity();
        $model->username = $identity->organization_code . '#' . $model->username;
        $user_data = $model->find()->where(['username' => $model->username])->one();
        $mapping_model = new TblUserOrganizationMapping();
        $mapping_model->user_id = $user_data->id;
        $map_data = $mapping_model->getUserOrgs($user_data->id);
        $union = [];
        $bmc = [];
        $dcs = [];
        $mcc = [];
        $plant = [];
        foreach ($map_data as $key => $value) {
            if ($value['organization_type'] == 'UNION') {
                $union[] = $value['organization_code'];
            }
            if ($value['organization_type'] == 'BMC') {
                $bmc[] = $value['organization_code'];
            }
            if ($value['organization_type'] == 'MCC') {
                $mcc[] = $value['organization_code'];
            }
            if ($value['organization_type'] == 'PLANT') {
                $plant[] = $value['organization_code'];
            }
            if ($value['organization_type'] == 'DCS') {
                $dcs[] = $value['organization_code'];
            }
        }
        $data['union'] = $union;
        $data['bmc'] = $bmc;
        $data['dcs'] = $dcs;
        $data['mcc'] = $mcc;
        $data['plant'] = $plant;
        return $data;
    }
    
    public function getSpData($sp, $param) {
        $str = '';
        $coutn = count($param);
        foreach ($param as $key => $value) {
            $str.=':' . $key . ',';
        }
        $str = substr($str, 0, -1);
        $command = \Yii::$app->db->createCommand("{CALL {$sp}({$str})}");
        foreach ($param as $key => $value) {
            $command->bindValue(':' . $key, $value);
        }
        return $command->queryAll();
    }

}
