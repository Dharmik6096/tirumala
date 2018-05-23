<?php

namespace app\modules\crystalreports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class ReportsModel extends Model {

    public $union_code, $mccid, $bmcid, $vlccid, $routeid, $date1, $date2, $from_shift, $to_shift, $plant_code, $CattleType, $MilkQualityType, $file_name, $VLCTotal, $MCCId;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_name', 'VLCTotal'], 'safe'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code', 'CattleType'], 'required', 'on' => 'BmcCollection'],
            [['union_code', 'MCCId', 'bmcid', 'routeid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'ActualBmcCollection'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code', 'CattleType', 'MilkQualityType'], 'required', 'on' => 'RmrdMilkCollection'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code', 'CattleType', 'MilkQualityType'], 'required', 'on' => 'BmcSummaryReport'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'VariationMilkTypeDateWise'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'VariationMilkTypeVillageWise'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'VariationDateWise'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'VariationVillageWise'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'VariationPercentageWise'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'DifferenceReport'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'DifferenceReportDateWise'],
            [['union_code', 'mccid', 'bmcid', 'routeid', 'vlccid', 'date1', 'date2', 'from_shift', 'to_shift', 'plant_code'], 'required', 'on' => 'DifferenceReportVillageWise'],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => Yii::t('app', 'Company'),
            'mccid' => Yii::t('app', 'MCC'),
            'bmcid' => Yii::t('app', 'BMC'),
            'vlccid' => Yii::t('app', 'TMCC'),
            'routeid' => Yii::t('app', 'ROUTE'),
            'date1' => Yii::t('app', 'From Date'),
            'date2' => Yii::t('app', 'To Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_shift' => Yii::t('app', 'To Shift'),
        ];
    }

}
