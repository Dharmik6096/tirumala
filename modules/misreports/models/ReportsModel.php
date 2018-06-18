<?php

namespace app\modules\misreports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class ReportsModel extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $report_type;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'safe'],
            [['report_type'], 'required', 'on' => ['SapReport', 'BmcCollection']],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => \Yii::t('app', 'Union'),
            'plant_code' => \Yii::t('app', 'Plant'),
            'mcc_code' => \Yii::t('app', 'MCC'),
            'bmc_code' => \Yii::t('app', 'BMC'),
            'dcs_code' => \Yii::t('app', 'DCS'),
            'from_date' => \Yii::t('app', 'From Date'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'to_date' => \Yii::t('app', 'To Date'),
            'to_shift' => \Yii::t('app', 'To Shift'),
        ];
    }

    public function search($params) {
        
    }

}
