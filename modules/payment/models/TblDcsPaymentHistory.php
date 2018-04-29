<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_payment_history".
 *
 * @property integer $id
 * @property integer $dcs_payment_code
 * @property string $union_code
 * @property string $dcs_code
 * @property integer $dcs_payment_cycle_applicabilty_code
 * @property integer $dcs_payment_cycle_code
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
 * @property string $operation_type
 * @property string $history_created_at
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
class TblDcsPaymentHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_payment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['dcs_payment_code'], 'required'],
//            [['dcs_payment_code', 'dcs_payment_cycle_applicabilty_code', 'ack'], 'integer'],
//            [['union_code', 'dcs_code', 'approved_by', 'status', 'transfer_mode', 'error_code', 'error_log', 'operation_type'], 'string'],
//            [['total_amount', 'total_deduction', 'final_amount', 'disburse_amount'], 'number'],
            [['member_count','total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'union_code', 'dcs_code', 'approved_by', 'status', 'transfer_mode', 'error_code', 'error_log', 'operation_type', 'dcs_payment_code', 'dcs_payment_cycle_applicabilty_code', 'ack', 'disburse_date', 'payment_date', 'history_created_at','created_at','created_by','updated_at','updated_by','qty','avg_fat','avg_snf','kg_fat','kg_snf','avg_rate','dcs_payment_cycle_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dcs_payment_code' => 'Society Payment Code',
            'union_code' => 'Union Code',
            'dcs_code' => 'Dcs Code',
            'dcs_payment_cycle_applicabilty_code' => 'Dcs Payment Cycle Applicabilty Code',
            'total_amount' => 'Total Amount',
            'total_deduction' => 'Total Deduction',
            'final_amount' => 'Final Amount',
            'disburse_amount' => 'Disburse Amount',
            'disburse_date' => 'Disburse Date',
            'payment_date' => 'Payment Date',
            'approved_by' => 'Approved By',
            'status' => 'Status',
            'transfer_mode' => 'Transfer Mode',
            'error_code' => 'Error Code',
            'error_log' => 'Error Log',
            'ack' => 'Ack',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg FAT'),
            'avg_snf' => Yii::t('app', 'Avg SNF'),
            'kg_fat' => Yii::t('app', 'Kg FAT'),
            'kg_snf' => Yii::t('app', 'Kg SNF'),
            'avg_rate' => Yii::t('app', 'Avg Rate')
        ];
    }
}
