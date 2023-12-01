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
                'TblDcsBmc' => [],
                'TblDcs' => [
                    'default' => [
                            [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                            [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                                'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']],
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
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->ifsc);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-ifsc').val() != ''; 
                        }", 'on' => ['importCsv']],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification']],
                    ],
                ],
                'TblBranch' => [],
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
                ],
                'TblDcs' => [
                    [['hamlet_code', 'dcs_type_code'], 'required'],
                    [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'customImport']],
                    [['contact_person', 'mobile_no'], 'required', 'on' => ['importCsv']],
                    [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                    [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                    [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                    [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']
                    ],
                ],
                'TblContactDetails' => [
                        [['firstname', 'mobile_no'], 'required'],
                        [['mobile_no'], 'required', 'on' => 'additional'],
                        [['mobile_no'], 'CheckDuplicate', 'except' => 'verification'],
                ],
                'TblMember' => [
                        [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                        [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                        [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                        [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification']],
                        [['bank_account_no'], 'required', 'when' => function ($model) {
                            return !empty($model->branch_code);
                        }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                        ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                            return $model->is_active;
                        }, 'except' => ['saveCreamyData', 'androidsync']],
                        [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                            return $model->is_active;
                        }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification']],
                ],
                'TblBranch' => [
                        [['hamlet_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => 'importCsv'],
                ],
                'BackGroundDataImport' => [
                        [['address', 'hamlet_code', 'gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'on' => ['member']],
                ],
                'TblBankDetails' => [
                        [['bank_account_no'], 'CheckDuplicate'],
                ],
                'TblDcsProvisional' => [
                    [['pincode'], 'required'],
                    [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping', 'uploadDoc']],
                    [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping', 'uploadDoc']
                    ],
                ],
                'TblUnions' => [
                    [['pincode'], 'required'],
                    [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                    [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                    ],
                ],
                'TblStaffMember' => [
                    [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"')],
                    [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                        'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit ')
                    ],
                ],
                'TblVehicleMaster' => [
                    [['parsing_no'], function ($attribute, $params) {
                        Yii::$app->general->validVehicleNumber($this, $attribute, $params);
                    }, 'except' => ['activation']],
                    [['parsing_no'], 'string', 'min' => 8, 'max' => 11, 'except' => ['activation']],
                ],
            ],
            'NIFPL' => [
                'TblDcs' => [
                    'default' => [
                            [['dcs_type_code'], 'required'],
                            [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                            [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                            [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                                'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']],
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
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv']],
                            [['max_allowed_qty'], 'number', 'min' => 0.5, 'max' => 15],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync']],
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
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync']],
                    ],
                ],
            ],
            'MURALYA' => [
                'TblBankDetails' => [
                    'default' => [],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync']],
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
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                                return $model->is_active;
                            }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync']],
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
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync']],
                            //[['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                            //    return $model->is_active;
                            //}, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync', 'verification']],
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
                            [['max_allowed_qty'], 'number', 'min' => 0.5, 'max' => 15],
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                        //[['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
                        //        return $model->is_active;
                        //    }, 'except' => ['deactivate', 'saveCreamyData', 'post_sap_data', 'androidsync']],
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
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['animal_type_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv', 'verification']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                    ],
                ],
            ],
            'UMANG' => [
                'TblDcs' => [
                    'default' => [
                        [['hamlet_code', 'dcs_type_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'customImport']],
                        [['contact_person', 'mobile_no'], 'required', 'on' => ['importCsv']],
                        [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                        //[['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                        [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                        [['pincode'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."123456"'), 'except' => ['routeMapping']],
                        [['pincode'], 'string', 'max' => 6, 'min' => 6, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 6 digit '),
                            'tooShort' => Yii::t('app/validation', '{attribute} must contain 6 digit '), 'except' => ['routeMapping']],
                    ],
                ],
            ],
            'VRS_NEWASA' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['adhar_no'], 'required'],
                    ],
                ],
            ],
            'VRS_GLT' => [
                'TblMemberProvisional' => [
                    'default' => [
                            [['adhar_no'], 'required'],
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
                'TblVehicleMaster' => [],
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
            ],
        ];
    }

}
