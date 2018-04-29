<?php

namespace app\modules\globalmaster\models;
use Yii;

/**
 * This is the model class for table "tbl_land_unit_history".
 *
 * @property integer $id
 * @property double $conversion_factor
 * @property string $created_at
 * @property string $flg_sentbox_entry
 * @property integer $is_active
 * @property integer $land_unit_code
 * @property string $land_unit_name
 * @property string $local_name
 * @property integer  $is_default
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 */
class TblLandUnitHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_land_unit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['conversion_factor', 'land_unit_name','land_unit_code','local_name','is_default','land_unit'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'conversion_factor' => Yii::t('app', 'Conversion Factor'),
//            'created_at' => Yii::t('app', 'Created At'),
//            'history_created_at' => Yii::t('app', 'History Created At'),
//            'is_active' => Yii::t('app', 'Is Active'),
//            'land_unit_code' => Yii::t('app', 'Land Unit Code'),
//            'land_unit_name' => Yii::t('app', 'Land Unit Name'),
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
    }
*/
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
  /*  public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }
  */
    /**
     * @inheritdoc
     * @return TblLandUnitHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblLandUnitHistoryQuery(get_called_class());
    }
}
