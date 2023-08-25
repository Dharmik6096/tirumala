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
            $this->model->getCode();
//            $this->model->cutoff_val = (double) $this->model->cutoff_val;
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
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TRUE);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_approval' => TRUE
        ]);
    }

    public function actionApproveDcs($id) {
        $model = TblProcessApproval::findOne($id);
        $historyApproval = new TblProcessApprovalHistory();
        Yii::$app->operation->history($model, $historyApproval, UPDATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save = [];
            $model_save[] = $historyApproval;
            $model_save[] = $model;
            if (!empty($model_save)) {
                $next_count = TblProcessApproval::find()
                        ->where(['process_code' => $model->process_code, 'status' => 0])
                        ->andWhere(['<>', 'process_approval_code', $model->process_approval_code])
                        ->count();
                if ($model->status == '2') {
                    $status = 'Reject';
                } else if ($model->status == '1' && $next_count > 0) {
                    $status = 'Inprogress';
                } else {
                    $status = 'Approve';
                }
                if ($status == 'Approve' || $status == 'Reject') {
                    $dcsModel = $this->findModel($model->process_code);
                    $historyModel = new TblDcsProvisionalHistory();
                    Yii::$app->operation->history($dcsModel, $historyModel, UPDATE);
                    $model_save[] = $historyModel;
                    $dcsModel->status = $status;
                    $dcsModel->remarks = $model->remarks;
                    $model_save[] = $dcsModel;
                    if ($dcsModel->status == 'Approve') {
                        $transaction = $this->createDcs($dcsModel, $model_save);
                    }
                } else {
                    $transaction = $this->generalModel->saveTransaction($model_save, ['Dcs Provisional Approval', 'edit']);
                }
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['pending-approval']);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Dcs provisional already approved by other user.']);
            }
        }
        return $this->render('approve_dcs', [
                    'model' => $model,
        ]);
    }

    public function createDcs($dcsProvisional, $model_save) {
        if (!empty($dcsProvisional)) {
            $this->model = new TblDcs();
            $this->model->scenario = 'createDcs';
            $this->bankDetails = new TblBankDetails();
            $this->contactDetails = new TblContactDetails();
            $this->contactDetails->form_validation_type = 'dcs-create';
            $validate = 1;
            $dcsProvisional->vendor = $dcsProvisional->vendor_code;
            $dcsProvisional->milk_type_code = !empty($dcsProvisional->milk_type) ? explode(',', $dcsProvisional->milk_type) : [];
            $dcsProvisional->created_at = '';
            $dcsProvisional->created_by = '';
            $dcsProvisional->updated_at = '';
            $dcsProvisional->updated_by = '';
            $dcsProvisional->remarks = '';
            $this->model->attributes = $dcsProvisional->attributes;
            $this->model->vendor = $dcsProvisional->vendor;
            $this->model->milk_type_code = $dcsProvisional->milk_type_code;
            
//            $this->model->load($dcsProvisional->attributes);
            $this->model->dcs_code = $this->model->getCode();
            //set mapping data
            $mapList = [];
            if (!empty($this->model->village_code)) {
                $modelMapping = new TblDcsVillageMapping();
                $this->setMapping($modelMapping);
                array_push($mapList, $modelMapping);
            }

            $modelCodes = new TblSocietyCodes();
            $modelCodes->dcs_code = $this->model->dcs_code;
            $modelCodes->bipl_code = $modelCodes->getBiplCode($this->model->village_code);
            $modelCodes->union_code = $this->model->union_code;
            $modelCodes->bmc_code = $this->model->bmc_code;
            array_push($mapList, $modelCodes);
//            $this->bankDetails->load($dcsProvisional);
            $this->bankDetails->attributes = $dcsProvisional->attributes;
            $bankValidate = 1;
            if (!empty($this->bankDetails->bank_code)) {
                $this->bankDetails->setModel('society', $this->model->dcs_code);
                $this->bankDetails->scenario = 'bank_selected';
                array_push($mapList, $this->bankDetails);
                $bankValidate = Yii::$app->warning->codeWarningBankAc($this->bankDetails);
            }
//            $this->contactDetails->load($dcsProvisional);
            $this->contactDetails->attributes = $dcsProvisional->attributes;
            if (!empty($this->contactDetails->mobile_no)) {
                $this->contactDetails->setModel('society', $this->model->dcs_code);
                array_push($mapList, $this->contactDetails);
            }
            //set milk type data
            if ($this->model->default_milk_type == 8) {
                $this->model->milk_type_auto = 1;
            }
//            $this->model->milk_type_code = !empty($dcsProvisional['milk_type']) ? explode(',', $dcsProvisional['milk_type']) : [];
            $modelMilkType = $this->setMilk();
//            $this->model->default_milk_type = !empty($this->model->milk_type_auto) ? 8 : $this->model->setDefaultMilkType($modelMilkType);
            if (!empty($modelMilkType))
                $mapList = array_merge($mapList, $modelMilkType);
            //set vendor applicability
            if ($this->model->vendor != 'NA') {
                $vendorModel = new TblSocietyVendor();
                $vendorModel->dcs_code = $this->model->dcs_code;
//                $vendorModel->vendor_code = $this->model->vendor;
                $vendorModel->vendor_code = $this->model->vendor_code;
                array_push($mapList, $vendorModel);
            }
            if ($bankValidate == 1) {
//            if ($bankValidate == 1 && $_POST['warning'] == 0) {
                $msg = $this->model->dcs_name . ' for dcs/subcenter/collection center';
                $validate = Yii::$app->warning->unique($this->model, 'dcs_name', $this->model->dcs_name, $msg);
            }
            if ($bankValidate == 1 && $validate == 1 && empty($this->model->getErrors())) {
                $this->model->setModelData($this->model, $mapList);
//                $this->model->cutoff = '0000';
                if (!empty($this->model->lower_milk_type) && !empty($this->model->cutoff_val)) {
                    $val = str_replace('.', '', $this->model->cutoff_val);
                    $val = str_pad($val, 3, '0', STR_PAD_LEFT);
                    $milkType = Yii::$app->general->getforeignkey($this->model->lowerMilkType, 'short_name');
                    $cutOffVal = $val . strtoupper($milkType);
                    $this->model->cutoff = substr($cutOffVal, -4);
                }
                $transaction = $this->saveDcs($this->model, $mapList, $model_save, ['society', 'create']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
                        if (!empty($vendorModel)) {
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
                        }
                    }
                }
                return $transaction;
            }
        }
    }
    
    private function setMapping(&$modelMapping) {
        $modelMapping->dcs_code = $this->model->dcs_code;
        $modelMapping->village_code = $this->model->village_code;
        $modelMapping->is_active = $this->model->is_active;
    }

    public function saveDcs($model, $childModel, $provisionalModel, $message) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $master[] = $model->save();
            if (!in_array(FALSE, $master)) {
                foreach ($childModel as $key => $m) {
                    if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                        $master[] = $m->delete();
                    } else {
                        $name = (new ReflectionClass($m))->getShortName();
                        if ($name == 'TblContactDetails' || $name == 'TblBankDetails'){
                            $master[] = $m->save();
                        } else {
                            $master[] = $m->save(FALSE);
                        }
                    }
                }
            }

            if (!in_array(FALSE, $master)) {
                if (!empty($model->auto_member_create)) {
                    $config = !empty(Yii::$app->session->get('unionConfig')[$model->union_code]['no_of_auto_member_create']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['no_of_auto_member_create'] : 100;
                    for ($x = 1; $x <= $config; $x += 1) {
                        $memberModel = new TblMember();
                        $memberModel->attributes = $model->attributes;
                        $memberModel->setKeyPattern($memberModel, 'tbl_member', 'ex_member_code', 3);
                        $memberModel->member_code = $model->dcs_code . $memberModel->ex_member_code;
                        $memberModel->animal_type_code = 1;
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
                        $master[] = $memberModel->save();
                    }
                }
            }
            if (!in_array(FALSE, $master)) {
                foreach ($provisionalModel as $m) {
                    $master[] = $m->save();
                    //var_dump($m->getErrors());
                }
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
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
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Your transaction is not saved successfully']);
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

}
