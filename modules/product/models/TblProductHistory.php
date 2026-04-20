<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_history".
 *
 * @property integer $id
 * @property integer $product_code
 * @property integer $product_group_code
 * @property string $product_name
 * @property string $description
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblProductHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_code', 'product_group_code', 'is_active', 'product_name', 'product_desc', 'created_by', 'operation_type', 'updated_by', 'local_name', 'created_at', 'history_created_at', 'updated_at', 'union_code', 'unit_code'], 'safe'],
                [['is_inhouse', 'is_inclusive_tax', 'is_saleable', 'is_indent', 'ref_code', 'tax_code', 'product_category_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'product_market_name', 'product_variant', 'product_sku', 'product_pack_type', 'brand_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_dpu_product', 'dpu_product_code', 'item_code', 'is_milk', 'milk_type', 'purchase_ledger', 'sale_ledger', 'stock_ledger', 'local_sale_ledger', 'other_state_tax_code', 'coupon_ledger'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_code' => Yii::t('app', 'Product Code'),
            'product_group_code' => Yii::t('app', 'Product Group Code'),
            'product_name' => Yii::t('app', 'Product Name'),
            'product_desc' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

}
