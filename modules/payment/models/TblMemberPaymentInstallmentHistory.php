<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_member_payment_installment_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMemberPaymentInstallmentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_installment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_payment_installment_code', 'payment_cycle_code', 'originating_type'], 'safe'],
            [['main_amount', 'installment_amount'], 'safe'],
            [['installment_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['product_sale_installment_code'], 'safe'],
            [['product_sale_code'], 'safe'],
            [['customer_type', 'customer_code'], 'safe'],
            [['dcs_code', 'bmc_code'], 'safe'],
            [['mcc_plant_code', 'plant_code'], 'safe'],
            [['union_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
