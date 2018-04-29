<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_deduction_type".
 *
 * @property integer $id
 * @property string $deduction_type
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_by
 * @property string $deleted_at
 */
class TblRateDeductionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rate_deduction_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'is_delete'], 'integer'],
            [['deduction_type'], 'required'],
            [['created_at', 'updated_at', 'deleted_at',], 'safe'],
            [['deduction_type'], 'string', 'max' => 50],
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
            'deduction_type' => Yii::t('app', 'Deduction Type'),
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
     * @return TblRateDeductionTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblRateDeductionTypeQuery(get_called_class());
    }
    
    /**
     * USED IN TABULAR FORM DD (DON'T REMOVE IT)
     * @return type
     */
    public function getActiveDeductionType(){
        
        $data = $this->find()->select('deduction_type,deduction_type')->where(['is_active'=>1,'is_delete'=>0])->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'deduction_type', 'deduction_type');
        return $array;
    }
}
