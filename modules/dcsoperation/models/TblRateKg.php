<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_kg".
 *
 * @property integer $id
 * @property string $kg
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_by
 * @property string $deleted_at
 */
class TblRateKg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rate_kg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kg'], 'required'],
            [['is_active', 'is_delete'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', ], 'safe'],
            [['kg'], 'string', 'max' => 30],
            [['created_by', 'deleted_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kg' => Yii::t('app', 'Kg'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblRateKgQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblRateKgQuery(get_called_class());
    }
}
