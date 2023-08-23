<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\payment\models\TblMccPayment;
use app\modules\vsp\models\TblMccBillHead;

/**
 * This is the model class for table "tbl_mcc_payment_transaction".
 *
 * @property integer $mcc_payment_transaction_code
 * @property integer $mcc_payment_code
 * @property string $mcc_bill_head_code
 * @property string $amount
 * @property integer $bill_head_type
 * @property string $general_formula
 */
class TblMccPaymentTransaction extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_payment_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_payment_code', 'mcc_bill_head_code'], 'required'],
            [['mcc_payment_code', 'bill_head_type'], 'integer'],
            [['amount'], 'number'],
            [['mcc_bill_head_code'], 'string', 'max' => 10],
            [['general_formula'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_payment_transaction_code' => 'Mcc Payment Transaction Code',
            'mcc_payment_code' => 'Mcc Payment Code',
            'mcc_bill_head_code' => 'Bill Head',
            'amount' => 'Amount',
            'bill_head_type' => 'Bill Head Type',
            'general_formula' => 'General Formula',
        ];
    }

    public function getMccPaymentCode() {
        return $this->hasOne(TblMccPayment::className(), ['mcc_payment_code' => 'mcc_payment_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblMccBillHead::className(), ['mcc_bill_head_code' => 'mcc_bill_head_code']);
    }

}
