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
            ]
        ];
        return isset($data[$svc]) ? $data[$svc] : [];
    }

}
