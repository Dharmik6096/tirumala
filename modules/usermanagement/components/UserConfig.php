<?php

namespace app\modules\usermanagement\components;

class UserConfig extends \webvimark\modules\UserManagement\components\UserConfig {

    public $identityClass = 'app\modules\usermanagement\models\User';
    public $loginUrl = ['/usermanagement/auth/login'];

}
