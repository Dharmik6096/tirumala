<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberSearch;
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use \app\modules\organisation\models\TblDcs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\sms\models\TblAlertNotification;
use app\modules\webservice\eipl\models\TblEiplAppLoginHistory;
use yii\imagine\Image;
use yii\web\UploadedFile;
use app\modules\dcsoperation\models\TblMemberDeactiveSearch;
use app\modules\document\controllers\TblAttachmentController;
use app\modules\dcsoperation\models\TblMemberFamilyDetails;
use app\modules\dcsoperation\models\TblMemberFamilyDetailsHistory;
use app\modules\dcsoperation\models\TblMemberFamilyDetailsSearch;
use app\modules\dcsoperation\models\TblMemberShareDetails;
use app\modules\dcsoperation\models\TblMemberShareDetailsHistory;
use app\modules\dcsoperation\models\TblUnionShareConfig;
use app\modules\dcsoperation\models\TblMemberAnimalDetails;
use app\modules\dcsoperation\models\TblMemberAnimalType;
use yii\bootstrap\ActiveForm;
use app\modules\dcsoperation\models\TblMemberAnimalDetailsHistory;
use app\modules\dcsoperation\models\TblMemberShareDetailsSearch;
use app\modules\dcsoperation\models\TblMemberAnimalDetailsSearch;
use app\modules\document\models\TblAttachment;
use app\modules\veterinary\models\TblMemberAnimalTagDetailsSearch;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcsSearch;
use yii\helpers\Url;
use app\modules\organisation\models\TblDcsHistory;

/**
 * TblMemberController implements the CRUD actions for TblMember model.
 */
class TblMemberController extends \app\controllers\ChildController {

    public $bankDetails;
    public $freeAccessActions = ['import-file', 'activate-member-code-list'];

    /**
     * Lists all TblMember models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMember model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblMemberDeactiveSearch();
        $searchModel->member_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);

        $familyMemberModel = new TblMemberFamilyDetailsSearch();
        $familyMemberModel->member_code = $id;
        $fDataProvider = $familyMemberModel->search(Yii::$app->request->queryParams);

        $animalMemberModel = new TblMemberAnimalDetailsSearch();
        $animalMemberModel->member_code = $id;
        $animalDataProvider = $animalMemberModel->search(Yii::$app->request->queryParams);

        $shareMemberModel = new TblMemberShareDetailsSearch();
        $shareMemberModel->member_code = $id;
        $shareDataProvider = $shareMemberModel->search(Yii::$app->request->queryParams);

        $tagSearchModel = new TblMemberAnimalTagDetailsSearch();
        $tagSearchModel->member_code = $id;
        $tagDataProvider = $tagSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'familyMemberModel' => $familyMemberModel,
                    'fDataProvider' => $fDataProvider,
                    'animalMemberModel' => $animalMemberModel,
                    'animalDataProvider' => $animalDataProvider,
                    'shareMemberModel' => $shareMemberModel,
                    'shareDataProvider' => $shareDataProvider,
                    'shareMemberModel' => $shareMemberModel,
                    'shareDataProvider' => $shareDataProvider,
                    'tagSearchModel' => $tagSearchModel,
                    'tagDataProvider' => $tagDataProvider,
        ]);
    }

    /**
     * Creates a new TblMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMember();
        $this->viewFile = 'create';
        $this->bankDetails = new TblBankDetails();
        $this->bankDetails->scenario = 'member_create';
        $this->setDefaultModel();
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
//var_dump($this->model->unionCode->federationCode);exit();
            $this->model->member_code = $this->model->getCode();
            $this->setModel();
// $dcs = TblDcs::findOne($this->model->dcs_code);
// $this->model->state_code = $dcs->state_code;
//$this->model->district_code = $dcs->district_code;
// $this->model->sub_district_code = $dcs->sub_district_code;
            $this->model->upload = 0;
            $this->bankDetails->load(Yii::$app->request->post());
            if (!empty($this->model->bank_code)) {
                $this->model->scenario = 'bank_selected';
            }

            $bankValidate = 1;
            $bankValidate = Yii::$app->warning->codeWarningBankAc($this->model);
            if ($bankValidate == 1 && $_POST['warning'] == '0') {
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            }
            if ($bankValidate == 1 && $validate == 1 && empty($this->model->getErrors()) && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $transaction = $this->generalModel->saveTransaction([$this->model], ['member', 'create']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
//                        if (Yii::$app->general->isVendor($this->model->dcs_code, 'BIPL')) {
//                            $this->model->generateBiplMemberFiles();
//                        }
                    }
                    return $this->{$transaction}();
                }
            } else {
                $this->model->scenario = '';
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;
        $this->setModel();

        if (Yii::$app->request->post()) {
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
//var_dump($this->model);exit();
//$dcs = TblDcs::findOne($this->model->dcs_code);
// $this->model->state_code = $dcs->state_code;
//   $this->model->district_code = $dcs->district_code;
//   $this->model->sub_district_code = $dcs->sub_district_code;
            $historyModel = new TblMemberHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
//            $this->setModel();
            if (!empty($this->model->bank_code)) {
                $this->model->scenario = 'bank_selected';
            }

            $bankValidate = 1;
            $bankValidate = Yii::$app->warning->codeWarningBankAc($this->model);
            if ($bankValidate == 1 && $_POST['warning'] == 0) {
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            }
            if ($bankValidate == 1 && $validate == 1 && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $oldAttr = $this->model['oldAttributes'];
                $adhar_no = Yii::$app->general->decryptData($oldAttr['adhar_no']) !== FALSE ? Yii::$app->general->decryptData($oldAttr['adhar_no']) : $oldAttr['adhar_no'];
                $pan_no = Yii::$app->general->decryptData($oldAttr['pan_no']) !== FALSE ? Yii::$app->general->decryptData($oldAttr['pan_no']) : $oldAttr['pan_no'];
                if ($oldAttr['bank_account_no'] != $this->model->bank_account_no || $oldAttr['ifsc'] != $this->model->ifsc || $oldAttr['beneficiary_name'] != $this->model->beneficiary_name || $pan_no != $this->model->pan_no || $adhar_no != $this->model->adhar_no || $oldAttr['voter_id'] != $this->model->voter_id) {
                    $this->model->is_verified = 0;
                    $this->model->is_kyc_verified = 0;
                }
                if ($oldAttr['hamlet_code'] != $this->model->hamlet_code || $oldAttr['address'] != $this->model->address || $oldAttr['local_address'] != $this->model->local_address || $oldAttr['pincode'] != $this->model->pincode || $oldAttr['mobile_no'] != $this->model->mobile_no || $oldAttr['email'] != $this->model->email) {
                    $this->model->is_contact_verified = 0;
                }
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['member', 'edit']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
//                        if (Yii::$app->general->isVendor($this->model->dcs_code, 'BIPL')) {
//                            $this->model->generateBiplMemberFiles();
//                        }
                    }
                    return $this->{$transaction}();
                }
            } else {
                $this->model->scenario = '';
            }
        }
        return $this->customRender();
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->member_code]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'bankDetails' => $this->bankDetails,
        ]);
    }

    /**
     * Deletes an existing TblMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_member', Yii::$app->request->post('id'), 'member_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $localHistory = new TblMemberHistory();
            Yii::$app->operation->history($this->model, $localHistory, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $localHistory]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDcsMemberExist() {
        $count = TblMember::find()->select(['COUNT(*) AS cnt'])->where(['dcs_code' => Yii::$app->request->post('code')])->all();
        $record = ['cnt' => $count[0]->cnt];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($member_code) {
        if (($model = TblMember::findOne(['member_code' => $member_code])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBankDetails($id) {
        $bankDetails = new TblBankDetails();
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name = 'member';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelMember = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
                    'model' => $bankDetails,
                    'id' => $id,
                    'module' => 'member',
                    'dist' => $modelMember->district_code,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dist_field' => 'tblmember-district_code'
        ]);
    }

    public function actionDistrictCode() {
        if (isset($_POST['dcs_code'])) {
            $dcs = TblDcs::findOne($_POST['dcs_code']);
            $dist_code = $dcs->district_code;
            return $dist_code;
        }
        return false;
    }

    public function actionDeactivateUser($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblMemberHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->scenario = 'deactivate';
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['member', 'edit']);
        if ($transaction == 'customRedirect') {
//            if (Yii::$app->general->isVendor($this->model->dcs_code, 'BIPL')) {
//                $this->model->generateBiplMemberFiles();
//            }
            $record = ['status' => 'success', 'msg' => 'Member Deactivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Member Not Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    private function setDefaultModel() {
        $this->model->no_of_buffalo = $this->model->no_of_cow_cross = $this->model->no_of_cow_ind = $this->model->total_animals = 0;
    }

    private function setModel() {
        $this->model->dob = empty($this->model->dob) ? NULL : $this->model->dob;
        $this->model->member_name = ucwords($this->model->member_name);
        $this->model->ex_member_code = str_pad($this->model->ex_member_code, 4, '0', STR_PAD_LEFT);
    }

    public function actionAppInformation() {
        $member_code = !empty(Yii::$app->request->post('member_code')) ? Yii::$app->request->post('member_code') : NULL;
        $this->model = $this->findModel($member_code);
        $appModel = new TblEiplAppLogin();
        $appInfo = $appModel->getMemberAppInfo($this->model);
        $alertInfo = [];
        if (!empty($appInfo)) {
            $alertModel = new TblAlertNotification();
            $alertInfo = $alertModel->getAppAlertInfo($appInfo);
        }
        if (!empty(Yii::$app->request->post()['TblEiplAppLogin']) && !empty(Yii::$app->request->post()['TblEiplAppLogin']['app_login_id'])) {
            $appModel = TblEiplAppLogin::findOne(['app_login_id' => Yii::$app->request->post()['TblEiplAppLogin']['app_login_id']]);
            $historyModel = new TblEiplAppLoginHistory();
            Yii::$app->operation->history($appModel, $historyModel, UPDATE);
            if ($appModel->is_block == 1) {
                $appModel->is_block = 0;
                $label = 'app Un-block';
            } else {
                $appModel->is_block = 1;
                $label = 'app Block';
            }
            $transaction = $this->generalModel->saveTransaction([$appModel, $historyModel], [$label, 'edit']);
            return $this->redirect(['index']);
        }

        return $this->renderAjax('app_info_form', [
                    'model' => $this->model,
                    'appInfo' => $appInfo,
                    'alertInfo' => $alertInfo,
        ]);
    }

    public function actionImportAttachements() {
        $model = new TblMember();
        if (isset($_POST['code'])) {
            $model->member_code = $_POST['code'];
        }
        $saveModel = [];
        if ($model->load(Yii::$app->request->post())) {
            $files = !empty(Yii::$app->request->post()['TblMember']['file_name']) ? Yii::$app->request->post()['TblMember']['file_name'] : '';
            Yii::$app->general->setAttachment($saveModel, $files, $model->member_code, 'TblMember');
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Image Uploaded', 'edit']);
            return $this->redirect(['index']);
        }
        return $this->renderAjax('_popup', ['model' => $model]);
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/upload/images/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = date('YmdHis') . rand(1000, 9999) . $file->name;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'filename' => $name, 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'filename' => $name, 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionMemberDocumentUpload($id) {
        $model = $this->findModel($id);
        $module_code = $model->member_code;
        $module_name = 'tbl_member';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('member', $id, $model, $module_code, $module_name);
    }

    public function actionActivateMemberCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2])) {
                $member = new TblMember();
                $data = $member->getActivateMemberCode($parents[0], $parents[1], $parents[2]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMemberDetails($id) {
        $this->model = new TblMember();
        $this->model->member_code = $id;
        $memberData = $this->model->findOne($id);
        if (!empty($memberData)) {
            $this->model = $memberData;
        }
        $animal_model = new TblMemberAnimalType();
        $animal_model->union_code = $this->model->union_code;
        $animals = $animal_model->getAnimal();
        $member_animal_model_data = [];
        $member_animal_model = new TblMemberAnimalDetails();
        $member_animal_model->member_code = $id;

        $h_model = [];
        $msearchModel = new TblMemberSearch();
        $msearchModel->member_code = $id;
        $mdataProvider = $msearchModel->search(Yii::$app->request->queryParams);
        $this->setAnimalModelData($animals, $member_animal_model_data, $h_model, $id);

        // family detail 
        $memberFamilyDetail = new TblMemberFamilyDetails();
        $memberFamilyDetail->member_code = $id;
        $memberFamilyDetail->scenario = 'member_family_detail';
        $memberFamilySearchModel = new TblMemberFamilyDetailsSearch();
        $memberFamilySearchModel->member_code = $id;
        $memberFamilyDataProvider = $memberFamilySearchModel->search(Yii::$app->request->queryParams);
        //
        // share detail 
        $shareConfig = new TblUnionShareConfig();
        $dcs_detail = TblDcs::find()->select('bmc_code')->where(['dcs_code' => $this->model->dcs_code])->one();
        $shares = $shareConfig->getShareDetail('member', $this->model->gender_code, $this->model->union_code, $dcs_detail['bmc_code']);

        $memberShareDetail = new TblMemberShareDetails();
        $memberShareDetail->member_code = $id;

        $memberShareDetail = TblMemberShareDetails::find()->where(['member_code' => $id])->one();
        if (empty($memberShareDetail)) {
            $memberShareDetail = new TblMemberShareDetails();
            $memberShareDetail->member_code = $id;
            $memberShareDetail->no_of_share_req = $shares['min_share'];
            $memberShareDetail->no_of_share_apply = $shares['max_share'];
            $memberShareDetail->admission_fee = $shares['admission_fee'];
            $memberShareDetail->payable_share_amount = $shares['max_share'] * $shares['per_share_rate'];
            $memberShareDetail->amount_payable = ($shares['max_share'] * $shares['per_share_rate']) + $shares['admission_fee'];
            $memberShareDetail->total_amount = ($shares['max_share'] * $shares['per_share_rate']) + $shares['admission_fee'];
            $memberShareDetail->per_share_rate = $shares['per_share_rate'];
        }
        $this->setShareModelData($memberShareDetail, $h_model, $id);
        $this->model->scenario = 'member_detail';

        if (Yii::$app->request->post()) {
            $master_model = [];
            $post_data = Yii::$app->request->post();
            $member_details = $post_data['TblMember'];
            $this->model->load($post_data);
            if (!empty($memberData)) {
                $historyModel = new TblMemberHistory();
                Yii::$app->operation->history($memberData, $historyModel, UPDATE);
                $h_model[] = $historyModel;
                $memberData->attributes = $this->model->attributes;
                $memberData->setAttributes($member_details);
                $master_model[] = $memberData;
            }
            $animal_details = $post_data['TblMemberAnimalDetails'];
            $this->setAnimalDetails($animal_details, $master_model, $member_animal_model_data);

            $share_details = $post_data['TblMemberShareDetails'];
            $memberShareDetail->load($post_data);
            $share_model_data = $memberShareDetail->getMemberShare();
            if (!empty($share_model_data)) {
                $memberShareDetail = $share_model_data;
                $memberShareDetail->deposit_date = empty($memberShareDetail->deposit_date) ? NULL : Yii::$app->formatter->asDate($memberShareDetail->deposit_date, DATE_FORMAT);
                $memberShareDetail->load($post_data);
            }
            $memberShareDetail['gender_code'] = $this->model->gender_code;
            $memberShareDetail['bmc_code'] = $dcs_detail['bmc_code'];

            $master_model[] = $memberShareDetail;
            $msg = '';
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (empty($memberShareDetail->getErrors()) && empty($this->model->getErrors()) && empty($member_animal_model->getErrors()) && $memberShareDetail->validate() && $member_animal_model->validate() && $this->model->validate()) {

                $transaction = $this->generalModel->saveTransaction($master_model, $h_model, ['member', 'create']);
                $msg = '';
                if (Yii::$app->session->hasFlash('success')) {
                    $msg = Yii::$app->session->getFlash('success');
                    $msg = $msg['message'];
                }
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $data = [];
                    $data['status'] = 'error';
                    $data['errors'] = ActiveForm::validate($this->model, $memberShareDetail, $member_animal_model);
                    $data['message'] = $msg;
                    return $data;
                }
            } else {
                $data = [];
                $data['status'] = 'error';
                $data['errors'] = ActiveForm::validate($this->model, $memberShareDetail, $member_animal_model);
                $data['message'] = $msg;
                return $data;
            }
        }
        return Yii::$app->controller->render('@app/modules/dcsoperation/views/tbl-member-animal-details/create', [
                    'model' => $this->model,
                    'animals' => $animals,
                    'member_animal_model' => $member_animal_model,
                    'member_animal_model_data' => $member_animal_model_data,
                    'msearchModel' => $msearchModel,
                    'mdataProvider' => $mdataProvider,
                    'memberFamilyDetail' => $memberFamilyDetail,
                    'memberFamilySearchModel' => $memberFamilySearchModel,
                    'memberFamilyDataProvider' => $memberFamilyDataProvider,
                    'memberShareDetail' => $memberShareDetail,
        ]);
    }

    private function setAnimalDetails($animal_details, &$master_model, &$provisional_animal_model_data) {
        unset($animal_details['no_of_heifers_count']);
        unset($animal_details['no_of_milch_animal_count']);
        unset($animal_details['no_of_dry_animal_count']);
        unset($animal_details['no_of_total_animal']);
        foreach ($animal_details as $animal_detail) {
            $animal_detail_model = new TblMemberAnimalDetails();
            $animal_detail_model->member_code = $this->model->member_code;
            $animal_detail_model->animal_type_code = $animal_detail['animal_type_code'];
            $animal_detail_model->union_code = $this->model->union_code;
            $animal_model_data = $animal_detail_model->getMemberAnimals();
            if (!empty($animal_model_data)) {
                $animal_detail_model = $animal_model_data;
            }
            $animal_detail_model->setAttributes($animal_detail);
            $provisional_animal_model_data[$animal_detail_model->animal_type_code] = $animal_detail_model;
            $master_model[] = $animal_detail_model;
        }
    }

    private function setAnimalModelData($animals, &$member_animal_model_data, &$h_model, $id = '') {
        if (!empty($animals)) {
            foreach ($animals as $animal) {
                $animal_model = new TblMemberAnimalDetails();
                $animal_model->animal_type_code = $animal->animal_type_code;
                if (!empty($id)) {
                    $animal_model->member_code = $id;
                    $animal_model_data = $animal_model->getMemberAnimals();
                    if (!empty($animal_model_data)) {
                        $animal_model = $animal_model_data;
                        $historyModel = new TblMemberAnimalDetailsHistory();
                        Yii::$app->operation->history($animal_model_data, $historyModel, UPDATE);
                        $h_model[] = $historyModel;
                    }
                }
                $member_animal_model_data[$animal->animal_type_code] = $animal_model;
            }
        }
    }

    private function setShareModelData($memberShareDetail, &$h_model, $id = '') {

        if (!empty($memberShareDetail)) {
            $share_model = new TblMemberShareDetails();
            if (!empty($id)) {
                $share_model->member_code = $id;
                $share_model_data = $share_model->getMemberShare();
                if (!empty($share_model_data)) {
                    $share_model = $share_model_data;
                    $historyModel = new TblMemberShareDetailsHistory();
                    Yii::$app->operation->history($share_model_data, $historyModel, UPDATE);
                    $h_model[] = $historyModel;
                }
            }
        }
    }

    public function actionCreateFamily() {
        $id = $_POST['TblMemberFamilyDetails']['member_code'];
        $memberFamilyDetail = new TblMemberFamilyDetails();
        $memberFamilyDetail->scenario = 'member_family_detail';
        $memberFamilySearchModel = new TblMemberFamilyDetailsSearch();
        $memberFamilyDataProvider = null;
        $memberFamilyHistory = [];
        $saveModel = [];
        if (Yii::$app->request->post()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post()['TblMemberFamilyDetails'];

            $existData = TblMemberFamilyDetails::find()->where(['member_family_detail_code' => $data['member_family_detail_code']])->one();

            $memberFamilyDetail->load(Yii::$app->request->post());
            if ($memberFamilyDetail->validate()) {
                $memberFamilyDetail->dob = empty($memberFamilyDetail->dob) ? NULL : Yii::$app->formatter->asDate($memberFamilyDetail->dob, DATE_FORMAT);

                if (!empty($existData)) {
                    $historyModel = new TblMemberFamilyDetailsHistory();
                    Yii::$app->operation->history($existData, $historyModel, UPDATE);
                    $memberFamilyHistory[] = $historyModel;
                    $existData->load(Yii::$app->request->post());
                    $saveModel[] = $existData;
                } else {
                    $saveModel[] = $memberFamilyDetail;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, $memberFamilyHistory, ['member family details', 'edit']);
                $msg = '';
                if (Yii::$app->session->hasFlash('success')) {
                    $msg = Yii::$app->session->getFlash('success');
                    $msg = $msg['message'];
                }
                if ($transaction == 'customRedirect') {
                    $data = [];
                    $data['status'] = 'success';
                    $data['errors'] = [];
                    $data['message'] = 'Member Family Details Successfully Created';
                    return $data;
                } else {
                    $data = [];
                    $data = [
                        'status' => 'error',
                        'errors' => $memberFamilyDetail->errors,
                    ];
                    return $data;
                }
            } else {
                $data = [];
                $data = [
                    'status' => 'error',
                    'errors' => $memberFamilyDetail->errors,
                ];
                return $data;
            }
        }
    }

    public function actionDeleteFamily() {
        if (Yii::$app->request->post('id')) {
            $id = $_POST['id'];
            $deleteModel = [];
            $history_model = [];
            $FamilyDelmodel = TblMemberFamilyDetails::find()->where(['member_family_detail_code' => $id])->one();
            if (!empty($FamilyDelmodel)) {
                $historyModel = new TblMemberFamilyDetailsHistory();
                Yii::$app->operation->history($FamilyDelmodel, $historyModel, DELETE);
                $deleteModel[] = $FamilyDelmodel;
                $history_model[] = $historyModel;
            }
            $record = $this->generalModel->deleteTransaction([$FamilyDelmodel, $historyModel]);
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionGetFamilyData() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['id'])) {
            $modelData = TblMemberFamilyDetails::find()->where(['member_family_detail_code' => $_POST['id']])->one();
            if (!empty($modelData)) {
                $data['status'] = 'success';
                $modelData->dob = !empty($modelData->dob) ? date('d-m-Y', strtotime($modelData->dob)) : '';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

    public function actionRepushBulkData() {
        $param = Yii::$app->request->queryParams;
        $isDcs = !empty($param['TblMemberSearch']['smart_master_type']) || !empty($param['TblDcsSearch']['smart_master_type']);
        $searchModel = $isDcs ? new TblDcsSearch() : new TblMemberSearch();
        if (!empty($param['TblMemberSearch']['smart_master_type'])) {
            $param['TblDcsSearch'] = $param['TblMemberSearch'];
        } elseif (!empty($param['TblDcsSearch']) && empty($param['TblDcsSearch']['smart_master_type'])) {
            $param['TblMemberSearch'] = $param['TblDcsSearch'];
        }
        $searchModel->scenario = 'listSearch';
        $dataProvider = $searchModel->searchrepush($param);
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $selectedcodes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                foreach ($selectedcodes as $code) {
                    if ($_REQUEST['type'] && $_REQUEST['type'] == 'dcs') {
                        $model = TblDcs::findOne($code);
                        $historyModel = new TblDcsHistory();
                    } else {
                        $model = $this->findModel($code);
                        $historyModel = new TblMemberHistory();
                    }
                    
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $historyModel->operation_type = 'BIPLREPUSH';
                    $saveModel[] = $historyModel;
                    $model->data_post_status = 0;
                    $model->resp_desc = null;
                    $model->resp_status = null;
                    $model->response_datetime = null;
                    $model->picked_datetime = null;
                    
                    $saveModel[] = $model;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['BIPL Smart Re-Push', 'edit']);
                var_dump($transaction); die;
                if ($transaction == 'customRedirect') {
                    return $this->redirect(Url::previous());
                }
            }
        }
        return $this->render('_repush_data', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
