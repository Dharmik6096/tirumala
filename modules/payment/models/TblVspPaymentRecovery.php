<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_payment_recovery".
 *
 * @property integer $recovery_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_type
 * @property string $for_customer_code
 * @property string $from_customer_code
 * @property string $recovery_amount
 * @property integer $payment_cycle_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVspPaymentRecovery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vsp_payment_recovery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recovery_amount'], 'number'],
            [['payment_cycle_code', 'from_shift', 'to_shift', 'originating_type'], 'integer'],
            [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
            [['bmc_code'], 'string', 'max' => 12],
            [['customer_type', 'for_customer_code', 'from_customer_code'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recovery_code' => 'Recovery Code',
            'union_code' => 'Union Code',
            'plant_code' => 'Plant Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'bmc_code' => 'Bmc Code',
            'customer_type' => 'Customer Type',
            'for_customer_code' => 'For Customer Code',
            'from_customer_code' => 'From Customer Code',
            'recovery_amount' => 'Recovery Amount',
            'payment_cycle_code' => 'Payment Cycle Code',
            'from_datetime' => 'From Datetime',
            'from_shift' => 'From Shift',
            'to_datetime' => 'To Datetime',
            'to_shift' => 'To Shift',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
        ];
    }
}
