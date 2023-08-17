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
class TblDcsProvisional extends \yii\db\ActiveRecord {

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
    public $milk_type_auto,$auto_member_create,$detail_code;
//    public $department, $middle_name, $surname, $local_middlename, $local_surname, $milk_type_auto, $auto_member_create, $route, $beneficiary_name;
    public $operation, $verifie_for, $file_name,$is_default;
    public $toEncrypt = ['password', 'pan_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'aadhaar_no', 'dob'];

    
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
                [['milk_type_auto','auto_member_create','detail_code','is_default'],'safe'],
                [['dcs_code','milk_type_code','allow_multi_family_member', 'destination_type', 'is_active', 'is_bmc', 'dcs_type_code', 'mapped_village_no', 'organisation_type_code', 'scheme_type_code', 'is_registered', 'data_post_status', 'rate_flag', 'is_name_request', 'is_dispatch_mandate', 'is_weight_manual', 'is_quality_manual', 'dpu_type', 'member_rate_code', 'is_live', 'is_single_farmer', 'default_milk_type', 'credit_sale_allow', 'auto_code', 'mfile_digit', 'is_chiller', 'antibiotic_check', 'is_security_cheque', 'originating_type', 'effective_date', 'registration_date', 'valid_from', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'DPUVersionNo', 'morning_kms', 'evening_kms', 'cheque_amount', 'address', 'dcs_name'], 'safe'],
                [['bank_account_no', 'ifsc', 'mobile_no', 'pan_no', 'phone_no', 'upi_no', 'ccenter_code', 'center_code', 'vendor_code', 'sap_center_code', 'rate_chart_code', 'resp_status', 'resp_desc', 'aadhaar_no', 'ts_code_m', 'ts_code_e', 'dob', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['contact_person', 'dcs_short_name', 'beneficiary_name', 'punch_line', 'department', 'firstname', 'lastname', 'surname', 'password', 'gender', 'account_type', 'cheque_number', 'dcs_code_ex', 'route_code', 'old_route_code', 'cutoff', 'lower_milk_type', 'cutoff_val', 'destination_code', 'branch_code', 'email', 'pincode', 'village_code', 'mcc_plant_code', 'plant_code', 'old_mcc_plant_code', 'registration_code', 'service_tax', 'tin_no', 'gst_no', 'fssi'], 'safe'],
                [['bank_code', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'block_code', 'local_name', 'local_short_name', 'local_contact_person', 'logo_path', 'secretory_info', 'bank_name', 'branch_name', 'local_address', 'bmc_code', 'old_bmc_code', 'local_firstname', 'local_lastname', 'local_surname', 'ref_code', 'originating_org_code', 'originating_org_type', 'data_post_id', 'sap_vendor_code', 'voter_id', 'created_by', 'updated_by', 'ref_code'], 'safe'],
                [['union_code', 'dcs_name', 'bmc_code'], 'required', 'except' => ['deactivate', 'saveCreamyData', 'customImport', 'customImportUpdate', 'routeMapping']],
                [['dcs_short_name'], 'required', 'except' => ['deactivate', 'saveCreamyData', 'customImport', 'updateDcs', 'routeMapping', 'customImportUpdate']],
                [['union_code', 'bmc_code', 'dcs_code', 'dcs_code_ex', 'dcs_name', 'dcs_short_name', 'ref_code'], 'required', 'on' => ['customImport']],
                [['dcs_code'], 'required', 'on' => ['customImportUpdate']],
                [['milk_type_code'], 'required', 'on' => ['importCsv']],
                [['dcs_code', 'is_bmc', 'destination_type'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                [
                    ['milk_type_code'], 'required', 'when' => function ($model) {
                    return empty($model->milk_type_auto);
                },
                'whenClient' => "function (attribute, value) { return !$('#tbldcs-milk_type_auto').is(':checked') }", 'except' => ['routeMapping', 'deactivate', 'saveCreamyData', 'customImport', 'customImportUpdate']
            ],
                [['vendor'], 'required', 'except' => ['deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate', 'DcsMilkType']],
                [['vendor'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'vendor_type');
                }, 'except' => ['deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'updateDcs', 'customImportUpdate']],
                [['dpu_type'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->validateGlobalStatic($this, $attribute, $this->vendor . '_dpu_type');
                    }
                }, 'except' => ['deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate', 'DcsMilkType']],
                [['same_milk_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['diff_milk_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['department'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'department');
                }, 'on' => ['importCsv']],
                [['state_code'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'updateDcs', 'customImportUpdate']],
                [['union_code'], 'required', 'message' => Yii::t('app/validation', 'Union cannot be blank'), 'except' => ['saveCreamyData', 'routeMapping']],
                [['state_code'], 'required', 'message' => Yii::t('app/validation', 'State cannot be blank'), 'except' => ['importCsv', 'saveCreamyData', 'customImport', 'updateDcs', 'routeMapping', 'customImportUpdate']],
            //[['district_code'], 'required', 'message' => Yii::t('app/validation', 'District cannot be blank'), 'except' => ['importCsv', 'saveCreamyData', 'customImport', 'updateDcs', 'routeMapping', 'customImportUpdate']],
            // [['sub_district_code'], 'required', 'message' => Yii::t('app/validation', 'Sub District cannot be blank'), 'except' => ['importCsv', 'saveCreamyData', 'customImport', 'updateDcs', 'routeMapping', 'customImportUpdate']],
            // [['village_code'], 'required', 'message' => Yii::t('app/validation', 'Village cannot be blank'), 'except' => ['importCsv', 'saveCreamyData', 'customImport', 'updateDcs', 'routeMapping', 'customImportUpdate']],
            //[['hamlet_code'], 'required', 'message' => Yii::t('app/validation', 'Hamlet cannot be blank')],
            //            [['dcs_code', 'dcs_short_name', 'gst_no'], 'unique'],
            [['gst_no'], 'unique', 'except' => ['routeMapping']],
            // [['dcs_code'], 'IntValidateDcs', 'on' => ['customImport', 'importCsv', 'createDcs']],
            [['allow_multi_family_member', /* 'destination_type', */], 'integer', 'except' => ['routeMapping']],
                [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
            //  [['tin_no'], 'string', 'max' => 11, 'min' => 11],
            [
                    ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']
            ],
                [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['routeMapping']],
            //[['destination_code'],'bmcValidate','skipOnEmpty'=> false],
            //            [['effective_date', 'valid_from'],'validateDate'],
            [['address', 'dcs_name'], 'string', 'max' => 500],
                [['registration_code'], 'string', 'max' => 20],
                [['contact_person', 'dcs_short_name'], 'string', 'max' => 100],
            //[['dcs_code_ex'], 'string', 'max' => 6, 'min' => '3', 'except' => ['saveCreamyData']],
            [['gst_no'], 'string', 'max' => 15],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['ifsc', 'pan_no'], 'trim'],
            //[['ifsc'], 'string', 'max' => 11, 'min' => 11, 'message' => Yii::t('app/validation', 'Please enter a valid IFSC Length')],
            [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'routeMapping']],
                [['gst_no'], function ($attribute, $params) {
                    $this->validateGstNo($attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'routeMapping']],
                [['phone_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'routeMapping']],
            //            [['service_tax'], function ($attribute, $params) {
            //                    Yii::$app->general->vaildateServiceTax($this, $attribute,$params);
            //                },'skipOnEmpty'=> false],
            //            ['bank_account_no', 'unique', 'targetAttribute' => 'ifsc'],
//            ['bank_account_no', 'unique', 'when' => function ($model) {
//                $data = $this->find()->where(['ifsc' => $model->ifsc])->andWhere(['<>', 'dcs_code', $model->dcs_code])->one();
//                return ($data) ? true : false;
//            }, 'except' => ['saveCreamyData', 'routeMapping']/* , 'targetAttribute' => 'bank_code' */],
            [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'routeMapping']],
            //                [['dcs_name', 'contact_person'], function ($attribute, $params) {
            //                    Yii::$app->general->validateName($this, $attribute, $params);
            //                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData']],
            [['local_name', 'local_short_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'routeMapping']],
                [['registration_code'], function ($attribute, $params) {
                    Yii::$app->general->vaildateNumericField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['saveCreamyData', 'routeMapping']],
                [
                    ['registration_code', 'registration_date'], 'required', 'when' => function ($model) {
                    return $model->is_registered == 1;
                },
                'whenClient' => "function (attribute, value) { return $('#tbldcs-is_registered').is(':checked') }", 'except' => ['routeMapping']
            ],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'except' => ['routeMapping', 'importCsv']],
                [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'except' => ['routeMapping']],
                [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code'], 'except' => ['routeMapping']],
            //            [['branch_code','bank_account_no','ifsc'], function ($attribute, $params) {
            //                    Yii::$app->general->validateBankDetail($this, $attribute,$params);
            //                },'skipOnEmpty'=> false],
            //  [['tmcc_code'], 'string', 'max' => 10],
            //  [['tmcc_code'], 'number', 'min' => 1],
            // ['dcs_code_ex', 'unique', 'targetAttribute' => ['dcs_code_ex', 'bmc_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['saveCreamyData']],
            // [['dcs_code_ex'], 'number'],
            [['is_active'], 'default', 'value' => 1],
                [['is_dispatch_mandate', 'is_live'], 'default', 'value' => 0],
                [['is_dispatch_mandate'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_dispatch_mandate');
                }, 'skipOnEmpty' => false, 'on' => ['importCsv']],
                [['is_weight_manual', 'is_quality_manual', 'credit_sale_allow', 'is_chiller'], 'boolean'],
                [['dpu_type', 'is_dispatch_mandate'], 'required', 'on' => ['createDcs', 'updateDcs', 'customImportUpdate', 'routeMapping']],
                [['x_col1'], 'default', 'value' => '1#1'],
                [['dcs_code'], function ($attribute, $params) {
                    Yii::$app->general->validateOnUnionConfig($this, 'rate_chart_member', 'dcs_create_with_member_rate', 1);
                }, 'skipOnEmpty' => false, 'on' => ['importCsv', 'createDcs']],
                [['dcs_code'], 'importData', 'skipOnError' => true, 'on' => ['importCsv', 'createDcs']],
                [['milk_type_code'], 'integer', 'on' => ['importCsv']],
                [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'default_milk_type');
                }, 'on' => 'importCsv'],
            //            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'on' => ['importCsv']],
            [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id'], 'on' => ['importCsv']],
                [['local_contact_person', 'local_middlename', 'local_surname'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['importCsv', 'routeMapping']],
                [['ifsc'], 'setBankDetail', 'on' => ['importCsv']],
                [['dcs_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'dcs_type_code');
                }, 'on' => 'importCsv'],
                [['dcs_type_code'], 'integer'],
                [['dcs_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsTypes::className(), 'targetAttribute' => ['dcs_type_code' => 'dcs_type_code'], 'on' => ['importCsv']],
                ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['credit_sale_allow', 'is_chiller'], 'default', 'value' => 0],
                [['dcs_code'], function ($attribute, $params) {
                    ($this->vendor == 'BIPL') ? Yii::$app->general->generateFTPDir($this, $attribute, $params, $this->mcc_plant_code, $this->ref_code) : '';
                }, 'skipOnEmpty' => false, 'on' => ['createDcs', 'importCsv']],
                [['dcs_code'], function ($attribute, $params) {
                    ($this->vendor == 'BIPL' && $this->oldAttributes['ref_code'] != $this->ref_code) ? Yii::$app->general->generateFTPDir($this, $attribute, $params, $this->mcc_plant_code, $this->ref_code) : '';
                }, 'skipOnEmpty' => false, 'on' => ['updateDcs']],
                [['dcs_code'], function ($attribute, $params) {
                    Yii::$app->general->vaildateKeyCodes($this, 'tbl_dcs', 'dcs_code_ex', 'dcs_code');
                }, 'skipOnEmpty' => false, 'on' => ['updateDcs', 'importCsv']],
                [['bmc_code'], 'setXcol', 'on' => ['importCsv']],
                [['default_milk_type'], 'default', 'value' => 8],
                [['dcs_code'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
                [['route'], 'required', 'on' => ['importCsv']],
                [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
                [['dcs_code'], 'validateRoute', 'on' => ['importCsv']],
                [['dcs_code'], function ($attribute, $params) {
                    $update = FALSE;
                    if ($this->scenario == 'updateDcs') {
                        $update = TRUE;
                    }
                    Yii::$app->general->validateExCodes($this, 'tbl_dcs', 'dcs_code_ex', 'tbl_customer_master', 'customer_code_ex', 'TblCustomerMaster', $this->union_code, $update);
                }, 'skipOnEmpty' => false, 'on' => ['updateDcs', 'importCsv', 'createDcs']],
                [['aadhaar_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAadharcard($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                [['aadhaar_no'], 'unique', 'skipOnError' => TRUE, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                [['password'], 'string', 'min' => 8, 'max' => 8],
                [['antibiotic_check'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['ts_code_m', 'ts_code_e'], 'string', 'max' => 10],
                [['ts_code_m', 'ts_code_e'], 'number'],
                [['is_bmc'], 'unique', 'targetAttribute' => ['is_bmc', 'bmc_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                    return $model->is_bmc;
                }, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                [['cutoff', 'morning_kms', 'evening_kms'], 'default', 'value' => 0],
                [['cutoff_val'], 'default', 'value' => 0.1],
                [['cutoff_val'], 'number', 'min' => 0.1, 'max' => 99.9, 'skipOnEmpty' => true, 'except' => ['routeMapping', 'deactivate', 'saveCreamyData', 'customImport', 'customImportUpdate', 'importCsv']],
                [['lower_milk_type', 'cutoff_val'], 'required', 'when' => function ($model) {
                    return $model->cutoff == 1;
                },
                'whenClient' => "function (attribute, value) { return $('#tbldcs-cutoff').is(':checked') }", 'except' => ['routeMapping', 'deactivate', 'saveCreamyData', 'customImport', 'customImportUpdate', 'importCsv']
            ],
                [['cutoff_val'], function ($attribute, $params) {
                    if (!empty($this->cutoff) && $this->cutoff != '0000') {
                        Yii::$app->general->validOneDigitDecimal($this, $attribute, $params);
                    }
                }, 'except' => ['routeMapping', 'deactivate', 'saveCreamyData', 'customImport', 'customImportUpdate', 'importCsv']],
        ];
//        $client_rules = Yii::$app->customvalidation->getRules('TblDcs', $this->form_validation_type);
        $client_rules = [];
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
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
            'route_code' => Yii::t('app', 'Route Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'village_code' => Yii::t('app', 'Village Code'),
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
            'fssi' => Yii::t('app', 'Fssi'),
            'organisation_type_code' => Yii::t('app', 'Organisation Type Code'),
            'scheme_type_code' => Yii::t('app', 'Scheme Type Code'),
            'is_registered' => Yii::t('app', 'Is Registered'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
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
            'is_security_cheque' => Yii::t('app', 'Is Security Cheque'),
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
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
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

}
