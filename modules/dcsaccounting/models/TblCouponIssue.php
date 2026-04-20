<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblBanks;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_coupon_issue".
 *
 * @property string $coupon_issue_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $amount
 * @property string $consumer_code
 * @property integer $consumer_type
 * @property integer $milk_type_code
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $issue_date
 * @property string $voucher_code
 * @property integer $payment_mode
 * @property string $bank_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblCouponIssue extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_coupon_issue';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['coupon_issue_code'], 'required', 'on' => ['androidsync']],
                [['coupon_issue_code', 'originating_org_code', 'originating_org_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'consumer_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'voucher_code', 'bank_code', 'created_by', 'updated_by', 'amount', 'consumer_type', 'milk_type_code', 'is_active', 'is_delete', 'payment_mode', 'originating_type', 'issue_date', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'coupon_issue_code' => Yii::t('app', 'Coupon Issue Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'amount' => Yii::t('app', 'Amount'),
            'consumer_code' => Yii::t('app', 'Consumer Code'),
            'consumer_type' => Yii::t('app', 'Consumer Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'issue_date' => Yii::t('app', 'Issue Date'),
            'voucher_code' => Yii::t('app', 'Voucher'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'bank_code' => Yii::t('app', 'Bank'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
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

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'consumer_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'consumer_code']);
    }

}
