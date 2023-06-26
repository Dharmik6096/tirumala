<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\components\AuthHelper;
use app\modules\usermanagement\models\rbacDB\Role;
use yii\helpers\Html;

class RoleController extends \webvimark\modules\UserManagement\controllers\RoleController {

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

}
