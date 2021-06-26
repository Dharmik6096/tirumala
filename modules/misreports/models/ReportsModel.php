<?php

namespace app\modules\misreports\models;

use Yii;
use yii\base\Model;

class ReportsModel extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $report_type, $date, $shift;
    public $calibration_day, $p_date, $customer_code, $member_code, $p_organization_type, $p_purchase_rate_code, $rate_type, $customer_type, $vendor_code, $payment_cycle_code, $bank_type, $report_status, $member_type, $route_code;
    public $no_of_payment_cycle, $output_type, $store_location_type, $asset_code, $sap_code, $sr_no, $main_customer_type, $transporter_code, $vehicle_code, $originating_type, $report_collection_type, $type_wise_report, $route_type_trans, $product_code;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_code', 'p_purchase_rate_code', 'payment_cycle_code', 'vendor_code', 'customer_type', 'route_code', 'main_customer_type', 'transporter_code', 'vehicle_code'], 'default', 'value' => 0],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'p_date', 'customer_code', 'member_code', 'rate_type', 'customer_type', 'vendor_code', 'payment_cycle_code', 'report_status', 'member_type', 'route_code', 'no_of_payment_cycle', 'output_type', 'report_type', 'store_location_type', 'asset_code', 'sap_code', 'sr_no', 'main_customer_type', 'transporter_code', 'vehicle_code', 'originating_type', 'report_collection_type', 'type_wise_report', 'route_type_trans', 'product_code'], 'safe'],
            [['union_code', 'plant_code', 'mcc_code', 'date', 'shift'], 'required', 'on' => ['MemberCollectionShiftReport']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['BmcCollDateShiftWiseSummary']],
            [['union_code', 'plant_code', 'mcc_code', 'date'], 'required', 'on' => ['MemberCollectionPaymentCycleWise', 'MemberWiseMonthlyCollection']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['DCSWiseFromDateToDateSummary', 'AgentPaymentFromDateToDate', 'VendorPaymentConsolidated']],
            [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status'], 'required', 'on' => ['UnionWiseCollVsDispatch', 'UnionWiseCollVsRecipt', 'UnionWiseDispatchVsRecipt']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'member_code', 'no_of_payment_cycle'], 'required', 'on' => ['MemberWiseNoOfPaymentCycle']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['AnalyzerCleaningReview', 'AnalyzerCleaningPendingActivity', 'AnalyzerPcbReplacement', 'VendorPayment', 'MemberReceptionStatus']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'date'], 'required', 'on' => ['CleaningFlag', 'EkoMilkCalibration']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'date', 'calibration_day'], 'required', 'on' => ['CalibrationFlag', 'CleaningFlagBmc']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberWiseSummary']],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberDailyCollection']],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['DcsCollDateShiftSummary', 'ManualMilkEntryMemberDateShiftWise', 'ManualMilkEntrySocietyDateShiftWise']],
            // [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['ManualMilkEntrySocietyDateShiftWise']],
            [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['UnionCollDateShiftWiseSummary']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['VendorPaymentCycleWiseBmcWise', 'CPReportSap']],
            [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['UnionWiseSummary', 'CompanyWisePaymentCycleWise', 'VendorPaymentCycleWiseUnionWise', 'TotalPaymentCompanyWisePaymentCycleWise']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['BmcWisePaymentCycleWise', 'SocietyWiseCda']],
            [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['GprsDataReconciliation', 'MilkCollectionRegister', 'BmcCollectionRegister']],
            [['union_code', 'plant_code', 'report_type'], 'required', 'on' => ['SapStatusReport', 'SapComparisionReport']],
            [['union_code', 'plant_code'], 'required', 'on' => 'DispatchVsReceipt'],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberPayment', 'MemberPaymentDrafted']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['VendorBankPayment']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['MemberBankPayment']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['MemberOutstandingDetail', 'MemberData']],
            [['union_code'], 'required', 'on' => ['RateApplicabilityDetails']],
            [['p_organization_type', 'rate_type', 'union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['RateAcknowledgement']],
            [['p_organization_type', 'union_code', 'plant_code'], 'required', 'on' => ['AmcsSyncPending']],
            [['output_type'], 'required', 'except' => ['DcsMaster', 'MemberMaster', 'CustomerMaster', 'CollectionPendriveFile']],
            [['union_code', 'date'], 'required', 'on' => ['LocationWiseAssetSummary', 'LocationWiseAssetMovement']],
            [['asset_code', 'store_location_type'], 'default', 'value' => 0, 'on' => ['LocationWiseAssetDetail', 'LocationWiseAssetSummary', 'LocationWiseAssetMovement']],
            [['union_code', 'from_date', 'to_date'], 'required', 'on' => 'LocationWiseAssetDetail'],
            [['from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['BMCAutomationReport']],
            [['from_date', 'to_date'], 'required', 'on' => ['SocietyCollectionData']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'route_code', 'date', 'shift'], 'required', 'on' => ['CollectionPendriveFile']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['RouteWiseCollection', 'RouteWiseCollectionSummary', 'VendorWiseCollectionSummary']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'payment_cycle_code'], 'required', 'on' => ['PaymentAbstract']],
            [['union_code', 'plant_code', 'mcc_code'], 'required', 'on' => ['RateMasterRegister', 'ProductSaleRateMasterRegister']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['BmcCollectionData']],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'originating_type'], 'required', 'on' => ['MilkCollectionData']],
            [['union_code', 'plant_code', 'from_date', 'to_date', 'report_collection_type', 'type_wise_report'], 'required', 'on' => ['MilkAndBmcCollectionMonthlyComparision']],
            [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['WeightCollectionList']],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status', 'report_type'], 'required', 'on' => ['DcsCollDateShiftSummary']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'route_type_trans'], 'required', 'on' => ['BmcCollectionShiftReport']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['VendorWiseSummary', 'BmcWiseSummary']],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MilkCollectionNotExistsDetail', 'MilkCollectionNotExistsSummary']],
            [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['DayWiseQtyDetail', 'DayWiseQtySummary']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'to_date', 'product_code'], 'required', 'on' => ['AdvancePm']],
            [['to_date'], 'validateToDate', 'on' => ['AdvancePm']],
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

}
