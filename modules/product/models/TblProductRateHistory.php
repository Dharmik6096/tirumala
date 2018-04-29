<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_rate_history".
 *
 * @property integer $id
 * @property integer $product_rate_code
 * @property integer $product_code
 * @property double $rate
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 */
class TblProductRateHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['rate_code', 'product_code', 'is_active'], 'integer'],
//            [['rate'], 'number'],
            [['product_rate_code', 'product_code', 'is_active', 'rate', 'wef_date', 'created_at', 'history_created_at', 'updated_at', 'created_by', 'operation_type', 'updated_by','union_code'], 'safe'],
//            [['created_by', 'operation_type', 'updated_by'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_rate_code' => Yii::t('app', 'Rate Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
