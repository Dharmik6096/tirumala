<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_designation_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property integer $designation_code
 * @property string $designation_name
 * @property integer $designation_type
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblUsers $deletedBy
 */
class TblDesignationHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_designation_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['designation_type', 'created_at', 'history_created_at', 'updated_at', 'is_active'], 'safe'],
            [['designation_code'], 'safe'],
            [['designation_name'], 'safe'],
            [['designation_name'], 'safe'],
            [['operation_type', 'created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'designation_code' => Yii::t('app', 'Designation Code'),
            'designation_name' => Yii::t('app', 'Designation Name'),
            'designation_type' => Yii::t('app', 'Designation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*  public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
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
     * @return \yii\db\ActiveQuery
     */
    /*  public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
      }
     */

    /**
     * @inheritdoc
     * @return TblDesignationHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDesignationHistoryQuery(get_called_class());
    }

}
