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

    public function getUser($department, $code) {
        return $this->find()->alias('u')
                        ->select(['tbl_user_organization_mapping.*'])
                        ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
                        ->where(['u.department' => $department, 'tbl_user_organization_mapping.organization_code' => $code])
                        ->orderBy('u.id asc')
                        ->asArray()
                        ->one();
    }

    public function getContactCode($user_code) {
        return $this->find()->where(['user_code' => $user_code])->one();
    }

}
