<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_vsp_payment".
 *
 * @property integer $vsp_payment_code
 * @property string $dcs_code
 * @property string $union_code
 * @property integer $payment_cycle_code
 * @property integer $dcs_payment_cycle_applicabilty_code
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

    public $otp_code, $plant_code, $mcc_plant_code;

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
        return [
            [['union_code', 'adjust_remark', 'created_by', 'updated_by', 'status'], 'safe'],
            [['payment_cycle_code', 'dcs_payment_cycle_applicabilty_code'], 'safe'],
            [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay', 'previous_hold', 'previous_due', 'hold_amount'], 'safe'],
            [['created_at', 'updated_at', 'dcs_code', 'bmc_code', 'customer_code', 'customer_type', 'plant_code', 'mcc_plant_code'], 'safe'],
            [['payment_cycle_code', 'customer_type', 'bmc_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vsp_payment_code' => Yii::t('app', 'Vsp Payment Code'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'dcs_payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
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
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    //relationship with dcs
    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'payment_cycle_code' => $this->payment_cycle_code,
                    'bmc_code' => $this->bmc_code,
                    'customer_type' => $this->customer_type, 'status' => 'processed']);
    }

}
