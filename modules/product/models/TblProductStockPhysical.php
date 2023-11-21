<?php

namespace app\modules\product\models;

use Yii;
use app\models\ChildModel;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\product\models\TblProduct;

class TblProductStockPhysical extends ChildModel
{
    public $customer_type, $customer_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_stock_physical';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_code', 'qty', 'stock_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['customer_type', 'customer_code'], 'safe'],
            [['customer_type'], 'in', 'range' => ['MCC', 'DCS']],
            [['stock_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['stock_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['stock_date'], 'convertDate', 'on' => ['importCsv']],
            [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::class, 'targetAttribute' => ['product_code' => 'product_code'], 'on' => ['importCsv']],
            [['customer_type', 'customer_code', 'product_code', 'qty', 'stock_date'], 'required', 'on' => ['importCsv']],
            [['product_code'], 'setData', 'on' => ['importCsv']],
            [['product_code', 'stock_date'], 'validateUniqueStockEntry', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_stock_physical_code' => Yii::t('app', 'Product Stock Physical Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'qty' => Yii::t('app', 'QTY'),
            'stock_date' => Yii::t('app', 'Stock Date'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC Plant'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
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

    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::class, ['dcs_code' => 'dcs_code']);
    }

    public function getTblMccPlant()
    {
        return $this->hasOne(TblMccPlant::class, ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function convertDateDot()
    {
        try {
            $this->stock_date = Yii::$app->controls->view_date($this->stock_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->stock_date = '-';
        }
    }

    public function convertDate()
    {
        if (empty($this->getErrors())) {
            $this->stock_date = !empty($this->stock_date) ? Yii::$app->controls->view_date($this->stock_date, 'php:Y-m-d') : NULL;
        }
    }

    public function setData()
    {
        if ($this->customer_type == 'MCC') {
            $mccData = TblMccPlant::find()->where(['ref_code' => $this->customer_code])->one();
            if (!empty($mccData)) {
                $this->union_code = $mccData->union_code;
                $this->plant_code = $mccData->plant_code;
                $this->mcc_plant_code = $mccData->mcc_plant_code;
            } else {
                $this->addError('customer_code', 'Customer code is invalid.');
                return false;
            }
        } else {
            $dcsData = TblDcs::find()->where(['ref_code' => $this->customer_code])->one();
            if (!empty($dcsData)) {
                $this->union_code = $dcsData->union_code;
                $this->plant_code = $dcsData->plant_code;
                $this->mcc_plant_code = $dcsData->mcc_plant_code;
                $this->bmc_code = $dcsData->bmc_code;
                $this->dcs_code = $dcsData->dcs_code;
            } else {
                $this->addError('customer_code', 'Customer code is invalid.');
                return false;
            }
        }
    }

    public function validateUniqueStockEntry($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $existingRecord = $this->find()
                ->where(['product_code' => $this->product_code, 'stock_date' => $this->stock_date]);
            if ($this->customer_type == 'MCC') {
                $existingRecord->andWhere(['mcc_plant_code' => $this->mcc_plant_code])
                                ->andWhere(['is','dcs_code', null]);
            } else {
                $existingRecord->andWhere(['dcs_code' => $this->dcs_code]);
            }
            $existingRecord =  $existingRecord->one();
            if (!empty($existingRecord)) {
                $this->addError($attribute, 'A record with the same product code and stock date already exists.');
                return false;
            }
        }
    }
}
