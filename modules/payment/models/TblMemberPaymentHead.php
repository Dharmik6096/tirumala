<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\vsp\models\TblBillHead;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblMember;
use app\modules\payment\models\TblPaymentCycle;

/**
 * This is the model class for table "tbl_member_payment_head".
 *
 * @property integer $member_payment_head_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property integer $payment_cycle_code
 * @property string $bill_head_code
 * @property string $amount
 * @property integer $bill_head_type
 */
class TblMemberPaymentHead extends \app\models\ChildModel {

    public $current_cycle;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_head';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bmc_code', 'dcs_code', 'member_code', 'bill_head_code', 'is_hold', 'is_skippable', 'general_formula','payment_cycle_type'], 'safe'],
                [['payment_cycle_code', 'bill_head_type'], 'safe'],
                [['amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_head_code' => Yii::t('app', 'Member Payment Head Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head'),
            'amount' => Yii::t('app', 'Amount'),
            'bill_head_type' => Yii::t('app', 'Type'),
            'is_hold' => Yii::t('app', 'Is Hold ?'),
            'payment_cycle_type' => Yii::t('app', 'New Cycle'),
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

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

}
