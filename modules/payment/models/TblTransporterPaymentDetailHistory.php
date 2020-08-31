<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_transporter_payment_detail_history".
 *
 * @property integer $id
 * @property integer $payment_detail_code
 * @property integer $transporter_payment_code
 * @property string $vehicle_code
 * @property string $parsing_no
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $coll_qty
 * @property string $coll_kg_fat
 * @property string $coll_kg_snf
 * @property string $disp_qty
 * @property string $disp_kg_fat
 * @property string $disp_kg_snf
 * @property string $rec_qty
 * @property string $rec_kg_fat
 * @property string $rec_kg_snf
 * @property string $cd_qty_diff
 * @property string $cd_kg_fat_diff
 * @property string $cd_kg_snf_diff
 * @property string $rd_qty_diff
 * @property string $rd_kg_fat_diff
 * @property string $rd_kg_snf_diff
 * @property string $avg_rate
 * @property string $morning_qty
 * @property string $evening_qty
 * @property string $qty
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $extra_kms
 * @property string $total_kms
 * @property string $km_rate
 * @property string $fuel_rate
 * @property string $fuel_consumption
 * @property string $amount
 * @property string $toll_amount
 * @property string $fastag_amount
 * @property string $fixed_amount
 * @property string $other_amount
 * @property string $total_amount
 * @property string $dispatch_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblTransporterPaymentDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_payment_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_detail_code', 'transporter_payment_code'], 'integer'],
            [['vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'string'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'avg_rate', 'morning_qty', 'evening_qty', 'qty', 'morning_kms', 'evening_kms', 'extra_kms', 'total_kms', 'km_rate', 'fuel_rate', 'fuel_consumption', 'amount', 'toll_amount', 'fastag_amount', 'fixed_amount', 'other_amount', 'total_amount'], 'number'],
            [['dispatch_date', 'created_at', 'updated_at', 'history_created_at', 'weighing_cost'], 'safe'],
            [['rejected_kg_fat', 'rejected_kg_snf', 'morning_rejected_qty', 'evening_rejected_qty', 'rejected_qty', 'rejected_amount', 'qty_amount', 'morning_vts_kms', 'evening_vts_kms', 'total_vts_kms', 'primary_tpt_cost', 'morning_late_minute', 'morning_applicable_penalty', 'morning_penalty_amount', 'evening_late_minute', 'evening_applicable_penalty', 'evening_penalty_amount', 'penalty_amount', 'morning_least_kms', 'evening_least_kms', 'total_least_kms', 'incentive_value', 'e_basic_price', 'm_basic_price', 'chilling_cost', 'basic_price'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_detail_code' => Yii::t('app', 'Payment Detail Code'),
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'parsing_no' => Yii::t('app', 'Parsing No'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'coll_qty' => Yii::t('app', 'Coll Qty'),
            'coll_kg_fat' => Yii::t('app', 'Coll Kg Fat'),
            'coll_kg_snf' => Yii::t('app', 'Coll Kg Snf'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg Fat'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg Snf'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Kg Fat'),
            'rec_kg_snf' => Yii::t('app', 'Rec Kg Snf'),
            'cd_qty_diff' => Yii::t('app', 'Cd Qty Diff'),
            'cd_kg_fat_diff' => Yii::t('app', 'Cd Kg Fat Diff'),
            'cd_kg_snf_diff' => Yii::t('app', 'Cd Kg Snf Diff'),
            'rd_qty_diff' => Yii::t('app', 'Rd Qty Diff'),
            'rd_kg_fat_diff' => Yii::t('app', 'Rd Kg Fat Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'Rd Kg Snf Diff'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'morning_qty' => Yii::t('app', 'Morning Qty'),
            'evening_qty' => Yii::t('app', 'Evening Qty'),
            'qty' => Yii::t('app', 'Qty'),
            'morning_kms' => Yii::t('app', 'Morning Kms'),
            'evening_kms' => Yii::t('app', 'Evening Kms'),
            'extra_kms' => Yii::t('app', 'Extra Kms'),
            'total_kms' => Yii::t('app', 'Total Kms'),
            'km_rate' => Yii::t('app', 'Km Rate'),
            'fuel_rate' => Yii::t('app', 'Fuel Rate'),
            'fuel_consumption' => Yii::t('app', 'Fuel Consumption'),
            'amount' => Yii::t('app', 'Amount'),
            'toll_amount' => Yii::t('app', 'Toll Amount'),
            'fastag_amount' => Yii::t('app', 'Fastag Amount'),
            'fixed_amount' => Yii::t('app', 'Fixed Amount'),
            'other_amount' => Yii::t('app', 'Other Amount'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
