<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCustomerMasterProvisional;
use app\modules\organisation\models\TblCustomerMasterProvisionalSearch;
use yii\web\NotFoundHttpException;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\document\models\TblAttachment;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\organisation\models\TblCustomerMasterProvisionalHistory;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblCustomerMasterHistory;
use app\modules\document\controllers\TblAttachmentController;
use app\modules\general\models\TblProcessApprovalSearch;

/**
 * TblCustomerMasterProvisionalController implements the CRUD actions for TblCustomerMasterProvisional model.
 */
class TblCustomerMasterProvisionalController extends \app\controllers\ChildController {

    public $bankDetails, $contactDetails;
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

        $processApprovalModel = new TblProcessApprovalSearch();
        $processApprovalModel->process_name = 'tbl_customer_master_provisional';
        $processApprovalModel->process_code = $id;
        $processApprovalDataProvider = $processApprovalModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                    'processApprovalModel' => $processApprovalModel,
                    'processApprovalDataProvider' => $processApprovalDataProvider
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
                $this->model->data_post_id = Yii::$app->general->getUuid();
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
        $module_code = $model->customer_provisional_code;
        $module_name = 'tbl_customer_master_provisional';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actionDocumentUpload('provisional_customer', $id, $model, $module_code, $module_name, true);
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
                if ($this->model->status == 'Pending' || $this->model->status == 'Reroute') {
                    return $this->redirect(['document-upload', 'id' => $this->model->customer_provisional_code]);
                } else {
                    return $this->redirect(['pending-customer-approval']);
                }
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
            $status = '';
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

                $customerCreationPendingForSapApproval = Yii::$app->general->getUnionConfiguration($customerModel->union_code, 'customer_creation_pending_for_sap_approval', 'PORTAL') == '1';
                $customerModel->customer_status = 0;
                if (strtolower($status) === 'approve') {
                    $customerModel->approved_at = date('Y-m-d H:i:s');
                    $customerModel->approved_by = Yii::$app->user->identity->user_code;
                    $customerModel->customer_status = $customerCreationPendingForSapApproval ? 0 : 1;
                }

                $model_save[] = $customerModel;
                $all_doc = [];
                $customerdoc = [];
                if ($customerModel->validate()) {
                    if ($status == 'Approve' && $customerCreationPendingForSapApproval != '1') {
                        $this->createCustomer($customerModel, $model_save, $all_doc, $customerdoc, $message);
                    }
                } else {
                    foreach ($customerModel->getErrors() as $errorkey => $value) {
                        $message = $value;
                    }
                }
                if (!empty($message)) {
                    foreach ($message as $msg) {
                        $customer_error .= !empty($customer_error) ? '<br>' . $msg : $msg;
                    }
                }
                if (empty($customer_error)) {

                    $transaction = $this->generalModel->saveTransaction($model_save, ['Customer Provisional Approval', 'edit']);

                    if ($transaction == 'customRedirect') {

                        // if ($status == 'Approve' && $customerCreationPendingForSapApproval != '1') {
                        if ($status == 'Approve') {
                            $baseDir = Yii::$app->basePath . '/' . Yii::$app->params['document_upload'];
                            $customerDir = $baseDir . 'customer';
                            $proCustomerDir = $baseDir . 'provisional_customer';
                            for ($i = 0; $i < count($all_doc); $i++) {
                                $fileName = basename($customerdoc[$i]);
                                $file = $customerDir . '/' . $fileName;
                                if (file_exists($proCustomerDir . '/' . $all_doc[$i])) {
                                    $upload = copy($proCustomerDir . '/' . $all_doc[$i], $file);
                                    if ($upload) {
                                        unlink($proCustomerDir . '/' . $all_doc[$i]);
                                    }
                                }
                            }
                        }

                        return $this->redirect(['pending-customer-approval']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => $customer_error . ' in Customer Master.']);
                    return $this->redirect(['update', 'id' => $model->process_code]);
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

    public function createCustomer($customerProvisional, &$model_save, &$all_attachment, &$customerdoc, &$message) {
        if (!empty($customerProvisional)) {
            $customerProvisional->is_approved = 1;
            $customerModel = new TblCustomerMaster();
            if ($customerProvisional->provisional_from == 'mobile_update') {
                $this->bankDetails = TblBankDetails::updateBankDetails($customerProvisional->customer_code, $customerProvisional->bank_account_no, 'customer', $model_save);
                $this->contactDetails = TblContactDetails::updateContactDetails($customerProvisional->customer_code, $customerProvisional->mobile_no, 'customer', $model_save);
            } else {
                $this->bankDetails = new TblBankDetails();
                $this->contactDetails = new TblContactDetails();
            }
            $this->contactDetails->form_validation_type = 'customer-create';
            $customerModel->scenario = 'createFront';
            if ($customerProvisional->provisional_from == 'mobile_update') {
                $customerModel = TblCustomerMaster::find()->where(['customer_code' => $customerProvisional->customer_code])->one();
                $customerHistoryModel = new TblCustomerMasterHistory();
                Yii::$app->operation->history($customerModel, $customerHistoryModel, UPDATE);
                $model_save[] = $customerHistoryModel;
                foreach ($customerProvisional->attributes as $key => $value) {
                    if ($value !== null && $value !== '' && $customerModel->hasAttribute($key)) {
                        $customerModel->$key = $value;
                    }
                }
            } else {
                $customerModel->attributes = $customerProvisional->attributes;
                $customerModel->customer_code = $customerModel->getCode();
            }

            $exCode = $customerModel->customer_code_ex;
            if ($customerModel->validate()) {
                $model_save[] = $customerModel;

                $this->bankDetails->attributes = $customerProvisional->attributes;
                $this->bankDetails->is_verified = $this->bankDetails->is_kyc_verified = $customerProvisional->is_bank_verify;
                $bankValidate = 1;
                if (!empty($this->bankDetails->bank_code)) {
                    if (empty($this->bankDetails->detail_code)) {
                        $this->bankDetails->setModel('customer', $customerModel->customer_code);
                    }
                    $this->bankDetails->scenario = 'bank_selected';
                    array_push($model_save, $this->bankDetails);

                    $bankValidate = Yii::$app->warning->codeWarningBankAc($this->bankDetails, 'warning');
                }

                $this->contactDetails->attributes = $customerProvisional->attributes;
                if (!empty($this->contactDetails->mobile_no)) {
                    if (empty($this->contactDetails->detail_code)) {
                        $this->contactDetails->setModel('customer', $customerModel->customer_code);
                    }
                    array_push($model_save, $this->contactDetails);
                }

                if ($bankValidate == 1) {
                    $customerModel->customer_code_ex = !empty($customerModel->prefix . $exCode) ? $customerModel->prefix . $exCode : $customerModel->customer_code_ex;

                    $tblAttachment = new TblAttachment();
                    $customerProvisionalCode = (string) $customerProvisional->customer_provisional_code;
                    $tblAttachment->AttachmentSave($customerProvisionalCode, 'tbl_customer_master_provisional', 'customer', $customerModel->customer_code, 'tbl_customer_master', $all_attachment, $model_save, $customerdoc);
                }
            } else {
                foreach ($customerModel->getErrors() as $errorkey => $value) {
                    $message = $value;
                }
            }
        }
    }

    public function actionApprovedAttachmentDetails() {
        $searchModel = new TblCustomerMasterProvisionalSearch();
        $searchModel->scenario = 'ApprovedAttachmentDetails';
        $dataProvider = $searchModel->approvedattachmentdetailssearch(Yii::$app->request->queryParams);

        $from_date = Yii::$app->request->getQueryParam('from_date');
        $to_date = Yii::$app->request->getQueryParam('to_date');

        return $this->render('index_other', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
        ]);
    }

    public function actionRfcRePush($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblCustomerMasterProvisionalHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->data_post_status = 0;
        $record = [];
        if ($this->model->save(true, false)) {
            $historyModel->save();
            $record = ['status' => 'success', 'msg' => 'Customer Master Provisional re-pushed successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Failed to re-push Customer Master Provisional.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionSapErrorDataList() {
        $searchModel = new TblCustomerMasterProvisionalSearch();
        $searchModel->data_post_status = 3;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false);

        return $this->render('index_sap', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateSapErrorData($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update_sap_error_data';
        $this->model->scenario = 'updateFront';
        if (Yii::$app->request->post()) {
            $historyModel = new TblCustomerMasterProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->data_post_status = 0;
            $this->model->resp_desc = $this->model->resp_status = $this->model->response_datetime = $this->model->picked_datetime = $this->model->response_msg = NULL;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Customer Master Provisional', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['sap-error-data-list']);
            }
        }
        return $this->customRender();
    }

}
