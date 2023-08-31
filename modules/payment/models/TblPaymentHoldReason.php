<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_payment_hold_reason".
 *
 * @property integer $payment_hold_reason_code
 * @property string $description
 * @property string $hold_reason
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $is_active
 */
class TblPaymentHoldReason extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_payment_hold_reason';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'hold_reason', 'created_at', 'updated_at'], 'safe'],
            [['originating_type', 'is_active'], 'integer'],
            [['description', 'hold_reason'], 'string', 'max' => 255],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 20],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_hold_reason_code' => 'Payment Hold Reason Code',
            'description' => 'Description',
            'hold_reason' => 'Hold Reason',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'is_active' => 'Is Active',
        ];
    }
}
