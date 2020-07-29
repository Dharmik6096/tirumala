<?php

namespace app\modules\sms\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class ManualNotification extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $route_code, $dcs_code, $customer_code, $from_date, $from_shift, $to_date, $to_shift, $report_type, $date, $shift, $data_type;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'route_code', 'dcs_code', 'customer_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'report_type', 'date', 'shift', 'data_type'], 'safe'],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'date', 'shift', 'data_type', 'report_type'], 'required', 'on' => ['RmrdCollectionVsp']],
            [['to_date'], function ($attribute, $params) {
            Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date');
        }, 'skipOnEmpty' => false],
            [['from_date', 'to_date', 'date'], 'default', 'value' => date('d-m-Y')],
            [['from_shift', 'to_shift', 'shift'], 'default', 'value' => 1],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'customer_code', 'route_code', 'data_type'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => \Yii::t('app', 'Union'),
            'plant_code' => \Yii::t('app', 'Plant'),
            'mcc_code' => \Yii::t('app', 'MCC'),
            'bmc_code' => \Yii::t('app', 'BMC'),
            'route_code' => \Yii::t('app', 'ROUTE'),
            'dcs_code' => \Yii::t('app', 'DCS'),
            'customer_code' => \Yii::t('app', 'Vendor'),
            'from_date' => \Yii::t('app', 'From Date'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'to_date' => \Yii::t('app', 'To Date'),
            'to_shift' => \Yii::t('app', 'To Shift'),
            'date' => \Yii::t('app', 'Date'),
            'shift' => \Yii::t('app', 'Shift'),
            'report_type' => \Yii::t('app', 'Receiver Type'),
            'data_type' => \Yii::t('app', 'Data Type'),
        ];
    }

    public function search($params) {
        
    }

}
