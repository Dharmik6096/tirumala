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
            ],
            'TblPlantSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMccPlantSearch' => [
                'filter' => ['f_union_code', 'f_plant_code'],
            ],
            'TblDcsBmcSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code'],
            ],
            'TblRouteMappingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'unit'],
            ],
            'TblFormulaMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductSaleSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblDcsPaymentCycleSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMemberDownloadSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblDpuCalibrationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'min_date', 'shift'],
            ],
            'TblCleaningDpuSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'min_date', 'Shift'],
            ],
            'TblFatSnfThresholdSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'shift_id'],
            ],
            'TblShiftTimeSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'shift_code'],
            ],
            'TblFuelRateMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMemberClassificationSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblVehicleMasterSearch' => [
                'filter' => ['f_union_code', 'transporter_code'],
            ],
        ];
        return isset($label[$l]) ? $label[$l] : NULL;
    }

}
