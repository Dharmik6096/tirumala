<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_type".
 *
 * @property integer $code
 * @property string $type
 */
class TblMilkType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_quality_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblMilkTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkTypeQuery(get_called_class());
    }

    public function getMilkType(){
        return $this->find()->all();
    }
    
    public function getActiveMilkType(){
        
        $data = $this->getMilkType();
        $array = \yii\helpers\ArrayHelper::map($data, 'code', 'type');
        return $array;
    }
    
    public function getMilkTypeId($name){
        
        $data = $this->find()->select(['code'])->where(['type'=>$name])->one();
        
        return ($data)?$data->code:'';
    }
}
