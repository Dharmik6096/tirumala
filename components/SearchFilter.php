<?php

namespace app\components;

use yii;

class SearchFilter {

    public function getRecord($l) {
        $label = [
            'TblDcsSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_bmc_code', 'f_route_code'],
            ],
            'TblMemberSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_bmc_code', 'f_route_code', 'f_dcs_code'],
            ],
        ];
        return isset($label[$l]) ? $label[$l] : NULL;
    }

}
