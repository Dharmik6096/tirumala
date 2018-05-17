<?php

namespace app\components;

use yii;

class SearchFilter {

    public function getRecord($l) {
        $label = [
            'TblDcsSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblMemberSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMilkCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'min_date', 'shift'],
            ],
            'TblMilkDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'min_date', 'shift'],
            ],
            'TblBmcCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ]
        ];
        return isset($label[$l]) ? $label[$l] : NULL;
    }

}
