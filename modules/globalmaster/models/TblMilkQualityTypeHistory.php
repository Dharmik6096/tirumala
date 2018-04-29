<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_quality_type_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property integer $milk_quality_type_code
 * @property string $milk_quality_type_name
 * @property string $local_name
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 */
class TblMilkQualityTypeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_quality_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['milk_quality_type_code', 'milk_quality_type_name','local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'created_at' => Yii::t('app', 'Created At'),
//            'history_created_at' => Yii::t('app', 'History Created At'),
//            'is_active' => Yii::t('app', 'Is Active'),
//            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Code'),
//            'milk_quality_type_name' => Yii::t('app', 'Milk Quality Type Name'),
//            'operation_type' => Yii::t('app', 'Operation Type'),
//            'updated_at' => Yii::t('app', 'Updated At'),
//            'created_by' => Yii::t('app', 'Created By'),
//            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }*/

    /**
     * @inheritdoc
     * @return TblMilkQualityTypeHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkQualityTypeHistoryQuery(get_called_class());
    }
}
