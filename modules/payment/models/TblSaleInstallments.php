<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_sale_installments".
 *
 * @property integer $installment_code
 * @property string $member_code
 * @property string $sale_type
 * @property string $sale_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $main_amount
 * @property string $installment_amount
 * @property integer $payment_cycle_applicabilty_code
 * @property integer $dcs_payment_cycle_code
 * @property integer $installment_status
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblDcs $dcsCode
 * @property TblUnions $unionCode
 * @property TblDcsPaymentCycleApplicability $paymentCycleApplicabiltyCode
 * @property TblProductSale $saleCode
 */
class TblSaleInstallments extends \app\models\ChildModel {

    public $max;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sale_installments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_cycle_applicabilty_code', 'installment_status', 'is_active'], 'integer'],
                [['member_code', 'sale_type', 'sale_code', 'dcs_code', 'union_code', 'created_by', 'updated_by'], 'string'],
                [['main_amount', 'installment_amount'], 'number'],
                [['created_at', 'updated_at', 'dcs_payment_cycle_code', 'payment_cycle_code'], 'safe'],
                //[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
                //[['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
                //[['payment_cycle_applicabilty_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsPaymentCycleApplicability::className(), 'targetAttribute' => ['payment_cycle_applicabilty_code' => 'payment_cycle_applicabilty_code']],
                //[['sale_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProductSale::className(), 'targetAttribute' => ['sale_code' => 'product_sale_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'installment_code' => Yii::t('app', 'Installment Code'),
            'member_code' => Yii::t('app', 'Member'),
            'sale_type' => Yii::t('app', 'Sale Type'),
            'sale_code' => Yii::t('app', 'Sale Code'),
            'dcs_code' => Yii::t('app', 'Society'),
            'union_code' => Yii::t('app', 'Union'),
            'main_amount' => Yii::t('app', 'Main Amount'),
            'installment_amount' => Yii::t('app', 'Installment Amount'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
            'installment_status' => Yii::t('app', 'Installment Paid?'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentCycleApplicabiltyCode() {
        return $this->hasOne(TblDcsPaymentCycleApplicability::className(), ['payment_cycle_applicabilty_code' => 'payment_cycle_applicabilty_code']);
    }

    //relationship with dcs
    public function getPaymentCycleCode() {
        return $this->hasOne(TblDcsPaymentCycle::className(), ['dcs_payment_cycle_code' => 'dcs_payment_cycle_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleCode() {
        return $this->hasOne(TblProductSale::className(), ['product_sale_code' => 'sale_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @inheritdoc
     * @return TblSaleInstallmentsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblSaleInstallmentsQuery(get_called_class());
    }

    public function getInstData($invoice_no) {
        return $this->find()->select(['installment_code',
                            'installment_amount', 'dcs_payment_cycle_code', 'sale_code', 'sale_type'])
                        ->where(['sale_code' => $invoice_no])->all();
    }

}
