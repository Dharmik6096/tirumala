<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\payment\models\TblSaleInstallments;
use app\modules\payment\models\TblPaymentCycle;

/**
 * This is the model class for table "tbl_member_payment_installment".
 *
 * @property integer $member_payment_installment_code
 * @property string $product_sale_installment_code
 * @property string $product_sale_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $main_amount
 * @property string $installment_amount
 * @property string $installment_date
 * @property integer $payment_cycle_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMemberPaymentInstallment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['main_amount', 'installment_amount'], 'number'],
                [['installment_date', 'created_at', 'updated_at'], 'safe'],
                [['payment_cycle_code', 'originating_type'], 'integer'],
                [['product_sale_installment_code'], 'safe'],
                [['product_sale_code'], 'safe'],
                [['customer_type', 'customer_code'], 'string', 'max' => 20],
                [['dcs_code', 'bmc_code'], 'string', 'max' => 12],
                [['mcc_plant_code', 'plant_code'], 'string', 'max' => 6],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_installment_code' => Yii::t('app', 'Member Payment Installment Code'),
            'product_sale_installment_code' => Yii::t('app', 'Product Sale Installment Code'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'main_amount' => Yii::t('app', 'Main Amount'),
            'installment_amount' => Yii::t('app', 'Installment Amount'),
            'installment_date' => Yii::t('app', 'Installment Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getSaleInstallment() {
        return $this->hasOne(TblSaleInstallments::className(), ['product_sale_installment_code' => 'product_sale_installment_code']);
    }

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getExistingData() {
        return $this->find()
                        ->where(['bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code, 'customer_code' => $this->customer_code, 'customer_type' => 'Member', 'payment_cycle_code' => $this->payment_cycle_code])
                        ->all();
    }

}
