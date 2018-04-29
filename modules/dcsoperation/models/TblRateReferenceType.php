<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_reference_type".
 *
 * @property integer $id
 * @property string $reference_type
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_by
 * @property string $deleted_at
 */
class TblRateReferenceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rate_reference_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference_type'], 'required'],
            [['is_active', 'is_delete'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', ], 'safe'],
            [['reference_type'], 'string', 'max' => 50],
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
            'reference_type' => Yii::t('app', 'Reference Type'),
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
     * @return TblRateReferenceTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblRateReferenceTypeQuery(get_called_class());
    }
    
    /**
     * USED IN TABULAR FORM DD (DON'T REMOVE IT)
     * @return type
     */
    public function getActiveRefType(){
        
        $data = $this->find()->select('reference_type,reference_type')->where(['is_active'=>1,'is_delete'=>0])->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'reference_type', 'reference_type');
        return $array;
    }
}
