<?php

namespace app\modules\webservice\dataexchange\v1;

/**
 * v1 module definition class
 */
class V1 extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\dataexchange\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

    public static function setParam($svc) {
        $data = [
            'dcs_create' => [
                'mcc_code' => 'parent_code',
                'vendor_code' => 'master_code',
                'vendor_type' => 'master_type',
                'vendor_name' => 'master_name',
                'route_code' => 'parent_code_other',
                'route_effective_date' => 'date_1:date',
                'address_line1' => 'address',
                'address_line2' => 'address_2',
                'start_date' => 'date_2:date',
                'district' => 'district_code',
                'sub_district' => 'sub_district_code',
                'village' => 'village_code',
                'hamlet' => 'hamlet_code',
                'contact_person_name' => 'contact_first_name',
                'email' => 'email',
                'mobile_no' => 'mobile_no',
                'bank_name' => 'bank_name',
                'branch_name' => 'branch_name',
                'account_no' => 'bank_account_no',
                'ifsc' => 'ifsc',
                'is_active' => 'is_active',
                'scenario' => 'scenario',
            ],
            'route_create' => [
                'route_code_ex' => 'parent_code',
                'sap_route_code' => 'master_code',
                'route_name' => 'master_name',
                'route_type' => 'master_type',
                'to_type' => 'type_2',
                'to_dest' => 'parent_code_other',
                'capacity' => 'capacity',
                'vehicle_type_code' => 'x_col1',
                'route_length_kms' => 'route_length',
                'valid_from' => 'date_1:date',
                'morning_start_time' => 'time_1',
                'morning_end_time' => 'time_2',
                'evening_start_time' => 'time_3',
                'evening_end_time' => 'time_4',
                'first_name' => 'contact_first_name',
                'last_name' => 'contact_last_name',
                'surname' => 'contact_middle_name',
                'email' => 'email',
                'mobileno' => 'mobile_no',
                'is_active' => 'is_active',
                'department' => 'x_col2',
                'scenario' => 'scenario',
            ],
        ];
        return isset($data[$svc]) ? $data[$svc] : [];
    }

}
