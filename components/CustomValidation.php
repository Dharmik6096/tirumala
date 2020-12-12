<?php

namespace app\components;

use yii\base\Component;

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
                    ]
                ],
                'TblContactDetails' => [
                    'dcs-create' => [],
                    'dcs-import' => [],
                    'default' => [
                            [['firstname', 'mobile_no'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync']],
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
                    ],
                ],
                'TblBankDetails' => [
                    'default' => [
                            [['bank_account_no'], 'CheckDuplicate'],
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
                        [['hamlet_code', 'pincode', 'dcs_type_code'], 'required'],
                        [['district_code', 'sub_district_code', 'village_code'], 'required', 'except' => ['importCsv', 'customImport']],
                        [['contact_person', 'mobile_no'], 'required', 'on' => ['importCsv']],
                        [['valid_from'], 'required', 'except' => ['importCsv', 'deactivate', 'routeMapping', 'saveCreamyData', 'customImport', 'customImportUpdate']],
                ],
                'TblContactDetails' => [
                        [['firstname', 'mobile_no'], 'required'],
                        [['mobile_no'], 'required', 'on' => 'additional'],
                ],
                'TblMember' => [
                        [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                        [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                        [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                        [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection']],
                        [['bank_account_no'], 'required', 'when' => function ($model) {
                            return !empty($model->branch_code);
                        }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                        ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                            return $model->is_active;
                        }, 'except' => ['saveCreamyData', 'androidsync']],
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
            ],
            'NIFPL' => [
                'TblDcs' => [
                    'default' => [
                            [['dcs_type_code'], 'required'],
                    ]
                ],
                'TblContactDetails' => [
                    'dcs-create' => [
                            [['firstname'], 'required'],
                    ],
                    'dcs-import' => [],
                    'default' => [
                            [['firstname'], 'required'],
                            [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'importCsv']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection', 'importCsv']],
                            [['max_allowed_qty'], 'number', 'min' => 0.5, 'max' => 15],
                            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => \Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                                return $model->is_active;
                            }, 'except' => ['saveCreamyData', 'androidsync']],
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
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                    ],
                ],
            ],
            'MURALYA' => [
                'TblBankDetails' => [
                    'default' => [],
                ],
                'TblMember' => [
                    'default' => [
                            [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                            [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                            [['district_code', 'sub_district_code', 'village_code', 'hamlet_code'], 'required', 'on' => ['ApprovalMember']],
                            [['gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'collection']],
                            [['bank_account_no'], 'required', 'when' => function ($model) {
                                return !empty($model->branch_code);
                            }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblmember-bank_code').val() != ''; 
                        }", 'on' => ['importCsv']],
                    ],
                ],
            ],
        ];
    }

}
