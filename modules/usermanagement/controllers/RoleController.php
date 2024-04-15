<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\components\AuthHelper;
use app\modules\usermanagement\models\rbacDB\Role;
use yii\helpers\Html;
use yii\rbac\DbManager;

class RoleController extends \webvimark\modules\UserManagement\controllers\RoleController {

    use \app\controllers\ChildControllerTrait;

    public $modelClass = 'app\modules\usermanagement\models\rbacDB\Role';
    public $modelSearchClass = 'app\modules\usermanagement\models\rbacDB\search\RoleSearch';

    public function actionCreate() {
        $model = new Role;
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

    public function actionSetChildPermissions($id) {
        $role = $this->findModel($id);

        $newChildPermissions = Yii::$app->request->post('child_permissions', []);

        $dbManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();

        $oldChildPermissions = array_keys($dbManager->getPermissionsByRole($role->name));

        $toRemove = array_diff($oldChildPermissions, $newChildPermissions);
        $toAdd = array_diff($newChildPermissions, $oldChildPermissions);

        Role::addChildren($role->name, $toAdd);
        Role::removeChildren($role->name, $toRemove);

        Yii::$app->session->setFlash('success', yii::t('app', 'Saved'));

        return $this->redirect(['view', 'id' => $id]);
    }

}
