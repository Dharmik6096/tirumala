<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

class User extends \webvimark\modules\UserManagement\models\User {

    public function getUserList() {
        $data = $this->find()->where(['portal_type' => 'portal', 'is_active' => 1])->all();
        $list = ArrayHelper::map($data, 'user_code', function($array, $key) {
                    return $array['name'] . '-' . $array['department'];
                });
        return $list;
    }

    public function getLoginDetails() {
        return $this->find()
                        ->where(['is_active' => 1, 'mobile_no' => $this->mobile_no])
                        ->one();
    }

    public function getAssignList($location_type, $code) {
        $userData = $this->find()->alias('u')
                        ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
                        ->where(['tbl_user_organization_mapping.organization_code' => $code, 'tbl_user_organization_mapping.organization_type' => $location_type])->all();
        $user = ArrayHelper::map($userData, 'id', 'name');
        return $user;
    }

    public function getCode() {

        $identity = \app\models\IdentityMaster::find()->one();
        $federation = '00';
        $union = '000';
        $other = '0000';
        if ($identity) {
            switch ($identity->organization_type) {
                case 'Federations' :
                    $federation = $identity->organization_code;
                    break;
                case 'Unions' :
                    $federation = $identity->parent_code;
                    $union = $identity->organization_code;
                    break;
            }

            $code = $federation . $union . $other;

            $val = (new \yii\db\Query)
                    ->select(["MAX(convert(int,id)) as id"])
                    ->from('user')
                    ->one();
            $newcode = (int) $val['id'] + 1;


            $value = $code . str_pad($newcode, 5, '0', STR_PAD_LEFT);
            return $value;
        }
    }

    public function getContactCode($user_code) {
        return $this->find()->where(['user_code' => $user_code])->one();
    }

    public function setUserCode($login_type, $location_type, $codes) {

        $org_code = '';

        switch ($login_type) {

            case 1:
                if ($login_type == 'procurement_staff') {
                    $org_code = $this->getUserCode($login_type, $codes['plant_code'], 'PLANT');
                }
                break;
            case 2:
                if ($login_type == 'procurement_staff') {
                    $org_code = $this->getUserCode('procurement_staff', $codes['plant_code'], 'PLANT');
                } else if ($login_type != 'vsp' && $login_type != 'service_engineer' && $login_type != 'route_supervisor') {
                    $org_code = $this->getUserCode($login_type, $codes['mcc_plant_code'], 'MCC');
                }
                break;
            case 3:
                if ($login_type == 'procurement_staff') {
                    $org_code = $this->getUserCode('procurement_staff', $codes['plant_code'], 'PLANT');
                } else if ($login_type == 'vsp' && $login_type == 'service_engineer' && $login_type == 'route_supervisor') {
                    $org_code = $this->getUserCode($login_type, $codes['dcs_code'], 'DCS');
                } else {
                    $org_code = $this->getUserCode($login_type, $codes['mcc_plant_code'], 'MCC');
                }
                break;
        }
        return $org_code;
    }

    private function getUserCode($login_type, $code, $org_type) {

        return $this->find()->alias('u')
                        ->select(['tbl_user_organization_mapping.organization_code', 'tbl_user_organization_mapping.organization_type', 'u.user_code'])
                        ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
                        ->where(['u.login_type' => $login_type, 'tbl_user_organization_mapping.organization_code' => $code, 'tbl_user_organization_mapping.organization_type' => $org_type])
                        ->orderBy('u.id asc')
                        ->asArray()
                        ->one();
    }

}
