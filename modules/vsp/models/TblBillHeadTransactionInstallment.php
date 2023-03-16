<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\vsp\models\TblBillHead;

/**
 * This is the model class for table "tbl_bill_head_transaction_installment".
 *
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
class TblBillHeadTransactionInstallment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_transaction_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_txn_code', 'payment_cycle_code', 'installment_status'], 'integer'],
                [['installment_amount'], 'number'],
                [['installment_date'], 'safe'],
                [['bill_head_code'], 'string', 'max' => 10],
                [['union_code'], 'string', 'max' => 3],
                [['customer_type', 'customer_code', 'bill_head_for'], 'string', 'max' => 20],
                [['dcs_code'], 'string', 'max' => 12],
                [['payment_cycle_type'], 'string', 'max' => 25],
                [['installment_status'], 'default', 'value' => 0],
                [['bill_head_code'], 'setHeadDetail']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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

    public function getData($detail_id) {
        return $this->find()->select(['bill_head_txn_inst_code'])->where(['bill_head_txn_code' => $detail_id])->all();
    }

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function setHeadDetail() {
        $bill_head = $this->billHeadCode;
        if (empty($this->payment_cycle_type) && !empty($bill_head)) {
            $this->payment_cycle_type = $bill_head->payment_cycle_type;
        }
    }

}
