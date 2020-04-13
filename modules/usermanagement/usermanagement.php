<?php

namespace app\modules\usermanagement;

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

        // custom initialization code goes here
    }

}
