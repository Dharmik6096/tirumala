<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHead;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_bill_head_release_applicability".
 *
 * @property integer $bill_head_release_applicabilty_code
 * @property string $bill_head_code
 * @property string $bill_head_for
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $bmc_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $payment_cycle_code
 * @property string $previous_amount
 * @property string $current_amount
 * @property string $total_amount
 * @property integer $is_processed
 * @property integer $is_disbursed
 * @property string $disburse_date
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBillHeadReleaseApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_release_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_disbursed', 'is_processed', 'previous_amount', 'current_amount', 'total_amount'], 'default', 'value' => 0],
                [['from_date'], 'required'],
                [['from_date', 'to_date', 'disburse_date', 'created_at', 'updated_at'], 'safe'],
                [['payment_cycle_code', 'is_processed', 'is_disbursed', 'originating_type'], 'safe'],
                [['previous_amount', 'current_amount', 'total_amount'], 'safe'],
                [['bill_head_code'], 'safe'],
                [['bill_head_for', 'applicable_code', 'applicable_for'], 'safe'],
                [['bmc_code'], 'safe'],
                [['union_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['applicable_code'], 'setBMCCode']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_release_applicabilty_code' => Yii::t('app', 'Bill Head Release Applicabilty Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'from_date' => Yii::t('app', 'From Date (Release Payment Cycle)'),
            'to_date' => Yii::t('app', 'To Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'previous_amount' => Yii::t('app', 'Previous Amount'),
            'current_amount' => Yii::t('app', 'Current Amount'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'is_processed' => Yii::t('app', 'Is Processed'),
            'is_disbursed' => Yii::t('app', 'Is Disbursed'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function setBMCCode($attribute, $params) {
        if (empty($this->bmc_code)) {
            $this->bmc_code = Yii::$app->general->getCustomer($this, $this->applicable_for, FALSE, TRUE);
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function deleteCheck() {
        return ($this->is_processed == 1 || $this->is_disbursed == 1) ? FALSE : TRUE;
    }

}
