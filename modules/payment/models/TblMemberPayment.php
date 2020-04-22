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
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;

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
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'adjust_remark', 'payment_status', 'approved_by', 'transfer_mode', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'vsp_payment_reference_no', 'utr_no', 'reference_no', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_by', 'updated_by'], 'safe'],
                [['payment_cycle_code', 'payment_cycle_applicabilty_code', 'is_verified'], 'integer'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'additional_pay'], 'number'],
                [['disburse_date', 'payment_date', 'process_date', 'created_at', 'updated_at'], 'safe'],
                [['payment_cycle_code', 'bmc_code', 'dcs_code'], 'required'],
                [['disburse_date', 'payment_date', 'payment_cycle', 'created_at', 'created_by', 'updated_at', 'updated_by', 'qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'dcs_payment_cycle_code', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'is_verified', 'vsp_payment_reference_no', 'additional_pay', 'transfer_mode', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
                [['additional_pay'], 'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_code' => Yii::t('app', 'Member Payment Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'Society'),
            'member_code' => Yii::t('app', 'Member'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'total_amount' => Yii::t('app', 'Milk Amount(+)'),
            'total_deduction' => Yii::t('app', 'Deduction(-)'),
            'final_amount' => Yii::t('app', 'Net Payable'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'additional_pay' => Yii::t('app', 'Additional Pay(+)'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'payment_status' => Yii::t('app', 'Status'),
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
            'qty' => Yii::t('app', 'Total Qty'),
            'avg_fat' => Yii::t('app', 'AvgFAT'),
            'avg_snf' => Yii::t('app', 'AvgSNF'),
            'kg_fat' => Yii::t('app', 'KgFAT'),
            'kg_snf' => Yii::t('app', 'KgSNF'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'adjust_remark' => Yii::t('app', 'Remarks'),
            'total_addition' => Yii::t('app', 'Addition(+)'),
            'previous_hold' => Yii::t('app', 'Previous Hold(+)'),
            'previous_due' => Yii::t('app', 'Previous Due(-)'),
            'hold_amount' => Yii::t('app', 'Hold Amount(-)'),
            'net_payable' => Yii::t('app', 'Final Pay'),
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
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getData() {
        return $this->find()->where(['payment_cycle_code' => $this->payment_cycle_code, 'bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code])->one();
    }

}
