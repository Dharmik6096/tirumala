<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vendor_payment_hold_release_transaction_history".
 *
 * @property integer $id
 * @property integer $vendor_payment_hold_release_transaction_code
 * @property integer $vendor_payment_hold_release_code
 * @property string $bill_head_code
 * @property integer $bill_head_type
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblVendorPaymentHoldReleaseTransactionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vendor_payment_hold_release_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','vendor_payment_hold_release_transaction_code','vendor_payment_hold_release_code','bill_head_code','bill_head_type','amount','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','history_created_at','history_created_by','operation_type','is_reserved'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vendor_payment_hold_release_transaction_code' => Yii::t('app', 'Vendor Payment Hold Release Transaction Code'),
            'vendor_payment_hold_release_code' => Yii::t('app', 'Vendor Payment Hold Release'),
            'bill_head_code' => Yii::t('app', 'Bill Head'),
            'bill_head_type' => Yii::t('app', 'Bill Head Type'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'is_reserved' => Yii::t('app', 'Is Reserved'),
        ];
    }
}
