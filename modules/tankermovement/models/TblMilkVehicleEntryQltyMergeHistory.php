<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_qlty_merge_history".
 *
 * @property integer $id
 * @property integer $milk_vehicle_entry_qlty_merge_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $vehicle_code
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
 * @property integer $is_qty_only
 * @property integer $is_pending_merge
 * @property integer $is_approved
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
class TblMilkVehicleEntryQltyMergeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_qlty_merge_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_qlty_merge_code', 'is_qty_only', 'is_pending_merge', 'is_approved', 'originating_type', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity', 'created_at', 'updated_at', 'history_created_at', 'union_code', 'plant_code', 'vehicle_code', 'trip_code', 'chamber_no', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'tested_by', 'verified_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_vehicle_entry_qlty_merge_code' => Yii::t('app', 'Milk Vehicle Entry Qlty Merge Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'chamber_no' => Yii::t('app', 'Chamber No'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'density' => Yii::t('app', 'Density'),
            'protein' => Yii::t('app', 'Protein'),
            'lactose' => Yii::t('app', 'Lactose'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'temp' => Yii::t('app', 'Temp'),
            'acidity' => Yii::t('app', 'Acidity'),
            'is_qty_only' => Yii::t('app', 'Is Qty Only'),
            'is_pending_merge' => Yii::t('app', 'Is Pending Merge'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
