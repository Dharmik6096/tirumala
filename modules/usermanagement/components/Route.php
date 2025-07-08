<?php

namespace app\modules\usermanagement\components;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Route extends \app\modules\usermanagement\models\rbacDB\Route {

    public static function getUserRoutes($userId, $withSubRoutes = true) {
        if (Yii::$app->session->get('organizations_type') == NULL) {
            Yii::$app->user->logout();
            return [];
        }
        $permissions = array_keys(Permission::getUserPermissions($userId));
        $free_actions = Route::getFreeAccessRoutes();
        if (!$permissions) {
            return $free_actions;
        }

        $auth_item = Yii::$app->getModule('user-management')->auth_item_table;
        $auth_item_child = Yii::$app->getModule('user-management')->auth_item_child_table;
        $permissionNames = array_map('strval', array_keys($permissions));
        $routes = (new Query)
                ->select(['name'])
                ->from($auth_item)
                ->innerJoin($auth_item_child, '(' . $auth_item_child . '.child = ' . $auth_item . '.name AND ' . $auth_item . '.type = :type)')
                ->params([
                    ':type' => self::TYPE_ROUTE,
                ])
                ->where([
                    $auth_item_child . '.parent' => $permissionNames, $auth_item . '.' . Yii::$app->session->get('organizations_type') => '1'
                ])
                ->column();
        $result = $withSubRoutes ? static::withSubRoutes($routes, ArrayHelper::map(Route::find()->where([Yii::$app->session->get('organizations_type') => '1'])->asArray()->all(), 'name', 'name')) : $routes;
        $result = array_merge($free_actions, $result);
        return $result;
    }

}
