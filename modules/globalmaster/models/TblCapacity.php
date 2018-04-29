<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_capacity".
 *
 * @property integer $capacity_code
 * @property integer $is_active
 * @property integer $value
 * @property string $unit
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblCapacity extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_capacity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'value'], 'integer'],
            [['value'], 'required'],
            [['value'], 'unique'],
            [['value'], 'number', 'min'=>0],
            [['unit', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at','capacity_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capacity_code' => Yii::t('app', 'Capacity Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'value' => Yii::t('app', 'Capacity'),
            'unit' => Yii::t('app', 'Unit'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblCapacityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblCapacityQuery(get_called_class());
    }
}
