<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\models\UserHistory;
use app\modules\details\models\TblContactDetails;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\webservice\eipl\models\TblEiplAppLoginTemp;
use app\modules\webservice\eipl\models\TblAppOrganizationMapping;
use app\models\TblUserOrganizationMapping;
use app\modules\usermanagement\models\User;
use app\modules\usermanagement\models\search\UserSearch;
use app\modules\usermanagement\models\rbacDB\Role;
use yii\helpers\Json;
use yii\helpers\FileHelper;
use yii\widgets\ActiveForm;
use \app\modules\details\models\TblContactDetailsHistory;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertNotification;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends \webvimark\modules\UserManagement\controllers\UserController {

    use \app\controllers\ChildControllerTrait;

    /**
     * @var User
     */
    public $modelClass = 'app\modules\usermanagement\models\User';

    /**
     * @var UserSearch
     */
    public $modelSearchClass = 'app\modules\usermanagement\models\search\UserSearch';

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
            $this->model->user_identity = $data['organization_code'];

            $this->model->username = $this->model->user_identity . '#' . $this->model->username;
            $this->model->portal_type = 'portal';
            $this->model->is_active = 1;
            $this->model->mobile_no = !empty($this->model->mobile_no) ? $this->model->mobile_no : NULL;

            //Assign Role
            $master = [];
            $master[] = $this->model;
            if ($this->model->allow_app_login == 1 && !empty($this->model->mobile_no)) {
                $contactModel = new TblContactDetails();
                $contactModel->mobile_no = $this->model->mobile_no;
                $contactModelData = $contactModel->getContactDetailsRecord();
                if (!empty($contactModelData)) {
                    $contactModel = $contactModelData;
                } else {
                    $contactModel->firstname = $this->model->name;
                    $contactModel->setModel('user', $this->model->id, 0);
                }
                $contactModel->department = $this->model->department;
                $master[] = $contactModel;
            }
            $transaction = $this->generalModel->saveTransaction($master, ['User', 'create']);
            if ($transaction !== FALSE) {
                if ($roleName) {
                    foreach ($roleName as $role) {
                        User::assignRole($this->model->id, $role);
                    }
                }
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

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($this->scenarioOnUpdate) {
            $model->scenario = $this->scenarioOnUpdate;
        }
        $tableName = $model->tableName();
        if ($tableName == "{{%user}}") {
            $oldUsername = $model->username;
            $model->username = Yii::$app->general->getUserName($model->username);
        }
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            //if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            $master = [];
            $delete = [];
            if ($model->validate()) {
                if ($tableName == "{{%user}}") {
                    $UsersModel = $this->findModel($id);
                    $historyModel = new UserHistory();
                    Yii::$app->operation->history($UsersModel, $historyModel, UPDATE);
                    $model->load(Yii::$app->request->post());
//                    $model->scenario = 'userUpdate';
                    $model->username = $oldUsername;
                    $master[] = $model;

                    if ($model->oldAttributes['allow_app_login'] == 1 && $model->allow_app_login == 1) {
                        if ($model->oldAttributes['mobile_no'] != $model->mobile_no || $model->oldAttributes['login_type'] != $model->login_type) {
                            if($model->oldAttributes['mobile_no'] != $model->mobile_no){
                                $contactModel = new TblContactDetails();
                                $contactModel->mobile_no = $model->oldAttributes['mobile_no'];
                                $contactModelData = $contactModel->getContactDetailsRecord();
                                if (!empty($contactModelData)) {
                                    $contactModelData->is_active = 0;
                                    $contactModel = $contactModelData;
                                }
                                $master[] = $contactModel;
    
                                $contNewModel = new TblContactDetails();
                                $contNewModel->mobile_no = $model->mobile_no;
                                $newModelData = $contNewModel->getContactDetailsRecord();
                                if (!empty($newModelData)) {
                                    $contNewModel = $newModelData;
                                } else {
                                    $contNewModel->firstname = $model->name;
                                    $contNewModel->setModel('user', $model->id, 0);
                                }
                                $contNewModel->department = $model->department;
                                $master[] = $contNewModel;
    
                                $appOrgModel = new TblAppOrganizationMapping();
                                $appOrgModel->mobile_no = $model->oldAttributes['mobile_no'];
                                $appOrgModel->detail_code = $contNewModel->detail_code;
                                $orgModelData = $appOrgModel->getAppOrgData();
                                if (!empty($orgModelData)) {
                                    foreach ($orgModelData as $org) {
                                        $org->mobile_no = $model->mobile_no;
                                        $master[] = $org;
                                    }
                                }
                            }
                            $appModel = new TblEiplAppLogin();
                            $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $appModelList = $appModel->getAppLogin($id);
                            if (!empty($appModelList)) {
                                foreach ($appModelList as $appModelData) {
                                    $appModelData->is_active = 0;
                                    $master[] = $appModelData;
                                }
                            }
                            $tempModel = new TblEiplAppLoginTemp();
                            $tempModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $tempModelData = $tempModel->getAppTempLogin();
                            if (!empty($tempModelData)) {
                                foreach ($tempModelData as $temp) {
                                    $delete[] = $temp;
                                }
                            }
                        }
                    }

                    if ($model->oldAttributes['allow_app_login'] == 1 && $model->allow_app_login == 0) {
                        $contactModel = new TblContactDetails();
                        $contactModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $contactModelData = $contactModel->getContactDetailsRecord();
                        if (!empty($contactModelData)) {
                            $contactModelData->is_active = 0;
                            $contactModel = $contactModelData;
                        }
                        $master[] = $contactModel;

                        $appOrgModel = new TblAppOrganizationMapping();
                        $appOrgModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $appOrgModel->detail_code = $contactModel->detail_code;
                        $orgModelData = $appOrgModel->getAppOrgData();
                        if (!empty($orgModelData)) {
                            foreach ($orgModelData as $org) {
                                $delete[] = $org;
                            }
                        }
                        $appModel = new TblEiplAppLogin();
                        $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $appModelList = $appModel->getAppLogin($id);
                        if (!empty($appModelList)) {
                            foreach ($appModelList as $appModelData) {
                                $appModelData->is_active = 0;
                                $master[] = $appModelData;
                            }
                        }
                        $tempModel = new TblEiplAppLoginTemp();
                        $tempModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $tempModelData = $tempModel->getAppTempLogin();
                        if (!empty($tempModelData)) {
                            foreach ($tempModelData as $temp) {
                                $delete[] = $temp;
                            }
                        }
                    }
                    if ($model->oldAttributes['allow_app_login'] == 0 && $model->allow_app_login == 1) {
                        $contactModel = new TblContactDetails();
                        $contactModel->mobile_no = $model->mobile_no;
                        $contactModelData = $contactModel->getContactDetailsRecord();
                        if (!empty($contactModelData)) {
                            $contactModel = $contactModelData;
                        } else {
                            $contactModel->firstname = $model->name;
                            $contactModel->setModel('user', $model->id, 0);
                        }
                        $contactModel->department = $model->department;
                        $master[] = $contactModel;


                        $appOrgModel = new TblAppOrganizationMapping();
                        $appOrgModel->mobile_no = $model->mobile_no;
                        $appOrgModel->detail_code = $contactModel->detail_code;
                        $orgModelData = $appOrgModel->getAppOrgData();
                        if (!empty($orgModelData)) {
                            foreach ($orgModelData as $org) {
                                $delete[] = $org;
                            }
                        }

                        $UserOrgModel = new TblUserOrganizationMapping();
                        $UserOrgexistData = $UserOrgModel::find()->where(['user_id' => $id, 'is_active' => 1])->all();
                        if (!empty($UserOrgexistData)) {
                            foreach ($UserOrgexistData as $org) {
                                $appOrgModel = new TblAppOrganizationMapping();
                                $appOrgModel->is_active = 1;
                                $appOrgModel->mobile_no = $model->mobile_no;
                                $appOrgModel->detail_code = $contactModel->detail_code;
                                $appOrgModel->organization_code = $org->organization_code;
                                $appOrgModel->organization_type = $org->organization_type;
                                $master[] = $appOrgModel;
                            }
                        }
                    }
                    if ($model->oldAttributes['department'] != $model->department && $model->oldAttributes['allow_app_login'] == $model->allow_app_login && $model->oldAttributes['mobile_no'] == $model->mobile_no) {
                        if ($model->allow_app_login == 1) {
                            $contactModel = new TblContactDetails();
                            $contactModel->mobile_no = $model->mobile_no;
                            $contactModelData = $contactModel->getContactDetailsRecord();
                            if (!empty($contactModelData)) {
                                $contactModel = $contactModelData;
                            }
                            $contactModel->department = $model->department;
                            $master[] = $contactModel;

                            $appModel = new TblEiplAppLogin();
                            $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $appModelList = $appModel->getAppLogin($id);
                            if (!empty($appModelList)) {
                                foreach ($appModelList as $appModelData) {
                                    $appModelData->department = $model->department;
                                    $master[] = $appModelData;
                                }
                            }
                            $tempModel = new TblEiplAppLoginTemp();
                            $tempModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $tempModelData = $tempModel->getAppTempLogin();
                            if (!empty($tempModelData)) {
                                foreach ($tempModelData as $temp) {
                                    $temp->department = $model->department;
                                    $delete[] = $temp;
                                }
                            }
                        }
                    }
                }

//                else {
//                    $model->load(Yii::$app->request->post());
//                    $model->save();
//                }
                $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['User', 'edit']);
//                $redirect = $this->getRedirectPage('update', $model);
//                Yii::$app->getSession()->setFlash('success', [
//                    'type' => 'success',
//                    'message' => Html::encode('Record successfully updated.'),
//                    'title' => Html::encode('Success'),
//                ]);
//                if($tableName=="{{%user}}")
//                    return $this->redirect(['organization-map','id'=>$model->id]);
//                else
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
                //return $redirect === false ? '' : $this->redirect($redirect);
            }
        }
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        } else {
            //$modelClass = $this->modelClass;
            $dataProvider = new ActiveDataProvider([
                'query' => $model::find(),
            ]);
        }
        return $this->renderIsAjax('update', compact('model', 'dataProvider', 'searchModel'));
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

    public function actionGetOrgazinations() {
        $out = '';
        if (!isset($_POST['depdrop_parents'])) {
            return Json::encode(['output' => '', 'selected' => '']);
        }
        $data = [];
        $type = explode('-', $_POST['depdrop_parents'][0]);
        $data = User::getSelectedOrganization($type[0]);
        foreach ($data['data'] as $row) {
            $out[] = array('id' => $row->{$data['field'][0]} . '_' . $row->{$data['field'][1]}, 'name' => $row->{$data['field'][1]});
        }
        return Json::encode(['output' => $out]);
    }

    public function actionGetRoles() {
        $out = '';
        if (!isset($_POST['depdrop_parents'])) {
            return Json::encode(['output' => '', 'selected' => '']);
        }
        $roles = Role::getAvailableRoles(true, true);

        foreach ($roles as $key => $row) {
            $out[] = array(
                'id' => $key,
                'name' => str_replace('_', ' ', $row)
            );
        }
        return Json::encode(['output' => $out, 'selected' => '']);
    }

    public function actionOrganizationMap($id) {
        $user = User::findOne($id);
        $model = new TblUserOrganizationMapping();
        $model->scenario = 'organizationMapping';
        if (Yii::$app->session->get('organizations_type') == 'UNION') {
            $model->scenario = 'organizationMappingUnion';
        }
        $modelData = $model->getUserOrgs($id);
        $app_organization = [];
        $app_org_array = false;
        if ($user->allow_app_login == 1 && !empty($user->mobile_no)) {
            $contactModel = new TblContactDetails();
            $contactModel->mobile_no = $user->mobile_no;
            $values = $contactModel->getContactDetailsOrg();
            $app_organization = $model->getOrganizationsArray($id, '', $values);
            $app_org_array = true;
        }
        $stickeyOrgArray = [];
        $stickeyOrgArray['union'] = [];
        $stickeyOrgArray['plant'] = [];
        $stickeyOrgArray['mcc'] = [];
        $stickeyOrgArray['bmc'] = [];
        $stickeyOrgArray['dcs'] = [];
        $stickeyOrgArray['route'] = [];
        if ($app_org_array) {
            $stickeyOrgArray['union'] = !empty($app_organization['union']['selectedArray']) ? $app_organization['union']['selectedArray'] : [];
            $stickeyOrgArray['plant'] = !empty($app_organization['plant']['selectedArray']) ? $app_organization['plant']['selectedArray'] : [];
            $stickeyOrgArray['mcc'] = !empty($app_organization['mcc']['selectedArray']) ? $app_organization['mcc']['selectedArray'] : [];
            $stickeyOrgArray['bmc'] = !empty($app_organization['bmc']['selectedArray']) ? $app_organization['bmc']['selectedArray'] : [];
            $stickeyOrgArray['dcs'] = !empty($app_organization['dcs']['selectedArray']) ? $app_organization['dcs']['selectedArray'] : [];
            $stickeyOrgArray['route'] = !empty($app_organization['route']['selectedArray']) ? $app_organization['route']['selectedArray'] : [];
        }
        if (empty($modelData) && !empty($app_organization)) {
            $organization = $app_organization;
        } else {
            $organization = $model->getOrganizationsArray($id, $user->user_type_id);
        }
        $federations = $organization['federation'];
        $unions = $organization['union'];
        $plant = $organization['plant'];
        $mcc = $organization['mcc'];
        $bmc = $organization['bmc'];
        $dcs = $organization['dcs'];
        $route = $organization['route'];
        $model->route = $route['selectedArray'];
        $model->dcs = $dcs['selectedArray'];
        $model->bmc = $bmc['selectedArray'];
        $model->mcc = $mcc['selectedArray'];
        $model->plant = $plant['selectedArray'];
        $model->union = $unions['selectedArray'];
        $model->federation = ['01'];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $setAppOrgMap = false;
            $contactModel = new TblContactDetails();
            if ($user->allow_app_login == 1 && !empty($user->mobile_no)) {
                $contactModel->mobile_no = $user->mobile_no;
                $contactModelData = $contactModel->getContactDetailsRecord();
                if (!empty($contactModelData)) {
                    $contactModel = $contactModelData;
                } else {
                    $contactModel->firstname = $user->name;
                    $contactModel->setModel('user', $user->id, 0);
                }
                $contactModel->department = $user->department;
                $contactModel->save();
                TblAppOrganizationMapping::deleteAll(['detail_code' => $contactModel->detail_code]);
                $setAppOrgMap = true;
            }

            TblUserOrganizationMapping::deleteAll(['user_id' => $id]);
            switch ($_POST['user_type']) {
                case 7:
                    $this->addUserOrganizationMapping($model->dcs, 'DCS', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->dcs, 'DCS', $contactModel) : '';
                    $type = 'BMC';
                    $org_id = $model->bmc;
                    break;
                case 6:
                    $this->addUserOrganizationMapping($model->bmc, 'BMC', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->bmc, 'BMC', $contactModel) : '';
                    $type = 'BMC';
                    $org_id = $model->bmc;
                    break;
                case 5:
                    $this->addUserOrganizationMapping($model->mcc, 'MCC', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->mcc, 'MCC', $contactModel) : '';
                    $type = 'MCC';
                    $org_id = $model->mcc;
                    break;
                case 4:
                    $this->addUserOrganizationMapping($model->plant, 'PLANT', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->plant, 'PLANT', $contactModel) : '';
                    $type = 'PLANT';
                    $org_id = $model->plant;
                    break;
                case 3:
                    $this->addUserOrganizationMapping($model->union, 'UNION', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->union, 'UNION', $contactModel) : '';
                    break;
                case 2:
                    $this->addUserOrganizationMapping($model->federation, 'FEDERATION', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->federation, 'FEDERATION', $contactModel) : '';
                    break;
            }
            $user->user_type_id = $_POST['user_type'];
            $user->save(false);
            Yii::$app->display->message(true, 'user', 'edit');
            return $this->redirect(['index']);
        }
        return $this->renderIsAjax('organization_map', ['model' => $model, 'user' => $user, 'federations' => $federations, 'unions' => $unions, 'plant' => $plant, 'mcc' => $mcc, 'bmc' => $bmc, 'dcs' => $dcs, 'route' => $route, 'stickeyOrgArray' => $stickeyOrgArray]);
    }

    private function addUserOrganizationMapping($data, $type, $userId, $active) {
        $userModel = new User();
        $users = $userModel->findByRole(['EIPL']);
        $users = \yii\helpers\ArrayHelper::getColumn($users, 'id');
        $is_eipl = in_array($userId, $users);
        foreach ($data as $value) {
            $modelNew = new TblUserOrganizationMapping();
            $modelNew->organization_code = $value;
            $modelNew->organization_type = $type;
            $modelNew->user_id = $userId;
            $modelNew->is_active = $active;
            Yii::$app->operation->defaults($modelNew, INSERT);
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

    public function setAppOrgMapping($org_codes, $org_type, $contactModel) {
        foreach ($org_codes as $org_code) {
            $appOrgMapModel = new TblAppOrganizationMapping();
            $appOrgMapModel->detail_code = $contactModel->detail_code;
            $appOrgMapModel->organization_code = $org_code;
            $appOrgMapModel->organization_type = $org_type;
            $appOrgMapModel->mobile_no = $contactModel->mobile_no;
            $appOrgMapModel->is_active = 1;
            $appOrgMapModel->save();
        }
    }

    public function actionDeactiveUser() {
        $model = $this->findModel(Yii::$app->request->get()['id']);
        if (Yii::$app->request->post()) {

            $historyModel = new UserHistory();
            Yii::$app->operation->history($model, $historyModel, UPDATE);
            $saveModel[] = $historyModel;
            $delete = [];
            $model->load(Yii::$app->request->post());
            $model->scenario = 'DeactiveUser';
            if ($model->validate()) {
                $model->is_active = 0;
                $model->wef_date = !empty(Yii::$app->request->post()['User']['wef_date']) ? Yii::$app->formatter->asDate(Yii::$app->request->post()['User']['wef_date'], DATE_FORMAT) : '';
                $saveModel[] = $model;
                $contactModel = new TblContactDetails();
                $contactModel->module_code = $model->id;
                $contactModel->module_name = 'user';
                $contactModelData = $contactModel->getAllContactData(1);
                if (!empty($contactModelData)) {
                    foreach ($contactModelData as $contactdetail) {
                        $historyModel = new TblContactDetailsHistory();
                        Yii::$app->operation->history($contactdetail, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;
                        $contactdetail->is_active = 0;
                        $saveModel[] = $contactdetail;
                        $appModel = new TblEiplAppLogin();
                        $appModel->mobile_no = $contactdetail->mobile_no;
                        $appModelList = $appModel->getAppLogin($model->id);
                        if (!empty($appModelList)) {
                            foreach ($appModelList as $appModelData) {
                                $appModelData->is_active = 0;
                                $saveModel[] = $appModelData;
                            }
                        }
                        $tempModel = new TblEiplAppLoginTemp();
                        $tempModel->mobile_no = $contactdetail->mobile_no;
                        $tempModelData = $tempModel->getAppTempLogin();
                        if (!empty($tempModelData)) {
                            foreach ($tempModelData as $temp) {
                                $delete[] = $temp;
                            }
                        }
                    }
                }
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $delete, ['User Deactivated', 'create']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
            } else {
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $record = ['status' => 'error', 'msg' => $msg];

                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($model));
            }


            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
        return $this->renderAjax('deactive_user', [
                    'model' => $model,
        ]);
    }

    public function actionActivateUser($id) {
        $this->model = $this->findModel($id);
        $historyModel = new UserHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $saveModel[] = $historyModel;
//        $this->model->scenario = 'deactivate';
        $this->model->is_active = 1;
        $this->model->wef_date = NULL;
        $saveModel[] = $this->model;
        $contactModel = new TblContactDetails();
        $contactModel->module_code = $this->model->id;
        $contactModel->module_name = 'user';
        $contactModelData = $contactModel->getAllContactData(0);
        if (!empty($contactModelData)) {
            foreach ($contactModelData as $contactdetail) {
                $historyModel = new TblContactDetailsHistory();
                Yii::$app->operation->history($contactdetail, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
                $contactdetail->is_active = 1;
                $saveModel[] = $contactdetail;
            }
        }
        $transaction = $this->generalModel->saveTransaction($saveModel, ['User', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'User Activated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'User Not Activated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionPasswordReset($id) {
        $model = $this->findModel($id);

        if ($this->scenarioOnUpdate) {
            $model->scenario = $this->scenarioOnUpdate;
        }
        $tableName = $model->tableName();
        if (Yii::$app->request->post()) {
            //if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            $master = [];
            $delete = [];
            if ($model->validate()) {
                if ($tableName == "{{%user}}") {
                    $historyModel = new UserHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $model->load(Yii::$app->request->post());
                    $master[] = $model;

                    $apiMaster = new TblApiMaster();
                    $apiMaster->receiver_type = 'EMAIL';
                    $apiMasterData = $apiMaster->getAPI();

                    if (!empty($apiMasterData)) {
                        $htmlContent = "";
                        $message = "";
                        $this->setHtmlContent($model->password, $htmlContent, $message, $model->username);

                        $notificationModel = new TblAlertNotification();
                        $notificationModel->receiver_type = 'EMAIL';
                        $notificationModel->message = $htmlContent;
                        $notificationModel->header_info = $message;
                        $notificationModel->send_status = 0;
                        $notificationModel->content_id = $apiMasterData->api_master_id;
                        $notificationModel->refecence_code = $model->id;
                        $notificationModel->module_type = "OTP - Forgot Password";
                        $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                        $notificationModel->send_mail = 1;
                        $notificationModel->receiver_detail = $model->email;
                        $master[] = $notificationModel;
                    }
                }
                $transaction = $this->generalModel->saveDelete4($master, [], $delete, ['Password', 'edit']);

                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $model::find(),
            ]);
        }
        return $this->renderIsAjax('reset_password', compact('model', 'dataProvider', 'searchModel'));
    }
    
    public function actionOrganizationMapNew($id) {
        $user = User::findOne($id);
        $model = new TblUserOrganizationMapping();
        $model->user_id = $id;
        $model->scenario = 'organizationMapping';
        if (Yii::$app->session->get('organizations_type') == 'UNION') {
            $model->scenario = 'organizationMappingUnion';
        }
        $modelData = $model->getUserOrgs($id);
        $app_organization = [];
        $app_org_array = false;
        if ($user->allow_app_login == 1 && !empty($user->mobile_no)) {
            $contactModel = new TblContactDetails();
            $contactModel->mobile_no = $user->mobile_no;
            $values = $contactModel->getContactDetailsOrg();
            $app_organization = $model->getOrganizationsArray($id, '', $values);
            $app_org_array = true;
        }
        $stickeyOrgArray = [];
        $stickeyOrgArray['union'] = [];
        $stickeyOrgArray['plant'] = [];
        $stickeyOrgArray['mcc'] = [];
        $stickeyOrgArray['bmc'] = [];
        $stickeyOrgArray['dcs'] = [];
        $stickeyOrgArray['route'] = [];
        if ($app_org_array) {
            $stickeyOrgArray['union'] = !empty($app_organization['union']['selectedArray']) ? $app_organization['union']['selectedArray'] : [];
            $stickeyOrgArray['plant'] = !empty($app_organization['plant']['selectedArray']) ? $app_organization['plant']['selectedArray'] : [];
            $stickeyOrgArray['mcc'] = !empty($app_organization['mcc']['selectedArray']) ? $app_organization['mcc']['selectedArray'] : [];
            $stickeyOrgArray['bmc'] = !empty($app_organization['bmc']['selectedArray']) ? $app_organization['bmc']['selectedArray'] : [];
            $stickeyOrgArray['dcs'] = !empty($app_organization['dcs']['selectedArray']) ? $app_organization['dcs']['selectedArray'] : [];
            $stickeyOrgArray['route'] = !empty($app_organization['route']['selectedArray']) ? $app_organization['route']['selectedArray'] : [];
        }
        if (empty($modelData) && !empty($app_organization)) {
            $organization = $app_organization;
        } else {
            $organization = $model->getOrganizationsArray($id, $user->user_type_id);
        }
        $federations = $organization['federation'];
        $unions = $organization['union'];
        $plant = $organization['plant'];
        $mcc = $organization['mcc'];
        $bmc = $organization['bmc'];
        $dcs = $organization['dcs'];
        $route = $organization['route'];
        $model->route = $route['selectedArray'];
        $model->dcs = $dcs['selectedArray'];
        $model->bmc = $bmc['selectedArray'];
        $model->mcc = $mcc['selectedArray'];
        $model->plant = $plant['selectedArray'];
        $model->union = $unions['selectedArray'];
        $model->federation = ['01'];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $setAppOrgMap = false;
            $contactModel = new TblContactDetails();
            if ($user->allow_app_login == 1 && !empty($user->mobile_no)) {
                $contactModel->mobile_no = $user->mobile_no;
                $contactModelData = $contactModel->getContactDetailsRecord();
                if (!empty($contactModelData)) {
                    $contactModel = $contactModelData;
                } else {
                    $contactModel->firstname = $user->name;
                    $contactModel->setModel('user', $user->id, 0);
                }
                $contactModel->department = $user->department;
                $contactModel->save();
                TblAppOrganizationMapping::deleteAll(['detail_code' => $contactModel->detail_code]);
                $setAppOrgMap = true;
            }

            TblUserOrganizationMapping::deleteAll(['user_id' => $id]);
            switch ($_POST['user_type']) {
                case 7 : $this->addUserOrganizationMapping($model->dcs, 'DCS', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->dcs, 'DCS', $contactModel) : '';
                    $type = 'BMC';
                    $org_id = $model->bmc;
                    break;
                case 6 : $this->addUserOrganizationMapping($model->bmc, 'BMC', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->bmc, 'BMC', $contactModel) : '';
                    $type = 'BMC';
                    $org_id = $model->bmc;
                    break;
                case 5 : $this->addUserOrganizationMapping($model->mcc, 'MCC', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->mcc, 'MCC', $contactModel) : '';
                    $type = 'MCC';
                    $org_id = $model->mcc;
                    break;
                case 4 : $this->addUserOrganizationMapping($model->plant, 'PLANT', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->plant, 'PLANT', $contactModel) : '';
                    $type = 'PLANT';
                    $org_id = $model->plant;
                    break;
                case 3 : $this->addUserOrganizationMapping($model->union, 'UNION', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->union, 'UNION', $contactModel) : '';
                    break;
                case 2 : $this->addUserOrganizationMapping($model->federation, 'FEDERATION', $id, $user->is_active);
                    $setAppOrgMap ? $this->setAppOrgMapping($model->federation, 'FEDERATION', $contactModel) : '';
                    break;
            }
            $user->user_type_id = $_POST['user_type'];
            $user->scenario = 'orgMapping';
            $user->save(false);
            $sp_param = [];
            $sp_param[] = $id;
            \Yii::$app->general->getSpData('proc_insert_user_latlong_applicability', $sp_param);
            Yii::$app->display->message(true, 'user', 'edit');
            return $this->redirect(['index']);
        }
        return $this->renderIsAjax('organization_map', ['model' => $model, 'user' => $user, 'federations' => $federations, 'unions' => $unions, 'plant' => $plant, 'mcc' => $mcc, 'bmc' => $bmc, 'dcs' => $dcs, 'route' => $route, 'stickeyOrgArray' => $stickeyOrgArray]);
    }

    public function setHtmlContent($OTP, &$htmlContent, &$message, $username) {
        $message = 'New Reset Password for ' . $username;
        $htmlContent = '';
        $htmlContent = '<p>Dear Sir, <br/><br/>';
        $htmlContent .= '<br/>Your password has been reset by admin. </p>';
        $htmlContent .= '<br/>new password is: <b>' . $OTP . '</b></p>';
        $htmlContent .= '<br/><br/>';
        $htmlContent .= '<p>Regards,';
        $htmlContent .= '<br/>Everest Instrument Pvt. Ltd.</p>';
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param mixed $id
     *
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $modelClass = $this->modelClass;

        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }

}
