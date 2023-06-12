<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_product_stock_adjustment_transaction".
 *
 * @property integer $product_stock_adjustment_transaction_code
 * @property string $product_stock_adjustment_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $product_code
 * @property string $sap_batch_no
 * @property string $old_value
 * @property string $new_value
 * @property string $final_value
 * @property string $adjustment_type
 * @property string $transaction_date
 * @property string $unit
 * @property string $stock
 * @property string $qty
 * @property string $reason
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblProductStockAdjustmentTransaction extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_stock_adjustment_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_stock_adjustment_code', 'adjustment_type', 'reason', 'remarks','originating_type','transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['union_code'], 'safe'],
            [['unit', 'created_by', 'updated_by', 'stock', 'qty'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['union_code', 'product_code', 'sap_batch_no', 'unit', 'stock', 'qty'], 'required'],
            [['stock', 'qty'], 'number'],
            [['remarks'], 'string'],
            [['originating_type'], 'integer'],          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_stock_adjustment_transaction_code' => Yii::t('app', 'Product Stock Adjustment Transaction Code'),
            'product_stock_adjustment_code' => Yii::t('app', 'Product Stock Adjustment'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'product_code' => Yii::t('app', 'Product'),
            'sap_batch_no' => Yii::t('app', 'Sap Batch No'),
            'adjustment_type' => Yii::t('app', 'Adjustment Type'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'unit' => Yii::t('app', 'Unit'),
            'stock' => Yii::t('app', 'Stock'),
            'qty' => Yii::t('app', 'Qty'),
            'reason' => Yii::t('app', 'Reason'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
    
    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    public function getProductStockAsjustmentCode() {
        return $this->hasOne(TblProductStockAdjustment::className(), ['product_stock_adjustment_code' => 'product_stock_adjustment_code']);
    }
}
