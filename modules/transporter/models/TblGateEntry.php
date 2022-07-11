<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_gate_entry".
 *
 * @property integer $gate_entry_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property string $transporter_code
 * @property string $vehicle_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $define_arrival_time
 * @property string $actual_arrival_time
 * @property string $grace_time
 * @property string $late_by_time
 * @property integer $responsibility_code
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
class TblGateEntry extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_gate_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_time_of_collection', 'define_arrival_time', 'actual_arrival_time', 'created_at', 'updated_at'], 'safe'],
            [['shift_code', 'responsibility_code', 'originating_type'], 'integer'],
            [['grace_time', 'late_by_time'], 'number'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
            [['bmc_code', 'dcs_code'], 'string', 'max' => 12],
            [['route_code'], 'string', 'max' => 10],
            [['transporter_code'], 'string', 'max' => 8],
            [['vehicle_code'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gate_entry_code' => Yii::t('app', 'Gate Entry Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'define_arrival_time' => Yii::t('app', 'Define Arrival Time'),
            'actual_arrival_time' => Yii::t('app', 'Actual Arrival Time'),
            'grace_time' => Yii::t('app', 'Grace Time'),
            'late_by_time' => Yii::t('app', 'Late By Time'),
            'responsibility_code' => Yii::t('app', 'Responsibility Code'),
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
}
