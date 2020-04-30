<?php

namespace app\modules\misreports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class ReportsModel extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $report_type, $date, $shift;
    public $calibration_day, $p_date, $customer_code, $member_code, $p_organization_type, $p_purchase_rate_code, $rate_type, $customer_type, $vendor_code, $payment_cycle_code, $bank_type;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_code', 'p_purchase_rate_code', 'payment_cycle_code', 'vendor_code', 'customer_type'], 'default', 'value' => 0],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'p_date', 'customer_code', 'member_code', 'rate_type', 'customer_type', 'vendor_code', 'payment_cycle_code'], 'safe'],
            [['report_type'], 'required', 'on' => 'BmcCollection'],
            [['union_code', 'mcc_code', 'date', 'shift', 'report_type'], 'required', 'on' => 'SapReport'],
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
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['VendorPayment']],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type'], 'required', 'on' => ['MemberPayment']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'customer_type', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['VendorBankPayment']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'bank_type', 'payment_cycle_code'], 'required', 'on' => ['MemberBankPayment']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['MemberOutstandingDetail']],
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
            'p_organization_type' => \Yii::t('app', 'Organization Type'),
            'p_purchase_rate_code' => \Yii::t('app', 'Rate'),
            'vendor_code' => \Yii::t('app', 'Name'),
            'customer_type' => \Yii::t('app', 'Type'),
            'payment_cycle_code' => \Yii::t('app', 'Payment Cycle'),
        ];
    }

    public function search($params) {
        
    }

}
