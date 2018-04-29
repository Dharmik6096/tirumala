<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_gender".
 *
 * @property integer $gender_code
 * @property string $gender
 * @property integer $is_active
 * @property integer $is_delete
 */
class TblGender extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_gender';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gender_code' => Yii::t('app', 'Gender Code'),
            'gender' => Yii::t('app', 'Gender'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblGenderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblGenderQuery(get_called_class());
    }
    
    public function getGender($gender){
        return $this->find()->where(['gender_code'=>$gender])->count();        
    }    
}
