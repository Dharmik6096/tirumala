<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use yii\helpers\ArrayHelper;
use app\modules\verification\models\TblVerification;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
/**
 * This is the model class for table "tbl_member_payment".
 *
 * @property integer $member_payment_code
 * @property string $union_code
 * @property string $dcs_code
 * @property integer $dcs_payment_cycle_applicabilty_code
 * @property integer $dcs_payment_cycle_code
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $disburse_amount
 * @property string $disburse_date
 * @property string $member_code
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

 */
class TblMemberPayment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $payment_cycle;
    public $otp_code;
    public $net_amount;

    public static function tableName() {
        return 'tbl_member_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'member_code', 'approved_by', 'status', 'error_code', 'error_log'], 'string'],
            [['dcs_payment_cycle_applicabilty_code', 'ack'], 'integer'],
            [['total_amount', 'total_deduction', 'final_amount', 'disburse_amount'], 'number'],
            [['disburse_date', 'payment_date', 'payment_cycle', 'created_at', 'created_by', 'updated_at', 'updated_by', 'qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'dcs_payment_cycle_code', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'is_verified', 'vsp_payment_reference_no', 'adjust_amount', 'transfer_mode'], 'safe'],
            [['dcs_payment_cycle_code', 'dcs_code'], 'required'],
            [['adjust_amount'], 'double'],
            [['utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'adjust_remark','payment_transaction_code'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_code' => Yii::t('app', 'Member Payment Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'Society'),
            'dcs_payment_cycle_applicabilty_code' => Yii::t('app', 'Dcs Payment Cycle Applicabilty Code'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'member_code' => Yii::t('app', 'Member'),
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
            'avg_fat' => Yii::t('app', 'Avg FAT'),
            'avg_snf' => Yii::t('app', 'Avg SNF'),
            'kg_fat' => Yii::t('app', 'Kg FAT'),
            'kg_snf' => Yii::t('app', 'Kg SNF'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'adjust_remark' => Yii::t('app', 'Remarks'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblMemberPaymentQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMemberPaymentQuery(get_called_class());
    }

    //relationship with dcs
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    //relationship with dcs
    public function getPaymentCycleCode() {
        return $this->hasOne(TblDcsPaymentCycle::className(), ['dcs_payment_cycle_code' => 'dcs_payment_cycle_code']);
    }

    public function getBankCode() {
        return $this->hasMany(TblBanks::className(), ['bank_code' => 'bank_code'])->via('memberCode');
    }

    public function uniqueCycle() {
        return ArrayHelper::map($this->find()->select(['from_date,to_date'])->where(['is_lock' => 0])->distinct()->all(), 'from_date-todate', 'from_date-todate');
    }

    public function getCode() {
        $val = (new \yii\db\Query)
                ->select(["convert(int,MAX(substring(member_payment_code,4,8))) as member_payment_code"])
                ->from('tbl_member_payment')
                ->where('union_code =' . trim($this->union_code))
                ->one();
        $code = (int) $val['member_payment_code'] + 1;
        return $this->union_code . str_pad($code, 8, '0', STR_PAD_LEFT);
    }

    public function getVerifiedBank() {
        return $this->hasOne(TblVerification::className(), ['module_id' => 'member_code'])
                        ->where(['module_name' => 'TblMember', 'module_field' => 'bank_account_no', 'status' => 1, 'is_verified' => 1]);
    }

    public function getRejectedBank() {
        return $this->hasOne(TblVerification::className(), ['module_id' => 'member_code'])
                        ->where(['module_name' => 'TblMember', 'module_field' => 'bank_account_no', 'status' => 2, 'is_verified' => 1]);
    }

    public function getRecords() {
        return $this->find()->where(['union_code' => $this->union_code, 'dcs_payment_cycle_code' => $this->dcs_payment_cycle_code, 'dcs_code' => $this->dcs_code]);
    }

    public function getPaymentCycleApplicabilityCode() {
        return $this->hasOne(TblDcsPaymentCycleApplicability::className(), ['dcs_payment_cycle_applicabilty_code' => 'dcs_payment_cycle_applicabilty_code']);
    }
}
