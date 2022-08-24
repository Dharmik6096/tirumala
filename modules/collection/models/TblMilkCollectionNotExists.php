<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_not_exists".
 *
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
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $purchase_rate_code
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
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $version_no
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $converted_qty_mode
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property integer $dcs_payment_cycle_code
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $incentive
 * @property string $deduction
 * @property string $total_amount
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property integer $send_status
 * @property string $response_datetime
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $is_provisional
 * @property integer $txfarmer_id
 * @property string $data_inserted_from
 */
class TblMilkCollectionNotExists extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_not_exists';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_type_code', 'sample_no', 'ack', 'data_post_status', 'qty_mode', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'is_approved', 'ftp_txn_log_id', 'originating_type', 'converted_qty_mode', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'send_status', 'is_provisional', 'txfarmer_id', 'is_sms_sent'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'adt_value'], 'safe'],
            [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'qlty_time', 'qty_time', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime'], 'safe'],
            [['remarks'], 'safe'],
            [['member_code', 'version_no'], 'safe'],
            [['dcs_code', 'bmc_code', 'plant_code', 'mcc_plant_code'], 'safe'],
            [['name'], 'safe'],
            [['mobile_no', 'sms_mobile', 'resp_status', 'resp_desc', 'ftp_txn_file_name', 'error_desc', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'adt_param'], 'safe'],
            [['auto_flag', 'soc_bmc_flag'], 'safe'],
            [['shift_code'], 'safe'],
            [['village_code'], 'safe'],
            [['type_of_data_receive', 'error_log', 'sms_msgid', 'data_inserted_from'], 'safe'],
            [['purchase_rate_code'], 'safe'],
            [['sms_status', 'route_code', 'tag_1', 'tag_2', 'own_bmc_code', 'own_mcc_plant_code'], 'safe'],
            [['sms_errorlog'], 'safe'],
            [['status'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['data_post_id'], 'safe'],
            [['last_edited_type', 'sync_status'], 'safe'],
            [['union_code'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'antibiotic'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
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
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'version_no' => Yii::t('app', 'Version No'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'incentive' => Yii::t('app', 'Incentive'),
            'deduction' => Yii::t('app', 'Deduction'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'send_status' => Yii::t('app', 'Send Status'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'is_provisional' => Yii::t('app', 'Is Provisional'),
            'txfarmer_id' => Yii::t('app', 'Txfarmer ID'),
            'data_inserted_from' => Yii::t('app', 'Data Inserted From'),
        ];
    }

}
