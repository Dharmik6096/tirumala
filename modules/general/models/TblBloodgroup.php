<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_bloodgroup".
 *
 * @property integer $blood_group_code
 * @property string $blood_group
 * @property integer $is_active
 * @property integer $is_delete
 */
class TblBloodgroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bloodgroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blood_group'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'blood_group_code' => Yii::t('app', 'Blood Group Code'),
            'blood_group' => Yii::t('app', 'Blood Group'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblBloodgroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBloodgroupQuery(get_called_class());
    }
    
    public function getBloodGroup($blood_group){
        return $this->find()->where(['blood_group_code'=>$blood_group])->count();      
    }
   
}
