<?php

namespace app\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "system_configuration".
 *
 * @property integer $id
 * @property string $module_name
 * @property integer $from_value
 * @property integer $to_value
 * @property integer $village_from
 * @property integer $village_to
 * @property string $organization_type
 * @property string $organization_id
 * @property string $union_id
 */
class SystemConfiguration extends \yii\db\ActiveRecord
{

    public $village_from;
    public $village_to;
    public $union_id;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_value', 'to_value','village_from','village_to'], 'integer'],
            [['from_value', 'to_value','organization_id','village_from','village_to','organization_type','module_name','id','union_id'], 'safe'],
            [['module_name', 'organization_type', 'organization_id'], 'string', 'max' => 100],
            [['from_value','to_value'],'rangeValidate','skipOnEmpty' => false],
            [['village_from','village_to'],'string','max'=>6,'min'=>6],
            [['village_from','village_to'],'villageValidate'],
//            ['organization_id', 'required', 'when' => function($model) {
//                return empty($model->village_from);
//            },'enableClientValidation' => false],
        ];
    }

     public function rangeValidate($attribute, $params) {

         switch ($this->id){
             case 1:
                 if(strlen($this->$attribute)>2){
                     $this->addError($attribute, $this->getAttributeLabel($attribute).' required in 2 digits.');
                 }
                 break;
             case 2:
                 if(strlen($this->$attribute)>3){
                     $this->addError($attribute, $this->getAttributeLabel($attribute).' required in 3 digits.');
                 }
                 break;
             case 3:
                 if(strlen($this->$attribute)>5){
                     $this->addError($attribute, $this->getAttributeLabel($attribute).' required in 5 digits.');
                 }
                 break;
         }
    }
    
    public function villageValidate($attribute, $params) {

        if (!empty($this->village_from) && !empty($this->village_to)) {
                $query = $this->find()->where('('.$this->village_from.'  between to_value and from_value) OR ('.$this->village_to.' between to_value  and from_value)');
                if(!empty($this->id))         
                    $query->andWhere(['<>','id',$this->id]);
                $record=$query->one();
                if($record){
                    $this->addError($attribute, 'Can not use range in between of used range.');
                    return false;
                }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'module_name' => Yii::t('app', 'Module Name'),
            'from_value' => Yii::t('app', 'From Value'),
            'to_value' => Yii::t('app', 'To Value'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'organization_id' => Yii::t('app', 'Organization ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return SystemConfigurationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SystemConfigurationQuery(get_called_class());
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'organization_id']);
    }
    
    public static function getRecords($flag){
        
        $values= new SystemConfiguration();
         switch ($flag) {
             case 'state':
                 $values = SystemConfiguration::find()->where(['id'=>1])->one();
                 break;
             case 'district':
                 $values = SystemConfiguration::find()->where(['id'=>2])->one();
                 break;
             case 'sub-district':
                 $values = SystemConfiguration::find()->where(['id'=>3])->one();
                 break;
         }
         
         return $values;
    }
    
    public function createObject(){
        
        $modelUnion = new TblUnions;
        $unions = $modelUnion->getActiveUnions();
        
        foreach ($unions as $key=>$u){
            $records = $this->find()->where(['organization_id' => $u->union_code])->one();
            if($records)
                $models[$key] = $records;
            else
                $models[$key] = new SystemConfiguration;
                $models[$key]->organization_id = $u->union_code;
        }
        
        return $models;
    }
}
