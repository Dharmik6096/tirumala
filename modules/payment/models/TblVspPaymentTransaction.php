<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\vsp\models\TblBillHead;

/**
 * This is the model class for table "tbl_vsp_payment_transaction".
 *
 * @property integer $tbl_vsp_payment_transaction_code
 * @property integer $vsp_payment_code
 * @property string $bill_head_code
 * @property string $amount
 */
class TblVspPaymentTransaction extends \app\models\ChildModel {

    public $current_cycle;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_payment_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vsp_payment_code', 'bill_head_code'], 'safe'],
                [['vsp_payment_code'], 'safe'],
                [['bill_head_code'], 'safe'],
                [['amount', 'bill_head_type', 'general_formula', 'is_hold', 'is_skippable', 'payment_cycle_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tbl_vsp_payment_transaction_code' => Yii::t('app', 'Tbl Vsp Payment Transaction Code'),
            'vsp_payment_code' => Yii::t('app', 'Vsp Payment Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head'),
            'amount' => Yii::t('app', 'Amount'),
            'bill_head_type' => Yii::t('app', 'Type'),
            'is_hold' => Yii::t('app', 'Is Hold ?'),
            'payment_cycle_type' => Yii::t('app', 'New Cycle'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVspPaymentTransactionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVspPaymentTransactionQuery(get_called_class());
    }

    public function getVspPaymentCode() {
        return $this->hasOne(TblVspPayment::className(), ['vsp_payment_code' => 'vsp_payment_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

}
