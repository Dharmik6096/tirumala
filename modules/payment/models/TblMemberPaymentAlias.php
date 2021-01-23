<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use yii\helpers\ArrayHelper;
use app\modules\verification\models\TblVerification;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_member_payment_alias".
 *
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
class TblMemberPaymentAlias extends \app\models\ChildModel {

    public $payment_cycle;
    public $otp_code;
    public $net_amount, $old_recovery;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_alias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'adjust_remark', 'payment_status', 'approved_by', 'transfer_mode', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'vsp_payment_reference_no', 'utr_no', 'reference_no', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_by', 'updated_by'], 'string'],
            [['payment_cycle_code', 'payment_cycle_applicabilty_code', 'is_verified'], 'integer'],
            [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'additional_pay'], 'number'],
            [['disburse_date', 'payment_date', 'process_date', 'created_at', 'updated_at', 'payment_cycle', 'otp_code', 'net_amount', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type', 'from_datetime', 'to_datetime', 'from_shift', 'to_shift', 'adjust_recovery', 'recovery', 'old_recovery'], 'safe'],
            [['payment_cycle_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required'],
            [['payment_cycle_code'], 'CheckPendingDisburse', 'skipOnError' => true, 'on' => ['processpayment']],
//            [['payment_cycle_code'], 'CheckFinalAmount', 'skipOnError' => true, 'except' => ['processpayment']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_alias_code' => Yii::t('app', 'Member Payment Alias Code'),
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

    public function getPaymentCycleApplicabilityCode() {
        return $this->hasOne(TblPaymentCycleApplicability::className(), ['payment_cycle_applicabilty_code' => 'payment_cycle_applicabilty_code']);
    }

    public function getTblPaymentCycleApplicability() {
        return $this->hasOne(TblPaymentCycleApplicability::className(), ['payment_cycle_applicabilty_code' => 'payment_cycle_applicabilty_code']);
    }

    public function getSmsRecords() {
        return $this->find()->innerJoinWith('memberCode')->where(['status' => 'disbursed', 'payment_transaction_code' => NULL, 'ack' => null])->andWhere(['and', ['IS NOT', 'tbl_member.mobile_no', NULL], ['<>', 'tbl_member.mobile_no', '']])->limit(2000)->all();
    }

    public function getRecords($checkDcs = true) {
        $query = $this->find()->where(['union_code' => $this->union_code, 'payment_cycle_code' => $this->payment_cycle_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])
                ->andWhere(['!=', 'payment_status', 'Lock']);
        if ($checkDcs) {
            $query->andWhere(['dcs_code' => $this->dcs_code]);
        }
        return $query;
    }

    public function getStatusLockedCount($status) {
        return $this->find()->where(['union_code' => $this->union_code, 'payment_cycle_code' => $this->payment_cycle_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])
                        ->andWhere(['payment_status' => $status])->count();
    }

    public function getExceptData($notIn = []) {
        return $this->find()->where(['union_code' => $this->union_code, 'payment_cycle_code' => $this->payment_cycle_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])
                        ->andWhere(['!=', 'payment_status', 'Lock'])
                        ->andWhere(['NOT IN', 'member_payment_alias_code', $notIn])
                        ->all();
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
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

    public function getData() {
        return $this->find()->where(['payment_cycle_code' => $this->payment_cycle_code, 'bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code])->one();
    }

    public function getNegativeValCount() {
        $query = $this->find()
                ->where(['payment_cycle_code' => $this->payment_cycle_code, 'bmc_code' => $this->bmc_code])
                ->andWhere(['<', 'final_amount', 0]);

        if (!empty($this->dcs_code)) {
            $query->andWhere(['not in', 'dcs_code', $this->dcs_code]);
        }

        return $query->count();
    }

    public function CheckPendingDisburse($attribute, $params) {
        $data = $this->find()
                ->select(['from_datetime', 'to_datetime'])
                ->where(['bmc_code' => $this->bmc_code])
                ->andWhere(['NOT IN', 'payment_cycle_code', $this->payment_cycle_code])
                ->one();
        if (!empty($data)) {
            $from_date = date('d-m-Y', strtotime($data->from_datetime));
            $to_date = date('d-m-Y', strtotime($data->to_datetime));
            $this->addError($attribute, Yii::t('app', "Please first disburse payment cycle $from_date to $to_date ."));
        }
    }

    public function CheckFinalAmount($attribute, $params) {
        if (!empty($this->final_amount) && $this->final_amount < 0) {
            $this->addError($attribute, Yii::t('app', "Net Payble Must Not Negative."));
        }
    }

    public function getRecoverData() {
        $query = $this->find()->where(['payment_cycle_code' => $this->payment_cycle_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code])
                ->andWhere(['!=', 'payment_status', 'Lock'])
                ->andWhere(['>', 'final_amount', '0'])
                ->all();

        return $query;
    }

    public function getMemberWiseData() {
        $query = $this->find()->where(['member_payment_alias_code' => $this->member_payment_alias_code])
                ->andWhere(['!=', 'payment_status', 'Lock'])
                ->one();

        return $query;
    }

}
