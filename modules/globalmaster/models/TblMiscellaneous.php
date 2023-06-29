<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_miscellaneous".
 *
 * @property integer $miscellaneous_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $miscellaneous_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $deletedBy
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
  */
class TblMiscellaneous extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_miscellaneous';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'safe'],
            [['miscellaneous_name'], 'required'],
            [['miscellaneous_name'], 'unique'],
            [['miscellaneous_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['miscellaneous_name'], 'string', 'max' => 100],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
//            [['dcs_type_name'], function ($attribute, $params) {
//                    Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
//                },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'miscellaneous_code' => Yii::t('app', 'Miscellaneous Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'miscellaneous_name' => Yii::t('app', 'Miscellaneous Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
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
     * @inheritdoc
     * @return TblMiscellaneousQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMiscellaneousQuery(get_called_class());
    }
    public  function getActiveMiscellaneous(){

         $records = $this->find()->where(['is_active'=>1])->all();
         return  ArrayHelper::map($records, 'miscellaneous_code', 'miscellaneous_name');

     }
}
