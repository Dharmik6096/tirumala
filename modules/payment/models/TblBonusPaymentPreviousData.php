<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHead;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\dcsoperation\models\TblMember;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_bonus_payment_previous_data".
 *
 * @property integer $bill_head_txn_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $bill_head_code
 * @property string $bill_head_for
 * @property string $transaction_date
 * @property string $amount
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBonusPaymentPreviousData extends ChildModel {

    /**
     * @inheritdoc
     */
    public $customer_name, $ex_code, $member_code;

    public static function tableName() {
        return 'tbl_bonus_payment_previous_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'bill_head_code', 'originating_type', 'customer_type', 'bill_head_for', 'transaction_date', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type', 'amount', 'remarks'], 'safe'],
            [['amount'], 'number', 'min' => 0],
            [['union_code'], 'string', 'max' => 3],
            [['bmc_code', 'bill_head_code'], 'string', 'max' => 10],
            [['dcs_code'], 'string', 'max' => 12],
            [['dcs_code', 'member_code', 'transaction_date'], 'required', 'on' => ['importCsv']],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
            [['bill_head_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBillHead::className(), 'targetAttribute' => ['bill_head_code' => 'bill_head_code'], 'on' => ['importCsv']],
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
            [['transaction_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['transaction_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['transaction_date'], 'convertDate', 'on' => ['importCsv']],
            [['bmc_code'], 'importData', 'skipOnError' => true, 'on' => ['importCsv']],
            [['customer_code'], 'required', 'on' => ['importCsv']],
            [['customer_code'], 'checkUnique', 'on' => 'importCsv'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'previous_data_code' => Yii::t('app', 'Previous Data Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'amount' => Yii::t('app', 'Amount'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'remarks' => Yii::t('app', 'Remarks'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHead::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
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

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->ex_code]);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

    public function importData($attribute, $params) {
        $bmcDetail = $this->bmcCode;
        if (!empty($bmcDetail)) {
            if (empty($this->getErrors())) {
                $this->union_code = $this->union_code;
                $this->plant_code = $this->plant_code;
                $this->plant_code = $this->mcc_plant_code;
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
                    $flag = ['data_lock_member', 'billing_lock_member'];
                } else {
                    $this->bill_head_for = 'VENDOR';
                    Yii::$app->general->validateCustomer($this);
                    $customer_type = $this->customer_type;
                    $customer_code = $this->customer_code;
                    $flag = ['data_lock_bmc', 'billing_lock_bmc'];
                }
            }
        }
    }

    public function checkUnique($attribute) {
        $data = 0;
        $data = $this->find()->where(['customer_code' => $this->customer_code, 'transaction_date' => $this->transaction_date, 'bill_head_code' => $this->bill_head_code])
                ->count();

        if ($data > 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Member Code, Transaction Date, and Bill Head Code must be unique.'));
        }
    }

}
