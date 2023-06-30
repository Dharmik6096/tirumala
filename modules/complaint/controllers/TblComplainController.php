<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplain;
use app\modules\complaint\models\TblComplainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\complaint\models\TblComplainProblem;
use yii\helpers\Json;
use app\modules\complaint\models\TblComplainActivity;
use yii\web\Response;
use app\modules\complaint\models\TblComplainType;
use app\modules\assetmanagement\models\TblAssetMaster;
use app\modules\complaint\models\TblComplainHistory;
use app\modules\complaint\models\TblComplainActivityHistory;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\sms\models\TblAlertNotification;
use app\modules\general\models\TblAttachment;
use app\modules\usermanagement\models\User;

/**
 * TblComplainController implements the CRUD actions for TblComplain model.
 */
class TblComplainController extends \app\controllers\ChildController {

    public $freeAccessActions = ['problem-list', 'get-asset'];

    /**
     * Lists all TblComplain models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblComplainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblComplain model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblComplain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblComplain();
        $this->model->scenario = 'portal_create_complaint';
        $complaint_activity_model = new TblComplainActivity();
        $this->viewFile = 'create';
        $this->model->complain_datetime = date('Y-m-d H:i:s');
        $this->model->complain_status = 'CREATED'; //create
        $this->model->entry_type = 'PORTAL';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            if ($this->model->validate()) {
                $saveModel = [];
                $saveModel[] = $this->model;
                $this->setComplaintActivityModel($complaint_activity_model, 'CREATED');
                $saveModel[] = $complaint_activity_model;
                $auto_key_config['TblComplainActivity'][] = ['self_key' => 'complain_code', 'parent_key' => 'complain_code', 'parent_index' => 0];
                $attachment = Yii::$app->request->post()['TblAttachment'];
                if (!empty($attachment['attachment'])) {
                    $complain_attachment = new TblAttachment();
                    $complain_attachment->load(Yii::$app->request->post());
                    $complain_attachment->module_name = 'tbl_complain';
                    $ext = (explode(".", $complain_attachment->attachment));
                    $complain_attachment->attachment_type = $ext[1];
                    $saveModel[] = $complain_attachment;
                    $auto_key_config['TblAttachment'][] = ['self_key' => 'module_code', 'parent_key' => 'complain_code', 'parent_index' => 0];
                }
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Complain', 'create'], $auto_key_config);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblComplain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $saveModel = [];
            $this->model->load(Yii::$app->request->post());
            $historyModel = new TblComplainHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $saveModel[] = $this->model;
            $saveModel[] = $historyModel;
            $attachment = Yii::$app->request->post()['TblAttachment'];
            if (!empty($attachment['attachment_code'])) {
                $attachment_model = TblAttachment::find($attachment['attachment_code'])->one();
                $attachment_model->load(Yii::$app->request->post());
                $ext = (explode(".", $attachment_model->attachment));
                $attachment_model->attachment_type = $ext[1];
                $saveModel[] = $attachment_model;
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Complain', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    public function customRender() {
        $complain_attachment = new TblAttachment();
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'complainAttachment' => $complain_attachment]
        );
    }

    /**
     * Deletes an existing TblComplain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $saveModel = [];
        $deleteModel = [];
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblComplainHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $model = TblComplainActivity::find()->where(['complain_code' => Yii::$app->request->post('id')])->one();
        $activityHistoryModel = new TblComplainActivityHistory();
        Yii::$app->operation->history($model, $activityHistoryModel, DELETE);
        $deleteModel[] = $model;
        $saveModel[] = $activityHistoryModel;

        $attachmentModel = TblAttachment::find()->where(['module_code' => Yii::$app->request->post('id')])->one();
        if (!empty($attachmentModel)) {
            $deleteModel[] = $attachmentModel;
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Complain', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the TblComplain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblComplain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblComplain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function setModel() {
        $this->model->complain_datetime = ($this->model->complain_datetime == '') ? null : $this->model->complain_datetime;
        $this->model->resolved_datetime = ($this->model->resolved_datetime == '') ? null : date('Y-m-d H:i:s');
        $this->model->complain_status_datetime = date('Y-m-d H:i:s');
    }

    private function setComplaintActivityModel($complaint_activity_model, $status) {
        $complaint_activity_model->complain_code = $this->model->complain_code;
        $complaint_activity_model->activity_type = $status;
        $complaint_activity_model->remarks = $this->model->remarks;
        $complaint_activity_model->entry_type = 'PORTAL';
        $complaint_activity_model->user_code = $this->model->user_code;
    }

    public function actionProblemList() {
        $complains = [];
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $complain_for = TblComplainType::find()->select('complain_for')->where(['complain_type_code' => $parents[0], 'is_active' => 1])->one();
                if (!empty($complain_for)) {
                    if ($complain_for->complain_for == 'general_complain') {
                        $complains = TblComplainProblem::find()->where(['or', ['asset_code' => null], ['asset_code' => '']])->all();
                    } else {
                        $complains = TblComplainProblem::find()->where(['is not', 'asset_code', null])->andWhere(['!=', 'asset_code', ''])->all();
                    }
                    foreach ($complains as $key => $val) {
                        $out[] = array('id' => $val->complain_problem_code, 'name' => $val->problem_desc);
                    }
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
        return;
    }

    public function actionGetComplainFor() {
        $complain_type = Yii::$app->request->post()['complain_type'];
        $complain_for = TblComplainType::find()->select('complain_for')->where(['complain_type_code' => $complain_type, 'is_active' => 1])->one();
        $record = ['status' => 'success', 'msg' => $complain_for];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionGetSrNumber() {
        $asset_code = Yii::$app->request->post()['asset_code'];
        $sno = TblAssetMaster::getSrNo($asset_code);
        $record = ['status' => 'success', 'msg' => $sno];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionAssignComplain($complain_code = '') {
        $this->model = new TblComplain();
        $this->model = $this->findModel($complain_code);
        $child = [];
        $historyModel = new TblComplainHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->scenario = 'assign_complain';
        $notificationSent = true;
        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
            $master = [];
            $appLoginModel = new User();
            $mobileNo = Yii::$app->general->getforeignkey($this->model->contactDetailsCodes, 'mobile_no');
            $appLoginModel->mobile_no = !empty($mobileNo) && $mobileNo != 'N/A' ? $mobileNo : '';
            $appLoginModelData = $appLoginModel->getLoginDetails();

            if (!empty($appLoginModelData)) {
                $recType = 'APP_NOTIFICATION';
                $moduleType = 'Complain Assignment';
                $apiMaster = new TblApiMaster();
                $apiMasterData = $apiMaster->getRecord($recType, $this->model->union_code);

                if (!empty($apiMasterData)) {
                    $alertTemplate = new TblAlertTemplate();
                    $alertTemplate->module_type = $moduleType;
                    $alertTemplate->receiver_type = $recType;
                    $alertTemplate->union_code = $this->model->union_code;
                    $alertTemplateData = $alertTemplate->getRecord();

                    if (!empty($alertTemplateData)) {
                        $alertNotification = new TblAlertNotification();
                        $alertNotification->receiver_detail = $appLoginModelData->device_id;
                        $alertNotification->receiver_type = $recType;
                        $masterDetail = '';
                        if (!empty($this->model->dcs_code)) {
                            $masterDetail = Yii::t('app', 'DCS') . '(' . Yii::$app->general->getforeignkey($this->model->dcsCode, 'dcs_name') . ' - ' . $this->model->dcs_code . ')';
                        } else if (!empty($this->model->mcc_plant_code)) {
                            $masterDetail = Yii::t('app', 'MCC') . '(' . Yii::$app->general->getforeignkey($this->model->mccPlantCode, 'name') . ' - ' . $this->model->mcc_plant_code . ')';
                        } else if (!empty($this->model->plant_code)) {
                            $masterDetail = Yii::t('app', 'Plant') . '(' . Yii::$app->general->getforeignkey($this->model->plantCode, 'name') . ' - ' . $this->model->plant_code . ')';
                        } else if (!empty($this->model->bmc_code)) {
                            $masterDetail = Yii::t('app', 'BMC') . '(' . Yii::$app->general->getforeignkey($this->model->plantCode, 'bmc_name') . ' - ' . $this->model->bmc_code . ')';
                        }
                        $alertNotification->message = str_replace('{master_detail}', $masterDetail, $alertTemplateData->message);
                        $alertNotification->header_info = str_replace('{complain_no}', $this->model->complain_code, $alertTemplateData->header_info);
                        $alertNotification->send_status = 0;
                        $alertNotification->content_id = $apiMasterData->api_master_id;
                        $alertNotification->refecence_code = $this->model->complain_code;
                        $alertNotification->module_type = $moduleType;
                        $alertNotification->entry_datetime = date('Y-m-d H:i:s');
                        $master[] = $alertNotification;
                    } else {
                        $notificationSent = false;
                    }
                } else {
                    $notificationSent = false;
                }
            } else {
                $notificationSent = false;
            }

            $complaint_activity_model = new TblComplainActivity();
            $this->setModel($this->model);
            $activityModel = TblComplainActivity::find()->where(['complain_code' => $this->model->complain_code, 'activity_type' => 'ASSIGN'])->orderBy('complain_activity_code', 'desc')->one();
            $status = 'ASSIGN';
            if (!empty($activityModel)) {
                $status = 'RE-ASSIGN';
            }
            $this->setComplaintActivityModel($complaint_activity_model, $status);
            $this->model->complain_status = 'INPROGRESS';
            $this->model->complain_assignment_datetime = date('Y-m-d H:i:s');

            $master[] = $this->model;
            $master[] = $complaint_activity_model;
            $child[] = $historyModel;

            $transaction = $this->generalModel->saveTransaction($master, $child, ['Complain Assign', 'create']);
            if ($transaction == 'customRedirect') {
                if (!$notificationSent) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'message' => Yii::t('app', 'Complain successfully assigned and notification is not generated.'),
                    ]);
                }
            }
            return $this->redirect(['index']);
        }
        return $this->renderAjax('assign_complain', [
                    'model' => $this->model
        ]);
    }

    public function actionUploadFile() {
        $path = Yii::$app->params['complaint_dir_path'];
        if (!is_dir($path)) {
            \yii\helpers\FileHelper::createDirectory($path, $mode = 0777, $recursive = true);
        }

        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $fn = $this->uploadFile($file);
            if ($file->saveAs($path . $fn)) {
                $record = ['status' => 'success', 'msg' => $fn];
            } else {
                $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function uploadFile($attachment) {
        if (isset($attachment)) {
            // store the source file name
//            $this->attachment = $attachment->name;
            $ext = (explode(".", $attachment->name));
            // generate a unique file name
            $files = \yii\helpers\FileHelper::findFiles(Yii::$app->params['complaint_dir_path']);
            if (isset($files[0])) {
                foreach ($files as $index => $file) {
                    $fileName = substr($file, strrpos($file, '/') + 2);
                    if ($attachment->name == $fileName) {
                        $fn = explode('.', $fileName);
                        $fn = $fn[0] . '(' . ($index + 1) . ').' . $fn[1];
                    }
                }
                return isset($fn) ? $fn : $attachment->name;
            }
            return Yii::$app->params['complaint_dir_path'] . $attachment->name;
        }
    }

    public function actionRemove() {
        $old_attachment = Yii::$app->request->post('value');
        $path = Yii::$app->params['complaint_dir_path'];
        if (!empty($old_attachment)) {
            if (file_exists($path . $old_attachment)) {
                if ($old_attachment != Yii::$app->request->post('old_value')) {
                    unlink($path . $old_attachment);
                }
                return 1;
            }
        } else {
            return 0;
        }
    }

}
