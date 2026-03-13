<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_dcs_deactive".
 *
 * @property integer $dcs_deactive_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
 */
class TblDcsDeactive extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_deactive';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'remarks', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'dcs_deactive_code', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime'], 'safe'],
                [['originating_type'], 'integer'],
                [['from_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['from_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['from_date'], 'convertDate', 'on' => ['importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['bmc_code'], 'setImport', 'skipOnError' => true, 'on' => ['importCsv']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date'], 'required', 'except' => ['importCsv']],
                [['bmc_code', 'dcs_code', 'from_date'], 'required', 'on' => ['importCsv']],
                [['to_date'], 'required', 'on' => ['activeDCS']],
                [['from_date'], 'validateFromDate', 'except' => ['activeDCS']],
                [['to_date'], 'validateToRange', 'on' => ['activeDCS']],
                [['to_date'], 'validateDuplicateOnActivate', 'on' => ['activeDCS']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_deactive_code' => Yii::t('app', 'Dcs Deactive Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'from_date' => Yii::t('app', 'Wef Date'),
            'to_date' => Yii::t('app', 'Wef Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function validateFromDate($attribute, $params) {
        $existDCS = $this->find()
                ->where(['dcs_code' => $this->dcs_code])
                ->andfilterWhere(['!=', 'dcs_deactive_code', $this->dcs_deactive_code])
                ->andWhere(['IS', 'to_date', NULL])
                ->one();
        if (!empty($existDCS)) {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'DCS') . ' Is Already Deactivated.'));
            return false;
        }
        $dateData = $this->find()
                ->where('dcs_code=\'' . $this->dcs_code . '\'')
                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date))')
                ->andfilterWhere(['!=', 'dcs_deactive_code', $this->dcs_deactive_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function validateToRange($attribute, $params) {
        $fromDate = date('Y-m-d', strtotime($this->from_date));
        $toDate = date('Y-m-d', strtotime($this->to_date));
        if ($fromDate > $toDate) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }

        $dateData = $this->find()
                ->where('dcs_code=\'' . $this->dcs_code . '\'')
                ->andWhere('((\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))')
                ->andfilterWhere(['!=', 'dcs_deactive_code', $this->dcs_deactive_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function getDcsDEactivated($bmc, $dateFilter) {
        $date = date('Y-m-d', strtotime($dateFilter));
        $records = $this->find()
                ->where('bmc_code=\'' . $bmc . '\'')
                ->andWhere('((\'' . $date . '\' between cast(from_date as date)  and case when to_date is null then \'' . date('Y-m-d') . '\' else cast(to_date as date) end))')
                ->all();
        $value = ArrayHelper::map($records, 'dcs_code', 'dcs_code');
        return $value;
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
                $query->andWhere(['dcs_code' => $data['organization_code']]);
            }
        }
        $dataList = $query->orderBy(['dcs_deactive_code' => SORT_ASC])
                ->limit($limit)
                ->all();
        return $dataList;
    }

    public function getActiveRecords($limit) {
        $date = date('Y-m-d');

        return $query = $this->find()
                ->where(['data_post_status' => 2])
                ->andWhere(['<', 'to_date', $date])
                ->orderBy(['dcs_deactive_code' => SORT_ASC])
                ->limit($limit)
                ->all();
    }

    public function updateFileStatus($value, $status) {
        return $this->updateAll(['data_post_status' => $status, 'picked_datetime' => date('Y-m-d H:i:s')], ['dcs_deactive_code' => $value]);
    }

    public function setImport($attribute, $params) {
        $date = date('Y-m-d');
        $fromDate = date('Y-m-d', strtotime($this->from_date));
        if ($date > $fromDate) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'From Date') . ' Must not past date'));
            return false;
        }
        $dcs = new TblDcs();
        $this->dcs_code = $dcs->getValidDcs($this->dcs_code, $this->bmc_code);
        if (empty($this->dcs_code)) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
            return false;
        }
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

    public function validateDuplicateOnActivate($attribute, $params) {
        if (!empty($this->dcs_code)) {

            $dcsMobileNo = Yii::$app->general->getforeignkey($this->dcsCode, 'mobile_no');
            $dcsAdharNo = Yii::$app->general->getforeignkey($this->dcsCode, 'aadhaar_no');
            $dcsBankAccNo = Yii::$app->general->getforeignkey($this->dcsCode, 'bank_account_no');

            if (!empty($dcsMobileNo)) {
                if ($duplicate = $this->checkDuplicateInActiveDcs($dcsMobileNo, 'mobile_no', $this->dcs_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Mobile No already exists in Active DCS: ' . $duplicate->dcs_code . ' - ' . $duplicate->dcs_name));
                    return false;
                }
            }

            if (!empty($dcsAdharNo)) {
                if ($duplicate = $this->checkDuplicateInActiveDcs($dcsAdharNo, 'aadhaar_no', $this->dcs_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Aadhaar No already exists in Active DCS: ' . $duplicate->dcs_code . ' - ' . $duplicate->dcs_name));
                    return false;
                }
            }

            if (!empty($dcsBankAccNo)) {
                if ($duplicate = $this->checkDuplicateInActiveDcs($dcsBankAccNo, 'bank_account_no', $this->dcs_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Bank Account No already exists in Active DCS: ' . $duplicate->dcs_code . ' - ' . $duplicate->dcs_name));
                    return false;
                }
            }
        }
    }

    public function checkDuplicateInActiveDcs($value, $fieldName, $dcsCode) {
        if (!empty($value)) {
            $encryptedValue = Yii::$app->general->encryptData($value);

            return TblDcs::find()->where(['is_active' => 1])
                            ->andWhere(['<>', 'dcs_code', $dcsCode])
                            ->andWhere(['or', [$fieldName => $value], [$fieldName => $encryptedValue]])
                            ->one();
        }
        return null;
    }

}
