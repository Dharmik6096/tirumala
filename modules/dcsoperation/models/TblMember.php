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
use app\modules\verification\models\TblKycRecord;
use app\modules\syncutility\models\TblSentbox;
use yii\db\Query;
use app\modules\organisation\models\TblDcsVendorStatus;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\geo\models\TblRegion;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblMemberSpecialCode;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_member".
 *
 * @property string $member_code
 * @property string $dcs_code
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
 */
class TblMember extends ChildModel {

    public $cnt, $reference_code, $max_allowed_qty, $import_key_pattern, $bmc_code;
    public $operation, $verifie_for, $file_name, $dcs_ref_code;
    public $check_is_dcs_member = 1;
    public $import_eipl_code, $import_union_code, $special_code;
    public $reference_id, $name_at_bank, $city, $branch, $micr, $name_match_result, $name_match_score, $account_status, $account_status_code, $utr, $ifsc_code, $has_available_branch_info, $branch_address, $beneficiary_id;
    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['is_download', 'is_email_verify'], 'default', 'value' => '0'],
                [['is_active'], 'default', 'value' => '1'],
                [['member_type_code'], 'default', 'value' => '1'],
                [['dcs_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'specialCodeImportCsv']],
                [['member_code', 'state_code', 'union_code'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'specialCodeImportCsv']],
                [['dcs_code', 'ex_member_code', 'member_name'], 'required', 'on' => ['ApprovalMember']],
                [['member_name'], 'required', 'except' => ['customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'specialCodeImportCsv']],
                [['branch_code', 'bank_account_no', 'ifsc'], 'required', 'on' => 'bank_selected'],
            /* [['member_name'],'unique', 'when' => function($model) {
              return ($model->isNewRecord)?true:false;
              },'skipOnEmpty'=> true], */
                [['gender_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'gender', false);
                }, 'on' => 'saveCreamyData'],
                [['email'], 'email', 'except' => ['androidsync', 'specialCodeImportCsv']],
                [['member_code', 'dcs_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'land_class', 'total_land', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'address', 'pan_no', 'adhar_no', 'village_code', 'created_by', 'updated_by', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'payment_mode', 'voter_id'], 'string', 'except' => ['androidsync', 'verification', 'specialCodeImportCsv']],
                [['qualification_code', 'caste_category_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'annual_income', 'is_active', 'animal_type_code', 'bloodgroup_code', 'gender_code', 'nominee_relation'], 'integer', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                [['created_at', 'updated_at', 'federation_code', 'bank_name', 'branch_name', 'upload', 'religion_code', 'is_download', 'download_date_time', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'ex_member_code', 'ref_code', 'beneficiary_name', 'max_allowed_qty', 'response_datetime', 'rate_class', 'operation', 'is_verified', 'is_contact_verified', 'file_name', 'vendor_code', 'bank_remarks', 'contact_remarks', 'sap_farmer_code', 'latitude', 'longitude', 'aadhaar_card_address', 'is_email_verify', 'email_relation', 'member_identity_no', 'applicant_relation', 'application_no', 'emilk_sync_status', 'emilk_sync_timestamp', 'is_kyc_verified', 'reference_id', 'name_at_bank', 'city', 'branch', 'micr', 'name_match_result', 'name_match_score', 'account_status', 'account_status_code', 'utr', 'ifsc_code', 'has_available_branch_info', 'branch_address', 'beneficiary_id'], 'safe'],
                [['sap_farmer_code'], 'unique', 'targetAttribute' => ['sap_farmer_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                [['ifsc', 'pan_no'], 'trim', 'except' => ['androidsync', 'specialCodeImportCsv', 'kycVerify']],
                [['member_name', 'father_name', 'surname', 'nominee_name'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync', 'specialCodeImportCsv']],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['androidsync', 'verification', 'specialCodeImportCsv']],
                [['mobile_no', 'religion_code'], 'integer', 'except' => ['androidsync', 'specialCodeImportCsv']],
                [['pan_no'], 'unique', 'targetAttribute' => ['pan_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
                    return $this->is_active;
                }, 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                [['email'], 'unique', 'targetAttribute' => ['email', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
                    return $this->is_active;
                }, 'except' => ['androidsync', 'verification', 'specialCodeImportCsv']],
                [['adhar_no'], 'unique', 'targetAttribute' => ['adhar_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
                    return $this->is_active;
                }, 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'member_detail', 'specialCodeImportCsv']],
            /*    [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }], */
//                ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
//                    return $this->is_active;
//                }, 'except' => ['saveCreamyData', 'androidsync']],
            [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'when' => function() {
                    return (!empty($this->branch_code) || (in_array($this->scenario, ['importLimitedCsv', 'importCsv']) && !empty($this->ifsc)));
                }, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                [['local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                [['voter_id'], 'string', 'max' => 15, 'skipOnEmpty' => true, 'except' => ['androidsync', 'verification', 'specialCodeImportCsv']],
                [['payment_mode'], 'string', 'max' => 10, 'skipOnEmpty' => true, 'except' => ['androidsync', 'verification', 'specialCodeImportCsv']],
                [['dob'], function ($attribute, $params) {
                    Yii::$app->general->validateAge($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
            //   [['ex_member_code'], 'integer', 'min' => 1, 'max' => 1500, 'except' => ['androidsync']],
            //   [['ex_member_code'], 'string', 'min' => 1, 'max' => 4, 'except' => ['androidsync']],
            // [['member_code'], 'unique', 'message' => Yii::t('app', 'Ex Member Code has already been taken.'), 'except' => ['androidsync']],
            [['member_code'], 'validateCreamyData', 'on' => ['saveCreamyData', 'androidsync']],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'witness_name', 'place', 'dcs_ref_code'], 'safe'],
                [['member_code', 'federation_code', 'dcs_code', 'ex_member_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'bloodgroup_code', 'gender_code', 'qualification_code', 'caste_category_code', 'land_class', 'total_land', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'mobile_no', 'email', 'address', 'pincode', 'pan_no', 'adhar_no', 'annual_income', 'village_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'payment_mode', 'animal_type_code', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'bank_name', 'branch_name', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'nominee_relation', 'voter_id', 'religion_code', 'upload', 'download_date_time', 'is_download', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'mobile_no', 'received_timestamp', 'employee_code', 'employee_name', 'region_code', 'occupation', 'age', 'daily_milk_total', 'home_consumption_milk', 'market_surplus_milk', 'annual_milk_pour'], 'safe'],
            //  [['member_code'], 'refCodeGenerate', 'except' => ['importLimitedCsv', 'deactivate', 'saveCreamyData']],
            [['ex_member_code'], 'setExMember', 'on' => ['androidsync']],
                ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['specialCodeImportCsv']],
                [['beneficiary_name'], function ($attribute, $params) {
                    Yii::$app->general->validateBeneficiary($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync', 'verification', 'specialCodeImportCsv']],
//                [['x_col3'], 'default', 'value' => 15],
            [['dcs_code'], 'setXcol3'],
                [['member_code'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
                [['rate_class'], 'default', 'value' => '0'],
                [['vendor_code'], 'unique', 'targetAttribute' => ['vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
                    return $this->is_active;
                }, 'skipOnEmpty' => true, 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                [['is_verified', 'is_contact_verified', 'is_dcs_member'], 'default', 'value' => 0],
                [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'check_is_dcs_member' => 'is_dcs_member'], 'message' => Yii::t('app/validation', 'DCS Member has already been taken.'), 'when' => function() {
                    return $this->is_dcs_member;
                },],
                [['member_code'], 'setNullValue'],
                [['dcs_code', 'member_code', 'special_code'], 'required', 'on' => ['specialCodeImportCsv']],
                [['dcs_code', 'member_code', 'special_code'], 'setImport', 'on' => 'specialCodeImportCsv'],
                [['x_col5'], 'exist', 'skipOnError' => true, 'targetClass' => TblMemberSpecialCode::className(), 'targetAttribute' => ['x_col5' => 'special_code'], 'on' => ['specialCodeImportCsv']],
                [['ifsc', 'bank_account_no', 'bank_code', 'branch_code', 'beneficiary_name'], 'required', 'on' => 'kycVerify'],
                [['rate_class'], 'validateRateClass', 'on' => ['androidsync']],
                [['member_code'], 'refCodeValidate', 'on' => ['androidsync']],
                [['member_code'], 'resetDefaultValue']
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMember', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Society'),
            'ex_member_code' => Yii::t('app', 'Member Code Ex'),
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
            'created_at' => Yii::t('app', 'Created At'),
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
            'ref_code' => Yii::t('app', 'Code'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'max_allowed_qty' => Yii::t('app', 'Max Allowed Qty'),
            'x_col3' => Yii::t('app', 'Max Allowed Qty'),
            'is_dcs_member' => Yii::t('app', 'Is DCS Member ?'),
            'employee_code' => Yii::t('app', 'Employee Code'),
            'employee_name' => Yii::t('app', 'Employee Name'),
            'region_code' => Yii::t('app', 'Region Name'),
            'dcs_ref_code' => Yii::t('app', 'Society Ref Code'),
            'x_col5' => Yii::t('app', 'Special Code'),
        ];
    }

    public static function primaryKey() {
        return array('member_code');
    }

    /**
     * @inheritdoc
     * @return TblMemberQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMemberQuery(get_called_class());
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
        $keyPattern = !empty($this->import_key_pattern) ? $this->import_key_pattern['tbl_member'] : '';
        Yii::$app->general->setKeyPattern($this, 'tbl_member', 'ex_member_code', 3, $keyPattern);
        if (!empty($this->set_master_hierarchy)) {
            $this->set_master_hierarchy[0]->member_code = $this->dcs_code . $this->ex_member_code;
        }
        return $this->dcs_code . $this->ex_member_code;
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

        $utility_path = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplMemberUtilityPath'];
        $command = 'cd ' . $utility_path . ' && ./milkven_cmd_x86_64-linux_B -i ' . $file . ' -o ' . $path;
        //echo 'milkvendor_cmd_i386-win32_B.exe -i '.$file.' -o '.$path;
//        exec('milkvendor_cmd_i386-win32_B.exe -i ' . $file . ' -o ' . $path);
        exec('cd ' . $utility_path . ' && ./milkven_cmd_x86_64-linux_B -i ' . $file . ' -o ' . $path);
        //exit;
        return;
    }

    public function generateCsvFile($fileName, $dcs) {

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

    public function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterSave($insert, $changedAttributes) {
//        $model = new TblMemberDownload();
//        $model->dcs_code = $this->dcs_code;
//        $data = $model->getRecord();
//        if (!empty($data)) {
//            $model = $data;
//        }
//        $model->is_download = 1;
//        $model->upload_datetime = date('Y-m-d H:i:s');
//        $model->save();
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            if (in_array(strtolower($sent['type']), ['mcc', 'bmc'])) {
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
        if (!empty($this->set_master_hierarchy) && $flag == 'INSERT') {
            foreach ($this->set_master_hierarchy as $hierarchy) {
                $hierarchy->save();
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
                        ->where(['module_name' => 'TblMember']);
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

    public function validateMember($dcs_code, $memberCode) {
        return $this->find()->where(['dcs_code' => $dcs_code, 'is_active' => 1])
                        ->andWhere(['or', ['member_code' => $memberCode], ['ex_member_code' => $memberCode]])->one();
    }

    public function setXcol3($attribute, $params) {
        $max_qty_config_val = Yii::$app->general->getUnionConfiguration($this->union_code, 'max_qty_limit_member', 'PORTAL');
        $this->x_col3 = !empty($this->max_allowed_qty) ? $this->max_allowed_qty : $this->x_col3;
        if (empty($this->x_col3)) {
            $this->x_col3 = (!empty($max_qty_config_val) && ($max_qty_config_val > 0)) ? $max_qty_config_val : 15;
        }
    }

    public function getUniqueBankDetails() {
        return $this->find()->where(['or', ['bank_account_no' => $this->bank_account_no], ['bank_account_no' => \Yii::$app->general->encryptData($this->bank_account_no)]])
                        ->andWhere(['or', ['ifsc' => $this->ifsc], ['ifsc' => \Yii::$app->general->encryptData($this->ifsc)]])
                        ->andWhere(['<>', 'member_code', $this->member_code])
                        ->andWhere(['is_active' => 1])
                        ->one();
    }

    public function getTblMember() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function setKeyPattern(&$model, $table_name, $ex_code_key, $auto_code_lenght = 3) {
        $keyPattern = Yii::$app->general->getKeyPattern($table_name);
        if (!empty($keyPattern)) {
            $ref_code_length = (int) $keyPattern['ref_code_length'];
            $ref_code_fix_length = (int) $keyPattern['ref_code_fix_length'];
            $ex_code_reset_on = $keyPattern['ex_code_reset_on'];
            //EX Code Auto
            $data = $model->find()->select(['ex_code' => 'ISNULL(MAX(CAST(' . $ex_code_key . ' as int)),0)+1'])
                    ->where([$ex_code_reset_on => $model->{$keyPattern['ex_code_reset_on']}])
                    ->asArray()
                    ->one();
            $model->{$ex_code_key} = str_pad(($data['ex_code']), $keyPattern['ex_code_length'], '0', STR_PAD_LEFT);

            $data = $model->find()->select(['ref_code' => 'ISNULL(MAX(CAST(RIGHT(ref_code,' . $ref_code_length . ')as int)),0)+1', 'auto_code' => 'ISNULL(MAX(auto_code),0)+1'])
                    ->where(['union_code' => $model->union_code])
                    ->asArray()
                    ->one();
            $model->auto_code = $data['auto_code'];

            $pk_code = $model->union_code . str_pad(($data['auto_code']), $auto_code_lenght, '0', STR_PAD_LEFT);
            if ($keyPattern['ref_code_type'] == 0) {
                $model->ref_code = $pk_code;
            } else if ($keyPattern['ref_code_type'] == 1) {
                $prefix_seq = explode(',', $keyPattern['prefix_field']);
                $ref_code = ($keyPattern['ref_code_length'] > 0 ) ? str_pad($data['ref_code'], $keyPattern['ref_code_length'], '0', STR_PAD_LEFT) : '';
                $model->ref_code = '';
                foreach ($prefix_seq as $pre) {
                    $pre_info = explode(':', $pre);
                    if (isset($pre_info[1])) {
                        $t_info = explode('#', $pre_info[0]);
                        $table_name = $t_info[0];
                        $where_key = $t_info[1];
                        $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                        $append_field = $pre_info[1];
                        $query = new Query();
                        $res = $query->select($append_field)
                                        ->from($table_name)
                                        ->where([$where_key => $model->{$where_val}])->one();
                        if (!empty($res)) {
                            $model->ref_code .= $res[$append_field];
                        } else {
                            $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $model->{$where_val} . ')';
                            $model->addError('ref_code', $message);
                            return;
                        }
                    } else {
                        $model->ref_code .= $model->{$pre};
                    }
                }
                $model->ref_code .= $ref_code;
            } else if ($keyPattern['ref_code_type'] == 2) {
                $model->ref_code = $pk_code;
            }
            $model->ref_code = str_pad(($model->ref_code), $ref_code_fix_length, '0', STR_PAD_LEFT);
            if ($keyPattern['master_hierarchy_auto_entry'] == 1) {
                Yii::$app->general->setKeyPatternChild($keyPattern, $model, $table_name, $pk_code);
            }
            return $pk_code;
        } else {
            $model->addError('auto_code', Yii::t('app/validation', 'Key pattern config missing.'));
            return;
        }
    }

    public function validateRefMember($dcs_code, $memberCode) {
        return $this->find()->where(['dcs_code' => $dcs_code, 'is_active' => 1])
                        ->andWhere(['or', ['member_code' => $memberCode], ['ref_code' => $memberCode]])->one();
    }

    public function getActiveStatus() {
        return $this->hasOne(TblDcsVendorStatus::className(), ['customer_code' => 'member_code'])->andOnCondition(['customer_type' => 'Member']);
    }

    public function getActivateMemberCode($union_code, $dcs, $dateFilter) {
        $deactivateList = new TblMemberDeactive();
        $deactivatedMember = $deactivateList->getDeactiveMember($union_code, $dcs, $dateFilter);

        $value = $this->find()
                ->where(['is_active' => 1])
                ->andFilterWhere(['dcs_code' => $dcs, 'union_code' => $union_code])
                ->andWhere(['not in', 'member_code', $deactivatedMember])
                ->all();

        return ArrayHelper::map($value, 'member_code', function($value) {
                    return $value->member_name . ' - ' . $value->ref_code;
                });
    }

    public function getProvisionalMember() {
        $provisional_status = ['Inprogress', 'Register'];
        return $this->hasOne(TblMemberProvisional::className(), ['member_code' => 'member_code'])->andOnCondition(['provisional_from' => 'mobile_update', 'provisional_status' => $provisional_status]);
    }

    public function getUserName() {
        return $this->hasOne(user::className(), ['id' => 'created_by']);
    }

    public function getRegionCode() {
        return $this->hasOne(TblRegion::className(), ['region_code' => 'region_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function setNullValue() {
        foreach ($this->attributes as $key => $value) {
            if ($value == '') {
                $this->$key = null;
            }
        }
    }

    public function memberVendorData($sap_farmer_code) {
        return $this->find()->select(['union_code', 'dcs_code', 'member_code'])
                        ->where(['is_active' => 1])
                        ->andWhere(['or', ['sap_farmer_code' => $sap_farmer_code], ['vendor_code' => $sap_farmer_code]])
                        ->one();
    }

    public function resetData() {
        $this->adhar_no = $this->pan_no = $this->mobile_no = $this->bank_code = $this->branch_code = $this->bank_account_no = $this->ifsc = $this->beneficiary_name = null;
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $dcs = new TblDcs();
            $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
            if (empty($this->dcs_code)) {
                $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
                return false;
            }

            $member = $this->validateMember($this->dcs_code, $this->member_code);
            $this->member_code = $member['member_code'];
            if (empty($this->member_code)) {
                $this->addError('member_code', Yii::t('app/validation', Yii::t('app', 'Member') . ' is invalid'));
                return false;
            }
            $this->x_col5 = $this->special_code;
        }
    }

    public function validateRateClass($attribute, $params) {
        $rateMapping = ['A' => 1, 'B' => 2, 'C' => 3];
        if (!is_numeric($this->$attribute)) {
            $value = strtoupper((string) $this->$attribute);
            if (ctype_alpha($value) && isset($rateMapping[$value])) {
                $this->$attribute = $rateMapping[$value];
            } elseif (!in_array($this->$attribute, [1, 2, 3], true)) {
                $this->$attribute = 0;
            }
        }
    }

    public function refCodeValidate($attribute, $params) {
        if ($this->isNewRecord) {
            $dcs_ref_code = Yii::$app->general->getforeignkey($this->dcsCode, 'ref_code');
            $this->ref_code = $dcs_ref_code . $this->ex_member_code;
        }
        if (empty($this->animal_type_code)) {
            Yii::$app->default->getDefaults($this);
        }
        if (empty($this->caste_category_code)) {
            $this->caste_category_code = 1;
        }
        if (empty($this->gender_code)) {
            $this->gender_code = '1';
        }
        if (empty($this->member_type_code)) {
            $this->member_type_code = 1;
        }
        if (empty($this->address)) {
            $this->address = 'address';
        }
        if (empty($this->hamlet_code)) {
            $this->hamlet_code = Yii::$app->general->getforeignkey($this->dcsCode, 'hamlet_code');
        }
    }

    public function resetDefaultValue() {
        $this->data_post_status = 0;
        $this->picked_datetime = $this->response_datetime = $this->resp_desc = NULL;
    }

    public function getMasterRecord(){
        $farmers = (new Query())
            ->select([
                'companyCode' => new Expression("ISNULL(u.x_col1, '')"),
                'memberCode' => new Expression("ISNULL(m.ex_member_code, '')"),
                'sapFarmerCode' => new Expression("ISNULL(m.sap_farmer_code, '')"),
                'memberName' => new Expression("ISNULL(m.member_name, '')"),
                'lastName' => new Expression("ISNULL(m.surname, '')"),
                'gender' => new Expression("ISNULL(LEFT(g.gender, 1), '')"),
                'address' => new Expression("ISNULL(m.address, '')"),
                'bmcCode' => new Expression("ISNULL(b.ref_code, '')"),
                'mppCode' => new Expression("ISNULL(d.ref_code, '')"),
                'bankId' => new Expression("0"),
                'accountNumber' => new Expression("''"),
                'accountHolderName' => new Expression("''"),
                'ifscCode' => new Expression("''"),
                'branchName' => new Expression("''"),
                'mobileNumber' => new Expression("ISNULL(m.mobile_no, '')"),
                'effectiveDate' => new Expression("ISNULL(CONVERT(VARCHAR(10), m.registration_date, 120), '')"),
                'isActive' => new Expression("ISNULL(m.is_active, 0)"),
                'expiryDate' => new Expression("''"),
                'expiryShift' => new Expression("''"),
                'approvalDate' => new Expression("ISNULL(CONVERT(VARCHAR(10), m.registration_date, 120), '')"),
                'aadharNumber' => new Expression("ISNULL(m.adhar_no, '')"),
            ])
            ->from(['m' => 'tbl_member'])
            ->innerJoin(['u' => 'tbl_unions'], 'u.union_code = m.union_code')
            ->innerJoin(['d' => 'tbl_dcs'], 'm.dcs_code = d.dcs_code')
            ->innerJoin(['b' => 'tbl_bmc'], 'd.bmc_code = b.bmc_code')
            ->leftJoin(['g' => 'tbl_gender'], 'm.gender_code = g.gender_code')
            ->where(['isnull(m.data_post_status,0)' => [0,'']])
            ->limit(20)
            ->all();
        if (!empty($farmers)) {
            $companyCode = (string)$farmers[0]['companyCode'];
            array_walk($farmers, function(&$item) {
                $item['isActive'] = (bool)$item['isActive'];
                $item['aadharNumber'] = !empty($item['aadharNumber']) ? \Yii::$app->general->decryptData($item['aadharNumber']) : '';
                unset($item['companyCode']);
            });
            return [
                'companyCode' => $companyCode,
                'farmerImport' => $farmers
            ];
        }
        return [];

    }
    
    public function updateStatus($updateData, $ids, $dcsRefCodes) 
    {
        return $this->updateAll(
            $updateData,
            [
                'AND',
                ['in', 'ex_member_code', $ids],
                ['in', 'dcs_code', (new \yii\db\Query())
                    ->select('dcs_code')
                    ->from('tbl_dcs')
                    ->where(['in', 'ref_code', $dcsRefCodes])
                ]
            ]
        );
    }

}
