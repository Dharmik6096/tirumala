<?php

namespace app\modules\usermanagement\components;

use app\modules\usermanagement\components\AuthHelper;

class UserConfig extends \webvimark\modules\UserManagement\components\UserConfig {

    public $identityClass = 'app\modules\usermanagement\models\User';
    public $loginUrl = ['/usermanagement/auth/login'];

    protected function afterLogin($identity, $cookieBased, $duration) {
        parent::afterLogin($identity, $cookieBased, $duration);
        AuthHelper::updatePermissions($identity);
    }

}
