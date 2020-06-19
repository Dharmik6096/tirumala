<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\models\UserHistory;
use app\modules\details\models\TblContactDetails;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\webservice\eipl\models\TblEiplAppLoginTemp;

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


                            $appModel = new TblEiplAppLogin();
                            $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                            $appModelData = $appModel->getAppLogin($id);
                            if (!empty($appModelData)) {
                                $appModelData->is_active = 0;
                                $appModel = $appModelData;
                            }
                            $master[] = $appModel;

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

                        $appModel = new TblEiplAppLogin();
                        $appModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $appModelData = $appModel->getAppLogin($id);
                        if (!empty($appModelData)) {
                            $appModelData->is_active = 0;
                            $appModel = $appModelData;
                        }
                        $master[] = $appModel;

                        $tempModel = new TblEiplAppLoginTemp();
                        $tempModel->mobile_no = $model->oldAttributes['mobile_no'];
                        $tempModelData = $tempModel->getAppTempLogin($id);
                        if (!empty($tempModelData)) {
                            $delete[] = $tempModelData;
                        }
                    }
                    if ($model->oldAttributes['allow_app_login'] == 0 && $model->allow_app_login == 1) {
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
                }

//                else {
//                    $model->load(Yii::$app->request->post());
//                    $model->save();
//                }
                $transaction = $this->generalModel->saveTransaction($master, [], $delete, ['User', 'edit']);
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
