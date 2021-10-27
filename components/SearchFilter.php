<?php

namespace app\components;

use yii;

class SearchFilter {

    public function getRecord($l) {
        $label = [
            'TblDcsSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code'],
            ],
            'TblMemberSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMilkCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
                'action' => ['index-allow', 'index']
            ],
            'TblMilkDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblBmcCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblFormulaMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductSaleSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMemberClassificationSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblVehicleMasterSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'from_date', 'to_date'],
            ],
            'TblVehicleKmInfoSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'route', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblKmWiseRateSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblMobileOilRateMasterSearch' => [
                'filter' => ['vehicle_code'],
            ],
            'TblVehicleTransporterHeadMappingSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'transporter_payment_head_code', 'from_date', 'to_date'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblQualityCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblDcsMilkDispatchTxnSearch' => [
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
            'TblBillHeadSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblHeadLoadSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblRateRecalculationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'customer_code', 'from_date', 'to_date'],
            ],
            'TblBillHeadDetailSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMilkRejectSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblVspPaymentSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblBmcTransitLossSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblVspOutstandingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblPreventCollectionDataSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code'],
            ],
            'TblProductSaleRateSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMemberPaymentSummarySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMemberPaymentRestrictSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblVehicleTripSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblBmcMilkDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblBasicTaxSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblTaxGroupSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblTaxSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblUnitsSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductGroupSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductPurchaseRateSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductRequisitionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date'],
            ],
            'TblProductReceiptSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblProductDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date'],
            ],
            'TblStaffMemberSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblStaffSalarySearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblStaffAttendanceSearch' => [
                'filter' => ['f_union_code', 'staff_member_code'],
            ],
            'TblStaffAdditionDeductionSearch' => [
                'filter' => ['f_union_code', 'staff_member_code'],
            ],
            'TblStaffSalaryProcessSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblBmcDispatchInspectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMemberProvisionalSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblSampleBottleTestingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblStaffLeaveMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblConfigMappingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'config_for', 'process_name'],
            ],
            'TblMilkVehicleEntrySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblDcsDeactiveSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMemberDeactiveSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'member_code'],
            ],
            'TblVspPaymentConfigSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblAssetTransactionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblTransporterPaymentSearch' => [
                'filter' => ['f_union_code', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblVehicleExtraKmDaywiseSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblTransporterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblBranchSearch' => [
                'filter' => ['bank_code'],
            ],
            'TblPaymentCycleSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblVillagesSearch' => [
                'filter' => ['state', 'district'],
            ],
            'TblFtpTxnLogSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
                'action' => ['list']
            ],
            'TblUserAndroidSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblCollectionPenaltyRateSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblCustomerDeactiveSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'vendor_type', 'vendor_code'],
            ],
            'TblVspBillHeadCriteriaSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMasterTransferSearch' => [
                'filter' => ['f_union_code'],
            ],
            'DpuStationDetailSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblBankPaymentLogSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'TblMasterTransferSearch' => [
                'filter' => ['master_type', 'transfer_type'],
            ],
            'TblShiftSummarySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblAnalyzerCalibrationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblAnalyzerCleaningSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblBmcCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
                'action' => ['index-allow', 'index']
            ],
            'TblMccShiftLockStagingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblAllowDcsManualCollectionRangeSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblDcsClosingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMccShiftLockSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMilkCollectionCreamBaseDataSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
        ];
        return isset($label[$l]) ? $label[$l] : NULL;
    }

}
