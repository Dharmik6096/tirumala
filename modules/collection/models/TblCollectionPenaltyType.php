<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_collection_penalty_type".
 *
 * @property string $penalty_type_code
 * @property string $union_code
 * @property string $penalty_type
 * @property integer $is_default
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblCollectionPenaltyType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_collection_penalty_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['penalty_type_code'], 'required'],
            [['is_default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['penalty_type_code', 'union_code'], 'string', 'max' => 3],
            [['penalty_type'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penalty_type_code' => Yii::t('app', 'Penalty Type Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'penalty_type' => Yii::t('app', 'Penalty Type'),
            'is_default' => Yii::t('app', 'Is Default'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
