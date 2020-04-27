<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\vsp\models\TblBillHead;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_member_payment_head_summary".
 *
 * @property integer $member_payment_head_summary_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property integer $payment_cycle_code
 * @property string $bill_head_code
 * @property string $amount
 * @property integer $bill_head_type
 */
class TblMemberPaymentHeadSummary extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_head_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bmc_code', 'dcs_code', 'bill_head_code'], 'string'],
                [['payment_cycle_code', 'bill_head_type'], 'integer'],
                [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_head_summary_code' => Yii::t('app', 'Member Payment Head Summary Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head'),
            'amount' => Yii::t('app', 'Amount'),
            'bill_head_type' => Yii::t('app', 'Type'),
        ];
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

}
