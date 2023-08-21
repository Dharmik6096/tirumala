<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblDcsProvisional;
use app\modules\organisation\models\TblDcsProvisionalSearch;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\organisation\models\TblDcsVillageMapping;
use app\modules\organisation\models\TblDcsVillageMappingHistory;
use app\modules\organisation\models\TblDcsBmcSearch;
use app\modules\organisation\models\TblDcsMilkType;
use app\modules\organisation\models\TblDcsMilkTypeHistory;
use app\modules\organisation\models\TblMccPlant;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\general\models\TblSocietyVendor;
use app\models\TblUserOrganizationMapping;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\Model;
use app\modules\organisation\models\TblSocietyCollection;
use app\modules\organisation\models\TblSocietyCollectionHistory;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilitySearch;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityHistory;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\general\models\TblDpuIncentiveMaster;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcsDeactiveSearch;
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\details\models\TblBankDetailsHistory;
use app\modules\details\models\TblContactDetailsHistory;
use yii\base\UserException;
use ReflectionClass;
use app\models\ChildModel;
use app\modules\bkgprocess\models\TblOrgFileCreator;
use app\modules\bkgprocess\models\TblOrgFileLog;
use app\modules\document\models\TblDocumentMapping;
use app\modules\document\models\TblAttachment;
use app\modules\organisation\models\TblDcsProvisionalHistory;
use yii\web\UploadedFile;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\document\models\TblAttachmentHistory;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblProcessApproval;

/**
 * TblDcsController implements the CRUD actions for TblDcs model.
 */
class TblDcsProvisionalController extends ChildController {

    public $bankDetails;
    public $contactDetails;
//    public $freeAccessActions = ['dcs-list', 'get-bmc-dcs', 'merge-dcs-customer-list', 'payment-cycle-dcs-list', 'merge-bmc-dcs-list'];
    public $freeAccessActions = [];
    public $showIsBMC;

    /**
     * Lists all TblDcs models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => FALSE
        ]);
    }

    /**
     * Creates a new TblDcs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($bmc_code = '', $is_bmc = 0) {
        $this->model = new TblDcsProvisional();
        $this->viewFile = 'create';
        $this->model->scenario = 'createDcs';
        $this->model->district_code = Yii::$app->session->get('Districts');
        $this->model->valid_from = date('Y-m-d');
        $this->showIsBMC = $is_bmc == 1 ? true : false;
        $this->model->bmc_code = !empty($bmc_code) ? $bmc_code : $this->model->bmc_code;
        $this->model->is_bmc = $is_bmc;
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->street1 != '' && $this->model->street2 != '') {
                $this->model->address = $this->model->fullAddress();
            } elseif ($this->model->street1 == '' && $this->model->street2 != '') {
                $this->model->address = $this->model->street2;
            } else {
                $this->model->address = $this->model->street1;
            }
            $this->setModel();
            $modelMilkType = $this->setMilk();
            $this->model->default_milk_type = !empty($this->model->milk_type_auto) ? 8 : $this->model->setDefaultMilkType($modelMilkType);
            $this->model->cutoff = '0000';
            if (!empty($this->model->lower_milk_type) && !empty($this->model->cutoff_val)) {
                $val = str_replace('.', '', $this->model->cutoff_val);
                $val = str_pad($val, 3, '0', STR_PAD_LEFT);
                $milkType = Yii::$app->general->getforeignkey($this->model->lowerMilkType, 'short_name');
                $cutOffVal = $val . strtoupper($milkType);
                $this->model->cutoff = substr($cutOffVal, -4);
            }
            $this->model->milk_type = implode(',', $this->model->milk_type_code);
            $this->model->vendor_code = $this->model->vendor;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['society', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDcs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'updateDcs';
        $this->model->tmcc_code = $this->model->dcs_code;
        $this->viewFile = 'update';
        $this->showIsBMC = FALSE;
        $address = explode(',', $this->model->address);
        if (isset($address)) {
            if (isset($address[0]))
                $this->model->street1 = $address[0];
            if (isset($address[1]))
                $this->model->street2 = $address[1];
        }
        $x_col1 = explode('#', $this->model->x_col1);
        if (isset($x_col1)) {
            if (isset($x_col1[0]) && isset($x_col1[1])) {
                $this->model->same_milk_type = $x_col1[0];
                $this->model->diff_milk_type = $x_col1[1];
            }
        }
        if (!empty($this->model->cutoff_val) && !empty($this->model->lower_milk_type)) {
            $this->model->cutoff = 1;
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel();
            $this->model->street1 = $_POST['TblDcsProvisional']['street1'];
            $this->model->street2 = $_POST['TblDcsProvisional']['street2'];
            if ($this->model->street1 != '' && $this->model->street2 != '') {
                $this->model->address = $this->model->fullAddress();
            } elseif ($this->model->street1 == '' && $this->model->street2 != '') {
                $this->model->address = $this->model->street2;
            } else {
                $this->model->address = $this->model->street1;
            }
            if (!empty($this->model->milk_type_auto)) {
                $this->model->milk_type_code = [1, 2, 3];
            }
            if (!empty($this->model->lower_milk_type) && !empty($this->model->cutoff_val)) {
                $val = str_replace('.', '', $this->model->cutoff_val);
                $val = str_pad($val, 3, '0', STR_PAD_LEFT);
                $milkType = Yii::$app->general->getforeignkey($this->model->lowerMilkType, 'short_name');
                $cutOffVal = $val . strtoupper($milkType);
                $this->model->cutoff = substr($cutOffVal, -4);
            }
            $this->model->milk_type = implode(',', $this->model->milk_type_code);
            $this->model->vendor_code = $this->model->vendor;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['society', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Displays a single TblDcs model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblDcsProvisionalSearch();
        $searchModel->dcs_provisional_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $id, 'module_name' => 'tbl_dcs_provisional']),
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
     * Finds the TblDcsProvisional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsProvisional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsProvisional::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDocumentUpload($id) {
        $model = $this->findModel($id);
        $model->scenario = 'uploadDoc';
        $doc_mapping = TblDocumentMapping::find()->where(['master_type' => 'provisional_dcs'])->all();
        $doc_model = [];
        foreach ($doc_mapping as $doc) {
            $attachments = $doc->uploadedDocument($doc->doc_id, $id, 'provisional_dcs');
            $master_doc = $doc->docId;
            if (empty($attachments)) {
                $attachments = new TblAttachment();
                $attachments->module_code = $model->dcs_provisional_code;
                $attachments->doc_id = $doc->doc_id;
            }
            $attachments->is_mandate = $doc->is_mandate;
            $attachments->attachment_type = $master_doc->doc_ext;
            $attachments->doc_name = $master_doc->doc_name .= ($doc->is_mandate == 1) ? ' *' : '';
            $doc_model[] = $attachments;
        }
        if (Yii::$app->request->post()) {
            $doc_path = Yii::$app->params['document_upload'] . 'provisional_dcs';

            if (Yii::$app->general->checkDirectory($doc_path)) {
                $error_msg = '';
                $save_model = [];
                Model::loadMultiple($doc_model, Yii::$app->request->post());

                foreach ($doc_model as $key => $d) {
                    $d->file_name = UploadedFile::getInstance($d, '[' . $key . ']file_name');
                    if (!empty($d->file_name)) {
                        $attach = TblAttachment::find()->where(['module_code' => $id, 'doc_id' => $d->doc_id])->one();
                        if (!empty($attach)) {
                            if ($d->file_name != $attach->file_name) {
                                $historyModel = new TblAttachmentHistory();
                                Yii::$app->operation->history($attach, $historyModel, UPDATE);
                                $save_model[] = $historyModel;
                            }
                        }
                        $file_name = 'provisional_dcs' . '_' . $id . '_' . $d->doc_id . '_' . time() . '.' . $d->file_name->extension;
                        $d->attachment = $doc_path . '/' . $file_name;
                        if (!$d->file_name->saveAs($d->attachment)) {
                            $error_msg .= $d->doc_name . '<br/>';
                        }
                        $d->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $d->attachment;
                        $d->module_name = 'tbl_dcs_provisional';
                        $d->file_name = $file_name;
                        $save_model[] = $d;
                    } else if ($d->is_mandate == 1) {
                        $error_msg .= $d->doc_name . '<br/>';
                    }
                }

                if (empty($error_msg)) {
                    $modelStages = new TblApprovalStagesDetail();
                    $modelStages->setApprovalData($model->union_code, 'society', $model->dcs_provisional_code, $save_model, $approval_stages);
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

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'bankDetails' => $this->bankDetails,
                    'contactDetails' => $this->contactDetails,
                    'showIsBMC' => $this->showIsBMC
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['document-upload', 'id' => $this->model->dcs_provisional_code]);
    }

    private function setMilk() {
        $milkArray = $this->model->milk_type_code;
        if ($this->model->milk_type_auto == 1) {
            $milkArray = ["1", "2", "3"];
        }
        $list = [];
        foreach ($milkArray as $row) {
            $modelMilk = new TblDcsMilkType();
            $modelMilk->dcs_code = $this->model->dcs_code;
            $modelMilk->milk_type_code = $row;
            $modelMilk->is_active = 1;
            array_push($list, $modelMilk);
        }
        return $list;
    }

    private function setModel() {
        $this->model->dcs_name = ucwords($this->model->dcs_name);
        $this->model->pan_no = strtoupper($this->model->pan_no);
        $this->model->dcs_short_name = ucwords($this->model->dcs_short_name);
        //$this->model->route_code = empty($this->model->route_code) ? null : $this->model->route_code;
        $this->model->registration_date = ($this->model->registration_date == '') ? null : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
        $this->model->effective_date = ($this->model->effective_date == '') ? null : Yii::$app->formatter->asDate($this->model->effective_date, DATE_FORMAT);
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
        $this->model->mcc_plant_code = Yii::$app->general->getforeignkey($this->model->bmcCode, 'mcc_plant_code');
        $this->model->plant_code = Yii::$app->general->getforeignkey($this->model->mccPlantCode, 'plant_code');
        $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
    }
    
    public function actionPendingApproval() {
        $searchModel = new TblDcsProvisionalSearch();
//        echo "<pre>";
//        print_r(\Yii::$app->user->identity);
//        die;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TRUE);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => TRUE
        ]);
    }
    
    public function actionApproveDcs($id) {
        $model = TblProcessApproval::findOne($id);
        $model->scenario = 'approve';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save = [];
            $model->status_date = date('Y-m-d H:i:s');
            $model->status_by = \Yii::$app->user->identity->user_code;
            if ($model->approval_mode == 'flexi') {
                $level_user = TblProcessApproval::find()
                        ->where(['application_id' => $model->application_id, 'level' => $model->level])
                        ->andWhere(['IS', 'application_status', NULL])
                        ->all();
                foreach ($level_user as $approval) {
                    $approval->status_date = $model->status_date;
                    $approval->status_by = $model->status_by;
                    //  $approval->approved_value = $model->approved_value;
                    $approval->application_status = $model->application_status;
                    $approval->status_remarks = $model->status_remarks;
                    $model_save[] = $approval;
                }
            } else {
                $model->status_date = date('Y-m-d H:i:s');
                $model->status_by = \Yii::$app->user->identity->user_code;
                $model_save[] = $model;
            }
            if (!empty($model_save)) {
                $next_count = TblSchemeApplicationApproval::find()
                        ->where(['application_id' => $model->application_id, 'level' => $model->level + 1])
                        ->count();
                $status = ($next_count > 0 && $model->application_status == 'approved') ? 'inprocess' : $model->application_status;
                $application = $this->findModel($model->application_id);
                $historyModel = new TblSchemeApplicationHistory();
                Yii::$app->operation->history($application, $historyModel, UPDATE);
                $model_save[] = $historyModel;
                $application->application_status = $status;
                $application->status_date = $model->status_date;
                $application->status_by = $model->status_by;
                //  $application->approved_value = $model->approved_value;
                $application->status_remarks = $model->status_remarks;
                $model_save[] = $application;
                $transaction = $this->generalModel->saveTransaction($model_save, ['Scheme Application Approval', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['pending-approval']);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Application already approved by other user.']);
            }
        }
        return $this->render('approve_dcs', [
                    'model' => $model,
        ]);
    }
}
