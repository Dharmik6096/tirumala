<?php

namespace app\modules\usermanagement\components;

use app\modules\usermanagement\components\AuthHelper;
use yii\web\UserEvent;

class UserConfig extends \webvimark\modules\UserManagement\components\UserConfig {

    public $identityClass = 'app\modules\usermanagement\models\User';
    public $loginUrl = ['/usermanagement/auth/login'];

    protected function afterLogin($identity, $cookieBased, $duration) {
        // parent::afterLogin($identity, $cookieBased, $duration);
        AuthHelper::updatePermissions($identity);
        $this->trigger(self::EVENT_AFTER_LOGIN, new UserEvent([
            'identity' => $identity,
            'cookieBased' => $cookieBased,
            'duration' => $duration,
        ]));
    }

}
