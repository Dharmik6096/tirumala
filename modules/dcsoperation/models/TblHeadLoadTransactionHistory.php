<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_head_load_transaction_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $deleted_at
 * @property double $from_km
 * @property double $from_qty
 * @property string $head_load_transaction_code
 * @property string $history_created_at
 * @property integer $is_delete
 * @property string $operation_type
 * @property double $to_km
 * @property double $to_qty
 * @property string $updated_at
 * @property double $value
 * @property string $created_by
 * @property string $deleted_by
 * @property string $head_load_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblHeadLoad $headLoadCode
 * @property User $updatedBy
 */
class TblHeadLoadTransactionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['head_load_code', 'head_load_transaction_code', 'flg_sentbox_entry','operation_type', 'sync_status', 'from_km', 'from_qty', 'to_km', 'to_qty', 'value', 'created_at','is_delete', 'deleted_at', 'history_created_at', 'sync_timestamp', 'updated_at', 'created_by', 'deleted_by', 'updated_by'], 'safe'],
//            [['from_km', 'from_qty', 'to_km', 'to_qty', 'value'], 'number'],
//            [['is_delete'], 'integer'],
//            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
//            [['head_load_transaction_code'], 'string', 'max' => 25],
//            [['operation_type'], 'string', 'max' => 10],
//            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['head_load_code'], 'string', 'max' => 20],
//            [['head_load_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoad::className(), 'targetAttribute' => ['head_load_code' => 'head_load_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'from_km' => Yii::t('app', 'From Km'),
            'from_qty' => Yii::t('app', 'From Qty'),
            'head_load_transaction_code' => Yii::t('app', 'Head Load Transaction Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'to_km' => Yii::t('app', 'To Km'),
            'to_qty' => Yii::t('app', 'To Qty'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'value' => Yii::t('app', 'Value'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode()
    {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadTransactionHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadTransactionHistoryQuery(get_called_class());
    }
}
