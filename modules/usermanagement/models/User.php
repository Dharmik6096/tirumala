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

}
    