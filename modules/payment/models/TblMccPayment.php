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
 * This is the model class for table "tbl_mcc_payment".
 *
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
class TblMccPayment extends \app\models\ChildModel {

    public $otp_code, $customer_ex_code, $old_recovery, $new_recovery, $total_recovery;
    public $p_bmc_code, $p_customer_type, $p_payment_cycle_code, $multiple_bmc;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'adjust_remark', 'created_by', 'updated_by', 'status', 'from_datetime', 'from_shift', 'to_datetime', 'to_shift', 'billing_type', 'p_bmc_code', 'p_customer_type', 'p_payment_cycle_code', 'multiple_bmc'], 'safe'],
            [['payment_cycle_code', 'payment_cycle_applicabilty_code', 'old_recovery', 'new_recovery', 'total_recovery'], 'safe'],
            [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay', 'previous_hold', 'previous_due', 'hold_amount', 'adjust_recovery', 'recovery'], 'safe'],
            [['created_at', 'updated_at', 'dcs_code', 'bmc_code', 'customer_code', 'customer_type', 'plant_code', 'mcc_plant_code'], 'safe'],
            [['plant_code', 'mcc_plant_code'], 'required'],
            [['bmc_code', 'customer_type'], 'required', 'on' => ['remuneration', 'processpayment']],
            [['bmc_code'], 'required', 'on' => ['mccremuneration']],
            [['payment_cycle_code'], 'required', 'on' => ['processpayment']],
            [['payment_cycle_code'], 'CheckPendingDisburse', 'skipOnError' => true, 'on' => ['processpayment']],
            [['route_code', 'avg_fat', 'avg_snf', 'std_qty', 'customer_name', 'beneficiary_name','minimum_qty','minimum_qty_amount','total_qty_amount','transfered_qty','billing_qty','received_qty','bmc_collection_qty','received_amount','transfered_amount','billing_qty_amount'], 'safe'],
            [['pin_code', 'aadhaar_no', 'contact_person_name', 'mobile_no', 'beneficiary_name', ' pan_no', 'gst_no', 'bmc_collection_amount', 'state_code', 'sub_district_code', 'village_code', 'hamlet_code', 'district_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_code' => Yii::t('app', 'Society Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'kg_fat' => Yii::t('app', 'KgFAT'),
            'kg_snf' => Yii::t('app', 'KgSNF'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'total_loss' => Yii::t('app', 'Total Loss'),
            'amount' => Yii::t('app', 'chilling cost(+)'),
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
            'total_qty_amount' => Yii::t('app', 'Total Qty Amount'),
            'minimum_qty' => Yii::t('app', 'Min Qty'),
            'minimum_qty_amount' => Yii::t('app', 'Min Qty Amount'),

        ];
    }

    /**
     * @inheritdoc
     * @return TblMccPaymentQuery the active query used by this AR class.
     */
//    public static function find() {
//        return new TblMccPaymentQuery(get_called_class());
//    }
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
                    'customer_type' => $this->customer_type, 'status' => 'processed'])->orderBy('net_payable');
    }

    public function getRemunerationRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'cast(from_datetime as date)' => date('Y-m-d', strtotime($this->from_datetime)),
                    'cast(to_datetime as date)' => date('Y-m-d', strtotime($this->to_datetime)),
                    'bmc_code' => $this->bmc_code,
                    'billing_type' => 'mcc_remuneration', 'status' => 'processed']);
    }

    public function CheckPendingDisburse($attribute, $params) {
        $data = $this->find()
                ->select(['from_datetime', 'to_datetime'])
                ->where(['status' => 'processed', 'billing_type' => 'regular', 'bmc_code' => $this->bmc_code,
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
                        ->leftJoin('tbl_mcc_payment_recovery r', 'r.payment_cycle_code=t.payment_cycle_code and r.bmc_code=t.bmc_code and r.customer_type=t.customer_type and r.from_customer_code=t.customer_code and r.for_customer_code=' . $this->customer_code . '')
                        ->where(['t.union_code' => $this->union_code,
                            't.payment_cycle_code' => $this->payment_cycle_code,
                            't.bmc_code' => $this->bmc_code,
                            't.customer_type' => $this->customer_type, 't.status' => 'processed'])
                        ->andWhere(['>', 't.net_payable', 0])
                        ->andWhere(['!=', 't.mcc_payment_code', $this->mcc_payment_code])->all();
    }

}
