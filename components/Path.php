<?php

/*
 *
 */

namespace app\components;

use yii\base\Component;

class Path extends Component {

    private $config = [
        '\\app\models\\' =>
            ['TblDpuCalibration', 'TblDpuCalibrationSearch', 'TblUserProfile'],
        '\\app\modules\collection\models\\' =>
            ['TblMilkCollection', 'TblMilkCollectionSearch', 'TblMilkCollectionHistory', 'TblMilkDispatch', 'TblMilkDispatchSearch', 'TblMilkDispatchHistory', 'TblBmcCollection', 'TblBmcCollectionHistory', 'TblMACAlibration', 'TblMACAlibrationChange', 'TblMACleaning', 'CollectionFarmerLocalSale', 'TblDpuShiftEndSummary', 'TblMASerialNo', 'TblQualityCollection', 'TblWeightCollection', 'TblShiftSummary',
            'TblDcsMilkDispatch', 'TblDcsMilkDispatchTxn', 'TblMilkCollectionSummary', 'TblMilkCollectionSummaryHistory', 'TblDcsMilkDispatchHistory', 'TblDcsMilkDispatchTxnHistory', 'TblMilkReject', 'TblMilkRejectHistory', 'TblMilkRejectHistory', 'TblCollectionDataAliasReject', 'TblBulkDataImport', 'TblBmcCollectionNotExist', 'TblMilkCollectionNotExists', 'TblCollectionPenaltyRate', 'TblCollectionPenaltyRateApplicability', 'TblCollectionPenaltyType',
            'TblAnalyzerCalibration', 'TblAnalyzerCleaning', 'TblMilkCollectionAudit', 'TblMilkCollectionAuditSearch', 'TblDcsClosing', 'TblDcsClosingHistory', 'TblMccShiftLockStaging', 'TblMccShiftLock', 'TblCollectionApproval', 'TblCollectionApprovalHistory', 'TblWeighBridgeData', 'TblMilkcostParam', 'TblMilkTransfer', 'TblBmcCollectionTransfer', 'TblWeightRejection', 'TblWeightRejectionHistory', 'TblAnalyzerSerialNo', 'TblIotTemperature', 'TblIotTemperatureSearch', 'TblBulkBillingImport', 'TblAllowManualCollectionRange', 'TblAllowManualCollectionRangeHistory', 'TblMilkCollectionSpecialCode', 'TblMilkCollectionSpecialCodeHistory', 'TblWeightScaleCalibration', 'TblWeightScaleCalibrationHistory', 'TblBmcCollectionAudit', 'TblBmcCollectionAuditHistory', 'TblMilkAnalyzerCalibration', 'TblMilkAnalyzerCalibrationHistory', 'TblMccShiftEndSummary', 'TblMccShiftEndSummaryHistory', 'TblMccShiftEndSummaryAdulterationTest', 'TblMccShiftEndSummaryAdulterationTestHistory', 'TblSampleMilkCollection', 'TblSampleMilkCollectionHistory'],
        '\\app\modules\geo\models\\' =>
            ['TblStates', 'TblStatesHistory',
            'TblVillagesHistory', 'TblVillages', 'TblVillageMiscellaneous', 'TblVillagesSearch',
            'TblBlocksHistory', 'TblBlocks', 'TblBlocksSearch',
            'TblSubDistrictsHistory', 'TblSubDistricts', 'TblSubDistrictsSearch',
            'TblHamletsHistory', 'TblHamlets', 'TblHamletsSearch',
            'TblDistrictsHistory', 'TblDistricts', 'TblDistrictsSearch', 'HamletImport', 'TblArea', 'TblRegion', 'TblAreaBmcMapping'],
        '\\app\modules\globalmaster\models\\' =>
            ['TblDcsTypes', 'TblDcsTypesHistory',
            'TblCasteCategory', 'TblCasteCategoryHistory', 'TblCapacity',
            'TblUnits', 'TblMilkType',
            'TblLandUnit', 'TblLandUnitHistory',
            'TblDesignation', 'TblDesignationHistory',
            'TblMeetingType', 'TblMeetingTypeHistory',
            'TblAnimalType', 'TblAnimalTypeHistory',
            'TblVehicleType', 'TblVehicalTypesHistory',
            'TblMilkQualityType', 'TblMilkQualityTypeHistory',
            'TblLedgerType', 'TblLedgerTypeHistory',
            'TblSalaryHeads', 'TblSalaryHeadsHistory',
            'TblVoucherType', 'TblVoucherTypeHistory', 'TblLanguages', 'TblMiscellaneous', 'TblMiscellaneousHistory', 'TblMiscellaneousSearch', 'TblCustomerType', 'TblTransferType', 'TblRejectionReason', 'TblRejectionResponsibility',
            'TblDeviceMaster', 'TblDeviceMasterMapping', 'TblRateClass',
        ],
        '\\app\modules\organisation\models\\' =>
            ['TblBanks', 'TblBanksDistrictsMapping', 'TblBanksDistrictsMappingHistory', 'TblBanksHistory', 'TblBanksSearch',
            'TblBranch', 'TblBranchHistory', 'TblBranchSearch',
            'TblCollectionPoint', 'TblCollectionPointHistory', 'TblCollectionPointSearch',
            'TblDcs', 'TblDcsBmc', 'TblDcsBmcHistory', 'TblDcsBmcSearch', 'TblDcsChillingCenter', 'TblDcsHistory', 'TblDcsMiscellaneous', 'TblDcsSearch', 'TblDcsVillageMapping', 'TblDcsVillageMappingHistory',
            'TblFederations', 'TblFederationsHistory', 'TblFederationsSearch', 'TblFederationsStateMapping', 'TblFederationsStateMappingHistory',
            'TblManufacturer', 'TblDcsMilkType', 'TblSubCenterMilkType',
            'BankImport', 'DcsImport', 'SubCenterImport', 'BranchImport', 'TransporterImport', 'MccPlantImport', 'PlantImport', 'UnionImport', 'BmcImport',
            'TblMccPlant', 'TblMccPlantHistory', 'TblPlant', 'TblPlantHistory', 'TblPlantProductGroup', 'TblPlantProductGroupHistory', 'TblPlantProductGroupDetails', 'TblPlantProductGroupDetailsHistory',
            'TblRoutes', 'TblRoutesHistory', 'TblRoutesLocl', 'TblRoutesSearch', 'TblRouteMappingSources', 'TblRouteMappingSourcesHistory', 'TblRouteMapping', 'TblRouteMappingHistory',
            'TblSubCenter', 'TblSubcenterBm', 'TblSubcenterBmcHistory', 'TblSubCenterHistory', 'TblSubCenterLcal', 'TblSubcenterMiscellaneous', 'TblSubcenterMiscellaneousistory', 'TblSubCenterSearch',
            'TblUnions', 'TblUnionsDistrictMapping', 'TblUnionsDistrictMappingHistory', 'TblUnionsHistory', 'TblUnionsSearch', 'TblDcsConfigHistory', 'TblSocietyCodes', 'TblMccMilkType', 'TblBmcMilkType', 'TblMccMilkTypeHistory', 'TblBmcMilkTypeHistory',
            'TblCustomerMaster', 'TblBmcSilosInfo', 'TblBmcSilosInfoHistory', 'TblDcsDeactive', 'TblCustomerMasterHistory', 'TblCustomerDeactive', 'TblDcsVendorStatus', 'TblAllowDcsManualCollectionRange', 'TblChannelMaster', 'TblAnimalInspector', 'TblAnimalInspectorApplicability', 'TblAnimalInspectorRequest', 'TblCustomerMasterProvisional', 'TblMasterHierarchy', 'TblOrganizationLatlong', 'TblOrganizationLatlongSearch', 'TblOrganizationLatlongHistory', 'TblOrganizationLatlongApplicability', 'TblBankVerification', 'TblBankVerificationHistory', 'TblPlantDockMapping', 'TblPlantDockMappingHistory',
            'TblBmcChillerInfo', 'TblBmcChillerInfoHistory', 'TblDcsProvisional', 'TblDcsProvisionalHistory', 'TblCustomerMasterProvisionalHistory'
        ],
        '\\app\modules\details\models\\' =>
            ['TblContactDetails', 'TblContactDetailsHistory', 'TblBankDetails', 'TblBankDetailsHistory'],
        '\\app\modules\dcsaccounting\models\\' =>
            ['TblLedgerGroup', 'TblLedger',
            'TblFinancialYear',
            'TblLedgerSubledgerMapping', 'TblLedgerSubledgerMappingHistory', 'TblAssetHistory', 'TblAssetSubGroup',
            'TblUnionBillHead', 'TblUnionBillHeadHistory',
            'TblTax', 'TblTaxStateMapping', 'TblTaxStateMappingHistory', 'TblTaxDepends', 'TblTaxDependsHistory',
            'TblTaxGroup', 'TblTaxGroupHistory', 'TblBasicTax', 'TblSubLedger', 'TblTaxHistory', 'TblBasicTaxHistory', 'TblTaxDetail', 'TblTaxDetailHistory', 'TblLedgerTypes', 'TblLedgerTypesHistory', 'TblLedgerGroups', 'TblLedgerGroupsHistory', 'TblVoucherTypes', 'TblVoucherTypesHistory', 'TblVoucherTypeLedgerConfig', 'TblVoucherTypeLedgerConfigHistory', 'TblVoucher', 'TblVoucherHistory', 'TblSubLedgers', 'TblSubLedgersHistory', 'TblSubLedgerOpeningBalance', 'TblSubLedgerOpeningBalanceHistory', 'TblSubLedgerLedgerConfig', 'TblSubLedgerLedgerConfigHistory', 'TblMemberBillHead', 'TblMemberBillHeadHistory', 'TblMemberBillCriteria', 'TblMemberBillCriteriaHistory', 'TblLedgers', 'TblLedgersHistory', 'TblLedgerSubLedgersMapping', 'TblLedgerSubLedgersMappingHistory', 'TblLedgerOpeningBalance', 'TblLedgerOpeningBalanceHistory', 'TblLedgerMappingTaxDetail', 'TblLedgerMappingTaxDetailHistory', 'TblLedgerMappingProductGroup', 'TblLedgerMappingProductGroupHistory', 'TblLedgerMappingEvent', 'TblLedgerMappingEventHistory', 'TblLedgerMappingBillHead', 'TblLedgerMappingBillHeadHistory', 'TblEvent', 'TblEventHistory', 'TblDcsYearClosing', 'TblDcsYearClosingHistory'],
        '\\app\modules\dcsoperation\models\\' =>
            ['TblRateType', 'TblRateGenerateMethod', 'TblShift',
            'TblMemberTypes', 'TblMember', 'MemberImport', 'TblMemberHistory',
            'TblMemberClassification', 'TblMemberClassificationHistory', 'MemberClassificationImport', 'TblPurchaseRateHistory', 'TblPurchaseRateApplicability', 'TblRateRecalculation', 'TblPurchaseRate', 'TblDcsPurchaseRate', 'TblPurchaseRateDetails', 'TblDcsPurchaseRateDetails', 'TblPurchaseRateBased', 'TblDcsPurchaseRateBased', 'TblPurchaseRateApplicabilityPending', 'TblMemberProvisional', 'TblMemberDeactive', 'TblSendSmsCount', 'TblDcsPurchaseRateApplicabilityAlias', 'TblPurchaseRateApplicabilityAlias', 'TblSchemeRate', 'TblSchemeRateApplicability', 'TblSchemeRateMcc', 'TblSchemeRateApplicabilityAlias'
            , 'TblMemberAnimalDetails', 'TblMemberAnimalDetailsHistory', 'TblMemberAnimalType', 'TblMemberFamilyDetails', 'TblMemberFamilyDetailsHistory', 'TblMemberProvisionalAnimalDetails', 'TblMemberProvisionalAnimalDetailsHistory', 'TblMemberProvisionalFamilyDetails', 'TblMemberProvisionalFamilyDetailsHistory', 'TblUnionShareConfig', 'TblMemberProvisionalShareDetails', 'TblMemberProvisionalShareDetailsHistory', 'TblMemberShareDetails', 'TblMemberShareDetailsHistory', 'TblMemberProvisionalHistory', 'TblMemberShareDeposit', 'TblMemberShareDepositHistory', 'TblMemberIncentive', 'TblMemberIncentiveHistory', 'TblMemberSpecialCode', 'TblLocalMilkSale', 'TblLocalMilkSaleSearch', 'TblLocalMilkRate', 'TblLocalMilkRateSearch', 'TblLocalMilkSaleRate', 'TblLocalMilkSaleRateSearch', 'TblLocalMilkSaleRateHistory', 'TblLocalMilkRateHistory', 'TblLocalMilkSaleHistory', 'TblDcsLocationDetail', 'TblDcsLocationDetailHistory'],
        '\\app\modules\product\models\\' =>
            ['TblProductGroup', 'TblProductGroupHistory', 'TblProduct', 'TblProductHistory', 'TblProductRequisition', 'TblProductRequisitionHistory', 'TblProductRequisitionTransaction', 'TblProductRequisitionTransactionHistory', 'TblProductReceipt', 'TblProductReceiptHistory', 'TblProductReceiptTransaction', 'TblProductReceiptTransactionHistory', 'TblProductReceiptTaxCalculated', 'TblProductReceiptTaxCalculatedHistory', 'TblProductDispatch', 'TblProductDispatchHistory', 'TblProductDispatchTransaction', 'TblProductDispatchTransactionHistory', 'TblProductSaleRate', 'TblProductSaleRateHistory', 'TblProductPurchaseRate', 'TblProductPurchaseRateApplicability', 'TblProductSaleRateApplicability', 'TblProductSaleRateApplicabilityHistory', 'TblProductStock', 'TblProductStockHistory', 'TblProductStockTransaction', 'TblProductStockTransactionHistory', 'TblVendorMaster', 'TblInventoryTransfer', 'TblInventoryTransferTxn',
            'TblGrn', 'TblGrnTxn', 'TblPlantDispatch', 'TblPlantDispatchTxn', 'TblIndentMaster', 'TblIndentDispatch', 'TblIndentDispatchTransaction', 'TblIndentProduct', 'TblProductStockPhysical', 'TblProductStockSap', 'TblGrnInstallment', 'TblPlantDispatchTxnHistory', 'TblPlantDispatchHistory', 'TblDispatchCenter', 'TblDispatchCenterType', 'TblGeneralPartyMaster', 'TblGeneralPartyMasterHistory'],
        '\\app\modules\hardwareconfigutation\models\\' =>
            ['TblDeviceManufacturer', 'TblInterfacingDevice', 'TblInterfacingDeviceHistory',],
        '\\app\components\\' =>
            ['DcsImportStrategy', 'SubCenterImportStrategy', 'MemberImportStrategy', 'CommonImportStrategy', 'DispatchImportStrategy', 'DpuIncentiveImportStrategy', 'DcsImportUpdateStrategy', 'DpuPasswordImportStrategy', 'BillHeadDetailImportStrategy', 'AssetDetailImportStrategy', 'BulkImportStrategy', 'BackGroundDataImportStrategy', 'MemberHouseHoldSurveyImportStrategy', 'MasterHerarchyImportStrategy', 'MemberDeactivateImportStrategy'],
        '\\app\modules\general\models\\' => ['TblBloodgroup', 'TblGender', 'TblQualification', 'TblBmcType', 'TblReligion', 'TblSchemeType', 'TblOrganisationType', 'TblRelationship', 'TblSocietyVendor', 'TblDpuIncentiveMaster', 'TblDpuIncentiveMasterHistory', 'TblDepartment', 'TblCollectionIncentiveDeductionHistory', 'TblViewHistoryTableList', 'TblApprovalStages', 'TblApprovalStagesDetail', 'TblApprovalStagesProcess', 'TblProcessApproval', 'TblBanner', 'TblBannerApplicability'],
        '\\app\modules\email\models\\' => ['TblEmailRuleMaster', 'TblEmailProcessMaster'],
        '\\app\modules\transporter\models\\' => ['TblTransporter', 'TblFuelTypeMaster', 'TblVehicleMaster', 'TblBillingType', 'TblTransporterPaymentHead', 'TblVehicleMasterHistory', 'TblVehicleKmInfo', 'TblVehicleExtraKmDaywise', 'TblVehicleExtraQtyDaywise', 'TblFuelRateMaster', 'TblTransporterHistory', 'TblKmWiseRate', 'TblQtyWiseRate', 'TblLocationWiseKmDetail', 'TblLocationWiseKmDetailHistory', 'TblRecoveryParamDetail', 'TblRecoveryParamDetailHistory', 'TblVehicleVtsKm', 'TblTransporterTimeWisePenalty', 'TblTransporterTimeWisePenaltyHistory', 'TblMccWiseTransportationCost', 'TblMccWiseTransportationCostHistory', 'TblRouteWiseLateArrival', 'TblVehicleCompartmentDetail', 'TblVehicleCompartmentDetailHistory'],
        '\\app\modules\creamy\models\\' =>
            ['TblDpuProductDemandCreamy', 'TblMACAlibrationCreamy', 'TblMACAlibrationChangeCreamy', 'TblMACleaningCreamy', 'CollectionFarmerLocalSaleCreamy', 'MasterfarmerCreamy', 'TblDcsPortalCreamy', 'MastervillageCreamy', 'CollectionvillageCreamy', 'TblDpuShiftEndSummaryCreamy', 'TblMASerialNoCreamy'],
        '\\app\modules\syncutility\models\\' => ['TblSentbox', 'TblInbox', 'TblSyncLog', 'TblSentboxClone', 'TblForceSyncRequest', 'TblForceSyncRequestHistory', 'TblForceSyncTableList'],
        '\\app\modules\configuration\models\\' => ['TblDcsGeneralConfig', 'TblMilkCollectionConfig', 'TblDcsGeneralConfigHistory', 'TblMilkCollectionConfigHistory', 'TblConfigResult', 'TblConfig', 'TblUnionConfigResult', 'TblUnionConfigResultHistory', 'TblGenerateReportParam',
            'TblAppLockConfig', 'TblAppLockConfigDetail', 'TblAppLockConfigResult', 'TblDeviceConfigMaster', 'TblDeviceConfigMasterTxn', 'TblDeviceConfigTemplate', 'TblDeviceConfigTemplateDetails', 'TblDeviceConfigTempMapping', 'TblShiftTimeExceed', 'TblMilkQualityParamRange', 'TblMilkQualityParamRangeHistory'],
        '\\app\modules\installation\models\\' => ['TblAndroidInstallation', 'TblAndroidInstallationDetails', 'TblAndroidInstallationDetailsHistory', 'TblAppStartup', 'TblAction', 'TblRole', 'TblRoleActionMapping', 'TblUserAndroid', 'TblUserRoleMapping', 'TblUserDownloadAck', 'TblAppLockPassword'],
        '\\app\modules\setting\models\\' => ['TblDpuPasswords', 'TblDPUPasswordsHistory'],
        '\\app\modules\sms\models\\' => ['TblApiMaster', 'TblBulkNotification', 'TblBulkNotificationHistory', 'TblSmsFailLog', 'TblAlertRuleMaster', 'TblAlertRuleMapping', 'TblAlertRuleMappingHistory'],
        '\\app\modules\vsp\models\\' => ['TblGeneralFormula', 'TblGeneralFormulaHistory', 'TblCriteriaKeywordMapping', 'TblBillHead', 'TblBillHeadHistory', 'TblBillHeadDefault', 'TblBillHeadDetail', 'TblVspBillHeadCriteria', 'TblVspBillHeadCriteriaSlabs', 'TblVspBillHeadCriteriaApplicability', 'TblMccBillHead', 'TblMccBillHeadApplicability', 'TblMccBillHeadDefault', 'TblMccGeneralFormula', 'TblMccBillHeadDetail', 'TblBillHeadTransaction', 'TblBillHeadTransactionHistory', 'TblBillHeadInstallment', 'TblBillHeadInstallmentHistory', 'TblTransitRecovery', 'TblTransitRecoveryHistory'],
        '\\app\modules\staffmanagement\models\\' => ['TblStaffMember', 'TblStaffMemberHistory', 'TblStaffAttendance', 'TblStaffAttendanceHistory', 'TblStaffSalaryTransaction', 'TblStaffSalaryTransactionHistory', 'TblStaffSalaryHistory', 'TblStaffInstallment', 'TblStaffInstallmentHistory', 'TblStaffMemberDesignationHistory', 'TblStaffSalaryProcess', 'TblStaffSalaryProcessTransaction', 'TblStaffSalaryProcessHistory', 'TblStaffSalaryProcessTransactionHistory', 'TblStaffAdditionDeduction', 'TblStaffMemberFamilyDetails', 'TblStaffMemberFamilyDetailsHistory', 'TblStaffSalaryHoldDue', 'TblStaffSalaryHoldDueHistory', 'TblStaffLeaveMaster', 'TblStaffLeaveMasterHistory'],
        '\\app\modules\payment\models\\' => ['TblProductSaleTransaction', 'TblLoanProductSaleDetails', 'TblLoanProduct', 'TblVspOutstanding', 'TblProductSaleTaxCalculated', 'TblProductSaleTaxCalculatedHistory', 'TblProductSaleTransactionHistory', 'TblProductSale', 'TblVspPaymentConfig', 'TblMemberPaymentInstallment', 'TblMemberPaymentRecovery', 'TblVehicleTollDetail', 'TblVehicleTollDetailHistory', 'TblVspPaymentDataConfig', 'TblVspPaymentDataConfigHistory', 'TblProductSaleLocking', 'TblLoanProductSaleLocking', 'TblMccRemunerationSummary', 'TblMccPayment', 'TblMccPaymentTransaction', 'TblMemberPaymentRestrict', 'TblPaymentHoldReason', 'TblBonusPaymentPreviousData', 'TblBonusPaymentPreviousDataSearch', 'TblMonthlyCreditLimit', 'TblMonthlyCreditLimitHistory', 'TblMonthlyCreditLimitSearch', 'TblPaymentTransactionSearch', 'TblPaymentCycleApplicability', 'TblMccRechillingDetail', 'TblMccRechillingDetailHistory'],
        '\\app\modules\tankermovement\models\\' => ['TblQtyDiffType', 'TblConfigTxnResult', 'TblBmcDispatchInspection', 'TblBmcMilkDispatch', 'TblBmcMilkDispatchTxn', 'TblBmcDispatchStock', 'TblVehicleTrip', 'TblPreCollectionCheck', 'TblMilkVehicleEntry', 'TblMilkVehicleEntryTransaction', 'TblPartyMaster', 'TblBmcMilkDispatchHistory', 'TblBmcMilkDispatchTxnHistory', 'TblMilkVehicleEntryHistory', 'TblPaymentHead', 'TblMilkVehicleEntryReject', 'TblMilkVehicleEntryTransactionReject', 'TblMilkVehicleEntryQlty', 'TblMilkVehicleEntryQltyHistory', 'TblVehicleCleaningInspection', 'TblVehicleCleaningInspectionHistory', 'TblConfigTxnResultHistory', 'TblVehicleQaInspection', 'TblVehicleQaInspectionHistory', 'TblMaterilMaster', 'TblMaterilMasterHistory', 'TblRawFgMaterialReceipt', 'TblRawFgMaterialReceiptHistory', 'TblMilkVehicleEntryQltyMerge', 'TblMilkVehicleEntryQltyMergeHistory', 'TblVehicleTripHistory', 'TblVehicleTripDetail', 'TblVehicleTripDetailHistory', 'TblBmcDispatchFlushStock', 'TblBmcDispatchFlushStockHistory'],
        '\\app\modules\assetmanagement\models\\' => ['TblAssetDetail', 'TblStoreLocation', 'TblAssetGroup', 'TblAssetMaster', 'TblAssetMasterHistory', 'TblAssetGroupHistory', 'TblAssetDetailHistory', 'TblStoreLocationHistory', 'TblStoreLocationType', 'TblAssetTransaction', 'TblAssetTransactionHistory', 'TblAssetSet', 'TblAssetSetHistory', 'TblAssetBom', 'TblAssetBomHistory', 'TblAssetBomSearch', 'TblAssetDetailBom', 'TblAssetDetailBomHistory', 'TblAssetDetailBomSearch', 'TblAssetVerificationData', 'TblAssetVerificationDataHistory', 'TblAssetVerificationDataSearch'],
        '\\app\modules\complaint\models\\' => ['TblComplainProduct', 'TblComplainEscalation', 'TblComplainEscalationTxn', 'TblComplainType', 'TblComplain', 'TblComplainActivity', 'TblComplainProblem', 'TblComplainSpare', 'TblComplainEscalationTxnDetail', 'TblSoftwareComplaint', 'TblSoftwareComplaintHistory', 'TblSoftwareComplaintTxn', 'TblSoftwareComplaintTxnHistory'],
        '\\app\modules\import\models\\' => ['BackGroundDataImport'],
        '\\app\modules\bkgprocess\models\\' => ['TblDataExchangeConfig'],
        '\\app\modules\dynamicreport\models\\' => ['TblReportList'],
        '\\app\modules\welfarescheme\models\\' => ['TblSchemeApplication', 'TblSchemeApplicationApproval', 'TblSchemeApplicationDisbursement', 'TblSchemeApplicationDocuments', 'TblSchemeApprovalStages', 'TblSchemeCriteria', 'TblSchemeDocumentMapping', 'TblDocumentMasterInfo', 'TblSchemeMaster'],
        '\\webvimark\modules\UserManagement\models\\' => ['User'],
        '\\app\modules\usermanagement\models\\' => ['TblUserEngineerMapping', 'TblUserEngineerMappingHistory'],
        '\\app\modules\tms\models\\' => ['TblFormType', 'TblTaskType', 'TblTask', 'TblTaskActivity'],
        '\\app\modules\document\models\\' => ['TblDocumentMapping', 'TblAttachment', 'TblDocumentMasterType'],
        '\\app\modules\webservice\eipl\models\\' =>
            ['TblEiplAppLogin'],
        '\\app\modules\feedback\models\\' => ['TblNonMemberHouseHoldVisit', 'TblNonMemberHouseHoldCurrentPouring', 'TblNonMemberHouseHoldVisitHistory', 'TblMppSurveyGeneralInfo', 'TblMppSurveyGeneralInfoSearch'],
        '\\app\modules\insurance\models\\' => ['TblInsuranceDetail', 'TblInsuranceDetailSearch', 'TblInsuranceDetailSummary', 'TblInsuranceDetailSummaryHistory', 'TblInsuranceDetailSummarySearch', 'TblInsuranceMaster', 'TblInsuranceMasterHistory', 'TblInsuranceMasterSearch'],
        '\\app\modules\usermanagement\models\\' => ['TblEiplAppUser', 'TblEiplAppUserHistory', 'TblEiplUserOrganizationMapping'],
        '\\app\modules\clienterp\models\\' => ['TblDataExchangeLog', 'TblDataExchangeLogHistory'],
    ];

    public function get($model) {
        foreach ($this->config as $key => $value) {
            if (in_array($model, $value))
                return $key;
        }
        return FALSE;
    }

    public function getModel($model) {
        $model_name = $this->get($model);
        if ($model_name) {
            $model_name = $model_name . $model;
            return new $model_name();
        }
        return FALSE;
    }

    public function define($model) {
        $namespace = $this->get($model);
        return ltrim($namespace, '\\') . $model;
    }

}
