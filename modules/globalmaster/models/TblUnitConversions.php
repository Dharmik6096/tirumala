<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_unit_conversions".
 *
 * @property integer $unit_conversion_code
 * @property string $conversion_factor
 * @property string $created_at
 * @property resource $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $from_unit
 * @property integer $to_unit
 */
class TblUnitConversions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_unit_conversions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversion_factor'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'string'],
            [['is_active', 'from_unit', 'to_unit'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_conversion_code' => Yii::t('app', 'Unit Conversion Code'),
            'conversion_factor' => Yii::t('app', 'Conversion Factor'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'from_unit' => Yii::t('app', 'From Unit'),
            'to_unit' => Yii::t('app', 'To Unit'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblUnitConversionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnitConversionsQuery(get_called_class());
    }
    
    public function getCovertedValue($value,$from_unit,$to_unit)
    {
        $conv=$this->find()->where(['from_unit'=>$from_unit,'to_unit'=>$to_unit,'is_active'=>1])->one();
        if(!empty($conv))
        {
            $factor=$conv->conversion_factor;
            $calc_value=$value*$factor;
            return $calc_value;    
        }
        else {
            return $value;
        }
    }
}
