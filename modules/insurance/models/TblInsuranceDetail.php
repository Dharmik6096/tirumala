<?php

namespace app\modules\insurance\models;

use app\models\ChildModel;
use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\general\models\TblGender;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;
use yii\base\UserException;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_insurance_detail".
 *
 * @property integer $insurance_detail_code
 * @property integer $insurance_master_code
 * @property string $sr_no
 * @property string $union_code
 * @property string $plant_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $dcs_code
 * @property string $dcs_name
 * @property string $member_id
 * @property string $member_code
 * @property string $member_name
 * @property string $adhar_no
 * @property string $dob
 * @property integer $age
 * @property string $gender_code
 * @property string $nominee_adhar_no
 * @property string $nominee_member_name
 * @property string $status
 * @property integer $is_delete
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
class TblInsuranceDetail extends ChildModel {

    public $is_sentbox = TRUE;
    public $date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_insurance_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['insurance_detail_code', 'insurance_master_code', 'sr_no', 'union_code', 'plant_code', 'bmc_code', 'mcc_plant_code', 'dcs_code', 'dcs_name', 'member_id', 'member_code', 'member_name', 'adhar_no', 'dob', 'age', 'gender_code', 'nominee_adhar_no', 'nominee_member_name', 'date_of_joining_scheme', 'status', 'is_delete', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'date', 'sys_updated_by'], 'safe'],
            [['dcs_code', 'member_id', 'member_code', 'member_name', 'adhar_no', 'dob', 'age', 'gender_code', 'nominee_member_name', 'date_of_joining_scheme'], 'required', 'on' => ['create', 'update']],
            [['insurance_master_code'], 'required', 'on' => ['import_insurance_detail', 'create', 'update', 'dcs_wise_import']],
            [['plant_code', 'bmc_code', 'mcc_plant_code'], 'required', 'on' => ['create']],
            [['plant_code', 'bmc_code', 'mcc_plant_code', 'dcs_code'], 'required', 'on' => ['dcs_wise_import']],
            [['dcs_name', 'member_name', 'nominee_member_name'], 'string', 'max' => 100],
            [['status'], 'default', 'value' => 'DRAFT', 'on' => ['create']],
            [['is_delete'], 'default', 'value' => 0, 'on' => ['create']],
            [['member_id'], 'unique', 'targetAttribute' => ['member_id', 'insurance_master_code'], 'on' => ['create'], 'message' => Yii::t('app/validation', 'The combination of {attribute} And Insurance Master Code has already been taken.')],
            // [['member_code'], 'unique', 'targetAttribute' => ['member_code', 'insurance_master_code'], 'on' => ['create'], 'message' => Yii::t('app/validation', 'The combination of {attribute} And Insurance Master Code has already been taken.')],
            [['adhar_no', 'nominee_adhar_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAadharcard($this, $attribute, $params);
                }, 'on' => ['create', 'update']],
            [['adhar_no'], 'unique', 'targetAttribute' => ['adhar_no', 'insurance_master_code'], 'message' => Yii::t('app/validation', 'The combination of {attribute} And Insurance Master Code has already been taken.'), 'on' => ['create', 'update']],
            [['age'], function ($attribute, $params) {
                    Yii::$app->general->vaildateNumericField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'on' => ['create', 'update']],
            [['dob'], 'checkAgeLimit', 'on' => ['create', 'update']],
            [['insurance_master_code'], 'safe', 'on' => ['androidsync']],
            [['member_name', 'nominee_member_name'], function ($attribute, $params) {
                Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
            },'skipOnEmpty'=> false, 'except' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'insurance_detail_code' => Yii::t('app', 'Insurance Detail Code'),
            'insurance_master_code' => Yii::t('app', 'Insurance Master'),
            'sr_no' => Yii::t('app', 'Sr No'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'dcs_name' => Yii::t('app', 'DCS Name'),
            'member_id' => Yii::t('app', 'MPH ID'),
            'member_code' => Yii::t('app', 'Member'),
            'member_name' => Yii::t('app', 'Member Name'),
            'adhar_no' => Yii::t('app', 'Adhar No.'),
            'dob' => Yii::t('app', 'DOB'),
            'age' => Yii::t('app', 'Age'),
            'gender_code' => Yii::t('app', 'Gender'),
            'nominee_adhar_no' => Yii::t('app', 'Nominee Adhar No'),
            'nominee_member_name' => Yii::t('app', 'Nominee Member Name'),
            'date_of_joining_scheme' => Yii::t('app', 'Date Of Joining Scheme'),
            'status' => Yii::t('app', 'Status'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originated At'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'sys_updated_by' => Yii::t('app', 'System Updated By'),
        ];
    }

    public function checkAgeLimit($attribute, $param) {
        $result = TblInsuranceMaster::find()->where(['insurance_master_code' => $this->insurance_master_code])->one();
        if (($this->age < $result->member_min_age) || ($this->age > $result->member_max_age)) {
            $this->addError($attribute, Yii::t('app/validation', ' Invalid ' . Yii::t('app', 'dob') . '. Please enter a valid age within the range of ' . $result->member_min_age . ' to ' . $result->member_max_age));
            return false;
        }
    }

    public function validateMember($attribute, $param) {
        $data = TblMember::find()->where(['or', ['ex_member_code' => $this->member_code], ['member_code' => $this->member_code]])
                ->andWhere(['is_active' => 1])
                ->andWhere(['dcs_code' => $this->dcs_code])
                ->one();
        if (!empty($data)) {
            $this->{$attribute} = $data->member_code;
        } else {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'Member Code') . ' is invalid'));
            return false;
        }
    }

    public function getInsuranceMasterCode() {
        return $this->hasOne(TblInsuranceMaster::className(), ['insurance_master_code' => 'insurance_master_code']);
    }

    public function getInsuranceMaster($insurance_master_code) {
        return TblInsuranceMaster::find()->where(['insurance_master_code' => $insurance_master_code])->one();
    }

    public function getunionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getGenderCode() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_code']);
    }

    public function checkInsuranceDetail($insurance_master_code, $dcs_code = '', $status = '') {
        $result = TblInsuranceDetailSummary::find()->where(['insurance_master_code' => $insurance_master_code]);
        if (!empty($dcs_code)) {
            $result = $result->andWhere(['dcs_code' => $dcs_code]);
        }
        if (!empty($status)) {
            $result = $result->andWhere(['status' => $status]);
        }
        $result = $result->one();
        return $result;
    }

    public function getInsuranceDetailSummaryCode() {
        return $this->hasOne(TblInsuranceDetailSummary::className(), ['insurance_master_code' => 'insurance_master_code', 'dcs_code' => 'dcs_code']);
    }

    public function getInsuranceDetailCode() {
        return $this->find()->where(['insurance_master_code' => $this->insurance_master_code, 'dcs_code' => $this->dcs_code])
                        ->andWhere(['not in', 'insurance_detail_code', $this->insurance_detail_code])
                        ->one();
    }

    public function getmccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getdcsEditEndDate($insurance_master_code, $dcs_code) {
        $today = date("Y-m-d");
        $result = TblInsuranceDetailSummary::find()
                ->where(['insurance_master_code' => $insurance_master_code])
                ->andWhere(['dcs_code' => $dcs_code])
                ->andWhere(['<>', 'status', 'PARTIAL_FINALIZE'])
                ->andWhere(['<=', 'from_date', $today])
                ->andWhere(['>=', 'to_date', $today])
                ->one();

        return !empty($result) ? FALSE : TRUE;
    }

    public function disableAction($type = 'delete') {
        $class = '';
        $editable = true;
        $status = strtolower($this->status);
        if ($status !== 'draft' && $status !== 'partial_finalize') {
            $today = date("Y-m-d");
            $insuranceDetailSummaryCode = $this->insuranceDetailSummaryCode;
            if (!empty($insuranceDetailSummaryCode) && strtotime($insuranceDetailSummaryCode->from_date) <= strtotime($today) && strtotime($insuranceDetailSummaryCode->to_date) >= strtotime($today) || $status == 'finalize') {
                $class = 'link-disable';
                $editable = false;
            }
        }
        return ($type == 'delete') ? $editable : $class;
    }

    public function validInsuranceDetailMember($member, $member_id) {
        return $this->find()->where(['member_code' => $member, 'member_id' => $member_id])->one();
    }

    public function afterSave($insert, $changedAttributes) {
        if (strtolower($this->status) == 'publish' || strtolower($this->status) == 'partial_finalize') {
            $sentboxArray = [];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
            foreach ($sentboxArray as $sent) {
                $flag = ((isset($this->operation) && $this->operation == true) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }

    public function afterDelete() {
        if (strtolower($this->status) == 'publish' || strtolower($this->status) == 'partial_finalize') {
            $sentboxArray = [];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
            foreach ($sentboxArray as $sent) {
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, 'DELETE'))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->dcs_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
