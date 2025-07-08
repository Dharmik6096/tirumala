<?php

namespace app\modules\usermanagement\components;

use Yii;
use app\modules\usermanagement\components\DbManager;

class Permission extends \app\modules\usermanagement\models\rbacDB\Permission {
    /**
	 * @param int $userId
	 *
	 * @return array|\yii\rbac\Permission[]
	 */
	public static function getUserPermissions($userId)
	{
		$dbManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();
		
		return $dbManager->getPermissionsByUser($userId);
	}
}
