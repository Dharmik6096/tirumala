<?php

namespace app\components;

use yii\base\Component;
use Yii;

class CustomValidation extends Component {

    public function getRules($model, $form, $eiplcode = '') {
        $rules = $this->processRulesArray();
        $client_code = !empty($eiplcode) ? $eiplcode : \Yii::$app->session->get('eiplCode');
        $client_rules = isset($rules[$client_code]) ? $rules[$client_code] : [];
        $client_rules = isset($client_rules[$model]) ? $client_rules : [];
        if (!empty($client_rules)) {
            $client_rules = !empty(($client_rules[$model][$form])) ? $client_rules[$model][$form] : [];
        } else {
            $client_code = 'EIPLCOMMON';
            $client_rules = !empty(($rules[$client_code])) ? $rules[$client_code] : [];
            $client_rules = !empty(($client_rules[$model])) ? $client_rules[$model] : [];
        }
        return $client_rules;
    }

    public static function processRulesArray() {
        return [
            'GYAN' => [
                'TblPlant' => [],
                'TblMccPlant' => [],
                'TblDcsBmc' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['post_sap_data', 'from_mcc']],
                            [
                                ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['post_sap_data', 'from_mcc']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['post_sap_data', 'from_mcc']],
                    ],
                ],
                'TblDcs' => [
                    'default' => [
                            [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                            [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                            [
                                ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                    ]
                ],
                'TblContactDetails' => [
                    'dcs-create' => [
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'dcs-import' => [
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'default' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->ifsc);
                            }, 'whenClient' => "function (attribute, value) { 
                                return $('#tblmember-ifsc').val() != ''; 
                            }", 'on' => ['importCsv']],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync', 'specialCodeImportCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblBranch' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                            [
                                ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                        ],
                            [['ifsc'], function ($attribute, $params) {
                                Yii::$app->general->validateIfsc($this, $attribute, $params);
                            }, 'skipOnEmpty' => false],
                    ],
                ],
                'BackGroundDataImport' => [
                    'default' => [
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'on' => ['member']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['member']],
                            [['rate_wharehouse'], 'required', 'on' => ['product_sale_rate_gyan']],
                    ],
                ],
                'TblBankDetails' => [
                    'default' => [
                            [['bank_account_no'], 'CheckDuplicate'],
                    ],
                ],
                'TblProductSaleRate' => [
                    'default' => [
                            [['rate_wharehouse'], 'required'],
                    ],
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
            ],
            'EIPLCOMMON' => [
                'TblPlant' => [
                        [['hamlet_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => 'importCsv'],
                ],
                'TblMccPlant' => [
                        [['hamlet_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => 'importCsv'],
                ],
                'TblDcsBmc' => [
                        [['hamlet_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => 'importCsv'],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['post_sap_data', 'from_mcc']],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['post_sap_data', 'from_mcc']
                    ],
                        [['aadhaar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true, 'except' => ['post_sap_data', 'from_mcc']],
                ],
                'TblDcs' => [
                        [['hamlet_code', 'pincode', 'dcs_type_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'customImport']],
                        [['contact_person', 'mobile_no'], 'required', 'on' => ['importCsv']],
                        [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                        [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                            return $model->isAttributeChanged('sap_vendor_code', FALSE);
                        }],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']
                    ],
                        [['aadhaar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                ],
                'TblContactDetails' => [
                        [['firstname', 'mobile_no'], 'required'],
                        [['mobile_no'], 'required', 'on' => 'additional'],
                        [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                ],
                'TblMember' => [
                        [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                        [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                        [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                        [['bank_account_no'], 'required', 'when' => function ($model) {
                            return !empty($model->branch_code);
                        }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                        ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                            return $model->is_active;
                        }, 'except' => ['saveCreamyData', 'androidsync', 'specialCodeImportCsv']],
                        [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                            return $model->is_active;
                        }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification', 'specialCodeImportCsv']],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                        [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                        [['adhar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                ],
                'TblBranch' => [
                        [['hamlet_code', 'pincode'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => 'importCsv'],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                    ],
                        [['ifsc'], function ($attribute, $params) {
                            Yii::$app->general->validateIfsc($this, $attribute, $params);
                        }, 'skipOnEmpty' => false],
                ],
                'BackGroundDataImport' => [
                        [['address', 'hamlet_code', 'gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'on' => ['member']],
                ],
                'TblBankDetails' => [
                        [['bank_account_no'], 'CheckDuplicate'],
                ],
                'TblDcsProvisional' => [
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping', 'uploadDoc']],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping', 'uploadDoc']
                    ],
                        [['aadhaar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs']],
                ],
                'TblUnions' => [
                        [['pincode'], 'required'],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                    ],
                ],
                'TblStaffMember' => [
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                    ],
                        [['aadhar_card_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharCard($this, $attribute, 'aadhar_card_no');
                        }, 'skipOnEmpty' => TRUE],
                ],
                'TblTransporter' => [
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['activation']],
                        [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['activation']],
                        [['hamlet_code'], 'required', 'except' => ['activation']],
                        [['state_code', 'district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'activation']],
                ],
                'TblVehicleMaster' => [
                        [['parsing_no'], function ($attribute, $params) {
                            Yii::$app->general->validVehicleNumber($this, $attribute, $params);
                        }, 'except' => ['activation']],
                        [['parsing_no'], 'string', 'min' => 8, 'max' => 11, 'except' => ['activation']],
                ],
                'TblFederations' => [
                        [['pincode'], 'required'],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                        [
                            ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                    ],
                ],
                'TblMemberProvisional' => [
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                        [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                        [['adhar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                        [['beneficiary_name'], 'required', 'when' => function ($model) {
                            return ($model->is_verify == 1);
                        }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                ],
                'TblCustomerMaster' => [
                        [['aadhaar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true, 'except' => ['deleteRouteMapping']],
                ],
                'TblVendorMaster' => [
                        [['adhar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }],
                ],
                'TblPartyMaster' => [
                        [['adhar_no'], function ($attribute, $params) {
                            Yii::$app->general->validateAadharcard($this, $attribute, $params);
                        }, 'skipOnEmpty' => true],
                ],
                'TblVspPayment' => [
                    // [['customer_type'], 'required', 'on' => ['remuneration', 'processpayment', 'paymenttypevendor','disbursesearch']],
                        [['customer_type'], 'required', 'except' => ['unreleasepaymentsearch', 'unreleasePaymentUpdate']],
                ],
                'TblBulkBillingImport' => [
                        [['bank_account_no', 'ifsc'], 'required', 'on' => ['member_billing_import']],
                ],
                'TblUserAndroid' => [
                        [['email'], 'email'],
                ],
                'TblMilkVehicleEntryQlty' => [
                        [['acidity', 'mbrt'], 'required', 'except' => ['resetQlty']],
                ],
            ],
            'NIFPL' => [
                'TblDcs' => [
                    'default' => [
                            [['dcs_type_code'], 'required'],
                            [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                            [
                                ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                    ]
                ],
                'TblContactDetails' => [
                    'dcs-create' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'dcs-import' => [
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'default' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'specialCodeImportCsv']],
                            [['max_allowed_qty'], 'number', 'min' => 0.5, 'max' => 15],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync', 'specialCodeImportCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ]
                ],
                'BackGroundDataImport' => [],
                'TblBankDetails' => [
                    'default' => [
                            [['bank_account_no'], 'CheckDuplicate'],
                    ],
                ],
            ],
            'ATMOST' => [
                'TblBankDetails' => [
                    'default' => [],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
            ],
            'MURALYA' => [
                'TblBankDetails' => [
                    'default' => [],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
            ],
            'MMD' => [
                'TblBankDetails' => [
                    'default' => [],
                ],
                'TblMember' => [
                    'default' => [
                            [['max_allowed_qty'], 'number', 'min' => 0.5, 'max' => 15],
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification']],
                    ],
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
            ],
            'THIRUMALA' => [
                'TblContactDetails' => [
                    'default' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    //[['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'route-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'dcs-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    //                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'dcs-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    //[['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'plant-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    //[['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'mcc-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    //[['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'bmc-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    //[['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblDcsProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping', 'uploadDoc']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping', 'uploadDoc']],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs']],
                            [['route_code'], 'required', 'on' => ['createDcs']],
                            [['dcs_code_ex'], 'required'],
                    ],
                ],
                'TblMemberProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                    ]
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
            ],
            'PRABHAT' => [
                'BackGroundDataImport' => [
                    'default' => [
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code', 'vendor_code'], 'required', 'on' => ['member']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['member']],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['vendor_code'], 'required'],
                            [['max_allowed_qty'], 'number', 'min' => 0.5],
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblDcsProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping', 'uploadDoc']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping', 'uploadDoc']],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs']],
                            [['route_code'], 'required', 'on' => ['createDcs']],
                            [['dcs_code_ex'], 'required'],
                    ],
                ],
                'TblMemberProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                    ]
                ],
            ],
            'ANIK' => [
                'TblDcsProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping', 'uploadDoc']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping', 'uploadDoc']],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs']],
                            [['route_code'], 'required', 'on' => ['createDcs']],
                            [['dcs_code_ex'], 'required'],
                    ],
                ],
                'TblMemberProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                    ]
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
            ],
            'HATSUN' => [
                'TblContactDetails' => [
                    'default' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-create' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'route-import' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'dcs-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional']
                    ],
                    'dcs-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'plant-create' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'except' => ['additional', 'verification']],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                    'mcc-create' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                            [['mobile_no'], 'required', 'except' => ['additional', 'verification']],
                    ],
                    'bmc-create' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                            [['mobile_no'], 'required', 'except' => ['additional', 'verification']],
                    ],
                    'cluster-create' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['max_allowed_qty'], 'number', 'min' => 0.5, 'max' => 15],
                        //[['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv']],
                        [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['animal_type_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification']],
                    ],
                ],
            ],
            'UMANG' => [
                'TblDcs' => [
                    'default' => [
                            [['hamlet_code', 'pincode', 'dcs_type_code'], 'required'],
                            [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'customImport']],
                            [['contact_person', 'mobile_no'], 'required', 'on' => ['importCsv']],
                            [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                        //[['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                        [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                            [
                                ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                    ],
                ],
            ],
            'VRS_NEWASA' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['adhar_no'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ],
                ],
            ],
            'VRS_GLT' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['adhar_no'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ],
                ],
            ],
            'VRS_MLP' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['adhar_no'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ],
                ],
            ],
            'VRS_SBD' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['adhar_no'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ],
                ],
            ],
            'CARGILL' => [
                'TblContactDetails' => [
                    'default' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'route-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'route-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'dcs-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'dcs-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'plant-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'mcc-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'bmc-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                ],
                'TblDcs' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['routeMapping']],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['routeMapping']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                    ],
                ],
                'TblDcsProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['routeMapping', 'uploadDoc']],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['routeMapping', 'uploadDoc']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs']],
                    ],
                ],
                'TblUnions' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                    ],
                ],
                'TblStaffMember' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                            [['aadhar_card_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, 'aadhar_card_no');
                            }, 'skipOnEmpty' => TRUE],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblTransporter' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['activation']],
                            [['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['activation']],
                            [['hamlet_code'], 'required', 'except' => ['activation']],
                            [['state_code', 'district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'activation']],
                    ],
                ],
                'TblDcsBmc' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['post_sap_data', 'from_mcc']],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['post_sap_data', 'from_mcc']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['post_sap_data', 'from_mcc']],
                    ],
                ],
                'TblBranch' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                    ],
                ],
                'TblFederations' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                    ],
                ],
                'TblMemberProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ],
                ],
                'TblCustomerMaster' => [
                    'default' => [
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['deleteRouteMapping']],
                    ],
                ],
                'TblVendorMaster' => [
                    'default' => [
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }],
                    ],
                ],
                'TblPartyMaster' => [
                    'default' => [
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true],
                    ],
                ],
                'TblVehicleMaster' => [],
                'TblBankDetails' => [],
                'TblVspPayment' => [],
            ],
            'KOTMALE' => [
                'TblContactDetails' => [
                    'default' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'route-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'route-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'dcs-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'dcs-import' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'plant-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'mcc-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                    'bmc-create' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                ],
                'TblDcs' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['routeMapping']],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['routeMapping']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs', 'importCsv']],
                    ],
                ],
                'TblDcsProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['routeMapping', 'uploadDoc']],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['routeMapping', 'uploadDoc']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'on' => ['createDcs', 'updateDcs']],
                    ],
                ],
                'TblUnions' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                    ],
                ],
                'TblStaffMember' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                            [['aadhar_card_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, 'aadhar_card_no');
                            }, 'skipOnEmpty' => TRUE],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblTransporter' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['activation']],
                            [['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['activation']],
                            [['hamlet_code'], 'required', 'except' => ['activation']],
                            [['state_code', 'district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'activation']],
                    ],
                ],
                'TblDcsBmc' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['post_sap_data', 'from_mcc']],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['post_sap_data', 'from_mcc']
                        ],
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['post_sap_data', 'from_mcc']],
                    ],
                ],
                'TblBranch' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                    ],
                ],
                'TblFederations' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"')],
                            [
                                ['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 5 digit ')
                        ],
                    ],
                ],
                'TblMemberProvisional' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."12345"'), 'except' => ['androidsync']],
                            [['pincode'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 5 digit '), 'except' => ['androidsync']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'MemberApprove', 'create_animal']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ],
                ],
                'TblCustomerMaster' => [
                    'default' => [
                            [['aadhaar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['deleteRouteMapping']],
                    ],
                ],
                'TblVendorMaster' => [
                    'default' => [
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }],
                    ],
                ],
                'TblPartyMaster' => [
                    'default' => [
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateCargillAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true],
                    ],
                ],
                'TblVehicleMaster' => [],
                'TblVspPayment' => [],
                'TblBankDetails' => [],
            ],
            'SAAHAJ' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['gender_code', 'branch_code', 'bank_account_no', 'bank_code', 'adhar_no', 'email', 'mobile_no', 'witness_name', 'place'], 'required', 'except' => ['pro_member_sap_import']],
                            [['daily_milk_total', 'home_consumption_milk', 'market_surplus_milk'], 'required', 'on' => ['member_detail']],
                            [['is_contact_verified', 'is_verify', 'is_email_verify', 'is_aadhar_verify'], 'validateFlag', 'except' => ['pro_member_sap_import']],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['gender_code'], 'required', 'except' => ['specialCodeImportCsv']],
                            [['daily_milk_total', 'home_consumption_milk', 'market_surplus_milk'], 'required', 'on' => ['member_detail']],
                    ],
                ],
                'TblMemberProvisionalShareDetails' => [
                    'default' => [
                            [['amount_deposit'], 'required', 'except' => ['import_receipt_detail']],
                    ],
                ],
                'TblBulkBillingImport' => [],
            ],
            'ANANDA' => [
                'TblMember' => [
                    'default' => [
                            [['max_allowed_qty'], 'number', 'min' => 0.5],
                        //[['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv']],
                        [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['animal_type_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
            ],
            'DEMO' => [
                'TblMember' => [
                    'default' => [
                            [['max_allowed_qty'], 'number', 'min' => 0.5],
                        //[['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv']],
                        [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['animal_type_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
            ],
            'ABT' => [
                'TblBankDetails' => [
                    'default' => [
                            [['bank_account_no'], 'CheckDuplicate', 'except' => ['deactivate']],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync', 'specialCodeImportCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
                'TblBranch' => [
                    'default' => [
                            [['pincode'], 'required'],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                            [
                                ['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                        ],
                            [['ifsc'], function ($attribute, $params) {
                                Yii::$app->general->validateIfsc($this, $attribute, $params);
                            }, 'skipOnEmpty' => false],
                    ],
                ],
                'TblMemberProvisional' => [
                    'default' => [
                            [['is_verify', 'is_aadhar_verify'], function ($attribute, $params, $validator) {
                                if ($this->$attribute == 0) {
                                    $this->addError($attribute, $this->getAttributeLabel($attribute) . ' field must be checked.');
                                }
                            }, 'on' => ['update_provisional_member', 'createProvisionalMember']],
                            [['is_operator_aggre'], function ($attribute, $params, $validator) {
                                if ($this->$attribute == 0) {
                                    $this->addError($attribute, $this->getAttributeLabel($attribute) . ' field must be checked.');
                                }
                            }, 'on' => ['MemberDocument', 'MemberApprove']],
                            [['beneficiary_name'], 'required', 'when' => function ($model) {
                                return ($model->is_verify == 1);
                            }, 'whenClient' => "function (attribute, value) { 
                                    return $('#tblmemberprovisional-is_verify').prop('checked') == true;
                             }", 'on' => ['createProvisionalMember']],
                    ]
                ],
            ],
            'DODLA' => [
                'TblVehicleMaster' => [
                    'default' => [
                            [['parsing_no'], function ($attribute, $params) {
                                Yii::$app->general->validVehicleNumber($this, $attribute, $params);
                            }, 'except' => ['activation']],
                            [['parsing_no'], 'string', 'min' => 8, 'max' => 11, 'except' => ['activation']],
                    ],
                ],
                'TblUserAndroid' => [
                    'default' => [
                            [['email'], 'email'],
                    ],
                ],
                'TblBmcMilkDispatch' => [
                    'default' => [
                            [['tested_by'], 'required']
                    ],
                ],
                'TblBmcMilkDispatchTxn' => [
                    'default' => [
                            [['shift_of_milk'], 'required']
                    ],
                ],
                'TblMilkVehicleEntryQlty' => [
                    'default' => [
                            [['tested_by', 'verified_by'], 'required', 'except' => ['resetQlty']]
                    ],
                ],
                'TblMilkVehicleEntryQltyMerge' => [
                    'default' => [
                            [['tested_by', 'verified_by'], 'required', 'except' => ['androidsync']]
                    ],
                ],
                'TblTransporter' => [
                    'default' => [
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['activation']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['activation']],
                            [['address'], function ($attribute, $params) {
                                Yii::$app->general->validateDiscriptiveField($this, $attribute, TRUE);
                            }, 'except' => ['activation']],
                    ],
                ],
            ],
            'ELANAD' => [
                'TblBankDetails' => [
                    'default' => [],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification', 'specialCodeImportCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification', 'specialCodeImportCsv']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification', 'specialCodeImportCsv']],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['androidsync', 'specialCodeImportCsv']],
                            [['adhar_no'], function ($attribute, $params) {
                                Yii::$app->general->validateAadharcard($this, $attribute, $params);
                            }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync', 'verification', 'specialCodeImportCsv']],
                    ],
                ],
            ],
        ];
    }

}
