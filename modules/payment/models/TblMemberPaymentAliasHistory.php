<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_member_payment_alias_history".
 *
 * @property integer $id
 * @property integer $member_payment_alias_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property integer $payment_cycle_code
 * @property integer $payment_cycle_applicabilty_code
 * @property string $qty
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $avg_rate
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $disburse_amount
 * @property string $additional_pay
 * @property string $adjust_remark
 * @property string $disburse_date
 * @property string $payment_date
 * @property string $payment_status
 * @property string $approved_by
 * @property string $transfer_mode
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property string $vsp_payment_reference_no
 * @property string $utr_no
 * @property string $reference_no
 * @property string $process_date
 * @property string $reject_reason
 * @property string $bank_status
 * @property string $payment_transaction_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMemberPaymentAliasHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_alias_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_payment_alias_code', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'is_verified', 'from_datetime', 'to_datetime', 'from_shift', 'to_shift', 'adjust_recovery', 'recovery'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'adjust_remark', 'payment_status', 'approved_by', 'transfer_mode', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'vsp_payment_reference_no', 'utr_no', 'reference_no', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_by', 'updated_by'], 'safe'],
            [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'additional_pay', 'operation_type', 'history_created_at', 'history_created_by'], 'safe'],
            [['disburse_date', 'payment_date', 'process_date', 'created_at', 'updated_at', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type', 'member_name', 'beneficiary_name', 'dcs_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_payment_alias_code' => Yii::t('app', 'Member Payment Alias Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'additional_pay' => Yii::t('app', 'Adjust Amount'),
            'adjust_remark' => Yii::t('app', 'Adjust Remark'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'transfer_mode' => Yii::t('app', 'Transfer Mode'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'vsp_payment_reference_no' => Yii::t('app', 'Vsp Payment Reference No'),
            'utr_no' => Yii::t('app', 'Utr No'),
            'reference_no' => Yii::t('app', 'Reference No'),
            'process_date' => Yii::t('app', 'Process Date'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'bank_status' => Yii::t('app', 'Bank Status'),
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
