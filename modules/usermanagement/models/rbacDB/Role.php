<?php

namespace app\modules\usermanagement\models\rbacDB;

use app\modules\usermanagement\components\AuthHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;

class Role extends \webvimark\modules\UserManagement\models\rbacDB\Role {

    use AbstractItemTrait;

    public $portal_type;

    public function checkNotVendor() {
        $authManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();
        $currentRoutesAndPermissions = AuthHelper::separateRoutesAndPermissions($authManager->getPermissionsByRole($this->name));
        $currentPermissions = $currentRoutesAndPermissions->permissions;
        if (in_array('Vendor Permission', ArrayHelper::getColumn($currentPermissions, 'name')) || ($this->entry_type == 1 && Yii::$app->session->get('organizations_type') == 'UNION')) {
            return false;
        }
        return true;
    }

}
