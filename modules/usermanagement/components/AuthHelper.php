<?php

namespace app\modules\usermanagement\components;

use Yii;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use yii\rbac\DbManager;

class AuthHelper extends \webvimark\modules\UserManagement\components\AuthHelper {

    /**
     * Get child routes, permissions or roles
     *
     * @param string $itemName
     * @param integer $childType
     *
     * @return array
     */
    public static function getChildrenByType($itemName, $childType, $organizations_type) {
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

}
