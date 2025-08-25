<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_animal_type_additional".
 *
 * @property integer $animal_type_additional_code
 * @property string $additional_animal_type_name
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 */
class TblAnimalTypeAdditional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_animal_type_additional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_type_additional_code', 'additional_animal_type_name', 'union_code'], 'required'],
            [['animal_type_additional_code', 'is_active'], 'integer'],
            [['created_at'], 'safe'],
            [['additional_animal_type_name'], 'string', 'max' => 50],
            [['union_code'], 'string', 'max' => 3],
            [['created_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'animal_type_additional_code' => Yii::t('app', 'Animal Type Additional Code'),
            'additional_animal_type_name' => Yii::t('app', 'Additional Animal Type Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
