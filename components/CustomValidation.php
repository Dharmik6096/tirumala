<?php

namespace app\components;

use yii\base\Component;

class CustomValidation extends Component {

    public function getRules($model, $form) {
        $rules = $this->processRulesArray();
        $client_code = \Yii::$app->session->get('eiplCode');
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
                'TblDcs' => [],
                'TblContactDetails' => [
                    'dcs-create' => [],
                    'dcs-import' => [],
                    'default' => [
                        [['firstname', 'mobile_no'], 'required'],
                        [['mobile_no'], 'required', 'on' => 'additional'],
                    ],
                ],
                'TblMember' => [],
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
                ],
                'TblContactDetails' => [
                    [['firstname', 'mobile_no'], 'required'],
                    [['mobile_no'], 'required', 'on' => 'additional'],
                ],
                'TblMember' => [
                    [['address', 'hamlet_code'], 'required', 'except' => ['importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                    [['district_code', 'sub_district_code', 'village_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals'], 'required', 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember']],
                    [['district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'mobile_no'], 'required', 'on' => ['ApprovalMember']],
                ],
            ],
        ];
    }

}
