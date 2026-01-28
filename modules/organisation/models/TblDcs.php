<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\geo\models\TblStates;
use app\modules\organisation\models\TblRoutes;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblUnions;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\globalmaster\models\TblDcsTypes;
use app\modules\general\models\TblOrganisationType;
use app\modules\general\models\TblSchemeType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcsMilkType;
use app\modules\geo\models\TblBlocks;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\general\models\TblSocietyVendor;
use app\models\ChildModel;
use app\models\TblKeyPatternChild;
use yii\db\Query;
use app\modules\organisation\models\TblDpuInstallation;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\organisation\models\TblSocietyCollection;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\syncutility\models\TblSentbox;
use app\modules\general\models\TblDpuIncentiveMaster;
use app\modules\general\models\TblCollectionIncentiveDeduction;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\general\models\TblDepartment;
use app\modules\organisation\models\TblRouteMappingSources;
use app\modules\organisation\models\TblDcsDeactive;
use app\modules\organisation\models\TblDcsVendorStatus;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\product\models\TblProductSaleRate;
use yii\base\UserException;
use yii\helpers\Html;
use app\modules\organisation\models\TblMasterHierarchy;
use yii\db\Expression;

//use app\modules\payment\models\TblDcsPaymentCycleApplicability;
//use app\modules\vsp\models\TblBillHeadApplicability;
//use app\modules\vsp\models\TblBillHeadDetail;

/**
 * This is the model class for table "tbl_dcs".
 *
 * @property string $dcs_code
 * @property string $address
 * @property string $local_address
 * @property integer $allow_multi_family_member
 * @property string $bank_account_no
 * @property string $contact_person
 * @property string $local_contact_person
 * @property string $created_at
 * @property string $dcs_code_ex
 * @property string $dcs_name
 * @property string $local_name
 * @property string $dcs_short_name
 * @property string $local_short_name
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
 * @property string $updated_at
 * @property string $bank_code
 * @property string $branch_code
 * @property string $created_by
 * @property integer $dcs_type_code
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $route_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $upi_no
 * @property string $secretory_info
 * @property string $gst_no
 * @property string $fssi
 * @property string $is_name_request
 * @property string $rate_flag
 */
class TblDcs extends ChildModel {

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
    public $department, $middle_name, $surname, $local_middlename, $local_surname, $milk_type_auto, $auto_member_create, $route, $beneficiary_name;
    public $operation, $verifie_for, $file_name, $employee_id;
    public $toEncrypt = ['password', 'pan_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'aadhaar_no', 'dob'];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
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
            [['gst_no'], 'unique', 'except' => ['routeMapping'], 'when' => function ($model) {
                    return $model->isAttributeChanged('gst_no', FALSE);
                }],
            // [['dcs_code'], 'IntValidateDcs', 'on' => ['customImport', 'importCsv', 'createDcs']],
            [['allow_multi_family_member', /* 'destination_type', */], 'integer', 'except' => ['routeMapping']],
            //  [['tin_no'], 'string', 'max' => 11, 'min' => 11],
            [['vendor_code', 'is_active', 'created_at', 'milk_type_code', 'destination_code', 'destination_type', 'effective_date', 'registration_date', 'updated_at', 'villages', 'branch_code', 'route_code', 'federation_code', 'upi_no', 'hamlet_code', 'secretory_info', 'gst_no', 'fssi', 'organisation_type_code', 'scheme_type_code', 'is_registered', 'street1', 'street2', 'valid_from', 'bipl_code', 'vendor', 'data_post_status', 'bmc_code', 'mcc_plant_code', 'plant_code', 'is_name_request', 'rate_flag', 'dpu_type', 'rate_chart_member', 'is_live', 'dcs_code_ex', 'ref_code', 'credit_sale_allow', 'default_milk_type', 'milk_type_auto', 'auto_member_create', 'beneficiary_name', 'operation', 'file_name', 'aadhaar_no', 'sap_vendor_code', 'antibiotic_check', 'ts_code_m', 'ts_code_e', 'cutoff', 'lower_milk_type', 'cutoff_val', 'employee_id', 'fssi_expiry_date', 'type_of_dcs'], 'safe'],
                [['fssi_expiry_date'], 'required', 'when' => function ($model) {
                    return !empty($model->fssi);
                }, 'whenClient' => "function (attribute, value) {return $('#tbldcs-fssi').val() !== '';
            }"],
                [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['routeMapping'], 'when' => function ($model) {
                    return $model->isAttributeChanged('sap_vendor_code', FALSE);
                }],
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
            //    [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'except' => ['routeMapping', 'importCsv']],
            //     [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'except' => ['routeMapping']],
            //     [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code'], 'except' => ['routeMapping']],
            //            [['branch_code','bank_account_no','ifsc'], function ($attribute, $params) {
            //                    Yii::$app->general->validateBankDetail($this, $attribute,$params);
            //                },'skipOnEmpty'=> false],
            //  [['tmcc_code'], 'string', 'max' => 10],
            //  [['tmcc_code'], 'number', 'min' => 1],
            // ['dcs_code_ex', 'unique', 'targetAttribute' => ['dcs_code_ex', 'bmc_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['saveCreamyData']],
            // [['dcs_code_ex'], 'number'],
            [['is_active'], 'default', 'value' => 1],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_dispatch_mandate', 'is_weight_manual', 'is_quality_manual', 'same_milk_type', 'diff_milk_type', 'with_member_rate', 'ref_code', 'is_chiller', 'machine_owned'], 'safe'],
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
                [['department', 'middle_name', 'surname', 'local_middlename', 'local_surname', 'bank_code', 'origination_type'], 'safe'],
                [['milk_type_code'], 'integer', 'on' => ['importCsv']],
                [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'default_milk_type');
                }, 'on' => 'importCsv'],
            //            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'on' => ['importCsv']],
            // [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id'], 'on' => ['importCsv']],
            [['local_contact_person', 'local_middlename', 'local_surname'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['routeMapping']],
                [['ifsc'], 'setBankDetail', 'on' => ['importCsv']],
                [['dcs_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'dcs_type_code');
                }, 'on' => 'importCsv', 'when' => function ($model) {
                    return $model->isAttributeChanged('dcs_type_code', FALSE);
                }],
                [['dcs_type_code'], 'integer'],
            //   [['dcs_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsTypes::className(), 'targetAttribute' => ['dcs_type_code' => 'dcs_type_code'], 'on' => ['importCsv']],
            ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                    return $model->isAttributeChanged('ref_code', FALSE);
                }],
                [['credit_sale_allow', 'is_chiller'], 'default', 'value' => 0],
                [['district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'password'], 'safe'],
                [['cheque_number', 'cheque_amount', 'is_security_cheque', 'emilk_sync_status', 'emilk_sync_timestamp', 'cheque_bank', 'security_return_date', 'security_return_amt', 'security_return_mode'], 'safe'],
                [['dcs_code'], function ($attribute, $params) {
                    ($this->vendor == 'BIPL' && $this->dpu_type == '91') ? Yii::$app->general->generateFTPDir($this, $attribute, $params, $this->mcc_plant_code, $this->ref_code) : '';
                }, 'skipOnEmpty' => false, 'on' => ['createDcs', 'importCsv'], 'when' => function ($model) {
                    return $model->isAttributeChanged('ref_code', FALSE);
                }],
                [['dcs_code'], function ($attribute, $params) {
                    ($this->vendor == 'BIPL' && $this->dpu_type == '91' && $this->oldAttributes['ref_code'] != $this->ref_code) ? Yii::$app->general->generateFTPDir($this, $attribute, $params, $this->mcc_plant_code, $this->ref_code) : '';
                }, 'skipOnEmpty' => false, 'on' => ['updateDcs']],
                [['dcs_code'], function ($attribute, $params) {
                    Yii::$app->general->vaildateKeyCodes($this, 'tbl_dcs', 'dcs_code_ex', 'dcs_code');
                }, 'skipOnEmpty' => false, 'on' => ['updateDcs', 'importCsv'], 'when' => function ($model) {
                    return ($model->isAttributeChanged('ref_code', FALSE) || $model->isAttributeChanged('dcs_code_ex', FALSE));
                }],
                [['bmc_code'], 'setXcol', 'on' => ['importCsv']],
                [['default_milk_type'], 'default', 'value' => 8],
                [['data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime'], 'safe'],
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
                }, 'skipOnEmpty' => false, 'on' => ['updateDcs', 'importCsv', 'createDcs'], 'when' => function ($model) {
                    return $model->isAttributeChanged('dcs_code_ex', FALSE);
                }],
                [['aadhaar_no'], 'unique', 'skipOnError' => TRUE, 'on' => ['createDcs', 'updateDcs', 'importCsv'], 'when' => function ($model) {
                    return $model->isAttributeChanged('aadhaar_no', FALSE);
                }],
                [['password'], 'string', 'min' => 8, 'max' => 8],
                [['antibiotic_check'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['machine_owned'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'machine_owned_type');
                }, 'on' => ['importCsv']],
                [['ts_code_m', 'ts_code_e'], 'string', 'max' => 10],
                [['ts_code_m', 'ts_code_e'], 'number'],
                [['is_bmc'], 'unique', 'targetAttribute' => ['is_bmc', 'bmc_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                    return $model->is_bmc && $model->isAttributeChanged('is_bmc', FALSE);
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
                [['is_security_cheque'], 'default', 'value' => 0],
                [['dcs_code'], 'resetDefaultValue']
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblDcs', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    public function validateGstNo($attribute, $params) {

        if (!empty($this->gst_no))
            if (strlen($this->gst_no) != 15) {
                $this->addError($attribute, Yii::t('app/validation', 'Gst no must contain 15 characters'));
            }
        return false;
    }

    public function bmcValidate($attribute, $params) {
        if ($this->is_bmc == 0 || $this->is_bmc == 1 || $this->is_bmc == 2) {
            if (empty($this->destination_code)) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' can not be blank.'));
                return false;
            }
        }
    }

    public function validateDate($attribute, $params) {

        if (!empty($this->effective_date) && !empty($this->valid_from)) {

            if ($this->valid_from < $this->effective_date) {
                $this->addError($attribute, Yii::t('app/validation', 'Valid From Must be Greater Than Start Date.'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_code' => Yii::t('app', 'Society Code'),
            'villages' => Yii::t('app', 'Applicable Villages'),
            //            'address' => Yii::t('app', 'Address'),
            'street1' => Yii::t('app', 'Address Street1'),
            'street2' => Yii::t('app', 'Address Street2'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'allow_multi_family_member' => Yii::t('app', 'Allow Multi Family Member'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'local_contact_person' => Yii::t('app', 'Contact Person Hindi Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'dcs_code_ex' => Yii::t('app', 'Society Code Ex'),
            'dcs_name' => Yii::t('app', 'Society Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'dcs_short_name' => Yii::t('app', 'Society Short Name'),
            'local_short_name' => Yii::t('app', 'Hindi Short Name'),
            'destination_code' => Yii::t('app', 'Destination'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'effective_date' => Yii::t('app', 'Start Date'),
            'email' => Yii::t('app', 'Email'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_bmc' => Yii::t('app', 'Is BMC'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_code' => Yii::t('app', 'Registration Code'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'service_tax' => Yii::t('app', 'Service Tax'),
            'tin_no' => Yii::t('app', 'Tin No'),
            'updated_at' => Yii::t('app', 'Download At'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_type_code' => Yii::t('app', 'Society Type'),
            'district_code' => Yii::t('app', 'District'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'route_code' => Yii::t('app', 'Route'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village'),
            'block_code' => Yii::t('app', 'Block'),
            'upi_no' => Yii::t('app', 'UPI No'),
            'secretory_info' => Yii::t('app', 'Secretary Info'),
            'gst_no' => Yii::t('app', 'GST No'),
            'fssi' => Yii::t('app', 'FSSAI'),
            'fssi_expiry_date' => Yii::t('app', 'FSSAI Expiry Date'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'bipl_code' => Yii::t('app', 'BIPL Code'),
            'society_status' => Yii::t('app', 'Collection Status'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'tmcc_code' => Yii::t('app', 'DCS Code'),
            'download_status' => Yii::t('app', 'Member Download Status'),
            'is_name_request' => Yii::t('app', 'Member Download Status'),
            'rate_flag' => Yii::t('app', 'Rate Download Status'),
            'is_dispatch_mandate' => Yii::t('app', 'Type of Dispatch'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'dpu_type' => Yii::t('app', 'DPU Type'),
            'diff_milk_type' => Yii::t('app', 'Different Milk Type'),
            'f_plant_code' => Yii::t('app', 'Plant'),
            'f_mcc_code' => Yii::t('app', 'MCC'),
            'f_bmc_code' => Yii::t('app', 'BMC'),
            'f_union_code' => Yii::t('app', 'Union'),
            'ref_code' => Yii::t('app', 'Code'),
            'x_col2' => Yii::t('app', 'Collection'),
            'auto_member_create' => Yii::t('app', 'Auto Member Create'),
            'rate_chart_member' => Yii::t('app', 'Rate Chart Member'),
            'morning_kms' => Yii::t('app', 'Head Load KM(M)'),
            'evening_kms' => Yii::t('app', 'Head Load KM(E)'),
            'machine_owned' => Yii::t('app', 'Machine Owned Type'),
            'employee_id' => Yii::t('app', 'Employee Id'),
            'is_security_cheque' => Yii::t('app', 'Is Security Cheque?'),
            'cheque_number' => Yii::t('app', 'Cheque Number'),
            'cheque_amount' => Yii::t('app', 'Cheque Amount'),
            'cheque_bank' => Yii::t('app', 'Cheque Bank'),
            'security_return_date' => Yii::t('app', 'Security Return Date'),
            'security_return_amt' => Yii::t('app', 'Security Return Amount'),
            'security_return_mode' => Yii::t('app', 'Security Return Mode'),
            'type_of_dcs' => Yii::t('app', 'Type Of DCS'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsQuery(get_called_class());
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_dcs', 'dcs_code_ex', 9);
    }

    public function getActiveDcs() {
        //echo $fedrCode;
        $value = $this->find()->where(['is_active' => 1])->all();
        return ArrayHelper::map($value, 'dcs_code', 'dcs_name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    public function getBankDetails() {
        return $this->hasMany(TblBankDetails::className(), ['module_code' => 'dcs_code'])->where(['tbl_bank_details.module_name' => 'society']);
    }

    public function getDefaultBankDetail() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'dcs_code'])->where(['tbl_bank_details.module_name' => 'society', 'tbl_bank_details.is_default' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code'])->via('defaultBankDetail');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code'])->via('defaultBankDetail');
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

    public function getBlockCode() {
        return $this->hasOne(TblBlocks::className(), ['block_code' => 'block_code']);
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
    public function getRouteCode() {
        return $this->hasOne(TblRoutes::className(), ['route_code' => 'route_code']);
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
    public function getMilkQualityTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
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

    public function getSchemeTypeCode() {
        return $this->hasOne(TblSchemeType::className(), ['scheme_type_code' => 'scheme_type_code']);
    }

    public function getTblDcsVillageMapping() {
        return $this->hasMany(TblDcsVillageMapping::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 1]);
    }

    public function getTblDcsMilkType() {
        return $this->hasMany(TblDcsMilkType::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 1]);
    }

    public function getTblDcsBmc() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code'])->andwhere(['is_active' => 1]);
    }

    public function getSocietyCodes() {
        return $this->hasOne(TblSocietyCodes::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getSocietyVendors() {
        return $this->hasOne(TblSocietyVendor::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getDcsForBmc($union) {
        $value = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_bmc' => '2', 'union_code' => $union])->all();
        $value = ArrayHelper::map($value, 'dcs_code', 'dcs_name');
        return $value;
    }

    public function getVillage() {
        $village = new TblVillages();
        $village_list = $village->getDcsVillageList($this->union_code);
        $values = TblDcsVillageMapping::find()->select('village_code')->where(['dcs_code' => $this->dcs_code])->asArray()->all();
        $selected = [];
        foreach ($village_list as $key => $row) {
            if (array_search($key, array_column($values, 'village_code')) !== FALSE) {
                //$selected[$key] = ['selected' => 'selected'];
                $selected[$key] = $row;
            }
        }
        return ['value' => $selected, 'selected' => ''];
        //return ['value' => $village_list, 'selected' => $selected];
    }

    public function getVillageList() {
        $out = '';
        foreach ($this->tblDcsVillageMapping as $row) {
            $out .= $row->villageCode->village_name . '<br>';
        }
        return $out;
    }

    public function isBmcValue() {

        switch ($this->is_bmc) {
            case 0;
                return 'No BMC';
                break;
            case 1;
                return 'Non Cluster BMC';
                break;
            case 2;
                return 'Cluster BMC';
                break;
            case 3;
                return 'Pours to BMC';
                break;
        }
    }

    public function destinationTypeValue() {

        if ($this->is_bmc == 3) {
            $dcs = $this->getDcsName($this->destination_code);
            $value = isset($dcs) ? $dcs->dcs_name : '';
        } else {
            $chilling = new TblMccPlant();
            $value = $chilling->getChillingCenterValue($this->destination_code);
            $value = isset($value) ? $value->name : '';
        }

        return $value;
    }

    public function getDcsName($value) {
        return $this->find()->select(['dcs_name', 'union_code'])->where(['dcs_code' => $value])->one();
    }

    public function getDcsList($unionCode, $dcsCode = '') {
        if ($unionCode === '')
            $unionCode = 0;
        $value = $this->getDcs($unionCode, $dcsCode);
        $value = ArrayHelper::map($value, 'dcs_code', 'dcs_name');
        return $value;
    }

    public function getDcs($unionCode, $dcsCode = '') {
        if (!empty($dcsCode)) {
            $unionQuery = $this->find()->select(['dcs_code', 'dcs_name'])->where(['dcs_code' => $dcsCode]);

            $query = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1])->union($unionQuery);
        } else {
            $query = $this->find()->select(['dcs_code', 'dcs_name'])->where(['is_active' => 1]);
        }

        if ($unionCode !== '')
            $query->andWhere(['union_code' => explode(',', $unionCode)]);
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        return $query->all();
    }

    public function getRouteDcs($route) {

        return $routeSocieties = TblDcs::find()->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name', 'tbl_dcs.ref_code'])->where(['tbl_dcs.route_code' => $route, 'tbl_dcs.is_active' => 1])->asArray()->all();


        //return $routeSocieties = TblSocietyCodes::find()->select(['dcs_code','dcs_name'])->joinWith('dcsCode')->where(['tbl_society_codes.route_code' => $route,'tbl_dcs.is_active'=>1])->indexBy('code')->all();
        ///$routeSocieties = TblSocietyCodes::find()->select('dcs_code,dcs_name')->where(['route_code'=>$route,'is_active'=>1])->all();
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

    public function milkType() {
        $out = '';
        foreach ($this->tblDcsMilkType as $row) {
            $out .= $row->milkTypeCode->animal_type_name . '<br>';
        }
        return $out;
    }

    public function getCheckDcsExist() {

        $unions = $this->find()->select('d.district_code')->where(['tbl_dcs.union_code' => $this->union_code, 'tbl_dcs.is_active' => 1])
                        ->join('INNER JOIN', 'tbl_dcs_village dv', 'dv.dcs_code=tbl_dcs.dcs_code')
                        ->join('INNER JOIN', 'tbl_villages dd', 'dd.village_code=dv.village_code')
                        ->join('INNER JOIN', 'tbl_sub_districts sd', 'dd.sub_district_code=sd.sub_district_code')
                        ->join('INNER JOIN', 'tbl_districts d', 'd.district_code=sd.district_code')
                        ->groupBy('d.district_code')->asArray()->all();

        $states = array_column($unions, 'district_code');
        return $states;
    }

    public function loadDcs($union_code = '', $q = '') {
        $records = (new Query())
                        ->select('dcs_code as id, dcs_name as text')
                        ->from('tbl_dcs')
                        ->where(['union_code' => $union_code])
                        ->andWhere(['like', 'dcs_name', $q])->createCommand()->rawSql;
        echo $records;
        exit;
        return $records;
    }

    public function fullAddress() {
        return $this->street1 . ', ' . $this->street2;
    }

    public function getInstallationId() {
        $query = TblDpuInstallation::find()->select(['inst_code'])->where(['dcs_code' => $this->dcs_code])->one();
        if (!empty($query)) {
            return $query->inst_code;
        }
        return false;
    }

    public function getRouteDcsList($routeCode, $dcsCode = '') {
        if ($routeCode === '')
            $routeCode = '0';
        $value = $this->getRouteDcs($routeCode);
        $value = ArrayHelper::map($value, 'dcs_code', function ($value) {
                    return $value['dcs_name'] . ' - ' . $value['ref_code'];
                });
        asort($value, SORT_NATURAL | SORT_FLAG_CASE);
        return $value;
    }

    public function getDefaultContactDetail() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'dcs_code'])->where(['tbl_contact_details.module_name' => 'society', 'tbl_contact_details.is_default' => 1]);
    }

    public function validDcs($dcs, $bmc) {
        $data = $this->find()->select('dcs_code')->where(['bmc_code' => $bmc, 'is_active' => 1])->andWhere(['or', ['dcs_code' => $dcs], ['dcs_code_ex' => $dcs], ['ref_code' => $dcs]])->all();
        return !empty($data) && count($data) == 1 ? $data[0]->dcs_code : '';
    }

    public function getSocietyStatus() {
        return $this->hasOne(TblSocietyCollection::className(), ['dcs_code' => 'dcs_code'])->orderBy('collection_id desc');
    }

    public function getNewDcs() {
        return $this->find()
                        ->joinWith(['societyVendors'])
                        ->where(['or', ['data_post_status' => [0, 3]], ['data_post_status' => NULL]])
                        ->andWhere(['tbl_society_vendor.vendor_code' => 'STELLAPPS'])
                        ->limit(200)
                        ->orderby('created_at ASC')
                        ->all();
    }

    public function updateDcs($value) {
        return $this->updateAll(['data_post_status' => 1], ['dcs_code' => $value]);
    }

    public function rlsBmcDcs($parents = '') {
        $rows = $this->find()->where(['bmc_code' => $parents])->all();
        $bmc = [];
        foreach ($rows as $value) {
            $bmc[] = array('id' => $value->dcs_code, 'name' => $value->dcs_name);
        }
        return $bmc;
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBMCDCSList($plantCode, $RLS = 'TRUE', $type = '', $dateFilter = '', $route = '') {
        $value = $this->getBMCDCS($plantCode, $RLS, $dateFilter, $route);
        $value = ArrayHelper::map($value, 'dcs_code', function ($value) use ($type) {
                    return !empty($type) ? $value->dcs_name . '(' . Yii::t('app', $type) . ') - ' . $value->ref_code : $value->dcs_name . ' - ' . $value->ref_code;
                });
        return $value;
    }

    public function getBMCDCS($plantCode = [], $RLS = 'TRUE', $dateFilter = NULL, $route = '') {
        $query = $this->find()->select(['dcs_code', 'dcs_name', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($plantCode))
            $query->andWhere(['bmc_code' => $plantCode]);
        if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (!empty($route)) {
            $query->andWhere(['route_code' => $route]);
        }
        if (!empty($dateFilter)) {
            $dcsdeactivate = new TblDcsDeactive();
            $deactivatedDCS = $dcsdeactivate->getDcsDEactivated($plantCode, $dateFilter);
            $query->andWhere(['not in', 'dcs_code', $deactivatedDCS]);
        }

        return $query->all();
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getTblPurchaseRateApplicabilityUnblock() {
        return $this->hasMany(TblPurchaseRateApplicability::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 1]);
    }

    public function getTblPurchaseRateApplicabilityBlock() {
        return $this->hasMany(TblPurchaseRateApplicability::className(), ['dcs_code' => 'dcs_code'])->andwhere(['is_active' => 0]);
    }

    public function getBmcDcsData() {
        $rows = $this->find()->where(['bmc_code' => $this->bmc_code])->all();
        $dcs = [];
        foreach ($rows as $key => $value) {
            $dcs[] = $value->dcs_code;
        }
        return $dcs;
    }

    public function getDownloadStatus() {
        $member_model = new TblMember();
        $data = $member_model->find()
                ->where(['dcs_code' => $this->dcs_code, 'is_download' => '1'])
                ->all();
        if (count($data) == 0) {
            return Yii::t('app', 'Downloaded');
        } else {
            return Yii::t('app', 'Pending');
        }
    }

    public function getSocietyData() {
        return $this->find()
                        ->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name', 'tbl_contact_details.mobile_no'])
                        ->joinWith(['defaultContactDetail'])
                        ->where(['bmc_code' => $this->bmc_code, 'tbl_dcs.is_active' => 1])
                        ->all();
    }

    public function getRouteMapping() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getMccDCS($mccCode = [], $RLS = 'TRUE', $values = []) {
        $query = $this->find()->select(['dcs_code', 'dcs_name', 'dcs_code_ex', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($mccCode))
            $query->andWhere(['mcc_plant_code' => $mccCode]);
        if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (!empty($values)) {
            $query->andWhere(['not in', 'dcs_code', $values]);
        }
        return $query->all();
    }

    public function getData($ref_code_check = FALSE) {
        if ($ref_code_check) {
            $data = $this->find()
                    ->where(['or', ['dcs_code' => $this->dcs_code], ['ref_code' => $this->dcs_code]])
                    ->andWhere(['is_active' => 1])
                    ->all();
            $data = (count($data) == 1) ? $data : [];
        } else {
            $data = $this->find()
                    ->where(['dcs_code' => $this->dcs_code])
                    ->one();
        }
        return $data;
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
        if (!empty($this->set_master_hierarchy) && $flag == 'INSERT') {
            foreach ($this->set_master_hierarchy as $hierarchy) {
                $hierarchy->save();
            }
        }
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function setModel() {
        $this->dcs_name = ucwords($this->dcs_name);
        $this->pan_no = strtoupper($this->pan_no);
        $this->dcs_short_name = ucwords($this->dcs_short_name);
        $this->route_code = empty($this->route_code) ? null : $this->route_code;
        //        $this->registration_date = ($this->registration_date == '') ? null : Yii::$app->formatter->asDate($this->registration_date, 'php:Y-m-d');
        //        $this->effective_date = ($this->effective_date == '') ? null : Yii::$app->formatter->asDate($this->effective_date, 'php:Y-m-d');
    }

    public function getDpuIncentiveMaster() {
        return $this->hasOne(TblDpuIncentiveMaster::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getDefaultMobileNo() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'dcs_code'])->andOnCondition(['tbl_contact_details.module_name' => 'society', 'tbl_contact_details.is_default' => 1, 'tbl_contact_details.is_active' => 1]);
    }

    public function getCollectionIncentive() {
        $date = date('Y-m-d');
        return $this->hasMany(TblCollectionIncentiveDeduction::className(), ['dcs_code' => 'dcs_code'])
                        ->andwhere("'$date' BETWEEN [from_date] AND [to_date]")->orderBy('from_time ASC');
    }

    public function getUnionDcs($unionCode, $notIn = [], $compareBmc = false, $mcc = [], $bmc = [], $concatField = 'dcs_code', $routes = []) {
        $query = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1]);
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['not in', 'dcs_code', $notIn]);
        }
        if ($compareBmc) {
            $query->andWhere(['bmc_code' => $bmc, 'mcc_plant_code' => $mcc]);
        }

        if (!empty($routes)) {
            $query->andWhere(['route_code' => $routes]);
        }
        $dcs = $query->all();
        $dcs = ArrayHelper::map($dcs, 'dcs_code', function ($dcs) use ($concatField) {
                    return $dcs->{$concatField} . '-' . $dcs->dcs_name;
                });
        asort($dcs, SORT_NATURAL | SORT_FLAG_CASE);
        return $dcs;
    }

    public function getUnionDpuConfig() {
        return $this->hasOne(TblUnionDpuConfig::className(), ['union_code' => 'union_code', 'dpu_type' => 'dpu_type']);
    }

    public function getPurchaseRate() {
        return $this->hasOne(TblPurchaseRate::className(), ['union_code' => 'union_code', 'purchase_rate_code' => 'rate_chart_member']);
    }

    public function importData($attribute, $params) {
        if (!empty($this->rate_chart_member) && empty($this->purchaseRate)) {
            $this->addError('rate_chart_member', Yii::t('app/validation', $this->getAttributeLabel('rate_chart_member') . ' is Invalid.'));
            return false;
        }
    }

    public function setModelData($model, &$saveModel) {
        $society_model = new TblSocietyCollection();
        $society_model->dcs_code = $model->dcs_code;
        $society_model->from_date = date('Y-m-d H:i:s');
        $society_model->status = 1;
        $society_model->remarks = NULL;
        array_push($saveModel, $society_model);

        $member_rate_model = new TblPurchaseRate();
        $member_rate_data = $member_rate_model->getRecord($model->rate_chart_member);
        if (!empty($member_rate_data)) {
            $member_applicability = new TblPurchaseRateApplicability();
            $member_applicability->purchase_rate_code = $member_rate_data->purchase_rate_code;
            $member_applicability->wef_date = $member_rate_data->wef_date;
            $member_applicability->shift_code = $member_rate_data->shift_id;
            $member_applicability->union_code = $model->union_code;
            $member_applicability->dcs_code = $model->dcs_code;
            array_push($saveModel, $member_applicability);
        }
        $incentive_model = new TblDpuIncentiveMaster();
        $incentive_model->dcs_code = $model->dcs_code;
        $incentive_model->m_cutoff_time = '12:30';
        $incentive_model->e_cutoff_time = '22:30';
        $incentive_model->m_start_time = '01:00';
        $incentive_model->e_start_time = '14:00';
        $incentive_model->m_lock_time = '13:55';
        $incentive_model->e_lock_time = '23:55';
        $incentive_model->inc_rate = 0.0;
        $incentive_model->inc_deduction = 0.0;
        $incentive_model->union_code = $model->union_code;
        $incentive_model->from_date = date('Y-m-d');
        $incentive_model->to_date = date('Y-m-d');
        array_push($saveModel, $incentive_model);
    }

    //    public function headWiseDcs($payment_cycle_code, $bill_head_code) {
    //        $value1 = $this->find()->select(['tbl_dcs.dcs_code', 'dcs_name'])
    //                        ->innerJoinWith(['paymentCycleApplicability', 'billHeadApplicability'])
    //                        ->where(['tbl_dcs_payment_cycle_applicability.dcs_payment_cycle_code' => $payment_cycle_code, 'tbl_bill_head_applicability.bill_head_code' => $bill_head_code])
    //                        ->orderby('dcs_name')->all();
    //
    //        $value1 = ArrayHelper::map($value1, 'dcs_code', 'dcs_name');
    //        $value2 = $this->find()->select(['tbl_dcs.dcs_code', 'dcs_name'])
    //                        ->innerJoinWith('billHeadTransactions')
    //                        ->where(['payment_cycle_code' => $payment_cycle_code, 'bill_head_code' => $bill_head_code])
    //                        ->orderby('dcs_name')->all();
    //
    //        $value2 = ArrayHelper::map($value2, 'dcs_code', 'dcs_name');
    //        $value = array_diff_key($value1, $value2);
    //        asort($value, SORT_NATURAL | SORT_FLAG_CASE);
    //        return $value;
    //    }
    //
    //    public function getPaymentCycleApplicability() {
    //        return $this->hasMany(TblDcsPaymentCycleApplicability::className(), ['dcs_code' => 'dcs_code']);
    //    }
    //
    //    public function getBillHeadApplicability() {
    //        return $this->hasMany(TblBillHeadApplicability::className(), ['dcs_code' => 'dcs_code']);
    //    }
    //
    //    public function getBillHeadTransactions() {
    //        return $this->hasMany(TblBillHeadDetail::className(), ['dcs_code' => 'dcs_code']);
    //    }


    public function getIfscDetail() {
        return $this->hasOne(TblBranch::className(), ['ifsc' => 'ifsc'])->andwhere(['is_active' => 1]);
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

    public function setbankDetails($model, &$saveModel, &$errors) {
        if (!empty($model->bank_account_no)) {
            $branch_model = new TblBankDetails();
            $branch_model->setModel('society', $model->dcs_code);
            $branch_model->ifsc = $model->ifsc;
            $branch_model->bank_account_no = $model->bank_account_no;
            $branch_model->bank_code = $model->bank_code;
            $branch_model->branch_code = $model->branch_code;
            $branch_model->beneficiary_name = $model->beneficiary_name;
            if (!$branch_model->validate()) {
                $errors[] = $branch_model->getErrors();
            }
            array_push($saveModel, $branch_model);
        }
    }

    public function setContactDetails($model, &$saveModel, &$errors) {
        if (!empty($model->mobile_no)) {
            $contact_model = new TblContactDetails();
            $contact_model->setModel('society', $model->dcs_code);
            $contact_model->department = $model->department;
            $contact_model->contact_person = $model->contact_person;
            $contact_model->firstname = $model->contact_person;
            $contact_model->local_contact_person = $model->local_contact_person;
            $contact_model->lastname = $model->middle_name;
            $contact_model->surname = $model->surname;
            $contact_model->local_lastname = $model->local_middlename;
            $contact_model->local_surname = $model->local_surname;
            $contact_model->mobile_no = $model->mobile_no;
            if (!$contact_model->validate()) {
                $errors[] = $contact_model->getErrors();
            }
            array_push($saveModel, $contact_model);
        }
    }

    public function getRecords($notInDcs = [], $dcs = '') {
        $query = $this->find()
                ->andWhere(['union_code' => $this->union_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'is_active' => 1])
                ->andWhere(['not in', 'dcs_code', $notInDcs]);
        if (!empty($dcs)) {
            $query->andWhere(['dcs_code' => $dcs]);
        }

        $dcsCode = $query->all();
        return $dcsCode;
    }

    public function IntValidateDcs() {
        /* if (substr($this->dcs_code, 0, 1) == '0') {
          $msg = Yii::t('app', 'DCS Code') . ' must not contain leading zero.';
          $this->addError('dcs_code', Yii::t('app/validation', $msg));
          return false;
          } */
        if (!preg_match('/^[0-9]*$/', $this->dcs_code)) {
            $msg = Yii::t('app', 'DCS Code') . ' must be number.';
            $this->addError('dcs_code', Yii::t('app/validation', $msg));
            return false;
        } else {
            $cnt = $this->find()->where(['convert(bigint,dcs_code)' => (int) $this->dcs_code])
                    ->andWhere(['!=', 'dcs_code', $this->dcs_code])
                    ->count();
            if ($cnt > 0) {
                $msg = Yii::t('app', 'DCS Code') . ' has already been taken.';
                $this->addError('dcs_code', Yii::t('app/validation', $msg));
                return false;
            }
        }
        $this->ref_code = $this->dcs_code;
    }

    public function getFtpCredentials() {
        return $this->find()
                        ->select(['module_code' => 'tbl_dcs.dcs_code', 'CP_Code' => 'tbl_dcs.ref_code', 'ftp_connection_code' => 'tbl_dcs.mcc_plant_code', 'sfd.ftp_type', 'sfd.ftp_host', 'sfd.ftp_username', 'sfd.ftp_password', 'sfd.ftp_port', 'sfd.ftp_path', 'sfd.ftp_mode'])
                        ->join('LEFT JOIN', 'tbl_society_vendor sv', 'sv.dcs_code = tbl_dcs.dcs_code')
                        ->join('LEFT JOIN', 'tbl_ftp_detail sfd', 'sfd.ftp_connection_code = tbl_dcs.mcc_plant_code')
                        ->where(['sv.vendor_code' => 'BIPL'])
                        ->orderBy('ftp_connection_code ASC')
                        ->asArray()
                        ->all();
    }

    public function getValidDcs($dcs) {
        $data = $this->find()->select('dcs_code')->where(['dcs_code' => $dcs])->andWhere(['is_active' => 1])->all();
        if (empty($data)) {
            $data = $this->find()->select('dcs_code')->where(['or', ['dcs_code' => $dcs], ['dcs_code_ex' => $dcs], ['ref_code' => $dcs]])->andWhere(['is_active' => 1])->all();
        }
        return !empty($data) && count($data) == 1 ? $data[0]->dcs_code : '';
    }

    public function getRouteSourceMapping() {
        return $this->hasOne(TblRouteMappingSources::className(), ['route_code' => 'route_code', 'from_dest' => 'dcs_code']);
    }

    public function dcsData($bmc) {
        return $this->find()
                        ->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name', 'tbl_dcs.ref_code', 'tbl_asset_set.sap_code', 'tbl_asset_set.asset_set_code', 'tbl_store_location.store_location_code', 'tbl_store_location.store_location_type'])
                        ->innerJoin('tbl_store_location', 'tbl_store_location.reference_code=tbl_dcs.dcs_code and tbl_store_location.store_location_type=3')
                        ->leftJoin('tbl_asset_set', 'tbl_asset_set.reference_code=tbl_dcs.dcs_code')
                        ->where(['tbl_dcs.bmc_code' => $bmc])
                        ->asArray()
                        ->all();
    }

    public function setXcol($attribute, $params) {
        $same = empty($this->same_milk_type) ? 0 : $this->same_milk_type;
        $different = empty($this->diff_milk_type) ? 0 : $this->diff_milk_type;
        $this->x_col1 = $same . '#' . $different;
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

    public function setMilkType($defaultMilk) {
        if (!empty($defaultMilk)) {
            if (in_array($defaultMilk, [6])) {
                $modelMilkType = [1, 3];
            } else if (in_array($defaultMilk, [5])) {
                $modelMilkType = [2, 3];
            } else if (in_array($defaultMilk, [4])) {
                $modelMilkType = [1, 2];
            } else if (in_array($defaultMilk, [3])) {
                $modelMilkType = [3];
            } else if (in_array($defaultMilk, [2])) {
                $modelMilkType = [2];
            } else if (in_array($defaultMilk, [1])) {
                $modelMilkType = [1];
            }
        }
        return $modelMilkType;
    }

    public function getRouteRefCode() {
        return $this->hasOne(TblRouteMapping::className(), ['ref_code' => 'route_code', 'union_code' => 'union_code']);
    }

    public function validateRoute($attribute, $params) {
        $this->route_code = $this->route;
        $this->route_code = Yii::$app->general->getforeignkey($this->routeRefCode, 'route_code');
        if (empty($this->oldAttributes) || ($this->oldAttributes['route_code'] != $this->route_code)) {
            if ((empty($this->routeMapping)) || (!empty($this->routeMapping) && ($this->routeMapping->to_type != 'bmc' || $this->routeMapping->to_dest != $this->bmc_code))) {
                $this->addError('route_code', Yii::t('app/validation', $this->getAttributeLabel('route_code') . ' is Invalid.'));
            }
        }
    }

    public function getActiveStatus() {
        return $this->hasOne(TblDcsVendorStatus::className(), ['customer_code' => 'dcs_code'])->andOnCondition(['customer_type' => 'DCS']);
    }

    public function getTblDcs() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMainBankDetails() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'dcs_code'])->andOnCondition(['tbl_bank_details.module_name' => 'society', 'tbl_bank_details.is_default' => 1, 'tbl_bank_details.is_active' => 1]);
    }

    public function getMainContactDetails() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'dcs_code'])->andOnCondition(['tbl_contact_details.module_name' => 'society', 'tbl_contact_details.is_default' => 1, 'tbl_contact_details.is_active' => 1]);
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

    public function getAndroidInstallation() {
        return $this->hasOne(TblAndroidInstallation::className(), ['organization_code' => 'ref_code'])->andOnCondition(['organization_type' => 'VLC']);
    }

    public function getSocietys($bmc_code, $as_array = false) {
        if (!empty($bmc_code)) {
            $query = $this->find()->where(['bmc_code' => $bmc_code, 'is_active' => 1]);
            if ($as_array)
                $query->asArray();
            $dcs = $query->all();
            return $dcs;
        }
        return false;
    }

    public function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function encryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model));
        foreach ($result as $key => $value) {
            if ($this->hasAttribute($value) && $this->{$value} != '')
                $model[$value] = \Yii::$app->general->encryptData($model[$value]);
        }

        return $model;
    }

    public function decryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model->attributes));
        foreach ($result as $key => $value) {
            $decryptData = \Yii::$app->general->decryptData($model->{$value});
            if ($decryptData) {
                $model->{$value} = $decryptData;
            }
        }
        return $model;
    }

    public function getDpuTypeWiseDCS($bmcCode = [], $dpuType = 0, $RLS = 'TRUE') {
        $query = $this->find()->alias('t')
                ->select(['t.dcs_code', 't.dcs_name', 't.ref_code', 'dpu_type' => 'count(m.member_code)'])
                ->leftJoin('tbl_member m', 'm.dcs_code=t.dcs_code')
                ->where(['t.is_active' => 1, 't.dpu_type' => $dpuType]);
        if (!empty($bmcCode))
            $query->andWhere(['t.bmc_code' => $bmcCode]);
        if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['t.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        $query->groupBy('t.dcs_code,t.dcs_name,t.ref_code');


        return $query->all();
    }

    public function getMergeBmcDcsList($type, $mcc = '', $bmc_code = '') {
        if (in_array($type, [1])) {
            $bmcModel = new TblDcsBmc();
            $query = $bmcModel->find()->where(['is_active' => 1]);
            if (Yii::$app->session->get('BMC') !== '') {
                $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
            }
            if (!empty($mcc)) {
                $query->andWhere(['mcc_plant_code' => $mcc]);
            }

            $bmc = $query->all();
            $bmc = ArrayHelper::map($bmc, 'bmc_code', function($bmc) {
                        return $bmc->ref_code . ' - ' . $bmc->bmc_name;
                    });
            asort($bmc, SORT_NATURAL | SORT_FLAG_CASE);
            return $bmc;
        } else if (in_array($type, [2])) {
            $query = $this->find()->where(['is_active' => 1]);
            if (Yii::$app->session->get('Dcs') !== '') {
                $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
            }
            if (!empty($mcc)) {
                $query->andWhere(['mcc_plant_code' => $mcc]);
            }
            if (!empty($bmc_code)) {
                $query->andWhere(['bmc_code' => $bmc_code]);
            }

            $dcs = $query->all();
            $dcs = ArrayHelper::map($dcs, 'dcs_code', function($bmc) {
                        return $bmc->ref_code . ' - ' . $bmc->dcs_name;
                    });
            asort($dcs, SORT_NATURAL | SORT_FLAG_CASE);
            return $dcs;
        } else if (in_array($type, [3])) {
            $plantModel = new TblPlant();
            $query = $plantModel->find()->where(['is_active' => 1]);
            if (Yii::$app->session->get('Plant') !== '') {
                $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
            }
            $plant = $query->all();
            $plant = ArrayHelper::map($plant, 'plant_code', function($plant) {
                        return $plant->ref_code . ' - ' . $plant->name;
                    });
            asort($plant, SORT_NATURAL | SORT_FLAG_CASE);
            return $plant;
        }
    }

    public function getLowerMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'lower_milk_type']);
    }

    public function getAllRlsDCS($unionCode = [], $plantCode = [], $mccCode = [], $bmcCode = [], $dcsCode = []) {
        $query = $this->find()->select(['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'dcs_code_ex', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($unionCode))
            $query->andWhere(['union_code' => $unionCode]);

        if (!empty($plantCode))
            $query->andWhere(['plant_code' => $plantCode]);

        if (!empty($mccCode))
            $query->andWhere(['mcc_plant_code' => $mccCode]);

        if (!empty($bmcCode))
            $query->andWhere(['bmc_code' => $bmcCode]);

        if (!empty($dcsCode))
            $query->andWhere(['dcs_code' => $dcsCode]);

        if (Yii::$app->session->get('Unions') !== '')
            $query->andWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);

        if (Yii::$app->session->get('Plant') !== '')
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);

        if (Yii::$app->session->get('MCC') !== '')
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);

        if (Yii::$app->session->get('BMC') !== '')
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);

        if (Yii::$app->session->get('Dcs') !== '')
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);

        return $query->all();
    }

    public function getValidDcsModel($dcs) {
        $data = $this->find()->where(['or', ['dcs_code' => $dcs], ['dcs_code_ex' => $dcs], ['ref_code' => $dcs]])
                ->andWhere(['union_code' => $this->union_code])
                ->all();
        return !empty($data) && count($data) == 1 ? $data[0] : '';
    }

    public function getOrgDCS($mccCode = [], $RLS = 'TRUE', $values = []) {
        $query = $this->find()->select(['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'])
                        ->distinct()->where(['is_active' => 1]);
        if (!empty($mccCode))
            $query->andWhere(['mcc_plant_code' => $mccCode]);
        if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (!empty($values)) {
            $query->andWhere(['not in', 'dcs_code', $values]);
        }
        return $query->all();
    }

    public function getProductSaleRates($unionCode) {
        $subQuery = TblProductSaleRate::find()
                ->select(['product_code', 'MAX(wef_date) AS max_wef_date'])
                ->where(['union_code' => $unionCode, 'is_member_rate' => 1])
                ->groupBy('product_code');

        return TblProductSaleRate::find()
                        ->alias('salerate')
                        ->innerJoin(['subsalerate' => $subQuery], 'salerate.product_code = subsalerate.product_code AND salerate.wef_date = subsalerate.max_wef_date')
                        ->where(['salerate.union_code' => $unionCode, 'salerate.is_member_rate' => 1])
                        ->orderBy(['salerate.wef_date' => SORT_DESC])
                        ->all();
    }

    public function resetData() {
        $this->aadhaar_no = $this->pan_no = null;
    }

    public function autoGenerateMember(&$master) {
        $config = !empty(Yii::$app->session->get('unionConfig')[$this->union_code]['no_of_auto_member_create']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['no_of_auto_member_create'] : 100;
        $max_qty_config_val = Yii::$app->general->getUnionConfiguration($this->union_code, 'max_qty_limit_member', 'PORTAL');
        $memberMod = new TblMember();

        $memberModels = [];
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_member');

        if (empty($keyPattern)) {
            $memberMod->addError('auto_code', Yii::t('app/validation', 'Key pattern config missing.'));
            $master[] = FALSE;
        }

        $exCodeData = $memberMod->find()
                ->select(['ex_code' => 'ISNULL(MAX(CAST(ex_member_code as int)),0)+1'])
                ->where([$keyPattern['ex_code_reset_on'] => $this->{$keyPattern['ex_code_reset_on']}])
                ->asArray()
                ->one();

        $refAutoCodeData = $memberMod->find()
                ->select(['ref_code' => 'ISNULL(MAX(CAST(RIGHT(ref_code,' . $keyPattern['ref_code_length'] . ') as int)),0)+1', 'auto_code' => 'ISNULL(MAX(auto_code),0)+1'])
                ->where(['union_code' => $this->union_code])
                ->asArray()
                ->one();

        $exCode = str_pad($exCodeData['ex_code'], $keyPattern['ex_code_length'], '0', STR_PAD_LEFT);
        $autoCode = $refAutoCodeData['auto_code'];
        $refCode = str_pad($refAutoCodeData['ref_code'], $keyPattern['ref_code_length'], '0', STR_PAD_LEFT);

        $prefixData = [];
        if ($keyPattern['ref_code_type'] == 1) {
            $prefix_seq = explode(',', $keyPattern['prefix_field']);
            foreach ($prefix_seq as $pre) {
                $pre_info = explode(':', $pre);
                if (isset($pre_info[1])) {
                    $t_info = explode('#', $pre_info[0]);
                    $table_name = $t_info[0];
                    $where_key = $t_info[1];
                    $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                    $append_field = $pre_info[1];

                    $key = "$table_name|$where_key|$where_val";
                    if (!isset($prefixData[$key])) {
                        $query = new \yii\db\Query();
                        $prefixData[$key] = $query->select($append_field)
                                ->from($table_name)
                                ->where([$where_key => $this->{$where_val}])
                                ->one();
                    }
                }
            }
        }
        if ($keyPattern['master_hierarchy_auto_entry'] == 1) {
            $childPattern = new TblKeyPatternChild();
            $childKeyPatterns = $childPattern->find()->where(['key_pattern_code' => $keyPattern['key_pattern_code']])->all();

            $childPrefixData = [];
            $childSuffixData = [];

            $childRefCodeData = [];

            foreach ($childKeyPatterns as $childKey => $childKeyPattern) {
                if ($childKeyPattern['key_code_type'] == 1) {
                    if (!empty($childKeyPattern['prefix_field'])) {
                        $prefix_seq = explode(',', $childKeyPattern['prefix_field']);
                        foreach ($prefix_seq as $pre) {
                            $pre_info = explode(':', $pre);
                            if (isset($pre_info[1])) {
                                $t_info = explode('#', $pre_info[0]);
                                $table_name = $t_info[0];
                                $where_key = $t_info[1];
                                $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                                $append_field = $pre_info[1];

                                $key = "$table_name|$where_key|$where_val";
                                if (!isset($childPrefixData[$key])) {
                                    $query = new \yii\db\Query();
                                    $childPrefixData[$key] = $query->select($append_field)
                                            ->from($table_name)
                                            ->where([$where_key => $this->{$where_val}])
                                            ->one();
                                }
                            }
                        }
                    }

                    if (!empty($childKeyPattern['suffix_field'])) {
                        $suffix_seq = explode(',', $childKeyPattern['suffix_field']);
                        foreach ($suffix_seq as $pre) {
                            $pre_info = explode(':', $pre);
                            if (isset($pre_info[1])) {
                                $t_info = explode('#', $pre_info[0]);
                                $table_name = $t_info[0];
                                $where_key = $t_info[1];
                                $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                                $append_field = $pre_info[1];

                                $key = "$table_name|$where_key|$where_val";
                                if (!isset($childSuffixData[$key])) {
                                    $query = new \yii\db\Query();
                                    $childSuffixData[$key] = $query->select($append_field)
                                            ->from($table_name)
                                            ->where([$where_key => $this->{$where_val}])
                                            ->one();
                                }
                            }
                        }
                    }
                }

                $index = $childKey + 1;
                $key_name = 'ref_code' . $index;
                $key_length = (int) $childKeyPattern['key_length'];
                $masterHierarchy = new TblMasterHierarchy();
                $childRefCodeData[$key_name] = $masterHierarchy->find()
                        ->select(['ref_code' => 'ISNULL(MAX(CAST(RIGHT(' . $key_name . ',' . $key_length . ') as bigint)),0)+1'])
                        ->where(['union_code' => $this->union_code])
                        ->asArray()
                        ->one();
                $activeCounts[$key_name] = $masterHierarchy->getActiveCount($key_name, $childKeyPattern['key_reset_on']);
            }
        }

        for ($x = 0; $x < $config; $x++) {
            $memberModel = new TblMember();
            $memberModel->attributes = $this->attributes;

            $memberModel->ex_member_code = str_pad((int) $exCode + $x, $keyPattern['ex_code_length'], '0', STR_PAD_LEFT);
            $memberModel->auto_code = $autoCode + $x;

            $pk_code = $this->union_code . str_pad($memberModel->auto_code, 3, '0', STR_PAD_LEFT);

            if ($keyPattern['ref_code_type'] == 0) {
                $memberModel->ref_code = $pk_code;
            } elseif ($keyPattern['ref_code_type'] == 1) {
                $memberModel->ref_code = '';
                foreach ($prefix_seq as $pre) {
                    $pre_info = explode(':', $pre);
                    if (isset($pre_info[1])) {
                        $t_info = explode('#', $pre_info[0]);
                        $table_name = $t_info[0];
                        $where_key = $t_info[1];
                        $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];

                        $key = "$table_name|$where_key|$where_val";
                        if (isset($prefixData[$key]) && !empty($prefixData[$key])) {
                            $memberModel->ref_code .= $prefixData[$key][$append_field];
                        } else {
                            $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $this->{$where_val} . ')';
                            $this->addError('ref_code', $message);
                            $master[] = FALSE;
                        }
                    } else {
                        $memberModel->ref_code .= $memberModel->{$pre};
                    }
                }
                $memberModel->ref_code = str_pad($memberModel->ref_code, $keyPattern['ref_code_length'], '0', STR_PAD_LEFT);
            } elseif ($keyPattern['ref_code_type'] == 2) {
                $memberModel->ref_code = $pk_code;
            }

            $memberModel->ref_code = str_pad($memberModel->ref_code, $keyPattern['ref_code_fix_length'], '0', STR_PAD_LEFT);
            $memberModel->member_code = $this->dcs_code . $memberModel->ex_member_code;

            if ($keyPattern['master_hierarchy_auto_entry'] == 1) {
                if (!empty($childKeyPatterns)) {
                    $masterHierarchy = new TblMasterHierarchy();
                    $pk_name = $memberModel::primaryKey()[0];
                    $masterHierarchy->attributes = $memberModel->attributes;
                    $masterHierarchy->master_key = $pk_name;
                    $masterHierarchy->{$pk_name} = $pk_code;
                    $masterHierarchy->wef_date = date('Y-m-d');
                    $masterHierarchy->is_active = 1;

                    foreach ($childKeyPatterns as $key => $childKeyPattern) {
                        $index = $key + 1;
                        $key_name = 'ref_code' . $index;
                        $masterHierarchy->master_type = $childKeyPattern->pattern_for;
                        $key_length = (int) $childKeyPattern['key_length'];
                        $key_fix_length = (int) $childKeyPattern['key_fix_length'];
                        $key_reset_on = $childKeyPattern['key_reset_on'];

                        if ($childKeyPattern['key_code_type'] == 1) {
                            $ref_code = ($key_length > 0) ? str_pad($childRefCodeData[$key_name]['ref_code'] + $x, $key_length, '0', STR_PAD_LEFT) : '';

                            if (!empty($childKeyPattern['prefix_field'])) {
                                $masterHierarchy->{$key_name} = '';
                                $prefix_seq = explode(',', $childKeyPattern['prefix_field']);
                                foreach ($prefix_seq as $pre) {
                                    $pre_info = explode(':', $pre);
                                    if (isset($pre_info[1])) {
                                        $t_info = explode('#', $pre_info[0]);
                                        $table_name = $t_info[0];
                                        $where_key = $t_info[1];
                                        $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                                        $append_field = $pre_info[1];

                                        $key = "$table_name|$where_key|$where_val";
                                        if (isset($childPrefixData[$key]) && !empty($childPrefixData[$key])) {
                                            $masterHierarchy->{$key_name} .= $childPrefixData[$key][$append_field];
                                        } else {
                                            $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $this->{$where_val} . ')';
                                            $this->addError('ref_code', $message);
                                            $master[] = FALSE;
                                        }
                                    } else {
                                        $masterHierarchy->{$key_name} .= $memberModel->{$pre};
                                    }
                                }
                            }

                            $masterHierarchy->{$key_name} .= $ref_code;

                            if (!empty($childKeyPattern['suffix_field'])) {
                                $suffix_seq = explode(',', $childKeyPattern['suffix_field']);
                                foreach ($suffix_seq as $pre) {
                                    $pre_info = explode(':', $pre);
                                    if (isset($pre_info[1])) {
                                        $t_info = explode('#', $pre_info[0]);
                                        $table_name = $t_info[0];
                                        $where_key = $t_info[1];
                                        $where_val = isset($t_info[2]) ? $t_info[2] : $t_info[1];
                                        $append_field = $pre_info[1];

                                        $key = "$table_name|$where_key|$where_val";
                                        if (isset($childSuffixData[$key]) && !empty($childSuffixData[$key])) {
                                            $masterHierarchy->{$key_name} .= $childSuffixData[$key][$append_field];
                                        } else {
                                            $message = 'Ref Code : No Data Found for ' . $table_name . '(' . $where_key . '=' . $this->{$where_val} . ')';
                                            $this->addError('ref_code', $message);
                                            $master[] = FALSE;
                                        }
                                    } else {
                                        $masterHierarchy->{$key_name} .= $memberModel->{$pre};
                                    }
                                }
                            }
                        } elseif ($childKeyPattern['key_code_type'] == 2) {
                            $masterHierarchy->{$key_name} = !empty($masterHierarchy->{$key_name}) ? $masterHierarchy->{$key_name} : NULL;
                        }

                        if ($childKeyPattern['key_code_type'] == 1) {
                            if (empty($masterHierarchy->{$key_name})) {
                                $this->addError('ref_code', Yii::t('app/validation', $this->getAttributeLabel('ref_code') . ' can not be blank.'));
                                $master[] = FALSE;
                            } else {
                                if ($activeCounts[$key_name] > 0) {
                                    $this->addError('ref_code', Yii::t('app/validation', $this->getAttributeLabel('ref_code') . ' has already been taken.'));
                                    $master[] = FALSE;
                                }
                            }

                            if (!empty($masterHierarchy->{$key_name})) {
                                $masterHierarchy->{$key_name} = str_pad($masterHierarchy->{$key_name}, $key_fix_length, '0', STR_PAD_LEFT);
                                if (strlen($masterHierarchy->{$key_name}) != $key_fix_length) {
                                    $this->addError('ref_code', Yii::t('app/validation', $this->getAttributeLabel('ref_code') . ' length must be ' . $key_fix_length . '.'));
                                    $master[] = FALSE;
                                }
                            }
                        }
                    }
                    $memberModel->set_master_hierarchy[] = $masterHierarchy;
                }
            }

            Yii::$app->default->getDefaults($memberModel);
            $memberModel->address = $this->dcs_name;
            $memberModel->no_of_buffalo = $memberModel->no_of_cow_cross = $memberModel->no_of_cow_ind = $memberModel->total_animals = 0;
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
            $memberModel->data_post_status = 0;
            $memberModel->is_download = $memberModel->is_email_verify = $memberModel->rate_class = $memberModel->is_verified = $memberModel->is_contact_verified = $memberModel->is_dcs_member = '0';
            $memberModel->is_active = 1;
            if (empty($memberModel->x_col3)) {
                $memberModel->x_col3 = (!empty($max_qty_config_val) && ($max_qty_config_val > 0)) ? $max_qty_config_val : 15;
            }
            foreach ($memberModel->attributes as $key => $value) {
                if ($value == '') {
                    $memberModel->$key = null;
                }
            }

            $memberModels[] = $memberModel;
        }

        $columns = array_keys($memberModels[0]->getAttributes());
        $rows = [];
        foreach ($memberModels as $memberModel) {
            $row = [];
            foreach ($columns as $column) {
                $row[] = $memberModel->$column;
            }
            $rows[] = $row;
        }
        Yii::$app->db->createCommand()->batchInsert(TblMember::tableName(), $columns, $rows)->execute();
    }

    public function resetDefaultValue() {
        $this->data_post_status = 0;
        $this->picked_datetime = $this->response_datetime = $this->resp_desc = NULL;
    }

    public function getMasterRecord(){
        $data = (new \yii\db\Query())
            ->select([
                'companyCode' => new Expression("ISNULL(u.x_col1, '')"),
                'mppCode' => new Expression("ISNULL(d.ref_code, '')"),
                'mppName' => new Expression("ISNULL(d.dcs_name, '')"),
                'sapMppCode' => new Expression("ISNULL(d.sap_vendor_code, '')"),
                'sapRouteCode' => new Expression("ISNULL(rm.sap_route_code, '')"),
                'sapPlantCode' => new Expression("''"),
                'mobileNo' => new Expression("ISNULL(c.mobile_no, '')"),
                'bmcCode' => new Expression("ISNULL(b.ref_code, '')"),
                'routeCode' => new Expression("ISNULL(rm.ref_code, '')"),
                'bankId' => new Expression("0"),
                'bankBranchName' => new Expression("''"),
                'accountName' => new Expression("''"),
                'accountNumber' => new Expression("''"),
                'ifsc' => new Expression("''"),
                'isActive' => new Expression("ISNULL(d.is_active, 0)"),
                'effectiveDate' => new Expression("ISNULL(CONVERT(VARCHAR(10), d.valid_from, 120), '')"),
                'effectiveShift' => new Expression("''"),
                'censusCode' => new Expression("ISNULL(d.dcs_code, '')"),
                'sapVendorCode' => new Expression("''"),
            ])
            ->from('tbl_dcs d')
            ->innerJoin('tbl_unions u', 'u.union_code = d.union_code')
            ->innerJoin('tbl_bmc b', 'b.bmc_code = d.bmc_code')
            ->leftJoin('tbl_route_mapping rm', 'rm.route_code  = d.route_code')
            ->leftJoin('tbl_contact_details c', 'c.module_code = d.dcs_code AND c.is_default = 1 AND c.is_active = 1')
            ->where(['isnull(d.data_post_status,0)' => [0,'']])
            ->limit(5)
            ->all();
        if (!empty($data)) {
            $companyCode = (string)$data[0]['companyCode'];
            array_walk($data, function(&$item) {
                $item['isActive'] = (bool)$item['isActive'];
                unset($item['companyCode']);
            });
            return [
                'companyCode' => $companyCode,
                'mppDetails' => $data
            ];
        }
        return [];

    }

    public function updateStatus($updateData, $ids, $extr_value = '') {
        return $this->updateAll($updateData, ['ref_code' => $ids]);
    }

}
