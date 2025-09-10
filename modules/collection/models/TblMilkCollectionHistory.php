<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_history".
 *
 * @property integer $id
 * @property string $milk_collection_code
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
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblMilkCollectionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['milk_collection_code'], 'required'],
//            [['milk_collection_code', 'member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag'], 'string'],
//            [['milk_type_code', 'sample_no', 'ack'], 'integer'],
//            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount'], 'number'],
                [['milk_collection_code', 'member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'milk_type_code', 'sample_no', 'ack', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'date_time_of_collection', 'date_time_of_recieve', 'history_created_at', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'sms_status', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'dt_date', 'is_approved', 'operation_type', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'device_lat', 'device_long', 'mob_lat', 'mob_long', 'is_sms_sent', 'response_datetime'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'antibiotic_sms_sent'], 'safe'],
                [['adt_param', 'adt_value', 'dpu_rtpl', 'dpu_amount', 'dpu_incentive', 'dpu_deduction', 'dpu_total_amount', 'received_timestamp', 'is_rate_recalc', 'purchase_rate_code_old', 'antibiotic', 'is_antibiotic'], 'safe'],
                [['scheme_rate', 'scheme_rate_code', 'actual_rate', 'other_reading'], 'safe'],
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
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'rate_code' => Yii::t('app', 'Rate Code'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'soc_bmc_flag' => Yii::t('app', 'Soc Bmc Flag'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'antibiotic' => Yii::t('app', 'Antibiotic'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblMilkCollectionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMilkCollectionQuery(get_called_class());
    }

}
