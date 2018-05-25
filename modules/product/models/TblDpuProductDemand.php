<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblProductGroup;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;
/**
 * This is the model class for table "tbl_DPU_ProductDemand".
 *
 * @property integer $Id
 * @property string $trDate
 * @property string $shift
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $product_code
 * @property string $PPrice
 * @property string $PQty
 * @property string $PAmount
 * @property integer $Status
 * @property string $ApprovedDate
 * @property string $CreateOnUtc
 * @property string $CreatedBy
 * @property string $UpdateOnUtc
 * @property string $UpdatedBy
 * @property string $ProductStatus
 */
class TblDpuProductDemand extends \app\models\ChildModel
{
    public $bmc_name, $dcs_name, $member_name, $product_name, $unit_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_DPU_ProductDemand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trDate', 'shift', 'bmc_code', 'dcs_code', 'member_code', 'product_code', 'PPrice', 'PQty', 'PAmount', 'CreateOnUtc', 'CreatedBy'], 'required'],
            [['trDate', 'ApprovedDate', 'CreateOnUtc', 'UpdateOnUtc'], 'safe'],
            [['shift', 'bmc_code', 'dcs_code', 'member_code', 'product_code', 'CreatedBy', 'UpdatedBy', 'ProductStatus'], 'string'],
            [['PPrice', 'PQty', 'PAmount'], 'number'],
            [['Status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'trDate' => Yii::t('app', 'Tr Date'),
            'shift' => Yii::t('app', 'Shift'),
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'PPrice' => Yii::t('app', 'Price'),
            'PQty' => Yii::t('app', 'Qty'),
            'PAmount' => Yii::t('app', 'Amount'),
            'Status' => Yii::t('app', 'Status'),
            'ApprovedDate' => Yii::t('app', 'Approved Date'),
            'CreateOnUtc' => Yii::t('app', 'Create On Utc'),
            'CreatedBy' => Yii::t('app', 'Created By'),
            'UpdateOnUtc' => Yii::t('app', 'Update On Utc'),
            'UpdatedBy' => Yii::t('app', 'Updated By'),
            'ProductStatus' => Yii::t('app', 'Product Status'),
            'bmc_name' => Yii::t('app', 'BMC Name'),
            'dcs_name' => Yii::t('app', 'DCS Name'),
            'product_name' => Yii::t('app', 'Product Name'),
            'unit_code' => Yii::t('app', 'Unit'),
        ];
    }
    
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
    
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }
    
    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }
    
    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }
    
    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }
    
}
