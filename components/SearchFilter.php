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
//            'TblBmcCollectionSearch' => [
//                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
//            ],
            'TblPlantSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMccPlantSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblDcsBmcSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code'],
            ],
            'TblRouteMappingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblFormulaMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductSaleSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
                'action' => ['index', 'product-sale-transaction'],
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
                'filter' => ['f_union_code', 'transporter_code:f_union_code'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'send_status'],
                'action' => ['index', 'repush-bulk-data'],
                'removefield' => ['index' => ['send_status']]
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date', 'billing_type'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'trip_status', 'from_date', 'to_date'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date', 'approved_status'],
                'action' => ['index', 'pending-approval']
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
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
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date', 'txn_type'],
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
                'action' => ['index-other', 'index']
            ],
            'TblMilkCollectionCreamBaseDataSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblInterfacingDeviceMappingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblLoanProductSaleDetailsSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'member_code'],
            ],
            'TblGrnSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblInventoryTransferSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblCollectionApprovalSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblRouteWiseLateArrivalSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblRecoveryParamDetailSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'from_date', 'to_date'],
            ],
            'TblTransporterTimeWisePenaltySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMccWiseTransportationCostSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblWeighBridgeDataSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblAppLockPasswordSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMilkcostParamSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date'],
            ],
            'TblGenerateReportParamSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMilkTransferSearch' => [
                'filter' => ['f_union_code', 'transfer_types'],
            ],
            'TblGateEntrySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblBmcCollectionTransferSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblVspPaymentDataConfigSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblDocumentMasterInfoSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblSchemeMasterSearch' => [
                'filter' => ['f_union_code', 'from_date', 'to_date'],
            ],
            'TblSchemeApplicationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'application_status', 'from_date', 'to_date'],
                'action' => ['index', 'pending-approval']
            ],
            'TblSchemeApplicationDisbursementSearch' => [
                'filter' => ['f_union_code', 'scheme_id', 'from_date', 'to_date'],
            ],
            'TblQtyWiseRateSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblVehicleExtraQtyDaywiseSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblPlantDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblLoanProductSaleLockingSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblProductSaleLockingSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblIndentMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
                'action' => ['index-other', 'index']
            ],
            'TblIndentDispatchSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
                'action' => ['index-other', 'index']
            ],
            'TblIndentProductSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblAnimalInspectorSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblAnimalInspectorRequestSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblTaskType' => [
                'filter' => ['f_union_code'],
            ],
            'TblFormType' => [
                'filter' => ['f_union_code'],
            ],
            'TblTaskSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'task_type', 'form_type', 'user_code', 'from_date', 'to_date', 'department_wise'],
            ],
            'TblTaskTypeSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblFormTypeSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblUserAttendanceSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'state_code', 'region_code', 'area_code', 'from_date', 'to_date', 'department_wise'],
            ],
            'TblDcsProvisionalSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
                'action' => ['index', 'pending-approval'],
            ],
            'TblBonusPaymentSummarySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblBonusPaymentPreviousDataSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblShiftTimeAndroidSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblBmcDispatchStockSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblPartyPaymentSearch' => [
                'filter' => ['party_master_code', 'payment_type', 'from_date', 'to_date'],
            ],
            'TblBannerSearch' => [
                'filter' => ['f_union_code', 'department', 'from_date', 'to_date'],
            ],
            'TblProductStockPhysicalSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblProductStockSapSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblCustomerMasterProvisionalSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code', 'from_date', 'to_date'],
                'action' => ['index', 'pending-customer-approval']
            ],
            'TblReportTxnLogSearch' => [
                'filter' => ['from_date', 'to_date'],
            ],
            'TblPaymentTransactionApprovalSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMppSurveySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date'],
            ],
            'TblNonMemberHouseHoldVisitSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblVCGMRGMemberSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblVCGMeetingMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'route_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblAlertRuleMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMilkCollectionAuditSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblIotTemperatureSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
                'action' => ['iot-temperature']
            ],
            'TblLocalMilkSaleSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblClientErpApiLogSearch' => [
                'filter' => ['erp_process_name', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMilkVehicleEntryTransactionSearch' => [
                'filter' => ['erp_process_name', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMasterHierarchySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblEiplAppFeedbackMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblComplainSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblMonthlyCreditLimitSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblAllowManualCollectionRangeSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblMilkCollectionSpecialCodeSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblPermanentHoldAmountSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblWeightScaleCalibrationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMilkAnalyzerCalibrationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMccShiftEndSummarySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMccShiftEndSummaryAdulterationTestSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblBmcCollectionAuditSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblWeightRejectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblProductRequisitionTransactionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'dispatch_center_code', 'from_date', 'to_date'],
            ],
            'TblProductDispatchTransactionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'dispatch_center_code', 'from_date', 'to_date'],
            ],
            'TblSubLedgersSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMemberBillHeadSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMemberBillCriteriaSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblEventSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblVoucherSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblVoucherTypeLedgerConfigSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblSubLedgerLedgerConfigSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblLedgerSubLedgersMappingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblLedgerMappingTaxDetailSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblLedgerMappingProductGroupSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblLedgerMappingEventSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblLedgerMappingBillHeadSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblLedgerOpeningBalanceSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblSubLedgerOpeningBalanceSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblDcsYearClosingSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblInsuranceDetailSearch' => [
                'filter' => ['insurance_master_code', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblSoftwareComplaintSearch' => [
                'filter' => ['f_union_code', 'f_u_dcs_code'],
            ],
            'TblForceSyncRequestSearch' => [
                'filter' => ['from_date', 'from_shift', 'to_date', 'to_shift', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblMilkVehicleEntryQltySearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'vehicle_code:union_vehicle', 'status:lot_quality_status', 'from_date', 'to_date'],
            ],
            'TblVehicleCleaningInspectionSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblVehicleQaInspectionSearch' => [
                'filter' => ['f_union_code', 'transporter_code:f_union_code', 'vehicle_code:transporter_code', 'from_date', 'to_date'],
            ],
            'TblMaterialMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblRawFgMaterialReceiptSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'from_date', 'to_date'],
            ],
            'TblMilkVehicleEntryQltyMergeSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'vehicle_code:union_vehicle', 'from_date', 'to_date'],
            ],
            'TblDataExchangeLogSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblGeneralPartyMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblMccRechillingDetailSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblBmcDispatchFlushStockSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMilkQualityParamRangeSearch' => [
                'filter' => ['quality_config_process_name', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'],
            ],
            'TblUserAttendanceRegularizationSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'mcc_user_code', 'from_date', 'to_date'],
            ],
            'TblDcsLocationDetailSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblSampleMilkCollectionSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblMemberAnimalTagDetailsSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'member_code'],
            ],
            'TblAnimalTreatmentRequestSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'member_code', 'from_date', 'to_date'],
            ],
            'TblMemberRateRepushLogSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblAssetGroupSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblAssetMasterSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblStoreLocationSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblAssetSetSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblMccPaymentSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblMasterTransferDataUpdateSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'],
            ],
            'TblFsDataSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblCommitteeMembersSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
            'TblVendorPaymentHoldReleaseSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],
            ],
            'TblBiplSmartSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblExcessFatSnfMasterSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'],
            ],
            'TblVoucherTypesSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblLedgerTypesSearch' => [
                'filter' => ['f_union_code'],
            ],
            'TblLedgersSearch' => [
                'filter' => ['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'],
            ],
        ];
        return isset($label[$l]) ? $label[$l] : NULL;
    }

}
