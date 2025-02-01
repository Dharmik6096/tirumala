<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_permanent_hold_amount_history".
 *
 * @property integer $id
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
 * @property string $release_date
 * @property string $release_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblPermanentHoldAmountHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_permanent_hold_amount_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permanent_hold_amount_code','union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'release_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['transaction_date', 'release_date', 'created_at', 'updated_at'], 'safe'],
            [['payment_cycle_code', 'originating_type'], 'safe'],
            [['hold_amount','history_created_by', 'history_created_at'], 'safe'],
            [['customer_type', 'customer_code','operation_type'], 'safe'],
            [['bank_code','branch_code','bank_account_no','ifsc','bank_name','branch_name','beneficiary_name','is_verified','from_date','to_date', 'release_amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
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
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'release_amount' => Yii::t('app', 'Release Amount'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'release_date' => Yii::t('app', 'Release Date'),
            'release_by' => Yii::t('app', 'Release By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }
}
