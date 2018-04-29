<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_payment_cycle_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $dcs_payment_cycle_code
 * @property string $from_date
 * @property string $transfer_date
 * @property string $history_created_at
 * @property integer $interval_value
 * @property integer $is_active
 * @property integer $is_billing
 * @property string $to_date
 * @property string $updated_at
 * @property string $updated_by
 * @property string $dcs_code
 * @property string $union_code
 * @property integer $lock_data
 *
 * @property TblDcs $dcsCode
 */
class TblDcsPaymentCycleHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_payment_cycle_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['dcs_code', 'interval_value', 'is_active', 'is_billing', 'created_by', 'dcs_payment_cycle_code', 'updated_by', 'dcs_code', 'union_code', 'created_at', 'from_date', 'transfer_date', 'history_created_at', 'to_date', 'updated_at','lock_data','operation_type'], 'safe'],
//            [['created_by', 'dcs_payment_cycle_code', 'updated_by', 'dcs_code', 'union_code'], 'string'],
//            [['interval_value', 'is_active', 'is_billing'], 'integer'],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
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
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'transfer_date' => Yii::t('app', 'Transfer Date'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'interval_value' => Yii::t('app', 'Interval Value'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_billing' => Yii::t('app', 'Is Billing'),
            'to_date' => Yii::t('app', 'To Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsPaymentCycleHistoryQuery(get_called_class());
    }
}
