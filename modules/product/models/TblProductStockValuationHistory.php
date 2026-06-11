<?php

namespace app\modules\product\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_product_stock_valuation".
 *
 * @property string $product_stock_valuation_code
 * @property string|null $product_code
 * @property string|null $product_name
 * @property float|null $stock
 * @property float|null $valuation
 * @property string|null $unit
 * @property string|null $generated_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int|null $is_active
 * @property string|null $x_col1
 * @property string|null $x_col2
 * @property string|null $x_col3
 */
class TblProductStockValuationHistory extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_product_stock_valuation_history';
    }

    public function rules()
    {
        return [
            [['product_stock_valuation_code'], 'required', 'on' => ['androidsync']],
            [['id', 'product_stock_valuation_code', 'union_code', 'product_code', 'product_name', 'stock', 'valuation', 'unit', 'generated_at', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_active', 'x_col1', 'x_col2', 'x_col3', 'history_created_at', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_stock_valuation_code' => Yii::t('app', 'Valuation Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'product_name' => Yii::t('app', 'Product Name'),
            'stock' => Yii::t('app', 'Stock'),
            'valuation' => Yii::t('app', 'Valuation'),
            'unit' => Yii::t('app', 'Unit'),
            'generated_at' => Yii::t('app', 'Generated At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Status'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
