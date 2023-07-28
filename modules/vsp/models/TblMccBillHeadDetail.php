<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblMccBillHead;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\vsp\models\TblMccBillHeadInstallment;

/**
 * This is the model class for table "tbl_mcc_bill_head_detail".
 *
 * @property string $mcc_bill_head_detail_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $mcc_bill_head_code
 * @property integer $payment_cycle_code
 * @property string $amount
 * @property integer $is_installment
 * @property integer $no_installment
 * @property integer $is_active
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMccBillHeadDetail extends \app\models\ChildModel {

    public $installment_amount, $installment_start_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_bill_head_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_code', 'mcc_bill_head_code', 'transaction_date', 'amount'], 'required'],
            [['mcc_bill_head_detail_code', 'amount', 'is_installment', 'no_installment', 'is_active', 'payment_cycle_code', 'transaction_date'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'mcc_bill_head_code', 'mcc_bill_head_detail_code'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code'], 'required', 'except' => ['importCsv', 'importDetailCsv']],
            [['payment_cycle_code', 'is_installment', 'is_active'], 'integer'],
            [['amount'], 'number', 'min' => 0],
            [['no_installment'], 'number', 'min' => 0],
            [['no_installment'], 'default', 'value' => 1],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv', 'importDetailCsv']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv', 'importDetailCsv']],
            [['mcc_bill_head_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccBillHead::className(), 'targetAttribute' => ['mcc_bill_head_code' => 'mcc_bill_head_code'], 'on' => ['importCsv']],
            [['transaction_date'], 'convertDateDot', 'on' => ['importCsv', 'importDetailCsv']],
            [['transaction_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv', 'importDetailCsv']],
            [['transaction_date'], 'convertDate', 'on' => ['importCsv', 'importDetailCsv']],
            [['bmc_code'], 'importData', 'skipOnError' => true, 'on' => ['importCsv', 'importDetailCsv']],
            [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        $flag = ['data_lock_bmc', 'billing_lock_bmc'];
                        Yii::$app->general->paymentCycleLock($this, 'transaction_date', 'bmc_code', 'BMC', 'BMC', $flag);
                    }
                }, 'skipOnEmpty' => TRUE, 'except' => ['importCsv', 'importDetailCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_bill_head_detail_code' => Yii::t('app', 'Mcc Bill Head Detail Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_bill_head_code' => Yii::t('app', 'Bill Head'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
            'amount' => Yii::t('app', 'Amount'),
            'is_installment' => Yii::t('app', 'Is Installment'),
            'no_installment' => Yii::t('app', 'No Installment'),
            'is_active' => Yii::t('app', 'Is Active'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getPaymentCycleCode() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getData($bmc_code, $payment_cycle_code, $bill_head_code) {
        return $this->find()->select(['mcc_bill_head_code', 'amount', 'mcc_bill_head_detail_code'])->where(['bmc_code' => $bmc_code, 'payment_cycle_code' => $payment_cycle_code, 'mcc_bill_head_code' => $bill_head_code, 'is_active' => '1'])->asArray()->all();
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblMccBillHead::className(), ['mcc_bill_head_code' => 'mcc_bill_head_code']);
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

    public function getInstallmentCode() {
        return $this->hasOne(TblMccBillHeadInstallment::className(), ['mcc_bill_head_detail_code' => 'mcc_bill_head_detail_code']);
    }

    public function convertDateDot() {
        try {
            $this->transaction_date = Yii::$app->controls->view_date($this->transaction_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->transaction_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->transaction_date = !empty($this->transaction_date) ? Yii::$app->controls->view_date($this->transaction_date, 'php:Y-m-d') : NULL;
        }
    }

    public function importData($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');

            $billModel = new TblMccBillHead();
            $list = $billModel->billHeadTypeWise($this->union_code, $this->bmc_code);
            if (!array_key_exists($this->mcc_bill_head_code, $list)) {
                $this->addError('mcc_bill_head_code', Yii::t('app/validation', $this->getAttributeLabel('mcc_bill_head_code') . ' is invalid'));
            }

            $flag = ['data_lock_bmc', 'billing_lock_bmc'];
            Yii::$app->general->paymentCycleLock($this, 'transaction_date', 'bmc_code', 'BMC', 'BMC', $flag);
        }
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model)) {
            $no = !empty($model->no_installment) ? ($model->no_installment) : 1;
            for ($i = 0; $i < $no; $i++) {
                $instModel = new TblMccBillHeadInstallment();
                $instModel->mcc_bill_head_detail_code = $model->mcc_bill_head_detail_code;
                $instModel->mcc_bill_head_code = $model->mcc_bill_head_code;
                $instModel->union_code = $model->union_code;
                $instModel->bmc_code = $model->bmc_code;
                $instModel->installement_cycle = ($i + 1);
                $model->amount = empty($model->amount) ? 0 : $model->amount;
                $instModel->installment_amount = floatval($model->amount / $no);
                if (!$instModel->validate()) {
                    $errors[] = $instModel->getErrors();
                }
                if (empty($instModel->getErrors()) && $instModel->validate()) {
                    array_push($saveModel, $instModel);
                }
            }
        }
    }

}
