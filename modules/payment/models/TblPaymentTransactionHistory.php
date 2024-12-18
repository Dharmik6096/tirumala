<?php

namespace app\modules\payment\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_payment_transaction".
 *
 * @property integer $payment_transaction_code
 * @property string $union_code
 * @property string $code
 * @property string $type
 * @property integer $dcs_payment_cycle_applicabilty_code
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $disburse_amount
 * @property string $disburse_date
 * @property string $payment_date
 * @property string $approved_by
 * @property string $status
 * @property string $transfer_mode
 * @property string $error_code
 * @property string $error_log
 * @property integer $ack
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $qty
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $avg_rate
 * @property integer $dcs_payment_cycle_code
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property integer $member_count
 */
class TblPaymentTransactionHistory extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_transaction_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'name', 'is_file', 'file_id', 'file_datetime', 'disburse_date', 'payment_date', 'created_at', 'updated_at', 'mobile_no', 'sms_log', 'sms_status', 'sms_timestamp', 'sms_msgid', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_approval_code', 'is_approved', 'approved_at', 'union_bank_payment_code', 'history_created_at', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
            'dcs_payment_cycle_applicabilty_code' => Yii::t('app', 'Dcs Payment Cycle Applicabilty Code'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'status' => Yii::t('app', 'Status'),
            'transfer_mode' => Yii::t('app', 'Transfer Mode'),
            'error_code' => Yii::t('app', 'Error Code'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'member_count' => Yii::t('app', 'Member Count'),
            'plant_code' =>  Yii::t('app', 'PLANT'), 
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'Member BMC')
        ];
    }
}
