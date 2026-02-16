<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\payment\models\TblMilkShortageRecovery;
use app\modules\payment\models\TblMemberPaymentHeadSummary;
use app\modules\vsp\models\TblBillHead;
//tbl_member_payment_head_summary

/**
 * This is the model class for table "tbl_member_payment_summary_alias".
 *
 * @property integer $payment_sumary_alias_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property integer $member_count
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
 * @property string $disburse_date
 * @property string $payment_date
 * @property string $payment_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMemberPaymentSummaryAlias extends \app\models\ChildModel {

    public $stop_payment_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_summary_alias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'payment_status', 'created_by', 'updated_by'], 'string'],
                [['member_count', 'payment_cycle_code', 'payment_cycle_applicabilty_code'], 'integer'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount'], 'number'],
                [['disburse_date', 'payment_date', 'created_at', 'updated_at', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type', 'additional_pay', 'from_datetime', 'to_datetime', 'from_shift', 'to_shift', 'adjust_recovery', 'recovery', 'dcs_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_sumary_alias_code' => Yii::t('app', 'Payment Sumary Alias Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_count' => Yii::t('app', 'Member Count'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'qty' => Yii::t('app', 'Total Qty'),
            'avg_fat' => Yii::t('app', 'AvgFAT'),
            'avg_snf' => Yii::t('app', 'AvgSNF'),
            'kg_fat' => Yii::t('app', 'KgFAT'),
            'kg_snf' => Yii::t('app', 'KgSNF'),
            'avg_rate' => Yii::t('app', 'AvgRate'),
            'total_amount' => Yii::t('app', 'Milk Amount(+)'),
            'total_deduction' => Yii::t('app', 'Deduction(-)'),
            'final_amount' => Yii::t('app', 'Net Payable'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'total_addition' => Yii::t('app', 'Addition(+)'),
            'previous_hold' => Yii::t('app', 'Previous Hold(+)'),
            'previous_due' => Yii::t('app', 'Previous Due(-)'),
            'hold_amount' => Yii::t('app', 'Hold Amount(-)'),
            'net_payable' => Yii::t('app', 'Final Pay'),
        ];
    }

    public function getRecords($checkDcs = true) {
        $query = $this->find()->where(['union_code' => $this->union_code, 'payment_cycle_code' => $this->payment_cycle_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])
                ->andWhere(['!=', 'payment_status', 'Lock']);
        if ($checkDcs) {
            $query->andWhere(['dcs_code' => $this->dcs_code]);
        }

        return $query->all();
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getData() {
        return $this->find()->where(['payment_cycle_code' => $this->payment_cycle_code, 'bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code])->one();
    }

    public function getExistingData($model) {
        return $this->find()
                        ->where(['bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'payment_cycle_code' => $model->payment_cycle_code])
                        ->one();
    }

    public function getShortageRecoveryOtherMember() {
        return $this->hasOne(TblMilkShortageRecovery::className(), ['customer_code' => 'dcs_code', 'payment_cycle_code' => 'payment_cycle_code'])->andOnCondition(['customer_type' => 'DCS', 'recovery_type' => 'other_member']);
    }

    public function getShortageRecoveryMpgMember() {
        return $this->hasOne(TblMilkShortageRecovery::className(), ['customer_code' => 'dcs_code', 'payment_cycle_code' => 'payment_cycle_code'])->andOnCondition(['customer_type' => 'DCS', 'recovery_type' => 'mpg_member']);
    }
    
    public function getShortageRecoveredMember() {
        $bill_head_code = $this->getBillHead();
        return $this->hasOne(TblMemberPaymentHeadSummary::className(), ['dcs_code' => 'dcs_code', 'payment_cycle_code' => 'payment_cycle_code'])->andOnCondition(['bill_head_code' => $bill_head_code]);
    }
    
    public function getBillHead() {
        $bill_head = TblBillHead::find()->where(['union_code' => $this->union_code,'bill_head_for'=>'member','default_bill_head_code' => 16,'is_active'=>1])->one();
        return !empty($bill_head) ? $bill_head->bill_head_code : '';
    }    

    public function getPaymentCycleApplicabilityForMemberLock($payment_cycle_code, $bmc_code) {
        try {
            $applicabilityCodes = TblMemberPaymentSummaryAlias::find()
                ->select('payment_cycle_applicabilty_code')
                ->distinct()
                ->where([
                    'bmc_code' => $bmc_code,
                    'payment_cycle_code' => $payment_cycle_code
                ])
                ->column();

            if (!empty($applicabilityCodes)) {
                $applicabilityModels = TblPaymentCycleApplicability::find()
                    ->where(['payment_cycle_applicabilty_code' => $applicabilityCodes])
                    ->all();

                foreach ($applicabilityModels as $model) {
                    $model->process_lock_member = 1;
                    $model->scenario = 'processLock';
                }
                return $applicabilityModels;
            }
        } catch (\Exception $e) {
            return [];
        }
        return [];
    }
}
