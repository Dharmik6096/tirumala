<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_trip_detail_history".
 *
 * @property integer $id
 * @property string $vehicle_trip_detail_code
 * @property string $vehicle_trip_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $challan_no
 * @property string $transaction_datetime
 * @property string $destination_code
 * @property string $destination_type
 * @property string $source_org_code
 * @property string $source_org_type
 * @property integer $is_last_destination
 * @property string $arrival_time
 * @property string $departure_time
 * @property string $travel_km
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
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblVehicleTripDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_trip_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vehicle_trip_detail_code', 'vehicle_trip_code', 'vehicle_code', 'trip_code', 'challan_no', 'destination_code', 'destination_type', 'source_org_code', 'source_org_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_by'], 'safe'],
                [['transaction_datetime', 'arrival_time', 'departure_time', 'created_at', 'updated_at', 'history_created_at', 'sequence_no'], 'safe'],
                [['is_last_destination', 'originating_type'], 'safe'],
                [['travel_km', 'is_active', 'is_virtual_location'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'vehicle_trip_detail_code' => Yii::t('app', 'Vehicle Trip Detail Code'),
            'vehicle_trip_code' => Yii::t('app', 'Vehicle Trip Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'transaction_datetime' => Yii::t('app', 'Transaction Datetime'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'source_org_code' => Yii::t('app', 'Source Org Code'),
            'source_org_type' => Yii::t('app', 'Source Org Type'),
            'is_last_destination' => Yii::t('app', 'Is Last Destination'),
            'arrival_time' => Yii::t('app', 'Arrival Time'),
            'departure_time' => Yii::t('app', 'Departure Time'),
            'travel_km' => Yii::t('app', 'Travel Km'),
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
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
