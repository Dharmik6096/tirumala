<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_collection_not_exist".
 *
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
 * @property string $converted_can
 * @property string $data_post_id
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property integer $doc_no
 * @property string $tag_1
 * @property string $tag_2
 * @property integer $ftp_txn_log_id
 * @property string $error_desc
 * @property string $originating_type
 * @property string $last_edited_type
 * @property string $ftp_txn_file_name
 * @property string $RouteArivalTime
 * @property string $sync_status
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $own_mcc_plant_code
 * @property string $own_bmc_code
 * @property integer $converted_qty_mode
 * @property string $milk_analyser_type_code
 * @property string $ws_code
 * @property string $vehicle_no
 * @property string $route_arrival_time
 * @property string $purchase_rate_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $adt_param
 * @property string $adt_value
 * @property string $recalculated_code
 * @property integer $bmc_silos_info_code
 * @property string $adulteration_value
 * @property string $adulteration_code
 * @property integer $data_import_code
 * @property string $response_datetime
 * @property string $data_inserted_from
 */
class TblBmcCollectionNotExist extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_collection_not_exist';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_type_code', 'sample_no', 'ack', 'collection_type', 'milk_quality_type_code', 'qlty_auto', 'qty_mode', 'qty_auto', 'no_of_can', 'avg_qlty_param', 'qlty_times_no', 'data_post_status', 'doc_no', 'ftp_txn_log_id', 'converted_qty_mode', 'bmc_silos_info_code', 'data_import_code'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'density', 'clr', 'lactose', 'protein', 'converted_qty', 'converted_can', 'adt_value'], 'safe'],
            [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'qlty_time', 'qty_time', 'date_time_of_testing', 'created_at', 'updated_at', 'picked_datetime', 'route_arrival_time', 'response_datetime', 'can_no'], 'safe'],
            [['remarks'], 'safe'],
            [['dcs_code', 'bmc_code', 'plant_code', 'mcc_plant_code', 'own_mcc_plant_code', 'own_bmc_code'], 'safe'],
            [['name'], 'safe'],
            [['mobile_no', 'resp_status', 'resp_desc', 'error_desc', 'ftp_txn_file_name', 'ws_code', 'adt_param'], 'safe'],
            [['auto_flag', 'soc_bmc_flag'], 'safe'],
            [['shift_code'], 'safe'],
            [['village_code'], 'safe'],
            [['type_of_data_receive', 'error_log', 'sms_msgid', 'RouteArivalTime', 'adulteration_value', 'adulteration_code', 'data_inserted_from'], 'safe'],
            [['rate_code'], 'safe'],
            [['sms_status', 'route_code', 'tag_1', 'tag_2'], 'safe'],
            [['sms_mobile', 'vehicle_code', 'milk_analyser_type_code', 'customer_type', 'customer_code'], 'safe'],
            [['sms_errorlog'], 'safe'],
            [['transporter_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['data_post_id'], 'safe'],
            [['originating_type', 'last_edited_type', 'sync_status'], 'safe'],
            [['union_code'], 'safe'],
            [['vehicle_no', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['purchase_rate_code', 'recalculated_code', 'antibiotic', 'tare_weight', 'gross_weight'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
            'converted_can' => Yii::t('app', 'Converted Can'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'tag_1' => Yii::t('app', 'Tag 1'),
            'tag_2' => Yii::t('app', 'Tag 2'),
            'ftp_txn_log_id' => Yii::t('app', 'Ftp Txn Log ID'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'last_edited_type' => Yii::t('app', 'Last Edited Type'),
            'ftp_txn_file_name' => Yii::t('app', 'Ftp Txn File Name'),
            'RouteArivalTime' => Yii::t('app', 'Route Arival Time'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'route_arrival_time' => Yii::t('app', 'Route Arrival Time'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'recalculated_code' => Yii::t('app', 'Recalculated Code'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'adulteration_value' => Yii::t('app', 'Adulteration Value'),
            'adulteration_code' => Yii::t('app', 'Adulteration Code'),
            'data_import_code' => Yii::t('app', 'Data Import Code'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'data_inserted_from' => Yii::t('app', 'Data Inserted From'),
        ];
    }

}
