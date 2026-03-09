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
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\dcsoperation\models\TblMemberAnimalType;
use app\modules\dcsoperation\models\TblMemberProvisionalAnimalDetails;
use app\modules\dcsoperation\models\TblMemberProvisionalAnimalDetailsHistory;
use app\components\ActiveForm;
use app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails;
use app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetailsHistory;
use app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetailsSearch;
use app\modules\dcsoperation\models\TblMemberProvisionalShareDetails;
use app\modules\dcsoperation\models\TblMemberProvisionalShareDetailsHistory;
use app\modules\dcsoperation\models\TblMemberProvisionalShareDetailsSearch;
use app\modules\dcsoperation\models\TblUnionShareConfig;
use app\modules\dcsoperation\models\TblMemberFamilyDetails;
use app\modules\dcsoperation\models\TblMemberAnimalDetails;
use app\modules\dcsoperation\models\TblMemberShareDetails;
use app\modules\dcsoperation\models\TblMemberProvisionalAnimalDetailsSearch;
use app\modules\dcsoperation\models\TblMemberShareDetailsHistory;
use app\modules\dcsoperation\models\TblMemberAnimalDetailsHistory;
use app\modules\dcsoperation\models\TblMemberFamilyDetailsHistory;
use app\modules\general\models\TblProcessApprovalSearch;
use Exception;
use app\modules\bkgprocess\models\TblFtpTxnLog;

/**
 * TblMemberProvisionalController implements the CRUD actions for TblMemberProvisional model.
 */
class TblMemberProvisionalController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-ex-member-code'];
    public $toEncrypt = ['MemberDOB', 'AdharNo'];

    /**
     * Lists all TblMemberProvisional models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Url::remember(Yii::$app->request->url, 'member-provisional-index');
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

        $familyMemberModel = new TblMemberProvisionalFamilyDetailsSearch();
        $familyMemberModel->provisional_member_code = $id;
        $fDataProvider = $familyMemberModel->search(Yii::$app->request->queryParams);

        $animalMemberModel = new TblMemberProvisionalAnimalDetailsSearch();
        $animalMemberModel->provisional_member_code = $id;
        $animalDataProvider = $animalMemberModel->search(Yii::$app->request->queryParams);

        $shareMemberModel = new TblMemberProvisionalShareDetailsSearch();
        $shareMemberModel->provisional_member_code = $id;
        $shareDataProvider = $shareMemberModel->search(Yii::$app->request->queryParams);

        $processApprovalModel = new TblProcessApprovalSearch();
        $processApprovalModel->process_name = 'member';
        $processApprovalModel->process_code = $id;
        $processApprovalDataProvider = $processApprovalModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                    'familyMemberModel' => $familyMemberModel,
                    'fDataProvider' => $fDataProvider,
                    'animalMemberModel' => $animalMemberModel,
                    'animalDataProvider' => $animalDataProvider,
                    'shareMemberModel' => $shareMemberModel,
                    'shareDataProvider' => $shareDataProvider,
                    'processApprovalModel' => $processApprovalModel,
                    'processApprovalDataProvider' => $processApprovalDataProvider
        ]);
    }

    public function actionViewAttachment($id) {
        $attachmentModel = new TblAttachment();
        $attachmentModel->module_code = $id;
        $attachmentModel->module_name = 'tbl_member_provisional';
        $attachments = $attachmentModel->attachmentCode;
        return $this->render('view_attachment', [
                    'attachments' => $attachments
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
        } else {
            $this->model->scenario = 'createProvisionalMember';
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
                $config = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'allow_member_other_detail', 'PORTAL');
                $transaction = $this->generalModel->saveTransaction($master_model, ['member provisional', 'create']);
                if ($transaction == 'customRedirect') {
                    if ($config == 1) {
                        return $this->redirect(['member-detail', 'id' => $this->model->provisional_member_code]);
                    } else {
                        return $this->redirect(['document-upload', 'id' => $this->model->provisional_member_code]);
                    }
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
        $dcs_code = $this->model->dcs_code;
        $tblMember = new TblMember();
        if (empty($this->model->ex_member_code)) {
            $this->model->ex_member_code = Yii::$app->general->getMaxCode($tblMember, 'ex_member_code', $this->model->dcs_code, $this->model);
        }
        if (isset($this->model->scenarios()[Yii::$app->session['eiplCode']])) {
            $this->model->scenario = Yii::$app->session['eiplCode'];
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if ($dcs_code != $this->model->dcs_code && $this->model->provisional_from != 'mobile_update') {
                $this->model->ex_member_code = Yii::$app->general->getMaxCode($tblMember, 'ex_member_code', $this->model->dcs_code, $this->model);
            }
            $this->model->member_code = $this->model->getCode();
            $operation = Yii::$app->request->post()['operation'];
            if (!empty($operation) && ($operation == 'reroute')) {
                $this->model->provisional_status = 'Reroute';
            }
            $provisionalStatus = ['Register', 'Pending', 'Inprogress', 'Reroute'];
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code', 'provisional_status'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code, $provisionalStatus]);
            if ($validate == 1 && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $config = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'allow_member_other_detail', 'PORTAL');

                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Member Provisional', 'edit']);

                if ($transaction == 'customRedirect') {
                    if (($this->model->provisional_status == 'Reroute') && ($operation == 'reroute')) {
                        return $this->redirect(['index']);
                    }

                    if ($config == 1) {
                        return $this->redirect(['member-detail', 'id' => $this->model->provisional_member_code]);
                    } else if ($config == 0 && ($this->model->provisional_status == 'Pending' || $this->model->provisional_status == 'Reroute') && ($operation != 'reroute')) {
                        return $this->redirect(['document-upload', 'id' => $this->model->provisional_member_code]);
                    } else {
                        return $this->redirect(['pending-approval']);
                    }
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
        $this->model->ex_member_code = !empty($this->model->ex_member_code) ? str_pad($this->model->ex_member_code, 4, '0', STR_PAD_LEFT) : '';
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
                $message = [];
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
                        $unlink_files = [];
                        $config = Yii::$app->general->getUnionConfiguration($model->union_code, 'workflow_require', 'PORTAL');
                        $deleteModel = [];
                        $all_doc = [];
                        $model->load(Yii::$app->request->post());
                        if ($config == 1) {
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setApprovalData($model->union_code, 'member', $model->provisional_member_code, $save_model, $approval_stages);
                            if (!empty($approval_stages)) {
                                $status = 'Register';
                                $model->scenario = 'MemberDocument';
                            }
                            $model->provisional_status = empty($approval_stages) ? 'Approve' : $status;
                            if (strtolower($model->provisional_status) == 'approve') {
                                $model->member_status = 1; //Created
                                $model->scenario = 'MemberDocument';
                            }
                            if (!empty(Yii::$app->request->post()['operation'] == 'reroute')) {
                                $provisionalModel = TblMemberProvisional::find()->where(['provisional_member_code' => $model->provisional_member_code])->one();
                                $historyModel = new TblMemberProvisionalHistory();
                                Yii::$app->operation->history($provisionalModel, $historyModel, UPDATE);
                                $save_model[] = $historyModel;
                                $model->remarks = !empty(Yii::$app->request->post()['remarks']) ? Yii::$app->request->post()['remarks'] : '';
                                $status = 'Reroute';
                                $model->provisional_status = $status;
                                $model->scenario = 'MemberReroute';
                            }
                            $save_model[] = $model;
                        } else {
                            $operation = Yii::$app->request->post('operation');
                            if (Yii::$app->request->post('request_button') === 'approve') {
                                $model->provisional_status = 'Approve';
                                $status = 'Approve';
                                if (strtolower($status) == 'approve') {
                                    $model->member_status = 1; //Created
                                }
                                $model->scenario = 'MemberApprove';
                                $save_model[] = $model;
                                $memberdoc = [];
                                $attachment = [];
                                if (strtolower($model->provisional_status) == 'approve') {
                                    $this->memberApprove($status, $save_model, $deleteModel, $model, $all_doc, $memberdoc, $save_member_doc, $message, $unlink_files, $attachment);
                                }
                                if (!empty($message)) {
                                    foreach ($message as $msg) {
                                        $member_error .= $msg;
                                    }
                                }
                            } else if (!empty($operation) && (in_array($operation, ['reject', 'reroute']))) {
                                $provisionalModel = TblMemberProvisional::find()->where(['provisional_member_code' => $model->provisional_member_code])->one();
                                $historyModel = new TblMemberProvisionalHistory();
                                Yii::$app->operation->history($provisionalModel, $historyModel, UPDATE);
                                $save_model[] = $historyModel;
                                $model->remarks = !empty(Yii::$app->request->post()['remarks']) ? Yii::$app->request->post()['remarks'] : '';
                                $status = ($operation == 'reject') ? 'Reject' : 'Reroute';
                                $model->provisional_status = $status;
                                $model->scenario = 'MemberReroute';
                                $save_model[] = $model;
                            } else {
                                $model->scenario = 'MemberDocument';
                                $model->is_approved = 0;
                                $model->provisional_status = 'Pending';
                                $save_model[] = $model;
                            }
                        }
                        if ($model->validate()) {
                            if (empty($member_error)) {
                                $transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $deleteModel, ['Document Upload', 'create']);
                                if ($transaction == 'customRedirect') {
                                    if (strtolower($model->provisional_status) == 'approve') {
                                        $model->moveFiles($unlink_files, $attachment, $memberdoc);
                                    }
                                    $record = ['status' => 'success', 'msg' => $this->redirect(['index'])];
                                } else {
                                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                                    $record = ['status' => 'error', 'msg' => $msg];
                                }
                                $searchUrl = Url::previous('member-provisional-index');
                                $record = ['status' => 'success', 'msg' => $this->redirect($searchUrl ? $searchUrl : ['index'])];
                            } else {
                                $record = ['status' => 'error', 'msg' => $member_error . ' in Member'];
                            }
                        } else {
                            $member_massege = '';
                            if (!empty($model->getErrors())) {
                                foreach ($model->getErrors() as $m) {
                                    $member_massege = $member_massege . $m[0] . '<br>';
                                }
                            }
                            $record = ['status' => 'error', 'msg' => $member_massege];
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

    public function actionApproveMember($id, $isTabApproval = 0) {
        $model = TblProcessApproval::findOne($id);
        $aproveStatus = Yii::$app->request->post('TblProcessApproval')['status'] ?? '';
        $model->scenario = ($isTabApproval == 1 && $aproveStatus == 2) ? 'approvalTabWise' : 'approve';
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
                $memberCreationPendingForSapApproval = Yii::$app->general->getUnionConfiguration($memberModel->union_code, 'member_creation_pending_for_sap_approval', 'PORTAL');
                $memberModel->member_status = 0; // Approved
                if (strtolower($status) == 'approve' && $memberCreationPendingForSapApproval == 1) {
                    $memberModel->approved_at = date('Y-m-d H:i:s');
                    $memberModel->approved_by = Yii::$app->session['UserCode'];
                }
                if (strtolower($status) == 'approve' && ($memberCreationPendingForSapApproval != '1' || $memberModel->provisional_from == 'mobile_update')) {
                    $memberModel->member_status = 1; // Created
                }
                $model_save[] = $memberModel;
                $all_doc = [];
                $memberdoc = [];
                $unlink_files = [];
                $attachments = [];
                $config = Yii::$app->general->getUnionConfigResult($memberModel->union_code, 'allow_member_other_detail');

                $validationConfig = $this->getValidationConfig($memberModel);
                $basePath = '/dcsoperation/tbl-member-provisional/';
                $isValid = true;

                foreach ($validationConfig as $action => $items) {
                    if (Yii::$app->general->checkAccess($basePath . $action)) {
                        foreach ($items as $item) {
                            $modelToValidate = $item['model'];
                            $modelToValidate->scenario = $item['scenario'];
                            if (!$modelToValidate->validate(null, false)) {
                                $isValid = false;
                            }
                        }
                    }
                }

                $memberModel->scenario = 'MemberApprove';
                if (!$memberModel->validate(null, false)) {
                    $isValid = false;
                }

                if (!$isValid) {
                    if ($shareModel->hasErrors()) {
                        foreach ($shareModel->getErrors() as $attr => $errors) {
                            foreach ($errors as $error) {
                                $memberModel->addError($attr, $error);
                            }
                        }
                    }
                }

                if ($isValid) {
                    if ($memberModel->provisional_status == 'Approve' && $memberCreationPendingForSapApproval != '1') {
                        $this->memberApprove($status, $model_save, $deleteModel, $memberModel, $all_doc, $memberdoc, $save_member_doc = [], $message, $unlink_files, $attachments);
                        if ($config == 1) {
                            $this->memberEnrollmentApprove($status, $model_save, $memberModel, $deleteModel);
                        }
                    }
                } else {
                    foreach ($memberModel->getErrors() as $errorkey => $errors) {
                        foreach ($errors as $error) {
                            $member_error .= $error . '<br/>';
                        }
                    }
                }
                if (empty($member_error)) {
                    $transaction = $this->generalModel->saveDeleteTransaction([], $model_save, $deleteModel, ['Member Provisional Approval', 'edit']);
                    if ($transaction == 'customRedirect') {
                        if ($memberModel->provisional_status == 'Approve') {
                            $memberModel->moveFiles($unlink_files, $attachments, $memberdoc);
                        }
                        $redirectUrl = ($isTabApproval == 1 ? ['pending-approval'] : Url::previous());
                        return $this->redirect($redirectUrl);
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
        if ($isTabApproval == 1) {
            $errors = [];
            foreach ($model->getErrors() as $attrErrors) {
                foreach ($attrErrors as $error) {
                    $errors[] = $error;
                }
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => implode('<br/>', $errors)]);
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('approve_member', [
                    'model' => $model,
        ]);
    }

    public function memberApprove($status, &$model_save, &$deleteModel, $memberModel, &$all_attachment, &$memberdoc, $save_member_doc = [], &$message = [], &$unlink_files = [], &$attachments = []) {
        if (strtolower($status) == 'approve' && strtolower($memberModel->provisional_status) == 'approve') {
            $memberModel->is_approved = 1;
            $memberModel->approved_at = date('Y-m-d H:i:s');
            $memberModel->approved_by = Yii::$app->session['UserCode'];
            if ($memberModel->is_approved = 1) {
                $tblMember = new TblMember();
                if ($memberModel->provisional_from == 'mobile_update') {
                    $tblMember = TblMember::find()->where(['member_code' => $memberModel->member_code])->one();
                    $memberCode = $memberModel->member_code;
                    $historyMemberModel = new TblMemberHistory();
                    Yii::$app->operation->history($tblMember, $historyMemberModel, UPDATE);
                    $model_save[] = $historyMemberModel;
                    foreach ($memberModel->attributes as $key => $value) {
                        if ($value != null && $value != '' && $tblMember->hasAttribute($key)) {
                            $tblMember->$key = $value;
                        }
                    }
                } else {
                    $tblMember->attributes = $memberModel->attributes;
                }
                $tblMember->scenario = 'ApprovalMember';

                $tblMember->is_verified = $memberModel->is_verify;
                $tblMember->member_code = ($memberModel->provisional_from == 'mobile_update') ? $memberCode : $tblMember->getCode();
                $historyModel = new TblMemberProvisionalHistory();
                Yii::$app->operation->history($memberModel, $historyModel, UPDATE);
                if ($tblMember->validate()) {
                    $model_save[] = $tblMember;
                    $model_save[] = $memberModel;
                    $model_save[] = $historyModel;
                    $deleteAttachment = [];
                    if ($memberModel->provisional_from != 'mobile_update') {
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
                    } else {
                        $deleteAttachment['module_code'] = $memberModel->member_code;
                        $deleteAttachment['module_name'] = 'tbl_member';
                    }
                    $tblAttachment = new TblAttachment();
                    $memberProvisionalCode = (string) $memberModel->provisional_member_code;
                    $tblAttachment->AttachmentSave($memberProvisionalCode, 'tbl_member_provisional', 'member', $tblMember->member_code, 'tbl_member', $all_attachment, $model_save, $memberdoc, $deleteModel, $deleteAttachment, $unlink_files, $attachments);

                    if (!empty($save_member_doc)) {
                        foreach ($save_member_doc as $key => $member_attach) {
                            $all_attachment[] = $member_attach->file_name;
                            $attachments[] = $member_attach->attachment;
                            $tblAttachments = new TblAttachment();
                            $tblAttachments->attributes = $member_attach->attributes;
                            $doc_path = Yii::$app->params['document_upload'] . 'member';
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

    public function actionMemberDetail($id) {
        $this->model = new TblMemberProvisional();
        $this->model->provisional_member_code = $id;
        $memberData = $this->model->findOne($id);
        if (!empty($memberData)) {
            $this->model = $memberData;
        }
        $animal_model = new TblMemberAnimalType();
        $animal_model->union_code = $this->model->union_code;
        $animals = $animal_model->getAnimal();
        $member_animal_model_data = [];
        $member_animal_model = new TblMemberProvisionalAnimalDetails();
        $member_animal_model->provisional_member_code = $id;

        $h_model = [];
        $msearchModel = new TblMemberProvisionalSearch();
        $msearchModel->provisional_member_code = $id;
        $mdataProvider = $msearchModel->search(Yii::$app->request->queryParams);
        $this->setAnimalModelData($animals, $member_animal_model_data, $h_model, $id);

// family detail 
        $memberFamilyDetail = new TblMemberProvisionalFamilyDetails();
        $memberFamilyDetail->provisional_member_code = $id;
        $memberFamilyDetail->scenario = 'member_family_detail';
        $memberFamilySearchModel = new TblMemberProvisionalFamilyDetailsSearch();
        $memberFamilySearchModel->provisional_member_code = $id;
        $memberFamilyDataProvider = $memberFamilySearchModel->search(Yii::$app->request->queryParams);
//
// share detail 
        $shareConfig = new TblUnionShareConfig();
        $shares = $shareConfig->getShareDetail('member', $this->model->gender_code, $this->model->union_code, $this->model->bmc_code);

        $memberShareDetail = new TblMemberProvisionalShareDetails();
        $memberShareDetail->provisional_member_code = $id;

        $memberShareDetail = TblMemberProvisionalShareDetails::find()->where(['provisional_member_code' => $id])->one();
        if (empty($memberShareDetail)) {
            $memberShareDetail = new TblMemberProvisionalShareDetails();
            $memberShareDetail->provisional_member_code = $id;
            $memberShareDetail->no_of_share_req = $shares['min_share'];
            $memberShareDetail->no_of_share_apply = $shares['max_share'];
            $memberShareDetail->admission_fee = $shares['admission_fee'];
            $memberShareDetail->payable_share_amount = $shares['max_share'] * $shares['per_share_rate'];
            $memberShareDetail->amount_payable = ($shares['max_share'] * $shares['per_share_rate']) + $shares['admission_fee'];
            $memberShareDetail->total_amount = ($shares['max_share'] * $shares['per_share_rate']) + $shares['admission_fee'];
            $memberShareDetail->per_share_rate = $shares['per_share_rate'];
        }
        if (!Yii::$app->request->post()) {
            $memberShareDetail->scenario = 'share_detail';
        }
        $this->setShareModelData($memberShareDetail, $h_model, $id);
        $this->model->scenario = 'member_detail';

//   
        if (Yii::$app->request->post()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $master_model = [];
            $post_data = Yii::$app->request->post();
            $member_details = $post_data['TblMemberProvisional'];
            $this->model->load($post_data);

            if (!empty($memberData)) {
                $historyModel = new TblMemberProvisionalHistory();
                Yii::$app->operation->history($memberData, $historyModel, UPDATE);
                $h_model[] = $historyModel;
                $memberData->attributes = $this->model->attributes;
                $memberData->setAttributes($member_details);
                $memberData->scenario = 'create_animal';
                if (!empty(Yii::$app->request->post()['operation']) && (Yii::$app->request->post()['operation'] == 'reroute')) {
                    $memberData->remarks = !empty($member_details['remarks']) ? $member_details['remarks'] : '';
                    $memberData->provisional_status = 'Reroute';
                    $memberShareDetail->scenario = 'share_detail';
                }
                $master_model[] = $memberData;
            }
            $animal_details = $post_data['TblMemberProvisionalAnimalDetails'];
            $this->setAnimalDetails($animal_details, $master_model, $member_animal_model_data);

            $memberShareDetail->load($post_data);
            $memberShareDetail->gender_code = $this->model->gender_code;
            $memberShareDetail->union_code = $this->model->union_code;
            $memberShareDetail->bmc_code = $this->model->bmc_code;
            $memberShareDetail->deposit_date = empty($memberShareDetail->deposit_date) ? NULL : Yii::$app->formatter->asDate($memberShareDetail->deposit_date, DATE_FORMAT);
            $master_model[] = $memberShareDetail;
            $msg = '';
            if (empty($memberShareDetail->getErrors()) && empty($this->model->getErrors()) && empty($member_animal_model->getErrors()) && $memberShareDetail->validate() && $member_animal_model->validate() && $this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction($master_model, $h_model, ['member provisional', 'create']);
                $msg = '';

                if ($transaction == 'customRedirect') {
                    if ($memberData->provisional_status == 'Reroute' && Yii::$app->request->post('operation') == 'reroute') {
                        return $this->redirect(['index']);
                    } else {
                        return $this->redirect(['document-upload', 'id' => $this->model->provisional_member_code]);
                    }
                } else {
                    $data = [];
                    $data['status'] = 'error';
                    $data['errors'] = ActiveForm::validate($this->model, $memberShareDetail, $member_animal_model);
                    $data['message'] = $msg;
                    return $data;
                }
                if (Yii::$app->session->hasFlash('success')) {
                    $msg = Yii::$app->session->getFlash('success');
                    $msg = $msg['message'];
                }
            } else {
                $data = [];
                $data['status'] = 'error';
                $data['errors'] = ActiveForm::validate($this->model, $memberShareDetail, $member_animal_model);
                $data['message'] = $msg;
                return $data;
            }
        }
        return Yii::$app->controller->render('@app/modules/dcsoperation/views/tbl-member-provisional-animal-details/create', [
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
            $animal_detail_model = new TblMemberProvisionalAnimalDetails();
            $animal_detail_model->provisional_member_code = $this->model->provisional_member_code;
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
                $animal_model = new TblMemberProvisionalAnimalDetails();
                $animal_model->animal_type_code = $animal->animal_type_code;
                if (!empty($id)) {
                    $animal_model->provisional_member_code = $id;
                    $animal_model_data = $animal_model->getMemberAnimals();
                    if (!empty($animal_model_data)) {
                        $animal_model = $animal_model_data;
                        $historyModel = new TblMemberProvisionalAnimalDetailsHistory();
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
            $share_model = new TblMemberProvisionalShareDetails();
            if (!empty($id)) {
                $share_model->provisional_member_code = $id;
                $share_model_data = $share_model->getMemberShare();
                if (!empty($share_model_data)) {
                    $share_model = $share_model_data;
                    $historyModel = new TblMemberProvisionalShareDetailsHistory();
                    Yii::$app->operation->history($share_model_data, $historyModel, UPDATE);
                    $h_model[] = $historyModel;
                }
            }
        }
    }

    private function setShareDetails($share_details, &$master_model) {
        $share_detail_model = new TblMemberProvisionalShareDetails();
        $share_detail_model->provisional_member_code = $this->model->provisional_member_code;
        $share_detail_model->union_code = $this->model->union_code;
        $share_model_data = $share_detail_model->getMemberShare();
        if (!empty($share_model_data)) {
            $share_detail_model = $share_model_data;
        }
        $share_detail_model->setAttributes($share_details);
        $share_detail_model->deposit_date = empty($share_detail_model->deposit_date) ? NULL : Yii::$app->formatter->asDate($share_detail_model->deposit_date, DATE_FORMAT);
        $master_model[] = $share_detail_model;
    }

    public function actionCreateFamily() {
        $id = $_POST['TblMemberProvisionalFamilyDetails']['provisional_member_code'];
        $memberFamilyDetail = new TblMemberProvisionalFamilyDetails();
        $memberFamilyDetail->scenario = 'member_family_detail';
        $memberFamilySearchModel = new TblMemberProvisionalFamilyDetailsSearch();
        $memberFamilyDataProvider = null;
        $memberFamilyHistory = [];
        $saveModel = [];
        if (Yii::$app->request->post()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post()['TblMemberProvisionalFamilyDetails'];

            $existData = TblMemberProvisionalFamilyDetails::find()->where(['member_provisional_family_detail_code' => $data['member_provisional_family_detail_code']])->one();

            $memberFamilyDetail->load(Yii::$app->request->post());
            if ($memberFamilyDetail->validate()) {
                $memberFamilyDetail->dob = empty($memberFamilyDetail->dob) ? NULL : Yii::$app->formatter->asDate($memberFamilyDetail->dob, DATE_FORMAT);

                if (!empty($existData)) {
                    $historyModel = new TblMemberProvisionalFamilyDetailsHistory();
                    Yii::$app->operation->history($existData, $historyModel, UPDATE);
                    $memberFamilyHistory[] = $historyModel;
                    $existData->load(Yii::$app->request->post());
//                    $existData->attributes = $memberFamilyDetail->attributes;
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
            $FamilyDelmodel = TblMemberProvisionalFamilyDetails::find()->where(['member_provisional_family_detail_code' => $id])->one();
            if (!empty($FamilyDelmodel)) {
                $historyModel = new TblMemberProvisionalFamilyDetailsHistory();
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
            $modelData = TblMemberProvisionalFamilyDetails::find()->where(['member_provisional_family_detail_code' => $_POST['id']])->one();
            if (!empty($modelData)) {
                $data['status'] = 'success';
                $modelData->dob = !empty($modelData->dob) ? date('d-m-Y', strtotime($modelData->dob)) : '';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

    public function memberEnrollmentApprove($status, &$model_save, $memberModel, &$deleteModel) {
        if (strtolower($status) == 'approve' && strtolower($memberModel->provisional_status) == 'approve') {
            $familyData = new TblMemberProvisionalFamilyDetails();
            $familyData = $familyData->getFamilyData($memberModel->provisional_member_code);
            $existingFamilyDetail = TblMemberFamilyDetails::find()->where(['member_code' => $memberModel->member_code])->all();
            if (!empty($existingFamilyDetail)) {
                foreach ($existingFamilyDetail as $value) {
                    $historyModel = new TblMemberFamilyDetailsHistory();
                    Yii::$app->operation->history($value, $historyModel, DELETE);
                    $deleteModel[] = $value;
                    $model_save[] = $historyModel;
                }
            }
            if (!empty($familyData)) {
                foreach ($familyData as $key => $value) {
                    $memberFamilyModel = new TblMemberFamilyDetails();
                    $memberFamilyModel->attributes = $value->attributes;
                    $memberFamilyModel->member_code = $memberModel->member_code;
                    $model_save[] = $memberFamilyModel;
                }
            }

            $animalData = new TblMemberProvisionalAnimalDetails();
            $animalData = $animalData->getAnimalData($memberModel->provisional_member_code);
            $existingAnimalDetail = TblMemberAnimalDetails::find()->where(['member_code' => $memberModel->member_code])->all();
            if (!empty($existingAnimalDetail)) {
                foreach ($existingAnimalDetail as $value) {
                    $familyHistoryModel = new TblMemberAnimalDetailsHistory();
                    Yii::$app->operation->history($value, $familyHistoryModel, DELETE);
                    $deleteModel[] = $value;
                    $model_save[] = $familyHistoryModel;
                }
            }
            if (!empty($animalData)) {
                foreach ($animalData as $key => $value) {
                    $memberAnimalModel = new TblMemberAnimalDetails();
                    $memberAnimalModel->attributes = $value->attributes;
                    $memberAnimalModel->member_code = $memberModel->member_code;
                    $model_save[] = $memberAnimalModel;
                }
            }

            $shareData = new TblMemberProvisionalShareDetails();
            $shareData = $shareData->getShareData($memberModel->provisional_member_code);
            $existingShareData = TblMemberShareDetails::find()->where(['member_code' => $memberModel->member_code])->one();
            if (!empty($existingShareData)) {
                $shareHistoryModel = new TblMemberShareDetailsHistory();
                Yii::$app->operation->history($existingShareData, $shareHistoryModel, DELETE);
                $deleteModel[] = $existingShareData;
                $model_save[] = $shareHistoryModel;
            }
            if (!empty($shareData)) {
                $memberShareModel = new TblMemberShareDetails();
                $memberShareModel->attributes = $shareData->attributes;
                $memberShareModel->member_code = $memberModel->member_code;
                $memberShareModel->gender_code = $memberModel->gender_code;
                $memberShareModel->bmc_code = $memberModel->bmc_code;
                $model_save[] = $memberShareModel;
            }
        }
    }

    public function actionImportProvisionalMemberBankReceipt() {
        $model = new TblMemberProvisionalShareDetails();
        $model->scenario = 'import_receipt_detail';
        if ($model->load(Yii::$app->request->post())) {
            $message = '';
            $masterModel = [];
            if ($model->validate()) {
                $this->uploadExcel($model, $_POST['file_name'], $masterModel, $message);
                if (!empty($masterModel)) {
                    $this->generalModel->saveTransaction($masterModel, ['member provisional share detail', 'edit']);
                }
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'message' => $message
                ]);
                return $this->redirect(['import-provisional-member-bank-receipt']);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($model));
            }
        }
        return $this->render('import_receipt_detail', [
                    'model' => $model,
        ]);
    }

    private function uploadExcel($model, $fileName, &$masterModel, &$message) {
        $importPath = Yii::getAlias('@webroot') . '/' . Yii::$app->params['import_path'];
        $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($importPath . $fileName);
        $successfulRecords = 0;
        $errorRecords = 0;
        $alreadyUpdatedRecords = 0;
        $updatedLineErrors = [];
        $errorLineNumbers = [];
        $totalAmount = 0;
        $ref_no = '';
        $totalAmountFromExcel = 0;

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            if ($worksheet->getHighestRow() <= 1 || $worksheet->getCell('A1')->getValue() != 'provisional_member_code') {
                $message = 'Please Upload Valid Excel Sheet. <br>';
                return;
            }
            for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
                $amountPayable = $worksheet->getCell('M' . $row)->getValue();
                $totalAmountFromExcel += (float) $amountPayable;
            }
        }
        if ($model->amount_payable != $totalAmountFromExcel) {
            $message = 'Amount Data Not Match With Receipt Detail. <br>';
            return;
        }
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
                $provisionalMemberCode = $worksheet->getCell('A' . $row)->getValue();
                $existData = TblMemberProvisionalShareDetails::find()->where(['provisional_member_code' => $provisionalMemberCode])->one();
                if (!empty($existData)) {
                    if (empty($existData->ref_no)) {
                        $historyModel = new TblMemberProvisionalShareDetailsHistory();
                        Yii::$app->operation->history($existData, $historyModel, UPDATE);
                        $masterModel[] = $historyModel;
                        $existData->scenario = 'import_receipt_detail';
                        $existData->bank_name = $model->bank_name;
                        $existData->amount_deposit = $existData->amount_payable;
                        $existData->deposit_date = empty($model->deposit_date) ? NULL : Yii::$app->formatter->asDate($model->deposit_date, DATE_FORMAT);
                        $existData->mode_of_payment = $model->mode_of_payment;
                        $date = empty($model->deposit_date) ? '' : date('Ymd', strtotime($model->deposit_date));
                        $ref_no = $model->amount_payable . $model->bank_name . $date;
                        $existData->ref_no = $ref_no;
                        $masterModel[] = $existData;
                        $totalAmount += $existData->amount_payable;
                        $successfulRecords++;
                    } else {
                        $alreadyUpdatedRecords++;
                        $updatedLineErrors[] = $row - 1;
                    }
                } else {
                    $errorRecords++;
                    $errorLineNumbers[] = $row - 1;
                }
            }
        }
        if ($message == '') {
            $message = "Success Records: $successfulRecords. <br>Total Amount: $totalAmount. Ref Reciept No: $ref_no<br>" .
                    "Already Updated Records: $alreadyUpdatedRecords. <br>" .
                    (!empty($updatedLineErrors) ? "Already Updated Line Numbers: " . implode(', ', $updatedLineErrors) . "<br>" : "") .
                    "Error Records: $errorRecords. <br>" .
                    (!empty($errorLineNumbers) ? "Error Line Numbers: " . implode(', ', $errorLineNumbers) . "<br>" : "");
        }
    }

    public function actionImportBankReceiptDetail() {
        $path = Yii::getAlias('@webroot') . '/' . Yii::$app->params['import_path'];
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = 'excel_' . time() . '.' . $file->extension;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'msg' => $name];
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

    public function actionBulkPendingApproval() {
        $provisionalModel = new TblMemberProvisional();
        $searchModel = new TblMemberProvisionalSearch();
        $searchModel->scenario = 'bulk_approval';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TRUE, TRUE);
        $selection = Yii::$app->request->post('selection');
        if (Yii::$app->request->post() && !empty($selection)) {
            $succCount = 0;
            $errorCount = 0;
            $memberpostData = Yii::$app->request->post()['TblMemberProvisional'];
            $remarks = Yii::$app->request->post()['approve_remarks'];
            $postData = Yii::$app->request->post();
            foreach ($selection as $value) {
                $model = TblProcessApproval::findOne($value);
                $model->scenario = 'approve';
                $member_error = '';
                $message = '';
                $saveModel = [];
                $deleteModel = [];
                $approvalHistoryModel = new TblProcessApprovalHistory();
                Yii::$app->operation->history($model, $approvalHistoryModel, 'UPDATE');
                $saveModel[] = $approvalHistoryModel;
                $operation = $postData['operation'];
                $model->status = $operation == 'approve' ? 1 : 2;
                $model->remarks = $remarks . $memberpostData[$value]['remarks'];
                $saveModel[] = $model;
                if (!empty($saveModel)) {
                    $model->ApprovalList($model, $saveModel, $status);
                    $memberModel = $this->findModel($model->process_code);
                    $historyModel = new TblMemberProvisionalHistory();
                    Yii::$app->operation->history($memberModel, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $memberModel->provisional_status = $status;
                    $memberModel->remarks = $remarks . $memberpostData[$value]['remarks'];
                    $memberCreationPendingForSapApproval = Yii::$app->general->getUnionConfiguration($memberModel->union_code, 'member_creation_pending_for_sap_approval', 'PORTAL');
                    if (strtolower($status) == 'approve' && $memberCreationPendingForSapApproval == 1) {
                        $memberModel->approved_at = date('Y-m-d H:i:s');
                        $memberModel->approved_by = Yii::$app->session['UserCode'];
                    }
                    $memberModel->member_status = 0; // Approved
                    if (strtolower($status) == 'approve' && ($memberCreationPendingForSapApproval != '1' || $memberModel->provisional_from == 'mobile_update')) {
                        $memberModel->member_status = 1; // Created
                    }
                    $memberModel->scenario = 'MemberApprove';
                    $saveModel[] = $memberModel;
                    $all_doc = [];
                    $memberdoc = [];
                    $unlink_files = [];
                    $attachments = [];
                    $config = Yii::$app->general->getUnionConfigResult($memberModel->union_code, 'allow_member_other_detail');
                    if ($memberModel->validate()) {
                        if ($memberModel->provisional_status == 'Approve' && $memberCreationPendingForSapApproval != '1') {
                            $this->memberApprove($status, $saveModel, $deleteModel, $memberModel, $all_doc, $memberdoc, $save_member_doc = [], $message, $unlink_files, $attachments);
                            if ($config == 1) {
                                $this->memberEnrollmentApprove($status, $saveModel, $memberModel, $deleteModel);
                            }
                        }
                        $succCount++;
                    } else {
                        $errorCount++;
                        foreach ($memberModel->getErrors() as $errorkey => $value) {
                            $message = $value;
                        }
                    }
                    if (!empty($message)) {
                        foreach ($message as $msg) {
                            $member_error .= $msg;
                        }
                    }
                    if (empty($member_error)) {
                        $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Member Provisional Approval', 'edit']);
                        if ($transaction == 'customRedirect') {
                            if ($memberModel->provisional_status == 'Approve') {
                                $memberModel->moveFiles($unlink_files, $attachments, $memberdoc);
                            }
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => $member_error . ' in Member']);
                        return $this->redirect(['bulk-pending-approval']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Member provisional already approved by other user.']);
                    return $this->redirect(['bulk-pending-approval']);
                }
            }
            $msg = $operation == 'approve' ? ('Provisional Member approved successfully. <br /> Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount) : ('Provisional Member rejected successfully.  <br />Rejected count : ' . $succCount . '<br />Not Rejected count : ' . $errorCount);
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => $msg]);
            $getData = Yii::$app->request->queryParams;
            if (!empty($getData['TblMemberProvisionalSearch'])) {
                return $this->redirect(['bulk-pending-approval', 'TblMemberProvisionalSearch' => $getData['TblMemberProvisionalSearch']]);
            } else {
                return $this->redirect(['bulk-pending-approval']);
            }
        }

        return $this->render('bulk_approve', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'provisionalModel' => $provisionalModel,
        ]);
    }

    public function actionGetExMemberCode() {
        $provisionalModel = new TblMemberProvisional();
        $model = new TblMember();
        $exMemberCode = '';
        $dcsCode = Yii::$app->request->post('dcs_code');
        if (!empty($dcsCode)) {
            $exMemberCode = Yii::$app->general->getMaxCode($model, 'ex_member_code', $dcsCode, $provisionalModel);
        }
        return $exMemberCode;
    }

    public function actionRfcRePush($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblMemberProvisionalHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->data_post_status = 0;
        $record = [];
        if ($this->model->save(true, false)) {
            $historyModel->save();
            $record = ['status' => 'success', 'msg' => 'Member Provisional re-pushed successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Failed to re-push Member Provisional.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDelete() {
        $saveModel = [];

        $id = Yii::$app->request->post('id');

        $this->model = $this->findModel($id);
        $memberProvisionalhistoryModel = new TblMemberProvisionalHistory();
        Yii::$app->operation->history($this->model, $memberProvisionalhistoryModel, DELETE);
        $saveModel[] = $this->model;
        $saveModel[] = $memberProvisionalhistoryModel;

        $memberProvisionalAnimalDetailsModels = TblMemberProvisionalAnimalDetails::findAll(['provisional_member_code' => $id]);
        if (!empty($memberProvisionalAnimalDetailsModels)) {
            foreach ($memberProvisionalAnimalDetailsModels as $memberProvisionalAnimalDetailsModel) {
                $memberProvisionalAnimalDetailshistoryModel = new TblMemberProvisionalAnimalDetailsHistory();
                Yii::$app->operation->history($memberProvisionalAnimalDetailsModel, $memberProvisionalAnimalDetailshistoryModel, DELETE);
                $saveModel[] = $memberProvisionalAnimalDetailsModel;
                $saveModel[] = $memberProvisionalAnimalDetailshistoryModel;
            }
        }

        $memberProvisionalShareDetailsModels = TblMemberProvisionalShareDetails::findAll(['provisional_member_code' => $id]);
        if (!empty($memberProvisionalShareDetailsModels)) {
            foreach ($memberProvisionalShareDetailsModels as $memberProvisionalShareDetailsModel) {
                $memberProvisionalShareDetailshistoryModel = new TblMemberProvisionalShareDetailsHistory();
                Yii::$app->operation->history($memberProvisionalShareDetailsModel, $memberProvisionalShareDetailshistoryModel, DELETE);
                $saveModel[] = $memberProvisionalShareDetailsModel;
                $saveModel[] = $memberProvisionalShareDetailshistoryModel;
            }
        }

        $memberProvisionalFamilyDetailsModels = TblMemberProvisionalFamilyDetails::findAll(['provisional_member_code' => $id]);
        if (!empty($memberProvisionalFamilyDetailsModels)) {
            foreach ($memberProvisionalFamilyDetailsModels as $memberProvisionalFamilyDetailsModel) {
                $memberProvisionalFamilyDetailshistoryModel = new TblMemberProvisionalShareDetailsHistory();
                Yii::$app->operation->history($memberProvisionalFamilyDetailsModel, $memberProvisionalFamilyDetailshistoryModel, DELETE);
                $saveModel[] = $memberProvisionalFamilyDetailsModel;
                $saveModel[] = $memberProvisionalFamilyDetailshistoryModel;
            }
        }

        $deleteAttachments = TblAttachment::findAll(['module_code' => $id, 'module_name' => 'tbl_member_provisional']);
        if (!empty($deleteAttachments)) {
            foreach ($deleteAttachments as $deleteAttachment) {
                $attachHistoryModel = new TblAttachmentHistory();
                Yii::$app->operation->history($deleteAttachment, $attachHistoryModel, DELETE);
                $saveModel[] = $deleteAttachment;
                $saveModel[] = $attachHistoryModel;
            }
        }

        $record = $this->generalModel->deleteTransaction($saveModel);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionUploadMemberDataToSapFtp() {
        $model = new TblMemberProvisional();
        $searchModel = new TblMemberProvisionalSearch();
        $searchModel->scenario = 'sapFtpUpload';
        $dataProvider = $searchModel->searchSapFtpUpload(Yii::$app->request->queryParams);
        $msg = '';
        if (Yii::$app->request->post()) {
            $searchDataOutput = $dataProvider->getModels();
            $queryParams = Yii::$app->request->queryParams['TblMemberProvisionalSearch'];
            $all_data = [];

            $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation']) : '';
            if ($status == 'upload') {
                $qryParam = Yii::$app->request->queryParams['TblMemberProvisionalSearch'] ?? [];
                $sp_params = [
                    'union_code' => $qryParam['union_code'] ?? '',
                    'plant_code' => $qryParam['plant_code'] ?? '',
                    'mcc_plant_code' => !empty($qryParam['mcc_plant_code']) ? $qryParam['mcc_plant_code'] : (Yii::$app->session->get('MCC') ? ',' . Yii::$app->session->get('MCC') . ',' : 0),
                    'bmc_code' => !empty($qryParam['bmc_code']) ? $qryParam['bmc_code'] : (Yii::$app->session->get('BMC') ? ',' . Yii::$app->session->get('BMC') . ',' : 0),
                    'dcs_code' => !empty($qryParam['dcs_code']) ? $qryParam['dcs_code'] : (Yii::$app->session->get('Dcs') ? ',' . Yii::$app->session->get('Dcs') . ',' : 0),
                    'from_date' => date('Y-m-d', strtotime($qryParam['from_date'] ?? '')),
                    'to_date' => date('Y-m-d', strtotime($qryParam['to_date'] ?? '')),
                    'sap_status' => $qryParam['sap_status'] ?? '',
                ];
                $output = \Yii::$app->general->getSpData('member_provisional_sap_ftp_data_upload_mpcrmrd', $sp_params);

                $data_array = array_merge($sp_params, [
                    'module_name' => 'TblMemberProvisional',
                    'module_code' => $output[0]['BMCCode'] ?? '',
                ]);

                if (!empty($output)) {
                    $title = $output[0]['ftp_txn_file_name'];
                    $all_data = array_map(function($row) {
                        foreach ($this->toEncrypt as $col) {
                            if (!empty($row[$col])) {
                                $decrypted = Yii::$app->general->decryptData($row[$col]);
                                $row[$col] = ($decrypted !== false) ? $decrypted : $row[$col];
                            }
                        }
                        unset($row['ftp_txn_file_name']);
                        return $row;
                    }, $output);

                    $ftp_model = new TblFtpTxnLog();
                    $result = $ftp_model->exportData($data_array, $title, $all_data, '', FALSE, TRUE);
                    if (!empty($result)) {
                        $model->updateProcessStatus(2, $output[0]['ftp_txn_file_name']);
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => Yii::t('app', 'FTP Uploaded Successfully.')]);
                        return $this->redirect(['index']);
                    } else {
                        $model->updateProcessStatus(3, $output[0]['ftp_txn_file_name']);
                        $msg = Yii::t('app', 'FTP Upload Failed. Please try again later.');
                    }
                } else {
                    $msg = Yii::t('app', 'No data found for the given parameters.');
                }
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $msg]);
            }
        }
        return $this->render('sap_ftp_upload', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEditApproval($id) {
        return $this->startFlow($id, false);
    }

    public function actionViewApproval($id) {
        return $this->startFlow($id, true);
    }

    private function startFlow($id, $isView) {
        $steps = $isView ? $this->getViewApprovalStepsList() : $this->getApprovalStepsList();
        if (empty($steps)) {
            throw new \yii\web\ForbiddenHttpException('You do not have permission to access any approval steps.');
        }
        $firstStep = reset($steps);
        return $this->redirect([$firstStep['action'], 'id' => $id]);
    }

    private function getViewApprovalStepsList() {
        $stepsConfig = [
                ['label' => 'Member Details', 'actions' => ['approval-view-member-detail']],
                ['label' => 'Address & Adhar Details', 'actions' => ['approval-view-address-detail', 'approval-view-adhar-detail']],
                ['label' => 'Family Details', 'actions' => ['approval-view-family-detail']],
                ['label' => 'Animal & Commitment Details', 'actions' => ['approval-view-animal-detail', 'approval-view-commitment-detail']],
                ['label' => 'Share Details', 'actions' => ['approval-view-share-detail']],
                ['label' => 'Bank Details', 'actions' => ['approval-view-bank-detail', 'approval-view-fee-detail', 'approval-view-mismatch-detail']],
        ];
        return $this->processSteps($stepsConfig);
    }

    private function getApprovalStepsList() {
        $stepsConfig = [
                ['label' => 'Member Details', 'actions' => ['approval-member-detail']],
                ['label' => 'Address & Adhar Details', 'actions' => ['approval-address-detail', 'approval-adhar-detail']],
                ['label' => 'Family Details', 'actions' => ['approval-family-detail']],
                ['label' => 'Animal & Commitment Details', 'actions' => ['approval-animal-detail', 'approval-commitment-detail']],
                ['label' => 'Share Details', 'actions' => ['approval-share-detail']],
                ['label' => 'Bank Details', 'actions' => ['approval-bank-detail', 'approval-fee-detail', 'approval-mismatch-detail']],
        ];
        return $this->processSteps($stepsConfig);
    }

    private function processSteps($stepsConfig) {
        $basePath = '/dcsoperation/tbl-member-provisional/';
        $steps = [];

        foreach ($stepsConfig as $config) {
            foreach ($config['actions'] as $action) {
                if (Yii::$app->general->checkAccess($basePath . $action)) {
                    $steps[$action] = [
                        'label' => $config['label'],
                        'action' => $action
                    ];
                    break;
                }
            }
        }
        return $steps;
    }

    public function getNextStepUrl($currentAction, $id, $isView = false) {
        $steps = $isView ? $this->getViewApprovalStepsList() : $this->getApprovalStepsList();
        $keys = array_keys($steps);
        $currentIndex = array_search($currentAction, $keys);

        if ($currentIndex === false || $currentIndex >= count($keys) - 1) {
            return null;
        }
        $nextAction = $keys[$currentIndex + 1];
        return [$nextAction, 'id' => $id];
    }

    public function getPreviousStepUrl($currentAction, $id, $isView = false) {
        $steps = $isView ? $this->getViewApprovalStepsList() : $this->getApprovalStepsList();
        $keys = array_keys($steps);
        $currentIndex = array_search($currentAction, $keys);
        if ($currentIndex === false || $currentIndex <= 0) {
            return null;
        }
        $prevAction = $keys[$currentIndex - 1];
        return [$prevAction, 'id' => $id];
    }

    public function isLastStep($currentAction, $isView = false) {
        $steps = $isView ? $this->getViewApprovalStepsList() : $this->getApprovalStepsList();
        $keys = array_keys($steps);
        $currentIndex = array_search($currentAction, $keys);
        if ($currentIndex === false) {
            return true;
        }
        return ($currentIndex >= count($keys) - 1);
    }

    private function handleNextStep($currentAction, $id) {
        $isView = strpos($currentAction, 'view') !== false;
        if ($this->isLastStep($currentAction, $isView)) {
            return $this->actionApproveMember($id, 1);
        }
        $nextUrl = $this->getNextStepUrl($currentAction, $id, $isView);
        if ($nextUrl) {
            return $this->redirect($nextUrl);
        }

        return null;
    }

    public function actionApprovalMemberDetail($id) {
        return $this->renderUnifiedMemberDetail($id, 'approval-member-detail');
    }

    public function actionApprovalViewMemberDetail($id) {
        return $this->renderUnifiedMemberDetail($id, 'approval-view-member-detail', true);
    }

    private function renderUnifiedMemberDetail($id, $currentStep, $isView = false) {
        $processModel = TblProcessApproval::findOne($id);
        $memberModel = $this->findModel($processModel->process_code);

        if (!$isView) {
            $memberModel->dob = empty($memberModel->dob) ? NULL : $memberModel->dob;
            $memberModel->member_name = ucwords($memberModel->member_name);
            $memberModel->ex_member_code = !empty($memberModel->ex_member_code) ? str_pad($memberModel->ex_member_code, 4, '0', STR_PAD_LEFT) : '';
            $memberModel->scenario = 'approval_member_detail';
            $tblMember = new TblMember();
            if (empty($memberModel->ex_member_code)) {
                $memberModel->ex_member_code = Yii::$app->general->getMaxCode($tblMember, 'ex_member_code', $memberModel->dcs_code, $memberModel);
            }
        }

        if (Yii::$app->request->post()) {
            $res = $this->handleApprovalPost($id, $currentStep, $memberModel, $processModel, 'member');
            if ($res) {
                return $res;
            }
        }

        $viewFile = $isView ? 'approval_view_member_detail' : 'approval_member_detail';
        return $this->render($viewFile, [
                    'model' => $memberModel,
                    'processModel' => $processModel,
                    'currentStep' => $currentStep,
                    'isLastStep' => $this->isLastStep($currentStep, $isView)
        ]);
    }

    public function actionApprovalViewAddressDetail($id) {
        return $this->renderUnifiedAddressDetail($id, 'approval-view-address-detail', true);
    }

    public function actionApprovalViewAdharDetail($id) {
        return $this->renderUnifiedAddressDetail($id, 'approval-view-adhar-detail', true);
    }

    public function actionApprovalAddressDetail($id) {
        return $this->renderUnifiedAddressDetail($id, 'approval-address-detail');
    }

    public function actionApprovalAdharDetail($id) {
        return $this->renderUnifiedAddressDetail($id, 'approval-adhar-detail');
    }

    private function renderUnifiedAddressDetail($id, $currentStep, $isView = false) {
        $processModel = TblProcessApproval::findOne($id);
        $memberModel = $this->findModel($processModel->process_code);
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $processModel->process_code, 'module_name' => 'tbl_member_provisional']),
        ]);
        if (Yii::$app->request->post()) {
            $res = $this->handleApprovalPost($id, $currentStep, $memberModel, $processModel, 'address');
            if ($res) {
                return $res;
            }
        }

        $viewFile = $isView ? 'approval_view_address_detail' : 'approval_address_detail';
        return $this->render($viewFile, [
                    'model' => $memberModel,
                    'processModel' => $processModel,
                    'currentStep' => $currentStep,
                    'attachment' => $attachment,
                    'dataProviderOther' => $dataProviderOther,
                    'isLastStep' => $this->isLastStep($currentStep, $isView)
        ]);
    }

    public function actionApprovalViewFamilyDetail($id) {
        return $this->renderUnifiedFamilyDetail($id, 'approval-view-family-detail', true);
    }

    public function actionApprovalFamilyDetail($id) {
        return $this->renderUnifiedFamilyDetail($id, 'approval-family-detail');
    }

    private function renderUnifiedFamilyDetail($id, $currentStep, $isView = false) {
        $processModel = TblProcessApproval::findOne($id);
        $memberModel = $this->findModel($processModel->process_code);

        $msearchModel = new TblMemberProvisionalSearch();
        $msearchModel->provisional_member_code = $memberModel->provisional_member_code;
        $mdataProvider = $msearchModel->search(Yii::$app->request->queryParams);

        $memberFamilyDetail = new TblMemberProvisionalFamilyDetails();
        $memberFamilyDetail->provisional_member_code = $memberModel->provisional_member_code;
        $memberFamilyDetail->scenario = 'member_family_detail';
        $familySearchModel = new TblMemberProvisionalFamilyDetailsSearch();
        $familySearchModel->provisional_member_code = $memberModel->provisional_member_code;
        $fDataProvider = $familySearchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {
            $res = $this->handleApprovalPost($id, $currentStep, $memberModel, $processModel, 'family');
            if ($res) {
                return $res;
            }
        }

        $viewFile = $isView ? 'approval_view_family_detail' : 'approval_family_detail';
        return $this->render($viewFile, [
                    'model' => $memberModel,
                    'processModel' => $processModel,
                    'currentStep' => $currentStep,
                    'memberFamilyDataProvider' => $fDataProvider,
                    'memberFamilySearchModel' => $familySearchModel,
                    'memberFamilyDetail' => $memberFamilyDetail,
                    'msearchModel' => $msearchModel,
                    'mdataProvider' => $mdataProvider,
                    'isLastStep' => $this->isLastStep($currentStep, $isView)
        ]);
    }

    public function actionApprovalViewAnimalDetail($id) {
        return $this->renderUnifiedAnimalDetail($id, 'approval-view-animal-detail', true);
    }

    public function actionApprovalViewCommitmentDetail($id) {
        return $this->renderUnifiedAnimalDetail($id, 'approval-view-commitment-detail', true);
    }

    public function actionApprovalAnimalDetail($id) {
        return $this->renderUnifiedAnimalDetail($id, 'approval-animal-detail');
    }

    public function actionApprovalCommitmentDetail($id) {
        return $this->renderUnifiedAnimalDetail($id, 'approval-commitment-detail');
    }

    private function renderUnifiedAnimalDetail($id, $currentStep, $isView = false) {
        $processModel = TblProcessApproval::findOne($id);
        $memberModel = $this->findModel($processModel->process_code);

        $animalMemberModel = new TblMemberProvisionalAnimalDetailsSearch();
        $animalMemberModel->provisional_member_code = $memberModel->provisional_member_code;
        $animalDataProvider = $animalMemberModel->search(Yii::$app->request->queryParams);

        $animal_model = new TblMemberAnimalType();
        $animal_model->union_code = $memberModel->union_code;
        $animals = $animal_model->getAnimal();
        $member_animal_model_data = [];
        $member_animal_model = new TblMemberProvisionalAnimalDetails();
        $member_animal_model->provisional_member_code = $memberModel->provisional_member_code;

        $h_model = [];
        $msearchModel = new TblMemberProvisionalSearch();
        $msearchModel->provisional_member_code = $memberModel->provisional_member_code;
        $mdataProvider = $msearchModel->search(Yii::$app->request->queryParams);
        $this->setAnimalModelData($animals, $member_animal_model_data, $h_model, $memberModel->provisional_member_code);

        if (Yii::$app->request->post()) {
            $res = $this->handleApprovalPost($id, $currentStep, $memberModel, $processModel, 'animal', null, $member_animal_model_data, $h_model);
            if ($res) {
                return $res;
            }
        }

        $viewFile = $isView ? 'approval_view_animal_detail' : 'approval_animal_detail';
        return $this->render($viewFile, [
                    'animals' => $animals,
                    'member_animal_model' => $member_animal_model,
                    'member_animal_model_data' => $member_animal_model_data,
                    'model' => $memberModel,
                    'processModel' => $processModel,
                    'currentStep' => $currentStep,
                    'animalDataProvider' => $animalDataProvider,
                    'animalMemberModel' => $animalMemberModel,
                    'isLastStep' => $this->isLastStep($currentStep, $isView)
        ]);
    }

    public function actionApprovalViewShareDetail($id) {
        return $this->renderUnifiedShareDetail($id, 'approval-view-share-detail', true);
    }

    public function actionApprovalShareDetail($id) {
        return $this->renderUnifiedShareDetail($id, 'approval-share-detail');
    }

    private function renderUnifiedShareDetail($id, $currentStep, $isView = false) {
        $processModel = TblProcessApproval::findOne($id);
        $memberModel = $this->findModel($processModel->process_code);
        $h_model = [];

        $shareModel = new TblMemberProvisionalShareDetails();
        $shareMemberModel = new TblMemberProvisionalShareDetailsSearch();
        $shareMemberModel->provisional_member_code = $memberModel->provisional_member_code;
        $shareDataProvider = $shareMemberModel->search(Yii::$app->request->queryParams);

        $memberShareDetail = TblMemberProvisionalShareDetails::find()->where(['provisional_member_code' => $memberModel->provisional_member_code])->one();

        if (!$isView) {
            $shareConfig = new TblUnionShareConfig();
            $shares = $shareConfig->getShareDetail('member', $memberModel->gender_code, $memberModel->union_code, $memberModel->bmc_code);

            if (empty($memberShareDetail)) {
                $memberShareDetail = new TblMemberProvisionalShareDetails();
                $memberShareDetail->provisional_member_code = $memberModel->provisional_member_code;
                $memberShareDetail->no_of_share_req = $shares['min_share'];
                $memberShareDetail->no_of_share_apply = $shares['max_share'];
                $memberShareDetail->admission_fee = $shares['admission_fee'];
                $memberShareDetail->payable_share_amount = $shares['max_share'] * $shares['per_share_rate'];
                $memberShareDetail->amount_payable = ($shares['max_share'] * $shares['per_share_rate']) + $shares['admission_fee'];
                $memberShareDetail->total_amount = ($shares['max_share'] * $shares['per_share_rate']) + $shares['admission_fee'];
                $memberShareDetail->per_share_rate = $shares['per_share_rate'];
            }
            $this->setShareModelData($memberShareDetail, $h_model, $memberModel->provisional_member_code);
        }

        if (Yii::$app->request->post()) {
            $res = $this->handleApprovalPost($id, $currentStep, $memberModel, $processModel, 'share', $memberShareDetail, null, null, $h_model);
            if ($res) {
                return $res;
            }
        }

        $viewFile = $isView ? 'approval_view_share_detail' : 'approval_share_detail';
        return $this->render($viewFile, [
                    'model' => $memberModel,
                    'shareModel' => $shareModel,
                    'processModel' => $processModel,
                    'currentStep' => $currentStep,
                    'shareDataProvider' => $shareDataProvider,
                    'shareMemberModel' => $shareMemberModel,
                    'memberShareDetail' => $memberShareDetail,
                    'isLastStep' => $this->isLastStep($currentStep, $isView)
        ]);
    }

    public function actionApprovalViewBankDetail($id) {
        return $this->renderUnifiedBankDetail($id, 'approval-view-bank-detail', true);
    }

    public function actionApprovalViewFeeDetail($id) {
        return $this->renderUnifiedBankDetail($id, 'approval-view-fee-detail', true);
    }

    public function actionApprovalViewMismatchDetail($id) {
        return $this->renderUnifiedBankDetail($id, 'approval-view-mismatch-detail', true);
    }

    public function actionApprovalBankDetail($id) {
        return $this->renderUnifiedBankDetail($id, 'approval-bank-detail', false, 'approval_bank_detail');
    }

    public function actionApprovalFeeDetail($id) {
        return $this->renderUnifiedBankDetail($id, 'approval-fee-detail');
    }

    public function actionApprovalMismatchDetail($id) {
        return $this->renderUnifiedBankDetail($id, 'approval-mismatch-detail');
    }

    private function renderUnifiedBankDetail($id, $currentStep, $isView = false, $scenario = '') {
        $processModel = TblProcessApproval::findOne($id);
        $memberModel = $this->findModel($processModel->process_code);
        $shareModel = TblMemberProvisionalShareDetails::find()->where(['provisional_member_code' => $memberModel->provisional_member_code])->one();
        if (!$shareModel) {
            $shareModel = new TblMemberProvisionalShareDetails();
            $shareModel->provisional_member_code = $memberModel->provisional_member_code;
        }

        if (!$isView) {
            $memberModel->scenario = $scenario;
        }

        if (Yii::$app->request->post()) {
            $res = $this->handleApprovalPost($id, $currentStep, $memberModel, $processModel, 'bank');
            if ($res) {
                return $res;
            }
        }

        $viewFile = $isView ? 'approval_view_bank_detail' : 'approval_bank_detail';
        $steps = $isView ? $this->getViewApprovalStepsList() : $this->getApprovalStepsList();
        return $this->render($viewFile, [
                    'model' => $memberModel,
                    'shareModel' => $shareModel,
                    'processModel' => $processModel,
                    'steps' => $steps,
                    'currentStep' => $currentStep,
                    'isLastStep' => $this->isLastStep($currentStep, $isView)
        ]);
    }

    private function getValidationConfig($memberModel) {
        return [
            'approval-member-detail' => [
                    ['model' => $memberModel, 'scenario' => 'approval_member_detail']
            ],
            'approval-address-detail' => [
                    ['model' => $memberModel, 'scenario' => 'approval_address_detail']
            ],
            'approval-adhar-detail' => [
                    ['model' => $memberModel, 'scenario' => 'approval_adhar_detail']
            ],
            'approval-bank-detail' => [
                    ['model' => $memberModel, 'scenario' => 'approval_bank_detail'],
            ],
        ];
    }

    private function handleApprovalPost($id, $action, $memberModel, $processModel, $step, $shareModel = null, $animalModel = null, $animalHistory = null, $shareHistory = null) {
        $this->model = $memberModel;
        $message = '';
        if (!Yii::$app->request->post())
            return null;

        $postData = Yii::$app->request->post();

        if (isset($postData['operation']) && $postData['operation'] == 'reroute') {
            $remarks = !empty($postData['reroute_remarks']) ? $postData['reroute_remarks'] : '';
            $res = $this->handleReroute($memberModel, $remarks);
            if ($res == 'customRedirect') {
                return $this->redirect(['pending-approval']);
            }
            return $res;
        }

        if (strpos($action, 'approval-view') !== false) {
            return $this->handleNextStep($action, $id);
        }

        $models = [];
        $histories = [];
        $log = ['Member Provisional', 'edit'];

        if ($step == 'member') {
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($memberModel, $historyModel, 'UPDATE');
            $dcs_code = $memberModel->dcs_code;
            $memberModel->load($postData);
            if ($dcs_code != $memberModel->dcs_code && $memberModel->provisional_from != 'mobile_update') {
                $memberModel->ex_member_code = Yii::$app->general->getMaxCode(new TblMember(), 'ex_member_code', $memberModel->dcs_code, $memberModel);
            }
            $memberModel->member_code = $memberModel->getCode();
            $provisionalStatus = ['Register', 'Pending', 'Inprogress', 'Reroute'];
            $memberModel->scenario = 'approval_member_detail';
            if (isset($_POST['warning']) && $_POST['warning'] == 0) {
                $validate = Yii::$app->warning->unique_member($memberModel, ['member_name', 'dcs_code', 'hamlet_code', 'provisional_status'], [$memberModel->member_name, $memberModel->dcs_code, $memberModel->hamlet_code, $provisionalStatus]);
                if ($validate != 1)
                    return null;
            }
            if ($memberModel->validate()) {
                $memberModel->registration_date = empty($memberModel->registration_date) ? NULL : Yii::$app->formatter->asDate($memberModel->registration_date, DATE_FORMAT);
                $memberModel->dob = empty($memberModel->dob) ? NULL : Yii::$app->formatter->asDate($memberModel->dob, DATE_FORMAT);
                $models = [$memberModel];
                $histories = [$historyModel];
            }
        } else if (in_array($step, ['address', 'commitment', 'bank'])) {
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($memberModel, $historyModel, 'UPDATE');
            $memberModel->load($postData);

            $isValid = true;
            if ($step == 'address') {
                if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-address-detail')) {
                    $memberModel->scenario = 'approval_address_detail';
                    if (!$memberModel->validate()) {
                        $isValid = false;
                    }
                }

                if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-adhar-detail')) {
                    $memberModel->scenario = 'approval_adhar_detail';
                    if (!$memberModel->validate(null, false)) {
                        $isValid = false;
                    }
                }
            }

            if ($step == 'bank') {
                if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-bank-detail')) {
                    $memberModel->scenario = 'approval_bank_detail';
                    if (!$memberModel->validate()) {
                        $isValid = false;
                    }
                }
            }

            if ($isValid) {
                $models[] = $memberModel;
                $histories[] = $historyModel;
            }
        } else if ($step == 'family' || $step == 'mismatch') {
            return $this->handleNextStep($action, $id);
        } else if ($step == 'animal') {
            $memberModel->load($postData);
            $histories = [$animalHistory];
            $models = [$animalModel];
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($memberModel, $historyModel, 'UPDATE');
            $histories = [$historyModel];
            $models = [$memberModel];

            if (isset($postData['TblMemberProvisionalAnimalDetails'])) {
                $this->setAnimalDetails($postData['TblMemberProvisionalAnimalDetails'], $models, $animalModel);
            }
            $member_animal_model = new TblMemberProvisionalAnimalDetails();
            if (!$member_animal_model->validate() || !$memberModel->validate()) {
                return ['status' => 'error', 'errors' => \app\components\ActiveForm::validate($memberModel, $member_animal_model)];
            }
        } else if ($step == 'share') {
            $histories = $shareHistory;
            if ($shareModel->load($postData)) {
                $shareModel->gender_code = $memberModel->gender_code;
                $shareModel->union_code = $memberModel->union_code;
                $shareModel->bmc_code = $memberModel->bmc_code;
                $shareModel->deposit_date = empty($shareModel->deposit_date) ? NULL : Yii::$app->formatter->asDate($shareModel->deposit_date, DATE_FORMAT);
                if ($shareModel->validate()) {
                    $models = [$shareModel];
                } else {
                    foreach ($shareModel->getErrors() as $errorkey => $value) {
                        $message .= $value[0] . '<br>';
                    }
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $message]);
                }
            } else {
                return ['status' => 'error', 'errors' => ActiveForm::validate($shareModel)];
            }
        } else if ($step == 'fee') {
            $historyModel = new TblMemberProvisionalShareDetailsHistory();
            Yii::$app->operation->history($shareModel, $historyModel, 'UPDATE');
            if ($shareModel->load($postData)) {
                if ($shareModel->validate()) {
                    $models = [$shareModel];
                    $histories = [$historyModel];
                    $log = ['Member Provisional Share Detail', 'edit'];
                }
            }
        }

        if (!empty($models)) {
            $transaction = $this->generalModel->saveTransaction($models, $histories, $log);
            if ($transaction == 'customRedirect') {
                return $this->handleNextStep($action, $id);
            }
        }
        return null;
    }

    private function handleReroute($memberModel, $remarks) {
        $saveModel = [];
        $historyModel = new TblMemberProvisionalHistory();
        Yii::$app->operation->history($memberModel, $historyModel, UPDATE);
        $saveModel[] = $historyModel;

        $memberModel->provisional_status = 'Reroute';
        $memberModel->remarks = $remarks;
        $memberModel->scenario = 'Reroute';
        $saveModel[] = $memberModel;
        if (!$memberModel->validate()) {
            $errors = [];
            foreach ($memberModel->getErrors() as $attrErrors) {
                foreach ($attrErrors as $error) {
                    $errors[] = $error;
                }
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => implode('<br/>', $errors)]);
            return false;
        }
        $workflowRequired = Yii::$app->general->getUnionConfiguration($memberModel->union_code, 'workflow_require', 'PORTAL');
        if ($workflowRequired == 1) {
            $approvals = TblProcessApproval::find()->where(['process_code' => $memberModel->provisional_member_code])->all();
            foreach ($approvals as $approval) {
                $approvalHistory = new TblProcessApprovalHistory();
                Yii::$app->operation->history($approval, $approvalHistory, UPDATE);
                $saveModel[] = $approvalHistory;

                $approval->status = 0;
                $saveModel[] = $approval;
            }
        }
        return $this->generalModel->saveTransaction([], $saveModel, ['Member Provisional Reroute', 'edit']);
    }

}
