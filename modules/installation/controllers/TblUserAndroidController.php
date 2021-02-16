<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\TblUserAndroid;
use app\modules\installation\models\TblUserAndroidSearch;
use app\modules\installation\models\TblUserRoleMapping;
use app\modules\installation\models\TblRole;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblUserAndroidController implements the CRUD actions for TblUserAndroid model.
 */
class TblUserAndroidController extends \app\controllers\ChildController {

    /**
     * Lists all TblUserAndroid models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblUserAndroidSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblUserAndroid model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblUserAndroid model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblUserAndroid();
        $this->viewFile = "create";
        $saveModel = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->user_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            if (!empty($this->model->role_code)) {
                $model = new TblUserRoleMapping();
                $model->role_code = $this->model->role_code;
                $model->user_code = $this->model->user_code;
                $saveModel[] = $model;
            }
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['User', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblUserAndroid model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = "update";
        $saveModel = [];
        $deleteModel = [];
        $model = new TblUserRoleMapping();
        $this->model->role_code = $model->getRole($id);
        $this->model->repeat_password = $this->model->password;
        if ($this->model->load(Yii::$app->request->post())) {
            $where['user_code'] = $id;
            $roleMappingModel = TblUserRoleMapping::find()->where($where)->one();
            $deleteModel[] = $roleMappingModel;
            if (!empty($this->model->role_code)) {
                $model->role_code = $this->model->role_code;
                $model->user_code = $id;
                $saveModel[] = $model;
            }
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['User', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblUserAndroid model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblUserAndroid model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblUserAndroid the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblUserAndroid::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMapRoles($id) {
        $this->model = new TblRole();
        $this->viewFile = "_map_user_role";
        $userModel = $this->findModel($id);
        $menuArray = [];
        $menuArray = $this->model->getRoleDetail();
        $mappingModel = new TblUserRoleMapping();
        $selectedArray = [];
        $selectedArray = $mappingModel->getExistMapingMenu($id);
        if (Yii::$app->request->post()) {

            $postArray = [];
            $postArray = Yii::$app->request->post()['TblRole']['role_code'];
            $master = [];
            $auto_inc = 1;
            $newAssignments = [];
            if (!empty($postArray)) {
                $newAssignments = $postArray;
            }
            $oldAssignments = [];
            if (!empty($selectedArray)) {
                $oldAssignments = array_keys($selectedArray);
            }
            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_values(array_diff($oldAssignments, $newAssignments));
            $delete = [];
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_key) {
                    $model = new TblUserRoleMapping();
                    $model->role_code = (string) $revoke_key;
                    $model->user_code = $id;
                    $record = $model->getExistMappedmenus($id);
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }

            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_key) {
                    $model = new TblUserRoleMapping();
                    $model->role_code = $Assign_key;
                    $model->user_code = $id;
                    $master[] = $model;
                    $auto_inc++;
                }
            }
            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['User Role Mapping', 'edit']);

            $selectedArray = !empty($postArray) ? $postArray : [];
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
            return $this->render('_map_user_role', [
                        'model' => $this->model,
            ]);
        }
        return $this->render('_map_user_role', [
                    'model' => $this->model,
                    'mappingModel' => $mappingModel,
                    'userModel' => $userModel,
                    'defaultValue' => $this->model->role_code,
                    'selectedArray' => $selectedArray,
                    'menuArray' => $menuArray,
                    'id' => $id
        ]);
    }

}
