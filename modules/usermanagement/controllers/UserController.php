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
use \app\modules\details\models\TblContactDetailsHistory;
use app\modules\product\models\TblDispatchCenter;
use app\modules\product\models\TblDispatchCenterApplicability;
use app\modules\usermanagement\models\search\UserSearch;
use app\modules\usermanagement\models\rbacDB\Role;
use yii\helpers\Json;
use yii\helpers\FileHelper;
use yii\widgets\ActiveForm;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertNotification;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\modules\usermanagement\models\TblUserDispatchCenterMapping;
use app\modules\usermanagement\models\TblUserEngineerMapping;
use app\modules\usermanagement\models\TblUserEngineerMappingSearch;
use app\modules\usermanagement\models\TblUserEngineerMappingHistory;
use yii\helpers\ArrayHelper;
use app\modules\sms\models\TblAlertTemplate;

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
            $this->model->last_password_updated_at = date('Y-m-d H:i:s');
            $this->model->date_of_joining = !empty($this->model->date_of_joining) ? date('Y-m-d', strtotime($this->model->date_of_joining)) : NULL;

            //Assign Role
            $master = [];
            // $master[] = $this->model;
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
                $contactModel->primary_parent = !empty($this->model->primary_parent) ? $this->model->primary_parent : NULL;
                $contactModel->secondary_parent = !empty($this->model->secondary_parent) ? $this->model->secondary_parent : NULL;
                $master[] = $contactModel;
            }
            if (!empty($this->model->dispatch_center_type_code)) {
                if (empty($this->model->dispatch_center_code)) {
                    $dispatchCenters = TblDispatchCenter::find()->where(['dispatch_center_type_code' => $this->model->dispatch_center_type_code])->all();
                    $this->model->dispatch_center_code = array_column($dispatchCenters, 'dispatch_center_code');
                }
                foreach ($this->model->dispatch_center_code as $dispatch_center_code) {
                    $dispCenterMapping = new TblUserDispatchCenterMapping();
                    $dispCenterMapping->user_code = $this->model->id;
                    $dispCenterMapping->dispatch_center_type_code = $this->model->dispatch_center_type_code;
                    $dispCenterMapping->dispatch_center_code = $dispatch_center_code;
                    $master[] = $dispCenterMapping;
                }
                $dispactApplicability = TblDispatchCenterApplicability::find()->where(['dispatch_center_code' => $this->model->dispatch_center_code])->all();
                $this->model->user_type_id = '7';
                unset($this->model->dispatch_center_code);
                $master[] = $this->model;
                if (!empty($dispactApplicability)) {
                    foreach ($dispactApplicability as $key => $applicability) {
                        $organizationMappingModel = new TblUserOrganizationMapping();
                        $organizationMappingModel->organization_code = $applicability->applicable_code;
                        $organizationMappingModel->organization_type = 'DCS';
                        $organizationMappingModel->user_id = $this->model->id;
                        $organizationMappingModel->is_active = 1;
                        $master[] = $organizationMappingModel;
                    }
                }
            } else {
                unset($this->model->dispatch_center_code);
                $master[] = $this->model;
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
                    // $master[] = $model;
                    $model->date_of_joining = !empty($model->date_of_joining) ? date('Y-m-d', strtotime($model->date_of_joining)) : NULL;
                    $master[] = $historyModel;
                    $master[] = $model;

                    if ($model->oldAttributes['allow_app_login'] == 1 && $model->allow_app_login == 1) {
                        if ($model->oldAttributes['mobile_no'] != $model->mobile_no || $model->oldAttributes['login_type'] != $model->login_type) {
                            if ($model->oldAttributes['mobile_no'] != $model->mobile_no) {
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
                                $contNewModel->primary_parent = !empty($model->primary_parent) ? $model->primary_parent : NULL;
                                $contNewModel->secondary_parent = !empty($model->secondary_parent) ? $model->secondary_parent : NULL;
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
                        $contactModel->primary_parent = !empty($model->primary_parent) ? $model->primary_parent : NULL;
                        $contactModel->secondary_parent = !empty($model->secondary_parent) ? $model->secondary_parent : NULL;
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
                        $contactModel->primary_parent = !empty($model->primary_parent) ? $model->primary_parent : NULL;
                        $contactModel->secondary_parent = !empty($model->secondary_parent) ? $model->secondary_parent : NULL;
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
                            $contactModel->primary_parent = !empty($model->primary_parent) ? $model->primary_parent : NULL;
                            $contactModel->secondary_parent = !empty($model->secondary_parent) ? $model->secondary_parent : NULL;
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
                    $existDispatchCenterCodes = [];
                    $dispatchMappingDelete = [];
                    $newDispatchCenterCodes = [];
                    if (!empty($model->dispatch_center_type_code)) {
                        $newDispatchCenterCodes = $model->dispatch_center_code;
                        if (empty($model->dispatch_center_code)) {
                            $dispatchCenters = TblDispatchCenter::find()->where(['dispatch_center_type_code' => $model->dispatch_center_type_code])->all();
                            $newDispatchCenterCodes = array_column($dispatchCenters, 'dispatch_center_code');
                        }
                        $existDispatchCenters = TblUserDispatchCenterMapping::find()->where(['user_code' => $model->id])->all();
                        if (!empty($existDispatchCenters)) {
                            $delete = array_merge($delete, $existDispatchCenters);
                            $existDispatchCenterCodes = array_column($existDispatchCenters, 'dispatch_center_code');
                        }
                        // Step 1: Matching values remain unchanged, non-matching values are removed
                        $existDispatchCenterCodes = array_intersect($existDispatchCenterCodes, $newDispatchCenterCodes);
                        // Step 2: Insert non-matching values from the new array
                        $nonMatchingCodes = array_diff($newDispatchCenterCodes, $existDispatchCenterCodes);
                        $newDispatchCenterCodes = array_merge($existDispatchCenterCodes, $nonMatchingCodes);
                        foreach ($newDispatchCenterCodes as $dispatch_center_code) {
                            $dispCenterMapping = new TblUserDispatchCenterMapping();
                            $dispCenterMapping->user_code = $model->id;
                            $dispCenterMapping->dispatch_center_type_code = $model->dispatch_center_type_code;
                            $dispCenterMapping->dispatch_center_code = $dispatch_center_code;
                            $master[] = $dispCenterMapping;
                        }
                    }
                    if (!empty($newDispatchCenterCodes)) {
                        $UserOrgMapModel = new TblUserOrganizationMapping();
                        $UserOrgMapexistData = $UserOrgMapModel::find()->where(['user_id' => $id])->all();
                        if (!empty($UserOrgMapexistData)) {
                            foreach ($UserOrgMapexistData as $org) {
                                $delete[] = $org;
                            }
                        }
                        $model->user_type_id = NULL;
                        $dispactApplicability = TblDispatchCenterApplicability::find()->where(['dispatch_center_code' => $newDispatchCenterCodes])->all();
                        if (!empty($dispactApplicability)) {
                            foreach ($dispactApplicability as $key => $applicability) {
                                $organizationMappingModel = new TblUserOrganizationMapping();
                                $organizationMappingModel->organization_code = $applicability->applicable_code;
                                $organizationMappingModel->organization_type = 'DCS';
                                $organizationMappingModel->user_id = $model->id;
                                $organizationMappingModel->is_active = 1;
                                $master[] = $organizationMappingModel;
                            }
                        }
                        $model->user_type_id = '7';
                    }
                }
                unset($model->dispatch_center_code);
                $master[] = $model;

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
        if (!empty($model->userDispatchCenterMappingCode)) {
            $model->dispatch_center_code = array_column($model->userDispatchCenterMappingCode, 'dispatch_center_code');
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
        $model->scenario = 'passwordReset';
        if ($this->scenarioOnUpdate) {
            $model->scenario = $this->scenarioOnUpdate;
        }
        $tableName = $model->tableName();
        if (Yii::$app->request->post()) {
            $master = [];
            $delete = [];
            $historyModel = new UserHistory();
            Yii::$app->operation->history($model, $historyModel, UPDATE);
            $model->load(Yii::$app->request->post());
            if ($model->validate() && !empty($model->email)) {
                if ($tableName == "{{%user}}") {
                    $model->load(Yii::$app->request->post());
                    $model->last_password_updated_at = date('Y-m-d H:i:s');
                    $master[] = $historyModel;
                    $master[] = $model;

                    $apiMaster = new TblApiMaster();
                    $apiMaster->receiver_type = 'EMAIL';
                    $apiMasterData = $apiMaster->getAPI();

                    if (!empty($apiMasterData)) {
                        $templateModel = new TblAlertTemplate();
                        $templateData = $templateModel->getTemplateData('portal_password_reset_admin', 'EMAIL', $apiMaster->union_code);
                        $notificationModel = new TblAlertNotification();
                        $notificationModel->receiver_type = 'EMAIL';
                        $notificationModel->message = str_replace('{PWD}', $model->password, $templateData->message);;
                        $notificationModel->header_info = str_replace('{name}', substr($model->username, 3), $templateData->header_info);;
                        $notificationModel->send_status = 0;
                        $notificationModel->content_id = $apiMasterData->api_master_id;
                        $notificationModel->refecence_code = $model->id;
                        $notificationModel->module_type = "portal_password_reset_admin";
                        $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                        $notificationModel->send_mail = 1;
                        $notificationModel->receiver_detail = $model->email;
                        $master[] = $notificationModel;
                    }
                }
            } else {
                $model->addError('password', Yii::t('app', 'Email Is Not Available for This user'));
                return $this->renderIsAjax('reset_password', ['model' => $model]);
            }
            $transaction = $this->generalModel->saveDelete4($master, [], $delete, ['Password', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->renderIsAjax('reset_password', ['model' => $model]);
    }

    public function actionOrganizationMap($id) {
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
            if (!empty(Yii::$app->request->post()['TblUserOrganizationMapping'])) {
                $postData = Yii::$app->request->post();
                $organization = $model->getOrganizationsArray($id, $postData['user_type'], $postData['TblUserOrganizationMapping']);
            }
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

    private function addUserOrganizationMapping($data, $type, $userId, $active) {
        foreach ($data as $value) {
            $modelNew = new TblUserOrganizationMapping();
            $modelNew->organization_code = $value;
            $modelNew->organization_type = $type;
            $modelNew->user_id = $userId;
            $modelNew->is_active = $active;
            Yii::$app->operation->defaults($modelNew, INSERT);
            $modelNew->save(TRUE, FALSE);
        }
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

    public function actionMapEngineer($id) {
        $DcsBmcModel = new User();
        $engineer_data = $DcsBmcModel->getEngineerList();
        unset($engineer_data[$id]);
        $model = new TblUserEngineerMapping();
        $searchModel = new TblUserEngineerMappingSearch();
        $searchModel->user_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exist_data = ArrayHelper::map($dataProvider->getModels(), 'engineer_id', 'engineer_id');
        $engineer_data = array_diff_key($engineer_data, $exist_data);
        $master = [];
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblUserEngineerMapping'])) {
            $engineer_code = Yii::$app->request->post()['TblUserEngineerMapping']['engineer_id'];
            $i = 1;
            if (!empty($engineer_code)) {
                foreach ($engineer_code as $mapped_engineer_code) {
                    $engineerModel = new TblUserEngineerMapping();
                    $engineerModel->user_engineer_mapping_code = Yii::$app->general->getCodeAutoIncrement($engineerModel, $i);
                    $engineerModel->user_id = $id;
                    $engineerModel->engineer_id = $mapped_engineer_code;
                    $i++;
                    $master[] = $engineerModel;
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, ['Enginner Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }

        return $this->render('_map_engineer', [
                    'model' => $model, 'engineer_data' => $engineer_data,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionDeleteEngineer() {
        $model = TblUserEngineerMapping::findOne(Yii::$app->request->post('id'));
        $record = [];
        $historyModel = new TblUserEngineerMappingHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
