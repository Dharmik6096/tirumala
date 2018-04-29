<?php

namespace app\modules\geo\models;

use Yii;
use app\modules\globalmaster\models\TblMiscellaneous;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_village_miscellaneous".
 *
 * @property string $village_miscellaneous_code
 * @property string $created_at
 * @property string $description
 * @property string $local_description
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property integer $miscellaneous_code
 * @property string $updated_by
 * @property string $village_code
 *
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblMiscellaneous $miscellaneousCode
 * @property TblVillages $villageCode
 * @property TblUsers $deletedBy
 */
class TblVillageMiscellaneous extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_village_miscellaneous';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['village_miscellaneous_code', 'description','miscellaneous_code'], 'required'],
            [['is_active', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['miscellaneous_code'], 'integer'],
            [['miscellaneous_code'], 'safe'],
            [['local_description'], function ($attribute, $params) {                     
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);                 
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['village_code'], 'string', 'max' => 6],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_code']],
      //      [['miscellaneous_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMiscellaneous::className(), 'targetAttribute' => ['miscellaneous_code' => 'id']],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'village_miscellaneous_code' => Yii::t('app', 'Village Miscellaneous Code'),
          /*  'created_at' => Yii::t('app', 'Created At'),*/
            'description' => Yii::t('app', 'Description'),
            'local_description' => Yii::t('app', 'Local Description'),
           /* 
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),*/
            'miscellaneous_code' => Yii::t('app', 'Miscellaneous Name'),
           /* 'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village Code'),*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_code' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_code' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMescellaneousCode()
    {
        return $this->hasOne(TblMiscellaneous::className(), ['miscellaneous_code' => 'miscellaneous_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode()
    {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }

    /**
     * @inheritdoc
     * @return TblVillageMiscellaneousQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVillageMiscellaneousQuery(get_called_class());
    }
    
    public function getCode() {    
        $data=  $this->find()->select(["MAX(CAST(village_miscellaneous_code as int)) as village_miscellaneous_code"])->one();
        return (int)$data['village_miscellaneous_code']+1;       
    }
}
