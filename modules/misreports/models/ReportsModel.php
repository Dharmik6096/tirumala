<?php

namespace app\modules\misreports\models;

use Yii;
use yii\base\Model;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;

class ReportsModel extends Model {

    public $year, $month, $union_code, $plant_code, $mcc_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $report_type, $date, $shift;
    public $calibration_day, $p_date, $customer_code, $member_code, $p_organization_type, $p_purchase_rate_code, $rate_type, $customer_type, $vendor_code, $payment_cycle_code, $bank_type, $report_status, $member_type, $route_code;
    public $no_of_payment_cycle, $output_type, $store_location_type, $asset_code, $sap_code, $sr_no, $main_customer_type, $transporter_code, $vehicle_code, $originating_type, $report_collection_type, $type_wise_report, $route_type_trans, $product_code;
    public $org_type, $product_type, $module_type, $action_perform, $channel_code, $upload_ftp_file, $sap_file, $trip_code, $grn_no, $plant_register_type, $bill_head_code, $trip_status, $milk_sale_on, $billing_on, $dispatch_center_type, $dispatch_center, $dispatch_type;
    public $state_code, $region_code, $area_code, $user_code, $report_req_status, $login_user_code, $payment_type, $user_login_type, $as_on_date, $from_value, $to_value, $basis_on, $top_collection_on, $param_type, $top_value, $milk_type, $f_spr_date, $t_spr_date, $f_cmpr_date, $t_cmpr_date, $animal_type, $current_status, $login_type_report;
    public $rate_cal_for, $insurance_master_code, $operation_type, $date_payment_cycle, $store_location_code, $is_groupbyserial, $store_location_type_all, $product_group_code, $header_reference, $assignment;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_code', 'p_purchase_rate_code', 'payment_cycle_code', 'vendor_code', 'customer_type', 'route_code', 'main_customer_type', 'transporter_code', 'vehicle_code', 'product_type', 'animal_type'], 'default', 'value' => 0],
                [['year', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'p_date', 'customer_code', 'member_code', 'rate_type', 'customer_type', 'vendor_code', 'payment_cycle_code', 'report_status', 'member_type', 'route_code', 'no_of_payment_cycle', 'output_type', 'report_type', 'store_location_type', 'asset_code', 'sap_code', 'sr_no', 'main_customer_type', 'transporter_code', 'vehicle_code', 'originating_type', 'report_collection_type', 'type_wise_report', 'route_type_trans', 'product_code', 'org_type', 'product_type', 'module_type', 'action_perform', 'channel_code', 'upload_ftp_file', 'sap_file', 'channel_code', 'month', 'state_code', 'region_code', 'area_code', 'report_req_status', 'login_user_code', 'user_code', 'as_on_date', 'basis_on', 'top_collection_on', 'param_type', 'top_value', 'milk_type', 'f_spr_date', 't_spr_date', 'f_cmpr_date', 't_cmpr_date', 'animal_type', 'current_status', 'login_type_report', 'user_login_type', 'insurance_master_code', 'operation_type', 'bank_type', 'trip_status', 'trip_code', 'milk_sale_on', 'billing_on', 'dispatch_center_type', 'dispatch_center', 'dispatch_type', 'date_payment_cycle', 'bank_type', 'trip_status', 'trip_code', 'date_payment_cycle', 'store_location_code', 'is_groupbyserial', 'store_location_type_all', 'product_group_code', 'header_reference', 'assignment'], 'safe'],
                [['union_code', 'plant_code', 'mcc_code', 'date', 'shift'], 'required', 'on' => ['MemberCollectionShiftReport']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['BmcCollDateShiftWiseSummary', 'BmcCollDateShiftWiseSummaryCommon']],
                [['union_code', 'plant_code', 'mcc_code', 'date'], 'required', 'on' => ['MemberCollectionPaymentCycleWise', 'MemberWiseMonthlyCollection']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['DCSWiseFromDateToDateSummary', 'AgentPaymentFromDateToDate', 'VendorPaymentConsolidated']],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status'], 'required', 'on' => ['UnionWiseCollVsDispatch', 'UnionWiseCollVsRecipt', 'UnionWiseDispatchVsRecipt']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'member_code', 'no_of_payment_cycle'], 'required', 'on' => ['MemberWiseNoOfPaymentCycle']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['AnalyzerCleaningReview', 'AnalyzerCleaningPendingActivity', 'AnalyzerPcbReplacement', 'MemberReceptionStatus', 'PurchaseSummary', 'VendorPaymentFormat']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'date'], 'required', 'on' => ['CleaningFlag', 'EkoMilkCalibration']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'date', 'calibration_day'], 'required', 'on' => ['CalibrationFlag', 'CleaningFlagBmc']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberWiseSummary', 'PaymentSummary']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberDailyCollection', 'MemberMilkBill', 'MemberDailyCollectionCommon', 'FarmerPaymentWiseMilkWise']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['ManualMilkEntryMemberDateShiftWise', 'ManualMilkEntrySocietyDateShiftWise']],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['UnionCollDateShiftWiseSummary']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['CPReportSap']],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['UnionWiseSummary', 'CompanyWisePaymentCycleWise', 'VendorPaymentCycleWiseUnionWise', 'TotalPaymentCompanyWisePaymentCycleWise']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['BmcWisePaymentCycleWise', 'SocietyWiseCda', 'AgentWiseReconciliation', 'SocietyWiseCdaCommon']],
                [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['GprsDataReconciliation', 'MilkCollectionRegister', 'BmcCollectionRegister', 'BmcCollectionRouteWise']],
                [['union_code', 'plant_code', 'report_type'], 'required', 'on' => ['SapStatusReport', 'SapComparisionReport', 'BonusReport']],
                [['union_code', 'plant_code'], 'required', 'on' => ['DispatchVsReceipt', 'BmcCollectionSummary', 'BmcCollectionRouteWise', 'PlantWiseMilkCollectionTracking', 'TpCostDetail', 'TpCostSummary']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberPaymentDrafted']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['VendorBankPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['MemberBankPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['MemberOutstandingDetail', 'MemberData', 'MilkColllectionHistory', 'MemberHistory', 'DcsHistory', 'CpliabilityReport', 'TallyReport', 'AssetDetailsReport']],
                [['report_collection_type', 'date_payment_cycle'], 'required', 'on' => ['TallyReport']],
                [['union_code'], 'required', 'on' => ['RateApplicabilityDetails']],
                [['p_organization_type', 'rate_type', 'union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['RateAcknowledgement']],
                [['p_organization_type', 'union_code', 'plant_code'], 'required', 'on' => ['AmcsSyncPending']],
                [['output_type'], 'required', 'except' => ['DcsMaster', 'MemberMaster', 'CustomerMaster', 'CollectionPendriveFile', 'SapReport', 'SapReportCdpl']],
                [['union_code', 'date'], 'required', 'on' => ['LocationWiseAssetSummary', 'LocationWiseAssetMovement']],
                [['asset_code', 'store_location_type'], 'default', 'value' => 0, 'on' => ['LocationWiseAssetDetail', 'LocationWiseAssetSummary', 'LocationWiseAssetMovement']],
                [['union_code', 'from_date', 'to_date'], 'required', 'on' => 'LocationWiseAssetDetail'],
                [['from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['BMCAutomationReport']],
                [['from_date', 'to_date'], 'required', 'on' => ['SocietyCollectionData', 'FarmerRegister', 'SapDataExportForDeduction', 'SapDataExportForVlcReplacement', 'ApprovedAttachmentDetails', 'CompanyWiseMilkCollection', 'ProductDispatchCenterWiseDetail']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'route_code', 'date', 'shift'], 'required', 'on' => ['CollectionPendriveFile']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['RouteWiseCollection', 'RouteWiseCollectionSummary', 'VendorWiseCollectionSummary', 'MemberPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'payment_cycle_code'], 'required', 'on' => ['PaymentAbstract', 'PurchaseSummaryFormat']],
                [['union_code', 'plant_code', 'mcc_code'], 'required', 'on' => ['RateMasterRegister', 'ProductSaleRateMasterRegister', 'SapDataExportForDeduction', 'SapDataExportForVlcReplacement', 'SapDataExportFeedSaleMember']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['BmcCollectionData', 'VendorPayment', 'VspTransitRecovery', 'MilkDispatchList', 'MilkRejectList', 'CmpReport', 'PaymentCycleReport', 'FarmerMilkBillConsolidatedSummary']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['MilkCollectionData', 'FileGenerateStatus', 'QualityCollectionReport']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'report_collection_type', 'type_wise_report'], 'required', 'on' => ['MilkAndBmcCollectionMonthlyComparision']],
                [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['WeightCollectionList', 'MilkCollectionAudit', 'ProcMisLotWiseDetails', 'ComparisonReport', 'YearlyFarmerCollectionReport']],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['DcsCollDateShiftSummary', 'DcsCollDateShiftSummaryCommon']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'route_type_trans'], 'required', 'on' => ['BmcCollectionShiftReport', 'BmcCollectionShiftReportCommon']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['VendorWiseSummary', 'BmcWiseSummary']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MilkCollectionNotExistsDetail', 'MilkCollectionNotExistsSummary', 'UmangSapReport', 'MilkVan']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['DayWiseQtyDetail', 'DayWiseQtySummary', 'VendorPaymentCycleWiseBmcWise']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'to_date', 'product_code'], 'required', 'on' => ['AdvancePm']],
                [['to_date'], 'validateToDate', 'on' => ['AdvancePm']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['CcMilkPayment', 'MilkCollectionNegativeGroth', 'RootWiseDifference', 'AppStartupReport']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['FarmerFarmPayment', 'RouteWiseReconciliation', 'MonthlySahayakIncome', 'MisCcWiseClosingBalance', 'MccMilkBillDetailsWithIncentiveRouteWise', 'MccMilkBillDetailsMccDayWise', 'MisMilkPurchase']],
                [['union_code', 'plant_code', 'from_date', 'to_date'], 'required', 'on' => ['RateApplicabilityDetailsHistory', 'StockRegisterBmcToSap', 'BillHeadDetail', 'ComplainActivityList']],
                [['to_date'], 'validateDate', 'on' => ['RateApplicabilityDetailsHistory', 'MemberMilkBill', 'FarmerPaymentWiseMilkWise']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['MissingShift', 'CleaningFormat', 'VlccTransactionDataReport', 'IndentMemberDetail']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['SapMilkCollectionData']],
                [['union_code', 'plant_code', 'from_date', 'to_date'], 'required', 'on' => ['AlertNotification', 'GheeGroupIndentReport', 'CfGroupIndentReport', 'SapGheeGroupIndentReport', 'SapCfGroupIndentReport', 'ChillerCostSummary', 'ProductSaleLogHistory']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['SapMilkCollectionData']],
                [['union_code', 'p_date'], 'required', 'on' => ['StockSummary']],
                [['union_code', 'mcc_code', 'org_type', 'from_date', 'to_date'], 'required', 'on' => ['StockDetail']],
                [['bmc_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return strtoupper($model->org_type) == 'BMC';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#reportsmodel-org_type').val() == 'BMC';
}", 'on' => ['StockDetail']],
                [['bmc_code', 'dcs_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return strtoupper($model->org_type) == 'DCS';
                }, 'whenClient' => "function (attribute, value) {
return $('#reportsmodel-org_type').val() == 'DCS';
}", 'on' => ['StockDetail']],
                [['union_code', 'org_type', 'from_date', 'to_date'], 'required', 'on' => ['StockDetailSummary']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'payment_cycle_code'], 'required', 'on' => ['RecoveryFromOtherMember']],
                [['union_code', 'basis_on', 'from_date', 'to_date'], 'required', 'on' => ['MemberMilkCollection', 'MemberMilkCollectionDcsWise']],
                [['from_shift', 'to_shift'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->basis_on == '1' || $model->basis_on == '2';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#reportsmodel-basis_on').val() == '1' || $('#reportsmodel-basis_on').val() == '2';
                }", 'on' => ['MemberMilkCollection', 'MemberMilkCollectionDcsWise']],
                [['union_code', 'top_collection_on', 'from_date', 'to_date', 'param_type', 'top_value'], 'required', 'on' => ['DcsBmcMemberWiseTopCollection']],
                [['top_value'], 'default', 'value' => 1],
                [['top_value'], 'number', 'min' => 1],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'milk_type'], 'required', 'on' => ['FatAnalysisReport']],
                [['union_code', 'plant_code', 'f_spr_date', 't_spr_date', 'f_cmpr_date', 't_cmpr_date'], 'required', 'on' => ['DcsAndMemberWiseQtyCompare']],
                [['union_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['RecoveryFromDifferentVendor']],
                [['union_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['MissingCollectionShiftBmcCrossTab']],
                [['union_code', 'p_date'], 'required', 'on' => ['ProductStockDetailSummarySocietyWise']],
                [['union_code', 'p_date'], 'required', 'on' => ['ProductStockDetailSummaryMccWise']],
                [['union_code', 'plant_code', 'p_date'], 'required', 'on' => ['MemberWiseOutstanding', 'DcsWiseOutstanding', 'VendorWiseOutstanding', 'MccWiseOutstanding']],
                [['union_code', 'plant_code', 'from_date', 'to_date'], 'required', 'on' => ['MemberProductSaleForPaidInstallment', 'DcsProductSaleForPaidInstallment', 'VendorProductSaleForPaidInstallment', 'MccProductSaleForPaidInstallment', 'NewMemberPouringMilk', 'MilkCollectionProc', 'MilkCollectionProcDetail', 'BmcMilkCollectionProc', 'BmcMilkCollectionProcDetail', 'MilkShortageRecovery']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['NewMemberPouringMilk', 'NewCustomerPouringMilk']],
                [['union_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'report_type'], 'required', 'on' => ['CenterLossGainReport']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'action_perform'], 'required', 'on' => ['BmcCollectionHistory']],
                [['to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date', 16, '>', 'Day Difference can not be greater than 16.');
                }, 'skipOnEmpty' => false, 'on' => ['MemberDailyCollection', 'MilkCollectionData']],
                [['to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date');
                }, 'skipOnEmpty' => false, 'except' => ['MemberDailyCollection', 'MilkCollectionData', 'MemberMilkBill']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['SocietyCompositeVsActual', 'MpgPaymentBillStatement']],
                [['union_code', 'channel_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['SdFileSummary']],
                [['union_code', 'mcc_code', 'date', 'shift', 'report_type'], 'required', 'on' => 'SapReport'],
                [['union_code', 'mcc_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => 'SapReportCdpl'],
                [['union_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['TankerReport', 'AntibioticReport']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'payment_cycle_code'], 'required', 'on' => 'PaymentDifference'],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'sap_file'], 'required', 'on' => 'SapUploadSummary'],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['AutoManualMilkCollection']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['SocietyWiseRateDifferenceReport']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'to_date'], 'required', 'on' => ['MccBilling', 'CDAReport']],
                [['from_date', 'to_date'], 'required', 'on' => ['BmcRegister', 'AllReportRequest', 'PlantWiseMilkCollectionTracking', 'UserAttendanceDetails']],
                [['union_code', 'plant_register_type', 'from_date', 'to_date'], 'required', 'on' => ['PlantRegister']],
                [['union_code', 'from_date', 'to_date'], 'required', 'on' => ['TankerReceiptNote', 'MemberProvisionalFamilyDetail']],
                [['from_date', 'to_date', 'bmc_code'], 'required', 'on' => ['MccReceiptVsBmcDispatch']],
                [['from_date', 'to_date', 'bmc_code'], 'required', 'on' => ['MccDayBookDispatchHubHorizontal']],
                [['union_code', 'from_date', 'to_date'], 'required', 'on' => ['StockDispatchToMccFromStore', 'StockReceivedToMcc', 'StockDispatchToSale', 'StockRegisterToSap', 'StockRegisterMccToSap']],
                [['union_code', 'p_date'], 'required', 'on' => ['StockTransferToDcs', 'StockAtMcc']],
                [['union_code', 'p_date'], 'required', 'on' => ['StockAtDcs']],
                [['union_code', 'from_date', 'to_date'], 'required', 'on' => ['SaleReportFarmer']],
                [['union_code', 'from_date', 'to_date'], 'required', 'on' => ['SaleReportVendor', 'RMRDDataExport', 'IndentSummaryDetail']],
                [['union_code', 'from_date', 'to_date'], 'required', 'on' => ['SummaryReportDcs', 'MemberProvisionalSapExport']],
                [['union_code', 'mcc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['MemberCollectionReportForSap', 'RmrdMilkCollectionForSap']],
                [['union_code', 'plant_code', 'from_date', 'to_date'], 'required', 'on' => ['MilkCollectionAbsent']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'to_date'], 'required', 'on' => ['LeftPourer']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'customer_type', 'payment_cycle_code'], 'required', 'on' => ['MccRecipationSummary', 'MccRecipationDetail']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['BmcWiseSocietyWiseAutoManual', 'BmcWiseAutoManualSummary', 'PaymentCycleApplicabilityStatus']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'to_date'], 'required', 'on' => ['MemberPaymentBankFormat']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'to_date', 'report_type'], 'required', 'on' => ['BiplData', 'BiplDataAdmin']],
                [['union_code', 'login_user_code', 'from_date', 'to_date'], 'required', 'on' => ['DetailsReport']],
                [['union_code', 'state_code'], 'required', 'on' => ['RegionWiseUserAttendanceReport', 'EiplInstalledUsersDetails', 'AreBmcCollectionShiftReport', 'AreBmcCollDateShiftWiseSummary', 'AreBmcCollDateShiftWiseSummary', 'AreBmcCollDateShiftWiseSummary', 'AreSocietyWiseCda', 'AreSocietyWiseCda', 'AreSocietyWiseCda', 'AreVendorPayment', 'AreMemberPaymentDcsWise', 'AreMemberPaymentDcsWise', 'AreVendorBankPayment', 'AreMemberBankPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'payment_type', 'from_date', 'to_date'], 'required', 'on' => ['DcsWiseBillHeadApplicability']],
                [['union_code', 'mcc_code', 'bmc_code'], 'required', 'on' => 'SapWqFile'],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['BmcCollectionSummaryRahema', 'MilkCollectionFilterBased']],
                [['user_login_type'], 'required', 'on' => 'MobileAppReport'],
                [['union_code', 'user_code', 'from_date', 'to_date'], 'required', 'on' => ['FieldStaffActivity']],
                [['union_code', 'plant_code', 'mcc_code', 'as_on_date'], 'required', 'on' => 'ExportProvisionalMemberBankReceipt'],
                [['dcs_code', 'report_type', 'from_value', 'to_value'], 'required', 'on' => ['MilkCollectionFilterBased']],
                [['from_value', 'to_value'], 'number'],
                [['from_value'], 'compare', 'compareAttribute' => 'to_value', 'operator' => '<', 'type' => 'number', 'on' => ['MilkCollectionFilterBased']],
                [['region_code', 'area_code'], 'required', 'on' => ['EiplInstalledUsersDetails']],
                [['union_code', 'from_date', 'to_date', 'report_type'], 'required', 'on' => ['VlcQtySlabWiseCategory', 'AvgPerVlcMilkQtySlabWiseCategory']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'rate_cal_for', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['RateRecalculationWefDateWise']],
                [['insurance_master_code'], 'required', 'on' => ['InsuranceDetail', 'InsuranceDetailReconciliation', 'InsuranceSummaryDcsWise']],
                [['operation_type'], 'required', 'on' => ['InsuranceDetailReconciliation']],
                [['state_code', 'region_code', 'area_code', 'bmc_code', 'payment_cycle_code', 'bank_type'], 'required', 'on' => ['AreVendorBankPayment', 'AreMemberBankPayment']],
                [['union_code', 'milk_sale_on', 'from_date', 'to_date'], 'required', 'on' => ['LocalMilkSale']],
                [['from_shift', 'to_shift'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->milk_sale_on == '1';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#reportsmodel-milk_sale_on').val() == '1';
          }", 'on' => ['LocalMilkSale']],
                [['union_code', 'from_date', 'to_date', 'billing_on'], 'required', 'on' => ['MemberBilling', 'MemberBillingDcsWise']],
                [['union_code', 'as_on_date'], 'required', 'on' => ['SummaryReportMcc']],
                [['from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['MilkCollectionStatusReport', 'MilkCollectionStatusDetail']],
                [['login_type_report'], 'required', 'on' => ['UserAttendanceDetails']],
                [['store_location_type_all'], 'required', 'on' => ['AssetDetailSummary']],
                [['store_location_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->store_location_type_all != 0;
                }, 'whenClient' => "function (attribute, value) { 
                    return $('#reportsmodel-store_location_type_all').val() != 0;
                }", 'on' => ['AssetDetailSummary']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'report_type'], 'required', 'on' => ['AadeshLatter']],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => \Yii::t('app', 'Union'),
            'plant_code' => \Yii::t('app', 'Plant'),
            'mcc_code' => \Yii::t('app', 'MCC'),
            'bmc_code' => \Yii::t('app', 'BMC'),
            'dcs_code' => \Yii::t('app', 'DCS Code'),
            'dcs_name' => \Yii::t('app', 'DCS Name'),
            'from_date' => \Yii::t('app', 'From Date'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'to_date' => \Yii::t('app', 'To Date'),
            'to_shift' => \Yii::t('app', 'To Shift'),
            'p_date' => \Yii::t('app', 'As On Date'),
            'date' => \Yii::t('app', 'Date'),
            'shift' => \Yii::t('app', 'Shift'),
            'p_organization_type' => ($this->scenario == 'AmcsSyncPending') ? \Yii::t('app', 'Application') : \Yii::t('app', 'Organization Type'),
            'p_purchase_rate_code' => \Yii::t('app', 'Rate'),
            'vendor_code' => \Yii::t('app', 'Name'),
            'customer_type' => \Yii::t('app', 'Type'),
            'payment_cycle_code' => \Yii::t('app', 'Payment Cycle'),
            'asset_code' => \Yii::t('app', 'Asset'),
            'transporter_code' => \Yii::t('app', 'Transporter'),
            'vehicle_code' => \Yii::t('app', 'Vehicle'),
            'route_type_trans' => \Yii::t('app', 'Route Type'),
            'member' => \Yii::t('app', 'Member'),
            'channel_code' => \Yii::t('app', 'Channel'),
            'report_type' => (in_array($this->scenario, ['SaleReportFarmer', 'SaleReportVendor'])) ? \Yii::t('app', 'Lock Type') : ((in_array($this->scenario, ['SapWqFile'])) ? \Yii::t('app', 'Format Type') : \Yii::t('app', 'Report Type')),
            'report_req_status' => \Yii::t('app', 'Status'),
            'payment_type' => \Yii::t('app', 'Bill Head For'),
            'f_spr_date' => \Yii::t('app', 'From Supervision Date'),
            't_spr_date' => \Yii::t('app', 'To Supervision Date'),
            'f_cmpr_date' => \Yii::t('app', 'From Compare Date'),
            't_cmpr_date' => \Yii::t('app', 'To Compare Date'),
            'animal_type' => \Yii::t('app', 'Milk Type'),
            'current_status' => \Yii::t('app', 'Current Status'),
            'login_type_report' => \Yii::t('app', 'Login Type'),
            'rate_cal_for' => \Yii::t('app', 'Recalc For'),
            'store_location_code' => \Yii::t('app', 'Store Location'),
            'is_groupbyserial' => \Yii::t('app', 'Is Group By Serial'),
        ];
    }

    public function search($params) {
        
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $fDate = date('Y-m-d', strtotime($this->from_date));
            $tDate = date('Y-m-d', strtotime($this->to_date));
            if ($tDate < $fDate) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
                return false;
            } else {
                $fDate = date_create($fDate);
                $tDate = date_create($tDate);
                $diff = date_diff($fDate, $tDate);
                $DayCount = $diff->format("%a");
                $DayCount = $DayCount + 1;
                if ($DayCount > 15) {
                    $this->addError('to_date', Yii::t('app/validation', 'Day Difference can not be greater than 15.'));
                    return false;
                }
            }
        }
    }

    public function validateDate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $fDate = date('Y-m-d', strtotime($this->from_date));
            $tDate = date('Y-m-d', strtotime($this->to_date));
            if ($tDate < $fDate) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
                return false;
            } else {
                $fDate = date_create($fDate);
                $tDate = date_create($tDate);
                $diff = date_diff($fDate, $tDate);
                $DayCount = $diff->format("%a");

                $DayCount = $DayCount + 1;
                if ($DayCount > 30) {
                    $this->addError('to_date', Yii::t('app/validation', 'Day Difference can not be greater than 30.'));
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    public function getMccCode($mccCode) {
        $mccModel = new TblMccPlant();
        $mccData = $mccModel->find()->where(['mcc_plant_code' => $mccCode])->one();
        return $mccData;
    }

    public function getBmcCode($bmcCode) {
        $bmcModel = new TblDcsBmc();
        $bmcData = $bmcModel->find()->where(['bmc_code' => $bmcCode])->one();
        return $bmcData;
    }

}
