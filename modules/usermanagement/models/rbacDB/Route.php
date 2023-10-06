<?php

namespace app\modules\usermanagement\models\rbacDB;

use app\modules\usermanagement\components\AuthHelper;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use Yii;

class Route extends \webvimark\modules\UserManagement\models\rbacDB\Route {

    use AbstractItemTrait;

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

        $routes = (new Query)
                ->select(['name'])
                ->from($auth_item)
                ->innerJoin($auth_item_child, '(' . $auth_item_child . '.child = ' . $auth_item . '.name AND ' . $auth_item . '.type = :type)')
                ->params([
                    ':type' => self::TYPE_ROUTE,
                ])
                ->where([
                    $auth_item_child . '.parent' => $permissions, $auth_item . '.' . Yii::$app->session->get('organizations_type') => '1'
                ])
                ->column();
        $result = $withSubRoutes ? static::withSubRoutes($routes, ArrayHelper::map(Route::find()->where([Yii::$app->session->get('organizations_type') => '1'])->asArray()->all(), 'name', 'name')) : $routes;
        $result = array_merge($free_actions, $result);
        return $result;
    }

    public static function getFreeAccessRoutes() {
        $FreeActionsDB = Route::find()->select(['name'])->where(['is_free' => 1])->all();
        return ArrayHelper::getColumn($FreeActionsDB, 'name');
    }

    public static function refreshRoutes($deleteUnusedRoutes = true) {
        $allRoutes = AuthHelper::getRoutes();

        $currentRoutes = ArrayHelper::map(Route::find()->asArray()->all(), 'name', 'name');

        $toAdd = array_diff(array_keys($allRoutes), array_keys($currentRoutes));

        foreach ($toAdd as $addItem) {
            Route::create($addItem);
        }

        $toRemove = false;
        if ($deleteUnusedRoutes) {
            $toRemove = array_diff(array_keys($currentRoutes), array_keys($allRoutes));

            if ($toRemove) {
                Route::deleteAll(['in', 'name', $toRemove]);
            }
        }


        if ($toAdd || $toRemove) {
            if (Yii::$app->cache) {
                Yii::$app->cache->delete('__commonRoutes');
            }
        }
    }

}
