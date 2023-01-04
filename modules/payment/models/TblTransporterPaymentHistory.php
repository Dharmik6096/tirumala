<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_transporter_payment_history".
 *
 * @property integer $id
 * @property integer $transporter_payment_code
 * @property string $union_code
 * @property string $transporter_code
 * @property integer $transporter_type
 * @property string $from_date
 * @property string $to_date
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
 * @property string $no_of_days
 * @property string $total_kms
 * @property string $avg_rate
 * @property string $total_qty
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $total_addition
 * @property string $net_amount
 * @property string $previous_hold
 * @property string $previous_due
 * @property string $hold_amount
 * @property string $adjust_amount
 * @property string $adjust_remark
 * @property string $final_amount
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property string $payment_date
 * @property string $status
 * @property string $disburse_amount
 * @property string $disburse_date
 * @property string $utr_no
 * @property string $reference_no
 * @property string $route_code
 * @property string $process_date
 * @property string $reject_reason
 * @property string $bank_status
 * @property string $payment_transaction_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblTransporterPaymentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_payment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['transporter_payment_code', 'transporter_type', 'is_verified', 'basic_price', 'billing_type_code'], 'safe'],
                [['union_code', 'transporter_code', 'adjust_remark', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'status', 'utr_no', 'reference_no', 'route_code', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
                [['from_date', 'to_date', 'payment_date', 'disburse_date', 'process_date', 'created_at', 'updated_at', 'history_created_at', 'mcc_plant_code'], 'safe'],
                [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'no_of_days', 'total_kms', 'avg_rate', 'total_qty', 'total_amount', 'total_deduction', 'total_addition', 'net_amount', 'previous_hold', 'previous_due', 'hold_amount', 'adjust_amount', 'final_amount', 'disburse_amount', 'bill_no', 'primary_tpt_cost', 'incentive_value', 'chilling_cost'], 'safe'],
                [['billing_type_code', 'is_day_wise', 'qty_amount', 'total_vts_kms', 'rejected_kg_fat', 'rejected_kg_snf', 'total_rejected_qty', 'total_rejected_amount', 'total_penalty_amount', 'total_least_kms', 'parsing_no', 'transporter_name'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'transporter_type' => Yii::t('app', 'Transporter Type'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
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
            'no_of_days' => Yii::t('app', 'No Of Days'),
            'total_kms' => Yii::t('app', 'Total Kms'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'total_addition' => Yii::t('app', 'Total Addition'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'previous_hold' => Yii::t('app', 'Previous Hold'),
            'previous_due' => Yii::t('app', 'Previous Due'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'adjust_amount' => Yii::t('app', 'Adjust Amount'),
            'adjust_remark' => Yii::t('app', 'Adjust Remark'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'status' => Yii::t('app', 'Status'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'utr_no' => Yii::t('app', 'Utr No'),
            'reference_no' => Yii::t('app', 'Reference No'),
            'route_code' => Yii::t('app', 'Route Code'),
            'process_date' => Yii::t('app', 'Process Date'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'bank_status' => Yii::t('app', 'Bank Status'),
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
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
