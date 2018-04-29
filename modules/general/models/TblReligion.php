<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_religion".
 *
 * @property integer $religion_code
 * @property string $religion
 * @property integer $is_active
 */
class TblReligion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_religion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['religion_code'], 'required'],
            [['religion_code', 'is_active'], 'integer'],
            [['religion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'religion_code' => Yii::t('app', 'Religion Code'),
            'religion' => Yii::t('app', 'Religion'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblReligionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblReligionQuery(get_called_class());
    }
    
    public function getReligion($religion_code){
        return $this->find()->where(['religion_code'=>$religion_code])->count();       
    } 
}
