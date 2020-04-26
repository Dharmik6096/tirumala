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
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\collection\models\TblBmcCollection;
use app\modules\vsp\models\TblBillHeadInstallment;
use app\modules\dcsoperation\models\TblMember;

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
    public $installment_amount, $customer_name, $installment_start_date, $ex_code, $member_code;

    public static function tableName() {
        return 'tbl_bill_head_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'bill_head_code', 'dcs_code', 'amount', 'created_by', 'updated_by'], 'string'],
            [['bill_head_code', 'bmc_code', 'amount'], 'required'],
            [['dcs_code'], 'required', 'on' => ['memberBillHead', 'importDetailCsv']],
            [['member_code'], 'required', 'on' => ['importDetailCsv']],
            [['payment_cycle_code', 'union_code', 'plant_code', 'mcc_plant_code', 'customer_type'], 'required', 'except' => ['importCsv', 'importDetailCsv']],
            [['installment_start_date'], 'required', 'on' => ['importCsv', 'importDetailCsv']],
            [['payment_cycle_code', 'is_installment', 'is_active'], 'integer'],
            [['created_at', 'updated_at', 'installment_amount', 'bill_head_for'], 'safe'],
            [['amount'], 'number', 'min' => 0],
            [['no_installment'], 'number', 'min' => 0],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['customer_type', 'customer_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'installment_start_date'], 'safe'],
            [['no_installment'], 'default', 'value' => 1],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
            [['bill_head_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBillHead::className(), 'targetAttribute' => ['bill_head_code' => 'bill_head_code'], 'on' => ['importCsv']],
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
            [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'on' => ['importCsv']],
            [['installment_start_date'], 'convertDateDot', 'on' => ['importCsv', 'importDetailCsv']],
            [['installment_start_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv', 'importDetailCsv']],
            [['installment_start_date'], 'convertDate', 'on' => ['importCsv', 'importDetailCsv']],
            [['bmc_code'], 'importData', 'skipOnError' => true, 'on' => ['importCsv', 'importDetailCsv']],
            [['customer_code'], 'required', 'message' => Yii::t('app/validation', 'Name Cannot be blank'), 'except' => ['importCsv', 'importDetailCsv']],
            [['customer_code'], 'required', 'on' => ['importCsv']]
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
            'no_installment' => 'No. of Installment',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'installment_amount' => 'Installment Amount',
            'bmc_code' => 'BMC',
            'plant_code' => 'Plant',
            'mcc_plant_code' => 'MCC',
            'customer_code' => 'Code',
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

    public function getInstallmentCode() {
        return $this->hasOne(TblBillHeadInstallment::className(), ['bill_head_detail_code' => 'bill_head_detail_code']);
    }

    public function convertDateDot() {
        try {
            $this->installment_start_date = Yii::$app->controls->view_date($this->installment_start_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->installment_start_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->installment_start_date = !empty($this->installment_start_date) ? Yii::$app->controls->view_date($this->installment_start_date, 'php:Y-m-d') : NULL;
        }
    }

    public function importData($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            if (!empty($this->dcs_code)) {
                $this->customer_code = $this->dcs_code;
                $bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
                if ($bmc_code != $this->bmc_code) {
                    $this->addError('dcs_code', Yii::t('app/validation', $this->getAttributeLabel('dcs_code') . ' is invalid'));
                }
                $member = $this->dcs_code . $this->member_code;
                $this->customer_code = $member;
                if (empty($this->memberCode)) {
                    $this->addError('member_code', Yii::t('app/validation', $this->getAttributeLabel('member_code') . ' is invalid'));
                }
                $this->bill_head_for = 'MEMBER';
                $this->customer_type = 'MEMBER';
                $customer_type = 'DCS';
                $customer_code = $this->dcs_code;
            } else {
                $this->bill_head_for = 'VENDOR';
                Yii::$app->general->validateCustomer($this);
                $customer_type = $this->customer_type;
                $customer_code = $this->customer_code;
            }
            $billModel = new TblBillHead();
            $list = $billModel->billHeadTypeWise($this->union_code, $customer_type, $customer_code, $this->bill_head_for);
            if (!array_key_exists($this->bill_head_code, $list)) {
                $this->addError('bill_head_code', Yii::t('app/validation', $this->getAttributeLabel('bill_head_code') . ' is invalid'));
            }
            $paymentModel = new TblPaymentCycleApplicability();
            $paymentModel->applicable_type = $customer_type;
            $paymentModel->applicable_code = $this->bmc_code;
            $paymentModel->applicable_for = 'BMC';
            $modelData = $paymentModel->getApplicablePaymentCycle(date('Y-m-d', strtotime($this->installment_start_date)));
            if (empty($modelData)) {
                $this->addError('installment_start_date', "Payment Cycle aplicability not available for Installment Start Date.");
                return false;
            } else {
                return $this->payment_cycle_code = $modelData->payment_cycle_code;
            }
        }
    }

    public function validatePaymentCycle() {
        $model = new TblPaymentCycleApplicability();
        $model->applicable_type = $this->customer_type;
        $model->applicable_code = $this->bmc_code;
        $model->applicable_for = 'BMC';
        $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($this->installment_start_date)));
        if (empty($modelData)) {
            $this->addError('installment_start_date', "Payment Cycle aplicability not available for Installment Start Date.");
            return false;
        } else {
            return $this->payment_cycle_code = $modelData->payment_cycle_code;
        }
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->ex_code]);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

}
