<?php

namespace app\modules\complaint\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_complain_product".
 *
 * @property integer $cmpl_product_code
 * @property string $cmpl_product_name
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblComplainProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_complain_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cmpl_product_name'], 'required'],
            [['cmpl_product_name', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cmpl_product_code' => Yii::t('app', 'Cmpl Product Code'),
            'cmpl_product_name' => Yii::t('app', 'Cmpl Product Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblComplainProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblComplainProductQuery(get_called_class());
    }
    
    public function geComplainProductList() {
        $value = $this->getComplainProduct();
        $value = ArrayHelper::map($value, 'cmpl_product_code', 'cmpl_product_name');
        return $value;
    }

    public function getComplainProduct() {
        $query = $this->find()->select(['cmpl_product_code', 'cmpl_product_name'])->where(['is_active' => 1]);
        return $query->all();
    }
}
