<?php

namespace app\modules\dcsoperation\models;

use Yii;
Use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tbl_dcs_payment_cycle".
 *
 * @property string $dcs_payment_cycle_code
 * @property string $created_at
 * @property string $deleted_at
 * @property string $from_date
 * @property string $from_shift
 * @property integer $interval_value
 * @property integer $is_active
 * @property integer $is_billing
 * @property integer $is_delete
 * @property integer $lock_billing_process
 * @property string $to_date
 * @property string $to_shift
 * @property string $updated_at
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $updated_by
 */
class TblDcsPaymentCycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_payment_cycle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_payment_cycle_code'], 'required'],
            [['created_at', 'deleted_at', 'from_date',  'to_date', 'updated_at'], 'safe'],
            [['interval_value', 'is_active', 'is_billing', 'is_delete', 'lock_billing_process'], 'integer'],
            [['dcs_payment_cycle_code', 'from_shift', 'to_shift'], 'string', 'max' => 255],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code'], 'string', 'max' => 9],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'interval_value' => Yii::t('app', 'Interval Value'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_billing' => Yii::t('app', 'Billing'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'lock_billing_process' => Yii::t('app', 'Lock Billing Process'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsPaymentCycleQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
}
