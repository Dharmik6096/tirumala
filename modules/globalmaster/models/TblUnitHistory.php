<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_unit_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property integer $unit_code
 * @property string $unit_name
 * @property string $local_name
 * @property string $short_name
 * @property string $local_short_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 */
class TblUnitHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_units_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'history_created_at', 'updated_at', 'short_name'], 'safe'],
                [['is_active', 'unit_code', 'local_name', 'local_short_name'], 'safe'],
                [['union_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'created_at' => Yii::t('app', 'Created At'),
//            'history_created_at' => Yii::t('app', 'History Created At'),
//            'is_active' => Yii::t('app', 'Is Active'),
//            'operation_type' => Yii::t('app', 'Operation Type'),
//            'unit_code' => Yii::t('app', 'Unit Code'),
//            'unit_name' => Yii::t('app', 'Unit Name'),
//            'short_name' => Yii::t('app', 'Short Name'),
//            'updated_at' => Yii::t('app', 'Updated At'),
//            'created_by' => Yii::t('app', 'Created By'),
//            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

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
    /*   public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      }
     */
    /**
     * @return \yii\db\ActiveQuery
     */
    /*    public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      }
     */

    /**
     * @inheritdoc
     * @return TblUnitHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblUnitHistoryQuery(get_called_class());
    }

}
