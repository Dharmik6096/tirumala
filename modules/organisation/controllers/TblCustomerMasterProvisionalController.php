<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCustomerMasterProvisional;
use app\modules\organisation\models\TblCustomerMasterProvisionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\document\models\TblAttachment;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\document\models\TblDocumentMapping;
use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\organisation\models\TblCustomerMasterProvisionalHistory;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\document\models\TblAttachmentHistory;

/**
 * TblCustomerMasterProvisionalController implements the CRUD actions for TblCustomerMasterProvisional model.
 */
class TblCustomerMasterProvisionalController extends \app\controllers\ChildController {

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['excode-prefix'];

    /**
     * Lists all TblCustomerMasterProvisional models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCustomerMasterProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => FALSE
        ]);
    }

    /**
     * Displays a single TblCustomerMasterProvisional model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblCustomerMasterProvisionalSearch();
        $searchModel->customer_provisional_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $attachment = new TblAttachment();
        $id = (string) $id;
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => (string) $id, 'module_name' => 'tbl_customer_master_provisional']),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment
        ]);
    }

    /**
     * Creates a new TblCustomerMasterProvisional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCustomerMasterProvisional();
        $this->viewFile = 'create';

        $this->model->scenario = 'createFront';
        if ($this->model->load(Yii::$app->request->post())) {
            $exCode = $this->model->customer_code_ex;
            $this->model->customer_code_ex = $this->model->prefix . $exCode;
            $this->model->customer_code = $this->model->getCode();
            $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;

            if (empty($this->model->getErrors())) {
                $this->model->customer_code_ex = !empty($this->model->prefix . $exCode) ? $this->model->prefix . $exCode : $this->model->customer_code_ex;
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Customer Master', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
            $this->model->customer_code_ex = $exCode;
        }
        return $this->customRender();
    }

    protected function customRedirect() {
        return $this->redirect(['document-upload', 'id' => $this->model->customer_provisional_code]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, [
                    'model' => $this->model,
        ]);
    }

    public function actionDocumentUpload($id) {
        $model = $this->findModel($id);
        $doc_mapping = TblDocumentMapping::find()->where(['master_type' => 'provisional_customer'])->all();
        $doc_model = [];
        foreach ($doc_mapping as $doc) {
            $attachments = $doc->uploadedDocument($doc->doc_id, $id, 'tbl_customer_master_provisional');
            $master_doc = $doc->docId;
            if (empty($attachments)) {
                $attachments = new TblAttachment();
                $attachments->module_code = $model->customer_provisional_code;
                $attachments->doc_id = $doc->doc_id;
            }
            $attachments->is_mandate = $doc->is_mandate;
            $attachments->attachment_type = $master_doc->doc_ext;
            $attachments->doc_name = $master_doc->doc_name .= ($doc->is_mandate == 1) ? ' *' : '';
            $doc_model[] = $attachments;
        }
        if (Yii::$app->request->post()) {
            $doc_path = Yii::$app->params['document_upload'] . 'provisional_customer';

            if (Yii::$app->general->checkDirectory($doc_path)) {
                $error_msg = '';
                $save_model = [];
                Model::loadMultiple($doc_model, Yii::$app->request->post());

                foreach ($doc_model as $key => $d) {
                    $d->file_name = UploadedFile::getInstance($d, '[' . $key . ']file_name');
                    $id = (string) $id;
                    if (!empty($d->file_name)) {
                        $attach = TblAttachment::find()->where(['module_code' => $id, 'doc_id' => $d->doc_id])->one();
                        if (!empty($attach)) {
                            if ($d->file_name != $attach->file_name) {
                                $historyModel = new TblAttachmentHistory();
                                Yii::$app->operation->history($attach, $historyModel, UPDATE);
                                $save_model[] = $historyModel;
                            }
                        }
                        $file_name = 'provisional_customer' . '_' . $id . '_' . $d->doc_id . '_' . time() . '.' . $d->file_name->extension;
                        $d->attachment = $doc_path . '/' . $file_name;
                        if (!$d->file_name->saveAs($d->attachment)) {
                            $error_msg .= $d->doc_name . '<br/>';
                        }
                        $d->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $d->attachment;
                        $d->module_name = 'tbl_customer_master_provisional';
                        $d->file_name = $file_name;
                        $save_model[] = $d;
                    } else if ($d->is_mandate == 1) {
                        $error_msg .= $d->doc_name . '<br/>';
                    }
                }

                if (empty($error_msg)) {
                    $modelStages = new TblApprovalStagesDetail();
                    $modelStages->setApprovalData($model->union_code, 'tbl_customer_master_provisional', $model->customer_provisional_code, $save_model, $approval_stages);
                    $model->status = empty($approval_stages) ? 'Approve' : 'Register';
                    $save_model[] = $model;
                    $transaction = $this->generalModel->saveTransaction($save_model, ['Document Upload', 'create']);
                    if ($transaction == 'customRedirect') {
                        $record = ['status' => 'success', 'msg' => $this->redirect(['index'])];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg];
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

        return Yii::$app->controller->render('add_document', [
                    'model' => $model,
                    'doc_model' => $doc_model,
        ]);
    }

    /**
     * Updates an existing TblCustomerMasterProvisional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'updateFront';
        $x_col1 = explode('#', $this->model->x_col1);
        $prefix = Yii::$app->general->getforeignkey($this->model->customerTypePre, 'code_prefix');
        $exCode = str_replace($prefix, '', $this->model->customer_code_ex);
        if ($exCode != $this->model->customer_code_ex) {
            $this->model->prefix = $prefix;
            $this->model->customer_code_ex = $exCode;
        }
        if (isset($x_col1)) {
            if (isset($x_col1[0]) && isset($x_col1[1])) {
                $this->model->same_milk_type = $x_col1[0];
                $this->model->diff_milk_type = $x_col1[1];
            }
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblCustomerMasterProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $exCode = $this->model->customer_code_ex;
            $this->model->customer_code_ex = $prefix . $exCode;
            $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Customer Master Provisional', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
            $this->model->customer_code_ex = $exCode;
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblCustomerMasterProvisional model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblCustomerMasterProvisional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblCustomerMasterProvisional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCustomerMasterProvisional::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExcodePrefix() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $model = new TblCustomerMasterProvisional();
        $model->union_code = Yii::$app->request->post('union_code');
        $model->customer_type = Yii::$app->request->post('type');
        $response['status'] = 'success';
        $response['data'] = Yii::$app->general->getforeignkey($model->customerTypePre, 'code_prefix');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionPendingCustomerApproval() {
        $searchModel = new TblCustomerMasterProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TRUE);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => TRUE
        ]);
    }

    public function actionApproveCustomerMaster($id) {
        $model = TblProcessApproval::findOne($id);
        $model->scenario = 'approve';
        $historyApproval = new TblProcessApprovalHistory();
        Yii::$app->operation->history($model, $historyApproval, UPDATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save = [];
            $customer_error = '';
            $message = '';
            $model_save[] = $historyApproval;
            $model_save[] = $model;
            if (!empty($model_save)) {
                $model->ApprovalList($model, $model_save, $status);
                $customerModel = $this->findModel($model->process_code);
                $historyModel = new TblCustomerMasterProvisionalHistory();
                Yii::$app->operation->history($customerModel, $historyModel, UPDATE);
                $model_save[] = $historyModel;
                $customerModel->status = $status;
                $customerModel->remarks = $model->remarks;
                $model_save[] = $customerModel;
                $all_doc = [];
                $customerdoc = [];

                if ($status == 'Approve') {
                    $this->createCustomer($customerModel, $model_save, $all_doc, $customerdoc, $message);
                }
                if (!empty($message)) {
                    foreach ($message as $msg) {
                        $customer_error .= $msg;
                    }
                }
                if (empty($customer_error)) {

                    $transaction = $this->generalModel->saveTransaction($model_save, ['Customer Provisional Approval', 'edit']);

                    if ($transaction == 'customRedirect') {

                        if ($status == 'Approve') {
                            $baseDir = Yii::$app->basePath . '/' . Yii::$app->params['document_upload'];
                            $customerDir = $baseDir . 'customer';
                            $proCustomerDir = $baseDir . 'provisional_customer';
                            for ($i = 0; $i < count($all_doc); $i++) {
                                $fileName = basename($customerdoc[$i]);
                                $file = $customerDir . '/' . $fileName;
                                $upload = copy($proCustomerDir . '/' . $all_doc[$i], $file);
                                if ($upload) {
                                    if (file_exists($proCustomerDir . '/' . $all_doc[$i])) {
                                        unlink($proCustomerDir . '/' . $all_doc[$i]);
                                    }
                                }
                            }
                        }

                        return $this->redirect(['pending-customer-approval']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => $customer_error . ' in Customer Master']);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Customer Master provisional already approved by other user.'
                ]);
            }
        }
        return $this->render('approve_provisional_customer', [
                    'model' => $model,
        ]);
    }

    public function createCustomer($customerProvisional, &$model_save, &$all_attachment, &$customerdoc, $message) {
        if (!empty($customerProvisional)) {
            $customerModel = new TblCustomerMaster();
            $this->bankDetails = new TblBankDetails();
            $this->contactDetails = new TblContactDetails();
            $this->contactDetails->form_validation_type = 'customer-create';
            $validate = 1;

            $customerModel->scenario = 'createFront';
            $customerModel->attributes = $customerProvisional->attributes;
            $customerModel->customer_code = $customerModel->getCode();

            $exCode = $customerModel->customer_code_ex;
            if ($customerModel->validate()) {
                $model_save[] = $customerModel;

                $this->bankDetails->attributes = $customerProvisional->attributes;
                $bankValidate = 1;
                if (!empty($this->bankDetails->bank_code)) {
                    $this->bankDetails->setModel('customer', $customerModel->customer_code);
                    $this->bankDetails->scenario = 'bank_selected';
                    array_push($model_save, $this->bankDetails);

                    $bankValidate = Yii::$app->warning->codeWarningBankAc($this->bankDetails, 'warning');
                }

                $this->contactDetails->attributes = $customerProvisional->attributes;
                $this->contactDetails->load(Yii::$app->request->post());
                if (!empty($this->contactDetails->mobile_no)) {
                    $this->contactDetails->setModel('customer', $customerModel->customer_code);
                    array_push($model_save, $this->contactDetails);
                }

                if ($bankValidate == 1 && empty($customerModel->getErrors())) {
                    $customerModel->customer_code_ex = !empty($customerModel->prefix . $exCode) ? $customerModel->prefix . $exCode : $customerModel->customer_code_ex;
                }
                if ($bankValidate == 1 && $validate == 1 && empty($customerModel->getErrors())) {
                    $tblAttachment = new TblAttachment();
                    $customerProvisionalCode = (string) $customerProvisional->customer_provisional_code;
                    $tblAttachment = $tblAttachment->getAttachment($customerProvisionalCode, 'tbl_customer_master_provisional');
                    $doc_path = Yii::$app->params['document_upload'] . 'customer';

                    if (!empty($tblAttachment)) {
                        foreach ($tblAttachment as $key => $doc) {
                            $all_attachment[] = $doc->file_name;
                            $tblAttachments = new TblAttachment();
                            $tblAttachments->attributes = $doc->attributes;
                            if (Yii::$app->general->checkDirectory($doc_path)) {
                                if (!empty($doc->file_name)) {
                                    $attach = TblAttachment::find()
                                            ->where(['module_code' => $customerProvisionalCode, 'module_name' => 'tbl_customer_master_provisional', 'doc_id' => $doc->doc_id])
                                            ->one();
                                    $extension = explode('.', $doc->file_name)[1];
                                    $file_name = 'customer' . '_' . $customerModel->customer_code . '_' . $doc->doc_id . '_' . time() . '.' . $extension;
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
                                    $tblAttachments->module_name = 'tbl_customer_master';
                                    $tblAttachments->module_code = $customerModel->customer_code;
                                    $tblAttachments->file_name = $file_name;
                                    $customerdoc[] = $file_name;
                                    $model_save[] = $tblAttachments;
                                }
                            } else {
                                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                    'message' => 'Error while create directory.']);
                            }
                        }
                    }
                }
            } else {
                foreach ($customerModel->getErrors() as $errorkey => $value) {
                    $message = $value;
                }
            }
        }
    }

}
