<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\models\UserHistory;
use app\modules\details\models\TblContactDetails;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\webservice\eipl\models\TblEiplAppLoginTemp;
use app\modules\webservice\eipl\models\TblAppOrganizationMapping;
use app\models\TblUserOrganizationMapping;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends \webvimark\modules\UserManagement\controllers\UserController {

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
            //if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            $master = [];
            $delete = [];
            if ($model->validate()) {
                if ($tableName == "{{%user}}") {
                    $historyModel = new UserHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $model->load(Yii::$app->request->post());
                    $model->username = $oldUsername;
                    $master[] = $model;

                    if ($model->oldAttributes['allow_app_login'] == 1 && $model->allow_app_login == 1) {
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
                            $master[] = $contNewModel;

                            $appOrgModel = new TblAppOrganizationMapping();
                            $appOrgModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $appOrgModel->detail_code = $contNewModel->detail_code;
                            $orgModelData = $appOrgModel->getAppOrgData();
                            if (!empty($orgModelData)) {
                                $orgModelData->mobile_no = $model->mobile_no;
                                $master[] = $orgModelData;
                            }


                            $appModel = new TblEiplAppLogin();
                            $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $appModelData = $appModel->getAppLogin($id);
                            if (!empty($appModelData)) {
                                $appModelData->is_active = 0;
                                $master[] = $appModelData;
                            }
                            $tempModel = new TblEiplAppLoginTemp();
                            $tempModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $tempModelData = $tempModel->getAppTempLogin($id);
                            if (!empty($tempModelData)) {
                                $delete[] = $tempModelData;
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
                            $delete[] = $orgModelData;
                        }

                        $appModel = new TblEiplAppLogin();
                        $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $appModelData = $appModel->getAppLogin($id);
                        if (!empty($appModelData)) {
                            $appModelData->is_active = 0;
                            $master[] = $appModelData;
                        }

                        $tempModel = new TblEiplAppLoginTemp();
                        $tempModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $tempModelData = $tempModel->getAppTempLogin($id);
                        if (!empty($tempModelData)) {
                            $delete[] = $tempModelData;
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
                            $appOrgModel = $orgModelData;
                            $master[] = $appOrgModel;
                        } else {
                            $UserOrgModel = new TblUserOrganizationMapping();
                            $UserOrgexistData = $UserOrgModel::find()->where(['user_id' => $id, 'is_active' => 1])->one();
                            $appOrgModel->is_active = 1;
                            if (!empty($UserOrgexistData)) {
                                $appOrgModel->organization_code = $UserOrgexistData->organization_code;
                                $appOrgModel->organization_type = $UserOrgexistData->organization_type;
                                $master[] = $appOrgModel;
                            }
                        }
                    }
                }

//                else {
//                    $model->load(Yii::$app->request->post());
//                    $model->save();
//                }
                $transaction = $this->generalModel->saveDelete4($master, [], $delete, ['User', 'edit']);
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

}
