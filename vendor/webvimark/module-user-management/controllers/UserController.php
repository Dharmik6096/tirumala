<?php

namespace webvimark\modules\UserManagement\controllers;

use webvimark\components\AdminDefaultController;
use Yii;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\search\UserSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use app\modules\setting\models\TblUserProfileMapping;
use app\models\TblUserOrganizationMapping;
use yii\helpers\FileHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminDefaultController {

    /**
     * @var User
     */
    public $modelClass = 'webvimark\modules\UserManagement\models\User';

    /**
     * @var UserSearch
     */
    public $modelSearchClass = 'webvimark\modules\UserManagement\models\search\UserSearch';

    /**
     * @return mixed|string|\yii\web\Response
     */
    public function actionCreate() {
        $this->model = new User(['scenario' => 'newUser']);
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $identity = new \app\models\IdentityMaster();
            $data = $identity->getIdentity();
            $roleName = $_POST['User']['role'];
            Yii::$app->operation->defaults($this->model, INSERT);
            $this->model->id = $this->model->getCode();
            $this->model->user_code = $this->model->getCode();
//                        $identity = new IdentityMaster();
//                        $idenRecord = $identity->getIdentity();
            $this->model->user_identity = $data['organization_code'];

            $this->model->username = $this->model->user_identity . '#' . $this->model->username;
            $this->model->portal_type = 'portal';
            $this->model->is_active = 1;
            //Assign Role
            // $mapList = [];
            $transaction = $this->generalModel->saveTransaction([$this->model], ['User', 'create']);
            //var_dump($transaction);exit;
            if ($transaction !== FALSE) {
                if ($roleName) {
                    foreach ($roleName as $role) {
                        User::assignRole($this->model->id, $role);
                    }
                }
//                            echo $_POST['User']['username'];
//                            exit;
                $this->model->username = $_POST['User']['username'];
                return $this->{$transaction}();
            }
        }

        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        } else {
            $modelClass = $this->modelClass;
            $dataProvider = new ActiveDataProvider([
                'query' => $modelClass::find()->where(),
            ]);
        }
        return $this->renderIsAjax('create', ['model' => $this->model, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    public function customRedirect() {
        return $this->redirect(['organization-map', 'id' => $this->model->id]);
    }

    public function customRender() {
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        } else {
            $modelClass = $this->modelClass;
            $dataProvider = new ActiveDataProvider([
                'query' => $modelClass::find()->where(),
            ]);
        }
        return $this->render('create', ['model' => $this->model, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    /**
     * @param int $id User ID
     *
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionChangePassword($id) {
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderIsAjax('changePassword', compact('model'));
    }

    public function actionGetOrgazinations() {

        $out = '';
        if (!isset($_POST['depdrop_parents'])) {
            echo Json::encode(['output' => '', 'selected' => '']);
            return;
        }
        $data = [];
        $type = explode('-', $_POST['depdrop_parents'][0]);
        $data = User::getSelectedOrganization($type[0]);
        foreach ($data['data'] as $row) {
            $out[] = array('id' => $row->{$data['field'][0]} . '_' . $row->{$data['field'][1]}, 'name' => $row->{$data['field'][1]});
        }
        echo Json::encode(['output' => $out]);
        return;
    }

    public function actionGetRoles() {

        $out = '';
        if (!isset($_POST['depdrop_parents'])) {
            echo Json::encode(['output' => '', 'selected' => '']);
            return;
        }
        $roles = \webvimark\modules\UserManagement\models\rbacDB\Role::getAvailableRoles(true, true);


        foreach ($roles as $key => $row) {
            $out[] = array('id' => $key,
                'name' => str_replace('_', ' ', $row));
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
    }

    public function actionOrganizationMap($id) {
        $user = User::findOne($id);
        $model = new TblUserOrganizationMapping();
        $model->scenario = 'organizationMapping';
        if (Yii::$app->session->get('organizations_type') == 'UNION') {
            $model->scenario = 'organizationMappingUnion';
        }
        $organization = $model->getOrganizationsArray($id, $user->user_type_id);
        $federations = $organization['federation'];
        $unions = $organization['union'];
        $plant = $organization['plant'];
        $mcc = $organization['mcc'];
        $bmc = $organization['bmc'];
        $dcs = $organization['dcs'];
        $model->dcs = $dcs['selectedArray'];
        $model->bmc = $bmc['selectedArray'];
        $model->mcc = $mcc['selectedArray'];
        $model->plant = $plant['selectedArray'];
        $model->union = $unions['selectedArray'];
        $model->federation = ['01'];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            TblUserOrganizationMapping::deleteAll(['user_id' => $id]);
            switch ($_POST['user_type']) {
                case 7 : $this->addUserOrganizationMapping($model->dcs, 'DCS', $id, $user->is_active);
                    break;
                case 6 : $this->addUserOrganizationMapping($model->bmc, 'BMC', $id, $user->is_active);
                    break;
                case 5 : $this->addUserOrganizationMapping($model->mcc, 'MCC', $id, $user->is_active);
                    break;
                case 4 : $this->addUserOrganizationMapping($model->plant, 'PLANT', $id, $user->is_active);
                    break;
                case 3 : $this->addUserOrganizationMapping($model->union, 'UNION', $id, $user->is_active);
                    break;
                case 2 : $this->addUserOrganizationMapping($model->federation, 'FEDERATION', $id, $user->is_active);
                    break;
            }
            $user->user_type_id = $_POST['user_type'];
            $user->save(false);
            Yii::$app->display->message(true, 'user', 'edit');
            return $this->redirect(['index']);
        }
        return $this->renderIsAjax('organization_map', ['model' => $model, 'user' => $user, 'federations' => $federations, 'unions' => $unions, 'plant' => $plant, 'mcc' => $mcc, 'bmc' => $bmc, 'dcs' => $dcs]);
    }

    private function addUserOrganizationMapping($data, $type, $userId, $active) {
        $userModel = new User();
        $users = $userModel->findByRole(['EIPL']);
        $users = \yii\helpers\ArrayHelper::getColumn($users, 'id');
        $is_eipl = in_array($userId, $users);
        foreach ($data as $value) {
            $modelNew = new TblUserOrganizationMapping();
            // $modelNew->id = $modelNew->getCode();
            $modelNew->organization_code = $value;
            $modelNew->organization_type = $type;
            $modelNew->user_id = $userId;
            $modelNew->is_active = $active;
            Yii::$app->operation->defaults($modelNew, INSERT);
            //$roles=Yii::$app->authManager->getRolesByUser('00000000000035');

            if ($modelNew->save()) {
                if ($is_eipl && $modelNew->organization_type == 'DCS') {
                    $path = Yii::$app->params['eiplDirPath'] . $modelNew->organization_code . '/';
                    if (!file_exists($path) || !is_dir($path)) {
                        FileHelper::createDirectory($path);
                    }
                }
            }
        }
    }

    /* public function actionOrganizationMap($id){

      $model = User::findOne($id);
      $organizationModel = new \app\models\TblUserOrganizationMapping();
      $data = $organizationModel->getOrganization($id,$model->user_type_id);
      $model->organizations=$data['selected'];

      $userType = \app\models\TblUserTypes::findOne($model->user_type_id);
      $model->user_type_id = $model->user_type_id.'-'.$userType->user_type;

      if ( $model->load(Yii::$app->request->post())  )
      {
      if(!empty($model->organizations)){
      \app\models\TblUserOrganizationMapping::deleteAll(['user_id'=>$id]);
      foreach ($model->organizations as $row){

      $modelNew = new TblUserOrganizationMapping();
      $modelNew->organization_code = $row;
      $modelNew->organization_type = $userType->user_type;
      $modelNew->user_id = $id;
      $modelNew->is_active=$_POST['User']['is_active'];
      Yii::$app->operation->defaults($modelNew, INSERT);
      $modelNew->save();
      }
      Yii::$app->display->message(true, 'user','edit');
      return $this->redirect(['index']);
      }
      }

      return $this->renderIsAjax('organization_map',['model'=>$model,'values'=>$data['value'],'selected'=>$data['selected']]);
      } */
}
