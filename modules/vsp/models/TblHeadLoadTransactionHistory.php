<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_head_load_transaction_history".
 *
 * @property integer $id
 * @property string $created_at
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
 * @property string $head_load_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblHeadLoad $headLoadCode
 * @property User $updatedBy
 */
class TblHeadLoadTransactionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_head_load_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'history_created_at', 'updated_at', 'is_delete'], 'safe'],
            [['from_km', 'from_qty', 'to_km', 'to_qty', 'value'], 'safe'],
            [['head_load_transaction_code'], 'safe'],
            [['operation_type'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['head_load_code', 'km_value'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
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
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode() {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadTransactionHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblHeadLoadTransactionHistoryQuery(get_called_class());
    }

}
