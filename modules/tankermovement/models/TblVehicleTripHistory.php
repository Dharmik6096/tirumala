<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_trip_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $vehicle_trip_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $grn_no
 * @property string $transaction_date
 * @property string $trip_status
 * @property string $trip_for
 * @property string $trip_mode
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
 */
class TblVehicleTripHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_trip_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'transaction_date', 'created_at', 'updated_at', 'no_of_compartment', 'vehicle_capacity', 'remark'], 'safe'],
                [['originating_type'], 'safe'],
                [['operation_type', 'trip_mode'], 'safe'],
                [['history_created_by', 'created_by', 'updated_by'], 'safe'],
                [['vehicle_trip_code'], 'safe'],
                [['vehicle_code', 'trip_code', 'trip_status'], 'safe'],
                [['grn_no'], 'safe'],
                [['trip_for'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code'], 'safe'],
                [['bmc_code'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_active', 'trip_sub_status', 'sub_status_time', 'driver_name', 'mobile_no', 'is_check_in', 'check_in_type', 'check_in_code', 'check_in_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'vehicle_trip_code' => Yii::t('app', 'Vehicle Trip Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'trip_status' => Yii::t('app', 'Trip Status'),
            'trip_for' => Yii::t('app', 'Trip For'),
            'trip_mode' => Yii::t('app', 'Trip Mode'),
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
            'trip_sub_status' => Yii::t('app', 'Trip Sub Status'),
            'sub_status_time' => Yii::t('app', 'Sub Status Time'),
            'driver_name' => Yii::t('app', 'Driver Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
        ];
    }

}
