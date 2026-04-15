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
use app\modules\organisation\models\TblDcs;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\document\controllers\TblAttachmentController;
use app\modules\general\models\TblProcessApprovalSearch;

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
        $this->model->registration_date = date('Y-m-d');
        $this->model->valid_from = date('Y-m-d');
        $this->showIsBMC = $is_bmc == 1 ? true : false;
        $this->model->bmc_code = !empty($bmc_code) ? $bmc_code : $this->model->bmc_code;
        $this->model->is_bmc = $is_bmc;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->getCode();
            $this->model->data_post_id = Yii::$app->general->getUuid();
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
            $this->model->milk_type = !empty($this->model->milk_type_code) ? implode(',', $this->model->milk_type_code) : '';
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
        $this->model->gender_code = $this->model->gender;
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if ($this->model->validate()) {
                $this->setModel();
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
                $this->model->milk_type = !empty($this->model->milk_type_code) ? implode(',', $this->model->milk_type_code) : '';
                $this->model->vendor_code = $this->model->vendor;
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['society', 'edit']);
                if ($transaction == 'customRedirect') {
                    if ($this->model->status == 'Pending' || $this->model->status == 'Reroute') {
                        return $this->redirect(['document-upload', 'id' => $this->model->dcs_provisional_code]);
                    } else {
                        return $this->redirect(['pending-approval']);
                    }
                }
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
        $id = (string) $id;
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $id, 'module_name' => 'tbl_dcs_provisional']),
        ]);

        $processApprovalModel = new TblProcessApprovalSearch();
        $processApprovalModel->process_name = 'society';
        $processApprovalModel->process_code = $id;
        $processApprovalDataProvider = $processApprovalModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                    'processApprovalModel' => $processApprovalModel,
                    'processApprovalDataProvider' => $processApprovalDataProvider,
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
        $model->scenario = 'beforeDocUpload';
        if (!$model->validate()) {
            $errors = $model->getErrors();
            $errorMessage = implode('<br>', array_merge(...array_values($errors)));
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'message' => 'Validation Error: <br>' . $errorMessage
            ]);
            return $this->redirect(['update', 'id' => $id]);
        }
        $model->scenario = 'uploadDoc';
        $module_code = $model->dcs_provisional_code;
        $module_name = 'tbl_dcs_provisional';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('provisional_dcs', $id, $model, $module_code, $module_name, true);
    }

    protected function customRender() {
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'bankDetails' => $this->bankDetails,
                    'contactDetails' => $this->contactDetails,
                    'showIsBMC' => $this->showIsBMC
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['document-upload', 'id' => $this->model->dcs_provisional_code]);
    }

    private function setMilk($dcsUpdate = false) {
        $milkArray = $this->model->milk_type_code;
        if ($this->model->milk_type_auto == 1) {
            $milkArray = [1, 2, 3];
        }
        $list = [];

        if ($dcsUpdate) {
            $milkType = TblDcsMilkType::find()->where(['dcs_code' => $this->model->dcs_code, 'is_active' => 1])->all();
            $returnedArray = \yii\helpers\ArrayHelper::map($milkType, 'milk_type_code', 'milk_type_code');

            $toRevoke = array_diff($returnedArray, $milkArray);
            $toAssign = array_diff($milkArray, $returnedArray);
            foreach ($toRevoke as $value) {
                $milkModel = TblDcsMilkType::find()->where(['dcs_code' => $this->model->dcs_code, 'milk_type_code' => $value])->one();
                $milkHistory = new TblDcsMilkTypeHistory();
                Yii::$app->operation->history($milkModel, $milkHistory, DELETE);
                array_push($list, $milkHistory);
                array_push($list, $milkModel);
            }
            foreach ($toAssign as $value) {
                $milkModel = new TblDcsMilkType();
                $milkModel->dcs_code = $this->model->dcs_code;
                $milkModel->milk_type_code = $value;
                $milkModel->is_active = 1;
                array_push($list, $milkModel);
            }
        } else {
            foreach ($milkArray as $row) {
                $modelMilk = new TblDcsMilkType();
                $modelMilk->dcs_code = $this->model->dcs_code;
                $modelMilk->milk_type_code = $row;
                $modelMilk->is_active = 1;
                array_push($list, $modelMilk);
            }
        }

        return $list;
    }

    private function setModel() {
        $this->model->dcs_name = ucwords($this->model->dcs_name);
        $this->model->pan_no = strtoupper($this->model->pan_no);
        $this->model->dcs_short_name = ucwords($this->model->dcs_short_name);
        $this->model->registration_date = ($this->model->registration_date == '') ? null : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
        $this->model->effective_date = ($this->model->effective_date == '') ? null : Yii::$app->formatter->asDate($this->model->effective_date, DATE_FORMAT);
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
        $this->model->security_return_date = ($this->model->security_return_date == '') ? null : Yii::$app->formatter->asDate($this->model->security_return_date, DATE_FORMAT);
        $this->model->mcc_plant_code = Yii::$app->general->getforeignkey($this->model->bmcCode, 'mcc_plant_code');
        $this->model->plant_code = Yii::$app->general->getforeignkey($this->model->mccPlantCode, 'plant_code');
        $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
        $this->model->gender = $this->model->gender_code;
    }

    public function actionPendingApproval() {
        $searchModel = new TblDcsProvisionalSearch();
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
        $historyApproval = new TblProcessApprovalHistory();
        Yii::$app->operation->history($model, $historyApproval, UPDATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save = [];
            $model_save[] = $historyApproval;
            $model_save[] = $model;
            $status = '';
            if (!empty($model_save)) {
                $model->ApprovalList($model, $model_save, $status);
                $dcsModel = $this->findModel($model->process_code);
                $historyModel = new TblDcsProvisionalHistory();
                Yii::$app->operation->history($dcsModel, $historyModel, UPDATE);
                $model_save[] = $historyModel;
                $dcsModel->status = $status;
                $dcsModel->remarks = $model->remarks;
                $dcsModel->scenario = 'approveDcs';

                $dcsCreationPendingForSapApproval = Yii::$app->general->getUnionConfiguration($dcsModel->union_code, 'dcs_creation_pending_for_sap_approval', 'PORTAL');
                $dcsModel->dcs_status = 0;
                if (strtolower($status) === 'approve') {
                    $dcsModel->approved_at = date('Y-m-d H:i:s');
                    $dcsModel->approved_by = Yii::$app->user->identity->user_code;
                    $dcsModel->dcs_status = $dcsCreationPendingForSapApproval ? 0 : 1;
                } else if(strtolower($status) === 'reject'){
                    $dcsModel->scenario = 'reject';
                }
                $model_save[] = $dcsModel;
                $all_doc = [];
                $dcsdoc = [];
                $message = [];
                $dcs_error = '';
                if($dcsModel->validate()){
                    if ($status == 'Approve' && $dcsCreationPendingForSapApproval != '1') {
                        $transaction = $this->createDcs($dcsModel, $model_save, $all_doc, $dcsdoc, $message);
                        if (!empty($message)) {
                            foreach ($message as $msg) {
                                $dcs_error .= $msg;
                            }
                        }
                    } else {
                        $transaction = $this->generalModel->saveTransaction($model_save, ['Dcs Provisional Approval', 'edit']);
                    }
                    if ($transaction == 'customRedirect' && empty($dcs_error)) {

                        if ($status == 'Approve') {
                            $baseDir = Yii::$app->basePath . '/' . Yii::$app->params['document_upload'];
                            $dcsDir = $baseDir . 'dcs';
                            $proDcsDir = $baseDir . 'provisional_dcs';
                            for ($i = 0; $i < count($all_doc); $i++) {
                                $fileName = basename($dcsdoc[$i]);
                                $file = $dcsDir . '/' . $fileName;
                                if (file_exists($proDcsDir . '/' . $all_doc[$i])) {
                                    $upload = copy($proDcsDir . '/' . $all_doc[$i], $file);
                                    if ($upload) {
                                        unlink($proDcsDir . '/' . $all_doc[$i]);
                                    }
                                }
                            }
                        }

                        return $this->redirect(['pending-approval']);
                    }
                } else {
                    if (!empty($dcs_error)) {
                    } else if(!empty($dcsModel->getErrors())){
                        foreach($dcsModel->getErrors() as $error) {
                            $dcs_error = !empty($dcs_error) ? $dcs_error.'<br> '.$error[0] : $error[0];
                        }
                    }
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => $dcs_error . ' in DCS.']);
                    return $this->redirect(['update', 'id' => $dcsModel->dcs_provisional_code]);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Dcs provisional already approved by other user.'
                ]);
            }
        }
        return $this->render('approve_dcs', [
                    'model' => $model,
        ]);
    }

    public function createDcs($dcsProvisional, $model_save, &$all_attachment, &$dcsdoc, &$message, $skipUniqueValidation = false, $isWeb = true) {
        if (!empty($dcsProvisional)) {
            $dcsProvisional->is_approved = 1;
            $this->model = new TblDcs();
            $this->model->scenario = 'createDcs';
            $mapList = [];
            if ($dcsProvisional->provisional_from == 'mobile_update') {
                $this->bankDetails = TblBankDetails::updateBankDetails($dcsProvisional->dcs_code, $dcsProvisional->bank_account_no, 'society', $mapList);
                $this->contactDetails = TblContactDetails::updateContactDetails($dcsProvisional->dcs_code, $dcsProvisional->mobile_no, 'society', $mapList);
            } else {
                $this->bankDetails = new TblBankDetails();
                $this->contactDetails = new TblContactDetails();
            }
            $this->contactDetails->form_validation_type = 'dcs-create';
            $validate = 1;
            $dcsProvisional->vendor = $dcsProvisional->vendor_code;
            $dcsProvisional->milk_type_code = !empty($dcsProvisional->milk_type) ? explode(',', $dcsProvisional->milk_type) : [];
            $dcsProvisional->created_at = '';
            $dcsProvisional->created_by = '';
            $dcsProvisional->updated_at = '';
            $dcsProvisional->updated_by = '';
            $dcsProvisional->remarks = '';
            $oldVillage = '';
            if ($dcsProvisional->provisional_from == 'mobile_update') {
                $this->model = TblDcs::find()->where(['dcs_code' => $dcsProvisional->dcs_code])->one();
                $historyDcsModel = new TblDcsHistory();
                Yii::$app->operation->history($this->model, $historyDcsModel, UPDATE);
                $model_save[] = $historyDcsModel;
                $oldVillage = $this->model->village_code;
                foreach ($dcsProvisional->attributes as $key => $value) {
                    if ($value != null && $value != '' && $this->model->hasAttribute($key)) {
                        $this->model->$key = $value;
                    }
                }
            } else {
                $this->model->attributes = $dcsProvisional->attributes;
            }
            $this->model->scenario = 'createDcs';
            $this->model->vendor = $dcsProvisional->vendor;
            $this->model->milk_type_code = $dcsProvisional->milk_type_code;
            $this->model->auto_member_create = $dcsProvisional->auto_member_create;

            $this->model->dcs_code = ($dcsProvisional->provisional_from == 'mobile_update') ? $dcsProvisional->dcs_code : $this->model->getCode();
            //set mapping data
            if ($oldVillage != $this->model->village_code) {
                if($dcsProvisional->provisional_from == 'mobile_update'){
                    $oldModelMapping = TblDcsVillageMapping::find()->where(['dcs_code' => $this->model->dcs_code, 'village_code' => $oldVillage])->one();
                    if (!empty($oldModelMapping)) {
                        $modelMapping = $oldModelMapping;
                        $mappingHistory = new TblDcsVillageMappingHistory();
                        Yii::$app->operation->history($oldModelMapping, $mappingHistory, DELETE);
                        array_push($mapList, $mappingHistory);
                        array_push($mapList, $oldModelMapping);
                    }
                }
                if (!empty($this->model->village_code)) {
                    $modelMapping = new TblDcsVillageMapping();
                    $this->setMapping($modelMapping);
                    array_push($mapList, $modelMapping);
                }
            }
            if($dcsProvisional->provisional_from != 'mobile_update'){
                $modelCodes = new TblSocietyCodes();
                $modelCodes->dcs_code = $this->model->dcs_code;
                $modelCodes->bipl_code = $modelCodes->getBiplCode($this->model->village_code);
                $modelCodes->union_code = $this->model->union_code;
                $modelCodes->bmc_code = $this->model->bmc_code;
                array_push($mapList, $modelCodes);
            }

            $this->bankDetails->attributes = $dcsProvisional->attributes;
            $this->bankDetails->is_kyc_verified = $this->bankDetails->is_verified = $dcsProvisional->is_bank_verify;
            $bankValidate = 1;
            if (!empty($this->bankDetails->bank_code)) {
                if(empty($this->bankDetails->detail_code)){
                    $this->bankDetails->setModel('society', $this->model->dcs_code);
                }
                $this->bankDetails->scenario = 'bank_selected';
                array_push($mapList, $this->bankDetails);
                $bankValidate = Yii::$app->warning->codeWarningBankAc($this->bankDetails);
            }

            $this->contactDetails->attributes = $dcsProvisional->attributes;
            if (!empty($this->contactDetails->mobile_no)) {
                if(empty($this->contactDetails->detail_code)){
                    $this->contactDetails->setModel('society', $this->model->dcs_code);
                }
                array_push($mapList, $this->contactDetails);
            }
            //set milk type data
            if ($this->model->default_milk_type == 8) {
                $this->model->milk_type_auto = 1;
            }
            $modelMilkType = $this->setMilk($dcsProvisional->provisional_from == 'mobile_update');
            if (!empty($modelMilkType))
                $mapList = array_merge($mapList, $modelMilkType);
            //set vendor applicability
            if ($this->model->vendor != 'NA') {
                $vendorModel = null;
                $exists = ($dcsProvisional->provisional_from == 'mobile_update') ? TblSocietyVendor::find()->where(['dcs_code' => $this->model->dcs_code])->exists() : false;
                if (!$exists) {
                    $vendorModel = new TblSocietyVendor();
                    $vendorModel->dcs_code = $this->model->dcs_code;
                    $vendorModel->vendor_code = $this->model->vendor_code;
                    $mapList[] = $vendorModel;
                }
            }
            if ($bankValidate == 1 && !$skipUniqueValidation) {
                $msg = $this->model->dcs_name . ' for dcs/subcenter/collection center';
                $validate = Yii::$app->warning->unique($this->model, 'dcs_name', $this->model->dcs_name, $msg);
            }
            if ($bankValidate == 1 && $validate == 1 && empty($this->model->getErrors())) {
                if ($dcsProvisional->provisional_from != 'mobile_update') {
                    $this->model->setModelData($this->model, $mapList);
                }
                $this->model->cutoff = '0000';
                if (!empty($this->model->lower_milk_type) && !empty($this->model->cutoff_val)) {
                    $val = str_replace('.', '', $this->model->cutoff_val);
                    $val = str_pad($val, 3, '0', STR_PAD_LEFT);
                    $milkType = Yii::$app->general->getforeignkey($this->model->lowerMilkType, 'short_name');
                    $cutOffVal = $val . strtoupper($milkType);
                    $this->model->cutoff = substr($cutOffVal, -4);
                }
                $tblAttachment = new TblAttachment();
                $dcsProvisionalCode = (string) $dcsProvisional->dcs_provisional_code;
                $tblAttachment->AttachmentSave($dcsProvisionalCode, 'tbl_dcs_provisional', 'dcs', $this->model->dcs_code, 'tbl_dcs', $all_attachment, $model_save, $dcsdoc);

                $saveError = '';
                $transaction = $this->saveDcs($this->model, $mapList, $model_save, ['society', 'create'], $isWeb, $saveError);
                    if ($transaction == 'customRedirect' && !empty($vendorModel)) {
                        $orgMap = [];
                        $userModel = new User();
                        $users = $userModel->findByRole([$vendorModel->vendor_code]);
                        foreach ($users as $user) {
                            if (empty($user->user_type_id) || $user->user_type_id == 7) {
                                if (empty($user->user_type_id)) {
                                    $user->user_type_id = 7;
                                    array_push($orgMap, $user);
                                }
                                $modelNew = new TblUserOrganizationMapping();
                                $modelNew->organization_code = $vendorModel->dcs_code;
                                $modelNew->organization_type = 'DCS';
                                $modelNew->user_id = $user->id;
                                $modelNew->is_active = $user->is_active;
                                Yii::$app->operation->defaults($modelNew, INSERT);
                                array_push($orgMap, $modelNew);
                            }
                        }
                        if (strtolower($vendorModel->vendor_code) == 'eipl') {
                            $path = Yii::$app->basePath . '/' . Yii::$app->params['eiplDirPath'] . $vendorModel->dcs_code . '/';
                            if (!file_exists($path) || !is_dir($path)) {
                                FileHelper::createDirectory($path);
                            }
                        }
                        $this->generalModel->saveTransaction($orgMap, ['society', 'create']);
                    } else if (!empty($saveError)) {
                        $message[] = $saveError;
                    }
                return $transaction;
            } else {
                foreach ($this->model->getErrors() as $errorkey => $value) {
                    $message = $value;
                }
            }

            return 'customRender';
        }
    }

    private function setMapping(&$modelMapping) {
        $modelMapping->dcs_code = $this->model->dcs_code;
        $modelMapping->village_code = $this->model->village_code;
        $modelMapping->is_active = $this->model->is_active;
    }

    public function saveDcs($model, $childModel, $provisionalModel, $message, $isWeb, &$errorMsg = '') {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $allErrors = [];
            foreach ($provisionalModel as $m) {
                if (!in_array(FALSE, $master)) {
                    $master[] = $m->save();
                    if (in_array(FALSE, $master) && $m->hasErrors()) {
                        foreach ($m->getErrors() as $errVals) {
                            $allErrors[] = is_array($errVals) ? implode('; ', $errVals) : $errVals;
                        }
                    }
                }
            }
            if (!in_array(FALSE, $master)) {
                $master[] = $model->save();
                if (in_array(FALSE, $master) && $model->hasErrors()) {
                    foreach ($model->getErrors() as $errVals) {
                        $allErrors[] = is_array($errVals) ? implode('; ', $errVals) : $errVals;
                    }
                }
            }
            if (!in_array(FALSE, $master)) {
                foreach ($childModel as $key => $m) {
                    if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                        $master[] = $m->delete();
                    } else {
                        $name = (new ReflectionClass($m))->getShortName();
                        if ($name == 'TblContactDetails' || $name == 'TblBankDetails') {
                            $master[] = $m->save();
                        } else {
                            $master[] = $m->save(FALSE);
                        }
                        if (in_array(FALSE, $master) && $m->hasErrors()) {
                            foreach ($m->getErrors() as $errVals) {
                                $allErrors[] = is_array($errVals) ? implode('; ', $errVals) : $errVals;
                            }
                        }
                    }
                }
            }

            if (!in_array(FALSE, $master)) {
                if (!empty($model->auto_member_create)) {
                    $unionValue = Yii::$app->general->getUnionConfiguration($model->union_code, 'no_of_auto_member_create', 'PORTAL');
                    $config = !empty($unionValue) ? $unionValue : 100;
                    for ($x = 1; $x <= $config; $x += 1) {
                        $memberModel = new TblMember();
                        $memberModel->attributes = $model->attributes;
                        $memberModel->setKeyPattern($memberModel, 'tbl_member', 'ex_member_code', 3);
                        $memberModel->member_code = $model->dcs_code . $memberModel->ex_member_code;
                        if (!empty($memberModel->set_master_hierarchy)) {
                            $memberModel->set_master_hierarchy[0]->member_code = $memberModel->member_code;
                        }
                        Yii::$app->default->getDefaults($memberModel);
                        $memberModel->address = $model->dcs_name;
                        $memberModel->no_of_buffalo = $memberModel->no_of_cow_cross = $memberModel->no_of_cow_ind = $memberModel->total_animals = 0;
                        $memberModel->member_type_code = '1';
                        $memberModel->member_name = 'No Name';
                        $memberModel->gender_code = 1;
                        $memberModel->caste_category_code = 1;
                        $memberModel->member_type_code = 1;
                        $memberModel->bank_code = NULL;
                        $memberModel->branch_code = NULL;
                        $memberModel->bank_account_no = NULL;
                        $memberModel->ifsc = NULL;
                        $memberModel->beneficiary_name = NULL;
                        $memberModel->adhar_no = NULL;
                        $memberModel->pan_no = NULL;
                        $memberModel->mobile_no = NULL;
                        $memberModel->vendor_code = NULL;
                        $master[] = $memberModel->save();
                        if (in_array(FALSE, $master) && $memberModel->hasErrors()) {
                            foreach ($memberModel->getErrors() as $errVals) {
                                $allErrors[] = is_array($errVals) ? implode('; ', $errVals) : $errVals;
                            }
                        }
                    }
                }
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                if ($isWeb) {
                    Yii::$app->display->message(true, $message[0], $message[1]);
                }
                return 'customRedirect';
            }
            $child = new ChildModel();
            foreach ($childModel as $key => $m) { //this code to get validation msgs of child table when matster validation fails
                if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                    
                } else {
                    $child->decryptModel($m);
                    $master[] = $m->validate();
                }
            }
            //exit;

            $model->decryptModel($m);
            foreach ($childModel as $m) {
                $child->decryptModel($m);
            }
            $transaction->rollback();
            $errorMsg = !empty($allErrors) ? substr(implode(' | ', $allErrors), 0, 500) : 'Your transaction is not saved successfully';
            if ($isWeb) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => $errorMsg
                ]);
            }
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            $errorMsg = $e->getMessage();
            if ($isWeb) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => $errorMsg
                ]);
            }
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $errorMsg = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
            if ($isWeb) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => $errorMsg
                ]);
            }
            return false;
        }
    }

    public function actionRfcRePush($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblDcsProvisionalHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->data_post_status = 0;
        $record = [];
        if ($this->model->save(true, false)) {
            $historyModel->save();
            $record = ['status' => 'success', 'msg' => 'Dcs Provisional re-pushed successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Failed to re-push Dcs Provisional.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionSapErrorDataList() {
        $searchModel = new TblDcsProvisionalSearch();
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
        $this->model->scenario = 'updateDcs';
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->milk_type_code = $this->model->milk_type;
            $this->model->vendor = $this->model->vendor_code;
            $this->model->data_post_status = 0;
            $this->model->resp_desc = $this->model->resp_status = $this->model->response_datetime = $this->model->picked_datetime = $this->model->response_msg = NULL;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Dcs Provisional', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['sap-error-data-list']);
            }
        }
        return $this->customRender();
    }
}
