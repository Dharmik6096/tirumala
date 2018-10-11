<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_temp_history".
 *
 * @property integer $id
 * @property integer $milk_collection_code
 * @property string $member_code
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
 * @property string $shift
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
 * @property integer $data_post_status
 * @property string $clr
 * @property string $status
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $no_of_can
 * @property integer $milk_quality_type_code
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $route_code
 * @property string $bmc_code
 * @property string $converted_qty
 * @property integer $is_approved
 * @property integer $is_updated
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblMilkCollectionTempHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_temp_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_code', 'milk_type_code', 'sample_no', 'ack', 'data_post_status', 'qty_mode', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'is_approved', 'is_updated'], 'safe'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'status', 'created_by', 'updated_by', 'route_code', 'bmc_code', 'operation_type', 'history_created_by'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty'], 'safe'],
            [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'qlty_time', 'qty_time', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_collection_code' => Yii::t('app', 'Milk Collection Code'),
            'member_code' => Yii::t('app', 'Member Code'),
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
            'shift' => Yii::t('app', 'Shift'),
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
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'clr' => Yii::t('app', 'Clr'),
            'status' => Yii::t('app', 'Status'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'route_code' => Yii::t('app', 'Route Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'is_updated' => Yii::t('app', 'Is Updated'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
