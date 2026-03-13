<?php

namespace app\components;

use yii;
use yii\base\Component;

class DefaultValue extends Component {

    public function getDefaults(&$model, $eiplcode = '') {
        $defaults = $this->processDefaultsArray();
        $clientCode = $eiplcode ?: Yii::$app->session->get('eiplCode');
        $modelClass = (new \ReflectionClass($model))->getShortName();
        $clientDefaults = $defaults[$clientCode][$modelClass] ?? $defaults['EIPLCOMMON'][$modelClass] ?? [];

        foreach ($clientDefaults as $attr => $val) {
            $model->$attr = $val;
        }
    }

    public static function processDefaultsArray() {
        return [
            'EIPLCOMMON' => [
                'TblDcs' => [
                // Common defaults
                ],
                'TblBmcDispatchFlushStock' => [
                    'milk_type_code' => 3,
                    'milk_quality_type_code' => 1,
                ],
                'TblMilkVehicleEntryQlty' => [
                    'sample_time' => date('H:i'),
                    'sample_datetime' => date('Y-m-d'),
                ],
                'TblMember' => [
                    'animal_type_code' => 1,
                ],
                'TblMemberRateRepushLogSearch' => [
                    'dpu_type' => 91,
                ],
                'TblVehicleTrip' => [
                    'is_auto_trip' => 1,
                ],
            ],
            'ABT' => [
                'TblDcs' => [
                    'vendor' => 'EIPL',
                    'dpu_type' => 0,
                    'machine_owned' => 2
                ],
            ],
            'DODLA' => [
                'TblVehicleMaster' => [
                    'billing_method' => 'fix_rent_daily',
                    'vehicle_use_type' => 1,
                    'fuel_type_code' => 2,
                    'flag_wef_date' => date('Y-m-d'),
                    'billing_qty_flag' => 1,
                ],
            ],
            'AMULAMCS' => [
                'TblBulkNotification' => [
                    'login_type' => 'MEMBER',
                ],
            ],
            'COMFED' => [
                'TblMember' => [
                    'animal_type_code' => 3,
                ],
            ],
            'CARGILL' => [
                'TblVehicleTrip' => [
                    'is_auto_trip' => 0,
                ],
            ],
            'KOTMALE' => [
                'TblVehicleTrip' => [
                    'is_auto_trip' => 0,
                ],
            ],
        ];
    }

}
