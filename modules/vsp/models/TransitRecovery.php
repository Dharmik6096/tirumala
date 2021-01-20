<?php

namespace app\modules\vsp\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use Yii;

class TransitRecovery extends Model {

    public $union_code, $plant_code, $mcc_plant_code, $bmc_code, $dcs_code, $from_date, $from_shift, $to_date, $to_shift, $order_on, $ts_loss_responsibility, $qty_diff_type, $qty_diff_responsibility, $shortage_recovery, $total_recovery_incharge, $total_recovery_transporter;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'order_on', 'ts_loss_responsibility', 'qty_diff_type', 'qty_diff_responsibility', 'shortage_recovery', 'total_recovery_incharge', 'total_recovery_transporter'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'order_on'], 'required', 'on' => ['transit-shortage']],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => \Yii::t('app', 'Union'),
            'plant_code' => \Yii::t('app', 'Plant'),
            'mcc_plant_code' => \Yii::t('app', 'MCC'),
            'bmc_code' => \Yii::t('app', 'BMC'),
            'dcs_code' => \Yii::t('app', 'DCS'),
            'dcs_name' => \Yii::t('app', 'DCS Name'),
            'from_date' => \Yii::t('app', 'From Date'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'to_date' => \Yii::t('app', 'To Date'),
            'to_shift' => \Yii::t('app', 'To Shift'),
            'order_on' => \Yii::t('app', 'Order On'),
        ];
    }
}
