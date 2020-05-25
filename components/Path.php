<?php

/*
 *
 */

namespace app\components;

use yii\base\Component;

class Path extends Component {

    private $config = [
        '\\app\models\\' =>
        ['TblDpuCalibration', 'TblDpuCalibrationSearch', 'TblUserAndroid', 'TblUserProfile'],
        '\\app\modules\collection\models\\' =>
        ['TblMilkCollection', 'TblMilkCollectionSearch', 'TblMilkCollectionHistory', 'TblMilkDispatch', 'TblMilkDispatchSearch', 'TblMilkDispatchHistory', 'TblBmcCollection', 'TblBmcCollectionHistory', 'TblMACAlibration', 'TblMACAlibrationChange', 'TblMACleaning', 'CollectionFarmerLocalSale', 'TblDpuShiftEndSummary', 'TblMASerialNo', 'TblQualityCollection', 'TblWeightCollection', 'TblShiftSummary',
            'TblDcsMilkDispatch', 'TblDcsMilkDispatchTxn', 'TblMilkCollectionSummary', 'TblMilkCollectionSummaryHistory', 'TblDcsMilkDispatchHistory', 'TblDcsMilkDispatchTxnHistory', 'TblMilkReject', 'TblMilkRejectHistory', 'TblMilkRejectHistory'],
        '\\app\modules\geo\models\\' =>
        ['TblStates', 'TblStatesHistory',
            'TblVillagesHistory', 'TblVillages', 'TblVillageMiscellaneous', 'TblVillagesSearch',
            'TblBlocksHistory', 'TblBlocks', 'TblBlocksSearch',
            'TblSubDistrictsHistory', 'TblSubDistricts', 'TblSubDistrictsSearch',
            'TblHamletsHistory', 'TblHamlets', 'TblHamletsSearch',
            'TblDistrictsHistory', 'TblDistricts', 'TblDistrictsSearch', 'HamletImport'],
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
            'TblVoucherType', 'TblVoucherTypeHistory', 'TblLanguages', 'TblMiscellaneous', 'TblMiscellaneousHistory', 'TblMiscellaneousSearch', 'TblCustomerType', 'TblTransferType', 'TblRejectionReason'
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
            'TblSubCenter', 'TblSubcenterBm', 'TblSubcenterBmcHistory', 'TblSubCenterHistory', 'TblSubCenterLcal', 'TblSubcenterMiscellaneous', 'TblSubcenterMiscellaneousistory', 'TblSubCenterSearch', 'TblTransporterHistory',
            'TblUnions', 'TblUnionsDistrictMapping', 'TblUnionsDistrictMappingHistory', 'TblUnionsHistory', 'TblUnionsSearch', 'TblDcsConfigHistory', 'TblSocietyCodes', 'TblMccMilkType', 'TblBmcMilkType', 'TblMccMilkTypeHistory', 'TblBmcMilkTypeHistory',
            'TblCustomerMaster', 'TblBmcSilosInfo', 'TblBmcSilosInfoHistory'
        ],
        '\\app\modules\details\models\\' =>
        ['TblContactDetails', 'TblContactDetailsHistory', 'TblBankDetails', 'TblBankDetailsHistory'],
        '\\app\modules\dcsaccounting\models\\' =>
        ['TblLedgerGroup', 'TblLedger',
            'TblFinancialYear',
            'TblAssetGroup', 'TblLedgerSubledgerMapping', 'TblLedgerSubledgerMappingHistory', 'TblAssetHistory', 'TblAssetSubGroup',
            'TblUnionBillHead', 'TblUnionBillHeadHistory',
            'TblTax', 'TblTaxStateMapping', 'TblTaxStateMappingHistory', 'TblTaxDepends', 'TblTaxDependsHistory',
            'TblTaxGroup', 'TblTaxGroupHistory', 'TblBasicTax', 'TblSubLedger', 'TblTaxHistory', 'TblBasicTaxHistory', 'TblTaxDetail', 'TblTaxDetailHistory'],
        '\\app\modules\dcsoperation\models\\' =>
        ['TblRateType', 'TblRateGenerateMethod', 'TblShift',
            'TblMemberTypes', 'TblMember', 'MemberImport', 'TblMemberHistory',
            'TblMemberClassification', 'TblMemberClassificationHistory', 'MemberClassificationImport', 'TblPurchaseRateHistory', 'TblPurchaseRateApplicability', 'TblRateRecalculation', 'TblPurchaseRate', 'TblDcsPurchaseRate', 'TblPurchaseRateDetails', 'TblDcsPurchaseRateDetails', 'TblPurchaseRateBased', 'TblDcsPurchaseRateBased', 'TblPurchaseRateApplicabilityPending'],
        '\\app\modules\product\models\\' =>
        ['TblProductGroup', 'TblProductGroupHistory', 'TblProduct', 'TblProductHistory', 'TblProductRequisition', 'TblProductRequisitionHistory', 'TblProductRequisitionTransaction', 'TblProductRequisitionTransactionHistory', 'TblProductReceipt', 'TblProductReceiptHistory', 'TblProductReceiptTransaction', 'TblProductReceiptTransactionHistory', 'TblProductReceiptTaxCalculated', 'TblProductReceiptTaxCalculatedHistory', 'TblProductDispatch', 'TblProductDispatchHistory', 'TblProductDispatchTransaction', 'TblProductDispatchTransactionHistory'],
        '\\app\modules\hardwareconfigutation\models\\' =>
        ['TblDeviceManufacturer', 'TblInterfacingDevice', 'TblInterfacingDeviceHistory',],
        '\\app\components\\' =>
        ['DcsImportStrategy', 'SubCenterImportStrategy', 'MemberImportStrategy', 'CommonImportStrategy', 'DispatchImportStrategy', 'DpuIncentiveImportStrategy', 'DcsImportUpdateStrategy', 'DpuPasswordImportStrategy', 'BillHeadDetailImportStrategy'],
        '\\app\modules\general\models\\' => ['TblBloodgroup', 'TblGender', 'TblQualification', 'TblBmcType', 'TblReligion', 'TblSchemeType', 'TblOrganisationType', 'TblRelationship', 'TblSocietyVendor', 'TblDpuIncentiveMaster', 'TblDpuIncentiveMasterHistory', 'TblDepartment', 'TblCollectionIncentiveDeductionHistory'],
        '\\app\modules\email\models\\' => ['TblEmailRuleMaster', 'TblEmailProcessMaster'],
        '\\app\modules\transporter\models\\' => ['TblTransporter', 'TblFuelTypeMaster', 'TblVehicleMaster', 'TblBillingType', 'TblTransporterPaymentHead'],
        '\\app\modules\creamy\models\\' =>
        ['TblDpuProductDemandCreamy', 'TblMACAlibrationCreamy', 'TblMACAlibrationChangeCreamy', 'TblMACleaningCreamy', 'CollectionFarmerLocalSaleCreamy', 'MasterfarmerCreamy', 'TblDcsPortalCreamy', 'MastervillageCreamy', 'CollectionvillageCreamy', 'TblDpuShiftEndSummaryCreamy', 'TblMASerialNoCreamy'],
        '\\app\modules\syncutility\models\\' => ['TblSentbox', 'TblInbox', 'TblSyncLog', 'TblSentboxClone'],
        '\\app\modules\configuration\models\\' =>
        ['TblDcsGeneralConfig', 'TblMilkCollectionConfig', 'TblDcsGeneralConfigHistory', 'TblMilkCollectionConfigHistory', 'TblConfigResult', 'TblConfig', 'TblUnionConfigResult', 'TblUnionConfigResultHistory'],
        '\\app\modules\installation\models\\' => ['TblAndroidInstallation', 'TblAndroidInstallationDetails', 'TblAndroidInstallationDetailsHistory'],
        '\\app\modules\setting\models\\' => ['TblDpuPasswords', 'TblDPUPasswordsHistory'],
        '\\app\modules\sms\models\\' => ['TblApiMaster', 'TblBulkNotification', 'TblBulkNotificationHistory'],
        '\\app\modules\vsp\models\\' => ['TblGeneralFormula', 'TblGeneralFormulaHistory', 'TblCriteriaKeywordMapping', 'TblBillHead', 'TblBillHeadHistory', 'TblBillHeadDefault', 'TblBillHeadDetail'],
        '\\app\modules\staffmanagement\models\\' => ['TblStaffMember', 'TblStaffMemberHistory', 'TblStaffAttendance', 'TblStaffAttendanceHistory', 'TblStaffSalaryTransaction', 'TblStaffSalaryTransactionHistory', 'TblStaffSalaryHistory', 'TblStaffInstallment', 'TblStaffInstallmentHistory', 'TblStaffMemberDesignationHistory', 'TblStaffSalaryProcess', 'TblStaffSalaryProcessTransaction', 'TblStaffSalaryProcessHistory', 'TblStaffSalaryProcessTransactionHistory'],
        '\\app\modules\payment\models\\' => ['TblProductSaleDetails', 'TblLoanProductSaleDetails', 'TblLoanProduct', 'TblVspOutstanding', 'TblProductSaleTaxCalculated', 'TblProductSaleTaxCalculatedHistory'],
        '\\app\modules\tankermovement\models\\' => ['TblQtyDiffType'],
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
