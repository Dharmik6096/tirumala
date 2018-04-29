<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_group".
 *
 * @property integer $product_group_code
 * @property string $product_group_name
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 *
 * @property TblProduct[] $tblProducts
 */
class TblProductGroup extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_group_name'], 'required'],
            [['product_group_name', 'created_by', 'updated_by', 'local_name'], 'string'],
            ['product_group_name', 'unique'],
            [['product_group_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['created_at', 'updated_at','product_group_code'], 'safe'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_group_code' => Yii::t('app', 'Product Group Code'),
            'product_group_name' => Yii::t('app', 'Product Group Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProducts()
    {
        return $this->hasMany(TblProduct::className(), ['product_group_code' => 'product_group_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblProductGroupQuery(get_called_class());
    }
}
