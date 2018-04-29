<?php

namespace app\modules\globalmaster\models;
use Yii;

/**
 * This is the model class for table "tbl_caste_category_history".
 *
 * @property integer $id
 * @property integer $caste_category_code
 * @property string $caste_category_name
 * @property string $local_name
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 */
class TblCasteCategoryHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_caste_category_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['caste_category_code', 'caste_category_name','local_name'], 'safe'],
//            [['local_name'], function ($attribute, $params) {
//                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
//            },'skipOnEmpty'=> false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
         /*   'id' => Yii::t('app', 'ID'),
            'caste_category_code' => Yii::t('app', 'Caste Category Code'),
            'caste_category_name' => Yii::t('app', 'Caste Category Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }
	*/

    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }
*/
    /**
     * @inheritdoc
     * @return TblCasteCategoryHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblCasteCategoryHistoryQuery(get_called_class());
    }
}
