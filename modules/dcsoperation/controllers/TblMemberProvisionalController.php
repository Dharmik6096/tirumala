<?php

namespace app\modules\dcsoperation\controllers;

use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollectionHistory;
use app\modules\collection\models\TblProvisionalMilkCollectionSearch;
use app\modules\dcsoperation\models\TblMember;
use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\dcsoperation\models\TblMemberProvisionalSearch;
use app\modules\dcsoperation\models\TblMemberProvisionalHistory;
use \app\modules\organisation\models\TblDcs;
use yii\web\NotFoundHttpException;
use app\modules\document\models\TblDocumentMapping;
use app\modules\document\models\TblAttachment;
use app\modules\document\models\TblAttachmentHistory;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;

/**
 * TblMemberProvisionalController implements the CRUD actions for TblMemberProvisional model.
 */
class TblMemberProvisionalController extends \app\controllers\ChildController {

    /**
     * Lists all TblMemberProvisional models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => FALSE
        ]);
    }

    /**
     * Displays a single TblMemberProvisional model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $this->model = $this->findModel($id);
        $searchModel = new TblProvisionalMilkCollectionSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->member_code = $this->model->dcs_code . $this->model->pro_ex_member_code;
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $id, 'module_name' => 'tbl_member_provisional']),
        ]);
        $dataProvider = $searchModel->search($params);
        return $this->render('view', [
                    'model' => $this->model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
        ]);
    }

    /**
     * Creates a new TblMemberProvisional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMemberProvisional();
        $this->viewFile = 'create';
        $this->setDefaultModel();
        $validate = 1;
        if (isset($this->model->scenarios()[Yii::$app->session['eiplCode']])) {
            $this->model->scenario = Yii::$app->session['eiplCode'];
        }
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->is_approved = 0;
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
            $this->model->provisional_member_code = Yii::$app->general->getUuid();
            $this->model->member_code = $this->model->getCode();
            $this->model->pro_ex_member_code = $this->model->ex_member_code;
            $this->setModel();
            $this->model->upload = 0;
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            if ($validate == 1 && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $master_model = [];
                $master_model[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($master_model, ['member provisional', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['document-upload', 'id' => $this->model->provisional_member_code]);
                }
                return $this->render('create', ['model' => $this->model]);
            }
        }
        return $this->render('create', ['model' => $this->model]);
    }

    /**
     * Updates an existing TblMemberProvisional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;
        $this->setModel();
        $this->model->scenario = 'update_provisional_member';
        $tblMember = new TblMember();
        if ($this->model->provisional_from == 'mobile_app') {
            $this->model->ex_member_code = Yii::$app->general->getMaxCode($tblMember, 'ex_member_code', $this->model->dcs_code);
        }
        if (isset($this->model->scenarios()[Yii::$app->session['eiplCode']])) {
            $this->model->scenario = Yii::$app->session['eiplCode'];
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->member_code = $this->model->getCode();
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            if ($validate == 1 && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Member Provisional', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['document-upload', 'id' => $this->model->provisional_member_code]);
                }
            }
        }
        return $this->render('update', [
                    'model' => $this->model
        ]);
    }

    /**
     * Finds the TblMemberProvisional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMemberProvisional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($provisional_member_code) {
        if (($model = TblMemberProvisional::findOne(['provisional_member_code' => $provisional_member_code])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function setDefaultModel() {
        $this->model->no_of_buffalo = $this->model->no_of_cow_cross = $this->model->no_of_cow_ind = $this->model->total_animals = 0;
    }

    private function setModel() {
        $this->model->dob = empty($this->model->dob) ? NULL : $this->model->dob;
        $this->model->member_name = ucwords($this->model->member_name);
        $this->model->ex_member_code = str_pad($this->model->ex_member_code, 4, '0', STR_PAD_LEFT);
    }

    public function actionProvisionalMilkCollectionList() {
        $data = [];
        $model = new TblMemberProvisionalSearch();
        $model = $model->find()->where(['member_code' => $_POST['member_code']])->one();
        $searchModel = new TblProvisionalMilkCollectionSearch();
        if (!empty($_POST)) {
            $params = Yii::$app->request->queryParams;
            $searchModel->member_code = $_POST['member_code'];
            $dataProvider = $searchModel->search($params);
        }
        return $this->renderAjax('provisional_milk_collection', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $model]);
    }

    public function actionDocumentUpload($id) {
        $model = $this->findModel($id);
        $doc_mapping = TblDocumentMapping::find()->where(['master_type' => 'provisional_member'])->all();
        $doc_model = [];
        foreach ($doc_mapping as $doc) {
            $attachments = $doc->uploadedDocument($doc->doc_id, $id, 'tbl_member_provisional');
            $master_doc = $doc->docId;
            $is_new_file = 0;
            if (empty($attachments)) {
                $attachments = new TblAttachment();
                $attachments->module_code = $model->provisional_member_code;
                $attachments->doc_id = $doc->doc_id;
                $is_new_file = 1;
            }
            $attachments->is_new_file = $is_new_file;
            $attachments->is_mandate = $doc->is_mandate;
            $attachments->attachment_type = $master_doc->doc_ext;
            $attachments->doc_name = $master_doc->doc_name .= ($doc->is_mandate == 1) ? ' *' : '';
            $doc_model[] = $attachments;
        }
        if (Yii::$app->request->post()) {
            $doc_path = Yii::$app->params['document_upload'] . 'provisional_member';

            if (Yii::$app->general->checkDirectory($doc_path)) {
                $member_error = '';
                $message = '';
                $error_msg = '';
                $save_model = [];
                $save_member_doc = [];
                Model::loadMultiple($doc_model, Yii::$app->request->post());
                $msg = '';
                foreach ($doc_model as $key => $d) {
                    $d->file_name = UploadedFile::getInstance($d, '[' . $key . ']file_name');
                    if (!empty($d->file_name)) {
                        $attach = TblAttachment::find()->where(['module_code' => $id, 'doc_id' => $d->doc_id])->one();
                        if (!empty($attach)) {
                            if ($d->doc_id == $attach->doc_id) {
                                $msg .= $d->doc_name . ',';
                            }
                        }
                        if (Yii::$app->request->post('request_button') === 'approve' && $d->is_new_file = 1) {
                            $member_path = Yii::$app->params['document_upload'] . 'provisional_member';
                            if (Yii::$app->general->checkDirectory($member_path)) {
                                $file_name = 'member' . '_' . $model->member_code . '_' . $doc->doc_id . '_' . time() . '.' . $d->file_name->extension;
                                $d->attachment = $member_path . '/' . $file_name;
                                if (!$d->file_name->saveAs($d->attachment)) {
                                    $error_msg .= $d->doc_name . '<br/>';
                                }
                                $d->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $d->attachment;
                                $d->file_name = $file_name;
                                $save_member_doc[] = $d;
                                $d->module_name = 'tbl_member_provisional';
                                $save_model[] = $d;
                            } else {
                                $record = ['status' => 'error', 'msg' => 'Error while create directory.'];
                            }
                        } else {
                            $file_name = 'provisional_member' . '_' . $id . '_' . $d->doc_id . '_' . time() . '.' . $d->file_name->extension;
                            $d->attachment = $doc_path . '/' . $file_name;
                            if (!$d->file_name->saveAs($d->attachment)) {
                                $error_msg .= $d->doc_name . '<br/>';
                            }
                            $d->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $d->attachment;
                            $d->module_name = 'tbl_member_provisional';
                            $d->file_name = $file_name;
                            $save_model[] = $d;
                        }
                    } else if ($d->is_mandate == 1) {
                        $error_msg .= $d->doc_name . '<br/>';
                    }
                }
                if (empty($error_msg)) {
                    if (empty($msg)) {
                        $config = Yii::$app->general->getUnionConfiguration($model->union_code, 'workflow_require', 'PORTAL');
                        $deleteModel = [];
                        if ($config == 1) {
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setApprovalData($model->union_code, 'member', $model->provisional_member_code, $save_model, $approval_stages);
                            $model->provisional_status = empty($approval_stages) ? 'Approve' : 'Register';
                            $save_model[] = $model;
                        } else {
                            if (Yii::$app->request->post('request_button') === 'approve') {
                                $model->provisional_status = 'Approve';
                                $status = 'Approve';
                                $model->scenario = 'MemberApprove';
                                $save_model[] = $model;
                                $all_doc = [];
                                $memberdoc = [];
                                if ($model->provisional_status = 'Approve') {
                                    $this->memberApprove($status, $save_model, $deleteModel, $model, $all_doc, $memberdoc, $save_member_doc, $message);
                                }
                                if (!empty($message)) {
                                    foreach ($message as $msg) {
                                        $member_error .= $msg;
                                    }
                                }
                            } else {
                                $model->is_approved = 0;
                                $model->provisional_status = 'Pending';
                                $save_model[] = $model;
                            }
                        }
                        if (empty($member_error)) {
                            $transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $deleteModel, ['Document Upload', 'create']);
                            if ($transaction == 'customRedirect') {
                                if ($model->provisional_status == 'approve') {
                                    $baseDir = Yii::$app->basePath . '/' . Yii::$app->params['document_upload'];
                                    $memberDir = $baseDir . 'member';
                                    $proMemberDir = $baseDir . 'provisional_member';
                                    for ($i = 0; $i < count($all_doc); $i++) {
                                        $fileName = basename($memberdoc[$i]);
                                        $file = $memberDir . '/' . $fileName;
                                        $upload = copy($proMemberDir . '/' . $all_doc[$i], $file);
                                        if ($upload) {
                                            if (file_exists($proMemberDir . '/' . $all_doc[$i])) {
                                                unlink($proMemberDir . '/' . $all_doc[$i]);
                                            }
                                        }
                                    }
                                }
                                $record = ['status' => 'success', 'msg' => $this->redirect(['index'])];
                            } else {
                                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                                $record = ['status' => 'error', 'msg' => $msg];
                            }
                        } else {
                            $record = ['status' => 'error', 'msg' => $member_error . ' in Member'];
                        }
                    } else {
                        $record = ['status' => 'error', 'msg' => 'Documnet already available for ' . $msg];
                    }
                } else {
                    $record = ['status' => 'error', 'msg' => 'Please Upload Following Document <br/><br/>' . $error_msg];
                }
            } else {
                $record = ['status' => 'error', 'msg' => 'Error while create directory.'];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
        $attachment = new TblAttachment();
        $dataProvider = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $model->provisional_member_code, 'module_name' => 'tbl_member_provisional']),
        ]);
        return Yii::$app->controller->render('add_document', [
                    'model' => $model,
                    'doc_model' => $doc_model,
                    'attachment' => $attachment,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPendingApproval() {
        $searchModel = new TblMemberProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TRUE);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => TRUE
        ]);
    }

    public function actionApproveMember($id) {
        $model = TblProcessApproval::findOne($id);
        $model->scenario = 'approve';
        $model_save = [];
        $deleteModel = [];
        $member_error = '';
        $message = '';
        $approvalHistoryModel = new TblProcessApprovalHistory();
        Yii::$app->operation->history($model, $approvalHistoryModel, 'UPDATE');
        $model_save[] = $approvalHistoryModel;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save[] = $model;
            if (!empty($model_save)) {
                $model->ApprovalList($model, $model_save, $status);
                $memberModel = $this->findModel($model->process_code);
                $historyModel = new TblMemberProvisionalHistory();
                Yii::$app->operation->history($memberModel, $historyModel, UPDATE);
                $model_save[] = $historyModel;
                $memberModel->provisional_status = $status;
                $memberModel->remarks = $model->remarks;
                $memberModel->scenario = 'MemberApprove';
                $model_save[] = $memberModel;
                $all_doc = [];
                $memberdoc = [];
                if ($memberModel->provisional_status == 'Approve') {
                    $this->memberApprove($status, $model_save, $deleteModel, $memberModel, $all_doc, $memberdoc, $save_member_doc = [], $message);
                }
                if (!empty($message)) {
                    foreach ($message as $msg) {
                        $member_error .= $msg;
                    }
                }
                if (empty($member_error)) {
                    $transaction = $this->generalModel->saveDeleteTransaction([], $model_save, $deleteModel, ['Member Provisional Approval', 'edit']);
                    if ($transaction == 'customRedirect') {
                        if ($memberModel->provisional_status == 'Approve') {
                            $baseDir = Yii::$app->basePath . '/' . Yii::$app->params['document_upload'];
                            $memberDir = $baseDir . 'member';
                            $proMemberDir = $baseDir . 'provisional_member';
                            for ($i = 0; $i < count($all_doc); $i++) {
                                $fileName = basename($memberdoc[$i]);
                                $file = $memberDir . '/' . $fileName;
                                $upload = copy($proMemberDir . '/' . $all_doc[$i], $file);
                                if ($upload) {
                                    if (file_exists($proMemberDir . '/' . $all_doc[$i])) {
                                        unlink($proMemberDir . '/' . $all_doc[$i]);
                                    }
                                }
                            }
                        }
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => $member_error . ' in Member']);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Member provisional already approved by other user.']);
            }
        }
        return $this->render('approve_member', [
                    'model' => $model,
        ]);
    }

    public function memberApprove($status, &$model_save, &$deleteModel, $memberModel, &$all_attachment, &$memberdoc, $save_member_doc = [], &$message) {
        if ($status == 'Approve' && $memberModel->provisional_status == 'Approve') {
            $memberModel->is_approved = 1;
            $memberModel->approved_at = date('Y-m-d H:i:s');
            $memberModel->approved_by = Yii::$app->session['UserCode'];
            if ($memberModel->is_approved = 1) {
                $tblMember = new TblMember();
                $tblMember->scenario = 'ApprovalMember';
                $tblMember->attributes = $memberModel->attributes;
                $tblMember->member_code = $tblMember->getCode();
                $historyModel = new TblMemberProvisionalHistory();
                Yii::$app->operation->history($memberModel, $historyModel, UPDATE);
                if ($tblMember->validate()) {
                    $model_save[] = $tblMember;
                    $model_save[] = $memberModel;
                    $model_save[] = $historyModel;

                    $milkCollectionData = new TblProvisionalMilkCollection();
                    $milkCollectionData = $milkCollectionData->getMilkCollectionData($memberModel->dcs_code . $memberModel->pro_ex_member_code);
                    if (!empty($milkCollectionData)) {
                        foreach ($milkCollectionData as $key => $value) {
                            $deleteModel[] = $value;
                            $tblMilkCollection = new TblMilkCollection();
                            $tblMilkCollection->attributes = $value->attributes;
                            $tblMilkCollection->member_code = $tblMember->member_code;
                            $tblMilkCollection->is_provisional = 1;
                            $tblProvisionalMilkCollectionHistory = new TblProvisionalMilkCollectionHistory();
                            Yii::$app->operation->history($value, $tblProvisionalMilkCollectionHistory, DELETE);
                            $model_save[] = $tblMilkCollection;
                            $model_save[] = $tblProvisionalMilkCollectionHistory;
                        }
                    }

                    $tblAttachment = new TblAttachment();
                    $tblAttachment = $tblAttachment->getAttachment($memberModel->provisional_member_code);
                    $doc_path = Yii::$app->params['document_upload'] . 'member';

                    if (!empty($tblAttachment)) {
                        foreach ($tblAttachment as $key => $doc) {
                            $all_attachment[] = $doc->file_name;
                            $tblAttachments = new TblAttachment();
                            $tblAttachments->attributes = $doc->attributes;
                            if (Yii::$app->general->checkDirectory($doc_path)) {
                                if (!empty($doc->file_name)) {
                                    $attach = TblAttachment::find()
                                            ->where(['module_code' => $memberModel->provisional_member_code, 'module_name' => 'tbl_member_provisional', 'doc_id' => $doc->doc_id])
                                            ->one();
                                    $extension = explode('.', $doc->file_name)[1];
                                    $file_name = 'member' . '_' . $tblMember->member_code . '_' . $doc->doc_id . '_' . time() . '.' . $extension;
                                    $attachment = $doc_path . '/' . $file_name;
                                    if (!empty($attach)) {
                                        $attachHistoryModel = new TblAttachmentHistory();
                                        Yii::$app->operation->history($attach, $attachHistoryModel, UPDATE);
                                        $attach->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $attachment;
                                        $attach->file_name = $file_name;
                                        $model_save[] = $attach;
                                        $model_save[] = $attachHistoryModel;
                                    }
                                    $tblAttachments->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $attachment;
                                    $tblAttachments->module_name = 'tbl_member';
                                    $tblAttachments->module_code = $tblMember->member_code;
                                    $tblAttachments->file_name = $file_name;
                                    $memberdoc[] = $file_name;
                                    $model_save[] = $tblAttachments;
                                }
                            } else {
                                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                    'message' => 'Error while create directory.']);
                            }
                        }
                    }
                    if (!empty($save_member_doc)) {
                        foreach ($save_member_doc as $key => $member_attach) {
                            $all_attachment[] = $member_attach->file_name;
                            $tblAttachments = new TblAttachment();
                            $tblAttachments->attributes = $member_attach->attributes;
                            if (Yii::$app->general->checkDirectory($doc_path)) {
                                $tblAttachments->module_name = 'tbl_member';
                                $tblAttachments->module_code = $tblMember->member_code;
                                $attachment = $doc_path . '/' . $member_attach->file_name;
                                $tblAttachments->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $attachment;
                                $memberdoc[] = $member_attach->file_name;
                                $model_save[] = $tblAttachments;
                            } else {
                                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                    'message' => 'Error while create directory.']);
                            }
                        }
                    }
                } else {
                    foreach ($tblMember->getErrors() as $errorkey => $value) {
                        $message = $value;
                    }
                }
            }
        }
    }

}
