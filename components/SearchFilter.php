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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMilkDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblBmcCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblPlantSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMccPlantSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblDcsBmcSearch' => [
                'filter' => ['f_union_code', 'f_plant_code'],
            ],
            'TblRouteMappingSearch' => [
                'filter' => ['f_union_code', 'unit'],
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
                'filter' => ['f_union_code', 'transporter_code:f_union_code'],
            ],
            'TblVehicleKmInfoSearch' => [
                'filter' => ['transporter_code', 'vehicle_code:transporter_code'],
            ],
            'TblKmWiseRateSearch' => [
                'filter' => ['vehicle_code'],
            ],
            'TblMobileOilRateMasterSearch' => [
                'filter' => ['vehicle_code'],
            ],
            'TblVehicleTransporterHeadMappingSearch' => [
                'filter' => ['vehicle_code', 'transporter_payment_head_code'],
            ],
            'TblBmcDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblDpuProductDemandSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblTabLocalSaleSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblDpuShiftEndSummarySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblPurchaseRateApplicabilitySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TestingvillageweightSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'min_date'],
            ],
            'TestingvillagequalitySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'min_date'],
            ],
            'TblMilkCollectionTempSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMACAlibrationSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'TblMACleaningSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'CollectionFarmerLocalSaleSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'TblMACAlibrationChangeSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'TblWeightCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'min_date'],
            ],
            'TblQualityCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'min_date'],
            ],
            'TblDcsMilkDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblDpuIncentiveMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMilkCollectionSummarySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblPendriveImportExportSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblCustomerMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code'],
            ],
            'EiplPacketFileLogSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'TblAndroidInstallationDetailsSearch' => [
                'filter' => ['from_date', 'to_date'],
            ],
            'TblBulkNotificationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
        ];
        return isset($label[$l]) ? $label[$l] : NULL;
    }

}
