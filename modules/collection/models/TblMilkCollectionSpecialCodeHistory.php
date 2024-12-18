<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_special_code_history".
 *
 * @property integer $id
 * @property integer $milk_collection_special_code
 * @property string $member_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property integer $shift_code
 * @property string $date_time_of_collection
 * @property integer $sample_no
 * @property string $purchase_rate_code
 * @property string $clr
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $milk_quality_type_code
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $route_code
 * @property string $converted_qty
 * @property string $version_no
 * @property integer $converted_qty_mode
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property integer $dcs_payment_cycle_code
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $incentive
 * @property string $deduction
 * @property string $total_amount
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $is_sms_sent
 * @property string $antibiotic
 * @property integer $is_antibiotic
 * @property string $other_reading
 * @property string $received_timestamp
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMilkCollectionSpecialCodeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_special_code_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'own_bmc_code', 'own_mcc_plant_code', 'operation_type', 'other_reading', 'member_code', 'version_no', 'date_time_of_collection', 'qlty_time', 'qty_time', 'received_timestamp', 'created_at', 'updated_at', 'history_created_at', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'adt_value', 'purchase_rate_code', 'milk_collection_special_code', 'milk_type_code', 'shift_code', 'sample_no', 'qty_mode', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'converted_qty_mode', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'is_sms_sent', 'is_antibiotic', 'originating_type', 'antibiotic', 'adt_param', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'history_created_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_collection_special_code' => Yii::t('app', 'Milk Collection Special Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'clr' => Yii::t('app', 'Clr'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'route_code' => Yii::t('app', 'Route Code'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'version_no' => Yii::t('app', 'Version No'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'incentive' => Yii::t('app', 'Incentive'),
            'deduction' => Yii::t('app', 'Deduction'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'is_sms_sent' => Yii::t('app', 'Is Sms Sent'),
            'antibiotic' => Yii::t('app', 'Antibiotic'),
            'is_antibiotic' => Yii::t('app', 'Is Antibiotic'),
            'other_reading' => Yii::t('app', 'Other Reading'),
            'received_timestamp' => Yii::t('app', 'Received Timestamp'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
