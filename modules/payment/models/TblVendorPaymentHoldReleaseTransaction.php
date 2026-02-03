<?php

namespace app\modules\payment\models;

use app\modules\vsp\models\TblBillHead;
use Yii;

/**
 * This is the model class for table "tbl_vendor_payment_hold_release_transaction".
 *
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
 */
class TblVendorPaymentHoldReleaseTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vendor_payment_hold_release_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_payment_hold_release_transaction_code','vendor_payment_hold_release_code','bill_head_code','bill_head_type','amount','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type', 'is_reserved'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
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
            'is_reserved' => Yii::t('app', 'Is Reserved'),
        ];
    }

    public function getVendorPaymentHoldReleaseCode() {
        return $this->hasOne(TblVendorPaymentHoldRelease::className(), ['vendor_payment_hold_release_code' => 'vendor_payment_hold_release_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }
}
