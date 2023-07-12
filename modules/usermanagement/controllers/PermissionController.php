<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use yii\helpers\Html;
use app\modules\usermanagement\components\AuthHelper;
use app\modules\usermanagement\models\rbacDB\AbstractItem;
use app\modules\usermanagement\models\rbacDB\Permission;
use app\modules\usermanagement\models\rbacDB\Route;

class PermissionController extends \webvimark\modules\UserManagement\controllers\PermissionController {
    
    use \app\controllers\ChildControllerTrait;

    public $modelClass = 'app\modules\usermanagement\models\rbacDB\Permission';
    public $modelSearchClass = 'app\modules\usermanagement\models\rbacDB\search\PermissionSearch';

    public function actionView($id) {
        $item = $this->findModel($id);
        ($item->UNION == '1') ? $item->organizations_type = 'UNION' : $item->organizations_type = 'FEDERATION';
        $routes = Route::find()->where([$item->organizations_type => '1', 'type' => '3', 'is_free' => ['0', NULL]])->asArray()->all();
        $permissions = Permission::find()
                ->andWhere(['not in', Yii::$app->getModule('user-management')->auth_item_table . '.name', [Yii::$app->getModule('user-management')->commonPermissionName, $id]])
                ->joinWith('group')
                ->all();
        $permissionsByGroup = [];
        foreach ($permissions as $permission) {
            $permissionsByGroup[@$permission->group->name][] = $permission;
        }
        $childRoutes = AuthHelper::getChildrenByType($item->name, AbstractItem::TYPE_ROUTE, $item->organizations_type);
        $childPermissions = AuthHelper::getChildrenByType($item->name, AbstractItem::TYPE_PERMISSION, $item->organizations_type);
        return $this->renderIsAjax('view', compact('item', 'childPermissions', 'routes', 'permissionsByGroup', 'childRoutes'));
    }

    public function actionSetChildRoutes($id) {
        $item = $this->findModel($id);
        ($item->UNION == '1') ? $item->organizations_type = 'UNION' : $item->organizations_type = 'FEDERATION';
        $newRoutes = Yii::$app->request->post('child_routes', []);
        $oldRoutes = array_keys(AuthHelper::getChildrenByType($item->name, AbstractItem::TYPE_ROUTE, $item->organizations_type));
        $toAdd = array_diff($newRoutes, $oldRoutes);
        $toRemove = array_diff($oldRoutes, $newRoutes);
        Permission::addChildren($id, $toAdd);
        Permission::removeChildren($id, $toRemove);
        if (( $toAdd OR $toRemove ) AND ( $id == Yii::$app->getModule('user-management')->commonPermissionName )) {
            Yii::$app->cache->delete('__commonRoutes');
        }
        AuthHelper::invalidatePermissions();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved'));
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionCreate() {
        $model = new Permission();
        $model->scenario = 'webInput';

        if ($model->load(Yii::$app->request->post())) {
            $model->NATIONAL = '0';
            if ($model->organizations_type == '1') {
                $model->FEDERATION = '0';
                $model->UNION = '1';
            } else {
                $model->FEDERATION = '1';
                $model->UNION = '0';
            }
            $model->save();
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => Html::encode("Record successfully created."),
                'title' => Html::encode('Success'),
            ]);
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'id'=>$model->name]);
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
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = 'webInput';
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
            $model->save();

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => Html::encode('Record successfully updated.'),
                'title' => Html::encode('Success'),
            ]);
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'id'=>$model->name]);
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

    public function actionSetPermission($id) {
        $item = $this->findModel($id);
        if ($item->NATIONAL == '1') {
            $origin = 'NATIONAL';
        } else if ($item->FEDERATION == '1') {
            $origin = 'FEDERATION';
        } else {
            $origin = 'UNION';
        }

        $main_menu = $this->getChildren(4, null, [], '', $origin);
        $childRoutes = $this->getChildrenByType($item->name, AbstractItem::TYPE_ROUTE, $origin);
        return $this->renderIsAjax('set_permission', compact('item', 'main_menu', 'childRoutes'));
    }

    protected function getChildren($type, $name, $origin, $sub_menu = [], $data = '') {
        if ($type == 7) {
            $query = Route::find()->select(['name', 'description', 'type', 'data'])->where([$origin => '1', 'type' => 3, 'is_free' => 0])->andWhere(['not like', 'name', '*']);
            if (!empty($data)) {
                $data = explode(',', $data);
                $query = $query->andWhere(['name' => $data]);
            } else {
                $query = $query->andWhere(['like', 'name', $name]);
            }
            $query = $query->all();
        } else if ($type == 4) {
            $query = Route::find()->select(['name', 'description', 'type', 'data'])->where([$origin => '1', 'type' => $type, 'parent_action' => $name])->all();
        } else {
            $query = Route::find()->select(['name', 'description', 'type', 'data'])->where([$origin => '1', 'parent_action' => $name])->all();
        }
        $type++;
        if (!empty($query)) {
            foreach ($query as $result) {
                $name = $result->name;
                $sub_menu[$result->name] = ['name' => $result->name, 'description' => $result->description, 'children' => $type > 7 ? NULL : $this->getChildren($type, $result->name, $type > 4 ? [] : $sub_menu, $result->data, $origin)];
            }
        } else if ($type <= 7) {
            $sub_menu[$name] = $this->getChildren($type, $name, $type > 4 ? [] : $sub_menu, '', $origin);
        }
        return $sub_menu;
    }

    public static function getChildrenByType($itemName, $childType, $origin) {
        $dbManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();
        $children = $dbManager->getChildren($itemName);
        $result = [];
        foreach ($children as $id => $item) {
            $data = Route::find()->where([$origin => '1', 'name' => $item->name])->one();
            if ($item->type >= $childType && !empty($data)) {
                $result[$id] = $item;
            }
        }
        return $result;
    }

    public function actionSetFreeAction() {
        if (Yii::$app->request->post()) {
            $newRoutes = Yii::$app->request->post('child_routes', []);
            $oldRoutes = Route::find()->select(['name'])->where([Yii::$app->session->get('organizations_type') => '1', 'type' => 3, 'is_free' => 1])->all();
            $oldRoutes = \yii\helpers\ArrayHelper::map($oldRoutes, 'name', 'name');
            $toAdd = array_diff($newRoutes, $oldRoutes);
            $toRemove = array_diff($oldRoutes, $newRoutes);
            Yii::$app->db->createCommand()
                    ->update('auth_item', ['is_free' => 1], ['name' => $toAdd])
                    ->execute();
            Yii::$app->db->createCommand()
                    ->update('auth_item', ['is_free' => 0], ['name' => $toRemove])
                    ->execute();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Saved'));
        }
        $routes = Route::find()->where([Yii::$app->session->get('organizations_type') => '1', 'type' => 3])->asArray()->all();
        $childRoutes = Route::find()->select(['name', 'description', 'type'])->where([Yii::$app->session->get('organizations_type') => '1', 'type' => 3, 'is_free' => 1])->all();

        return $this->renderIsAjax('view', compact('routes', 'childRoutes'));
    }

    public function actionSetOriginateAction() {
        if (Yii::$app->request->post()) {
            foreach (Yii::$app->request->post('Route') as $action) {
                $model = Route::find()->where(['name' => $action['name']])->one();
                if ($model->NATIONAL != $action['NATIONAL'] || $model->FEDERATION != $action['FEDERATION'] || $model->UNION != $action['UNION'] || $model->description != $action['description'] || $model->is_free != $action['is_free']) {
                    $model->NATIONAL = $action['NATIONAL'];
                    $model->FEDERATION = $action['FEDERATION'];
                    $model->UNION = $action['UNION'];
                    $model->description = $action['description'];
                    $model->is_free = $action['is_free'];
                    $model->save(FALSE);
                }
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('app', "Originate Location successfully updated."));
        }
        $main_menu = $this->getChildrenOriginate(4, null);
        return $this->renderIsAjax('set_originate', compact('main_menu'));
    }

    protected function getChildrenOriginate($type, $name, $sub_menu = [], $data = '') {
        if ($type == 7) {
//            die('7');
            $query = Route::find()->select(['name', 'description', 'type', 'data', 'NATIONAL', 'FEDERATION', 'UNION', 'is_free'])->where(['type' => 3, 'is_free' => 0])->andWhere(['not like', 'name', '*']);
            if (!empty($data)) {
                $data = explode(',', $data);
                $query = $query->andWhere(['name' => $data]);
            } else {
                $query = $query->andWhere(['like', 'name', $name]);
            }
            $query = $query->all();
        } else if ($type == 4) {
//            die('4');
            $query = Route::find()->select(['name', 'description', 'type', 'data', 'NATIONAL', 'FEDERATION', 'UNION', 'is_free'])->where(['type' => $type, 'parent_action' => $name])->all();
        } else {
            $query = Route::find()->select(['name', 'description', 'type', 'data', 'NATIONAL', 'FEDERATION', 'UNION', 'is_free'])->where(['parent_action' => $name])->all();
        }
        $type++;
        if (!empty($query)) {
//            die('query');
            foreach ($query as $result) {
                $name = $result->name;
                $sub_menu[$result->name] = ['result' => $result, 'children' => $type > 7 ? NULL : $this->getChildrenOriginate($type, $result->name, $type > 4 ? [] : $sub_menu, $result->data)];
            }
        } else if ($type <= 7) {
//            die('<=7');
            $sub_menu[$name] = $this->getChildrenOriginate($type, $name, $type > 4 ? [] : $sub_menu);
        }
//        die('done');
        return $sub_menu;
    }

}
