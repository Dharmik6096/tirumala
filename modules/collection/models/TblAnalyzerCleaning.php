<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_analyzer_cleaning".
 *
 * @property string $analyzer_cleaning_code
 * @property string $date_time_of_cleaning
 * @property integer $shift_code
 * @property string $date_time_of_actual_cleaning
 * @property integer $cycle
 * @property integer $counter
 * @property integer $measuring
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
 * @property integer $txfarmer_id
 * @property string $data_inserted_from
 */
class TblAnalyzerCleaning extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_analyzer_cleaning';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['analyzer_cleaning_code'], 'required'],
            [['date_time_of_cleaning', 'date_time_of_actual_cleaning', 'created_at', 'updated_at'], 'safe'],
            [['shift_code', 'cycle', 'counter', 'measuring', 'originating_type', 'txfarmer_id'], 'integer'],
            [['analyzer_cleaning_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string', 'max' => 12],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['data_inserted_from'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'analyzer_cleaning_code' => Yii::t('app', 'Analyzer Cleaning Code'),
            'date_time_of_cleaning' => Yii::t('app', 'Date Of Cleaning'),
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_actual_cleaning' => Yii::t('app', 'Date Of Actual Cleaning'),
            'cycle' => Yii::t('app', 'Cycle'),
            'counter' => Yii::t('app', 'Counter'),
            'measuring' => Yii::t('app', 'Measuring'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
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
            'txfarmer_id' => Yii::t('app', 'Txfarmer ID'),
            'data_inserted_from' => Yii::t('app', 'Data Inserted From'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

}
