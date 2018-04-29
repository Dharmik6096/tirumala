<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_generate_method".
 *
 * @property integer $code
 * @property string $method
 * @property integer $is_active
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 */
class TblRateGenerateMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rate_generate_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['method', 'is_active'], 'required'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at', 'updated_by'], 'safe'],
            [['method'], 'string', 'max' => 30],
            [['created_by',], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'ID'),
            'method' => Yii::t('app', 'Method'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblRateGenerateMethodQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblRateGenerateMethodQuery(get_called_class());
    }
}
