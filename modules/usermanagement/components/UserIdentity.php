<?php

namespace app\modules\usermanagement\components;

use Yii;

abstract class UserIdentity extends \webvimark\modules\UserManagement\components\UserIdentity {

    public function checkNotSelf() {
        return $this->id != Yii::$app->session->get('UserCode');
    }

}
