<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_collection_data_alias_reject".
 *
 * @property integer $collection_data_alias_reject_code
 * @property integer $collection_data_alias_code
 * @property string $table_name
 * @property string $action_perform
 * @property string $member_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property integer $bmc_silos_info_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $name
 * @property string $mobile_no
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $purchase_rate_code
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $no_of_can
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $route_code
 * @property string $converted_qty
 * @property string $remarks
 * @property string $sync_status
 * @property integer $converted_qty_mode
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $incentive
 * @property string $deduction
 * @property string $total_amount
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property integer $send_status
 * @property string $transporter_code
 * @property integer $collection_type
 * @property string $date_time_of_testing
 * @property string $converted_can
 * @property integer $doc_no
 * @property string $vehicle_no
 * @property string $route_arrival_time
 * @property string $challan_no
 * @property integer $dispatch_type
 * @property string $destination_code
 * @property string $vehicle_in_time
 * @property string $vehicle_out_time
 * @property integer $destination_type
 * @property string $temperature
 * @property string $old_qty
 * @property string $old_fat
 * @property string $old_snf
 * @property string $old_rtpl
 * @property string $old_clr
 * @property string $old_amount
 * @property string $old_milk_type_code
 * @property string $old_milk_quality_type_code
 * @property integer $old_no_of_can
 * @property integer $old_purchase_rate_code
 * @property string $error_desc
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblCollectionDataAliasReject extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_data_alias_reject';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['collection_data_alias_code', 'bmc_silos_info_code', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty_mode', 'no_of_can', 'qlty_auto', 'qty_auto', 'converted_qty_mode', 'send_status', 'collection_type', 'doc_no', 'dispatch_type', 'destination_type', 'old_no_of_can', 'old_purchase_rate_code', 'originating_type'], 'safe'],
                [['table_name', 'action_perform', 'member_code', 'dcs_code', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'shift_code', 'name', 'mobile_no', 'type_of_data_receive', 'purchase_rate_code', 'route_code', 'remarks', 'sync_status', 'own_bmc_code', 'own_mcc_plant_code', 'transporter_code', 'vehicle_no', 'challan_no', 'destination_code', 'old_milk_type_code', 'old_milk_quality_type_code', 'error_desc', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['fat', 'snf', 'clr', 'water', 'qty', 'rtpl', 'amount', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'converted_can', 'temperature', 'old_qty', 'old_fat', 'old_snf', 'old_rtpl', 'old_clr', 'old_amount', 'is_sms_sent', 'old_customer_code', 'old_route_code'], 'safe'],
                [['date_time_of_collection', 'date_time_of_recieve', 'qlty_time', 'qty_time', 'date_time_of_testing', 'route_arrival_time', 'vehicle_in_time', 'vehicle_out_time', 'created_at', 'updated_at', 'antibiotic', 'can_no', 'old_antibiotic'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'collection_data_alias_reject_code' => Yii::t('app', 'Collection Data Alias Reject Code'),
            'collection_data_alias_code' => Yii::t('app', 'Collection Data Alias Code'),
            'table_name' => Yii::t('app', 'Table Name'),
            'action_perform' => Yii::t('app', 'Action Perform'),
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'route_code' => Yii::t('app', 'Route Code'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'remarks' => Yii::t('app', 'Remarks'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'incentive' => Yii::t('app', 'Incentive'),
            'deduction' => Yii::t('app', 'Deduction'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'send_status' => Yii::t('app', 'Send Status'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'collection_type' => Yii::t('app', 'Collection Type'),
            'date_time_of_testing' => Yii::t('app', 'Date Time Of Testing'),
            'converted_can' => Yii::t('app', 'Converted Can'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'route_arrival_time' => Yii::t('app', 'Route Arrival Time'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'dispatch_type' => Yii::t('app', 'Dispatch Type'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'vehicle_in_time' => Yii::t('app', 'Vehicle In Time'),
            'vehicle_out_time' => Yii::t('app', 'Vehicle Out Time'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'temperature' => Yii::t('app', 'Temperature'),
            'old_qty' => Yii::t('app', 'Old Qty'),
            'old_fat' => Yii::t('app', 'Old Fat'),
            'old_snf' => Yii::t('app', 'Old Snf'),
            'old_rtpl' => Yii::t('app', 'Old Rtpl'),
            'old_clr' => Yii::t('app', 'Old Clr'),
            'old_amount' => Yii::t('app', 'Old Amount'),
            'old_milk_type_code' => Yii::t('app', 'Old Milk Type Code'),
            'old_milk_quality_type_code' => Yii::t('app', 'Old Milk Quality Type Code'),
            'old_no_of_can' => Yii::t('app', 'Old No Of Can'),
            'old_purchase_rate_code' => Yii::t('app', 'Old Purchase Rate Code'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
