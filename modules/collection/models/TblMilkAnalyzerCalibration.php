<?php

namespace app\modules\collection\models;

use app\models\ChildModel;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_milk_analyzer_calibration".
 *
 * @property integer $milk_analyzer_calibration_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $milk_analyser_type_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $manual_fat
 * @property string $actual_fat
 * @property string $manual_snf
 * @property string $actual_snf
 * @property integer $milk_type_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkAnalyzerCalibration extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_analyzer_calibration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['milk_analyzer_calibration_id','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','milk_analyser_type_code','date_time_of_collection','shift_code','manual_fat','actual_fat','manual_snf','actual_snf','milk_type_code','sync_status','sync_timestamp','remarks','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
            [['union_code'], 'required', 'on' => ['androidsync']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'milk_analyzer_calibration_id' => Yii::t('app', 'Milk Analyzer Calibration ID'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'manual_fat' => Yii::t('app', 'Manual Fat'),
            'actual_fat' => Yii::t('app', 'Actual Fat'),
            'manual_snf' => Yii::t('app', 'Manual Snf'),
            'actual_snf' => Yii::t('app', 'Actual Snf'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }
}
