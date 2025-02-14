<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_weight_scale_calibration_history".
 *
 * @property integer $id
 * @property integer $weight_scale_calibration_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $ws_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $manual_quantity
 * @property string $actual_quantity
 * @property string $reference_measurement
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblWeightScaleCalibrationHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_weight_scale_calibration_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight_scale_calibration_id','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','ws_code','date_time_of_collection','shift_code','manual_quantity','actual_quantity','reference_measurement','sync_status','sync_timestamp','remarks','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','history_created_at','history_created_by','operation_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'weight_scale_calibration_id' => Yii::t('app', 'Weight Scale Calibration ID'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'manual_quantity' => Yii::t('app', 'Manual Quantity'),
            'actual_quantity' => Yii::t('app', 'Actual Quantity'),
            'reference_measurement' => Yii::t('app', 'Reference Measurement'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
