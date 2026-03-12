<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\feedback\models\TblVCGMRGMember;

/**
 * This is the model class for table "tbl_member_deactive".
 *
 * @property string $member_deactive_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
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
class TblMemberDeactive extends \app\models\ChildModel {

    public $member, $wef_date, $is_active;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_deactive';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['member_deactive_code'], 'required', 'except' => ['importCsv']],
                [['member_deactive_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'remarks', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'wef_date', 'is_active'], 'safe'],
                [['originating_type'], 'integer'],
                [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['wef_date'], 'convertDate', 'on' => ['importCsv']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'from_date'], 'required', 'except' => ['importCsv']],
                [['dcs_code', 'member', 'wef_date', 'is_active'], 'required', 'on' => ['importCsv']],
                [['is_active'], 'boolean', 'on' => ['importCsv']],
                [['to_date'], 'required', 'on' => ['activeMember']],
                [['member'], 'setImport', 'skipOnError' => true, 'on' => ['importCsv']],
                [['from_date'], 'validateFromDate', 'except' => ['activeMember', 'importCsv']],
                [['to_date'], 'validateToRange', 'on' => ['activeMember']],
                [['to_date'], 'validateToRange', 'on' => ['importCsv'], 'when' => function($model) {
                    return ($this->is_active == 1) ? true : false;
                }],
                [['from_date'], 'validateFromDate', 'on' => ['importCsv'], 'when' => function($model) {
                    return ($this->is_active == 0) ? true : false;
                }],
                [['data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'member'], 'safe'],
                [['to_date'], 'validateDuplicateOnActivate', 'on' => ['activeMember']],
                [['to_date'], 'validateDuplicateOnActivate', 'on' => ['importCsv'], 'when' => function($model) {
                    return ($this->is_active == 1) ? true : false;
                }],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMemberDeactive', $this->form_validation_type);
        $rules = array_merge($main_rules, $client_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_deactive_code' => Yii::t('app', 'Member Deactive Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member Code'),
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

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function validateFromDate($attribute, $params) {
        $existDCS = $this->find()
                ->where(['dcs_code' => $this->dcs_code, 'member_code' => $this->member_code])
                ->andfilterWhere(['!=', 'member_deactive_code', $this->member_deactive_code])
                ->andWhere(['IS', 'to_date', NULL])
                ->count();

        if ($existDCS > 0) {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'Member') . ' Is Already Deactivated.'));
            return false;
        }
        $dateData = $this->find()
                ->where('dcs_code=\'' . $this->dcs_code . '\' and member_code=\'' . $this->member_code . '\'')
                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date))')
                ->andfilterWhere(['!=', 'member_deactive_code', $this->member_deactive_code])
                ->count();

        if ($dateData > 0) {
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

//        $dateData = $this->find()
//                ->where('dcs_code=\'' . $this->dcs_code . '\' and member_code=\'' . $this->member_code . '\'')
//                ->andWhere('((\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))')
//                ->andfilterWhere(['!=', 'member_deactive_code', $this->member_deactive_code])
//                ->all();
//        if (!empty($dateData)) {
//            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
//            return false;
//        }
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
        $date = date('Y-m-d');
        $wefDate = date('Y-m-d', strtotime($this->wef_date));

        if (empty($this->dcs_code)) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
            return false;
        } else {
            $this->member_code = $this->dcs_code . str_pad(substr($this->member, -4), 4, '0', STR_PAD_LEFT);
            if (empty($this->memberCode)) {
                $this->addError('member_code', Yii::t('app/validation', Yii::t('app', 'Member') . ' is invalid'));
                return false;
            }
        }
        $dcs_code = $this->dcsCode;
        $this->bmc_code = $dcs_code->bmc_code;
        $this->union_code = $dcs_code->union_code;
        $this->plant_code = $dcs_code->plant_code;
        $this->mcc_plant_code = $dcs_code->mcc_plant_code;

        if ($this->is_active == 1) {
            $this->to_date = date('Y-m-d', strtotime('-1 day', strtotime($this->wef_date)));
        } else {
            $this->from_date = date('Y-m-d', strtotime($this->wef_date));
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
                $query->andWhere(['dcs_code' => $data['organization_code']]);
            }
        }
        $dataList = $query->orderBy(['member_deactive_code' => SORT_ASC])
                ->limit($limit)
                ->all();
        return $dataList;
    }

    public function getActiveRecords($limit) {
        $date = date('Y-m-d');

        return $query = $this->find()
                ->where(['data_post_status' => 2])
                ->andWhere(['<', 'to_date', $date])
                ->orderBy(['member_deactive_code' => SORT_ASC])
                ->limit($limit)
                ->all();
    }

    public function updateFileStatus($value, $status) {
        return $this->updateAll(['data_post_status' => $status, 'picked_datetime' => date('Y-m-d H:i:s')], ['member_deactive_code' => $value]);
    }

    public function getDeactiveMember($union_code, $dcs, $dateFilter) {
        $checkdate = date('Y-m-d', strtotime($dateFilter));
        $deactivateMemberList = $this->find()
                ->select('member_code')
                ->where(['dcs_code' => $dcs])
                ->andWhere('((:checkdate between cast(from_date as date) and coalesce(cast(to_date as date), \'9999-12-31\')))', [':checkdate' => $checkdate])
                ->column();
        return $deactivateMemberList;
    }

    public function updateChildRecord($model, $status) {
        if ($status == 0) {
            $vcgMrgMemberModel = TblVCGMRGMember::find()->where(['member_code' => $model->member_code, 'status' => ['DRAFT', 'APPROVED']])->one();
            if (!empty($vcgMrgMemberModel)) {
                $vcgMrgMemberModel->status = 'INACTIVATE';
                $vcgMrgMemberModel->end_date = date('Y-m-d');
                $vcgMrgMemberModel->save();
            }
        }
    }

    public function validateDuplicateOnActivate($attribute, $params) {
        if (!empty($this->member_code)) {

            $memberMobileNo = Yii::$app->general->getforeignkey($this->memberCode, 'mobile_no');
            $memberAdharNo = Yii::$app->general->getforeignkey($this->memberCode, 'adhar_no');
            $memberBankAccNo = Yii::$app->general->getforeignkey($this->memberCode, 'bank_account_no');

            if (!empty($memberMobileNo)) {
                if ($duplicate = $this->checkDuplicateInActiveMember($memberMobileNo, 'mobile_no', $this->member_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Mobile No already exists in Active Member: ' . $duplicate->member_code . ' - ' . $duplicate->member_name));
                    return false;
                }
            }

            if (!empty($memberAdharNo)) {
                if ($duplicate = $this->checkDuplicateInActiveMember($memberAdharNo, 'adhar_no', $this->member_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Adhar No already exists in Active Member: ' . $duplicate->member_code . ' - ' . $duplicate->member_name));
                    return false;
                }
            }

            if (!empty($memberBankAccNo)) {
                if ($duplicate = $this->checkDuplicateInActiveMember($memberBankAccNo, 'bank_account_no', $this->member_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Cannot activate. Bank Account No already exists in Active Member: ' . $duplicate->member_code . ' - ' . $duplicate->member_name));
                    return false;
                }
            }
        }
    }

    public function checkDuplicateInActiveMember($value, $fieldName, $memberCode) {
        if (!empty($value)) {
            $encryptedValue = Yii::$app->general->encryptData($value);

            return TblMember::find()->where(['is_active' => 1])
                            ->andWhere(['<>', 'member_code', $memberCode])
                            ->andWhere(['or', [$fieldName => $value], [$fieldName => $encryptedValue]])
                            ->one();
        }
        return null;
    }

}
