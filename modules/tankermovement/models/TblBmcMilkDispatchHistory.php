<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_milk_dispatch_history".
 *
 * @property integer $id
 * @property string $bmc_milk_dispatch_code
 * @property string $challan_no
 * @property string $transaction_date
 * @property string $from_date
 * @property integer $from_shift_code
 * @property string $to_date
 * @property integer $to_shift_code
 * @property string $destination_type
 * @property string $destination_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $driver_name
 * @property string $driver_contact_no
 * @property string $authorizer_name
 * @property string $vehicle_in_time
 * @property string $vehicle_out_time
 * @property string $remarks
 * @property string $gross_weight
 * @property string $tare_weight
 * @property integer $is_last_destination
 * @property integer $purchase_rate_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblBmcMilkDispatchHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_dispatch_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transaction_date', 'from_date', 'to_date', 'vehicle_in_time', 'vehicle_out_time', 'created_at', 'updated_at', 'history_created_at', 'tested_by'], 'safe'],
            [['from_shift_code', 'to_shift_code', 'is_last_destination', 'purchase_rate_code', 'originating_type'], 'safe'],
            [['gross_weight', 'tare_weight'], 'safe'],
            [['bmc_milk_dispatch_code'], 'safe'],
            [['challan_no'], 'safe'],
            [['destination_type'], 'safe'],
            [['destination_code', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['vehicle_code', 'trip_code'], 'safe'],
            [['driver_name', 'authorizer_name'], 'safe'],
            [['driver_contact_no', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['union_code'], 'safe'],
            [['plant_code', 'mcc_plant_code'], 'safe'],
            [['bmc_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['operation_type', 'source_org_code', 'source_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift_code' => Yii::t('app', 'From Shift Code'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift_code' => Yii::t('app', 'To Shift Code'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'driver_name' => Yii::t('app', 'Driver Name'),
            'driver_contact_no' => Yii::t('app', 'Driver Contact No'),
            'authorizer_name' => Yii::t('app', 'Authorizer Name'),
            'vehicle_in_time' => Yii::t('app', 'Vehicle In Time'),
            'vehicle_out_time' => Yii::t('app', 'Vehicle Out Time'),
            'remarks' => Yii::t('app', 'Remarks'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'is_last_destination' => Yii::t('app', 'Is Last Destination'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
