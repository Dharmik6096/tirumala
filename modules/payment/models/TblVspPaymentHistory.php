<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_payment_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
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
class TblVspPaymentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_payment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['operation_type', 'dcs_code', 'union_code', 'adjust_remark', 'created_by', 'updated_by', 'status', 'from_datetime', 'from_shift', 'to_datetime', 'to_shift', 'billing_type'], 'safe'],
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['vsp_payment_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'customer_code', 'customer_type'], 'safe'],
            [['vsp_payment_code', 'payment_cycle_code', 'dcs_payment_cycle_applicabilty_code'], 'safe'],
            [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay', 'adjust_recovery', 'recovery'], 'safe'],
            [['route_code', 'avg_fat', 'avg_snf', 'std_qty', 'customer_name', 'beneficiary_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'vsp_payment_code' => Yii::t('app', 'Vsp Payment Code'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'dcs_payment_cycle_applicabilty_code' => Yii::t('app', 'Dcs Payment Cycle Applicabilty Code'),
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
        ];
    }

}
