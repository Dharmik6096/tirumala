<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplain;
use app\modules\complaint\models\TblComplainSearch;
use yii\web\NotFoundHttpException;
use app\modules\complaint\models\TblComplainProblem;
use yii\helpers\Json;
use app\modules\complaint\models\TblComplainActivity;
use yii\web\Response;
use app\modules\complaint\models\TblComplainType;
use app\modules\assetmanagement\models\TblAssetMaster;
use app\modules\complaint\models\TblComplainHistory;
use app\modules\complaint\models\TblComplainActivityHistory;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\sms\models\TblAlertNotification;
use app\modules\document\models\TblAttachment;
use app\modules\usermanagement\models\User;
use app\modules\document\models\TblAttachmentHistory;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplainActivitySearch;
use app\modules\assetmanagement\models\TblAssetBom;
use app\modules\collection\models\TblAllowManualCollectionRange;
use app\modules\collection\models\TblAllowManualCollectionRangeHistory;
use app\modules\complaint\models\TblComplainSpare;
use yii\widgets\ActiveForm;
use app\modules\complaint\models\TblComplainEscalationTxnDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;

/**
 * TblComplainController implements the CRUD actions for TblComplain model.
 */
class TblComplainController extends \app\controllers\ChildController {

    public $freeAccessActions = ['problem-list', 'assign-list'];

    public function init() {
        parent::init();
        $this->enableCsrfValidation = FALSE;
    }

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
        $searchModel = new TblComplainActivitySearch();
        $searchModel->complain_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $complain_attachment = new TblAttachment();
        $attachmentDataProvider = new ActiveDataProvider([
            'query' => $complain_attachment->find()->where(['module_code' => (string) $id, 'module_name' => 'tbl_complain']),
        ]);
        $complain_escalation_txn = new TblComplainEscalationTxnDetail();
        $escalationTxnDataProvider = new ActiveDataProvider([
            'query' => $complain_escalation_txn->find()->where(['complain_code' => $id]),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'complain_attachment' => $complain_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider,
                    'complain_escalation_txn' => $complain_escalation_txn,
                    'escalationTxnDataProvider' => $escalationTxnDataProvider,
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
            if ($this->model->asset_code) {
                $assetsr = explode('##', $this->model->asset_code);
                $this->model->asset_code = $assetsr[0];
            }
            $this->setModel($this->model);
            if ($this->model->validate()) {
                $saveModel = [];
                if (!empty($this->model->from_date) && !empty($this->model->from_shift)) {
                    $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
                }
                $saveModel[] = $this->model;
                $i = 0;
                $this->setComplaintActivityModel($complaint_activity_model, 'CREATED');
                $saveModel[] = $complaint_activity_model;
                $i++;
                $auto_key_config[$i] = ['self_key' => 'complain_code', 'parent_key' => 'complain_code', 'parent_index' => 0];
                $attachmentString = Yii::$app->request->post()['attachment'];
                if (!empty($attachmentString)) {
                    $attachments = explode(',', $attachmentString);
                    foreach ($attachments as $atta) {
                        if (!empty($atta)) {
                            $complain_attachment = new TblAttachment();
                            $complain_attachment->load(Yii::$app->request->post());
                            $complain_attachment->module_name = 'tbl_complain';
                            $ext = (explode(".", $atta));
                            $file = Yii::$app->urlManager->createAbsoluteUrl('') . Yii::$app->params['complaint_dir_path'] . $atta;
                            $complain_attachment->attachment = $file;
                            $complain_attachment->file_name = $atta;
                            $complain_attachment->attachment_type = $ext[1];
                            $complain_attachment->remarks = $this->model->remarks;
                            $saveModel[] = $complain_attachment;
                            $i++;
                            $auto_key_config[$i] = ['self_key' => 'module_code', 'parent_key' => 'complain_code', 'parent_index' => 0];
                        }
                    }
                }
                if ($this->model->location_type == 3 && $this->model->complain_for == 'asset_complain' && !empty($this->model->collection_request_type) && !empty($this->model->from_date) && !empty($this->model->from_shift) && Tblassetmaster::find()->select('asset_type_code')->where(['asset_code' => $this->model->asset_code, 'is_active' => 1])->scalar() == '1') {
                    $allowManualCollectionRangeModel = new TblAllowManualCollectionRange();
                    $allowManualCollectionRangeModel->scenario = 'create';
                    $this->setAllowManualCollectionRangeModel($allowManualCollectionRangeModel);
                    $allowManualCollectionRangeModel->setManualCollectionData($saveModel, $auto_key_config, $i, true);
                    if (!$allowManualCollectionRangeModel->validate()) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return Json::encode(ActiveForm::validate($allowManualCollectionRangeModel));
                    }
                }
                $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($saveModel, ['Complain', 'create'], $auto_key_config);
                if ($transaction !== FALSE) {
                    $user_code = '';
                    $LastInsertedId = $this->model->complain_code;
                    $sp_param = [];
                    $sp_name = 'Proc_Insert_complain_escalation_txn_detail';
                    $sp_param[] = $LastInsertedId;
                    $result = \Yii::$app->general->getSpData($sp_name, $sp_param);
                    foreach ($result as $res) {
                        if (!empty($res['user_code'])) {
                            $user_code = $res['user_code'];
                        }
                    }
                    if ($user_code != '') {
                        $sp_param = [];
                        $sp_name = 'Proc_task_activity';
                        $sp_param[] = $this->model->union_code;
                        $sp_param[] = $LastInsertedId;
                        $sp_param[] = NULL;
                        $sp_param[] = $user_code;
                        $sp_param[] = NULL;
                        $result = \Yii::$app->general->getSpData($sp_name, $sp_param);
                        foreach ($result as $res) {
                            if ($res['retuns_value'] == 1 || $res['retuns_value'] == true) {
                                if (!empty($res['task_activity_code'])) {
                                    $txnDetail = TblComplainEscalationTxnDetail::find()->where(['complain_code' => $LastInsertedId, 'status' => 'Allocated'])->one();
                                    if (!empty($txnDetail)) {
                                        $txnDetail->task_activity_code = $res['task_activity_code'];
                                        $txnDetail->save();
                                    }
                                }
                            }
                        }
                    }
                    return $this->{$transaction}();
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        $complain_attachment = new TblAttachment();
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'complainAttachment' => $complain_attachment,
                        ]
        );
    }

    /**
     * Updates an existing TblComplain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'portal_update_complaint';
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $saveModel = [];
            $historyModel = new TblComplainHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $saveModel[] = $historyModel;
            $this->model->load(Yii::$app->request->post());
            if ($this->model->validate()) {
                $complianData = Yii::$app->request->post()['TblComplain'];
                if ($complianData['complain_for'] != 'general_complain' && isset($complianData['asset_code'])) {
                    $assetsr = explode('##', $this->model->asset_code);
                    $this->model->asset_code = $assetsr[0];
                } else {
                    $this->model->asset_code = '';
                    $this->model->serial_number = '';
                }
                $saveModel[] = $this->model;

                $attachmentString = Yii::$app->request->post()['attachment'];
                if (!empty($attachmentString)) {
                    $attachments = explode(',', $attachmentString);
                    foreach ($attachments as $atta) {
                        if (!empty($atta)) {
                            $complain_attachment = new TblAttachment();
                            $complain_attachment->load(Yii::$app->request->post());
                            $complain_attachment->module_name = 'tbl_complain';

                            $complain_attachment->module_code = $this->model->complain_code;
                            $ext = (explode(".", $atta));
                            $file = Yii::$app->urlManager->createAbsoluteUrl('') . Yii::$app->params['complaint_dir_path'] . $atta;
                            $complain_attachment->attachment = $file;
                            $complain_attachment->file_name = $atta;
                            $complain_attachment->attachment_type = $ext[1];
                            $complain_attachment->remarks = $this->model->remarks;
                            $saveModel[] = $complain_attachment;
                        }
                    }
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, ['Complain', 'edit']);

                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }

        $complain_attachment = new TblAttachment();

        $dataProvider = new ActiveDataProvider([
            'query' => $complain_attachment->find()->where(['module_code' => (string) $this->model->complain_code]),
        ]);

        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'complainAttachment' => $complain_attachment,
                    'dropdownSerialNo' => [],
                    'dataProvider' => $dataProvider,
                        ]
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

        $attachmentModel = TblAttachment::find()->where(['module_code' => Yii::$app->request->post('id')])->all();
        foreach ($attachmentModel as $key => $id) {
            $attachmentHistoryModel = new TblAttachmentHistory();
            Yii::$app->operation->history($id, $attachmentHistoryModel, DELETE);
            $deleteModel[] = $id;
            $saveModel[] = $attachmentHistoryModel;
        }

        $manualCollectionModel = TblAllowManualCollectionRange::find()->where(['complain_code' => Yii::$app->request->post('id')])->one();
        if (!empty($manualCollectionModel)) {
            $manualCollectionHistoryModel = new TblAllowManualCollectionRangeHistory();
            Yii::$app->operation->history($manualCollectionModel, $manualCollectionHistoryModel, DELETE);
            $deleteModel[] = $manualCollectionModel;
            $saveModel[] = $manualCollectionHistoryModel;
            $processApprovalModel = TblProcessApproval::find()->where(['process_code' => (string) $manualCollectionModel->allow_manual_collection_code, 'process_name' => 'tbl_allow_manual_collection_range'])->all();
            if (!empty($processApprovalModel)) {
                foreach ($processApprovalModel as $key => $id) {
                    $detailHistory = new TblProcessApprovalHistory();
                    Yii::$app->operation->history($id, $detailHistory, DELETE);
                    $deleteModel[] = $processApprovalModel[$key];
                    $saveModel[] = $detailHistory;
                }
            }
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
        $this->model->complain_status_datetime = date('Y-m-d H:i:s');
    }

    private function setComplaintActivityModel($complaint_activity_model, $status) {
        $complaint_activity_model->complain_code = $this->model->complain_code;
        $complaint_activity_model->activity_type = $status;
        $complaint_activity_model->remarks = $this->model->remarks;
        $complaint_activity_model->entry_type = 'PORTAL';
        $complaint_activity_model->user_code = $this->model->user_code;
        $complaint_activity_model->user_code = isset($this->model->user_code) ? $this->model->user_code : Yii::$app->session['UserCode'];
        $complaint_activity_model->union_code = $this->model->union_code;
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
        $sno = [];
        $assetTypeCode = false;
        if ($asset_code) {
            $assetsr = explode('##', $asset_code);
            $to_code = Yii::$app->request->post()['code'];
            $sno = TblAssetMaster::getSrNo($to_code, $assetsr[0], $assetsr[1]);
            $assetTypeCode = Yii::$app->request->post()['dcs'] ? Tblassetmaster::find()->where(['asset_code' => $assetsr[0], 'asset_type_code' => 1, 'is_active' => 1])->exists() : false;
        }
        $record = ['status' => 'success', 'msg' => $sno, 'assetTypeCode' => $assetTypeCode];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionAssignComplain($complain_code = '') {
        $this->model = new TblComplain();
        $this->model = $this->findModel($complain_code);
        $historyModel = new TblComplainHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->scenario = 'assign_complain';
        $complianUser = $this->model->user_code;
        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
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
            $master[] = $historyModel;

            $escalationModel = new TblComplainEscalationTxnDetail();
            $escalationMatrix = $escalationModel->updateAll(['escalation_message' => 'Not Escalated'], ['complain_code' => $this->model->complain_code, 'status' => 'pending']);

            $spCall = [];
            if (!empty($this->model->user_code)) {
                $sp_param = [];
                $sp_name = 'Proc_task_activity';
                $sp_param[] = $this->model->union_code;
                $sp_param[] = $this->model->complain_code;
                $sp_param[] = !empty($complianUser) ? $complianUser : NULL;
                $sp_param[] = $this->model->user_code;
                if (!empty($this->model->resolved_status)) {
                    $sp_param[] = $this->model->resolved_status;
                } else {
                    $sp_param[] = NULL;
                }
                $spCall[] = [$sp_name, $sp_param];
            }

            $transaction = $this->generalModel->saveTransactionWithSp($master, $spCall, ['Complain Assign', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('assign_complain', [
                    'model' => $this->model,
        ]);
    }

    public function setNotification(&$notificationSent, $complain_code) {
        $txnDetail = TblComplainEscalationTxnDetail::find()->where(['complain_code' => $complain_code, 'status' => 'Allocated'])->one();
        if (!empty($txnDetail->device_id)) {
            $recType = 'APP_NOTIFICATION';
            $moduleType = 'Complain';
            $apiMaster = new TblApiMaster();
            $apiMasterData = $apiMaster->getRecord($recType, $this->model->union_code, '1');
            if (!empty($apiMasterData)) {
                $alertTemplate = new TblAlertTemplate();
                $alertTemplate->module_type = $moduleType;
                $alertTemplate->receiver_type = $recType;
                $alertTemplate->union_code = $this->model->union_code;
                $alertTemplateData = $alertTemplate->getRecord();
                if (!empty($alertTemplateData)) {
                    $alertNotification = new TblAlertNotification();
                    if (!empty($txnDetail)) {
                        $alertNotification->receiver_detail = $txnDetail->device_id;
                    }
                    $alertNotification->receiver_type = $recType;
                    $alertNotification->message = str_replace('{complain_no}', $this->model->complain_code, $alertTemplateData->message);
                    $alertNotification->message = str_replace('{complain_status}', $this->model->complain_status, $alertNotification->message);
                    $alertNotification->header_info = str_replace('{complain_no}', $this->model->complain_code, $alertTemplateData->header_info);
                    $alertNotification->send_status = 0;
                    $alertNotification->content_id = $apiMasterData->api_master_id;
                    $alertNotification->refecence_code = $this->model->complain_code;
                    $alertNotification->module_type = $moduleType;
                    $alertNotification->entry_datetime = date('Y-m-d H:i:s');
                    $alertNotification->save();
                } else {
                    $notificationSent = false;
                }
            } else {
                $notificationSent = false;
            }
        } else {
            $notificationSent = false;
        }
    }

    public function actionUploadFile() {
        $path = Yii::$app->params['complaint_dir_path'];
        if (!is_dir($path)) {
            Yii::$app->general->CreateDirectory($path);
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
            return $attachment->name;
        }
    }

    public function actionResolveComplain($id) {

        $this->model = $this->findModel($id);
        $this->viewFile = 'resolve_complain';
        $this->model->scenario = 'portal_resolve_complaint';
        $complianUser = $this->model->user_code;
        $complain_spare = new TblComplainSpare();
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            if ($this->model->validate()) {
                if ($this->model->asset_code) {
                    $assetsr = explode('##', $this->model['asset_code']);
                    $this->model->asset_code = $assetsr[0];
                }
                $saveModel = [];
                $historyModel = new TblComplainHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $saveModel[] = $historyModel;

                $this->model->complain_status = 'RESOLVED';
                $this->model->complain_status_datetime = date('Y-m-d H:i:s');
                $this->model->resolved_datetime = date('Y-m-d H:i:s');
                $saveModel[] = $this->model;

                $complaint_activity_model = new TblComplainActivity();
                $this->setComplaintActivityModel($complaint_activity_model, 'SERVICE');
                $complaint_activity_model->remarks = $this->model->resolved_remarks;
                $saveModel[] = $complaint_activity_model;

                $attachment = Yii::$app->request->post()['attachment'];
                if (!empty($attachment)) {
                    $attachments = explode(',', $attachment);
                    foreach ($attachments as $atta) {
                        if (!empty($atta)) {
                            $complain_attachment = new TblAttachment();
                            $complain_attachment->load(Yii::$app->request->post());
                            $complain_attachment->module_name = 'tbl_complain';
                            $complain_attachment->module_code = $this->model->complain_code;
                            $ext = (explode(".", $atta));
                            $file = Yii::$app->urlManager->createAbsoluteUrl('') . Yii::$app->params['complaint_dir_path'] . $atta;
                            $complain_attachment->attachment = $file;
                            $complain_attachment->file_name = $atta;
                            $complain_attachment->attachment_type = $ext[1];
                            $complain_attachment->remarks = $this->model->remarks;
                            $saveModel[] = $complain_attachment;
                        }
                    }
                }
                $spare = [];
                $spCall = [];
                if ($this->model->spare_required == 1) {
                    if (!empty(Yii::$app->request->post()['tblcomplainspare'])) {
                        $spare = Yii::$app->request->post()['tblcomplainspare'];
                        if (!empty($spare)) {
                            foreach ($spare as $spare_list) {
                                $complain_spare = new TblComplainSpare();
                                $complain_spare->complain_code = $this->model->complain_code;
                                $complain_spare->spare_code = $spare_list['spare_code'];
                                $complain_spare->old_serial_no = $spare_list['old_serial_no'];
                                $complain_spare->old_spare_status = $spare_list['old_spare_status'];
                                $complain_spare->new_serial_no = $spare_list['new_serial_no'];
                                $complain_spare->qty = $spare_list['qty'];
                                $saveModel[] = $complain_spare;

                                $sp_param = [];
                                $sp_name = 'Proc_complain_asset';
                                $sp_param[] = $this->model->complain_code;
                                if ($this->model->asset_code) {
                                    $assetsr = explode('##', $this->model->asset_code);
                                    $sp_param[] = $assetsr[0];
                                } else {
                                    $sp_param[] = NULL;
                                }
                                $sp_param[] = $this->model->plant_code;
                                $sp_param[] = $this->model->mcc_plant_code;
                                $sp_param[] = $this->model->bmc_code;
                                $sp_param[] = $this->model->dcs_code;
                                $sp_param[] = $this->model->spare_required;
                                $sp_param[] = $spare_list['spare_code'];
                                $sp_param[] = $this->model->resolved_status;
                                $sp_param[] = $spare_list['new_serial_no'];
                                $sp_param[] = $spare_list['old_serial_no'];
                                $spCall[] = [$sp_name, $sp_param];
                            }
                        }
                    }
                } else {
                    $sp_param = [];
                    $sp_name = 'Proc_complain_asset';
                    $sp_param[] = $this->model->complain_code;
                    if ($this->model->asset_code) {
                        $assetsr = explode('##', $this->model->asset_code);
                        $sp_param[] = $assetsr[0];
                    } else {
                        $sp_param[] = NULL;
                    }
                    $sp_param[] = $this->model->plant_code;
                    $sp_param[] = $this->model->mcc_plant_code;
                    $sp_param[] = $this->model->bmc_code;
                    $sp_param[] = !empty($this->model->dcs_code) ? $this->model->dcs_code : NULL;
                    $sp_param[] = $this->model->spare_required;
                    $sp_param[] = NULL;
                    $sp_param[] = $this->model->resolved_status;
                    $sp_param[] = $this->model->new_serial_no;
                    $sp_param[] = NULL;
                    $spCall[] = [$sp_name, $sp_param];
                }

                if (!empty($this->model->resolved_status == 'replace')) {
                    $sp_param = [];
                    $sp_name = 'Proc_task_activity';
                    $sp_param[] = $this->model->union_code;
                    $sp_param[] = $this->model->complain_code;
                    $sp_param[] = !empty($complianUser) ? $complianUser : NULL;
                    $sp_param[] = $this->model->user_code;
                    $sp_param[] = $this->model->resolved_status;
                    $spCall[] = [$sp_name, $sp_param];
                }
                $transaction = $this->generalModel->saveTransactionWithSp($saveModel, $spCall, ['Complain Activity', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['tbl-complain/index']);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        $sp_param = [];
        $sp_name = 'sp_serial_no_list';
        $sp_param[] = $this->model->location_type;
        $sp_param[] = $this->model->plant_code;
        $sp_param[] = $this->model->bmc_code;
        $sp_param[] = $this->model->dcs_code;
        if ($this->model->asset_code) {
            $assetsr = explode('##', $this->model->asset_code);
            $sp_param[] = $assetsr[0];
        } else {
            $sp_param[] = NULL;
        }
        $srNos = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $serialNo = [];
        if (!empty($srNos)) {
            $serialNo = array_column($srNos, 'serial_number');
            $serialNo = array_combine($serialNo, $serialNo);
        }
        $complain_attachment = new TblAttachment();
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'complainAttachment' => $complain_attachment,
                    'dropdownSerialNo' => $serialNo,
                    'complain_spare' => $complain_spare,
                        ]
        );
    }

    public function actionAttachmentDelete() {
        $attachment = Yii::$app->request->post('id');
        $savedelModel = [];
        if (!empty($attachment)) {
            $attachmentModel = TblAttachment::find()->where(['attachment_code' => $attachment])->one();
            if (!empty($attachmentModel)) {
                $attachmentHistoryModel = new TblAttachmentHistory();
                Yii::$app->operation->history($attachmentModel, $attachmentHistoryModel, DELETE);
                $savedelModel[] = $attachmentModel;
                $savedelModel[] = $attachmentHistoryModel;
            }
            $record = $this->generalModel->deleteTransaction($savedelModel);
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionAssignList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $user = new User();
                $data = $user->getAssignList($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionSpareIsSerial() {
        $data = [];
        $data['status'] = 'error';
        if (!empty($_POST)) {
            $asset = $_POST['asset_code'] ? explode('##', $_POST['asset_code']) : '';
            $asset_code = !empty($asset) ? $asset[0] : '';
            $model = new TblAssetBom();
            $model->spare_code = !empty($_POST['spare_code']) ? $_POST['spare_code'] : '';
            $model->asset_code = $asset_code;
            $asset_serial = $model->getAssetData();

            if (!empty($asset_serial)) {
                $data['status'] = 'success';
                $data['is_serial_number'] = $asset_serial->is_serial_number;
            }
        }
        return Json::encode($data);
    }

    private function setAllowManualCollectionRangeModel($allowManualCollectionRangeModel) {
        $allowManualCollectionRangeModel->union_code = $this->model->union_code;
        $allowManualCollectionRangeModel->plant_code = $this->model->plant_code;
        $allowManualCollectionRangeModel->mcc_plant_code = $this->model->plant_code;
        $allowManualCollectionRangeModel->bmc_code = $this->model->bmc_code;
        $allowManualCollectionRangeModel->dcs_code = $this->model->dcs_code;
        $allowManualCollectionRangeModel->from_date = $allowManualCollectionRangeModel->to_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
        $allowManualCollectionRangeModel->from_shift = $allowManualCollectionRangeModel->to_shift = $this->model->from_shift;
        $allowManualCollectionRangeModel->is_weight_manual = $allowManualCollectionRangeModel->is_quality_manual = 1;
        $allowManualCollectionRangeModel->remark = $this->model->remarks;
        $allowManualCollectionRangeModel->is_approved = 0;
        $allowManualCollectionRangeModel->entry_type = $this->model->collection_request_type;
        $allowManualCollectionRangeModel->table_name = 'tbl_milk_collection';
        $allowManualCollectionRangeModel->application_type = 'MOBILE';
    }

}
