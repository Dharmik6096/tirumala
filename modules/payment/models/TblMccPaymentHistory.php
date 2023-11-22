<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_payment_history".
 *
 * @property integer $id
 * @property integer $mcc_payment_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property integer $payment_cycle_code
 * @property integer $payment_cycle_applicabilty_code
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $total_qty
 * @property string $total_loss
 * @property string $amount
 * @property string $addition
 * @property string $deduction
 * @property string $net_payable
 * @property string $adjust_amount
 * @property string $final_pay
 * @property string $adjust_remark
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $status
 * @property string $disburse_amount
 * @property string $disburse_date
 * @property string $payment_date
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property string $utr_no
 * @property string $reference_no
 * @property string $process_date
 * @property string $reject_reason
 * @property string $bank_status
 * @property string $payment_transaction_code
 * @property string $previous_hold
 * @property string $previous_due
 * @property string $hold_amount
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $billing_type
 * @property string $billing_based_on
 * @property string $commission_param1
 * @property string $commission_param2
 * @property string $deviation_penalty
 * @property string $deviation_max_cap
 * @property integer $member_billing_lock_check
 * @property string $adjust_recovery
 * @property string $recovery
 * @property string $route_code
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $std_qty
 * @property string $pan_no
 * @property string $gst_no
 * @property string $address
 */
class TblMccPaymentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_payment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_payment_code', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'is_verified', 'originating_type', 'from_shift', 'to_shift', 'member_billing_lock_check'], 'safe'],
            [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay', 'disburse_amount', 'previous_hold', 'previous_due', 'hold_amount', 'commission_param1', 'commission_param2', 'deviation_penalty', 'deviation_max_cap', 'adjust_recovery', 'recovery', 'avg_fat', 'avg_snf', 'std_qty'], 'safe'],
            [['created_at', 'updated_at', 'disburse_date', 'payment_date', 'process_date', 'history_created_at', 'from_datetime', 'to_datetime'], 'safe'],
            [['union_code'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'pan_no'], 'safe'],
            [['customer_type', 'customer_code', 'bank_status', 'payment_transaction_code', 'billing_type', 'billing_based_on', 'gst_no'], 'safe'],
            [['adjust_remark', 'bank_name', 'branch_name', 'reject_reason'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['status', 'utr_no', 'reference_no'], 'safe'],
            [['bank_code'], 'safe'],
            [['branch_code'], 'safe'],
            [['ifsc', 'bank_account_no'], 'safe'],
            [['operation_type'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['route_code'], 'safe'],
            [['address'], 'safe'],
            [['pin_code', 'aadhaar_no', 'contact_person_name', 'mobile_no', 'beneficiary_name', ' pan_no', 'gst_no', 'bmc_collection_amount', 'state_code', 'sub_district_code', 'village_code', 'hamlet_code', 'district_code', 'minimum_qty', 'minimum_qty_amount', 'total_qty_amount', 'transfered_qty', 'billing_qty', 'received_qty', 'bmc_collection_qty', 'received_amount', 'transfered_amount', 'billing_qty_amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'mcc_payment_code' => Yii::t('app', 'Mcc Payment Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'total_loss' => Yii::t('app', 'Total Loss'),
            'amount' => Yii::t('app', 'Amount'),
            'addition' => Yii::t('app', 'Addition'),
            'deduction' => Yii::t('app', 'Deduction'),
            'net_payable' => Yii::t('app', 'Net Payable'),
            'adjust_amount' => Yii::t('app', 'Adjust Amount'),
            'final_pay' => Yii::t('app', 'Final Pay'),
            'adjust_remark' => Yii::t('app', 'Adjust Remark'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'utr_no' => Yii::t('app', 'Utr No'),
            'reference_no' => Yii::t('app', 'Reference No'),
            'process_date' => Yii::t('app', 'Process Date'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'bank_status' => Yii::t('app', 'Bank Status'),
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
            'previous_hold' => Yii::t('app', 'Previous Hold'),
            'previous_due' => Yii::t('app', 'Previous Due'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'billing_type' => Yii::t('app', 'Billing Type'),
            'billing_based_on' => Yii::t('app', 'Billing Based On'),
            'commission_param1' => Yii::t('app', 'Commission Param1'),
            'commission_param2' => Yii::t('app', 'Commission Param2'),
            'deviation_penalty' => Yii::t('app', 'Deviation Penalty'),
            'deviation_max_cap' => Yii::t('app', 'Deviation Max Cap'),
            'member_billing_lock_check' => Yii::t('app', 'Member Billing Lock Check'),
            'adjust_recovery' => Yii::t('app', 'Adjust Recovery'),
            'recovery' => Yii::t('app', 'Recovery'),
            'route_code' => Yii::t('app', 'Route Code'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'std_qty' => Yii::t('app', 'Std Qty'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'address' => Yii::t('app', 'Address'),
            'total_qty_amount' => Yii::t('app', 'Total Qty Amount'),
            'minimum_qty' => Yii::t('app', 'Minimum Qty'),
            'minimum_qty_amount' => Yii::t('app', 'Minimum Qty Amount'),
        ];
    }
}
