<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;
/**
 * This is the model class for table "tbl_milk_quality_type".
 *
 * @property integer $milk_quality_type_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $milk_quality_type_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblMilkQualityGrade[] $tblMilkQualityGrades
 * @property TblUsers $updatedBy
 * @property TblUsers $deletedBy
 * @property TblUsers $createdBy
  */
class TblMilkQualityType extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_quality_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'safe'],
            [['milk_quality_type_name'], 'required'],
            [['milk_quality_type_name'], 'unique'],
            [['milk_quality_type_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['milk_quality_type_name'], 'string', 'max' => 20],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
         /*   [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'milk_quality_type_name' => Yii::t('app', 'Milk Quality Type Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkQualityGrades()
    {
        return $this->hasMany(TblMilkQualityGrade::className(), ['milk_quality_type' => 'milk_quality_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }*/

     /**
     * @inheritdoc
     * @return TblMilkQualityTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkQualityTypeQuery(get_called_class());
    }
  public function getActiveQualityType(){

        $data = $this->find()->select('milk_quality_type_code,milk_quality_type_name')->where(['is_active'=>1])->all();
        $array = ArrayHelper::map($data, 'milk_quality_type_code', 'milk_quality_type_name');
        return $array;
    }
}
