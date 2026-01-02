<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vendor_payment_hold_release_summary_history".
 *
 * @property integer $id
 * @property integer $vendor_payment_hold_release_summary_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_type
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $status
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
class TblVendorPaymentHoldReleaseSummaryHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vendor_payment_hold_release_summary_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','vendor_payment_hold_release_summary_code','union_code','plant_code','mcc_plant_code','bmc_code','customer_type','from_datetime','from_shift','to_datetime','to_shift','status','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','history_created_at','history_created_by','operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vendor_payment_hold_release_summary_code' => Yii::t('app', 'Vendor Payment Hold Release Summary Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'status' => Yii::t('app', 'Status'),
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
        ];
    }
}
