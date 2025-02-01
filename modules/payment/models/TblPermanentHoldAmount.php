<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\dcsoperation\models\TblMember;
use app\modules\payment\models\TblPaymentCycle;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_permanent_hold_amount".
 *
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
 */
class TblPermanentHoldAmount extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_permanent_hold_amount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'release_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['payment_cycle_code', 'originating_type'], 'safe'],
            [['hold_amount','release_date'], 'safe'],
            [['customer_type', 'customer_code'], 'safe'],
            [['bank_code','branch_code','bank_account_no','ifsc','bank_name','branch_name','beneficiary_name','is_verified','from_date','to_date', 'release_amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permanent_hold_amount_code' => Yii::t('app', 'Permanent Hold Amount Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
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
            'originating_org_code' => Yii::t('app', 'Originating Org'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
    
    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }
    
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }
    
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }
    
    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }
    
    public function getReleaseBy() {
        return $this->hasOne(User::className(), ['user_code' => 'release_by']);
    }
}
