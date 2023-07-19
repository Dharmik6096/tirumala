<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

class User extends \webvimark\modules\UserManagement\models\User {

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

}
