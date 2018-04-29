<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\models\ChildModel;
/**
 * This is the model class for table "tbl_animal_type".
 *
 * @property integer $animal_type_code
 * @property string $animal_type_name
 * @property string $local_name
 * @property string $short_name
 * @property string $created_at
 * @property integer $is_active
 * @property integer $is_milch
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblUsers $deletedBy

 */
class TblAnimalType extends ChildModel
{
    /**
     * @inheritdoc
     */
   public static function tableName()
    {
        return 'tbl_animal_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_type_name', 'short_name'], 'required'],
            [['animal_type_name'], 'unique'],
            [['animal_type_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['created_at', 'updated_at','short_name'], 'safe'],
            [['is_active',  'is_milch'], 'safe'],
            [['animal_type_name'], 'string', 'max' => 20],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['dcs_type_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['created_by',  'updated_by'], 'string', 'max' => 14],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
           // [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            //[['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
            'animal_type_name' => Yii::t('app', 'Animal Type Name'),
            'short_name' => Yii::t('app', 'Short Name'),
            'local_name' => Yii::t('app', 'Local Name'),
           /* 'created_at' => Yii::t('app', 'Created At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'is_active' => Yii::t('app', 'Is Active'),*/
            'is_milch' => Yii::t('app', 'Is Milch'),
        /*
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
  /* public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }*/

    /**
     * @inheritdoc
     * @return TblAnimalTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblAnimalTypeQuery(get_called_class());
    }

    public function getAnimalMilkTypeArray(){
        
        $data=  $this->getRecords();
        $values = \yii\helpers\ArrayHelper::map($data, 'animal_type_code', 'animal_type_name');
        return $values;
    }
    public function getRecords(){
       return $this->find()->where(['is_active'=>1])->all(); 
    }
    public function getMilkTypeCode($animal_type_name){
        $code=$this->find()->select(['animal_type_code'])->where(['is_active'=>1,'LOWER(animal_type_name)'=>strtolower($animal_type_name)])->one();
        return !empty($code)?$code->animal_type_code:'';
    }
}
