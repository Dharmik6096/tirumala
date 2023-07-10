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

//   $details = Self::find()->alias('u')->select(['tbl_user_organization_mapping.user_id'])
//                ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
//                ->where(['tbl_user_organization_mapping.organization_code' => '', 'tbl_user_organization_mapping.organization_type' =>'' ])
//                ->all();
}
