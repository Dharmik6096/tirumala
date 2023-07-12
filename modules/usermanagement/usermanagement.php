<?php

namespace app\modules\usermanagement;

use app\models\GeneralModel;

/**
 * usermanagement module definition class
 */
class usermanagement extends \webvimark\modules\UserManagement\UserManagementModule {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\usermanagement\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

}
