<?php

namespace app\modules\misreports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class ReportsModel extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $report_type;
    public $calibration_day, $p_date, $customer_code;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'p_date', 'customer_code'], 'safe'],
            [['report_type'], 'required', 'on' => 'BmcCollection'],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'report_type'], 'required', 'on' => 'SapReport'],
            [['to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date');
                }, 'skipOnEmpty' => false],
            [['union_code', 'plant_code'], 'required', 'on' => 'SapStatusReport'],
            [['union_code', 'plant_code', 'report_type'], 'required', 'on' => 'SapComparisionReport'],
            [['union_code', 'plant_code'], 'required', 'on' => 'DispatchVsReceipt'],
            [['union_code', 'plant_code', 'mcc_code', 'from_date', 'calibration_day'], 'required', 'on' => ['CalibrationFlag', 'CleaningFlagBmc']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['AnalyzerCleaningReview', 'AnalyzerCleaningPendingActivity', 'AnalyzerPcbReplacement']],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date'], 'required', 'on' => ['CleaningFlag', 'EkoMilkCalibration']],
            [['from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['TotalMilkCollectionDateShift']],
            [['union_code'], 'required', 'on' => ['RateApplicabilityDetails']]
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
        ];
    }

    public function search($params) {
        
    }

}
