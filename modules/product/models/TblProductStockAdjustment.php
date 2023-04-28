<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\product\models\TblProductStockAdjustmentTransaction;

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
            [['remarks','originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['type', 'code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'adjustment_type', 'invoice_no'], 'safe'],
            [['remarks'], 'string'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'type'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_stock_adjustment_code' => Yii::t('app', 'Product Stock Adjustment Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC Plant'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'adjustment_type' => Yii::t('app', 'Adjustment Type'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
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
    
    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }
    
    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }
    
    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }
    
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
    
    public function getProductStockAsjustmentTransactionCode() {
        return $this->hasMany(TblProductStockAdjustmentTransaction::className(), ['product_stock_adjustment_code' => 'product_stock_adjustment_code']);
    }
}
