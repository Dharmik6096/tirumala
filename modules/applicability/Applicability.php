<?php

namespace app\modules\applicability;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblRoutes;
use app\modules\organisation\models\TblDcs;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\models\GeneralModel;
use yii\helpers\Url;
use ReflectionClass;
use DateTime;
use yii\helpers\FileHelper;
use app\models\TblUserOrganizationMapping;
use webvimark\modules\UserManagement\models\User;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertNotification;
use PHPExcel;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\details\models\TblContactDetails;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitityAlias;

/**
 * applicability module definition class
 */
class Applicability extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\applicability\controllers';
    public $model, $field_name, $field_value, $union_code, $oldModel, $historyModel, $trans_label, $searchModel;
    public $is_union = true;
    public $dcs_filters = ['society' => 'Society', 'routes' => 'Routes'];
    public $fields = [];
    public $top_section = true;
    public $payment = false;
    public $title = '';
    public $actions = false;
    public $select_from_all = false;
    public $ratechart = false;
    public $shift_type = '';
    public $mcc_field_name = 'applicable_code';
    public $options = ['dcs', 'mcc'];
    public $default_option = 'dcs';
    public $script = false;
    public $header_title = '';
//put your code here
    protected $generalModel;
    public $bmc_field_name = 'applicable_code';
    public $customer_type_wise_entry = false;
    public $customer_type_field_name = 'applicable_type';
    public $customer_type_list = [];
    public $assignMultiData = false;
    public $assignMultiDataKey = '';
    public $assignDataKey = '';
    public $setModelFields = false;
    public $selectedCodes = [];
    public $selectedTypes = [];
    public $assignStaticData = [];
    public $check_wef_date = false;
    public $selectedMccCode = [];
    public $selectedBmcCode = [];
    public $selectedRouteCode = [];
    public $generateMail = false;
    public $attachment_folder = '/web/alert-data/';
    public $isApproval = false;
    public $periodic_applicability = FALSE;
    public $login_type = '';
    public $department = '';
    public $is_bulk_notification = false;
    public $with_wef_date = true;
    public $update_applicability = FALSE;
    public $rateMccCode = [];
    public $with_applicable_code = false;
    public $load_data_on_apply_to_checkbox = false;
    public $save_applicability_child = false;
    public $check_applicability_with_field_name = TRUE;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
        $this->union_code = !empty(Yii::$app->session->get('Unions') && count(explode(',', Yii::$app->session->get('Unions'))) == 1) ? Yii::$app->session->get('Unions') : '';

//        $this->searchModel= new $this->model->className().'Search()';
// custom initialization code goes here
    }

    protected function customRedirect() {
        return Yii::$app->controller->redirect(Url::previous());
    }

    protected function customRender() {
        $selected_customer_type = [];
        $field_name = $this->field_name;
        $preload = count($this->options) == 1 ? $this->options[0] : (empty($this->options) ? 'none' : $this->default_option);
        switch ($preload) {
            case 'dcs':
                $selected = $this->getDcs($this->top_section);
                $selectedDcs = $this->getDcs($this->top_section, '', true);
                $list = $this->loadUnionDcs($selectedDcs, '');
                $list = ArrayHelper::map($list, 'dcs_code', 'dcs_name');
                $main_field_name = 'dcs_code';
                $title = Yii::t('app', 'Societies');
                break;
            case 'mcc':
                $selected = $this->getMcc();
                $list = $this->loadUnionMcc($this->union_code);
                $main_field_name = $this->mcc_field_name;
                $title = 'MCCs';
                break;
            case 'tanker_rate':
                $selected = $this->getMcc();
                $list = $this->loadUnionMcc($this->union_code);
                $main_field_name = $this->mcc_field_name;
                $title = '';
                break;
            case 'bmc':
                $selected = []; //$this->getBmc();
                $list = $this->loadUnionBmc($this->union_code);
                $main_field_name = $this->mcc_field_name;
                $title = 'BMCs';
                break;
            case 'dcs_mcc_user':
                $selected = [];
                $list = $this->loadUnionMcc($this->union_code);
                $main_field_name = $this->mcc_field_name;
                $title = '';
                break;
            default :
                $selected = [];
                $list = [];
                $main_field_name = 'dcs_code';
                $title = Yii::t('app', 'Societies');
        }

//        $mccList = $this->loadUnionMcc($this->union_code);
        $mccList = $this->loadUnionMcc($this->union_code, '', $this->rateMccCode);
        $searchModel = $this->searchModel;
        $searchModel->$field_name = $this->field_value;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $hideCustomerType = false;
        if ($this->customer_type_wise_entry && empty($this->customer_type_list)) {
            $customerModel = new TblCustomerType();
            $customerModel->union_code = $this->union_code;
            $this->customer_type_list = $customerModel->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
            $this->customer_type_list['DCS'] = Yii::t('app', 'DCS');
            if (count($this->customer_type_list) == 1) {
                $hideCustomerType = true;
                $this->selectedTypes = !empty($this->selectedTypes) ? $this->selectedTypes : ['DCS'];
            }
            $selected_customer_type = []; //$this->getBmc($this->customer_type_field_name);
        }
        return Yii::$app->controller->render('/../../applicability/views/default/create', [
                    'model' => $this->model,
                    'field_name' => $field_name,
                    'dcs_list' => $list,
                    'mcc_field_name' => $this->mcc_field_name,
                    'selected' => $selected,
                    'filters' => $this->dcs_filters,
                    'filter_data' => $this->getFilterData(),
                    'union_code' => $this->union_code,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'field_code' => $this->field_value,
                    'model_name' => $this->model->className(),
                    'top_section' => $this->top_section,
                    'fields' => $this->fields,
                    'is_union' => $this->is_union,
                    'payment' => $this->payment,
                    'title' => $this->title,
                    'actions' => $this->actions,
                    'select_from_all' => $this->select_from_all,
                    'ratechart' => $this->ratechart,
                    'shift_type' => $this->shift_type,
                    'main_field_name' => $main_field_name,
                    'options' => $this->options,
                    'preload' => $preload,
                    'title' => $title,
                    'script' => $this->script,
                    'header_title' => $this->header_title,
                    'customer_type_wise_entry' => $this->customer_type_wise_entry,
                    'customer_type_list' => $this->customer_type_list,
                    'customer_type_field_name' => $this->customer_type_field_name,
                    'selected_customer_type' => $selected_customer_type,
                    'selectedCodes' => $this->selectedCodes,
                    'selectedTypes' => $this->selectedTypes,
                    'hideCustomerType' => $hideCustomerType,
                    'check_wef_date' => $this->check_wef_date,
                    'mccList' => $mccList,
                    'selectedMccCode' => $this->selectedMccCode,
                    'selectedBmcCode' => $this->selectedBmcCode,
                    'selectedRouteCode' => $this->selectedRouteCode,
                    'generateMail' => $this->generateMail,
                    'login_type' => $this->login_type,
                    'department' => $this->department,
                    'is_bulk_notification' => $this->is_bulk_notification,
                    'periodic_applicability' => $this->periodic_applicability,
                    'load_data_on_apply_to_checkbox' => $this->load_data_on_apply_to_checkbox,
                    'check_applicability_with_field_name' => $this->check_applicability_with_field_name
        ]);
    }

    public function createApp() {

        $model = $this->model;
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();

        $field_name = $this->field_name;
        $preload = count($this->options) == 1 ? $this->options[0] : (empty($this->options) ? 'none' : $this->default_option);
        switch ($preload) {
            case 'dcs':
                $main_field_name = 'dcs_code';
                $title = 'Society';
                break;
            case 'mcc':
                $main_field_name = $this->mcc_field_name;
                $title = 'MCC';
                break;
            case 'tanker_rate':
                $main_field_name = $this->mcc_field_name;
                $title = 'MCC';
                break;
            case 'dcs_mcc_user':
                $main_field_name = $this->mcc_field_name;
                $title = 'MCC';
                break;
            default :
                $main_field_name = 'dcs_code';
                $title = 'Society';
        }
        $hasError = false;
        $postData = [];
        if (Yii::$app->request->post()) {
//$model->union_code = $this->union_code;
            $model->$field_name = $this->field_value;

            if ($model->load(Yii::$app->request->post())) {
                $postData = Yii::$app->request->post();
                $model->setAttributes($postData);
                $model->setAttributes($this->assignStaticData);
                if ($model->validate()) {
                    $saveModel = [];
                    $dataold = $this->model->find()->where([$this->field_name => $this->field_value]);
                    if ($this->model->hasAttribute('wef_date')) {
                        $dataold->andWhere(['wef_date' => date('Y-m-d', strtotime($model->wef_date))]);
                    }
                    $dataold->all();
                    $returnedArray = \yii\helpers\ArrayHelper::getColumn($dataold, $main_field_name);
                    $toRevoke = array_intersect($returnedArray, $model->{$main_field_name});
                    $toAssign = $model->{$main_field_name};
//$toAssign = array_diff($returnedArray, $model->dcs_code);
//$toRevoke = array_diff($model->dcs_code, $returnedArray);
                    $mappingList = [];
                    $errorArr = [];
//var_dump($returnedArray);
//var_dump($model->dcs_code);
                    $session = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['rate_approval']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['rate_approval'] : '';
                    if ($this->isApproval && $session) {
                        foreach ($toAssign as $value) {
                            try {
                                $aliasName = $this->model->className() . 'Alias';
                                $aliasModel = new $aliasName();
                                $data = $model->attributes;
                                $aliasModel->setAttributes($data);
                                $aliasModel->setAttributes($this->assignStaticData);
                                $aliasModel->{$main_field_name} = $value;
                                $aliasModel->$field_name = $this->field_value;
                                $aliasModel->union_code = $this->union_code;
                                if ($aliasModel->hasAttribute('wef_date')) {
                                    $aliasModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);
                                    if ($model->hasAttribute('shift_code')) {
                                        $aliasModel->wef_date = $aliasModel->wef_date . ' ' . Yii::$app->general->getshift($model->shift_code);
                                    }
                                }
                                if ($aliasModel->hasAttribute('from_date') && $aliasModel->hasAttribute('from_shift')) {
                                    $aliasModel->from_date = $aliasModel->schemeRateCode->from_date;
                                    $aliasModel->from_shift = $aliasModel->schemeRateCode->from_shift;
                                    $aliasModel->rtpl = $aliasModel->schemeRateCode->rtpl;
                                    $aliasModel->rate_class = $aliasModel->schemeRateCode->rate_class;
                                }
                                if ($aliasModel->hasAttribute('to_date') && $aliasModel->hasAttribute('to_shift')) {
                                    $aliasModel->to_date = $aliasModel->schemeRateCode->to_date;
                                    $aliasModel->to_shift = $aliasModel->schemeRateCode->to_shift;
                                }
                                $saveModel[] = $aliasModel->save();
                            } catch (UserException $e) {
                                $saveModel[] = false;
                                $hasError = true;
                                $errorArr[] = $e->getMessage();
                            } catch (\yii\db\Exception $e) {
                                $saveModel[] = false;
                                $hasError = true;
                                $errorArr[] = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
                            }
                        }
                    } else {
                        foreach ($toRevoke as $value) {
                            if (!empty($value)) {
                                try {
//echo $value.'<br/>';
                                    $r = new ReflectionClass($this->model->className());
                                    $appModel = $r->newInstanceArgs();
                                    $appModel = $appModel->find()->where([$main_field_name => $value, $field_name => $this->field_value]);
                                    if ($appModel->hasAttribute('wef_date')) {
                                        $appModel->andWhere(['wef_date' => date('Y-m-d', strtotime($model->wef_date))]);
                                    }
                                    $appModel->one();
                                    $h = new ReflectionClass($this->historyModel->className());
                                    $appHistory = $h->newInstanceArgs();
                                    Yii::$app->operation->history($appModel, $appHistory, DELETE);
                                    $saveModel[] = $appHistory->save();
                                    $saveModel[] = $appModel->delete();
                                } catch (UserException $e) {
                                    $saveModel[] = false;
                                    $hasError = true;
                                    $errorArr[] = $e->getMessage();
                                } catch (\yii\db\Exception $e) {
                                    $saveModel[] = false;
                                    $hasError = true;
                                    $errorArr[] = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
                                }
//                                array_push($mappingList, $appHistory);
//                                array_push($mappingList, $appModel);
                            }
                        }
                        foreach ($toAssign as $value) {
                            try {
                                $r = new ReflectionClass($this->model->className());
                                $appModel = $r->newInstanceArgs();
                                $data = $model->attributes;
                                $appModel->setAttributes($data);
                                $appModel->setAttributes($this->assignStaticData);
                                $primaryKey = $model->tableSchema->primaryKey[0];
                                unset($appModel->$primaryKey);
                                $appModel->{$main_field_name} = $value;
                                if ($appModel->hasMethod('setOrgDetail')) {
                                    $appModel->setOrgDetail();
                                }
                                $appModel->$field_name = $this->field_value;
                                $appModel->union_code = $this->union_code;

                                if ($this->periodic_applicability) {
                                    $appModel->from_date = Yii::$app->formatter->asDate($model->from_date, DATE_FORMAT);
                                    $appModel->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT);
                                    if ($appModel->hasAttribute('wef_date')) {
                                        $appModel->wef_date = $model->wef_date = $appModel->from_date;
                                    }
                                } else if ($appModel->hasAttribute('from_date') && !empty($model->from_date)) {
                                    $appModel->from_date = Yii::$app->formatter->asDate($model->from_date, DATE_FORMAT);
                                }
                                if ($this->update_applicability) {
                                    $appModel->to_date = Yii::$app->formatter->asDate($model->to_date, DATE_FORMAT);
                                }
                                if (!$this->update_applicability && $appModel->hasAttribute('wef_date')) {
                                    $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);
                                    if ($model->hasAttribute('shift_code')) {
                                        $appModel->wef_date = $appModel->wef_date . ' ' . Yii::$app->general->getshift($model->shift_code);
                                    }
                                    $check = $this->checkDuplicateCount($appModel);
                                    if ($check >= 1) {
                                        if ($this->periodic_applicability) {
                                            $model->addError('from_date', 'Date Range already taken by ' . $title . '.');
                                        } else {
                                            $model->addError('wef_date', $appModel->wef_date . ' date already taken by ' . $title . '.');
                                        }
                                        return $this->customRender();
                                    }
                                }
                                if (!empty($appModel->purchaseRateCode->for_member) && $appModel->purchaseRateCode->for_member == 1) {
                                    $dcsRateModel = TblPurchaseRate::find()->where(['dcs_purchase_rate_code' => $appModel->purchase_rate_code])->one();
                                    if (!empty($dcsRateModel) && strtoupper($appModel->applicable_for) == 'DCS') {
                                        $dcsAppModel = new TblPurchaseRateApplicability();
                                        $dcsAppModel->attributes = $appModel->attributes;
                                        $dcsAppModel->purchase_rate_code = $dcsRateModel->purchase_rate_code;
                                        $dcsAppModel->dcs_code = $appModel->applicable_code;
                                        $dcsAppModel->union_code = $appModel->union_code;
                                        $dcsAppModel->applicable_for = 'DCS';
                                        $saveModel[] = $dcsAppModel->save();
                                    }
                                }

                                if (!empty($appModel->purchaseRateCode->rate_type)) {
                                    $sms_data = [];
                                    $type = $appModel->purchaseRateCode->rate_type == 1 ? 'increase_rate' : 'decrease_rate';

                                    $date = date('d-m-Y', strtotime($appModel->wef_date));
                                    $rate = $appModel->purchaseRateCode->rate_value;
                                    $mobilNo = '';
                                    $moduleName = strtoupper($appModel->applicable_for) == 'DCS' ? 'society' : 'customer';
                                    $contact = new TblContactDetails();
                                    $contactData = $contact->find()
                                            ->where(['module_code' => $appModel->applicable_code, 'module_name' => $moduleName, 'is_default' => 1, 'is_active' => 1])
                                            ->one();
                                    if ($contactData) {
                                        $mobilNo = $contactData->mobile_no;
                                    }
                                    if (!empty($mobilNo)) {
                                        $templateModel = new TblAlertTemplate();
                                        $templateData = $templateModel->getTemplateData($type, 'SMS', $appModel->union_code);
                                        if (!empty($templateData)) {
                                            $arrFrom = array("{date}", '{' . $type . '}');
                                            $arrTo = array($date, $rate);
                                            $word = $templateData->message;
                                            $message = str_replace($arrFrom, $arrTo, $word);

                                            $notificationmodel = new TblAlertNotification();
                                            $datetime = date('Y-m-d H:i:s');
                                            $notificationmodel->module_type = $type;
                                            $notificationmodel->content_id = !empty($templateData->api_master_id) ? $templateData->api_master_id : '1';
                                            $notificationmodel->receiver_detail = $mobilNo;
                                            $notificationmodel->receiver_type = 'SMS';
                                            $notificationmodel->message = $message;
                                            $notificationmodel->send_status = '0';
                                            $notificationmodel->entry_datetime = $datetime;
                                            $notificationmodel->pick_datetime = NULL;
                                            $notificationmodel->response_datetime = NULL;
                                            $notificationmodel->response_status = 0;
                                            $notificationmodel->template_id = $templateData->header_info;
                                            $saveModel[] = $notificationmodel->save();
                                        }
                                    }
                                }
                                if ($appModel->hasMethod('setOrgDetail')) {
                                    $appModel->setOrgDetail();
                                }

                                if ($this->update_applicability) {
                                    $editRecords = $appModel->getEditRecord();
                                    foreach ($editRecords as $rec) {
                                        $historyModel = new ReflectionClass($this->model->className() . 'History');
                                        $historyModel = $historyModel->newInstanceArgs();
                                        Yii::$app->operation->history($rec, $historyModel, 'UPDATE');
                                        $rec->to_date = $appModel->to_date;
                                        $saveModel[] = $historyModel->save();
                                        $saveModel[] = $rec->save();
                                    }
                                } else {
                                    $saveModel[] = $appModel->save();
                                }
                                $saveChildModels = [];
                                if ($this->save_applicability_child) {
                                    $model->saveApplicabilityChild($model, $saveChildModels, $value);
                                    if (!empty($saveChildModels)) {
                                        foreach ($saveChildModels as $saveChildModel) {
                                            $saveModel[] = $saveChildModel->save();
                                        }
                                    }
                                }
                            } catch (UserException $e) {
                                $saveModel[] = false;
                                $hasError = true;
                                $errorArr[] = $e->getMessage();
                            } catch (\yii\db\Exception $e) {
                                $saveModel[] = false;
                                $hasError = true;
                                $errorArr[] = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
                            }
//                            array_push($mappingList, $appModel);
                        }
                    }
                    $this->selectedMccCode = [];
                    $this->selectedBmcCode = [];
                    $this->selectedRouteCode = [];
                    if (!in_array(FALSE, $saveModel)) {
                        if ($this->update_applicability) {
                            Yii::$app->display->message(true, $this->trans_label, 'edit');
                        } else {
                            Yii::$app->display->message(true, $this->trans_label, 'create');
                        }
                        if ($this->generateMail && !$this->isApproval && !$session) {
                            $this->GenerateMail($appModel, $toAssign);
                        }
                        return $this->customRedirect();
                    } else {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => implode('<br/>', $errorArr)]);
                    }
//                        $transaction = $this->generalModel->appTransaction($mappingList, [$this->trans_label, 'create']);
//                        if ($transaction == 'customRedirect') {
//                            $mname = \yii\helpers\StringHelper::basename(get_class($this->model));
//                            if ($transaction == 'customRedirect' && $mname == 'TblPurchaseRateApplicability') {
//                                $files = [];
//                                foreach ($mappingList as $mapping) {
//                                    $cmname = \yii\helpers\StringHelper::basename(get_class($mapping));
//                                    if ($cmname == 'TblPurchaseRateApplicability' && Yii::$app->general->isVendor($mapping->dcs_code, 'BIPL')) {
//                                        $rfiles = $mapping->generateBiplRateFiles();
//                                        if ($rfiles != false) {
//                                            if (!empty($files))
//                                                $files = array_merge($files, $rfiles);
//                                            else
//                                                $files = $rfiles;
//                                        }
//                                    }
//                                }
//                                $files = array_values($files);
//                                $app = new TblPurchaseRateApplicability();
//                                foreach ($files as $key => $value) {
//                                    $cp_code = key($value);
//                                    $cp_path = Yii::$app->params['biplDirPath'] . 'EKOMILK/' . $cp_code . '/' . 'MASFILES';
//                                    if (Yii::$app->general->checkDirectory($cp_path))
//                                        $app->generateEncFile($value[$cp_code], $cp_path);
//                                }
//                            }
//                            $this->selectedMccCode = [];
//                            $this->selectedBmcCode = [];
//                            return $this->{$transaction}();
//                        }
                } else {
                    $hasError = true;
                }
                if (false && $hasError) {
                    $pData = array_values($postData);
                    $mccCode = array_map(function($a) {
                        return !empty($a['f_mcc_code']) ? $a['f_mcc_code'] : [];
                    }, $pData);
                    foreach ($mccCode as $m) {
                        if (!empty($m)) {
                            $this->selectedMccCode = $m;
                        }
                    }
                    $bmcCode = array_map(function($a) {
                        return !empty($a['f_bmc_code']) ? $a['f_bmc_code'] : [];
                    }, $pData);
                    foreach ($bmcCode as $m) {
                        if (!empty($m)) {
                            $this->selectedBmcCode = $m;
                        }
                    }
                    $routeCode = array_map(function($a) {
                        return !empty($a['f_route_code']) ? $a['f_route_code'] : [];
                    }, $pData);
                    foreach ($routeCode as $m) {
                        if (!empty($m)) {
                            $this->selectedRouteCode = $m;
                        }
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function getFilterData() {
        $filters = $this->dcs_filters;
        $filter_data = [];
        switch (1) {
            case key_exists('routes', $filters):
                $routs = new TblRoutes();
                $filter_data['routes'] = $routs->getRoutes($this->union_code);
                break;
            case in_array('plants', $filters):
                break;
            case in_array('mcc', $filters):
                break;
            default :
        }
        return $filter_data;
    }

    public function getDcs($top_section, $date = '', $returnQuery = false) {
        $field_name = $this->field_name;
        if ($this->select_from_all == false)
            $query = $this->model->find()->select('dcs_code')->where([$field_name => $this->field_value]);
        else
            $query = $this->model->find()->select('dcs_code');
//        if ($top_section)
//            $query->andWhere(['<=', 'wef_date', date('Y-m-d')]);
        if (!empty($date) && $this->model->hasAttribute('wef_date')) {
            $query->andWhere(['wef_date' => $date]);
        }
        if ($returnQuery) {
            return $query;
        } else {
            $values = $query->all();
            $selected = ArrayHelper::getColumn($values, 'dcs_code');
            return $selected;
        }
    }

    public function getDcsAlert($union_code, $id) {
        $query = $this->model->getPaymentCycleDcs($this->field_value);
        $query2 = $this->model->getPaymentCycleDcsWithGap($this->field_value);
        $message = [];
        $message1 = [];
        $dcs = [];
        $messagestring = '';

        for ($i = 0; $i < count($query); $i++) {
            if (is_array($id) && in_array($query[$i]->dcs_code, $id)) {
                $date = Yii::$app->controls->view_date($query[$i]->paymentCycle->from_date) . ' to ' . Yii::$app->controls->view_date($query[$i]->paymentCycle->to_date);
                $message[] = $query[$i]->dcsCode->dcs_name . ' [' . $date . ']';
                $dcs[] = $query[$i]->dcs_code;
            }
        }
        if (count($message) > 0) {
            $messagestring = 'Following societies already has payment cycle conflicting with current cycle <br/>' . implode('<br/>', $message);
        }

        for ($i = 0; $i < count($query2); $i++) {
            if (is_array($id) && in_array($query2[$i]->dcs_code, $id)) {
                $date = Yii::$app->controls->view_date($query2[$i]->paymentCycle->from_date) . ' to ' . Yii::$app->controls->view_date($query2[$i]->paymentCycle->to_date);
                $message1[] = $query2[$i]->dcsCode->dcs_name . ' [' . $date . ']';
                $dcs[] = $query2[$i]->dcs_code;
            }
        }
        if (count($message1) > 0) {
            $messagestring = $messagestring . '<br/> Following societies has payment cycle gap with current cycle <br/>' . implode('<br/>', $message1);
        }
        return [0 => $messagestring, 1 => $dcs];
    }

    public function loadUnionDcs($dcs = [], $union_code, $returnQuery = false) {
        //var_dump($dcs); exit;
        $dcsList = TblDcs::find()->leftJoin('tbl_society_codes', 'tbl_society_codes.dcs_code = tbl_dcs.dcs_code')->where(['tbl_dcs.union_code' => $union_code, 'is_active' => 1])->andWhere(['not in', 'tbl_dcs.dcs_code', $dcs])->andWhere(['not', ['tbl_society_codes.bmc_code' => 0]])->andWhere(['not', ['tbl_society_codes.bmc_code' => null]]);
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcsList->andWhere(['tbl_dcs.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        $dcsList = $dcsList->all();
        return $dcsList;
    }

    public function checkDuplicate($model) {
        $field_name = $this->field_name;
        $query = $this->model->find()->where(['dcs_code' => $model->dcs_code, $field_name => $this->field_value, 'wef_date' => $model->wef_date]);
        foreach ($this->fields as $key => $f) {
            if (in_array('create', $f['view'])) {
                $query->andWhere([$key => $model->{$key}]);
            }
        }
        return $query->all();
    }

    public function paymentApplicability() {
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();

        if (Yii::$app->request->post()) {

            if ($this->model->load(Yii::$app->request->post()) && $this->model->dcs_code != '') {

                if ($this->model->validate() && $this->model->dcs_code != '') {
                    $save_model = [];
                    foreach ($this->model->dcs_code as $dcs) {
                        $paymentCycleModel = new TblDcsPaymentCycle();
                        $paymentCycleData = $paymentCycleModel->findOne($this->field_value);
                        $new_model = new ReflectionClass($this->model->className());
                        $model = $new_model->newInstanceArgs();
                        $model->from_date = $paymentCycleData->from_date;
                        $model->to_date = $paymentCycleData->to_date;
                        $model->dcs_payment_cycle_code = $this->field_value;
                        $model->is_lock = 0;
                        $model->data_lock = 0;
                        /* for (; $from_date < $to_date;) {
                          $new_model = new ReflectionClass($this->model->className());
                          $model = $new_model->newInstanceArgs();
                          $model->dcs_payment_cycle_code = $this->field_value;

                          $tmp_Date = clone $from_date;
                          $end_date = clone $from_date;
                          $tmp_Date->modify("last day of this month");
                          $end_date->modify("+" . $paymentCycleData->interval_value . " day");
                          $model->from_date = $from_date->format('Y-m-d');
                          if ($end_date > $tmp_Date) {
                          if ($tmp_Date < $to_date)
                          $end_date = clone $tmp_Date;
                          else {
                          $end_date = clone $to_date;
                          }
                          }
                          $model->to_date = $end_date->format('Y-m-d'); */
                        $model->dcs_code = $dcs;
                        $save_model[] = $model;
                        /* $from_date = clone $end_date;
                          $from_date->modify("+ 1 day"); */
//}
                    }
                    $transaction = $this->generalModel->appTransaction($save_model, [$this->trans_label, 'create']);
                    if ($transaction !== FALSE) {
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function vendorApplicability() {
        $model = $this->model;
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();

        $field_name = $this->field_name;
        if (Yii::$app->request->post()) {
            if ($this->is_union) {
                $model->union_code = $this->union_code;
            }
            $model->$field_name = $this->field_value;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $dataold = $this->model->find()->where([$this->field_name => $this->field_value])->all();
                    $returnedArray = \yii\helpers\ArrayHelper::getColumn($dataold, 'dcs_code');
                    $toRevoke = array_intersect($returnedArray, $model->dcs_code);
                    $toAssign = $model->dcs_code;
//$toAssign = array_diff($returnedArray, $model->dcs_code);
//$toRevoke = array_diff($model->dcs_code, $returnedArray);
                    $mappingList = [];
//var_dump($returnedArray);
//var_dump($model->dcs_code);
//var_dump($toRevoke);exit;
                    foreach ($toRevoke as $value) {
                        if (!empty($value)) {
//echo $value.'<br/>';
                            $r = new ReflectionClass($this->model->className());
                            $appModel = $r->newInstanceArgs();
                            $appModel = $appModel->find()->where(['dcs_code' => $value, $field_name => $this->field_value])->one();
                            $h = new ReflectionClass($this->historyModel->className());
                            $appHistory = $h->newInstanceArgs();
                            Yii::$app->operation->history($appModel, $appHistory, DELETE);
                            array_push($mappingList, $appHistory);
                            array_push($mappingList, $appModel);
                        }
                    }

                    foreach ($toAssign as $value) {
                        $r = new ReflectionClass($this->model->className());
                        $appModel = $r->newInstanceArgs();
                        $data = $model->attributes;
//$appModel->setAttributes($data);
// $appModel->society_vendor_code='';
                        $appModel->dcs_code = $value;
                        $appModel->$field_name = $this->field_value;

                        if ($this->is_union) {
                            $appModel->union_code = $this->union_code;
                        }
                        if (key_exists('wef_date', $this->fields)) {
                            $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);

                            $check = $this->checkDuplicate($appModel);
                            if ($check == 1) {
                                $model->addError('wef_date', $appModel->wef_date . ' date already taken by dcs.');
                                return $this->customRender();
                            }

                            $appModel->union_code = $this->union_code;
                            $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);
                            $check = $this->checkDuplicate($appModel);
                            if ($check == 1) {
                                $model->addError('wef_date', $appModel->wef_date . ' date already taken by dcs.');
                                return $this->customRender();
                            }
                        }
                        array_push($mappingList, $appModel);
                    }
                    $transaction = $this->generalModel->appTransaction($mappingList, [$this->trans_label, 'create']);
                    if ($transaction == 'customRedirect') {
                        $orgMap = [];
                        foreach ($mappingList as $map) {
                            $userModel = new User();
                            $users = $userModel->findByRole([$map->vendor_code]);
                            foreach ($users as $user) {
                                if (empty($user->user_type_id) || $user->user_type_id == 7) {
//TblUserOrganizationMapping::deleteAll(['user_id' => $user->id]);
                                    if (empty($user->user_type_id)) {
                                        $user->user_type_id = 7;
                                        array_push($orgMap, $user);
                                    }
                                    $modelNew = new TblUserOrganizationMapping();
                                    $modelNew->organization_code = $map->dcs_code;
                                    $modelNew->organization_type = 'DCS';
                                    $modelNew->user_id = $user->id;
                                    $modelNew->is_active = $user->is_active;
                                    Yii::$app->operation->defaults($modelNew, INSERT);
                                    array_push($orgMap, $modelNew);
//$modelNew->save();
                                }
                            }
                            if (strtolower($map->vendor_code) == 'eipl') {
                                $path = Yii::$app->params['eiplDirPath'] . $map->dcs_code . '/';
                                if (!file_exists($path) || !is_dir($path)) {
                                    FileHelper::createDirectory($path);
                                }
                            }
                        }
                        $this->generalModel->saveTransaction($orgMap, ['society mapping', 'create']);
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function getDcsAlertRateChart($union_code, $id, $wef_date, $shift) {
        if ($shift == 'all') {
            $shiftarray = ['all', 'morning', 'evening'];
        } else {
            $shiftarray = ['all', $shift];
        }
        $model_name = str_replace('\\', '_', $this->model->className());
        $cname = explode('_', $model_name);
        $cname = end($cname);
        $nameforid = strtolower($cname);
        if ($nameforid == 'tbldcspurchaserateapplicabitity') {
            $query = $this->model->find()
                            ->select(['dcs_code', 'tbl_dcs_purchase_rate.shift_applicability', 'tbl_dcs_purchase_rate.purchase_rate_code'])
                            ->joinWith(['purchaseRateCode.shiftApplicability'])
                            ->where(['dcs_code' => $id, 'convert(date, tbl_dcs_purchase_rate.wef_date, 103)' => $wef_date, 'tbl_dcs_purchase_rate.originating_org_type' => 'UNION', 'tbl_dcs_purchase_rate.union_code' => $union_code])
                            ->andWhere(['tbl_shift.shift' => $shiftarray])->all();
        } else {
            $query = $this->model->find()
                            ->select(['dcs_code', 'tbl_purchase_rate.shift_applicability', 'tbl_purchase_rate.purchase_rate_code'])
                            ->joinWith(['purchaseRateCode.shiftApplicability'])
                            ->where(['dcs_code' => $id, 'convert(date, tbl_purchase_rate.wef_date, 103)' => $wef_date, 'tbl_purchase_rate.originating_org_type' => 'UNION', 'tbl_purchase_rate.union_code' => $union_code])
                            ->andWhere(['tbl_shift.shift' => $shiftarray])->all();
        }
        $message = [];
        $dcs = [];
        $messagestring = '';

        for ($i = 0; $i < count($query); $i++) {
            $message[] = $query[$i]->dcsCode->dcs_name . ' -' . $query[$i]->purchase_rate_code;
            $dcs[] = $query[$i]->dcs_code;
        }
        if (count($message) > 0) {
            $messagestring = 'Following are the current <b>society - purchase rate</b> applicabilities.<br/>' . implode('<br/>', $message);
        }
        return [0 => $messagestring, 1 => $dcs];
    }

    public function getMcc() {
        $field_name = $this->field_name;
        if ($this->select_from_all == false)
            $query = $this->model->find()->select($this->mcc_field_name)->where([$field_name => $this->field_value]);
        else
            $query = $this->model->find()->select($this->mcc_field_name);
        $values = $query->all();
        $selected = ArrayHelper::getColumn($values, $this->mcc_field_name);
        return $selected;
    }

//    public function loadUnionMcc($union_code) {
    public function loadUnionMcc($union_code, $field = '', $in = []) {
        $mccModel = new TblMccPlant();
//        $mccList = $mccModel->getMccs($union_code, [], true);
        $mccList = $mccModel->getMccs($union_code, [], true, $in);
        return $mccList;
    }

    public function loadUnionBmc($union_code) {
        $bmcModel = new TblDcsBmc();
        $bmcList = $bmcModel->getBmcs($union_code, [], TRUE, $RLS = 'TRUE');
        return $bmcList;
    }

    public function getBmc($selectFieldName = '') {
        $selectFieldName = !empty($selectFieldName) ? $selectFieldName : $this->bmc_field_name;
        $field_name = $this->field_name;
        if ($this->select_from_all == false)
            $query = $this->model->find()->select($selectFieldName)->where([$field_name => $this->field_value]);
        else
            $query = $this->model->find()->select($selectFieldName);
        $values = $query->all();
        $selected = ArrayHelper::getColumn($values, $selectFieldName);
        return $selected;
    }

    public function customerTypeWiseApplicability() {
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();
        if (Yii::$app->request->post()) {
            $saveModel = [];
            $errorArr = [];
            $this->selectedCodes = [];
            $fieldName = $this->field_name;
            if ($this->model->load(Yii::$app->request->post())) {
                $this->model->$fieldName = $this->field_value;
                $save_model = [];
                $setField = $this->assignDataKey;
                $saveDataArray = $this->model->$setField;
                $wefDate = '';
                if ($this->model->hasAttribute('wef_date')) {
                    $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
                }
                $this->model->setAttributes($this->assignStaticData);
                if ($this->model->validate()) {
                    foreach ($saveDataArray as $val) {
                        if ($this->assignMultiData) {
                            $array = $this->model->{$this->assignMultiDataKey};
                            foreach ($array as $a) {
                                try {
                                    $new_model = new ReflectionClass($this->model->className());
                                    $model = $new_model->newInstanceArgs();
                                    $model->$fieldName = $this->field_value;
                                    $model->$setField = $val;
                                    $model->{$this->assignMultiDataKey} = $a;
                                    if ($model->hasAttribute('wef_date')) {
                                        $model->wef_date = $this->model->wef_date;
                                    }
                                    if ($model->hasAttribute('union_code')) {
                                        $model->union_code = $this->union_code;
                                    }
                                    $model->setAttributes($this->assignStaticData);
                                    if ($model->hasMethod('setOrgDetail')) {
                                        $model->setOrgDetail();
                                    }
                                    if ($model->validate()) {
                                        $saveModel[] = $model->save();
                                    } else {
                                        foreach ($model->getErrors() as $value) {
                                            $errorArr[] = $value[0];
                                        }
                                        $saveModel[] = false;
                                    }
                                } catch (UserException $e) {
                                    $saveModel[] = false;
                                    $errorArr[] = $e->getMessage();
                                } catch (\yii\db\Exception $e) {
                                    $saveModel[] = false;
                                    $errorArr[] = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
                                }
                            }
                        } else {
                            try {
                                $new_model = new ReflectionClass($this->model->className());
                                $model = $new_model->newInstanceArgs();
                                $model->$fieldName = $this->field_value;
                                $model->$setField = $val;
                                if ($model->hasAttribute('wef_date')) {
                                    $model->wef_date = $this->model->wef_date;
                                }
                                if ($model->hasAttribute('union_code')) {
                                    $model->union_code = $this->union_code;
                                }
                                $model->setAttributes($this->assignStaticData);
                                $save_model[] = $model;
                            } catch (UserException $e) {
                                $saveModel[] = false;
                                $errorArr[] = $e->getMessage();
                            } catch (\yii\db\Exception $e) {
                                $saveModel[] = false;
                                $errorArr[] = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
                            }
                        }
                    }
                    if (!in_array(FALSE, $saveModel)) {
                        Yii::$app->display->message(true, $this->trans_label, 'create');
                        return $this->customRedirect();
                    } else {
                        $this->selectedCodes = $saveDataArray;
                        if ($this->assignMultiData) {
                            $this->selectedTypes = $this->model->{$this->assignMultiDataKey};
                        }
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => implode('<br/>', $errorArr)]);
                    }
//                    $transaction = $this->generalModel->appTransaction($save_model, [$this->trans_label, 'create']);
//                    if ($transaction !== FALSE) {
//                        return $this->{$transaction}();
//                    }
                } else {
                    $this->selectedCodes = $saveDataArray;
                    if ($this->assignMultiData) {
                        $this->selectedTypes = $this->model->{$this->assignMultiDataKey};
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function GenerateMail($appModel, $toAssign) {
        if (!empty($toAssign)) {
            $apiMaster = new TblApiMaster();
            $apiMaster->receiver_type = 'EMAIL';
            $apiMasterData = $apiMaster->getAPI();
            if (!empty($apiMasterData)) {
                $htmlContent = "";
                $message = "";
                $file_name = "";
                $file_path = "";
                $this->setHtmlContent($appModel, $toAssign, $htmlContent, $message, $file_name, $file_path);
                $notificationModel = new TblAlertNotification();
                $notificationModel->receiver_type = 'EMAIL';
                $notificationModel->message = $htmlContent;
                $notificationModel->header_info = $message;
                $notificationModel->send_status = 0;
                $notificationModel->content_id = $apiMasterData->api_master_id;
                $notificationModel->refecence_code = $appModel->purchase_rate_code;
                $notificationModel->module_type = "Applicability(BMC)";
                $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                $notificationModel->send_mail = 1;
                $notificationModel->receiver_detail = 'vinay@everestinstruments.com,it.up@everestinstruments.com';
                $notificationModel->filename = $file_name;
                $notificationModel->file_path = $file_path;
                $notificationModel->has_attachment = 2;
                $notificationModel->save();
            }
        }
    }

    public function setHtmlContent($appModeldata, $toAssign, &$htmlContent, &$message, &$fileName, &$file_path) {
        $User = Yii::$app->general->getforeignkey($appModeldata->createdBy, 'name');
        $wefDate = Yii::$app->controls->view_date($appModeldata->wef_date);
        $message = 'Applicability given by ' . $User;
        $baseUrl = Yii::$app->request->baseUrl;
        $hostUrl = Url::base('http');
        $hostUrl = str_replace($baseUrl, '', $hostUrl);
        $htmlContent = '';
        $unionCode = $appModeldata->union_code . '-' . Yii::$app->general->getforeignkey($appModeldata->unionCode, 'union_name');
//$htmlContent .= "<table cellpadding='5'  cellspacing='0'><tbody>";
//$htmlContent .= "<tr>";
//$htmlContent .= "<td colspan='2'><b>Rate Id: </b>" . $appModeldata->purchase_rate_code . "</td>";
//$htmlContent .= "</tr>";
//$htmlContent .= "<tr>";
//$htmlContent .= "<td colspan='2'><b>WEF Date: </b>" . $wefDate . "</td>";
//$htmlContent .= "</tr>";
//$htmlContent .= "</tbody></table>";
        $htmlContent .= '<br/>';
        $htmlContent .= "<table cellpadding='5'  cellspacing='0'><thead><tr>";
        $htmlContent .= "<th style='background: #ddd; text-align: left; border: 1px solid #ccc'>#</th>";
        $htmlContent .= "<th style='background: #ddd; text-align: left; border: 1px solid #ccc; border-left: 0px'>" . Yii::t('app', 'UNION') . "</th>";
        $htmlContent .= "<th style='background: #ddd; text-align: left; border: 1px solid #ccc; border-left: 0px'>" . Yii::t('app', 'Rate Id') . "</th>";
        $htmlContent .= "<th style='background: #ddd; text-align: left; border: 1px solid #ccc; border-left: 0px'>" . Yii::t('app', 'WEF Date') . "</th>";
        $htmlContent .= "<th style='background: #ddd; text-align: left; border: 1px solid #ccc; border-left: 0px'>" . Yii::t('app', 'Total Applicability') . "</th>";
        $htmlContent .= "</tr></thead><tbody>";

        $r = new ReflectionClass($this->model->className());
        $appModel = $r->newInstanceArgs();
        $data = $this->model->attributes;
        $appModel->setAttributes($data);
        $appModel->setAttributes($this->assignStaticData);
        $primaryKey = $this->model->tableSchema->primaryKey[0];
        unset($appModel->$primaryKey);
        $htmlContent .= "<td style='border: 1px solid #ccc; border-top:0px; border-left: 0px'>1</td>";
        $htmlContent .= "<td style='border: 1px solid #ccc; border-top:0px; border-left: 0px'>" . $unionCode . "</td>";
        $htmlContent .= "<td style='border: 1px solid #ccc; border-top:0px; border-left: 0px'>" . $appModeldata->purchase_rate_code . "</td>";
        $htmlContent .= "<td style='border: 1px solid #ccc; border-top:0px; border-left: 0px'>" . $wefDate . "</td>";
        $htmlContent .= "<td style='border: 1px solid #ccc; border-top:0px; border-left: 0px'>" . count($toAssign) . "</td>";
        $htmlContent .= "</tbody></table>";
        $result = [];
        $output = [];
        foreach ($toAssign as $value) {
            $result['union'] = Yii::$app->general->getmultiforeignkey($appModel->purchaseRateCode, ['unionCode'], 'union_name') . '-' . Yii::$app->general->getforeignkey($appModel->purchaseRateCode, 'union_code');
            $result['bmc'] = Yii::$app->general->getCustomer($appModel, $appModel->applicable_for, FALSE, TRUE, FALSE);
            $result['rate_id'] = $appModeldata->purchase_rate_code;
            $result['wef_date'] = $wefDate;
            $result['shift'] = Yii::$app->general->getforeignkey($appModel->shiftCode, 'shift');
            $result['applicable_for'] = $appModel->applicable_for;
            $appModel->applicable_code = $value;
            $result['applicable_code'] = $value;
            $result['ref_code'] = Yii::$app->general->getCustomer($appModel, $appModel->applicable_for, false, FALSE, TRUE);
            $result['ex_code'] = Yii::$app->general->getCustomer($appModel, $appModel->applicable_for, true);
            $result['applicable_name'] = Yii::$app->general->getCustomer($appModel, $appModel->applicable_for, false, FALSE, FALSE);
            $output[] = $result;
        }

        $t = microtime(true);
        $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
        $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
        $datetime = $d->format("YmdHisu");
        $fileName = Yii::$app->general->getforeignkey($appModel->purchaseRateCode, 'union_code') . '_' . $appModel->applicable_for . '_' . $datetime . '.xls';
        $file_path = $this->CreateFile($fileName, $output);
    }

    public function CreateFile($fileName, $output) {
        $column_header = array_keys($output[0]);
        $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . $this->attachment_folder;
        if (\Yii::$app->general->checkDirectory($path)) {
            $absoluteBaseUrl = Url::base(true);
            $objPHPExcel = new PHPExcel();
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->fromArray(
                    $column_header, // The data to set
                    NULL, // Array values with this value will not be set
                    'A1'         // Top left coordinate of the worksheet range where
                    //    we want to set these values (default is A1)
            );
            $sheet->fromArray(
                    $output, // The data to set
                    NULL, // Array values with this value will not be set
                    'A2'         // Top left coordinate of the worksheet range where
                    //    we want to set these values (default is A1)
            );
            $filePath = $path . $fileName;
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($filePath);
            return $absoluteBaseUrl . $this->attachment_folder . $fileName;
        }
    }

    public function checkDuplicateCount($model) {
        $field_name = $this->field_name;
        if ($this->is_bulk_notification) {
            $mcc_field_name = $this->mcc_field_name;
        } else if ($this->with_applicable_code) {
            $mcc_field_name = $this->mcc_field_name;
        } else
            $mcc_field_name = ($model->hasAttribute('dcs_code')) ? 'dcs_code' : $this->mcc_field_name;
        $query = $this->model->find()->where([$mcc_field_name => $model->{$mcc_field_name}, $field_name => $this->field_value]);
        if ($this->with_wef_date) {
            $query->andWhere(['wef_date' => $model->wef_date]);
        }
        foreach ($this->fields as $key => $f) {
            if (in_array('create', $f['view'])) {
                $query->andWhere([$key => $model->{$key}]);
            }
        }
        return $query->count();
    }

}
