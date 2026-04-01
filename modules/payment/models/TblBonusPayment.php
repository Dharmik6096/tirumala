<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_bonus_payment".
 *
 * @property integer $bonus_payment_code
 * @property integer $bonus_payment_summary_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $customer_name
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $qty
 * @property string $amount
 * @property string $addition
 * @property string $deduction
 * @property string $net_payable
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
 * @property string $beneficiary_name
 * @property integer $is_verified
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
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBonusPayment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bonus_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bonus_payment_summary_code'], 'required'],
                [['bonus_payment_summary_code', 'is_verified', 'originating_type'], 'integer'],
                [['kg_fat', 'kg_snf', 'avg_fat', 'avg_snf', 'qty', 'amount', 'addition', 'deduction', 'net_payable', 'disburse_amount'], 'number'],
                [['disburse_date', 'payment_date', 'process_date', 'created_at', 'updated_at'], 'safe'],
                [['customer_type', 'customer_code'], 'string', 'max' => 20],
                [['customer_name', 'bank_name', 'branch_name', 'beneficiary_name'], 'string', 'max' => 100],
                [['status', 'utr_no', 'reference_no', 'bank_status', 'payment_transaction_code'], 'string', 'max' => 50],
                [['bank_code'], 'string', 'max' => 4],
                [['branch_code'], 'string', 'max' => 9],
                [['ifsc', 'bank_account_no', 'reject_reason'], 'string', 'max' => 255],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bonus_payment_code' => Yii::t('app', 'Bonus Payment Code'),
            'bonus_payment_summary_code' => Yii::t('app', 'Bonus Payment Summary Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_name' => Yii::t('app', 'Name'),
            'kg_fat' => Yii::t('app', 'KgFAT'),
            'kg_snf' => Yii::t('app', 'KgSNF'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'qty' => Yii::t('app', 'Qty'),
            'amount' => Yii::t('app', 'Milk Amount'),
            'addition' => Yii::t('app', 'Addition'),
            'deduction' => Yii::t('app', 'Deduction'),
            'net_payable' => Yii::t('app', 'Net Payable'),
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
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'is_verified' => Yii::t('app', 'Is Verified'),
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
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getBonusPaymentSummaryCode() {
        return $this->hasOne(TblBonusPaymentSummary::className(), ['bonus_payment_summary_code' => 'bonus_payment_summary_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

}
