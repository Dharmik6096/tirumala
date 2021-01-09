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
use app\modules\general\models\TblAttachment;

/**
 * TblMemberController implements the CRUD actions for TblMember model.
 */
class TblMemberController extends \app\controllers\ChildController {

    public $bankDetails;
    public $freeAccessActions = ['import-file'];

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
        return $this->render('view', [
                    'model' => $this->findModel($id),
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
            if ($bankValidate == 1 && $validate == 1 && $this->model->validate() && empty($this->model->getErrors())) {
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
        $saveModel = [];
        if ($model->load(Yii::$app->request->post())) {
            $files = !empty(Yii::$app->request->post()['TblMember']['file_name']) ? Yii::$app->request->post()['TblMember']['file_name'] : '';
            $this->setAttachment($saveModel, $files);
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Image Uploaded', 'edit']);
            return $this->redirect(['index']);
        }

        return $this->render('_popup', ['model' => $model]);
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

    private function setAttachment(&$child, $files) {
        $filesArray = explode(',', $files);
        unset($filesArray[0]);

        $auto_inc = 0;
        foreach ($filesArray as $key => $file) {
            $path = Yii::$app->basePath . '/web/upload/images//' . $file; //Generate your save file path here;
            $modelAttachment = new TblAttachment();
            $modelAttachment->attachment_code = (string) Yii::$app->general->getCodeAutoIncrement($modelAttachment, $auto_inc);
            $file = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/images/' . $file;
            $modelAttachment->attachment = $file;
            $modelAttachment->module_name = 'TblMember';
            $modelAttachment->module_code = '13';
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $modelAttachment->remarks = 'Documents';
            $modelAttachment->attachment_type = $ext;
            // save thumbnail
            $imagePath = Yii::getAlias('@webroot') . 'web/upload/images/';
            $thumbnail_path = $imagePath . 'thumbnail';
            $thumbnail_base_path = Yii::$app->urlManager->createAbsoluteUrl('') . 'web/upload/images/' . 'thumbnail';
            if (Yii::$app->general->checkDirectory($thumbnail_path)) {
                list($width, $height) = getimagesize($file);
                $data = Image::thumbnail($file, 60, 60)->save($thumbnail_path . '/' . $file, ['quality' => 100]);
                $modelAttachment->thumbnail = $thumbnail_base_path . '/' . $file;
            }
            $child[] = $modelAttachment;
            $auto_inc++;
        }
    }

}
