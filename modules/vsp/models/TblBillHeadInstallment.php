<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\vsp\models\TblBillHead;

/**
 * This is the model class for table "tbl_bill_head_installment".
 *
 * @property integer $bill_head_installment_code
 * @property integer $bill_head_detail_code
 * @property string $bill_head_code
 * @property string $dcs_code
 * @property string $installement_cycle
 * @property string $installment_amount
 * @property string $installment_date
 * @property string payment_cycle_code
 */
class TblBillHeadInstallment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_detail_code'], 'integer'],
                [['bill_head_code', 'dcs_code', 'installement_cycle', 'installment_amount'], 'safe'],
                [['installment_date', 'payment_cycle_code', 'payment_cycle_type'], 'safe'],
                [['customer_type', 'customer_code', 'union_code', 'bill_head_for', 'installment_status'], 'safe'],
                [['installment_status'], 'default', 'value' => 0],
                [['bill_head_code'], 'setHeadDetail']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_installment_code' => 'Bill Head Installment Code',
            'bill_head_detail_code' => 'Bill Head Detail Code',
            'bill_head_code' => 'Bill Head Code',
            'dcs_code' => 'Dcs Code',
            'installement_cycle' => 'Installement Cycle',
            'installment_amount' => 'Installment Amount',
            'installment_date' => 'Installment Date',
            'payment_cycle_type' => 'Payment Cycle Type',
        ];
    }

    public function getData($detail_id) {
        return $this->find()->select(['bill_head_installment_code'])->where(['bill_head_detail_code' => $detail_id])->all();
    }

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function setHeadDetail() {
        $bill_head = $this->billHeadCode;
        if (!empty($bill_head)) {
            $this->payment_cycle_type = $bill_head->payment_cycle_type;
        }
    }

}
