<?php

namespace webvimark\modules\UserManagement\controllers;

use webvimark\modules\UserManagement\components\AuthHelper;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\search\RoleSearch;
use webvimark\components\AdminDefaultController;
use webvimark\modules\UserManagement\UserManagementModule;
use Yii;
use yii\rbac\DbManager;
use yii\helpers\Html;

class RoleController extends AdminDefaultController {

    /**
     * @var Role
     */
    public $modelClass = 'webvimark\modules\UserManagement\models\rbacDB\Role';

    /**
     * @var RoleSearch
     */
    public $modelSearchClass = 'webvimark\modules\UserManagement\models\rbacDB\search\RoleSearch';

    /**
     * @param string $id
     *
     * @return string
     */
    public function actionView($id) {
        $role = $this->findModel($id);

        $authManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();

        $allRoles = Role::find()
                ->asArray()
                ->andWhere('name != :current_name', [':current_name' => $id])
                ->all();

        $permissions = Permission::find()
                ->andWhere(Yii::$app->getModule('user-management')->auth_item_table . '.name != :commonPermissionName', [':commonPermissionName' => Yii::$app->getModule('user-management')->commonPermissionName])
                ->joinWith('group')
                ->all();

        $permissionsByGroup = [];
        foreach ($permissions as $permission) {
            $permissionsByGroup[@$permission->group->name][] = $permission;
        }

        $childRoles = $authManager->getChildren($role->name);

        $currentRoutesAndPermissions = AuthHelper::separateRoutesAndPermissions($authManager->getPermissionsByRole($role->name));

        $currentPermissions = $currentRoutesAndPermissions->permissions;

        return $this->renderIsAjax('view', compact('role', 'allRoles', 'childRoles', 'currentPermissions', 'permissionsByGroup'));
    }

    /**
     * Add or remove child roles and return back to view
     *
     * @param string $id
     *
     * @return \yii\web\Response
     */
    public function actionSetChildRoles($id) {
        $role = $this->findModel($id);

        $newChildRoles = Yii::$app->request->post('child_roles', []);

        $dbManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();

        $children = $dbManager->getChildren($role->name);

        $oldChildRoles = [];

        foreach ($children as $child) {
            if ($child->type == Role::TYPE_ROLE) {
                $oldChildRoles[$child->name] = $child->name;
            }
        }

        $toRemove = array_diff($oldChildRoles, $newChildRoles);
        $toAdd = array_diff($newChildRoles, $oldChildRoles);

        Role::addChildren($role->name, $toAdd);
        Role::removeChildren($role->name, $toRemove);

        Yii::$app->session->setFlash('success', UserManagementModule::t('back', 'Saved'));

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Add or remove child permissions (including routes) and return back to view
     *
     * @param string $id
     *
     * @return \yii\web\Response
     */
    public function actionSetChildPermissions($id) {
        $role = $this->findModel($id);

        $newChildPermissions = Yii::$app->request->post('child_permissions', []);

        $dbManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();

        $oldChildPermissions = array_keys($dbManager->getPermissionsByRole($role->name));

        $toRemove = array_diff($oldChildPermissions, $newChildPermissions);
        $toAdd = array_diff($newChildPermissions, $oldChildPermissions);

        Role::addChildren($role->name, $toAdd);
        Role::removeChildren($role->name, $toRemove);

        Yii::$app->session->setFlash('success', UserManagementModule::t('back', 'Saved'));

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Role;
//		$model->scenario = 'webInput';

        if ($model->load(Yii::$app->request->post())) {
            $model->NATIONAL = '0';
            if ($model->organizations_type == '1') {
                $model->FEDERATION = '0';
                $model->UNION = '1';
            } else {
                $model->FEDERATION = '1';
                $model->UNION = '0';
            }
            $model->description = $model->name;
            $model->save();
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => Html::encode("Record successfully created."),
                'title' => Html::encode('Success'),
            ]);
            return $this->redirect(['index']);
//			return $this->redirect(['view', 'id'=>$model->name]);
        }

        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        } else {
            $modelClass = $this->modelClass;
            $dataProvider = new ActiveDataProvider([
                'query' => $modelClass::find(),
            ]);
        }

        return $this->renderIsAjax('create', compact('model', 'dataProvider', 'searchModel'));
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id, $flag = 'portal') {

        $model = $this->findModel($id);
        ($model->UNION == 1) ? $model->organizations_type = 1 : $model->organizations_type = 0;

        if ($model->load(Yii::$app->request->post())) {
            $model->NATIONAL = '0';
            if ($model->organizations_type == '1') {
                $model->FEDERATION = '0';
                $model->UNION = '1';
            } else {
                $model->FEDERATION = '1';
                $model->UNION = '0';
            }
            $model->description = $model->name;
            $model->save();

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => Html::encode('Record successfully updated.'),
                'title' => Html::encode('Success'),
            ]);
            return $this->redirect(['index']);
//			return $this->redirect(['view', 'id'=>$model->name]);
        }

        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;
        if ($searchModel) {
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        } else {
            $modelClass = $this->modelClass;
            $dataProvider = new ActiveDataProvider([
                'query' => $modelClass::find(),
            ]);
        }

        return $this->renderIsAjax('update', compact('model', 'dataProvider', 'searchModel'));
    }

}
