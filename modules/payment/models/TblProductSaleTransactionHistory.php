<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_product_sale_details_history".
 *
 * @property integer $id
 * @property string $product_sale_code
 * @property integer $product_code
 * @property string $product_sale_rate_applicability_code
 * @property string $rate
 * @property string $quantity
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblProductSaleTransactionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code', 'product_sale_transaction_code', 'send_status', 'picked_datetime', 'response_datetime', 'resp_desc'], 'safe'],
            [['product_sale_code', 'product_sale_rate_applicability_code', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['rate', 'quantity', 'amount', 'remarks', 'sap_batch_no'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'originating_org_code', 'originating_org_type', 'originating_type', 'reference_code'], 'safe'],
            [['send_status'], 'default', 'value' => 0],
            [['transaction_no', 'sales_order_no', 'delivery_no', 'billing_no'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'product_sale_rate_applicability_code' => Yii::t('app', 'Rate App Code'),
            'rate' => Yii::t('app', 'Rate'),
            'quantity' => Yii::t('app', 'Quantity'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
