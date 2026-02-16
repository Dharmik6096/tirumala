<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_vsp_payment".
 *
 * @property integer $vsp_payment_code
 * @property string $dcs_code
 * @property string $union_code
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
 */
class TblVspPayment extends \app\models\ChildModel {

    public $otp_code, $customer_ex_code, $old_recovery, $new_recovery, $total_recovery;
    public $multiple_bmc, $stop_payment_type;
    public $stop_payment_only = 0;
    public $payment_release_type;
    public $payment_type, $payment_sumary_code, $types_title;
    public $union_bank_payment_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['vsp_payment_code', 'union_code', 'adjust_remark', 'created_by', 'updated_by', 'status', 'from_datetime', 'from_shift', 'to_datetime', 'to_shift', 'billing_type', 'bmc_code', 'customer_type', 'payment_cycle_code', 'multiple_bmc'], 'safe'],
                [['payment_cycle_code', 'payment_cycle_applicabilty_code', 'old_recovery', 'new_recovery', 'total_recovery', 'union_bank_payment_code'], 'safe'],
                [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay', 'previous_hold', 'previous_due', 'hold_amount', 'adjust_recovery', 'recovery'], 'safe'],
                [['created_at', 'updated_at', 'dcs_code', 'bmc_code', 'customer_code', 'customer_type', 'plant_code', 'mcc_plant_code', 'p_customer_type', 'types_title'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'except' => ['finalize_payment']],
                [['bmc_code'], 'required', 'on' => ['remuneration', 'processpayment']],
//            [['payment_cycle_code'], 'required', 'on' => ['processpayment']],
            [['payment_cycle_code'], 'required', 'except' => ['remuneration', 'unreleasepaymentsearch', 'paymenttypevendor', 'unreleasePaymentUpdate', 'finalize_payment']],
                [['payment_cycle_code'], 'CheckPendingDisburse', 'skipOnError' => true, 'on' => ['processpayment']],
                [['route_code', 'avg_fat', 'avg_snf', 'std_qty', 'customer_name', 'beneficiary_name', 'payment_type'], 'safe'],
                [['payment_release_type'], 'safe'],
                [['payment_type'], 'required', 'on' => ['unreleasepaymentsearch', 'paymenttypevendor']],
//            [['customer_type'], 'required', 'on' => ['paymenttypevendor','paymentdisburse']],
            [['release_date'], 'validateReleaseDate', 'on' => 'unreleasePaymentUpdate'],
            [['vsp_payment_code'], 'checkBankValidate', 'on' => ['finalize_payment']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblVspPayment', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vsp_payment_code' => Yii::t('app', 'Vsp Payment Code'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'kg_fat' => Yii::t('app', 'KgFAT'),
            'kg_snf' => Yii::t('app', 'KgSNF'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'total_loss' => Yii::t('app', 'Total Loss'),
            'amount' => Yii::t('app', 'Milk Amount(+)'),
            'addition' => Yii::t('app', 'Addition(+)'),
            'deduction' => Yii::t('app', 'Deduction(-)'),
            'net_payable' => Yii::t('app', 'Net Payable'),
            'adjust_amount' => Yii::t('app', 'Additional Pay(+)'),
            'final_pay' => Yii::t('app', 'Final Pay'),
            'adjust_remark' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'previous_hold' => Yii::t('app', 'Previous Hold(+)'),
            'previous_due' => Yii::t('app', 'Previous Due(-)'),
            'hold_amount' => Yii::t('app', 'Hold Amount(-)'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Code'),
            'customer_type' => Yii::t('app', 'Type'),
            'adjust_recovery' => Yii::t('app', 'Adjusted Recovery(+)'),
            'recovery' => Yii::t('app', 'Recovery(-)'),
            'old_recovery' => Yii::t('app', 'Old Recovery(-)'),
            'new_recovery' => Yii::t('app', 'New Recovery(+)'),
            'total_recovery' => Yii::t('app', 'Net Recovery'),
            'p_bmc_code' => Yii::t('app', 'BMC'),
            'p_customer_type' => Yii::t('app', 'Type'),
            'p_payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
            'payment_release_type' => Yii::t('app', 'Disburse Type'),
            'billing_type' => Yii::t('app', 'Payment Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVspPaymentQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVspPaymentQuery(get_called_class());
    }

    //relationship with dcs
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    //relationship with payment cycle
    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
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

    public function getRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'payment_cycle_code' => $this->payment_cycle_code,
                    'bmc_code' => $this->bmc_code,
                    'customer_type' => $this->customer_type, 'status' => ['generated', 'processed']])->orderBy('net_payable');
    }

    public function getRemunerationRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'from_datetime' => $this->from_datetime,
                    'to_datetime' => $this->to_datetime,
                    'bmc_code' => $this->bmc_code,
                    'billing_type' => 'remuneration', 'status' => ['generated', 'processed']]);
    }

    public function CheckPendingDisburse($attribute, $params) {
        $data = $this->find()
                ->select(['from_datetime', 'to_datetime'])
                ->where(['status' => ['generated', 'processed', 'locked'], 'billing_type' => 'regular', 'bmc_code' => $this->bmc_code,
                    'customer_type' => $this->customer_type,
                ])
                ->andWhere(['NOT IN', 'payment_cycle_code', $this->payment_cycle_code])
                ->one();
        if (!empty($data)) {
            $from_date = date('d-m-Y', strtotime($data->from_datetime));
            $to_date = date('d-m-Y', strtotime($data->to_datetime));
            $this->addError($attribute, Yii::t('app', "Please first disburse payment cycle $from_date to $to_date ."));
        }
    }

    public function getRecoveryRecords() {
        return $this->find()
                        ->alias('t')->select('t.*,r.recovery_amount as old_recovery')
                        ->leftJoin('tbl_vsp_payment_recovery r', 'r.payment_cycle_code=t.payment_cycle_code and r.bmc_code=t.bmc_code and r.customer_type=t.customer_type and r.from_customer_code=t.customer_code and r.for_customer_code=' . $this->customer_code . '')
                        ->where(['t.union_code' => $this->union_code,
                            't.payment_cycle_code' => $this->payment_cycle_code,
                            't.bmc_code' => $this->bmc_code,
                            't.customer_type' => $this->customer_type, 't.status' => ['processed', 'generated']])
                        ->andWhere(['>', 't.net_payable', 0])
                        ->andWhere(['!=', 't.vsp_payment_code', $this->vsp_payment_code])->all();
    }

    public function getStatusCount($status) {
        return $this->find()->where(['union_code' => $this->union_code, 'payment_cycle_code' => $this->payment_cycle_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'customer_type' => $this->customer_type])
                        ->andWhere(['status' => $status])->count();
    }

    public function validateReleaseDate($attribute, $params) {
        $disburseDate = date('Y-m-d', strtotime($this->disburse_date));
        $currentDate = date('Y-m-d');
        $release_date = $this->release_date;
        if ($release_date < $disburseDate || $release_date > $currentDate) {
            $this->addError($release_date, 'Invalid release date.');
        }
    }

    public function getVendorPaymentRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'payment_cycle_code' => $this->payment_cycle_code,
                    'bmc_code' => $this->bmc_code,
                    'billing_type' => 'regular',
                    'status' => ['locked']])->orderBy('net_payable')->all();
    }

    public function checkBankValidate($attribute, $params){
        $isValid = true;
        $pendingBankVerifyCount = $this->find()
                ->where(['vsp_payment_code' => $this->vsp_payment_code, 'is_verified' => 0])
                ->andWhere(['and', ['is not', 'ifsc', null], ['is not', 'bank_account_no', null], ['is not', 'bank_name', null], ['is not', 'bank_code', null], ['is not', 'branch_name', null], ['is not', 'branch_code', null], ['is not', 'beneficiary_name', null], ['<>', 'ifsc', ''], ['<>', 'bank_account_no', ''], ['<>', 'bank_name', ''], ['<>', 'bank_code', ''], ['<>', 'branch_name', ''], ['<>', 'branch_code', ''], ['<>', 'beneficiary_name', '']])
                ->count();
        $rejectBankVerifyCount = $this->find()
                ->where(['vsp_payment_code' => $this->vsp_payment_code, 'is_verified' => 2])
                ->andWhere(['and', ['is not', 'ifsc', null], ['is not', 'bank_account_no', null], ['is not', 'bank_name', null], ['is not', 'bank_code', null], ['is not', 'branch_name', null], ['is not', 'branch_code', null], ['is not', 'beneficiary_name', null], ['<>', 'ifsc', ''], ['<>', 'bank_account_no', ''], ['<>', 'bank_name', ''], ['<>', 'bank_code', ''], ['<>', 'branch_name', ''], ['<>', 'branch_code', ''], ['<>', 'beneficiary_name', '']])
                ->count();
        $pendingBankCount = $this->find()
                ->where(['vsp_payment_code' => $this->vsp_payment_code])
                ->andWhere(['or', ['ifsc' => null], ['bank_account_no' => null], ['bank_name' => null], ['bank_code' => null], ['branch_name' => null], ['branch_code' => null], ['beneficiary_name' => null], ['ifsc' => ''], ['bank_account_no' => ''], ['bank_name' => ''], ['bank_code' => ''], ['branch_name' => ''], ['branch_code' => ''], ['beneficiary_name' => '']])
                ->count();

        if($pendingBankVerifyCount > 0 || $pendingBankCount > 0 || $rejectBankVerifyCount > 0){
            $isValid = false;
            $message = "";
            if ($pendingBankVerifyCount > 0) {
                $message .= Yii::t('app', "Number of records bank verification pending: $pendingBankVerifyCount. ");
            }
            if ($rejectBankVerifyCount > 0) {
                $message .= Yii::t('app', "Number of records bank verification rejected: $rejectBankVerifyCount. ");
            }
            if ($pendingBankCount > 0) {
                $message .= Yii::t('app', "Number of records bank detail not exist: $pendingBankCount. ");
            }
            $this->addError($attribute, trim($message));
        }
        return $isValid;
    }

    public function getPaymentCycleApplicabilityForLock($payment_cycle_code, $bmc_code, $customer_type) {
        if (is_array($bmc_code)) {
            $bmc_code = $bmc_code[0];
        }
        try {
            $applicability = TblPaymentCycleApplicability::find()
                ->where([
                    'payment_cycle_code' => $payment_cycle_code,
                    'applicable_code' => $bmc_code,
                    'applicable_type' => $customer_type,
                    'applicable_for' => 'BMC'
                ])
                ->one();

            if (!empty($applicability)) {
                $applicability->process_lock_bmc = 1;
                $applicability->scenario = 'processLock';
                return $applicability;
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

}
