<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_milk_dispatch".
 *
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
 */
class TblBmcMilkDispatch extends \app\models\ChildModel {

    public $transporter_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_milk_dispatch_code'], 'required'],
            [['bmc_milk_dispatch_code', 'challan_no', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'driver_name', 'driver_contact_no', 'authorizer_name', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['transaction_date', 'from_date', 'to_date', 'vehicle_in_time', 'vehicle_out_time', 'created_at', 'updated_at'], 'safe'],
            [['from_shift_code', 'to_shift_code', 'is_last_destination', 'purchase_rate_code', 'originating_type'], 'integer'],
            [['gross_weight', 'tare_weight'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift_code' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift_code' => Yii::t('app', 'To Shift'),
            'destination_type' => Yii::t('app', 'Dest. Type'),
            'destination_code' => Yii::t('app', 'Dest. Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'driver_name' => Yii::t('app', 'Driver Name'),
            'driver_contact_no' => Yii::t('app', 'Driver Contact No'),
            'authorizer_name' => Yii::t('app', 'Authorizer Name'),
            'vehicle_in_time' => Yii::t('app', 'In Time'),
            'vehicle_out_time' => Yii::t('app', 'Out Time'),
            'remarks' => Yii::t('app', 'Remarks'),
            'gross_weight' => Yii::t('app', 'Gross Wt.'),
            'tare_weight' => Yii::t('app', 'Tare Wt.'),
            'is_last_destination' => Yii::t('app', 'Is Last Destination'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
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
        ];
    }

}
