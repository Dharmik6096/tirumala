<?php

namespace app\modules\usermanagement\components;

use Yii;
use app\modules\usermanagement\components\Route;
use app\modules\usermanagement\models\rbacDB\Role;
use app\modules\usermanagement\components\Permission;
use app\modules\usermanagement\components\DbManager;

class AuthHelper extends \webvimark\modules\UserManagement\components\AuthHelper {

    /**
     * Get child routes, permissions or roles
     *
     * @param string $itemName
     * @param integer $childType
     *
     * @return array
     */
    public static function getChildrenByType($itemName, $childType, $organizations_type = '') {
        $dbManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();
        $children = $dbManager->getChildren($itemName);
        $result = [];
        foreach ($children as $id => $item) {
            $origin = Route::find()->where([$organizations_type => '1', 'name' => $item->name])->one();
            if ($item->type == $childType && !empty($origin)) {
                $result[$id] = $item;
            }
        }
        return $result;
    }

    public static function updatePermissions($identity) {
        $session = Yii::$app->session;

        // Clear data first in case we want to refresh permissions
        $session->remove(self::SESSION_PREFIX_ROLES);
        $session->remove(self::SESSION_PREFIX_PERMISSIONS);
        $session->remove(self::SESSION_PREFIX_ROUTES);

        // Set permissions last mod time
        $session->set(self::SESSION_PREFIX_LAST_UPDATE, filemtime(self::getPermissionsLastModFile()));

        // Save roles, permissions and routes in session
        $session->set(self::SESSION_PREFIX_ROLES, array_keys(Role::getUserRoles($identity->id)));
        $session->set(self::SESSION_PREFIX_PERMISSIONS, array_keys(Permission::getUserPermissions($identity->id)));
        $session->set(self::SESSION_PREFIX_ROUTES, Route::getUserRoutes($identity->id));
    }

}
