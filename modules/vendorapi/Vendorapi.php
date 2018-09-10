<?php

namespace app\modules\vendorapi;

/**
 * vendorapi module definition class
 */
class Vendorapi extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\vendorapi\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
    }

    public static function setParam($svc) {
        $data = [
            'route_master' => [
                'route_code' => 'master_code',
                'route_name' => 'master_name',
                'route_type' => 'master_type',
                'route_destination' => 'parent_code',
                'vehicle_type' => 'type_2',
                'vehicle_capacity' => 'capacity',
                'route_length' => 'route_length',
                'route_start_date' => 'date_1:date',
                'morning_start_time' => 'time_1',
                'morning_end_time' => 'time_2',
                'evening_start_time' => 'time_3',
                'evening_end_time' => 'time_4',
                'contact_person_fname' => 'contact_first_name',
                'contact_person_mname' => 'contact_middle_name',
                'contact_person_lname' => 'contact_last_name',
                'email' => 'email',
                'mobile_no' => 'mobile_no',
                'is_active' => 'is_active',
                'scenario' => 'scenario'
            ],
            'dcs_master' => [
                'mcc_code' => 'parent_code',
                'parent_route' => 'parent_code_other',
                'vlcc_code' => 'master_code',
                'vlcc_name' => 'master_name',
                'start_date' => 'date_1:date',
                'district' => 'district_code',
                'sub_district' => 'sub_district_code',
                'village' => 'village_code',
                'hamlet' => 'hamlet_code',
                'contact_person_first_name' => 'contact_first_name',
                'contact_person_middle_name' => 'contact_middle_name',
                'contact_person_last_name' => 'contact_last_name',
                'address_line_1' => 'address',
                'address_line_2' => 'address_2',
                'email' => 'email',
                'mobile_no' => 'mobile_no',
                'is_active' => 'is_active',
                'bank_name' => 'bank_name',
                'branch_name' => 'branch_name',
                'account_no' => 'bank_account_no',
                'ifsc_code' => 'ifsc',
                'scenario' => 'scenario'
            ],
            'mcc_master' => [
                'plant_code' => 'parent_code',
                'mcc_code' => 'master_code',
                'mcc_name' => 'master_name',
                'start_date' => 'date_1:date',
                'region' => 'state_code',
                'district' => 'district_code',
                'sub_district' => 'sub_district_code',
                'village' => 'village_code',
                'hamlet' => 'hamlet_code',
                'contact_person_first_name' => 'contact_first_name',
                'contact_person_middle_name' => 'contact_middle_name',
                'contact_person_last_name' => 'contact_last_name',
                'mcc_capacity' => 'capacity',
                'address_line_1' => 'address',
                'email' => 'email',
                'mobile_no' => 'mobile_no',
                'is_active' => 'is_active',
                'scenario' => 'scenario'
            ],
            'route_dcs' => [
                'route_code' => 'parent_code',
                'vlcc_vendor_code' => 'master_code',
                'from_date' => 'date_1:date',
                'to_date' => 'date_2:date',
                'scenario' => 'scenario'
            ],
        ];
        return isset($data[$svc]) ? $data[$svc] : [];
    }

}
