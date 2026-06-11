<?php


namespace app\modules\product\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\models\User;
use Yii;

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
class TblProductStockValuation extends ChildModel
{
    public static function tableName()
    {
        return 'tbl_product_stock_valuation';
    }

    public function rules()
    {
        return [
            [['product_stock_valuation_code'], 'required', 'on' => ['androidsync']],
            [['product_stock_valuation_code', 'union_code', 'product_code', 'product_name', 'stock', 'valuation', 'unit', 'generated_at', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_active', 'x_col1', 'x_col2', 'x_col3'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
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
        ];
    }

    public function getProductCode()
    {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getUserCode()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
}
