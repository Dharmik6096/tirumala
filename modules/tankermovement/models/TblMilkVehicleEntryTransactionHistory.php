<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_transaction_history".
 *
 * @property integer $id
 * @property string $milk_vehicle_entry_transaction_code
 * @property string $milk_vehicle_entry_code
 * @property string $vehicle_entry_chamber_date
 * @property string $chamber_quantity
 * @property string $grn_no
 * @property string $chamber_no
 * @property string $challan_no
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $source_org_code
 * @property string $source_org_type
 * @property string $destination_code
 * @property string $destination_type
 * @property string $entry_type
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
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblMilkVehicleEntryTransactionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_transaction_code', 'milk_vehicle_entry_code', 'grn_no', 'chamber_no', 'challan_no', 'source_org_code', 'source_org_type', 'destination_code', 'destination_type', 'entry_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by'], 'string'],
            [['vehicle_entry_chamber_date', 'created_at', 'updated_at', 'history_created_at', 'is_qty_only', 'is_pending_merge'], 'safe'],
            [['chamber_quantity', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'number'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['status', 'cron_pick_datetime', 'pick_datetime', 'response_datetime', 'response_msg'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'record_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_vehicle_entry_transaction_code' => Yii::t('app', 'Milk Vehicle Entry Transaction Code'),
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'vehicle_entry_chamber_date' => Yii::t('app', 'Vehicle Entry Chamber Date'),
            'chamber_quantity' => Yii::t('app', 'Chamber Quantity'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'chamber_no' => Yii::t('app', 'Chamber No'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'source_org_code' => Yii::t('app', 'Source Org Code'),
            'source_org_type' => Yii::t('app', 'Source Org Type'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'entry_type' => Yii::t('app', 'Entry Type'),
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
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
