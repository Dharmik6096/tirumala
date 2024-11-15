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

    public $wef_date, $member_code, $customer_name, $ex_code;

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
            [['customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'final_amount', 'milk_amount', 'manual_amount', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'wef_date', 'customer_name', 'ex_code'], 'safe'],
            [['customer_type', 'customer_code'], 'string', 'max' => 20],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv', 'importCsvOther']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv', 'importCsvOther']],
            [['wef_date'], 'convertDate'],
            [['customer_type', 'customer_code'], 'required', 'on' => ['importCsv']],
            [['final_amount'], 'required'],
            [['customer_name', 'ex_code', 'wef_date'], 'required', 'except' => ['update', 'importCsv', 'importCsvOther']],
            [['dcs_code'], 'required', 'except' => ['importCsv', 'importCsvOther'], 'when' => function () {
                    return ($this->customer_type == 'MEMBER');
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblmonthlycreditlimit-customer_type').val() == 'MEMBER'; 
            }"],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'except' => ['importCsv', 'importCsvOther']],
            [['customer_type'], 'default', 'value' => 'member', 'on' => ['importCsvOther']],
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
            [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'filter' => ['is_product_sale' => 1], 'on' => ['importCsv']],
            [['final_amount'], function ($attribute, $params) {
                    $this->milk_amount = $this->manual_amount = $this->$attribute;
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
            'mcc_plant_code' => Yii::t('app', 'MCC Plant'),
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

    public function importFieldSet() {
        if (empty($this->getErrors())) {
            if (!empty($this->customer_type) && strtolower($this->customer_type) == 'member') {
                $dcsData = $this->validDcs();
                if (empty($dcsData)) {
                    $this->addError('dcs_code', Yii::t('app/validation', $this->getAttributeLabel('dcs_code') . ' is Invalid.'));
                } else {
                    $memberModel = new TblMember();
                    $memberCode = str_pad($this->member_code, 4, '0', STR_PAD_LEFT);
                    $memberData = $memberModel->validateMember($dcsData->dcs_code, $memberCode);
                    if (empty($memberData)) {
                        $this->addError('member_code', Yii::t('app/validation', $this->getAttributeLabel('member_code') . ' is Invalid.'));
                    } else {
                        $this->customer_code = $memberData->member_code;
                        $this->customer_type = 'MEMBER';
                        $this->setHierarchy($dcsData);
                    }
                }
            } else if (!empty($this->customer_type) && strtolower($this->customer_type) != 'dcs') {
                $customerData = $this->validateCustomer($this->union_code, $this->customer_code, $this->customer_type, $this->bmc_code);
                if (empty($customerData)) {
                    $this->addError('customer_code', Yii::t('app/validation', $this->getAttributeLabel('customer_code') . ' is Invalid.'));
                } else {
                    $this->customer_type = $customerData->customer_type;
                    $this->customer_code = $customerData->customer_code;
                    $this->mcc_plant_code = $customerData->mcc_plant_code;
                    $this->plant_code = $customerData->plant_code;
                }
            } else {
                $this->dcs_code = $this->customer_code;
                $data = $this->validDcs();
                if (empty($data)) {
                    $this->addError('dcs_code', Yii::t('app/validation', $this->getAttributeLabel('dcs_code') . ' is Invalid.'));
                } else {
                    $this->setHierarchy($data);
                    $this->customer_type = 'DCS';
                    $this->customer_code = $data->dcs_code;
                }
            }
        }
    }

    public function setHierarchy($data) {
        if (!empty($data)) {
            $this->union_code = $data->union_code;
            $this->plant_code = $data->plant_code;
            $this->mcc_plant_code = $data->mcc_plant_code;
            $this->bmc_code = $data->bmc_code;
            $this->dcs_code = $data->dcs_code;
        }
    }

    public function validateCustomer($union, $code, $type, $bmc) {
        if (!empty($code) && strtolower($type) != 'dcs') {
            $this->union_code = $union;
            $this->bmc_code = $bmc;
            $customer_type = $this->customerType;
            $prefix = $customer_type->code_prefix;
            $length = $customer_type->code_length;
            $Code = '';
            if (!empty($prefix) && is_numeric($this->customer_code)) {
                $customerModel = new TblCustomerMaster();
                $customerModel->customer_type = $this->customer_type;

                $customerModelData = $customerModel->find()
                        ->select(['customer_code', 'customer_type', 'plant_code', 'mcc_plant_code'])
                        ->where(['customer_type' => $type, 'bmc_code' => $bmc])
                        ->andWhere(['or', ['CAST(REPLACE(customer_code_ex, \'' . $prefix . '\', \'\') as int)' => (int) $code], ['ref_code' => $code], ['customer_code' => $code]])
                        ->all();
                if (count($customerModelData) == 1) {
                    $Code = $customerModelData[0];
                }
            } else {
                $this->ex_code = !empty($length) ? $prefix . str_pad($code, $length, '0', STR_PAD_LEFT) : '';
                $Code = $this->customerCode;
            }

            return $data = empty($Code) ? '' : $Code;
        }
    }

    public function validDcs() {
        $query = TblDcs::find()
                ->select(['dcs_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'])
                ->where(['or', ['dcs_code' => $this->dcs_code], ['dcs_code_ex' => $this->dcs_code], ['ref_code' => $this->dcs_code]])
                ->andWhere(['is_active' => 1]);
        if (!empty($this->bmc_code)) {
            $query->andWhere(['bmc_code' => $this->bmc_code]);
        }
        $data = $query->all();
        return count($data) === 1 ? $data[0] : '';
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
        return $this->find()->where('CAST(from_date AS DATE) <= :toDate AND CAST(to_date AS DATE) >= :fromDate', [':fromDate' => $fromDate, ':toDate' => $toDate])
                        ->andWhere(['customer_code' => $this->customer_code, 'customer_type' => $this->customer_type, 'union_code' => $this->union_code])
                        ->one();
    }

}
