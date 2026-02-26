<?php

namespace app\modules\misreports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;

class ReportsModelOld extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $report_type, $date, $shift;
    public $calibration_day, $p_date, $customer_code, $member_code, $p_organization_type, $p_purchase_rate_code, $rate_type, $customer_type, $vendor_code, $payment_cycle_code, $bank_type, $report_status, $member_type, $route_code, $upload_ftp_file, $output_type;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_code', 'p_purchase_rate_code', 'payment_cycle_code', 'vendor_code', 'customer_type', 'route_code'], 'default', 'value' => 0],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'p_date', 'customer_code', 'member_code', 'rate_type', 'customer_type', 'vendor_code', 'payment_cycle_code', 'report_status', 'member_type', 'route_code', 'upload_ftp_file', 'output_type'], 'safe'],
                [['report_type'], 'required', 'on' => ['BmcCollection', 'CPReportSap']],
                [['union_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['SapReport', 'SapReportExport']],
                [['report_type'], 'default', 'value' => 1, 'on' => 'SapWqFile'],
                [['union_code', 'mcc_code', 'bmc_code', 'report_type'], 'required', 'on' => 'SapWqFile'],
                [['to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date');
                }, 'skipOnEmpty' => false],
                [['union_code', 'plant_code', 'report_type'], 'required', 'on' => 'SapStatusReport'],
                [['union_code', 'plant_code', 'report_type'], 'required', 'on' => 'SapComparisionReport'],
                [['union_code', 'plant_code'], 'required', 'on' => 'DispatchVsReceipt'],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'calibration_day'], 'required', 'on' => ['CalibrationFlag', 'CleaningFlagBmc']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['AnalyzerCleaningReview', 'AnalyzerCleaningPendingActivity', 'AnalyzerPcbReplacement']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date'], 'required', 'on' => ['CleaningFlag', 'EkoMilkCalibration']],
                [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['TotalMilkCollectionDateShift', 'CollectionDataSummary', 'MccShiftCrossTab']],
                [['union_code'], 'required', 'on' => ['RateApplicabilityDetails', 'MccShiftCrossTab']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['MemberCollectionPassbook', 'MemberCollectionDayWise', 'MemberCollectionSummary']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'p_date'], 'required', 'on' => ['MemberCollectionPaymentcycleWise', 'MemberCollectionMonthWise']],
                [['union_code', 'plant_code'], 'required', 'on' => ['MemberMobileAppDetail']],
                [['union_code', 'report_type'], 'required', 'on' => ['CollectionDataSummary']],
                [['p_organization_type', 'rate_type', 'union_code', 'plant_code'], 'required', 'on' => ['RateAcknowledgement']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['VendorPayment', 'CPReportSap']],
                [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'customer_type', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['VendorBankPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['MemberBankPayment']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['MemberOutstandingDetail']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'p_date', 'from_shift'], 'required', 'on' => ['ShiftReportNameWise']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type', 'report_status'], 'required', 'on' => ['MemberMilkCollection', 'ConsolidatedUnionMilkCollection', 'DcsWiseMilkCollection']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['MemberMilkCollectionRegister']],
                [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'route_code', 'report_status'], 'required', 'on' => ['CollectionVsDispatchGraph']],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'route_code', 'report_status'], 'required', 'on' => ['UnionWiseCollectionVsDispatch']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_status'], 'required', 'on' => ['CdaDateAndShiftWise', 'CdaConsolidated', 'BmcCollectionConsolidated', 'CdaDateWise']],
                [['union_code', 'plant_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['VariationPercentageWise', 'VariationVillageWise']],
                [['p_organization_type', 'union_code', 'plant_code'], 'required', 'on' => ['AmcsSyncPending']],
                [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => 'GprsDataReconciliation'],
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
            'member' => \Yii::t('app', 'Member'),
        ];
    }

    public function search($params) {
        
    }

    public function getMccCode($mccCode) {
        $mccModel = new TblMccPlant();
        $mccData = $mccModel->find()->select('ref_code')->where(['mcc_plant_code' => $mccCode])->one();
        return !empty($mccData->ref_code) ? $mccData->ref_code : 'All';
    }

    public function getBmcCode($bmcCode) {
        $bmcModel = new TblDcsBmc();
        $bmcData = $bmcModel->find()->select('ref_code')->where(['bmc_code' => $bmcCode])->one();
        return !empty($bmcData->ref_code) ? $bmcData->ref_code : 'All';
    }

}
