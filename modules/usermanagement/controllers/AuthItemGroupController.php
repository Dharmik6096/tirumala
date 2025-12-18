<?php

namespace app\modules\usermanagement\controllers;

use Yii;

/**
 * AuthItemGroupController implements the CRUD actions for AuthItemGroup model.
 */
class AuthItemGroupController extends \webvimark\modules\UserManagement\controllers\AuthItemGroupController {
    
    public $modelClass = 'app\modules\usermanagement\models\rbacDB\AuthItemGroup';
}
