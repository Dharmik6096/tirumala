<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_banks_history".
 *
 * @property integer $id
 * @property integer $ac_no_length
 * @property string $bank_code
 * @property string $bank_name
 * @property string $local_name
 * @property boolean $checked_ac_no
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property boolean $nationalized_bank
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 */
class TblBanksHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banks_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active', 'old_bank_code', 'ledger_code'], 'safe'],
                [['ac_no_length', 'checked_ac_no', 'nationalized_bank', 'bank_code', 'bank_name', 'local_name', 'is_alpha_acno_allow', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'ac_no_length' => Yii::t('app', 'Ac No Length'),
                  'bank_code' => Yii::t('app', 'Bank Code'),
                  'bank_name' => Yii::t('app', 'Bank Name'),
                  'checked_ac_no' => Yii::t('app', 'Checked Ac No'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'is_active' => Yii::t('app', 'Is Active'),
                  'nationalized_bank' => Yii::t('app', 'Nationalized Bank'),
                  'operation_type' => Yii::t('app', 'Operation Type'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'updated_by' => Yii::t('app', 'Updated By'), */
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
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
    /*   public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      }
     */

    /**
     * @inheritdoc
     * @return TblBanksHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBanksHistoryQuery(get_called_class());
    }

}
