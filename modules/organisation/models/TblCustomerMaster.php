<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblBranch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblBankDetails;
use app\modules\general\models\TblDepartment;
use app\modules\organisation\models\TblDcsVendorStatus;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_customer_master".
 *
 * @property string $customer_code
 * @property string $customer_name
 * @property string $address
 * @property integer $is_active
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $local_name
 * @property string $local_address
 * @property string $gst_no
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $customer_type
 * @property string $sap_code
 * @property string $refference_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCustomerMaster extends \app\models\ChildModel {

    public $same_milk_type, $diff_milk_type;
    public $contact_person, $local_contact_person, $middle_name, $local_middlename, $surname, $local_surname, $email, $department, $ifsc, $bank_account_no, $route, $beneficiary_name, $prefix, $file_name;
    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['union_code', 'plant_code', 'mcc_plant_code', 'route_code'], 'required', 'except' => ['importCsv', 'deleteRouteMapping']],
                [['customer_name', 'address', 'customer_type', 'bmc_code'], 'required', 'except' => ['deleteRouteMapping']],
                [['customer_name', 'address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'local_name', 'local_address', 'gst_no', 'union_code', 'created_by', 'updated_by', 'route', 'beneficiary_name', 'aadhaar_no', 'file_name', 'ts_code_m', 'ts_code_e', 'customer_category', 'animal_type_code', 'distance_from_mcc', 'pan_no'], 'safe'],
                [['route'], 'required', 'on' => ['importCsv']],
                [['is_active', 'animal_type_code'], 'integer'],
                [['created_at', 'updated_at', 'customer_type', 'sap_code', 'refference_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type', 'route_code', 'same_milk_type', 'diff_milk_type', 'prefix'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
                [['gst_no'], 'string', 'max' => 15, 'min' => 15, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 15 digit '),
                'tooShort' => Yii::t('app/validation', '{attribute} must contain 15 digit '), 'skipOnEmpty' => TRUE],
                [['local_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['customer_code_ex'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
            //['customer_code_ex', 'unique', 'targetAttribute' => ['customer_code_ex', 'union_code', 'customer_type'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'skipOnError' => TRUE],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code'], 'on' => ['importCsv']],
                [['customer_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['is_organisation' => 0, 'union_code' => (!empty(Yii::$app->session->get('Unions')) && count(explode(',', Yii::$app->session->get('Unions'))) == 1) ? Yii::$app->session->get('Unions') : NULL]);
                }, 'on' => ['importCsv']],
                [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'on' => ['importCsv']],
                [['bmc_code'], 'setImport', 'skipOnError' => true, 'on' => ['importCsv']],
                [['route_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRouteMapping::className(), 'targetAttribute' => ['route_code' => 'route_code'], 'on' => ['importCsv']],
                [['x_col1'], 'default', 'value' => '1#1'],
                [['contact_person', 'local_contact_person', 'middle_name', 'local_middlename', 'surname', 'local_surname', 'email', 'mobile_no', 'department', 'ifsc', 'bank_account_no', 'ref_code', 'customer_code_ex', 'sap_vendor_code', 'x_col2'], 'safe'],
                [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['local_contact_person', 'local_middlename', 'local_surname'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['importCsv']],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'on' => ['importCsv']],
                [['ifsc'], 'trim', 'on' => ['importCsv']],
                [['email'], 'email', 'on' => ['importCsv']],
                [['mobile_no', 'contact_person'], 'required', 'on' => ['importCsv']],
                [['customer_code'], function ($attribute, $params) {
                    Yii::$app->general->vaildateKeyCodes($this, 'tbl_customer_master', 'customer_code_ex', 'customer_code', FALSE);
                }, 'skipOnEmpty' => false, 'on' => ['updateFront', 'importCsv']],
                [['same_milk_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['diff_milk_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => ['importCsv']],
                [['department'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'department');
                }, 'on' => ['importCsv']],
                [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id'], 'on' => ['importCsv']],
                [['bmc_code'], 'setXcol', 'on' => ['importCsv']],
                [['bmc_code'], 'setExCode'],
                [['data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime'], 'safe'],
                [['customer_code'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
                [['customer_code'], function ($attribute, $params) {
                    $update = FALSE;
                    if ($this->scenario == 'updateFront') {
                        $update = TRUE;
                    }
                    Yii::$app->general->validateExCodes($this, 'tbl_customer_master', 'customer_code_ex', 'tbl_dcs', 'dcs_code_ex', 'TblDcs', $this->union_code, $update);
                }, 'skipOnEmpty' => false, 'on' => ['updateFront', 'importCsv', 'createFront']],
                [['aadhaar_no'], 'unique', 'skipOnError' => TRUE, 'except' => ['deleteRouteMapping']],
                [['ts_code_m', 'ts_code_e'], 'string', 'max' => 10],
                [['ts_code_m', 'ts_code_e'], 'number'],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblCustomerMaster', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_code_ex' => Yii::t('app', 'Customer Code Ex.'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'address' => Yii::t('app', 'Address'),
            'is_active' => Yii::t('app', 'Is Active'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'sap_code' => Yii::t('app', 'Sap Code'),
            'refference_code' => Yii::t('app', 'Refference Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'Collection'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'ref_code' => Yii::t('app', 'Code'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No.'),
            'customer_category' => Yii::t('app', 'Customer Category'),
            'animal_type_code' => Yii::t('app', 'Milk Type'),
            'distance_from_mcc' => Yii::t('app', 'Distance From MCC'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_customer_master', 'customer_code_ex', 4, '', FALSE);
        // $data = $this->find()->select(["MAX(CONVERT(INT,RIGHT(customer_code,4))) AS customer_code"])->where(['union_code' => $this->union_code])->one();
        // return $this->union_code . str_pad((int) $data['customer_code'] + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getCodeEx() {
        $code_prefix = $this->customerType->code_prefix;
        $code_length = $this->customerType->code_length;
        $data = $this->find()->select(["MAX(CONVERT(INT,RIGHT(customer_code_ex,$code_length))) AS customer_code_ex"])->where(['customer_type' => $this->customer_type, 'union_code' => $this->union_code])->one();
        return $code_prefix . str_pad((int) $data['customer_code_ex'] + 1, $code_length, '0', STR_PAD_LEFT);
    }

    public function getCustomerWithType($unionCode, $customer_type = '', $notIn = [], $concatCode = false, $compareBmc = false, $mcc = [], $bmc = [], $routes = []) {
        $query = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1]);
        if (!empty($customer_type)) {
            $query->andWhere(['customer_type' => $customer_type]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['not in', 'customer_code', $notIn]);
        }
        if ($compareBmc) {
            $query->andWhere(['bmc_code' => $bmc, 'mcc_plant_code' => $mcc]);
        }

        if (!empty($routes)) {
            $query->andWhere(['route_code' => $routes]);
        }
        $data = $query->all();
        if ($concatCode) {
            $data = ArrayHelper::map($data, 'customer_code', function($data) {
                        return $data->ref_code . ' - ' . $data->customer_name;
                    });
        } else {
            $data = ArrayHelper::map($data, 'customer_code', 'customer_name');
        }
        asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getUnionCustomerList($unionCode) {
        $value = $this->getUnionCustomer($unionCode);
        $value = ArrayHelper::map($value, 'customer_code', 'customer_name');
        return $value;
    }

    public function getUnionCustomer($unionCode = []) {
        $query = $this->find()->select(['customer_code', 'customer_name'])->where(['is_active' => 1]);
        $query->andWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        $query->andFilterWhere(['customer_type' => $this->customer_type]);
        $query->andFilterWhere(['union_code' => $unionCode]);

        return $query->all();
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

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
        if (!empty($this->set_master_hierarchy) && $flag == 'INSERT') {
            foreach ($this->set_master_hierarchy as $hierarchy) {
                $hierarchy->save();
            }
        }
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function customerType($bmc_code) {
        $data = $this->find()->select(['customer_type'])
                ->distinct()
                ->where(['bmc_code' => $bmc_code]);
        $data = $data->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'customer_type', function($data) {
                    return Yii::$app->general->getforeignkey($data->customerType, 'customer_desc');
                });
        $array['DCS'] = Yii::t('app', 'DCS');
        return $array;
    }

    public function getCustomerList($bmc, $route = '') {
        $query = $this->find()->where(['is_active' => 1]);
        if (!empty($bmc)) {
            $query->andWhere(['bmc_code' => $bmc]);
        }
        if (!empty($route)) {
            $query->andWhere(['route_code' => $route]);
        }
        $data = $query->all();
        $data = ArrayHelper::map($data, 'customer_code', function($data) {
                    return !empty($data->customerType) ? $data->customer_name . '(' . Yii::t('app', $data->customerType->customer_desc) . ') - ' . $data->ref_code : $data->customer_name . ' - ' . $data->ref_code;
                });
        asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getDefaultMcc() {
        return $this->hasOne(TblMccPlant::className(), ['plant_code' => 'plant_code'])->where(['is_plant' => 1]);
    }

    public function setImport($attribute, $params) {
//        $this->customer_code = $this->getCode();
        $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
        $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
//        $this->bmc_code = Yii::$app->general->getforeignkey($this->bmcCode, 'bmc_code');
//        $this->union_code = Yii::$app->general->getforeignkey($this->routeCode, 'union_code');
//        $toType = Yii::$app->general->getforeignkey($this->routeCode, 'to_type');
//        if (strtolower($toType) == 'bmc') {
//            $this->bmc_code = Yii::$app->general->getforeignkey($this->routeCode, 'to_dest');
//            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
//            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
//        } elseif (strtolower($toType) == 'mcc') {
//            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->routeCode, 'to_dest');
//            $this->plant_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'plant_code');
//            $this->bmc_code = Yii::$app->general->getmultiforeignkey($this->mccPlantCode, ['bmcCode'], 'bmc_code');
//        } elseif (strtolower($toType) == 'plant') {
//            $this->plant_code = Yii::$app->general->getforeignkey($this->routeCode, 'to_dest');
//            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->defaultMcc, 'mcc_plant_code');
//            $this->bmc_code = Yii::$app->general->getmultiforeignkey($this->mccPlantCode, ['bmcCode'], 'bmc_code');
//        }
//
//        if (empty($this->bmc_code) || $this->bmc_code == 'N/A') {
//            $this->addError('bmc_code', Yii::t('app/validation', $this->getAttributeLabel('bmc_code') . ' not available'));
//        } else if (empty($this->mcc_plant_code) || $this->mcc_plant_code == 'N/A') {
//            $this->addError('mcc_plant_code', Yii::t('app/validation', $this->getAttributeLabel('mcc_plant_code') . ' not available'));
//        } elseif (empty($this->plant_code) || $this->plant_code == 'N/A') {
//            $this->addError('plant_code', Yii::t('app/validation', $this->getAttributeLabel('plant_code') . ' not available'));
//        }

        $this->village_code = Yii::$app->general->getforeignkey($this->hamletCode, 'village_code');
        $this->sub_district_code = Yii::$app->general->getmultiforeignkey($this->hamletCode, ['villageCode'], 'sub_district_code');
        $this->district_code = Yii::$app->general->getmultiforeignkey($this->hamletCode, ['villageCode', 'subDistrictCode'], 'district_code');
        $this->state_code = Yii::$app->general->getmultiforeignkey($this->hamletCode, ['villageCode', 'subDistrictCode', 'districtCode'], 'state_code');

        $this->route_code = $this->route;
        $this->route_code = Yii::$app->general->getforeignkey($this->routeRefCode, 'route_code');
        $prefix = Yii::$app->general->getforeignkey($this->customerTypePre, 'code_prefix');
        if (empty($this->getErrors())) {
            $mainExCode = $this->customer_code_ex;
            if (strstr($this->customer_code_ex, $prefix)) {
                if (is_numeric(substr($this->customer_code_ex, 0, 1))) {
                    $this->addError('customer_code_ex', Yii::t('app/validation', $this->getAttributeLabel('customer_code_ex') . ' is invalid'));
                }
                $this->customer_code_ex = str_replace($prefix, '', $this->customer_code_ex);
                if (!is_numeric($this->customer_code_ex)) {
                    $this->addError('customer_code_ex', Yii::t('app/validation', $this->getAttributeLabel('customer_code_ex') . ' is invalid'));
                }
            } elseif (!is_numeric($this->customer_code_ex)) {
                $this->addError('customer_code_ex', Yii::t('app/validation', $this->getAttributeLabel('customer_code_ex') . ' is invalid'));
            }
            $this->customer_code_ex = $mainExCode;
        }
    }

    public function getCustomerCodeList($bmc, $type, $union_code = '') {
        $query = $this->find()->where(['is_active' => 1]);
        $query->andFilterWhere(['bmc_code' => $bmc]);
        $query->andFilterWhere(['customer_type' => $type]);
        $query->andFilterWhere(['union_code' => $union_code]);
        $data = $query->all();
        $data = ArrayHelper::map($data, 'customer_code', function($value) {
                    return $value->customer_name . ' - ' . $value->ref_code;
                });
        asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getActivateCustomerCodeList($union_code, $bmc, $type, $dateFilter) {
        $deactivateList = new TblCustomerDeactive();
        $deactivatedCustomer = $deactivateList->getDeactiveCustomer($type, $dateFilter);

        $value = $this->find()
                ->where(['is_active' => 1])
                ->andFilterWhere(['bmc_code' => $bmc, 'customer_type' => $type, 'union_code' => $union_code])
                ->andWhere(['not in', 'customer_code', $deactivatedCustomer])
                ->all();

        return ArrayHelper::map($value, 'customer_code', function($value) {
                    return $value->customer_name . ' - ' . $value->ref_code;
                });
    }

    public function getBMCCustomerList($bmc, $customer_type = NULL) {
        $query = $this->find()->where(['is_active' => 1]);
        if (!empty($bmc)) {
            $query->andWhere(['bmc_code' => $bmc]);
        }
        $query->andFilterWhere(['customer_type' => $customer_type]);
        $value = $query->all();
//        $value = ArrayHelper::map($data, 'customer_code', 'customer_type');
        return $value;
    }

    public function getDefaultBankDetail() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'customer_code'])->where(['tbl_bank_details.module_name' => 'customer', 'tbl_bank_details.is_default' => 1]);
    }

    public function getDefaultContactDetail() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'customer_code'])->where(['tbl_contact_details.module_name' => 'customer', 'tbl_contact_details.is_default' => 1]);
    }

    public function getIfscDetail() {
        return $this->hasOne(TblBranch::className(), ['ifsc' => 'ifsc'])->andwhere(['is_active' => 1]);
    }

    public function setChildTable(&$model, &$modelList, &$errors) {
        $existData = $model::find()->where(['union_code' => $model->union_code, 'ref_code' => $model->ref_code])->one();
        $defaultBankDetail = '';
        $defaultContactDetail = '';
        if (!empty($existData)) {
            $defaultBankDetail = $existData->defaultBankDetail;
            $defaultContactDetail = $existData->defaultContactDetail;
        }

        if (empty($defaultBankDetail) || $defaultBankDetail->bank_account_no != $model->bank_account_no) {
            if (!empty($defaultBankDetail)) {
                $defaultBankDetail->is_default = 0;
                $defaultBankDetail->is_active = 0;
                array_push($modelList, $defaultBankDetail);
            }
            $model->setbankDetails($model, $modelList, $errors);
        } elseif (!empty($defaultBankDetail)) {
            $defaultBankDetail->ifsc = $model->ifsc;
            $defaultBankDetail->beneficiary_name = $model->beneficiary_name;
            $defaultBankDetail->branch_code = Yii::$app->general->getforeignkey($model->ifscDetail, 'branch_code');
            $defaultBankDetail->bank_code = Yii::$app->general->getforeignkey($model->ifscDetail, 'bank_code');
            if (empty($defaultBankDetail->branch_code)) {
                $this->addError('ifsc', Yii::t('app/validation', $this->getAttributeLabel('ifsc') . ' is Invalid.'));
                return false;
            }
            if (!$defaultBankDetail->validate()) {
                $errors[] = $defaultBankDetail->getErrors();
            }
            array_push($modelList, $defaultBankDetail);
        }

        if (empty($defaultContactDetail) || $defaultContactDetail->mobile_no != $model->mobile_no) {
            if (!empty($defaultContactDetail)) {
                $defaultContactDetail->is_default = 0;
                $defaultContactDetail->is_active = 0;
                array_push($modelList, $defaultContactDetail);
            }
            $model->setContactDetails($model, $modelList, $errors);
        } elseif (!empty($defaultContactDetail)) {
            $defaultContactDetail->department = $model->department;
            $defaultContactDetail->contact_person = $model->contact_person;
            $defaultContactDetail->firstname = $model->contact_person;
            $defaultContactDetail->local_contact_person = $model->local_contact_person;
            $defaultContactDetail->lastname = $model->middle_name;
            $defaultContactDetail->surname = $model->surname;
            $defaultContactDetail->local_lastname = $model->local_middlename;
            $defaultContactDetail->local_surname = $model->local_surname;
            array_push($modelList, $defaultContactDetail);
        }
    }

    public function setbankDetails($model, &$saveModel, &$errors) {
        if (!empty($model->bank_account_no)) {
            $branch_model = new TblBankDetails();
            $branch_model->setModel('customer', $model->customer_code);
            $branch_model->ifsc = $model->ifsc;
            $branch_model->beneficiary_name = $model->beneficiary_name;
            $branch_model->bank_account_no = $model->bank_account_no;
            $branch_model->branch_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'branch_code');
            $branch_model->bank_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'bank_code');
            if (empty($branch_model->branch_code)) {
                $this->addError('ifsc', Yii::t('app/validation', $this->getAttributeLabel('ifsc') . ' is Invalid.'));
                return false;
            }
            if (!$branch_model->validate()) {
                $errors[] = $branch_model->getErrors();
            }
            array_push($saveModel, $branch_model);
        }
    }

    public function setContactDetails($model, &$saveModel, &$errors) {
        if (!empty($model->mobile_no)) {
            $contact_model = new TblContactDetails();
            $contact_model->setModel('customer', $model->customer_code);
            $contact_model->department = $model->department;
            $contact_model->contact_person = $model->contact_person;
            $contact_model->firstname = $model->contact_person;
            $contact_model->local_contact_person = $model->local_contact_person;
            $contact_model->lastname = $model->middle_name;
            $contact_model->surname = $model->surname;
            $contact_model->local_lastname = $model->local_middlename;
            $contact_model->local_surname = $model->local_surname;
            $contact_model->mobile_no = $model->mobile_no;
            $contact_model->email = $model->email;
            if (!$contact_model->validate()) {
                $errors[] = $contact_model->getErrors();
            }
            array_push($saveModel, $contact_model);
        }
    }

    public function setXcol($attribute, $params) {
        $same = empty($this->same_milk_type) ? 0 : $this->same_milk_type;
        $different = empty($this->diff_milk_type) ? 0 : $this->diff_milk_type;
        $this->x_col1 = $same . '#' . $different;
    }

    public function setExCode($attribute, $params) {
        $this->customer_code_ex = strtoupper($this->customer_code_ex);
    }

    public function getRouteRefCode() {
        return $this->hasOne(TblRouteMapping::className(), ['ref_code' => 'route_code']);
    }

    public function getActiveStatus() {
        return $this->hasOne(TblDcsVendorStatus::className(), ['customer_code' => 'customer_code', 'customer_type' => 'customer_type']);
    }

    public function getCustomerTypePre() {
        return $this->hasOne(TblCustomerType::className(), ['union_code' => 'union_code', 'customer_type' => 'customer_type'])->andOnCondition(['tbl_customer_type.is_active' => 1]);
    }

    public function getMainBankDetails() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'customer_code'])->where(['tbl_bank_details.module_name' => 'customer', 'tbl_bank_details.is_default' => 1, 'tbl_bank_details.is_active' => 1]);
    }

    public function getMainContactDetails() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'customer_code'])->andOnCondition(['tbl_contact_details.module_name' => 'customer', 'tbl_contact_details.is_default' => 1, 'tbl_contact_details.is_active' => 1]);
    }

    public function validateCustomerRef($bmc_code, $customer_code) {
        return $this->find()->where(['bmc_code' => $bmc_code, 'is_active' => 1])
                        ->andWhere(['or', ['customer_code' => $customer_code], ['ref_code' => $customer_code]])->one();
    }

    public function getvendor($bmc_code, $as_array = false) {
        if (!empty($bmc_code)) {
            $query = $this->find()->where(['bmc_code' => $bmc_code, 'is_active' => 1]);
            if ($as_array)
                $query->asArray();
            $dcs = $query->all();
            return $dcs;
        }
        return false;
    }

    public function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function maxDigits($attribute) {
        if (strlen($attribute) > 10) {
            $this->addError($attribute, 'must contain maximum 10 digits.');
        }
    }

    public function getAnimalTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'animal_type_code']);
    }

    public function resetData() {
        $this->aadhaar_no = null;
    }

}
