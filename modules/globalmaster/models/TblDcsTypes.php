<?php

namespace app\modules\globalmaster\models;
use Yii;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_dcs_types".
 *
 * @property integer $dcs_type_code
 * @property string $created_at
 * @property string $dcs_type_name
 * @property string $local_name
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 */
class TblDcsTypes extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'safe'],
            [['dcs_type_name'], 'string', 'max' => 255],
            [['dcs_type_name'], 'required'],
            [['dcs_type_name'], 'unique'],
            [['dcs_type_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dcs_type_code' => Yii::t('app', 'Society Type ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'dcs_type_name' => Yii::t('app', 'Society Type Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

   public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className());
    }

     public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblDcsTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsTypesQuery(get_called_class());
    }

    public  function getActiveDcsTypes(){
         //echo $fedrCode;
         $value = $this->find()->where(['is_active'=>1])->all();
         $value = ArrayHelper::map($value, 'dcs_type_code', 'dcs_type_name');
         return $value;
     }
}
