<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use app\modules\document\models\TblAttachment;
use app\modules\general\models\TblDepartment;
use app\modules\general\models\TblProcessApproval;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use app\modules\organisation\models\TblCustomerDeactive;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_customer_master_provisional".
 *
 * @property integer $customer_provisional_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $customer_code
 * @property string $customer_code_ex
 * @property string $customer_name
 * @property string $customer_type
 * @property string $sap_code
 * @property string $refference_code
 * @property string $address
 * @property string $local_name
 * @property string $local_address
 * @property string $gst_no
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $rate_chart_code
 * @property string $billing_payment_cycle
 * @property string $over_head
 * @property string $ccenter_code
 * @property string $ref_code
 * @property string $old_bmc_code
 * @property string $old_mcc_plant_code
 * @property string $old_route_code
 * @property integer $auto_code
 * @property string $vendor_code
 * @property string $data_post_id
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 * @property string $aadhaar_no
 * @property string $ts_code_m
 * @property string $ts_code_e
 * @property string $sap_vendor_code
 * @property string $customer_category
 * @property integer $animal_type_code
 * @property string $distance_from_mcc
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $beneficiary_name
 * @property string $contact_person
 * @property string $email
 * @property string $mobile_no
 * @property string $local_contact_person
 * @property string $department
 * @property string $firstname
 * @property string $lastname
 * @property string $surname
 * @property string $local_firstname
 * @property string $local_lastname
 * @property string $local_surname
 * @property string $status
 * @property string $remarks
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCustomerMasterProvisional extends \app\models\ChildModel {

    public $same_milk_type, $diff_milk_type, $prefix, $local_middlename, $detail_code, $process_approval_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_master_provisional';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['union_code', 'plant_code', 'mcc_plant_code', 'route_code', 'customer_code', 'firstname', 'mobile_no', 'customer_name', 'address', 'customer_type', 'bmc_code'], 'required', 'except' => ['reject', 'reroute']],
                [['customer_name', 'address', 'state_code', 'process_approval_code', 'district_code', 'rate_chart_code', 'billing_payment_cycle', 'detail_code', 'over_head', 'ccenter_code', 'customer_code', 'bmc_code', 'old_bmc_code', 'bank_code', 'sub_district_code', 'old_mcc_plant_code', 'village_code', 'hamlet_code', 'vendor_code', 'local_name', 'local_firstname', 'local_lastname', 'local_address', 'gst_no', 'union_code', 'created_by', 'updated_by', 'beneficiary_name', 'aadhaar_no', 'file_name', 'ts_code_m', 'ts_code_e', 'customer_category', 'remarks', 'animal_type_code', 'distance_from_mcc', 'created_at', 'updated_at', 'customer_type', 'sap_code', 'refference_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type', 'old_route_code', 'route_code', 'same_milk_type', 'diff_milk_type', 'prefix', 'contact_person', 'local_contact_person', 'middle_name', 'local_middlename', 'surname', 'local_surname', 'email', 'mobile_no', 'department', 'ifsc', 'bank_account_no', 'ref_code', 'customer_code_ex', 'sap_vendor_code', 'x_col2', 'data_post_id', 'data_post_status', 'status', 'picked_datetime', 'resp_status', 'resp_desc', 'branch_code', 'response_datetime', 'morning_kms', 'evening_kms', 'originating_type', 'firstname', 'lastname'], 'safe'],
                [['latitude', 'longitude', 'gender_code', 'pincode', 'pan_no', 'customer_status', 'supervisor_employee_id', 'supervisor_employee_name', 'is_aadhar_verify', 'is_bank_verify', 'provisional_from', 'is_approved', 'approved_at', 'approved_by'], 'safe'],
                [['animal_type_code', 'auto_code'], 'integer', 'except' => ['reject', 'reroute']],
                [['status'], 'default', 'value' => 'Pending'],
                [['is_approved'], 'default', 'value' => 0],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'except' => ['reject', 'reroute']],
                [['route_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRouteMapping::className(), 'targetAttribute' => ['route_code' => 'route_code'], 'except' => ['reject', 'reroute']],
                [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code'], 'except' => ['reject', 'reroute']],
                [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'except' => ['reject', 'reroute']],
                [['gst_no'], 'string', 'max' => 15, 'min' => 15, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 15 digit '),
                'tooShort' => Yii::t('app/validation', '{attribute} must contain 15 digit '), 'skipOnEmpty' => TRUE, 'except' => ['reject', 'reroute']],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['address'], function ($attribute, $params) { 
                    Yii::$app->general->validateDiscriptiveField($this, $attribute, true); 
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['local_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['customer_code_ex'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['x_col1'], 'default', 'value' => '1#1'],
                [['sap_vendor_code'], 'validateCustomerUniqueness', 'except' => ['reject', 'reroute']],
                [['local_contact_person', 'local_middlename', 'local_surname'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['customer_code'], function ($attribute, $params) {
                    Yii::$app->general->vaildateKeyCodes($this, 'tbl_customer_master', 'customer_code_ex', 'customer_code', FALSE);
                }, 'skipOnEmpty' => false, 'on' => ['updateFront', 'approve']],
                [['department'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'department');
                }, 'except' => ['reject', 'reroute']],
                [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id'], 'except' => ['reject', 'reroute']],
                [['bmc_code'], 'setExCode', 'except' => ['reject', 'reroute']],
                [['customer_code'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['customer_code'], function ($attribute, $params) {
                    $update = FALSE;
                    if ($this->scenario == 'updateFront') {
                        $update = TRUE;
                    }
                    Yii::$app->general->validateExCodes($this, 'tbl_customer_master', 'customer_code_ex', 'tbl_dcs', 'dcs_code_ex', 'TblDcs', $this->union_code, $update);
                }, 'skipOnEmpty' => false, 'on' => ['updateFront', 'createFront', 'approve']],
                [['aadhaar_no'], 'required', 'when' => function ($model) {
                    return ($model->is_aadhar_verify == 1);
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblcustomermasterprovisional-is_aadhar_verify').prop('checked') == true;
                }", 'on' => ['createFront', 'updateFront', 'approve']],
                [['bank_account_no', 'bank_code', 'branch_code'], 'required', 'when' => function ($model) {
                    return ($model->is_bank_verify == 1);
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblcustomermasterprovisional-is_bank_verify').prop('checked') == true;
                }", 'on' => ['createFront', 'updateFront', 'approve']],
                [['aadhaar_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAadharcard($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['reject', 'reroute']],
                [['ts_code_m', 'ts_code_e'], 'number', 'max' => 10, 'except' => ['reject', 'reroute']],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['email'], 'email', 'message' => Yii::t('app/validation', 'You have entered invalid email address. e.g. "abc@xyz.com"')],
                [['firstname', 'lastname', 'surname'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['local_firstname', 'local_lastname', 'local_surname'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['ifsc'], 'required', 'when' => function ($model) {
                    return !empty($model->bank_account_no);
                }, 'whenClient' => "function (attribute, value) {
                    return $('#tblcustomermasterprovisional-bank_account_no').val() != '';
                }", 'except' => ['reject', 'reroute']],
                [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['reject', 'reroute']],
                [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }, 'except' => ['reject', 'reroute']],
                [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code'], 'except' => ['reject', 'reroute']],
                [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code'], 'except' => ['reject', 'reroute']],
                [['beneficiary_name'], function ($attribute, $params) {
                    $error = Yii::$app->general->validateBeneficiary($this, $attribute, $params);
                    if ($error != NULL) {
                        $this->addError($attribute, Yii::t('app/validation', 'Beneficiary Name Is Invalid'));
                    }
                }, 'skipOnEmpty' => false, 'except' => ['reject', 'reroute']],
                [['aadhaar_no'], 'validateAdharNo', 'on' => ['createFront', 'updateFront', 'approve']],
                [['customer_code_ex'], 'checkExistingExCode', 'on' => ['createFront', 'updateFront', 'approve']],
                [['ref_code'], 'checkExistingRefCode', 'on' => ['createFront', 'updateFront', 'approve']],
                [['remarks'], 'required', 'message' => 'Reroute remarks cannot be blank.', 'on' => ['reroute']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblCustomerMaster', $this->form_validation_type);
        $client_rules1 = Yii::$app->customvalidation->getRules('TblContactDetails', 'customer-create');
        $client_rules2 = Yii::$app->customvalidation->getRules('TblBankDetails', 'default');
        $rules = array_merge($client_rules, $main_rules);
        $rules = array_merge($client_rules1, $rules);
        $rules = array_merge($client_rules2, $rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'customer_provisional_code' => Yii::t('app', 'Customer Provisional Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_code_ex' => Yii::t('app', 'Customer Code Ex'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'sap_code' => Yii::t('app', 'Sap Code'),
            'refference_code' => Yii::t('app', 'Refference Code'),
            'address' => Yii::t('app', 'Address'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'morning_kms' => Yii::t('app', 'Morning Kms'),
            'evening_kms' => Yii::t('app', 'Evening Kms'),
            'rate_chart_code' => Yii::t('app', 'Rate Chart Code'),
            'billing_payment_cycle' => Yii::t('app', 'Billing Payment Cycle'),
            'over_head' => Yii::t('app', 'Over Head'),
            'ccenter_code' => Yii::t('app', 'Ccenter Code'),
            'ref_code' => Yii::t('app', 'Code'),
            'old_bmc_code' => Yii::t('app', 'Old Bmc Code'),
            'old_mcc_plant_code' => Yii::t('app', 'Old Mcc Plant Code'),
            'old_route_code' => Yii::t('app', 'Old Route Code'),
            'auto_code' => Yii::t('app', 'Auto Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'ts_code_m' => Yii::t('app', 'Ts Code M'),
            'ts_code_e' => Yii::t('app', 'Ts Code E'),
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
            'customer_category' => Yii::t('app', 'Customer Category'),
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
            'distance_from_mcc' => Yii::t('app', 'Distance From Mcc'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'email' => Yii::t('app', 'Email'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'local_contact_person' => Yii::t('app', 'Local Contact Person'),
            'department' => Yii::t('app', 'Department'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'surname' => Yii::t('app', 'Surname'),
            'local_firstname' => Yii::t('app', 'Local Firstname'),
            'local_lastname' => Yii::t('app', 'Local Lastname'),
            'local_surname' => Yii::t('app', 'Local Surname'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'Collection'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'supervisor_employee_id' => Yii::t('app', 'Supervisor Employee'),
            'supervisor_employee_name' => Yii::t('app', 'Supervisor Employee Name'),
            'provisional_from' => Yii::t('app', 'Provisional From'),
            'customer_status' => Yii::t('app', 'Customer Status'),
            'is_approved' => Yii::t('app', 'Approval status'),
            'approved_at' => Yii::t('app', 'Approve Date'),
            'approved_by' => Yii::t('app', 'Approved By'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getMainBankDetails() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'customer_code'])->where(['tbl_bank_details.module_name' => 'customer', 'tbl_bank_details.is_default' => 1, 'tbl_bank_details.is_active' => 1]);
    }

    public function getMainContactDetails() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'customer_code'])->andOnCondition(['tbl_contact_details.module_name' => 'customer', 'tbl_contact_details.is_default' => 1, 'tbl_contact_details.is_active' => 1]);
    }

    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    public function setExCode($attribute, $params) {
        $this->customer_code_ex = strtoupper($this->customer_code_ex);
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_customer_master', 'customer_code_ex', 4, '', FALSE);
    }

    public function getCustomerTypePre() {
        return $this->hasOne(TblCustomerType::className(), ['union_code' => 'union_code', 'customer_type' => 'customer_type'])->andOnCondition(['tbl_customer_type.is_active' => 1]);
    }

    public function getCustomerPrivisionalDocuments() {
        $this->customer_provisional_code = (string) $this->customer_provisional_code;
        return $this->hasMany(TblAttachment::className(), ['module_code' => 'customer_provisional_code'])->andOnCondition(['module_name' => 'tbl_customer_master_provisional']);
    }

    public function getCustomerPrivisionalApproval() {
        $this->customer_provisional_code = (string) $this->customer_provisional_code;
        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'customer_provisional_code'])->andOnCondition(['process_name' => 'tbl_customer_master_provisional'])->orderBy('level ASC');
    }

    public function validateMobileNo($attribute, $params) {
        $mobile = $this->$attribute;

        if (!empty($mobile)) {
            $encryptedMobile = Yii::$app->general->encryptData($mobile);
            
            $existsInCustomer = TblCustomerMaster::find()->select(['customer_code', 'customer_name', 'customer_type'])->where(['is_active' => 1])
                    ->andWhere(['or', ['mobile_no' => $mobile], ['mobile_no' => $encryptedMobile]]);
            if (!empty($this->customer_code)) {
                $existsInCustomer->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInCustomer = $existsInCustomer->one();
            if ($existsInCustomer) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in ' . Yii::t('app', 'Customer') . ' - ' . Yii::t('app', 'Customer') . ' Code : ' . $existsInCustomer->customer_code . ' , ' . Yii::t('app', 'Customer') . ' Name : ' . $existsInCustomer->customer_name));
                return false;
            }

            $existsInProvisional = $this->find()
                    ->andWhere(['or', ['mobile_no' => $this->$attribute], ['mobile_no' => $encryptedMobile]])
                    ->andWhere(['not in', 'lower(status)', ['pending','reject']]);
            if (!empty($this->customer_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_provisional_code', $this->customer_provisional_code]);
            }
            if (!empty($this->customer_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in Provisional ' . Yii::t('app', 'Customer') . ' - Provisional ' . Yii::t('app', 'Customer') . ' Code : ' . $existsInProvisional->customer_provisional_code . ', Provisional ' . Yii::t('app', 'Customer') . ' Name : ' . $existsInProvisional->customer_name));
                return false;
            }

            $existsInContact = TblContactDetails::find()
                    ->select(['module_code', 'module_name'])
                    ->where(['is_active' => 1])
                    ->andWhere(['or', ['mobile_no' => $mobile], ['mobile_no' => $encryptedMobile]]);
            if (!empty($this->customer_code)) {
                $existsInContact->andWhere(['<>', 'module_code', $this->customer_code]);
            }
            if (!empty($this->detail_code)) {
                $existsInContact->andWhere(['<>', 'detail_code', $this->detail_code]);
            }
            $existsInContact = $existsInContact->one();
            if ($existsInContact) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in Contact Details - Module Code : ' . $existsInContact->module_code . ', Module Name : ' . $existsInContact->module_name));
                return false;
            }
        }
    }

    public function validateBankAccNo($attribute, $params) {
        $bankAccNo = $this->$attribute;

        if (!empty($bankAccNo)) {
            $encryptedBankAccNo = Yii::$app->general->encryptData($bankAccNo);

            $existsInProvisional = $this->find()->andWhere(['or', ['bank_account_no' => $this->$attribute], ['bank_account_no' => $encryptedBankAccNo]])
                    ->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);
            if (!empty($this->customer_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_provisional_code', $this->customer_provisional_code]);
            }
            if (!empty($this->customer_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in Provisional ' . Yii::t('app', 'Customer') . ' - Provisional ' . Yii::t('app', 'Customer') . ' Code : ' . $existsInProvisional->customer_provisional_code . ', Provisional ' . Yii::t('app', 'Customer') . ' Name : ' . $existsInProvisional->customer_name));
                return false;
            }

            $existsInBank = TblBankDetails::find()
                    ->where(['is_active' => 1])
                    ->andWhere(['or', ['bank_account_no' => $bankAccNo], ['bank_account_no' => $encryptedBankAccNo]])
                    ->andWhere(['or', ['ifsc' => $this->ifsc], ['ifsc' => \Yii::$app->general->encryptData($this->ifsc)]]);
            if (!empty($this->customer_code)) {
                $existsInBank->andWhere(['not', ['and', ['module_code' => $this->customer_code], ['module_name' => 'customer']]]);
            }
            $existsInBank = $existsInBank->one();
            if ($existsInBank) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in Bank Details - Module Code : ' . $existsInBank->module_code . ', Module Name : ' . $existsInBank->module_name));
                return false;
            }
        }
    }

    public function validateAdharNo($attribute, $params) {
        $adharNo = $this->$attribute;

        if (!empty($adharNo)) {
            $encryptedAdharNo = Yii::$app->general->encryptData($adharNo);

            $existsInCustomer = TblCustomerMaster::find()->select(['customer_code', 'customer_name', 'customer_type'])->where(['is_active' => 1])
                    ->andWhere(['or', ['aadhaar_no' => $adharNo], ['aadhaar_no' => $encryptedAdharNo]]);
            if (!empty($this->customer_code)) {
                $existsInCustomer->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInCustomer = $existsInCustomer->one();
            if ($existsInCustomer) {
                $this->addError($attribute, Yii::t('app/validation', 'Aadhar Card No already exists in ' . Yii::t('app', 'Customer') . ' - ' . Yii::t('app', 'Customer') . ' Code : ' . $existsInCustomer->customer_code . ', ' . Yii::t('app', 'Customer') . ' Name : ' . $existsInCustomer->customer_name));
                return false;
            }

            $existsInProvisional = $this->find()->andWhere(['or', ['aadhaar_no' => $this->$attribute], ['aadhaar_no' => $encryptedAdharNo]])
                    ->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);
            if (!empty($this->customer_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_provisional_code', $this->customer_provisional_code]);
            } else if (!empty($this->customer_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Aadhar Card No already exists in Provisional ' . Yii::t('app', 'Customer') . ' - Provisional ' . Yii::t('app', 'Customer') . ' Code : ' . $existsInProvisional->customer_provisional_code . ', Provisional ' . Yii::t('app', 'Customer') . ' Name :' . $existsInProvisional->customer_name));
                return false;
            }
        }
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function CheckDuplicate($attribute, $params) {
        if($this->scenario != 'reject' && $this->scenario != 'reroute'){
            if ($attribute == 'mobile_no') {
                return $this->validateMobileNo($attribute, $params);
            } else if ($attribute == 'bank_account_no') {
                return $this->validateBankAccNo($attribute, $params);
            }
        }
    }

    public function validateCustomerUniqueness($attribute, $params) {
        if (!empty($this->$attribute)) {
            $queryProv = TblCustomerMasterProvisional::find()->where([$attribute => $this->$attribute]);

            if ($attribute == 'sap_vendor_code') {
                $queryProv->andWhere(['union_code' => $this->union_code]);
            }
            if (!empty($this->customer_provisional_code)) {
                $queryProv->andWhere(['<>', 'customer_provisional_code', $this->customer_provisional_code]);
            }
            $queryProv->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);

            $existsProv = $queryProv->one();
            if ($existsProv) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' already exists in Provisional Customer - Provisional Code : ' . $existsProv->customer_provisional_code . ', Name : ' . $existsProv->customer_name));
                return false;
            }

            $query = TblCustomerMaster::find()->where([$attribute => $this->$attribute]);
            if ($attribute == 'sap_vendor_code') {
                $query->andWhere(['union_code' => $this->union_code]);
            }
            if (!empty($this->customer_code)) {
                $query->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $query->andWhere(['is_active' => 1]);

            $existsMaster = $query->one();
            if ($existsMaster) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' already exists in Customer Master - Customer Code : ' . $existsMaster->customer_code . ', Name : ' . $existsMaster->customer_name));
                return false;
            }
        }
    }

    public function checkExistingExCode($attribute, $params) {
        $code = $this->$attribute;
        if (!empty($code) && !empty($this->bmc_code)) {
            $existsInCustomer = TblCustomerMaster::find()->select(['customer_code', 'customer_name'])->where(['is_active' => 1])
                    ->andWhere(['customer_code_ex' => $code, 'bmc_code' => $this->bmc_code]);
            if (!empty($this->customer_code)) {
                $existsInCustomer->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInCustomer = $existsInCustomer->one();

            if ($existsInCustomer) {
                $this->addError($attribute, Yii::t('app/validation', 'Customer Code Ex already exists in Customer Master - Customer Code : ' . $existsInCustomer->customer_code . ', Name : ' . $existsInCustomer->customer_name));
                return false;
            }

            $existsInProvisional = $this->find()
                    ->andWhere(['customer_code_ex' => $code, 'bmc_code' => $this->bmc_code])
                    ->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);
            if (!empty($this->customer_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_provisional_code', $this->customer_provisional_code]);
            }
            if (!empty($this->customer_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Customer Code Ex already exists in Provisional Customer - Provisional Code : ' . $existsInProvisional->customer_provisional_code . ', Name : ' . $existsInProvisional->customer_name));
                return false;
            }
        }
    }

    public function checkExistingRefCode($attribute, $params) {
        $code = $this->$attribute;
        if (!empty($code)) {
            $existsInCustomer = TblCustomerMaster::find()->select(['customer_code', 'customer_name'])->where(['is_active' => 1])
                    ->andWhere(['ref_code' => $code]);
            if (!empty($this->customer_code)) {
                $existsInCustomer->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInCustomer = $existsInCustomer->one();

            if ($existsInCustomer) {
                $this->addError($attribute, Yii::t('app/validation', 'Code already exists in Customer Master - Customer Code : ' . $existsInCustomer->customer_code . ', Name : ' . $existsInCustomer->customer_name));
                return false;
            }

            $existsInProvisional = $this->find()
                    ->andWhere(['ref_code' => $code])
                    ->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);
            if (!empty($this->customer_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_provisional_code', $this->customer_provisional_code]);
            }
            if (!empty($this->customer_code)) {
                $existsInProvisional->andWhere(['<>', 'customer_code', $this->customer_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Code already exists in Provisional Customer - Provisional Code : ' . $existsInProvisional->customer_provisional_code . ', Name : ' . $existsInProvisional->customer_name));
                return false;
            }
        }
    }

}
