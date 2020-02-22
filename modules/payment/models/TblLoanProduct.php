<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_loan_product".
 *
 * @property integer $product_code
 * @property integer $product_group_code
 * @property string $product_name
 * @property string $description
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 * @property string $union_code
 * @property double $rate
 * @property integer $sap_product_code
 * @property string $dpu_product_code
 * @property integer $is_dpu_product
 */
class TblLoanProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_loan_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_code'], 'required'],
            [['product_code', 'product_group_code', 'is_active', 'sap_product_code', 'is_dpu_product'], 'integer'],
            [['product_name', 'description', 'created_by', 'updated_by', 'local_name', 'union_code', 'dpu_product_code'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['rate'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_code' => Yii::t('app', 'Product Code'),
            'product_group_code' => Yii::t('app', 'Product Group Code'),
            'product_name' => Yii::t('app', 'Product Name'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'rate' => Yii::t('app', 'Rate'),
            'sap_product_code' => Yii::t('app', 'Sap Product Code'),
            'dpu_product_code' => Yii::t('app', 'Dpu Product Code'),
            'is_dpu_product' => Yii::t('app', 'Is Dpu Product'),
        ];
    }
}
