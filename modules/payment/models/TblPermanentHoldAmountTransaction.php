<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_permanent_hold_amount_transaction".
 *
 * @property integer $permanent_hold_amount_transaction_code
 * @property integer $permanent_hold_amount_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $transaction_date
 * @property integer $payment_cycle_code
 * @property string $hold_amount
 * @property string $release_amount
 * @property string $release_date
 * @property string $release_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblPermanentHoldAmountTransaction extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_permanent_hold_amount_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permanent_hold_amount_code','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','customer_type','customer_code','transaction_date','payment_cycle_code','hold_amount','release_amount','release_date','release_by','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permanent_hold_amount_transaction_code' => Yii::t('app', 'Permanent Hold Amount Transaction Code'),
            'permanent_hold_amount_code' => Yii::t('app', 'Permanent Hold Amount Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'release_amount' => Yii::t('app', 'Release Amount'),
            'release_date' => Yii::t('app', 'Release Date'),
            'release_by' => Yii::t('app', 'Release By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
