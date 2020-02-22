<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_product_sale_details_history".
 *
 * @property integer $id
 * @property integer $sale_detail_code
 * @property string $product_sale_code
 * @property integer $product_code
 * @property string $rate_app_code
 * @property string $rate
 * @property string $qty
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblProductSaleDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['sale_detail_code', 'product_code'], 'integer'],
                [['product_sale_code', 'rate_app_code', 'created_by', 'updated_by', 'operation_type'], 'string'],
                [['rate', 'qty', 'amount'], 'number'],
                [['created_at', 'updated_at', 'history_created_at', 'discount', 'amount_due', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'sale_detail_code' => Yii::t('app', 'Sale Detail Code'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'rate_app_code' => Yii::t('app', 'Rate App Code'),
            'rate' => Yii::t('app', 'Rate'),
            'qty' => Yii::t('app', 'Qty'),
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
