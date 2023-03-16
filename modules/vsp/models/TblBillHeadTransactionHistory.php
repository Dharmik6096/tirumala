<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_transaction_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $bill_head_txn_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $bill_head_code
 * @property string $payment_cycle_type
 * @property string $bill_head_for
 * @property string $transaction_date
 * @property string $amount
 * @property integer $no_installment
 * @property string $installment_amount
 * @property string $paid_amount
 * @property string $unpaid_amount
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBillHeadTransactionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bill_head_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['bill_head_txn_code', 'no_installment', 'originating_type'], 'integer'],
            [['amount', 'installment_amount', 'paid_amount', 'unpaid_amount'], 'number'],
            [['operation_type', 'plant_code', 'mcc_plant_code', 'bmc_code', 'bill_head_code'], 'string', 'max' => 10],
            [['history_created_by', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['union_code'], 'string', 'max' => 3],
            [['dcs_code'], 'string', 'max' => 12],
            [['customer_type', 'customer_code', 'bill_head_for'], 'string', 'max' => 20],
            [['payment_cycle_type', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'bill_head_txn_code' => Yii::t('app', 'Bill Head Txn Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'payment_cycle_type' => Yii::t('app', 'Payment Cycle Type'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'amount' => Yii::t('app', 'Amount'),
            'no_installment' => Yii::t('app', 'No Installment'),
            'installment_amount' => Yii::t('app', 'Installment Amount'),
            'paid_amount' => Yii::t('app', 'Paid Amount'),
            'unpaid_amount' => Yii::t('app', 'Unpaid Amount'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
