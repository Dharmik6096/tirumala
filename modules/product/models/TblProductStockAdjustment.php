<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_stock_adjustment".
 *
 * @property integer $product_stock_adjustment_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $product_code
 * @property string $sap_batch_no
 * @property string $adjustment_type
 * @property string $invoice_no
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
class TblProductStockAdjustment extends \app\models\ChildModel
{
    public $code, $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_stock_adjustment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_date', 'created_at', 'updated_at','code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['reason', 'remarks', 'bmc_code', 'dcs_code', 'product_code', 'sap_batch_no'], 'safe'],
            [['originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['type', 'stock', 'qty', 'unit', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'product_code', 'sap_batch_no', 'adjustment_type', 'invoice_no', 'reason'], 'safe'],
            [['stock', 'qty'], 'number'],          
            [['remarks'], 'string'],            
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'required'],
//            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'product_code', 'sap_batch_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_stock_adjustment_code' => Yii::t('app', 'Product Stock Adjustment Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'sap_batch_no' => Yii::t('app', 'Sap Batch No'),
            'adjustment_type' => Yii::t('app', 'Adjustment Type'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
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
}
