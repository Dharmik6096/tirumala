<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblCustomerMaster;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\details\models\TblBankDetails;

/**
 * This is the model class for table "tbl_customer_deactive".
 *
 * @property string $customer_deactive_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_code
 * @property string $customer_type
 * @property string $from_date
 * @property string $to_date
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 */
class TblCustomerDeactive extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_deactive';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'from_date'], 'required', 'except' => ['importCsv']],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime', 'customer_deactive_code'], 'safe'],
                [['remarks'], 'string'],
                [['originating_type', 'data_post_status'], 'integer'],
//            [['customer_deactive_code', 'bmc_code'], 'string', 'max' => 12],
            [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
                [['customer_code', 'customer_type'], 'string', 'max' => 20],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
                [['resp_status', 'resp_desc'], 'string', 'max' => 255],
                [['to_date'], 'required', 'on' => ['activeCustomer']],
                [['from_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['from_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['from_date'], 'convertDate', 'on' => ['importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['bmc_code'], 'setImport', 'skipOnError' => true, 'on' => ['importCsv']],
                [['bmc_code', 'customer_code', 'from_date'], 'required', 'on' => ['importCsv']],
                [['from_date'], 'validateFromDate', 'except' => ['activeCustomer']],
                [['to_date'], 'validateToRange', 'on' => ['activeCustomer']],
                [['to_date'], 'validateDuplicateOnActivate', 'on' => ['activeCustomer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'customer_deactive_code' => Yii::t('app', 'Customer Deactive Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Customer'),
            'customer_type' => Yii::t('app', 'Type'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function validateFromDate($attribute, $params) {
        $existDCS = $this->find()
                ->where(['customer_code' => $this->customer_code])
                ->andfilterWhere(['!=', 'customer_deactive_code', $this->customer_deactive_code])
                ->andWhere(['IS', 'to_date', NULL])
                ->one();
        if (!empty($existDCS)) {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'Customer') . ' Is Already Deactivated.'));
            return false;
        }
        $dateData = $this->find()
                ->where('customer_code=\'' . $this->customer_code . '\'')
                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date))')
                ->andfilterWhere(['!=', 'customer_deactive_code', $this->customer_deactive_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function validateToRange($attribute, $params) {
        $fromDate = date('Y-m-d', strtotime($this->from_date));
        $toDate = date('Y-m-d', strtotime($this->to_date));
        if ($fromDate == $toDate || $fromDate > $toDate) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }

        $dateData = $this->find()
                ->where('customer_code=\'' . $this->customer_code . '\'')
                ->andWhere('((\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))')
                ->andfilterWhere(['!=', 'customer_deactive_code', $this->customer_deactive_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function getDeactiveRecords($checkStatus = true, $data = '', $limit = '') {
        $date = date('Y-m-d');
        $query = $this->find()
                ->where(['<=', 'from_date', $date])
                ->andWhere(['or', ['>=', 'to_date', $date], ['is', 'to_date', NULL]]);
        if ($checkStatus) {
            $query->andWhere(['or', ['data_post_status' => 0], ['is', 'data_post_status', NULL]]);
        }
        if (!empty($data) && (!empty($data['organization_code']) && !empty($data['organization_type']))) {
            if ($data['organization_type'] == 'MCC') {
                $query->andWhere(['mcc_plant_code' => $data['organization_code']]);
            }
            if ($data['organization_type'] == 'BMC') {
                $query->andWhere(['bmc_code' => $data['organization_code']]);
            }
            if ($data['organization_type'] == 'VLC') {
                $query->andWhere(['customer_code' => $data['organization_code']]);
            }
        }
        $data = $query->orderBy(['customer_deactive_code' => SORT_ASC])
                ->limit($limit)
                ->all();
        return $data;
    }

    public function getActiveRecords($limit) {
        $date = date('Y-m-d');
        return $query = $this->find()
                ->where(['data_post_status' => 2])
                ->andWhere(['<', 'to_date', $date])
                ->orderBy(['customer_deactive_code' => SORT_ASC])
                ->limit($limit)
                ->all();
    }

    public function updateFileStatus($value, $status) {
        return $this->updateAll(['data_post_status' => $status, 'picked_datetime' => date('Y-m-d H:i:s')], ['customer_deactive_code' => $value]);
    }

    public function setImport($attribute, $params) {
        $date = date('Y-m-d');
        $fromDate = date('Y-m-d', strtotime($this->from_date));
        if ($date > $fromDate) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'From Date') . ' Must not past date'));
            return false;
        }
        $custmr = new TblCustomerMaster();
        $customer_data = $custmr->validateCustomerRef($this->bmc_code, $this->customer_code);
        $this->customer_code = $customer_data->customer_code;
        if (empty($this->customer_code)) {
            $this->addError('customer_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
            return false;
        }
        $this->customer_type = Yii::$app->general->getforeignkey($this->customerCode, 'customer_type');
        $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
        $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
    }

    public function convertDateDot() {
        try {
            $this->from_date = Yii::$app->controls->view_date($this->from_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->from_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->from_date = !empty($this->from_date) ? Yii::$app->controls->view_date($this->from_date, 'php:Y-m-d') : NULL;
        }
    }

    public function getDeactiveCustomer($type, $dateFilter) {
        $checkdate = date('Y-m-d', strtotime($dateFilter));
        $deactivateList = $this->find()
                ->select('customer_code')
                ->where(['customer_type' => $type])
                ->andWhere('((:checkdate between cast(from_date as date) and coalesce(cast(to_date as date), \'9999-12-31\')))', [':checkdate' => $checkdate])
                ->column();
        return $deactivateList;
    }

    public function validateDuplicateOnActivate($attribute, $params) {
        if (!empty($this->customer_code)) {

            $customerMobileNo = Yii::$app->general->getforeignkey($this->customerCode, 'mobile_no');
            $customerAdharNo = Yii::$app->general->getforeignkey($this->customerCode, 'aadhaar_no');
            $customerBankAccNo = Yii::$app->general->getmultiforeignkey($this->customerCode, ['defaultBankDetail'], 'bank_account_no');

            if (!empty($customerMobileNo)) {
                if ($duplicate = $this->checkDuplicateInActiveCustomer($customerMobileNo, 'mobile_no', $this->customer_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Mobile No already exists in Active Customer Master: ' . $duplicate->customer_code . ' - ' . $duplicate->customer_name));
                    return false;
                }
            }

            if (!empty($customerAdharNo)) {
                if ($duplicate = $this->checkDuplicateInActiveCustomer($customerAdharNo, 'aadhaar_no', $this->customer_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Adhar No already exists in Active Customer Master: ' . $duplicate->customer_code . ' - ' . $duplicate->customer_name));
                    return false;
                }
            }

            if (!empty($customerBankAccNo)) {
                $encryptedBankAccNo = Yii::$app->general->encryptData($customerBankAccNo);

                $existsInCustomerBank = TblBankDetails::find()
                                ->select(['tbl_bank_details.module_code', 'cus.customer_name'])
                                ->leftJoin('tbl_customer_master cus', 'cus.customer_code = tbl_bank_details.module_code')
                                ->where(['tbl_bank_details.is_active' => 1])
                                ->andWhere(['tbl_bank_details.module_name' => 'customer'])
                                ->andWhere(['tbl_bank_details.is_default' => 1])
                                ->andWhere(['<>', 'tbl_bank_details.module_code', $this->customer_code])
                                ->andWhere(['or', ['tbl_bank_details.bank_account_no' => $customerBankAccNo], ['tbl_bank_details.bank_account_no' => $encryptedBankAccNo]])
                                ->asArray()->one();

                if ($existsInCustomerBank) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Bank Account No already exists in Customer Bank Detail - Code : ' . $existsInCustomerBank['module_code'] . ', Name : ' . $existsInCustomerBank['customer_name']));
                    return false;
                }
            }
        }
    }

    public function checkDuplicateInActiveCustomer($value, $fieldName, $customerCode) {
        if (!empty($value)) {
            $encryptedValue = Yii::$app->general->encryptData($value);

            return TblCustomerMaster::find()->where(['is_active' => 1])
                            ->andWhere(['<>', 'customer_code', $customerCode])
                            ->andWhere(['or', [$fieldName => $value], [$fieldName => $encryptedValue]])
                            ->one();
        }
        return null;
    }

}
