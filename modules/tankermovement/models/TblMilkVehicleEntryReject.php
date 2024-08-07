<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_reject".
 *
 * @property string $milk_vehicle_entry_reject_code
 * @property string $milk_vehicle_entry_code
 * @property string $trip_code
 * @property string $grn_no
 * @property string $receipt_at
 * @property string $vehicle_entry_date
 * @property string $vehicle_code
 * @property string $arrival_time
 * @property string $gross_weight
 * @property string $tare_weight
 * @property string $tare_weight_time
 * @property string $qty
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_code
 * @property integer $customer_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMilkVehicleEntryReject extends \app\models\ChildModel {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_reject';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_code', 'trip_code', 'grn_no', 'receipt_at', 'vehicle_entry_date', 'vehicle_code', 'arrival_time', 'gross_weight', 'tare_weight', 'tare_weight_time', 'qty', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'dispatch_from', 'dispatch_from_code', 'receipt_datetime', 'receipt_shift_code', 'tanker_no', 'receipt_at_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_reject_code' => Yii::t('app', 'Milk Vehicle Entry Reject Code'),
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'grn_no' => Yii::t('app', 'Grn No.'),
            'receipt_at' => Yii::t('app', 'Destination Type'),
            'vehicle_entry_date' => Yii::t('app', 'Vehicle Entry Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'arrival_time' => Yii::t('app', 'Arrival Time'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'tare_weight_time' => Yii::t('app', 'Tare Weight Time'),
            'qty' => Yii::t('app', 'Qty'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Name'),
            'customer_type' => Yii::t('app', 'Type'),
            'dispatch_from' => Yii::t('app', 'Source Type'),
            'dispatch_from_code' => Yii::t('app', 'Source Name'),
            'receipt_datetime' => Yii::t('app', 'Receipt Datetime'),
            'receipt_shift_code' => Yii::t('app', 'Receipt Shift'),
            'tanker_no' => Yii::t('app', 'Tanker No'),
            'receipt_at_code' => Yii::t('app', 'Destination Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
