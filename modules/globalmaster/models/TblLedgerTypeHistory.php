<?php

namespace app\modules\globalmaster\models;
use Yii;

/**
 * This is the model class for table "tbl_ledger_type_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property integer $is_balance_sheet
 * @property integer $is_profit_loss
 * @property string $ledger_type_name
 * @property integer $ledger_type_code
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 */
class TblLedgerTypeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ledger_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['ledger_type_code', 'ledger_type_name','is_balance_sheet','is_profit_loss','is_balance_sheet','local_name'], 'safe'],
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
//            'is_balance_sheet' => Yii::t('app', 'Is Balance Sheet'),
//            'is_profit_loss' => Yii::t('app', 'Is Profit Loss'),
//            'ledger_type_name' => Yii::t('app', 'Ledger Typeledger Type Name'),
//            'ledger_type_code' => Yii::t('app', 'Ledger Type Code'),
//            'operation_type' => Yii::t('app', 'Operation Type'),
//            'updated_at' => Yii::t('app', 'Updated At'),
//            'created_by' => Yii::t('app', 'Created By'),
//            'updated_by' => Yii::t('app', 'Updated By'),
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
   /* public function getDeletedBy()
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
     * @return TblLedgerTypeHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblLedgerTypeHistoryQuery(get_called_class());
    }
}
