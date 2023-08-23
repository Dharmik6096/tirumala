<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_transaction_installment_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $bill_head_txn_inst_code
 * @property integer $bill_head_txn_code
 * @property string $bill_head_code
 * @property string $union_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $dcs_code
 * @property string $installment_amount
 * @property string $installment_date
 * @property integer $payment_cycle_code
 * @property string $bill_head_for
 * @property integer $installment_status
 * @property string $payment_cycle_type
 */
class TblBillHeadTransactionInstallmentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_transaction_installment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'installment_date'], 'safe'],
                [['bill_head_txn_inst_code', 'bill_head_txn_code', 'payment_cycle_code', 'installment_status'], 'safe'],
                [['installment_amount'], 'safe'],
                [['operation_type', 'bill_head_code'], 'safe'],
                [['history_created_by'], 'safe'],
                [['union_code'], 'safe'],
                [['customer_type', 'customer_code', 'bill_head_for'], 'safe'],
                [['dcs_code'], 'safe'],
                [['payment_cycle_type'], 'safe'],
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
            'history_created_by' => Yii::t('app', 'History Created By'),
            'bill_head_txn_inst_code' => Yii::t('app', 'Bill Head Txn Inst Code'),
            'bill_head_txn_code' => Yii::t('app', 'Bill Head Txn Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'installment_amount' => Yii::t('app', 'Installment Amount'),
            'installment_date' => Yii::t('app', 'Installment Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'installment_status' => Yii::t('app', 'Installment Status'),
            'payment_cycle_type' => Yii::t('app', 'Payment Cycle Type'),
        ];
    }

}
