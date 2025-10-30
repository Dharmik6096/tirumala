<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_aadesh_master_applicability".
 *
 * @property integer $aadesh_master_applicability_code
 * @property string $wef_date
 * @property string $aadesh_master_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $product_code
 * @property double $sale_rate
 * @property string $mcc_plant_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblAadeshMasterApplicability extends \app\models\ChildModel {

    public $is_sentbox = TRUE;
    public $import_union_code, $import_eipl_code, $import_key_pattern, $ex_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_aadesh_master_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_code', 'wef_date'], 'required', 'except' => ['androidsync']],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['aadesh_master_code', 'dcs_code', 'union_code', 'created_by', 'updated_by', 'mcc_plant_code', 'applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type', 'product_mrp', 'distributor_landing_rate', 'sachiv_price', 'member_price'], 'safe'],
            [['product_code', 'originating_type', 'is_member_rate', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['sale_rate', 'aadesh_master_applicability_code', 'commission', 'ex_code'], 'safe'],
            [['aadesh_master_code'], 'validateAadeshMaster', 'skipOnEmpty' => false, 'except' => ['androidsync']],
            [['applicable_for', 'applicable_code', 'bmc_code', 'wef_date', 'aadesh_master_code'], 'required', 'on' => ['importCsv']],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
            [['applicable_for'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
            [['applicable_for'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['applicable_for' => 'customer_type'], 'on' => ['importCsv']],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['applicable_for'], 'setImport', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'aadesh_master_applicability_code' => Yii::t('app', 'Aadesh Master Applicability Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'aadesh_master_code' => Yii::t('app', 'Product Rate Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'product_code' => Yii::t('app', 'Product Code'),
            'sale_rate' => Yii::t('app', 'Rate'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'name' => Yii::t('app', 'Applicable Name'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getAadeshMasterCode() {
        return $this->hasOne(TblAadeshMaster::className(), ['aadesh_master_code' => 'aadesh_master_code']);
    }

    public function getProductRate() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getDispDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_type', 'union_code' => 'union_code']);
    }

    public function getCustomerTypeFor() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getName($applicableFor) {
        if ($applicableFor == 'PLANT') {
            return Yii::$app->general->getforeignkey($this->plantCode, 'name');
        } else if ($applicableFor == 'MCC') {
            return Yii::$app->general->getforeignkey($this->mccPlantCode, 'name');
        } else if ($applicableFor == 'BMC') {
            return Yii::$app->general->getforeignkey($this->bmcCode, 'bmc_name');
        } else if ($applicableFor == 'DCS') {
            return Yii::$app->general->getforeignkey($this->dispDcsCode, 'dcs_name');
        } else {
            return Yii::$app->general->getforeignkey($this->mainCustomerCode, 'customer_name');
        }
    }

    public function allowDelete() {
        return true;
    }

    public function validateAadeshMaster($attribute, $params) {
        $this->aadesh_master_applicability_code = Yii::$app->general->getTransactionCode($this, $this->aadesh_master_code, 1, 6);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getDcsName() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getCustomerMasterCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
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
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'union_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'plant_code');

            $this->wef_date = !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : '';
            if (empty($this->aadeshMasterCode)) {
                $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'aadesh_master_code') . '  is invalid.'));
            } else {
                $this->product_code = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'product_code');
                $this->sale_rate = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'sale_rate');
                $this->commission = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'commission');
                $this->is_member_rate = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'is_member_rate');
                $this->product_mrp = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'product_mrp');
                $this->distributor_landing_rate = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'distributor_landing_rate');
                $this->sachiv_price = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'sachiv_price');
                $this->member_price = Yii::$app->general->getforeignkey($this->aadeshMasterCode, 'member_price');
                if ($this->is_member_rate == 1) {
                    if (strtoupper($this->applicable_for) != 'DCS') {
                        $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'applicable_for') . '  is invalid.'));
                    }
                }
                $this->validateCustomer($this);
            }
        }
    }

    public function validateCustomer($model) {
        if (empty($model->applicable_for) || strtoupper($model->applicable_for) == 'DCS') {
            $model->applicable_for = 'DCS';
            $dcs = new TblDcs();
            $applicable_code = $dcs->validDcs($model->applicable_code, $model->bmc_code);
        } else {
            $model->applicable_for = strtoupper($model->applicable_for);
            $applicable_code = $this->validateCustomerCode($model);
        }

        if (empty($applicable_code)) {
            $model->addError('applicable_code', Yii::t('app/validation', Yii::t('app', 'Applicable Code') . ' is invalid'));
        } else {
            $model->applicable_code = $applicable_code;
        }
    }

    public function validateCustomerCode($model) {
        if (strtolower($model->applicable_for) != 'dcs') {
            $prefix = Yii::$app->general->getforeignkey($model->applicCustomerType, 'code_prefix');
            $length = Yii::$app->general->getforeignkey($model->applicCustomerType, 'code_length');
            $Code = '';
            if (!empty($prefix) && is_numeric($model->applicable_code)) {
                $customerModel = new TblCustomerMaster();
                $customerModel->customer_type = $model->applicable_for;
                $customerModelData = $customerModel->find()
                        ->where(['customer_type' => $model->applicable_for])
                        ->andWhere([
                            'OR',
                            ['CAST(REPLACE(customer_code_ex,\'' . $prefix . '\', \'\') as int)' => (int) $model->applicable_code],
                            ['customer_code' => $model->applicable_code],
                            ['ref_code' => $model->applicable_code]
                        ])
                        ->all();
                if (count($customerModelData) == 1) {
                    $Code = $customerModelData[0]->customer_code;
                    $model->ex_code = $customerModelData[0]->customer_code_ex;
                }
            } else {
                $model->ex_code = $prefix . str_pad($model->applicable_code, $length, '0', STR_PAD_LEFT);
                $Code = Yii::$app->general->getforeignkey($model->customerCode, 'customer_code');
            }
            return $data = empty($Code) ? '' : $Code;
        }
    }

    public function getDcsRefCode() {
        return $this->hasOne(TblDcs::className(), ['ref_code' => 'applicable_code', 'bmc_code' => 'bmc_code']);
    }

    public function getApplicCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code'])->andOnCondition(['is_applicability' => 1, 'is_active' => 1]);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'applicable_for'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->ex_code]);
    }

    public function getMainBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

}
