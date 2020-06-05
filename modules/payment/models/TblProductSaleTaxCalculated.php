<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_product_sale_tax_calculated".
 *
 * @property string $product_sale_tax_calculated_code
 * @property string $product_sale_code
 * @property string $product_sale_transaction_code
 * @property integer $tax_detail_code
 * @property string $value
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
class TblProductSaleTaxCalculated extends \app\models\ChildModel {

    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_tax_calculated';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_tax_calculated_code'], 'required', 'except' => ['androidsync']],
                [['product_sale_tax_calculated_code'], 'safe'],
                [['product_sale_tax_calculated_code', 'product_sale_code', 'product_sale_transaction_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['tax_detail_code', 'originating_type'], 'safe'],
                [['value'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_tax_calculated_code' => Yii::t('app', 'Product Sale Tax Calculated Code'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'product_sale_transaction_code' => Yii::t('app', 'Product Sale Transaction Code'),
            'tax_detail_code' => Yii::t('app', 'Tax Detail Code'),
            'value' => Yii::t('app', 'Value'),
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

    public function getRecords() {
        return $this->find()
                        ->where(['product_sale_code' => $this->product_sale_code, 'product_sale_transaction_code' => $this->product_sale_transaction_code, 'tax_detail_code' => $this->tax_detail_code])
                        ->one();
    }

}
