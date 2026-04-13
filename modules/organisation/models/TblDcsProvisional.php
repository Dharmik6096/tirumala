<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\organisation\models\TblRoutes;
use app\modules\geo\models\TblDistricts;
use app\modules\details\models\TblContactDetails;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\general\models\TblDepartment;
use app\modules\globalmaster\models\TblDcsTypes;
use app\modules\general\models\TblOrganisationType;
use app\modules\general\models\TblSchemeType;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblBlocks;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use app\modules\general\models\TblSocietyVendor;
use app\modules\details\models\TblBankDetails;
use app\models\ChildModel;
use app\modules\document\models\TblAttachment;
use yii\db\Expression;
use app\modules\general\models\TblProcessApproval;
use yii\helpers\Html;
use app\modules\organisation\models\TblDcsDeactive;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_dcs_provisional".
 *
 * @property integer $id
 * @property string $address
 * @property integer $allow_multi_family_member
 * @property string $bank_account_no
 * @property string $contact_person
 * @property string $dcs_code_ex
 * @property string $dcs_name
 * @property string $dcs_short_name
 * @property string $destination_code
 * @property integer $destination_type
 * @property string $effective_date
 * @property string $email
 * @property string $ifsc
 * @property integer $is_active
 * @property integer $is_bmc
 * @property string $mobile_no
 * @property string $pan_no
 * @property string $phone_no
 * @property string $pincode
 * @property string $registration_code
 * @property string $registration_date
 * @property string $service_tax
 * @property string $tin_no
 * @property string $upi_no
 * @property string $bank_code
 * @property string $branch_code
 * @property string $beneficiary_name
 * @property integer $dcs_type_code
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $route_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $village_code
 * @property string $block_code
 * @property string $local_name
 * @property string $local_address
 * @property string $local_short_name
 * @property string $local_contact_person
 * @property string $logo_path
 * @property string $punch_line
 * @property integer $mapped_village_no
 * @property string $secretory_info
 * @property string $gst_no
 * @property string $fssi
 * @property integer $organisation_type_code
 * @property integer $scheme_type_code
 * @property integer $is_registered
 * @property string $valid_from
 * @property integer $data_post_status
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $DPUVersionNo
 * @property integer $rate_flag
 * @property string $bank_name
 * @property string $branch_name
 * @property integer $is_name_request
 * @property string $department
 * @property string $firstname
 * @property string $lastname
 * @property string $surname
 * @property string $local_firstname
 * @property string $local_lastname
 * @property string $local_surname
 * @property integer $is_dispatch_mandate
 * @property integer $is_weight_manual
 * @property integer $is_quality_manual
 * @property integer $dpu_type
 * @property integer $member_rate_code
 * @property string $old_bmc_code
 * @property string $old_mcc_plant_code
 * @property string $old_route_code
 * @property integer $is_live
 * @property integer $is_single_farmer
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $ccenter_code
 * @property string $center_code
 * @property string $vendor_code
 * @property string $sap_center_code
 * @property string $rate_chart_code
 * @property string $ref_code
 * @property integer $default_milk_type
 * @property integer $credit_sale_allow
 * @property integer $auto_code
 * @property integer $mfile_digit
 * @property string $data_post_id
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 * @property string $aadhaar_no
 * @property integer $is_chiller
 * @property string $ts_code_m
 * @property string $ts_code_e
 * @property string $sap_vendor_code
 * @property string $password
 * @property integer $antibiotic_check
 * @property string $cutoff
 * @property string $lower_milk_type
 * @property string $cutoff_val
 * @property string $gender
 * @property string $voter_id
 * @property string $dob
 * @property string $account_type
 * @property integer $is_security_cheque
 * @property string $cheque_number
 * @property string $cheque_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblDcsProvisional extends ChildModel {

    public $villages;
    public $federation_code;
    public $milk_type_code;
    public $street1;
    public $street2;
    public $bipl_code;
    public $vendor;
    public $society_status;
    public $download_status;
    public $tmcc_code;
    public $is_sentbox;
    public $same_milk_type, $diff_milk_type, $rate_chart_member, $with_member_rate;
    public $milk_type_auto, $detail_code;
    public $local_middlename, $is_verified;
    public $operation, $verifie_for, $file_name, $is_default;
    public $process_approval_code, $gender_code;
    public $toEncrypt = ['password', 'pan_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'aadhaar_no', 'dob', 'voter_id'];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_provisional';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['milk_type', 'milk_type_auto', 'auto_member_create', 'detail_code', 'is_default', 'latitude', 'longitude', 'dcs_status', 'supervisor_employee_id', 'supervisor_employee_name', 'same_milk_type', 'diff_milk_type'], 'safe'],
            [['status', 'dcs_code', 'milk_type_code', 'allow_multi_family_member', 'destination_type', 'is_active', 'is_bmc', 'dcs_type_code', 'mapped_village_no', 'organisation_type_code', 'scheme_type_code', 'is_registered', 'data_post_status', 'rate_flag', 'is_name_request', 'is_dispatch_mandate', 'is_weight_manual', 'is_quality_manual', 'dpu_type', 'member_rate_code', 'is_live', 'is_single_farmer', 'default_milk_type', 'credit_sale_allow', 'auto_code', 'mfile_digit', 'is_chiller', 'antibiotic_check', 'is_security_cheque', 'originating_type', 'effective_date', 'registration_date', 'valid_from', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'DPUVersionNo', 'morning_kms', 'evening_kms', 'cheque_amount', 'address', 'dcs_name', 'gender_code', 'cheque_bank', 'security_return_date', 'security_return_amt', 'security_return_mode'], 'safe'],
            [['bank_account_no', 'ifsc', 'mobile_no', 'pan_no', 'phone_no', 'upi_no', 'ccenter_code', 'center_code', 'vendor_code', 'sap_center_code', 'rate_chart_code', 'resp_status', 'resp_desc', 'aadhaar_no', 'ts_code_m', 'ts_code_e', 'dob', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'provisional_from', 'is_aadhar_verify', 'is_bank_verify', 'is_approved', 'approved_at', 'approved_by'], 'safe'],
            [['contact_person', 'dcs_short_name', 'beneficiary_name', 'punch_line', 'department', 'firstname', 'lastname', 'surname', 'password', 'gender', 'account_type', 'cheque_number', 'dcs_code_ex', 'route_code', 'old_route_code', 'cutoff', 'lower_milk_type', 'cutoff_val', 'destination_code', 'branch_code', 'email', 'pincode', 'village_code', 'mcc_plant_code', 'plant_code', 'old_mcc_plant_code', 'registration_code', 'service_tax', 'tin_no', 'gst_no', 'fssi', 'fssi_expiry_date'], 'safe'],
            [['bank_code', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'block_code', 'local_name', 'local_short_name', 'local_contact_person', 'logo_path', 'secretory_info', 'bank_name', 'branch_name', 'local_address', 'bmc_code', 'old_bmc_code', 'local_firstname', 'local_lastname', 'local_surname', 'ref_code', 'originating_org_code', 'originating_org_type', 'data_post_id', 'sap_vendor_code', 'voter_id', 'created_by', 'updated_by', 'ref_code'], 'safe'],
            [['street1', 'street2'], 'safe'],
            [['ifsc', 'pan_no', 'beneficiary_name', 'address'], 'trim'],
            [['is_security_cheque', 'is_approved', 'is_dispatch_mandate', 'is_live', 'credit_sale_allow', 'is_chiller', 'cutoff', 'morning_kms', 'evening_kms', 'is_bank_verify', 'is_aadhar_verify'], 'default', 'value' => 0],
            [['mfile_digit'], 'default', 'value' => 4],
            [['status'], 'default', 'value' => 'Pending'],
            [['dpu_type', 'default_milk_type'], 'default', 'value' => 8],
            [['is_active'], 'default', 'value' => 1],
            [['x_col1'], 'default', 'value' => '1#1'],
            [['cutoff_val'], 'default', 'value' => 0.1],
            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'pincode', 'firstname', 'mobile_no', 'is_dispatch_mandate', 'union_code', 'dcs_name', 'bmc_code', 'ref_code', 'dcs_short_name', 'is_bmc', 'destination_type', 'dpu_type', 'plant_code', 'mcc_plant_code', 'dcs_type_code'], 'required'],
            [['dcs_code'], 'required', 'on' => ['customImportUpdate']],
            [['milk_type_code'], 'required', 'on' => ['importCsv']],
            [['vendor'], 'required', 'except' => ['uploadDoc', 'approveDcs', 'beforeDocUpload']],
            [['union_code'], 'required', 'message' => Yii::t('app/validation', 'Union cannot be blank')],
            [['state_code'], 'required', 'message' => Yii::t('app/validation', 'State cannot be blank')],
            [['fssi_expiry_date'], 'required', 'when' => function ($model) { 
                return !empty($model->fssi); 
            }, 'whenClient' => "function (attribute, value) {return $('#tbldcsprovisional-fssi').val() !== '';}"],
            [['milk_type_code'], 'required', 'when' => function ($model) { 
                return empty($model->milk_type_auto); 
            }, 'whenClient' => "function (attribute, value) { return !$('#tbldcsprovisional-milk_type_auto').is(':checked') }", 'except' => ['uploadDoc', 'approveDcs', 'beforeDocUpload']],
            [['registration_code', 'registration_date'], 'required', 'when' => function ($model) { 
                return $model->is_registered == 1; 
            }, 'whenClient' => "function (attribute, value) { return $('#tbldcsprovisional-is_registered').is(':checked') }"],
            [['bank_account_no', 'bank_code', 'branch_code', 'ifsc'], 'required', 'when' => function ($model) { 
                return ($model->is_bank_verify == 1); 
            }, 'whenClient' => "function (attribute, value) { return $('#tbldcsprovisional-is_bank_verify').prop('checked') == true; }"],
            [['aadhaar_no'], 'required', 'when' => function ($model) { 
                return ($model->is_aadhar_verify == 1); 
            }, 'whenClient' => "function (attribute, value) { return $('#tbldcsprovisional-is_aadhar_verify').prop('checked') == true; }"],
            [['lower_milk_type', 'cutoff_val'], 'required', 'when' => function ($model) { 
                return $model->cutoff == 1; 
            }, 'whenClient' => "function (attribute, value) { return $('#tbldcsprovisional-cutoff').is(':checked') }"],
            [['is_weight_manual', 'is_quality_manual', 'credit_sale_allow', 'is_chiller'], 'boolean'],
            [['allow_multi_family_member', 'dcs_type_code'], 'integer'],
            [['ts_code_m', 'ts_code_e'], 'number'],
            [['cutoff_val'], 'number', 'min' => 0.1, 'max' => 99.9, 'skipOnEmpty' => true],
            [['address', 'dcs_name'], 'string', 'max' => 500],
            [['dcs_name', 'firstname'], 'string', 'max' => 25],
            [['registration_code'], 'string', 'max' => 20],
            [['contact_person'], 'string', 'max' => 100],
            [['gst_no'], 'string', 'max' => 15],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['password'], 'string', 'min' => 8, 'max' => 8],
            [['ts_code_m', 'ts_code_e', 'dcs_short_name'], 'string', 'max' => 25],
            [['email'], 'email', 'message' => Yii::t('app/validation', 'You have entered invalid email address.e.g. "abc@xyz.com"')],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code']],
            [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code']],
            [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code']],
            [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id']],
            [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
            [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code']],
            [['vendor'], function ($attribute, $params) { 
                Yii::$app->general->validateGlobalStatic($this, $attribute, 'vendor_type'); 
            }],
            [['dpu_type'], function ($attribute, $params) { 
                if (empty($this->getErrors()) && !empty($this->dpu_type) && !empty($this->vendor)) { 
                    Yii::$app->general->validateGlobalStatic($this, $attribute, $this->vendor . '_dpu_type'); 
                } 
            }, 'except' => ['approveDcs']],
            [['dpu_type'], function ($attribute, $params) { 
                if (empty($this->getErrors()) && !empty($this->dpu_type) && !empty($this->vendor_code)) { 
                    Yii::$app->general->validateGlobalStatic($this, $attribute, $this->vendor_code . '_dpu_type'); 
                } 
            }, 'on' => ['approveDcs']],
            [['mobile_no'], function ($attribute, $params) { 
                Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params); 
            }, 'skipOnEmpty' => false],
            [['phone_no'], function ($attribute, $params) { 
                Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params); 
            }, 'skipOnEmpty' => false],
            [['gst_no'], function ($attribute, $params) { 
                $this->validateGstNo($attribute, $params); 
            }, 'skipOnEmpty' => false],
            [['pan_no'], function ($attribute, $params) { 
                Yii::$app->general->validatePancard($this, $attribute, $params); 
            }, 'skipOnEmpty' => false],
            [['local_name', 'local_short_name', 'local_address'], function ($attribute, $params) { 
                Yii::$app->general->vaildateLocalField($this, $attribute, $params); 
            }, 'skipOnEmpty' => false],
            [['local_contact_person', 'local_firstname', 'local_lastname', 'local_surname'], function ($attribute, $params) { Yii::$app->general->vaildateLocalField($this, $attribute, $params); }, 'skipOnEmpty' => false],
            [['registration_code'], function ($attribute, $params) { 
                Yii::$app->general->vaildateNumericField($this, $attribute, $params); 
            }, 'skipOnEmpty' => false],
            [['is_dispatch_mandate'], function ($attribute, $params) { 
                Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_dispatch_mandate'); 
            }, 'skipOnEmpty' => false],
            [['voter_id'], function ($attribute, $params) { 
                Yii::$app->general->validateAadharcard($this, $attribute, $params); 
            }, 'skipOnEmpty' => true],
            [['cutoff_val'], function ($attribute, $params) { 
                if (!empty($this->cutoff) && $this->cutoff != '0000') { 
                    $this->validOneDigitDecimal($this, $attribute, $params); 
                } 
            }],
            [['ifsc'], 'required', 'when' => function ($model) {
                return !empty($model->bank_account_no);
            }, 'whenClient' => "function (attribute, value) {
                return $('#tbldcsprovisional-bank_account_no').val() != '';
            }"],
            [['beneficiary_name'], function ($attribute, $params) { 
                $error = Yii::$app->general->validateBeneficiary($this, $attribute, $params); 
                if ($error != NULL) { 
                    $this->addError($attribute, Yii::t('app/validation', 'Beneficiary Name Is Invalid')); 
                } 
            }, 'skipOnEmpty' => false],
            [['ifsc'], function ($attribute, $params) { 
                Yii::$app->general->validateIfsc($this, $attribute, $params); 
            }, 'skipOnEmpty' => true],
            [['firstname', 'lastname', 'surname'], function ($attribute, $params) { 
                Yii::$app->general->validateDiscriptiveField($this, $attribute); 
            }, 'skipOnEmpty' => false],
            [['bank_account_no'], function ($attribute, $params) { 
                $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute); 
                if ($error !== TRUE) { 
                    $this->addError($attribute, $error); 
                } 
            }],
            [['street1', 'street2'], function ($attribute, $params) { 
                Yii::$app->general->validateDiscriptiveField($this, $attribute, true); 
            }, 'skipOnEmpty' => false],
            [['address'], function ($attribute, $params) { 
                Yii::$app->general->validateDiscriptiveField($this, $attribute, true); 
            }, 'skipOnEmpty' => false, 'on' => ['approveDcs']],
            [['dcs_code_ex'], 'required', 'on' => ['approveDcs']],
            [['gst_no'], 'unique'],
            [['gst_no', 'sap_vendor_code', 'ref_code', 'is_bmc'], 'validateDcsUniqueness'],
            [['dcs_code_ex'], 'checkValid'],
            [['aadhaar_no'], 'validateAdharNo'],
            [['mobile_no'], 'validateMobileNo'],
            [['bank_account_no'], 'validateBankAccNo'],
            [['is_active'], 'checkActive', 'on' => 'beforeDocUpload'],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblDcsProvisional', $this->form_validation_type);
        $client_rules1 = Yii::$app->customvalidation->getRules('TblContactDetails', 'dcs-create');
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
            'dcs_provisional_code' => Yii::t('app', 'DCS Provisional Code'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'address' => Yii::t('app', 'Address'),
            'allow_multi_family_member' => Yii::t('app', 'Allow Multi Family Member'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'dcs_code_ex' => Yii::t('app', 'Dcs Code Ex'),
            'dcs_name' => Yii::t('app', 'Dcs Name'),
            'dcs_short_name' => Yii::t('app', 'Dcs Short Name'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'effective_date' => Yii::t('app', 'Effective Date'),
            'email' => Yii::t('app', 'Email'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_bmc' => Yii::t('app', 'Is Bmc'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_code' => Yii::t('app', 'Registration Code'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'service_tax' => Yii::t('app', 'Service Tax'),
            'tin_no' => Yii::t('app', 'Tin No'),
            'upi_no' => Yii::t('app', 'Upi No'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'dcs_type_code' => Yii::t('app', 'Dcs Type Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'route_code' => Yii::t('app', 'Route'),
            'state_code' => Yii::t('app', 'State Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'union_code' => Yii::t('app', 'Union'),
            'village_code' => Yii::t('app', 'Village'),
            'block_code' => Yii::t('app', 'Block Code'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'local_short_name' => Yii::t('app', 'Local Short Name'),
            'local_contact_person' => Yii::t('app', 'Local Contact Person'),
            'logo_path' => Yii::t('app', 'Logo Path'),
            'punch_line' => Yii::t('app', 'Punch Line'),
            'mapped_village_no' => Yii::t('app', 'Mapped Village No'),
            'secretory_info' => Yii::t('app', 'Secretory Info'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'fssi' => Yii::t('app', 'FSSAI'),
            'fssi_expiry_date' => Yii::t('app', 'FSSAI Expiry Date'),
            'organisation_type_code' => Yii::t('app', 'Organisation Type Code'),
            'scheme_type_code' => Yii::t('app', 'Scheme Type Code'),
            'is_registered' => Yii::t('app', 'Is Registered'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'DPUVersionNo' => Yii::t('app', 'Dpu Version No'),
            'rate_flag' => Yii::t('app', 'Rate Flag'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'is_name_request' => Yii::t('app', 'Is Name Request'),
            'department' => Yii::t('app', 'Department'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'surname' => Yii::t('app', 'Surname'),
            'local_firstname' => Yii::t('app', 'Local Firstname'),
            'local_lastname' => Yii::t('app', 'Local Lastname'),
            'local_surname' => Yii::t('app', 'Local Surname'),
            'is_dispatch_mandate' => Yii::t('app', 'Is Dispatch Mandate'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'dpu_type' => Yii::t('app', 'Dpu Type'),
            'member_rate_code' => Yii::t('app', 'Member Rate Code'),
            'old_bmc_code' => Yii::t('app', 'Old Bmc Code'),
            'old_mcc_plant_code' => Yii::t('app', 'Old Mcc Plant Code'),
            'old_route_code' => Yii::t('app', 'Old Route Code'),
            'is_live' => Yii::t('app', 'Is Live'),
            'is_single_farmer' => Yii::t('app', 'Is Single Farmer'),
            'morning_kms' => Yii::t('app', 'Morning Kms'),
            'evening_kms' => Yii::t('app', 'Evening Kms'),
            'ccenter_code' => Yii::t('app', 'Ccenter Code'),
            'center_code' => Yii::t('app', 'Center Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'sap_center_code' => Yii::t('app', 'Sap Center Code'),
            'rate_chart_code' => Yii::t('app', 'Rate Chart Code'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'default_milk_type' => Yii::t('app', 'Default Milk Type'),
            'credit_sale_allow' => Yii::t('app', 'Credit Sale Allow'),
            'auto_code' => Yii::t('app', 'Auto Code'),
            'mfile_digit' => Yii::t('app', 'Mfile Digit'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'is_chiller' => Yii::t('app', 'Is Chiller'),
            'ts_code_m' => Yii::t('app', 'Ts Code M'),
            'ts_code_e' => Yii::t('app', 'Ts Code E'),
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
            'password' => Yii::t('app', 'Password'),
            'antibiotic_check' => Yii::t('app', 'Antibiotic Check'),
            'cutoff' => Yii::t('app', 'Cutoff'),
            'lower_milk_type' => Yii::t('app', 'Lower Milk Type'),
            'cutoff_val' => Yii::t('app', 'Cutoff Val'),
            'gender' => Yii::t('app', 'Gender'),
            'voter_id' => Yii::t('app', 'Voter ID'),
            'dob' => Yii::t('app', 'Dob'),
            'account_type' => Yii::t('app', 'Account Type'),
            'is_security_cheque' => Yii::t('app', 'Is Security Cheque?'),
            'cheque_number' => Yii::t('app', 'Cheque Number'),
            'cheque_amount' => Yii::t('app', 'Cheque Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'Collection'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'status' => Yii::t('app', 'Provisional Status'),
            'supervisor_employee_id' => Yii::t('app', 'Supervisor Employee'),
            'supervisor_employee_name' => Yii::t('app', 'Supervisor Employee Name'),
            'security_return_date' => Yii::t('app', 'Security Return Date'),
            'security_return_amt' => Yii::t('app', 'Security Return Amount'),
            'security_return_mode' => Yii::t('app', 'Security Return Mode'),
            'cheque_bank' => Yii::t('app', 'Cheque Bank'),
            'provisional_from' => Yii::t('app', 'Provisional From'),
            'is_aadhar_verify' => Yii::t('app', 'Is Aadhar Verify'),
            'is_bank_verify' => Yii::t('app', 'Is Bank Verify'),
            'is_approved' => Yii::t('app', 'Approval Status'),
            'approved_at' => Yii::t('app', 'Approve Date'),
            'approved_by' => Yii::t('app', 'Approved By'),
        ];
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_dcs', 'dcs_code_ex', 9);
    }

    public function fullAddress() {
        return trim($this->street1) . ', ' . trim($this->street2);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    public function getDefaultMobileNo() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'dcs_code'])->andOnCondition(['tbl_contact_details.module_name' => 'society', 'tbl_contact_details.is_default' => 1, 'tbl_contact_details.is_active' => 1]);
    }

    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getLowerMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'lower_milk_type']);
    }

    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getBlockCode() {
        return $this->hasOne(TblBlocks::className(), ['block_code' => 'block_code']);
    }

    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsTypeCode() {
        return $this->hasOne(TblDcsTypes::className(), ['dcs_type_code' => 'dcs_type_code']);
    }

    public function getOrganisationTypeCode() {
        return $this->hasOne(TblOrganisationType::className(), ['organisation_type_code' => 'organisation_type_code']);
    }

    public function getRouteMapping() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getDcsPrivisionalDocuments() {
        $this->dcs_provisional_code = (string) $this->dcs_provisional_code;
        return $this->hasMany(TblAttachment::className(), ['module_code' => 'dcs_provisional_code'])->andOnCondition(['tbl_attachment.module_name' => 'tbl_dcs_provisional']);
    }

    public function getDcsPrivisionalApproval() {
        $this->dcs_provisional_code = (string) $this->dcs_provisional_code;
        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'dcs_provisional_code'])->andOnCondition(['tbl_process_approval.process_name' => 'society'])->orderBy('level ASC');
    }

    function validOneDigitDecimal($model, $attribute, $params) {
        $pattern = "/^[0-9]{2}[.][0-9]{1}$/";
        $value = $model->$attribute;
        if (!preg_match($pattern, (float) $value)) {
            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . ' is must be between 0.1 to 99.9'));
            return false;
        }

        return TRUE;
    }

    public function getSchemeTypeCode() {
        return $this->hasOne(TblSchemeType::className(), ['scheme_type_code' => 'scheme_type_code']);
    }

    public function getSocietyVendors() {
        return $this->hasOne(TblSocietyVendor::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMilkTypes() {

        $milkType = new TblAnimalType();
        $data = $milkType->getAnimalMilkTypeArray();

        $values = TblDcsMilkType::find()->where(['dcs_code' => $this->dcs_code, 'is_active' => 1])->asArray()->all();
        $selected = [];

        foreach ($data as $key => $row) {
            if (array_search($key, array_column($values, 'milk_type_code')) !== FALSE) {
                $selected[$key] = ['selected' => 'selected'];
            }
        }
        return ['value' => $data, 'selected' => $selected];
    }

    public function importData($attribute, $params) {
        if (!empty($this->rate_chart_member) && empty($this->purchaseRate)) {
            $this->addError('rate_chart_member', Yii::t('app/validation', $this->getAttributeLabel('rate_chart_member') . ' is Invalid.'));
            return false;
        }
    }

    public function setBankDetail($attribute, $params) {

        if (empty($this->getErrors()) && !empty($this->ifsc)) {
            $this->branch_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'branch_code');
            $this->bank_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'bank_code');
            if (empty($this->branch_code)) {
                $this->addError('ifsc', Yii::t('app/validation', $this->getAttributeLabel('ifsc') . ' is Invalid.'));
                return false;
            }
        }
    }

    public function setXcol($attribute, $params) {
        $same = empty($this->same_milk_type) ? 0 : $this->same_milk_type;
        $different = empty($this->diff_milk_type) ? 0 : $this->diff_milk_type;
        $this->x_col1 = $same . '#' . $different;
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

    public function validateRoute($attribute, $params) {
        $this->route_code = $this->route;
        $this->route_code = Yii::$app->general->getforeignkey($this->routeRefCode, 'route_code');

        if ((empty($this->routeMapping)) || (!empty($this->routeMapping) && ($this->routeMapping->to_type != 'bmc' || $this->routeMapping->to_dest != $this->bmc_code))) {
            $this->addError('route_code', Yii::t('app/validation', $this->getAttributeLabel('route_code') . ' is Invalid.'));
        }
    }

    public function validateGstNo($attribute, $params) {

        if (!empty($this->gst_no))
            if (strlen($this->gst_no) != 15) {
                $this->addError($attribute, Yii::t('app/validation', 'Gst no must contain 15 characters'));
            }
        return false;
    }

    public function setDefaultMilkType($modelDcsMilkType, $passKey = '') {
        $Key = isset($passKey) && !empty($passKey) ? $passKey : 'milk_type_code';
        $milkType = [];
        foreach ($modelDcsMilkType as $type) {
            $milkType[] = $type->$Key;
        }
        if (!empty($milkType)) {
            if (in_array(1, $milkType) && in_array(2, $milkType) && in_array(3, $milkType)) {
                $defaultMilk = 7;
            } else if (in_array(1, $milkType)) {
                $defaultMilk = 1;
                if (in_array(2, $milkType)) {
                    $defaultMilk = 4;
                } else if (in_array(3, $milkType)) {
                    $defaultMilk = 6;
                }
            } else if (in_array(2, $milkType)) {
                $defaultMilk = 2;
                if (in_array(3, $milkType)) {
                    $defaultMilk = 5;
                }
            } else if (in_array(3, $milkType)) {
                $defaultMilk = 3;
            }
        }
        return $defaultMilk;
    }

    public function validateMobileNo($attribute, $params) {
        $mobile = $this->$attribute;

        if (!empty($mobile)) {
            $encryptedMobile = Yii::$app->general->encryptData($mobile);
            $existsInDcs = TblDcs::find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1])
                    ->andWhere(['or', ['mobile_no' => $mobile], ['mobile_no' => $encryptedMobile]]);
            if (!empty($this->dcs_code)) {
                $existsInDcs->andWhere(['<>', 'dcs_code', $this->dcs_code]);
            }
            $existsInDcs = $existsInDcs->one();
            if ($existsInDcs) {
                $isDeactive = Yii::$app->general->getDeactivateRecords($existsInDcs->dcs_code, TblDcsDeactive::class, 'dcs_code');
                if (empty($isDeactive)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in ' . Yii::t('app', 'DCS') . ' - ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInDcs->dcs_code . ' , ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInDcs->dcs_name));
                    return false;
                }
            }

            $existsInProvisional = $this->find()->where(['is_active' => 1])
                    ->andWhere(['or', ['mobile_no' => $this->$attribute], ['mobile_no' => $encryptedMobile]])
                    ->andWhere(['not in', 'lower(status)', ['pending','reject']]);
            if (!empty($this->dcs_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'dcs_provisional_code', $this->dcs_provisional_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in Provisional ' . Yii::t('app', 'DCS') . ' - Provisional ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInProvisional->dcs_provisional_code . ', Provisional ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInProvisional->dcs_name));
                return false;
            }
        }
    }

    public function validateBankAccNo($attribute, $params) {
        $bankAccNo = $this->$attribute;

        if (!empty($bankAccNo)) {
            $encryptedBankAccNo = Yii::$app->general->encryptData($bankAccNo);
            $existsInDcs = TblDcs::find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1])
                    ->andWhere(['or', ['bank_account_no' => $bankAccNo], ['bank_account_no' => $encryptedBankAccNo]]);
            if (!empty($this->dcs_code)) {
                $existsInDcs->andWhere(['<>', 'dcs_code', $this->dcs_code]);
            }
            $existsInDcs = $existsInDcs->one();
            if ($existsInDcs) {
                $isDeactive = Yii::$app->general->getDeactivateRecords($existsInDcs->dcs_code, TblDcsDeactive::class, 'dcs_code');
                if (empty($isDeactive)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in ' . Yii::t('app', 'DCS') . ' - ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInDcs->dcs_code . ' , ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInDcs->dcs_name));
                    return false;
                }
            }

            $existsInProvisional = $this->find()->where(['is_active' => 1])
                    ->andWhere(['or', ['bank_account_no' => $this->$attribute], ['bank_account_no' => $encryptedBankAccNo]])
                    ->andWhere(['not in', 'lower(status)', ['pending','reject']]);
            if (!empty($this->dcs_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'dcs_provisional_code', $this->dcs_provisional_code]);
            }
            if (!empty($this->dcs_code)) {
                $existsInProvisional->andWhere(['<>', 'dcs_code', $this->dcs_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in Provisional ' . Yii::t('app', 'DCS') . ' - Provisional ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInProvisional->dcs_provisional_code . ', Provisional ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInProvisional->dcs_name));
                return false;
            }
        }
    }

    public function validateAdharNo($attribute, $params) {
        $adharNo = $this->$attribute;

        if (!empty($adharNo)) {
            $encryptedAdharNo = Yii::$app->general->encryptData($adharNo);
            $existsInDcs = TblDcs::find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1])
                    ->andWhere(['or', ['aadhaar_no' => $adharNo], ['aadhaar_no' => $encryptedAdharNo]]);
            if (!empty($this->dcs_code)) {
                $existsInDcs->andWhere(['<>', 'dcs_code', $this->dcs_code]);
            }
            $existsInDcs = $existsInDcs->one();
            if ($existsInDcs) {
                $isDeactive = Yii::$app->general->getDeactivateRecords($existsInDcs->dcs_code, TblDcsDeactive::class, 'dcs_code');
                if (empty($isDeactive)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Aadhar Card No already exists in ' . Yii::t('app', 'DCS') . ' - ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInDcs->dcs_code . ' , ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInDcs->dcs_name));
                    return false;
                }
            }
            $existsInProvisional = $this->find()->where(['is_active' => 1])
                    ->andWhere(['or', ['aadhaar_no' => $this->$attribute], ['aadhaar_no' => $encryptedAdharNo]])
                    ->andWhere(['not in', 'lower(status)', ['reject','pending']]);
            if (!empty($this->dcs_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'dcs_provisional_code', $this->dcs_provisional_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Aadhar Card No already exists in Provisional ' . Yii::t('app', 'DCS') . ' - Provisional ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInProvisional->dcs_provisional_code . ', Provisional ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInProvisional->dcs_name));
                return false;
            }
        }
    }
    
    public function checkValid($attribute, $params) {
        $value = $this->$attribute;
        if (!empty($value)) {
            $existsInProvisional = $this->find()
                ->where(['dcs_code_ex' => $value, 'bmc_code' => $this->bmc_code])
                ->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);

            if (!empty($this->dcs_provisional_code)) {
                $existsInProvisional->andWhere(['<>', 'dcs_provisional_code', $this->dcs_provisional_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' already exists in Provisional ' . Yii::t('app', 'DCS') . ' - Provisional ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInProvisional->dcs_provisional_code . ', Provisional ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInProvisional->dcs_name));
                return false;
            }

            $existsInDcs = TblDcs::find()
                ->select(['dcs_code', 'dcs_name'])
                ->where(['dcs_code_ex' => $value, 'bmc_code' => $this->bmc_code]);

            if (!empty($this->dcs_code)) {
                $existsInDcs->andWhere(['<>', 'dcs_code', $this->dcs_code]);
            }
            $existsInDcs = $existsInDcs->one();

            if ($existsInDcs) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' already exists in ' . Yii::t('app', 'DCS') . ' - ' . Yii::t('app', 'DCS') . ' Code : ' . $existsInDcs->dcs_code . ' , ' . Yii::t('app', 'DCS') . ' Name : ' . $existsInDcs->dcs_name));
                return false;
            }
        }
    }

    public function validateRouteCode($attribute, $params) {
        $this->route_code = Yii::$app->general->getforeignkey($this->routeRefCode, 'route_code');

        if ((empty($this->routeMapping)) || (!empty($this->routeMapping) && ($this->routeMapping->to_type != 'bmc' || $this->routeMapping->to_dest != $this->bmc_code))) {
            $this->addError('route_code', Yii::t('app/validation', $this->getAttributeLabel('route_code') . ' is Invalid.'));
        }
    }



    public function validateDcsUniqueness($attribute, $params) {
        if (!empty($this->$attribute)) {
            $queryProv = TblDcsProvisional::find()->where([$attribute => $this->$attribute])->andWhere(['not in', 'lower(status)', ['reject', 'pending']]);

            if ($attribute == 'sap_vendor_code' || $attribute == 'ref_code') {
                $queryProv->andWhere(['union_code' => $this->union_code]);
            }
            if ($attribute == 'is_bmc' && $this->is_bmc && $attribute != 'ref_code' && $attribute != 'sap_vendor_code') {
                $queryProv->andWhere(['bmc_code' => $this->bmc_code]);
            }
            if (!empty($this->dcs_provisional_code)) {
                $queryProv->andWhere(['<>', 'dcs_provisional_code', $this->dcs_provisional_code]);
            }

            $existsProv = $queryProv->one();
            if ($existsProv) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' already exists in Provisional DCS'));
                return;
            }

            $query = TblDcs::find()->where([$attribute => $this->$attribute]);

            if ($attribute == 'sap_vendor_code' || $attribute == 'ref_code') {
                $query->andWhere(['union_code' => $this->union_code]);
            }
            if ($attribute == 'is_bmc' && $this->is_bmc && $attribute != 'ref_code' && $attribute != 'sap_vendor_code') {
                $query->andWhere(['bmc_code' => $this->bmc_code]);
            }
            if (!empty($this->dcs_code)) {
                $query->andWhere(['<>', 'dcs_code', $this->dcs_code]);
            }

            $exists = $query->one();
            if ($exists) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' has already been taken in DCS - Code: ' . $exists->dcs_code . ', Name: ' . $exists->dcs_name));
            }
        }
    }

    public function CheckDuplicate($attribute, $params) {
        if ($attribute == 'mobile_no') {
            $mobile = $this->$attribute;
            if (!empty($mobile)) {
                $encryptedMobile = Yii::$app->general->encryptData($mobile);
                $existsInContact = TblContactDetails::find()
                        ->select(['module_code', 'module_name'])
                        ->where(['is_active' => 1])
                        ->andWhere(['or', ['mobile_no' => $mobile], ['mobile_no' => $encryptedMobile]]);
                if (!empty($this->dcs_code)) {
                    $existsInContact->andWhere(['not', ['and', ['module_code' => $this->dcs_code], ['module_name' => 'society']]]);
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
        } elseif ($attribute == 'bank_account_no') {
            $bankAccNo = $this->$attribute;
            if (!empty($bankAccNo)) {
                $encryptedBankAccNo = Yii::$app->general->encryptData($bankAccNo);
                $existsInBank = TblBankDetails::find()
                        ->select(['module_code', 'module_name'])
                        ->where(['is_active' => 1])
                        ->andWhere(['or', ['bank_account_no' => $bankAccNo], ['bank_account_no' => $encryptedBankAccNo]])
                        ->andWhere(['or', ['ifsc' => $this->ifsc], ['ifsc' => \Yii::$app->general->encryptData($this->ifsc)]]);
                if (!empty($this->dcs_code)) {
                    $existsInBank->andWhere(['not', ['and', ['module_code' => $this->dcs_code], ['module_name' => 'society']]]);
                }
                if (!empty($this->detail_code)) {
                    $existsInBank->andWhere(['<>', 'detail_code', $this->detail_code]);
                }
                $existsInBank = $existsInBank->one();
                if ($existsInBank) {
                    $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in Bank Details - Module Code : ' . $existsInBank->module_code . ', Module Name : ' . $existsInBank->module_name));
                    return false;
                }
            }
        }
    }

    public function checkActive($attribute, $params)
    {
        if (!$this->is_active) {
            $this->addError($attribute, Yii::t('app/validation', 'You cannot upload documents for an inactive DCS.'));
            return false;
        }
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
