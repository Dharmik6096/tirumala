<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
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
 * @property string $customer_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $party_master_code
 */
class TblMilkVehicleEntryHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'vehicle_entry_date', 'arrival_time', 'tare_weight_time', 'created_at', 'updated_at'], 'safe'],
                [['gross_weight', 'tare_weight'], 'safe'],
                [['originating_type'], 'safe'],
                [['operation_type', 'trip_code'], 'safe'],
                [['history_created_by', 'created_by', 'updated_by'], 'safe'],
                [['milk_vehicle_entry_code', 'vehicle_code', 'customer_code', 'customer_type', 'party_master_code'], 'safe'],
                [['grn_no', 'receipt_at'], 'safe'],
                [['qty'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
                [['originating_org_code', 'originating_org_type','receipt_at_code', 'dispatch_from', 'dispatch_from_code', 'receipt_datetime', 'receipt_shift_code', 'tanker_no'], 'safe'],
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
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'receipt_at' => Yii::t('app', 'Receipt At'),
            'vehicle_entry_date' => Yii::t('app', 'Vehicle Entry Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'arrival_time' => Yii::t('app', 'Arrival Time'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'tare_weight_time' => Yii::t('app', 'Tare Weight Time'),
            'qty' => Yii::t('app', 'Qty'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'party_master_code' => Yii::t('app', 'Party Master Code'),
        ];
    }

}
