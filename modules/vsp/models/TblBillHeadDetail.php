<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHead;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_bill_head_detail".
 *
 * @property integer $bill_head_detail_code
 * @property string $union_code
 * @property string $bill_head_code
 * @property integer $payment_cycle_code
 * @property string $dcs_code
 * @property string $amount
 * @property integer $is_installment
 * @property string $no_installment
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBillHeadDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $installment_amount;

    public static function tableName() {
        return 'tbl_bill_head_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'bill_head_code', 'dcs_code', 'amount', 'created_by', 'updated_by'], 'string'],
            [['bill_head_code', 'payment_cycle_code', 'plant_code', 'mcc_plant_code', 'customer_code', 'customer_type', 'amount'], 'required'],
            [['payment_cycle_code', 'is_installment', 'is_active'], 'integer'],
            [['created_at', 'updated_at', 'installment_amount'], 'safe'],
            [['amount'], 'number', 'min' => 0],
            [['no_installment'], 'number', 'min' => 0],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['customer_type', 'customer_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_detail_code' => 'Bill Head Detail Code',
            'union_code' => 'Union',
            'bill_head_code' => 'Bill Head',
            'payment_cycle_code' => 'Payment Cycle',
            'dcs_code' => 'DCS',
            'amount' => 'Amount',
            'is_installment' => 'Is Installment',
            'no_installment' => 'No Of Installment',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'installment_amount' => 'Installment Amount',
            'bmc_code' => 'BMC',
            'plant_code' => 'Plant',
            'mcc_plant_code' => 'MCC',
            'customer_code' => 'Name',
            'customer_type' => 'Type',
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getData($dcs_code, $payment_cycle_code, $bill_head_code) {
        return $this->find()->select(['bill_head_code', 'amount', 'bill_head_detail_code'])->where(['dcs_code' => $dcs_code, 'payment_cycle_code' => $payment_cycle_code, 'bill_head_code' => $bill_head_code, 'is_active' => '1'])->asArray()->all();
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['mcc_plant_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }


}
