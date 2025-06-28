<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_qlty_history".
 *
 * @property integer $id
 * @property integer $milk_vehicle_entry_qlty_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $vehicle_code
 * @property string $arrival_datetime
 * @property string $trip_code
 * @property string $chamber_no
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $density
 * @property string $protein
 * @property string $lactose
 * @property string $freezing_point
 * @property string $mbrt
 * @property string $temp
 * @property string $acidity
 * @property string $status
 * @property string $status_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkVehicleEntryQltyHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_qlty_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_qlty_code', 'union_code', 'plant_code', 'vehicle_code', 'arrival_datetime', 'trip_code', 'chamber_no', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity', 'status', 'status_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'lot_datetime', 'lot_no', 'tested_by', 'verified_by', 'sample_datetime', 'record_status', 'is_qty_only', 'is_pending_merge', 'is_approved'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'milk_vehicle_entry_qlty_code' => 'Milk Vehicle Entry Qlty Code',
            'union_code' => 'Union Code',
            'plant_code' => 'Plant Code',
            'vehicle_code' => 'Vehicle Code',
            'arrival_datetime' => 'Arrival Datetime',
            'trip_code' => 'Trip Code',
            'chamber_no' => 'Chamber No',
            'fat' => 'Fat',
            'snf' => 'Snf',
            'clr' => 'Clr',
            'water' => 'Water',
            'density' => 'Density',
            'protein' => 'Protein',
            'lactose' => 'Lactose',
            'freezing_point' => 'Freezing Point',
            'mbrt' => 'Mbrt',
            'temp' => 'Temp',
            'acidity' => 'Acidity',
            'status' => 'Status',
            'status_datetime' => 'Status Datetime',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
            'x_col1' => 'X Col1',
            'x_col2' => 'X Col2',
            'x_col3' => 'X Col3',
            'x_col4' => 'X Col4',
            'x_col5' => 'X Col5',
        ];
    }

}
