<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblBranch;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblCasteCategory;
use app\modules\general\models\TblBloodgroup;
use app\modules\general\models\TblGender;
use app\modules\general\models\TblReligion;
use app\modules\dcsoperation\models\TblMemberDownload;
use app\modules\general\models\TblRelationship;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\verification\models\TblKycRecord;
use app\modules\syncutility\models\TblSentbox;
use app\modules\document\models\TblAttachment;
use app\modules\document\models\TblDocumentMapping;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\welfarescheme\models\TblDocumentMasterInfo;
use yii\web\UploadedFile;
use webvimark\modules\UserManagement\models\User;
use app\modules\geo\models\TblRegion;
use app\modules\dcsoperation\models\TblMemberProvisionalShareDetails;
use app\modules\collection\models\TblProvisionalMilkCollection;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\dcsoperation\models\TblMemberProvisionalHistory;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollectionHistory;
use app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails;
use app\modules\dcsoperation\models\TblMemberFamilyDetailsHistory;
use app\modules\dcsoperation\models\TblMemberFamilyDetails;
use app\modules\dcsoperation\models\TblMemberProvisionalAnimalDetails;
use app\modules\dcsoperation\models\TblMemberAnimalDetailsHistory;
use app\modules\dcsoperation\models\TblMemberAnimalDetails;
use app\modules\dcsoperation\models\TblMemberShareDetailsHistory;
use app\modules\dcsoperation\models\TblMemberShareDetails;
use app\modules\jasperreports\controllers\DefaultController;
use app\modules\organisation\models\TblFederations;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\sms\models\TblAlertNotification;
use yii\base\UserException;
use app\modules\details\models\TblContactDetails;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_member_provisional".
 *
 * @property string $provisional_member_code
 * @property string $member_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $ex_member_code
 * @property string $member_name
 * @property string $father_name
 * @property string $surname
 * @property string $nominee_name
 * @property string $nominee_relation
 * @property string $dob
 * @property integer $bloodgroup_code
 * @property integer $gender_code
 * @property integer $qualification_code
 * @property integer $caste_category_code
 * @property integer $religion_code
 * @property string $land_class
 * @property string $total_land
 * @property integer $no_of_buffalo
 * @property integer $no_of_cow_cross
 * @property integer $no_of_cow_ind
 * @property integer $total_animals
 * @property integer $member_type_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $mobile_no
 * @property string $email
 * @property string $address
 * @property string $pincode
 * @property string $pan_no
 * @property string $adhar_no
 * @property string $voter_id
 * @property integer $annual_income
 * @property string $village_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property integer $payment_mode
 * @property integer $animal_type_code
 * @property string $hamlet_code
 * @property string $sub_district_code
 * @property string $district_code
 * @property string $state_code
 * @property string $union_code
 * @property string $local_name
 * @property string $local_father_name
 * @property string $local_surname
 * @property string $local_nominee_name
 * @property string $local_address
 * @property string $federation_code
 * @property string $bank_name
 * @property string $branch_name
 * @property string $upload
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $is_approved
 * @property string $approved_at
 * @property string $approved_by
 */
class TblMemberProvisional extends ChildModel {

    public $cnt, $reference_code, $society_code, $bmc_name, $process_approval_code, $activityStatus, $dcs_ref_code, $payment_type, $recipt_ref_no, $operation, $approve_remarks;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_provisional';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['approved_at', 'created_at', 'updated_at', 'federation_code', 'bank_name', 'branch_name', 'upload', 'religion_code', 'is_download', 'download_date_time', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'is_approved', 'approved_at', 'provisional_status', 'process_approval_code', 'remarks', 'vendor_code', 'latitude', 'longitude', 'occupation', 'age', 'daily_milk_total', 'home_consumption_milk', 'market_surplus_milk', 'annual_milk_pour', 'aadhaar_card_address', 'is_contact_verified', 'is_email_verify', 'is_verify', 'email_relation', 'member_identity_no', 'applicant_relation', 'post_office', 'is_aadhar_verify', 'is_operator_aggre', 'application_no', 'name_as_per_adhar', 'member_status', 'witness_name', 'place', 'dcs_ref_code', 'payment_type', 'recipt_ref_no', 'sap_farmer_code', 'operation', 'approve_remarks', 'route_code', 'supervisor_employee_id', 'supervisor_employee_name', 'receipt_scan_copy'], 'safe'],
                [['is_download', 'is_contact_verified', 'is_verify', 'is_email_verify'], 'default', 'value' => '0'],
                [['is_active'], 'default', 'value' => '1'],
                [['is_approved'], 'default', 'value' => '0', 'on' => 'importCsv'],
                [['member_type_code'], 'default', 'value' => '1'],
                [['bmc_code', 'mcc_plant_code', 'plant_code'], 'required', 'except' => ['importCsv', 'collection', 'pro_member_sap_import']],
                [['dcs_code', 'member_name'], 'required', 'except' => ['collection', 'pro_member_sap_import']],
                [['ex_member_code'], 'required', 'except' => ['collection', 'hosync', 'pro_member_sap_import']],
                [['hamlet_code'], 'required', 'except' => ['collection', 'hosync', 'hosyncUpdate', 'pro_member_sap_import']],
                [['gender_code', 'caste_category_code'], 'required', 'on' => ['EIPLAMCS_TEST']],
                [['dcs_code', 'hamlet_code', 'ex_member_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'collection', 'hosync', 'hosyncUpdate', 'pro_member_sap_import']],
                [['member_code', 'state_code', 'union_code'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'pro_member_sap_import']],
                [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['collection', 'importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'pro_member_sap_import']],
                [['member_name'], 'required', 'except' => ['customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'pro_member_sap_import']],
                [['branch_code', 'bank_account_no', 'ifsc'], 'required', 'on' => 'bank_selected'],
            /* [['member_name'],'unique', 'when' => function($model) {
              return ($model->isNewRecord)?true:false;
              },'skipOnEmpty'=> true], */
                [['gender_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'gender', false);
                }, 'on' => 'saveCreamyData'],
                [['email'], 'email', 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['member_code', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'land_class', 'total_land', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'address', 'pan_no', 'adhar_no', 'village_code', 'created_by', 'updated_by', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'payment_mode', 'voter_id', 'approved_by',], 'string', 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['qualification_code', 'caste_category_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'annual_income', 'is_active', 'animal_type_code', 'bloodgroup_code', 'gender_code', 'nominee_relation'], 'integer', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"'), 'except' => ['androidsync', 'hosync', 'hosyncUpdate', 'pro_member_sap_import']],
                [['ifsc', 'pan_no'], 'trim', 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['member_name', 'father_name', 'surname', 'nominee_name'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['androidsync', 'hosync', 'hosyncUpdate', 'create_animal']],
                [['mobile_no', 'religion_code'], 'integer', 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['pan_no'], 'unique', 'targetAttribute' => ['pan_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($attribute, $params) {
                    return ($this->chackExistRecord($params) && $this->is_active);
                }, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['email'], 'unique', 'targetAttribute' => ['email', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($attribute, $params) {
                    return ($this->chackExistRecord($params) && $this->is_active);
                }, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
            /*    [['adhar_no'], 'unique', 'targetAttribute' => ['adhar_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }],
              [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }], */
                ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($attribute, $params) {
                    return ($this->chackExistRecord($params) && $this->is_active);
                }, 'except' => ['saveCreamyData', 'androidsync', 'hosync', 'hosyncUpdate']],
                [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }, 'except' => ['saveCreamyData', 'androidsync', 'hosync', 'hosyncUpdate']],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'hosync', 'hosyncUpdate', 'create_animal']],
                [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'when' => function () {
                    return (!empty($this->branch_code) || (in_array($this->scenario, ['importLimitedCsv', 'importCsv']) && !empty($this->ifsc)));
                }, 'except' => ['saveCreamyData', 'androidsync', 'hosync', 'hosyncUpdate']],
                [['local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'androidsync', 'hosync', 'hosyncUpdate']],
                [['voter_id'], 'string', 'max' => 15, 'skipOnEmpty' => true, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['payment_mode'], 'string', 'max' => 10, 'skipOnEmpty' => true, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['dob'], function ($attribute, $params) {
                    Yii::$app->general->validateAge($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'hosync', 'hosyncUpdate', 'create_animal']],
                [['ex_member_code'], 'integer', 'min' => 1, 'max' => 9999, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['ex_member_code'], 'string', 'min' => 1, 'max' => 4, 'except' => ['androidsync', 'hosync', 'hosyncUpdate']],
                [['member_code'], 'unique', 'message' => Yii::t('app', 'Ex Member Code has already been taken.'), 'when' => function($attribute, $params) {
                    return ($this->chackExistRecord($params));
                }, 'except' => ['androidsync', 'MemberApprove', 'hosyncUpdate', 'create_animal', 'update_provisional_member', 'approval_member_detail']],
                [['member_code'], 'validateCreamyData', 'on' => ['saveCreamyData', 'androidsync', 'hosync', 'hosyncUpdate']],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'activityStatus'], 'safe'],
                [['member_code', 'federation_code', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'ex_member_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'bloodgroup_code', 'gender_code', 'qualification_code', 'caste_category_code', 'land_class', 'total_land', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'mobile_no', 'email', 'address', 'pincode', 'pan_no', 'adhar_no', 'annual_income', 'village_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'payment_mode', 'animal_type_code', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'bank_name', 'branch_name', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'nominee_relation', 'voter_id', 'religion_code', 'upload', 'download_date_time', 'is_download', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_approved', 'approved_at', 'approved_by', 'provisional_from', 'employee_code', 'employee_name', 'region_code'], 'safe'],
                [['mobile_no'], 'validateProvisionalMobile', 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'bank_selected', 'hosync', 'hosyncUpdate']],
                [['member_code'], 'refCodeGenerate', 'except' => ['importLimitedCsv', 'deactivate', 'saveCreamyData']],
                [['ex_member_code'], 'setExMember'],
                [['provisional_member_code'], 'setProvisionalMemberCode', 'on' => 'importCsv'],
                [['dcs_code'], 'setAddressDetail', 'on' => ['importCsv']],
                [['ex_member_code'], 'setProExMemberCode', 'on' => ['importCsv']],
                [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
                [['provisional_from'], 'default', 'value' => 'collection'],
                [['provisional_status'], 'default', 'value' => 'Pending'],
                [['application_no', 'sap_farmer_code'], 'required', 'on' => ['pro_member_sap_import']],
                [['application_no'], 'checkExistData', 'on' => ['pro_member_sap_import'], 'except' => ['createProvisionalMember', 'MemberDocument', 'MemberReroute']],
                [['beneficiary_name'], function ($attribute, $params) {
                    Yii::$app->general->validateBeneficiary($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'on' => ['createProvisionalMember']],
                [['bank_account_no', 'bank_code', 'branch_code'], 'required', 'when' => function ($model) {
                    return ($model->is_verify == 1);
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                }", 'on' => ['createProvisionalMember', 'approval_bank_detail']],
                [['adhar_no'], 'required', 'when' => function ($model) {
                    return ($model->is_aadhar_verify == 1);
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblmemberprovisional-is_aadhar_verify').prop('checked') == true;
                }", 'on' => ['createProvisionalMember']],
                [['mobile_no'], 'required', 'when' => function ($model) {
                    return ($model->is_contact_verified == 1);
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblmemberprovisional-is_contact_verified').prop('checked') == true;
                }", 'on' => ['createProvisionalMember']],
                [['email'], 'required', 'when' => function ($model) {
                    return ($model->is_email_verify == 1);
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblmemberprovisional-is_email_verify').prop('checked') == true;
                }", 'on' => ['createProvisionalMember']],
                [['ifsc'], 'required', 'when' => function ($model) {
                    return !empty($model->bank_account_no);
                }, 'whenClient' => "function (attribute, value) {
                    return $('#tblmemberprovisional-bank_account_no').val() != '';
                }", 'except' => ['saveCreamyData', 'androidsync', 'hosync', 'hosyncUpdate']],
                [['mobile_no'], 'validateMobileNo', 'on' => ['createProvisionalMember', 'update_provisional_member', 'approval_address_detail']],
                [['bank_account_no'], 'validateBankAccNo', 'on' => ['createProvisionalMember', 'update_provisional_member', 'approval_bank_detail']],
                [['adhar_no'], 'validateAdharNo', 'on' => ['createProvisionalMember', 'update_provisional_member', 'approval_adhar_detail']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMemberProvisional', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'ex_member_code' => Yii::t('app', 'Ex Member Code'),
            'member_name' => Yii::t('app', 'Member Name'),
            'father_name' => Yii::t('app', 'Father’s Name/Husband’s Name'),
            'surname' => Yii::t('app', 'Surname'),
            'nominee_name' => Yii::t('app', 'Nominee Name'),
            'nominee_relation' => Yii::t('app', 'Relation With Nominee'),
            'dob' => Yii::t('app', 'Date of Birth'),
            'bloodgroup_code' => Yii::t('app', 'Bloodgroup'),
            'religion' => Yii::t('app', 'Religion'),
            'religion_code' => Yii::t('app', 'Religion'),
            'gender_code' => Yii::t('app', 'Gender'),
            'qualification_code' => Yii::t('app', 'Qualification'),
            'caste_category_code' => Yii::t('app', 'Caste Category'),
            'land_class' => Yii::t('app', 'Land Class'),
            'total_land' => Yii::t('app', 'Total Land(In Hectares)'),
            'no_of_buffalo' => Yii::t('app', 'No Of Buffalo'),
            'no_of_cow_cross' => Yii::t('app', 'No Of Cow Cross'),
            'no_of_cow_ind' => Yii::t('app', 'No Of Cow Indigenious'),
            'total_animals' => Yii::t('app', 'Total Animals'),
            'member_type_code' => Yii::t('app', 'Member Type'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'account_no' => Yii::t('app', 'Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email Id'),
            'address' => Yii::t('app', 'Address'),
            'pincode' => Yii::t('app', 'Pincode'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'adhar_no' => Yii::t('app', 'Aadhar No'),
            'voter_id' => Yii::t('app', 'Voter ID'),
            'annual_income' => Yii::t('app', 'Annual Income'),
            'village_code' => Yii::t('app', 'Village'),
            'created_at' => Yii::t('app', 'Create Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'animal_type_code' => Yii::t('app', 'Milk Type'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'district_code' => Yii::t('app', 'District'),
            'state_code' => Yii::t('app', 'State'),
            'union_code' => Yii::t('app', 'Union'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'local_father_name' => Yii::t('app', 'Hindi Father Name'),
            'local_surname' => Yii::t('app', 'Hindi Surname'),
            'local_nominee_name' => Yii::t('app', 'Hindi Nominee Name'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'upload' => Yii::t('app', 'Imported'),
            'is_download' => Yii::t('app', 'Download Status'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'member_class' => Yii::t('app', 'Member Class'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'society_code' => Yii::t('app', 'Society Code'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_name' => Yii::t('app', 'Plant'),
            'bmc_name' => Yii::t('app', 'BMC Name'),
            'is_approved' => Yii::t('app', 'Approval status'),
            'plant_code' => Yii::t('app', 'Plant'),
            'pro_ex_member_code' => Yii::t('app', 'Pro Ex Member Code'),
            'approved_at' => Yii::t('app', 'Approve Date'),
            'employee_code' => Yii::t('app', 'Employee Code'),
            'employee_name' => Yii::t('app', 'Employee Name'),
            'region_code' => Yii::t('app', 'Region Name'),
            'annual_milk_pour' => Yii::t('app', 'Annual Milk Pour Commitment'),
            'is_contact_verified' => Yii::t('app', 'Is Contact Verify ?'),
            'is_verify' => Yii::t('app', 'Is Bank Verify ?'),
            'is_email_verify' => Yii::t('app', 'Is Email Verify ?'),
            'email_relation' => Yii::t('app', 'Email Relation with Applicant'),
            'is_operator_aggre' => Yii::t('app', 'Operator Is Agree That All The Information Is Verified And Correct.'),
            'dcs_ref_code' => Yii::t('app', 'Society Ref Code'),
            'payment_type' => Yii::t('app', 'Mode Of Payment'),
            'route_code' => Yii::t('app', 'Route'),
            'supervisor_employee_id' => Yii::t('app', 'Supervisor Employee'),
            'supervisor_employee_name' => Yii::t('app', 'Supervisor Employee Name')
        ];
    }

    public function chackExistRecord($params) {
        $provisionalStatus = ['register', 'pending', 'inprogress'];
        $statusInDatabase = $this::find()->where(['<>', 'provisional_member_code', $this->provisional_member_code])
                        ->andWhere([$params => $this->{$params}, 'lower(provisional_status)' => $provisionalStatus, 'is_active' => 1])->scalar();
        if (!empty($statusInDatabase)) {
            return true;
        }
        return false;
    }

    public static function primaryKey() {
        return array('provisional_member_code');
    }

    /**
     * @inheritdoc
     * @return TblMemberProvisionalQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMemberProvisionalQuery(get_called_class());
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
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFederationCode() {
        return $this->hasOne(TblFederations::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberTypeCode() {
        return $this->hasOne(TblMemberTypes::className(), ['member_type_code' => 'member_type_code']);
    }

    public function getCode() {
        return $this->dcs_code . str_pad($this->ex_member_code, 4, '0', STR_PAD_LEFT);
        $data = $this->find()->select(["MAX(CONVERT(INT,substring(member_code,13,4))) AS member_code"])->where(['dcs_code' => $this->dcs_code])->one();
        return $this->dcs_code . str_pad((int) $data['member_code'] + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getAnimalTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'animal_type_code']);
    }

    public function getCasteCategoryCode() {
        return $this->hasOne(TblCasteCategory::className(), ['caste_category_code' => 'caste_category_code']);
    }

    public function getReligionCode() {
        return $this->hasOne(TblReligion::className(), ['religion_code' => 'religion_code']);
    }

    public function getQualificationCode() {
        return $this->hasOne(TblQualification::className(), ['qualification_code' => 'qualification_code']);
    }

    public function getBloodGroupCode() {
        return $this->hasOne(TblBloodgroup::className(), ['blood_group_code' => 'bloodgroup_code']);
    }

    public function getGenderCode() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_code']);
    }

    public function getRelationship() {
        return $this->hasOne(TblRelationship::className(), ['relationship_code' => 'nominee_relation']);
    }

    public function getApplicantRelationship() {
        return $this->hasOne(TblRelationship::className(), ['relationship_code' => 'applicant_relation']);
    }

    public function getTblDcsBmc() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getMembers($dcs_code, $as_array = false) {
        if (!empty($dcs_code)) {
            $query = $this->find()->where(['dcs_code' => $dcs_code, 'is_active' => 1]);
            if ($as_array)
                $query->asArray();
            $members = $query->all();
            return $members;
        }
        return false;
    }

    public function generateBiplMemberFiles() {
        $cp_code = !empty($this->dcsCode->societyCodes) ? $this->dcsCode->societyCodes->bipl_code : '';
        $path = Yii::$app->params['biplDirPath'] . 'EKOMILK/' . $cp_code . '/' . 'MASFILES';
        $csvPath = Yii::$app->params['rateFilesPath'] . '/members/' . $cp_code;
        if (!empty($cp_code) && Yii::$app->general->checkDirectory($csvPath)) {
            $file = \Yii::getAlias('@webroot') . '/' . $csvPath . '/' . 'members.csv';
            $flag = $this->generateCsvFile($file, $this->dcsCode);
            if ($flag && Yii::$app->general->checkDirectory($path)) {
                $this->generateEncFile($file, $path);
            }
        }
        return;
    }

    private function generateEncFile($file, $path) {
        chdir(Yii::$app->params['biplMemberUtilityPath']);
//$file=\Yii::getAlias('@webroot').'/'.Yii::$app->params['biplMemberUtilityPath'].'/VFSD_EXAMPLE.csv';
        $path = $path . '/myvendor.ven';
//echo 'milkvendor_cmd_i386-win32_B.exe -i '.$file.' -o '.$path;
        $utility_path = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplMemberUtilityPath'];
        $command = 'cd ' . $utility_path . ' && ./milkven_cmd_x86_64-linux_B -i ' . $file . ' -o ' . $path;
//        exec('milkvendor_cmd_i386-win32_B.exe -i ' . $file . ' -o ' . $path);
        exec('cd ' . $utility_path . ' && ./milkven_cmd_x86_64-linux_B -i ' . $file . ' -o ' . $path);
//exit;
        return;
    }

    private function generateCsvFile($fileName, $dcs) {

        $result = \Yii::$app->db->createCommand("{CALL sp_Benny_Vendor_Header(:stationid)}")
                ->bindValue(':stationid', $dcs->dcs_code);
        $header = $result->queryAll();
        $header = array_column($header, 'VMin');
        $mresult = \Yii::$app->db->createCommand("{CALL sp_Benny_Vendor_Member(:stationid)}")
                ->bindValue(':stationid', $dcs->dcs_code);
        $members = $mresult->queryAll();
        $members = array_column($members, 'MemberLine');
        $tresult = \Yii::$app->db->createCommand("{CALL sp_Benny_Vendor_Tpt()}");
        $trans = $tresult->queryAll();
        $trans = array_column($trans, 'MemberLine');

        $text = '';
        $flag = false;
        foreach ($header as $h) {
            $text .= $h . PHP_EOL;
        }

        foreach ($members as $m) {
            $text .= $m . PHP_EOL;
        }

        foreach ($trans as $t) {
            $text .= $t . PHP_EOL;
        }
        $vfile = fopen($fileName, "w") or die("Unable to open file!");
        if (fwrite($vfile, $text)) {
            $flag = true;
        }
        fclose($vfile);
        return $flag;
    }

    public function afterSave($insert, $changedAttributes) {
        $model = new TblMemberDownload();
        $model->dcs_code = $this->dcs_code;
        $data = $model->getRecord();
        if (!empty($data)) {
            $model = $data;
        }
        $model->is_download = 1;
        $model->upload_datetime = date('Y-m-d H:i:s');
        $model->save();
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function getKycInfo() {
        $data = $this->find()->where(['member_code' => $this->member_code])->one();
        $address = $data->address . ',' . Yii::$app->general->getforeignkey($data->hamletCode, 'hamlet_name');
        $address .= '<br/>' . Yii::$app->general->getforeignkey($data->villageCode, 'village_name') . ',' . Yii::$app->general->getforeignkey($data->subDistrictCode, 'sub_district_name');
        $address .= '<br/>' . Yii::$app->general->getforeignkey($data->districtCode, 'district_name') . '-' . $data->pincode;

        $bankdata = 'A/C No. - ' . $data->bank_account_no;
        $bankdata .= '<br/>Bank - ' . Yii::$app->general->getforeignkey($data->bankCode, 'bank_name');
        $bankdata .= '<br/>Branch - ' . Yii::$app->general->getforeignkey($data->branchCode, 'branch_name');
        $bankdata .= '<br/>IFSC - ' . $data->ifsc;

        return ['name' => $data->member_name, 'address' => $address, 'bankdetail' => $bankdata, 'panno' => $data->pan_no, 'aadharno' => $data->adhar_no];
    }

    public function getKycCode() {
        return $this->hasOne(TblKycRecord::className(), ['module_id' => 'member_code'])
                        ->where(['module_name' => 'TblMemberProvisional']);
    }

    public function memberInfo($encryptedmobile) {
        if (strlen($this->member_code) == 4) {
            return $this->find()->where(['mobile_no' => $encryptedmobile, "RIGHT(member_code,4)" => $this->member_code])->andWhere(['is_active' => 1])->all();
        } else {
            return $this->find()->where(['mobile_no' => $encryptedmobile, 'member_code' => $this->member_code])->andWhere(['is_active' => 1])->all();
        }
    }

    public function getmember() {
        return $this->find()->where(['member_code' => $this->member_code])->andWhere(['is_active' => 1])->one();
    }

    public function validateCreamyData($attribute, $params) {
        $this->state_code = Yii::$app->general->getforeignkey($this->dcsCode, 'state_code');
        $this->district_code = Yii::$app->general->getforeignkey($this->dcsCode, 'district_code');
        $this->sub_district_code = Yii::$app->general->getforeignkey($this->dcsCode, 'sub_district_code');
        $this->hamlet_code = Yii::$app->general->getforeignkey($this->dcsCode, 'hamlet_code');
        $this->village_code = Yii::$app->general->getforeignkey($this->dcsCode, 'village_code');
        $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
        $this->federation_code = Yii::$app->general->getforeignkey($this->unionCode, 'federation_code');
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function refCodeGenerate($attribute, $params) {
        if ($this->isNewRecord) {
            $bmc = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
            $bmc_code = !empty($bmc) ? $bmc : '';
            $this->ref_code = $bmc_code . '3' . substr($this->dcs_code, -4) . substr($this->member_code, -3);
        }
    }

    public function setExMember($attribute, $params) {
        $this->ex_member_code = str_pad($this->ex_member_code, 4, '0', STR_PAD_LEFT);
    }

    public function validMember($member) {
        return $this->find()->where(['member_code' => $member, 'is_active' => 1])->one();
    }

    public function setProvisionalMemberCode($attribute, $params) {
        $this->provisional_member_code = Yii::$app->general->getPrimaryCode($this);
        $this->member_code = $this->dcs_code . str_pad($this->ex_member_code, 4, '0', STR_PAD_LEFT);
    }

    public function setAddressDetail($attribute, $params) {
        if (isset($this->dcsCode)) {
            $this->union_code = $this->dcsCode->union_code;
            $this->state_code = $this->dcsCode->state_code;
            $this->district_code = $this->dcsCode->district_code;
            $this->sub_district_code = $this->dcsCode->sub_district_code;
            $this->village_code = $this->dcsCode->village_code;
            $this->bmc_code = $this->dcsCode->bmc_code;
            $this->mcc_plant_code = $this->dcsCode->mcc_plant_code;
            $this->plant_code = $this->dcsCode->plant_code;
        }
    }

    public function setProExMemberCode($attribute, $params) {
        if (isset($this->dcsCode)) {
            $this->pro_ex_member_code = $this->ex_member_code;
        }
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

    public function getMemberPrivisionalDocuments() {
        return $this->hasMany(TblAttachment::className(), ['module_code' => 'provisional_member_code']);
    }

    public function getMemberPrivisionalApproval() {
        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'provisional_member_code'])->orderBy('level ASC');
//        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'dcs_provisional_code'])->andOnCondition(['tbl_process_approval.status' => 0])->orderBy('level ASC');
    }

    public function setChildTable(&$model, &$modelSave, &$childModel) {
        $content = $modelSave['content'];
        $model->scenario = 'hosync';
        if (isset($modelSave['operation_type']) && strtolower($modelSave['operation_type']) == 'update') {
            $model->provisional_from = 'mobile_update';
        }
        if (!empty($model->member_code)) {
            $this->setExMemberCode($model, $model->ex_member_code);
            $member = TblMember::find()->where(['member_code' => $model->member_code])->one();
            if (!empty($member)) {
                $model->scenario = 'hosyncUpdate';
                $member['originating_type'] = '';
                $member['created_at'] = '';
                $member['created_by'] = '';
                $member['updated_at'] = '';
                $member['updated_by'] = '';
                $setField = array_diff_key($member->attributes, $content);
                $model->setAttributes($setField);
            }
        } else {
            $ex_code = TblMember::find()->select(['ex_code' => 'ISNULL(MAX(CAST(ex_member_code as int)),0)+1'])
                    ->where(['dcs_code' => $model->dcs_code])
                    ->asArray()
                    ->one();
            $this->setExMemberCode($model, $ex_code['ex_code']);
            $ex_member_code = $this->find()->where(['ex_member_code' => $model->ex_member_code])->one();
            if (!empty($ex_member_code)) {
                $ex_code = $this->find()->select(['ex_code' => 'ISNULL(MAX(CAST(ex_member_code as int)),0)+1'])
                        ->where(['dcs_code' => $model->dcs_code])
                        ->asArray()
                        ->one();
            }
            $this->setExMemberCode($model, $ex_code['ex_code']);
            $model->member_code = $this->getCode();
        }
        $model->originating_org_type = 'HO';
        $model->provisional_member_code = Yii::$app->general->getUuid();
        $model->dob = empty($model->dob) ? NULL : $model->dob;
        $model->member_name = ucwords($model->member_name);
        $model->registration_date = empty($model->registration_date) ? NULL : Yii::$app->controls->view_date($model->registration_date, 'php:Y-m-d');
        $model->provisional_status = 'Register';
        $doc_mapping = TblDocumentMapping::find()->where(['master_type' => 'provisional_member'])->all();
        if (!empty($doc_mapping)) {
            $error_msg = '';
            $doc_path = Yii::$app->params['document_upload'] . 'provisional_member';
            if (Yii::$app->general->checkDirectory($doc_path)) {
                foreach ($doc_mapping as $doc) {
                    $master_doc = $doc->docId;
                    $fileLable = $master_doc->doc_name;
                    if (!empty($_FILES[$fileLable]['name'])) {
                        $attaFile = UploadedFile::getInstanceByName($fileLable);
                        if ($attaFile && !$attaFile->hasError) {
                            $file_name = 'provisional_member' . '_' . $model->provisional_member_code . '_' . $doc->doc_id . '_' . time() . '.' . $attaFile->extension;
                            $attachments = new TblAttachment();
                            $attachments->attachment = $doc_path . '/' . $file_name;
                            if ($attaFile->saveAs($attachments->attachment)) {
                                $attachments->module_code = $model->provisional_member_code;
                                $attachments->doc_id = $doc->doc_id;
                                $attachments->is_mandate = $doc->is_mandate;
                                $attachments->attachment_type = $master_doc->doc_ext;
                                $attachments->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $attachments->attachment;
                                $attachments->module_name = 'tbl_member_provisional';
                                $attachments->file_name = $file_name;
                                $childModel[] = $attachments;
                            } else {
                                $error_msg .= $master_doc->doc_name . '<br/>';
                            }
                        } else if ($doc->is_mandate == 1) {
                            $error_msg .= $master_doc->doc_name . '<br/>';
                        } else {
// Handle case when no file is uploaded
                        }
                    }
                }
            }
        }
        $config = Yii::$app->general->getUnionConfiguration($model->union_code, 'workflow_require', 'PORTAL');
        if ($config == 1) {
            $modelStages = new TblApprovalStagesDetail();
            $modelStages->setApprovalData($model->union_code, 'member', $model->provisional_member_code, $childModel, $approval_stages);
        }
    }

    public function setExMemberCode(&$model, $code) {
        $model->ex_member_code = !empty($code) ? str_pad($code, 4, '0', STR_PAD_LEFT) : '';
    }

    public function getUserName() {
        return $this->hasOne(user::className(), ['id' => 'created_by']);
    }

    public function getRegionCode() {
        return $this->hasOne(TblRegion::className(), ['region_code' => 'region_code']);
    }

    public function validateFlag($attribute, $param) {
        if ($this->is_contact_verified != 1 || $this->is_verify != 1 || $this->is_email_verify != 1 || $this->is_aadhar_verify != 1) {
            $this->addError($attribute, Yii::t('app/validation', 'Please verify Email address, Mobile no, Bank detail, Adhar No.'));
        }
    }

    public function getShareCode() {
        return $this->hasOne(TblMemberProvisionalShareDetails::className(), ['provisional_member_code' => 'provisional_member_code']);
    }

    public function getFamilyDetail() {
        return $this->hasOne(TblMemberProvisionalFamilyDetails::className(), ['provisional_member_code' => 'provisional_member_code'])->onCondition(['is_nominee' => 1]);
    }

    public function setChildTableSaveDelete(&$model, &$modelSave, &$deleteModel, &$unlink_files, &$attachments, &$memberdoc, &$errors) {
        $memberCreationPendingForSapApproval = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'member_creation_pending_for_sap_approval');
        $config = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'allow_member_other_detail');
        $model->member_status = 1;
        $all_doc = [];
        $memberdoc = [];
        $unlink_files = [];
        $attachments = [];
        $message = [];

        if (!empty($model) && strtolower($model->provisional_status) == 'approve' && $memberCreationPendingForSapApproval == '1') {
            $this->memberApprove($modelSave, $deleteModel, $model, $all_doc, $memberdoc, $message, $unlink_files, $attachments);
            if ($config == 1) {
                $this->memberEnrollmentApprove($modelSave, $model, $deleteModel);
            }
        }
        if (!empty($message)) {
            foreach ($message as $msg) {
                $errors[] = $msg;
            }
        }
    }

    public function memberApprove(&$model_save, &$deleteModel, $memberModel, &$all_attachment, &$memberdoc, &$message, &$unlink_files, &$attachments) {
        $tblMember = new TblMember();
        if ($memberModel->provisional_from == 'mobile_update') {
            $tblMember = TblMember::find()->where(['member_code' => $memberModel->member_code])->one();
            $memberCode = $memberModel->member_code;
            $historyMemberModel = new TblMemberHistory();
            Yii::$app->operation->history($tblMember, $historyMemberModel, UPDATE);
            $model_save[] = $historyMemberModel;
        }
        $tblMember->scenario = 'ApprovalMember';
        $tblMember->attributes = $memberModel->attributes;
        $tblMember->is_verified = $memberModel->is_verify;
        $tblMember->member_code = ($memberModel->provisional_from == 'mobile_update') ? $memberCode : $tblMember->getCode();
        if ($tblMember->validate()) {
            $model_save[] = $tblMember;
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
            $this->RegisterEmailRequest($memberModel, $tblMember, $model_save);
        } else {
            foreach ($tblMember->getErrors() as $errorkey => $value) {
                $message[] = $value;
            }
        }
    }

    public function attachmentPath(&$baseDirPath, &$docMoveFolderName, &$docFolderName) {
        $baseDirPath = Yii::$app->params['document_upload'];
        $docMoveFolderName = 'member';
        $docFolderName = 'provisional_member';
    }

    public function memberEnrollmentApprove(&$model_save, $memberModel, &$deleteModel) {
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

    public function checkExistData($attribute, $params) {
        $modelData = TblMemberProvisional::find()->where(['application_no' => $this->$attribute, 'member_status' => 0, 'provisional_status' => 'Approve',])->one();
        if (empty($modelData)) {
            $this->addError($attribute, 'Application number does not exist.');
        }
    }

    public function RegisterEmailRequest($memberModel, $tblMember, &$model_save) {
        if ($memberModel->is_email_verify == 1 && !empty($tblMember->email)) {
            $report_config = DefaultController::getLabels('ProvisionalMemberRegister');
            if (!empty($report_config)) {
                $receiver_type = 'EMAIL';
                $module_type = 'member_register_form';
                $apiMaster = new TblApiMaster();
                $apiMasterData = $apiMaster->getRecord($receiver_type, $tblMember->union_code);
                if (!empty($apiMasterData)) {
                    $templateModel = new TblAlertTemplate();
                    $templateData = $templateModel->getTemplateData($module_type, $receiver_type, $tblMember->union_code);
                    if (!empty($templateData)) {
                        $notificationModel = new TblAlertNotification();
                        $notificationModel->receiver_type = $receiver_type;
                        $notificationModel->message = $templateData->message;
                        $notificationModel->header_info = $templateData->header_info;
                        $notificationModel->send_status = 0;
                        $notificationModel->content_id = $apiMasterData->api_master_id;
                        $notificationModel->module_type = $module_type;
                        $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                        $notificationModel->send_mail = 1;
                        $notificationModel->receiver_detail = $tblMember->email;
                        $notificationModel->refecence_code = $tblMember->member_code;
                        $notificationModel->parent_code = $memberModel->provisional_member_code;
                        $notificationModel->filename = $tblMember->member_code . '-' . date('YmdHis') . '.pdf';
                        $notificationModel->has_attachment = 1;
                        $controls = [];
                        $controls['p_provisional_member_code'] = $memberModel->provisional_member_code;
                        $controls['p_lang_code'] = '1';
                        $controls['locale'] = 'hn';
                        $controls['digit_config'] = '1';
                        $controls['REPORT_LOCALE'] = 'hn_IN';
                        $notificationModel->file_param = json_encode($controls);
                        $notificationModel->file_path = $report_config['path'];
                        $model_save[] = $notificationModel;
                    }
                }
            }
        }
    }

    public function moveFiles($unlink_files, $attachments, $masterdoc) {
        $this->attachmentPath($baseDirPath, $docMoveFolderName, $docFolderName);
        $baseDir = Yii::getAlias('@webroot') . '/' . $baseDirPath;
        $docDir = $baseDir . $docFolderName;
        $moveDir = $baseDir . $docMoveFolderName;

        if (!empty($unlink_files)) {
            foreach ($unlink_files as $file) {
                try {
                    if (file_exists($moveDir . '/' . $file)) {
                        unlink($moveDir . '/' . $file);
                    }
                } catch (\Throwable $ex) {
                    
                }
            }
        }

        for ($i = 0; $i < count($attachments); $i++) {
            try {
                $all_doc = basename($attachments[$i]);
                $fileName = basename($masterdoc[$i]);
                $file = $moveDir . '/' . $fileName;
                file_put_contents($file, file_get_contents($attachments[$i]));
                if (file_exists($docDir . '/' . $all_doc)) {
                    unlink($docDir . '/' . $all_doc);
                }
            } catch (\Throwable $ex) {
                
            }
        }
    }

    public function checkDelete() {
        return (strtolower($this->provisional_status) == 'pending' || strtolower($this->provisional_status) == 'reroute') ? true : false;
    }

    public function getDcs($dcs_code) {
        return TblDcs::find()->select(['dcs_code_ex'])->where(['dcs_code' => $dcs_code])->one();
    }

    public function validateMobileNo($attribute, $params) {
        $mobile = $this->$attribute;

        if (!empty($mobile)) {
            $encryptedMobile = Yii::$app->general->encryptData($mobile);
            $existsInMember = TblMember::find()->select(['member_code', 'member_name'])->where(['is_active' => 1])
                    ->andWhere(['or', ['mobile_no' => $mobile], ['mobile_no' => $encryptedMobile]]);
            if (!$this->isNewRecord && $this->provisional_from == 'mobile_update') {
                $existsInMember->andWhere(['<>', 'member_code', $this->member_code]);
            }
            $existsInMember = $existsInMember->one();
            if ($existsInMember) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in ' . Yii::t('app', 'Member') . ' - ' . Yii::t('app', 'Member') . ' Code : ' . $existsInMember->member_code . ' , ' . Yii::t('app', 'Member') . ' Name : ' . $existsInMember->member_name));
                return false;
            }
        }
    }

    public function validateProvisionalMobile($attribute, $params) {
        if (!empty($this->$attribute)) {
            $encryptedMobile = Yii::$app->general->encryptData($this->$attribute);
            $existsInProvisional = $this->find()->where(['is_active' => 1])
                    ->andWhere(['or', ['mobile_no' => $this->$attribute], ['mobile_no' => $encryptedMobile]])
                    ->andWhere(['not in', 'lower(provisional_status)', ['reject']]);
            if (!$this->isNewRecord && $this->provisional_from != 'mobile_update') {
                $existsInProvisional->andWhere(['<>', 'provisional_member_code', $this->provisional_member_code]);
            } else if (!$this->isNewRecord && $this->provisional_from == 'mobile_update') {
                $existsInProvisional->andWhere(['<>', 'member_code', $this->member_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Mobile No already exists in Provisional ' . Yii::t('app', 'Member') . ' - Provisional ' . Yii::t('app', 'Member') . ' Code : ' . $existsInProvisional->provisional_member_code . ', Provisional ' . Yii::t('app', 'Member') . ' Name : ' . $existsInProvisional->member_name));
                return false;
            }
        }
    }

    public function validateBankAccNo($attribute, $params) {
        $bankAccNo = $this->$attribute;

        if (!empty($bankAccNo)) {
            $encryptedBankAccNo = Yii::$app->general->encryptData($bankAccNo);
            $existsInMember = TblMember::find()->select(['member_code', 'member_name'])->where(['is_active' => 1])
                    ->andWhere(['or', ['bank_account_no' => $bankAccNo], ['bank_account_no' => $encryptedBankAccNo]]);
            if (!$this->isNewRecord && $this->provisional_from == 'mobile_update') {
                $existsInMember->andWhere(['<>', 'member_code', $this->member_code]);
            }
            $existsInMember = $existsInMember->one();

            if ($existsInMember) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in ' . Yii::t('app', 'Member') . ' - ' . Yii::t('app', 'Member') . ' Code : ' . $existsInMember->member_code . ' , ' . Yii::t('app', 'Member') . ' Name : ' . $existsInMember->member_name));
                return false;
            }

            $existsInProvisional = $this->find()->where(['is_active' => 1])
                    ->andWhere(['or', ['bank_account_no' => $this->$attribute], ['bank_account_no' => $encryptedBankAccNo]])
                    ->andWhere(['not in', 'lower(provisional_status)', ['reject']]);
            if (!$this->isNewRecord && $this->provisional_from != 'mobile_update') {
                $existsInProvisional->andWhere(['<>', 'provisional_member_code', $this->provisional_member_code]);
            } else if (!$this->isNewRecord && $this->provisional_from == 'mobile_update') {
                $existsInProvisional->andWhere(['<>', 'member_code', $this->member_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No already exists in Provisional ' . Yii::t('app', 'Member') . ' - Provisional ' . Yii::t('app', 'Member') . ' Code : ' . $existsInProvisional->provisional_member_code . ', Provisional ' . Yii::t('app', 'Member') . ' Name : ' . $existsInProvisional->member_name));
                return false;
            }
        }
    }

    public function validateAdharNo($attribute, $params) {
        $adharNo = $this->$attribute;

        if (!empty($adharNo)) {
            $encryptedAdharNo = Yii::$app->general->encryptData($adharNo);
            $existsInMember = TblMember::find()->select(['member_code', 'member_name'])->where(['is_active' => 1])
                    ->andWhere(['or', ['adhar_no' => $adharNo], ['adhar_no' => $encryptedAdharNo]]);
            if (!$this->isNewRecord && $this->provisional_from == 'mobile_update') {
                $existsInMember->andWhere(['<>', 'member_code', $this->member_code]);
            }
            $existsInMember = $existsInMember->one();

            if ($existsInMember) {
                $this->addError($attribute, Yii::t('app/validation', 'Aadhar Card No already exists in ' . Yii::t('app', 'Member') . ' - ' . Yii::t('app', 'Member') . ' Code : ' . $existsInMember->member_code . ' , ' . Yii::t('app', 'Member') . ' Name : ' . $existsInMember->member_name));
                return false;
            }

            $existsInProvisional = $this->find()->where(['is_active' => 1])
                    ->andWhere(['or', ['adhar_no' => $this->$attribute], ['adhar_no' => $encryptedAdharNo]])
                    ->andWhere(['not in', 'lower(provisional_status)', ['reject']]);
            if (!$this->isNewRecord && $this->provisional_from != 'mobile_update') {
                $existsInProvisional->andWhere(['<>', 'provisional_member_code', $this->provisional_member_code]);
            } else if (!$this->isNewRecord && $this->provisional_from == 'mobile_update') {
                $existsInProvisional->andWhere(['<>', 'member_code', $this->member_code]);
            }
            $existsInProvisional = $existsInProvisional->one();

            if ($existsInProvisional) {
                $this->addError($attribute, Yii::t('app/validation', 'Aadhar Card No already exists in Provisional ' . Yii::t('app', 'Member') . ' - Provisional ' . Yii::t('app', 'Member') . ' Code : ' . $existsInProvisional->provisional_member_code . ', Provisional ' . Yii::t('app', 'Member') . ' Name : ' . $existsInProvisional->member_name));
                return false;
            }
        }
    }

    public function updateProcessStatus($data_post_status, $file_name) {
        return $this->updateAll(['data_post_status' => $data_post_status], ['resp_desc' => $file_name]);
    }

}
