<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_shortage_recovery".
 *
 * @property integer $shortage_recovery_code
 * @property string $union_code
 * @property integer $payment_cycle_code
 * @property string $from_datetime
 * @property string $to_datetime
 * @property string $customer_type
 * @property string $customer_code
 * @property string $recovery_type
 * @property integer $priority
 * @property string $recovery_amount
 * @property string $reference_code
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMilkShortageRecovery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_shortage_recovery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_cycle_code', 'priority', 'originating_type'], 'integer'],
            [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
            [['recovery_amount'], 'number'],
            [['union_code'], 'string', 'max' => 3],
            [['customer_type', 'customer_code'], 'string', 'max' => 20],
            [['recovery_type', 'reference_code'], 'string', 'max' => 50],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shortage_recovery_code' => Yii::t('app', 'Shortage Recovery Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'recovery_type' => Yii::t('app', 'Recovery Type'),
            'priority' => Yii::t('app', 'Priority'),
            'recovery_amount' => Yii::t('app', 'Recovery Amount'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
