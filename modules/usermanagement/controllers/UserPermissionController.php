<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\models\rbacDB\Role;
use app\modules\usermanagement\models\User;

class UserPermissionController extends \webvimark\modules\UserManagement\controllers\UserPermissionController {

    public function actionSetRoles($id) {
        if (!Yii::$app->user->isSuperadmin AND Yii::$app->user->id == $id) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You can not change own permissions'));
            return $this->redirect(['set', 'id' => $id]);
        }

        $oldAssignments = array_keys(Role::getUserRoles($id));

        // To be sure that user didn't attempt to assign himself some unavailable roles
        $newAssignments = array_intersect(Role::getAvailableRoles(true, true), (array) Yii::$app->request->post('roles', []));

        $toAssign = array_diff($newAssignments, $oldAssignments);
        $toRevoke = array_diff($oldAssignments, $newAssignments);
        foreach ($toRevoke as $role) {
            User::revokeRole($id, $role);
        }

        foreach ($toAssign as $role) {
            User::assignRole($id, $role);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved'));

        return $this->redirect(['set', 'id' => $id]);
    }

}
