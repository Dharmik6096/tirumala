<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_collection_history".
 *
 * @property integer $id
 * @property integer $milk_collection_code
 * @property string $dcs_code
 * @property string $name
 * @property string $mobile_no
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property string $auto_flag
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $rate_code
 * @property string $error_log
 * @property integer $ack
 * @property string $soc_bmc_flag
 * @property string $dt_date
 * @property string $sms_status
 * @property string $sms_msgid
 * @property string $sms_mobile
 * @property string $sms_errorlog
 * @property string $sms_timestamp
 * @property string $remarks
 * @property string $transporter_code
 * @property string $vehicle_code
 * @property integer $collection_type
 * @property integer $milk_quality_type_code
 * @property string $density
 * @property string $clr
 * @property string $lactose
 * @property string $protein
 * @property integer $qlty_auto
 * @property integer $qty_mode
 * @property integer $qty_auto
 * @property integer $no_of_can
 * @property integer $avg_qlty_param
 * @property string $qlty_time
 * @property integer $qlty_times_no
 * @property string $qty_time
 * @property string $date_time_of_testing
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $converted_qty
 * @property string $route_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblBmcCollectionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_collection_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_code', 'milk_type_code', 'sample_no', 'ack', 'collection_type', 'milk_quality_type_code', 'qlty_auto', 'qty_mode', 'qty_auto', 'no_of_can', 'avg_qlty_param', 'qlty_times_no'], 'integer'],
            [['dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'remarks', 'transporter_code', 'vehicle_code', 'bmc_code', 'created_by', 'updated_by', 'route_code', 'operation_type'], 'string'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'density', 'clr', 'lactose', 'protein', 'converted_qty'], 'number'],
            [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'qlty_time', 'qty_time', 'date_time_of_testing', 'created_at', 'updated_at', 'history_created_at', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc'], 'safe'],
            [['own_mcc_plant_code', 'own_bmc_code', 'converted_qty_mode', 'milk_analyser_type_code', 'ws_code', 'vehicle_no', 'route_arrival_time', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['mcc_plant_code', 'plant_code', 'union_code', 'customer_code', 'customer_type'], 'safe'],
            [['adt_param', 'adt_value'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_collection_code' => Yii::t('app', 'Milk Collection Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'rate_code' => Yii::t('app', 'Rate Code'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'soc_bmc_flag' => Yii::t('app', 'Soc Bmc Flag'),
            'dt_date' => Yii::t('app', 'Dt Date'),
            'sms_status' => Yii::t('app', 'Sms Status'),
            'sms_msgid' => Yii::t('app', 'Sms Msgid'),
            'sms_mobile' => Yii::t('app', 'Sms Mobile'),
            'sms_errorlog' => Yii::t('app', 'Sms Errorlog'),
            'sms_timestamp' => Yii::t('app', 'Sms Timestamp'),
            'remarks' => Yii::t('app', 'Remarks'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'collection_type' => Yii::t('app', 'Collection Type'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'density' => Yii::t('app', 'Density'),
            'clr' => Yii::t('app', 'Clr'),
            'lactose' => Yii::t('app', 'Lactose'),
            'protein' => Yii::t('app', 'Protein'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'avg_qlty_param' => Yii::t('app', 'Avg Qlty Param'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qlty_times_no' => Yii::t('app', 'Qlty Times No'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'date_time_of_testing' => Yii::t('app', 'Date Time Of Testing'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'route_code' => Yii::t('app', 'Route Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
