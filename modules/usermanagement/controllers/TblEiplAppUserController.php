<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\models\TblEiplAppUser;
use app\modules\usermanagement\models\TblEiplAppUserSearch;
use yii\web\NotFoundHttpException;
use app\models\TblEiplUserOrganizationMapping;
use app\modules\usermanagement\models\TblEiplAppUserHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblEiplAppUserSummaryController implements the CRUD actions for TblEiplAppUser model.
 */
class TblEiplAppUserController extends \app\controllers\ChildController {

    /**
     * Lists all TblEiplAppUser models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblEiplAppUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblEiplAppUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblEiplAppUser();
        $this->viewFile = "create";
        $saveModel = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $identity = new \app\models\IdentityMaster();
            $data = $identity->getIdentity();
            $this->model->eipl_app_user_code = $this->model->getCode();
            $this->model->user_code = $this->model->getCode();
            $this->model->user_identity = $data['organization_code'];
            $this->model->is_active = 1;
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Eipl App User', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['organization-map', 'id' => $this->model->eipl_app_user_code]);
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblEiplAppUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblEiplAppUserHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());

            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Eipl App User', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblEiplAppUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblEiplAppUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblEiplAppUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplAppUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionOrganizationMap($id) {
        $user = TblEiplAppUser::findOne($id);
        $model = new TblEiplUserOrganizationMapping();
        $model->scenario = 'organizationMapping';
        if (Yii::$app->session->get('organizations_type') == 'UNION') {
            $model->scenario = 'organizationMappingUnion';
        }
        $modelData = $model->getUserOrgs($id);
        $app_organization = [];
        $app_org_array = false;
//        if ($user->allow_app_login == 1 && !empty($user->mobile_no)) {
//            $contactModel = new TblContactDetails();
//            $contactModel->mobile_no = $user->mobile_no;
//            $values = $contactModel->getContactDetailsOrg();
//            $app_organization = $model->getOrganizationsArray($id, '', $values);
//            $app_org_array = true;
//        }
        $stickeyOrgArray = [];
        $stickeyOrgArray['union'] = [];
        $stickeyOrgArray['plant'] = [];
        $stickeyOrgArray['mcc'] = [];
        $stickeyOrgArray['bmc'] = [];
        $stickeyOrgArray['dcs'] = [];
        if ($app_org_array) {
            $stickeyOrgArray['union'] = !empty($app_organization['union']['selectedArray']) ? $app_organization['union']['selectedArray'] : [];
            $stickeyOrgArray['plant'] = !empty($app_organization['plant']['selectedArray']) ? $app_organization['plant']['selectedArray'] : [];
            $stickeyOrgArray['mcc'] = !empty($app_organization['mcc']['selectedArray']) ? $app_organization['mcc']['selectedArray'] : [];
            $stickeyOrgArray['bmc'] = !empty($app_organization['bmc']['selectedArray']) ? $app_organization['bmc']['selectedArray'] : [];
            $stickeyOrgArray['dcs'] = !empty($app_organization['dcs']['selectedArray']) ? $app_organization['dcs']['selectedArray'] : [];
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
        $model->dcs = $dcs['selectedArray'];
        $model->bmc = $bmc['selectedArray'];
        $model->mcc = $mcc['selectedArray'];
        $model->plant = $plant['selectedArray'];
        $model->union = $unions['selectedArray'];
        $model->federation = ['01'];


        if ($model->load(Yii::$app->request->post())) {
            $setAppOrgMap = false;
//            $contactModel = new TblContactDetails();
//            if ($user->allow_app_login == 1 && !empty($user->mobile_no)) {
//                $contactModel->mobile_no = $user->mobile_no;
//                $contactModelData = $contactModel->getContactDetailsRecord();
//                if (!empty($contactModelData)) {
//                    $contactModel = $contactModelData;
//                } else {
//                    $contactModel->firstname = $user->name;
//                    $contactModel->setModel('user', $user->id, 0);
//                }
//                $contactModel->department = $user->department;
//                $contactModel->save();
//                TblAppOrganizationMapping::deleteAll(['detail_code' => $contactModel->detail_code]);
//                $setAppOrgMap = true;
//            }

            TblEiplUserOrganizationMapping::deleteAll(['user_id' => $id]);
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
            $user->save(false);
            Yii::$app->display->message(true, 'user', 'edit');
            return $this->redirect(['index']);
        }
        return $this->renderIsAjax('organization_map', ['model' => $model, 'user' => $user, 'federations' => $federations, 'unions' => $unions, 'plant' => $plant, 'mcc' => $mcc, 'bmc' => $bmc, 'dcs' => $dcs, 'stickeyOrgArray' => $stickeyOrgArray]);
    }

    private function addUserOrganizationMapping($data, $type, $userId, $active) {
        $userModel = new TblEiplAppUser();
        $users = $userModel->findByRole(['EIPL']);
        $users = \yii\helpers\ArrayHelper::getColumn($users, 'id');
        $is_eipl = in_array($userId, $users);
        foreach ($data as $value) {
            $modelNew = new TblEiplUserOrganizationMapping();
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

    protected function renderIsAjax($view, $params = []) {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($view, $params);
        } else {
            return $this->render($view, $params);
        }
    }

    public function actionDeactivateUser($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblEiplAppUserHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->scenario = 'deactivate';
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['user', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'User Deactivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'User Not Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
