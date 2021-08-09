<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_audit".
 *
 * @property integer $milk_collection_audit_code
 * @property string $member_code
 * @property string $dcs_code
 * @property string $name
 * @property string $mobile_no
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $auto_flag
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
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
 * @property integer $no_of_can
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_approved
 * @property string $data_post_id
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $picked_datetime
 * @property string $ftp_txn_file_name
 * @property string $tag_1
 * @property string $tag_2
 * @property integer $ftp_txn_log_id
 * @property string $error_desc
 * @property integer $originating_type
 * @property string $last_edited_type
 * @property string $remarks
 * @property string $sync_status
 * @property string $union_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property integer $send_status
 * @property string $response_datetime
 * @property integer $txfarmer_id
 * @property string $data_inserted_from
 * @property integer $is_provisional
 * @property string $device_lat
 * @property string $device_long
 * @property string $mob_lat
 * @property string $mob_long
 * @property string $dpu_rtpl
 * @property string $dpu_amount
 * @property string $dpu_incentive
 * @property string $dpu_deduction
 * @property string $dpu_total_amount
 * @property integer $is_rate_recalc
 * @property string $purchase_rate_code_old
 */
class TblMilkCollectionAudit extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_audit';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_type_code', 'sample_no', 'ack', 'data_post_status', 'no_of_can', 'is_approved', 'ftp_txn_log_id', 'originating_type', 'send_status', 'txfarmer_id', 'is_provisional', 'is_rate_recalc'], 'safe'],
                [['fat', 'snf', 'water', 'clr', 'dpu_rtpl', 'dpu_amount', 'dpu_incentive', 'dpu_deduction', 'dpu_total_amount'], 'safe'],
                [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime'], 'safe'],
                [['remarks', 'device_lat', 'device_long', 'mob_lat', 'mob_long'], 'safe'],
                [['member_code'], 'safe'],
                [['dcs_code'], 'safe'],
                [['name'], 'safe'],
                [['mobile_no', 'sms_mobile', 'resp_status', 'resp_desc', 'ftp_txn_file_name', 'error_desc', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['auto_flag', 'soc_bmc_flag'], 'safe'],
                [['shift_code'], 'safe'],
                [['village_code'], 'safe'],
                [['type_of_data_receive', 'error_log', 'sms_msgid', 'data_inserted_from'], 'safe'],
                [['sms_status', 'tag_1', 'tag_2'], 'safe'],
                [['sms_errorlog'], 'safe'],
                [['status'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['data_post_id'], 'safe'],
                [['last_edited_type', 'sync_status'], 'safe'],
                [['union_code'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['purchase_rate_code_old'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_audit_code' => Yii::t('app', 'Milk Collection Audit Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'water' => Yii::t('app', 'Water'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
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
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'ftp_txn_file_name' => Yii::t('app', 'Ftp Txn File Name'),
            'tag_1' => Yii::t('app', 'Tag 1'),
            'tag_2' => Yii::t('app', 'Tag 2'),
            'ftp_txn_log_id' => Yii::t('app', 'Ftp Txn Log ID'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'last_edited_type' => Yii::t('app', 'Last Edited Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'union_code' => Yii::t('app', 'Union Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'send_status' => Yii::t('app', 'Send Status'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'txfarmer_id' => Yii::t('app', 'Txfarmer ID'),
            'data_inserted_from' => Yii::t('app', 'Data Inserted From'),
            'is_provisional' => Yii::t('app', 'Is Provisional'),
            'device_lat' => Yii::t('app', 'Device Lat'),
            'device_long' => Yii::t('app', 'Device Long'),
            'mob_lat' => Yii::t('app', 'Mob Lat'),
            'mob_long' => Yii::t('app', 'Mob Long'),
            'dpu_rtpl' => Yii::t('app', 'Dpu Rtpl'),
            'dpu_amount' => Yii::t('app', 'Dpu Amount'),
            'dpu_incentive' => Yii::t('app', 'Dpu Incentive'),
            'dpu_deduction' => Yii::t('app', 'Dpu Deduction'),
            'dpu_total_amount' => Yii::t('app', 'Dpu Total Amount'),
            'is_rate_recalc' => Yii::t('app', 'Is Rate Recalc'),
            'purchase_rate_code_old' => Yii::t('app', 'Purchase Rate Code Old'),
        ];
    }

}
