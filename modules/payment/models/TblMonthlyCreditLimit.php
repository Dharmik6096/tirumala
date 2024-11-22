<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_monthly_credit_limit".
 *
 * @property integer $monthly_credit_limit_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $to_date
 * @property decimal $final_amount
 * @property decimal $milk_amount
 * @property decimal $manual_amount
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
class TblMonthlyCreditLimit extends \app\models\ChildModel {

    public $wef_date, $member_code, $customer_name, $ex_code, $avl_amount;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_monthly_credit_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'final_amount', 'milk_amount', 'manual_amount', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'wef_date', 'customer_name', 'ex_code', 'avl_amount', 'member_code'], 'safe'],
            [['customer_type', 'customer_code'], 'string', 'max' => 20],
            [['final_amount'], 'required'],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv', 'importCsvOther']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv', 'importCsvOther']],
            [['wef_date'], 'convertDate'],
            [['milk_amount'], 'default', 'value' => 0],
            [['customer_type'], 'default', 'value' => 'MEMBER', 'on' => ['importCsvOther']],
            [['customer_type', 'customer_code'], 'required', 'except' => ['importCsvOther']],
            [['wef_date'], 'required', 'except' => ['update']],
            [['customer_name', 'ex_code'], 'required', 'except' => ['update', 'importCsv', 'importCsvOther']],
            [['member_code'], 'required', 'on' => ['importCsvOther']],
            [['dcs_code'], 'required', 'except' => ['importCsv'], 'when' => function ($model) {
                    return ($model->customer_type == 'MEMBER');
                }],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'except' => ['importCsv', 'importCsvOther']],
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
            [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'filter' => ['is_product_sale' => 1], 'on' => ['importCsv']],
            [['final_amount'], function ($attribute, $params) {
                    $this->manual_amount = $this->$attribute;
                }],
            [['final_amount'], 'importFieldSet', 'on' => ['importCsv', 'importCsvOther']],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'monthly_credit_limit_code' => Yii::t('app', 'Monthly Credit Limit Code'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'milk_amount' => Yii::t('app', 'Milk Amount'),
            'manual_amount' => Yii::t('app', 'Manual Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Extra Column 1'),
            'x_col2' => Yii::t('app', 'Extra Column 2'),
            'x_col3' => Yii::t('app', 'Extra Column 3'),
            'x_col4' => Yii::t('app', 'Extra Column 4'),
            'x_col5' => Yii::t('app', 'Extra Column 5'),
            'customer_name' => Yii::t('app', 'Name'),
        ];
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMainDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function importFieldSet() {
        if (empty($this->getErrors())) {
            if (!empty($this->member_code)) {
                $dcs = new TblDcs();
                $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
                if (empty($this->dcs_code)) {
                    $this->addError('dcs_code', Yii::t('app/validation', $this->getAttributeLabel('dcs_code') . ' is Invalid.'));
                } else {
                    $memberModel = new TblMember();
                    $memberCode = str_pad($this->member_code, 4, '0', STR_PAD_LEFT);
                    $data = $memberModel->validateMember($this->dcs_code, $memberCode);
                    $this->customer_type = 'MEMBER';


                    if (empty($data)) {
                        $this->addError('member_code', Yii::t('app/validation', 'Member Code Is Invalid.'));
                    } else {
                        $this->customer_code = $data->member_code;
                        $bmcData = $this->mainDcsCode;
                        $this->setHierarchy($bmcData);
                        $detail = Yii::$app->general->validateDeactivateDcs($this, $this->wef_date, '', TRUE, $this->customer_code);
                        if ($detail === false) {
                            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' Or Member is Deactivated.'));
                        }
                    }
                }
            } else {
                Yii::$app->general->validateCustomer($this);
                if (!empty($this->customer_type) && strtolower($this->customer_type) == 'dcs') {
                    $this->dcs_code = $this->customer_code;
                    $detail = Yii::$app->general->validateDeactivateDcs($this, $this->wef_date);
                    if ($detail === false) {
                        $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is Deactivated.'));
                    }
                }
            }
            $bmcData = $this->bmcCode;
            $this->setHierarchy($bmcData);
        }
    }

    public function setHierarchy($data) {
        if (!empty($data)) {
            $this->union_code = $data->union_code;
            $this->plant_code = $data->plant_code;
            $this->mcc_plant_code = $data->mcc_plant_code;
            $this->bmc_code = $data->bmc_code;
        }
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
            if ($this->wef_date) {
                list($this->from_date, $this->to_date) = Yii::$app->general->getMonthStartEndDate($this->wef_date);
            }
        }
    }

    public function getMonthlyCreditLimit($date) {
        list($fromDate, $toDate) = Yii::$app->general->getMonthStartEndDate($date, 'previous');
        return $this->find()->Where(['from_date' => $fromDate, 'to_date' => $toDate, 'customer_code' => $this->customer_code, 'customer_type' => $this->customer_type, 'union_code' => $this->union_code])->one();
    }

}
