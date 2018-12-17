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
                'contact_person_first_name' => 'contact_first_name',
                'contact_person_middle_name' => 'contact_middle_name',
                'contact_person_last_name' => 'contact_last_name',
                'email' => 'email',
                'mobileno' => 'mobile_no',
                'isactive' => 'is_active',
                'scenario' => 'scenario',
                'json_key' => 'MT_Route_Master_IB',
                'content_json_key' => 'route_master',
                'response_master_key' => 'route_code',
                'response_main_array_key' => 'Route_Master_Response',
                'response_inner_array_key' => 'route_Response',
            ],
            'vlcc_master' => [
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
                'scenario' => 'scenario',
                'json_key' => 'MT_VLC_Master_IB',
                'content_json_key' => 'vlc_master_data',
                'response_master_key' => 'vlcc_code',
                'response_main_array_key' => 'Vlcc_Master_Response',
                'response_inner_array_key' => 'vlcc_Response',
            ],
            'mcc_master' => [
                'plant_code' => 'parent_code',
                'mcc_code' => 'master_code',
                'mcc_name' => 'master_name',
                'mcc_start_date' => 'date_1:date',
                'region' => 'state_code',
                'district' => 'district_code',
                'sub_district' => 'sub_district_code',
                'village' => 'village_code',
                'hamlet' => 'hamlet_code',
                'contact_person_first_name' => 'contact_first_name',
                'contact_person_middle_name' => 'contact_middle_name',
                'contact_person_last_name' => 'contact_last_name',
                'mcc_capacity' => 'capacity',
                'address' => 'address',
                'email' => 'email',
                'mobile_number' => 'mobile_no',
                'isactive' => 'is_active',
                'scenario' => 'scenario',
                'json_key' => 'MT_MCC_Master_IB',
                'content_json_key' => 'mcc_master',
                'response_master_key' => 'mcc_code',
                'response_main_array_key' => 'Mcc_Master_Response',
                'response_inner_array_key' => 'mcc_Response',
            ],
            'route_vlcc' => [
                'route_code' => 'parent_code',
                'vlcc_vendor_code' => 'master_code',
                'from_date' => 'date_1:date',
                'to_date' => 'date_2:date',
                'scenario' => 'scenario',
                'json_key' => 'MT_Route_VLC_Mapping_IB',
                'content_json_key' => 'route_vlc_mapping',
                'response_master_key' => 'vlcc_vendor_code',
                'response_main_array_key' => 'Route_Vlcc_Master_Response',
                'response_inner_array_key' => 'route_vlcc_Response',
            ],
            'rate_applicability' => [
                'rate_group_id' => 'master_code',
                'vlcc_code' => 'parent_code',
                'valid_start' => 'date_1:date',
                'valid_end' => 'date_2:date',
                'scenario' => 'scenario',
                'json_key' => 'MT_Rate_Applicability_IB',
                'content_json_key' => 'rate_applicability',
                'response_master_key' => 'rate_group_id',
                'response_main_array_key' => 'Rate_Applicability_Response',
                'response_inner_array_key' => 'rate_applicability_Response',
            ],
            'rate_master' => [
                'rate_group' => 'master_code',
                'from_fat' => 'from_fat',
                'to_fat' => 'to_fat',
                'fat_price' => 'fat_price',
                'from_snf' => 'from_snf',
                'to_snf' => 'to_snf',
                'snf_price' => 'snf_price',
                'valid_from_date' => 'date_1:date',
                'valid_end_date' => 'date_2:date',
                'scenario' => 'scenario',
                'json_key' => 'MT_Rate_Master_IB',
                'content_json_key' => 'rate_master',
                'response_master_key' => 'rate_group',
                'response_main_array_key' => 'Rate_Master_Response',
                'response_inner_array_key' => 'rate_master_Response',
            ],
        ];
        return isset($data[$svc]) ? $data[$svc] : [];
    }

}
