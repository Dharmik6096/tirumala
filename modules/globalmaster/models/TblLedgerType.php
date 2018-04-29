<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_ledger_type".
 *
 * @property integer $ledger_type_code
 * @property string $created_at
 * @property integer $is_active
 * @property integer $is_balance_sheet
 * @property integer $is_profit_loss
 * @property string $ledger_type_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblLedgerGroup[] $tblLedgerGroups
 * @property TblLedgerGroupHistory[] $tblLedgerGroupHistories
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblUsers $deletedBy
 */
class TblLedgerType extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ledger_type_code', 'ledger_type_name'], 'required'],
            [['ledger_type_name'], function ($attribute, $params) {
            Yii::$app->general->validateName($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['ledger_type_name'], 'unique'],
            [['ledger_type_code', 'is_balance_sheet',], 'integer'],
            [['created_at', 'is_profit_loss', 'updated_at', 'is_active'], 'safe'],
            [['ledger_type_name'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['local_name'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
                //  [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
                // [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ledger_type_code' => Yii::t('app', 'Ledger Type Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_balance_sheet' => Yii::t('app', 'Is Balance Sheet'),
            'is_profit_loss' => Yii::t('app', 'Is Profit Loss'),
            'ledger_type_name' => Yii::t('app', 'Ledger Type Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroups() {
        return $this->hasMany(TblLedgerGroup::className(), ['ledger_type_code' => 'ledger_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroupHistories() {
        return $this->hasMany(TblLedgerGroupHistory::className(), ['ledger_type_code' => 'ledger_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*    public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
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
    /*  public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className());
      }
     */

    /**
     * @inheritdoc
     * @return TblLedgerTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblLedgerTypeQuery(get_called_class());
    }

    /* public function getCode(){
      $val= (new \yii\db\Query)
      ->select("MAX(CAST(trim(ledger_type_code) AS UNSIGNED)) as ledger_type_code")
      ->from('tbl_ledger_type')
      ->one();
      $number=(int)$val['ledger_type_code']+1;
      return str_pad($number,2,'0',STR_PAD_LEFT);
      } */
}
