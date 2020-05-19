<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_salary_heads_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $salary_head_code
 * @property string $salary_head_name
 * @property integer $salary_head_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 */
class TblSalaryHeadsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_salary_heads_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'deleted_at', 'history_created_at', 'salary_head_code', 'updated_at'], 'safe'],
            [['is_active', 'salary_head_type'], 'safe'],
            [['operation_type', 'created_by', 'deleted_by', 'updated_by'], 'safe'],
            [['salary_head_name'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'salary_head_code' => Yii::t('app', 'Salary Head Code'),
            'salary_head_name' => Yii::t('app', 'Salary Head Name'),
            'salary_head_type' => Yii::t('app', 'Salary Head Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*    public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      }
     */
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
    /*   public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
      }
     */

    /**
     * @inheritdoc
     * @return TblSalaryHeadsHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblSalaryHeadsHistoryQuery(get_called_class());
    }

}
