<?php

namespace app\modules\bkgprocess\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class FtpGenerate extends Model {

    public $union_code, $plant_code, $mcc_code, $bmc_code, $from_date, $from_shift, $to_date, $to_shift, $status, $token;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'status', 'token'], 'safe'],
            [['union_code', 'plant_code', 'mcc_code', 'bmc_code', 'from_date', 'from_shift', 'to_date', 'to_shift'], 'required', 'on' => ['FTPMilkCollection']],
            [['token'], 'default', 'value' => NULL],
            [['status'], 'default', 'value' => 'Generate'],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => \Yii::t('app', 'Union'),
            'plant_code' => \Yii::t('app', 'Plant'),
            'mcc_code' => \Yii::t('app', 'MCC'),
            'bmc_code' => \Yii::t('app', 'BMC'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'from_date' => \Yii::t('app', 'Date From'),
        ];
    }

    public function search($params) {
        
    }

}
