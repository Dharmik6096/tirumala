<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\globalmaster\models\TblDesignation;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblHamlets;
use app\modules\geo\models\TblDistricts;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblBranch;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\organisation\models\TblBanks;
use app\modules\globalmaster\models\TblCasteCategory;
use app\modules\general\models\TblBloodgroup;
use app\modules\general\models\TblGender;
use app\modules\staffmanagement\models\TblMember;
use app\modules\organisation\models\TblUnions;
use app\modules\general\models\TblQualification;
use app\modules\general\models\TblDepartment;

/**
 * This is the model class for table "tbl_staff_member".
 *
 * @property string $staff_member_code
 * @property string $aadhar_card_no
 * @property string $address
 * @property string $bank_account_no
 * @property string $birth_date
 * @property string $created_at
 * @property string $email_id
 * @property string $ifsc
 * @property integer $is_active
 * @property string $mobile_no
 * @property string $pan_no
 * @property integer $payment_mode
 * @property string $pincode
 * @property string $staff_member_name
 * @property string $tenure_from_date
 * @property string $tenure_to_date
 * @property string $updated_at
 * @property string $bank_code
 * @property integer $blood_group_code
 * @property string $branch_code
 * @property integer $caste_category_code
 * @property string $created_by
 * @property string $designation_code
 * @property string $district_code
 * @property integer $gender_code
 * @property string $hamlet_code
 * @property string $member_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $updated_by
 * @property string $village_code
 *
 * @property TblMeetingAttendance[] $tblMeetingAttendances
 * @property TblStaffAdditionDeduction[] $tblStaffAdditionDeductions
 * @property TblStaffAdditionDeductionHistory[] $tblStaffAdditionDeductionHistories
 * @property TblStaffAttendance[] $tblStaffAttendances
 * @property TblStaffAttendanceHistory[] $tblStaffAttendanceHistories
 * @property TblStates $stateCode
 * @property TblDesignation $designationCode
 * @property TblHamlets $hamletCode
 * @property TblUsers $updatedBy
 * @property TblGender $gender
 * @property TblDistricts $districtCode
 * @property TblSubCenter $subCenterCode
 * @property TblUsers $createdBy
 * @property TblBranch $branchCode
 * @property TblBloodgroup $bloodGroup
 * @property TblVillages $villageCode
 * @property TblSubDistricts $subDistrictCode
 * @property TblBanks $bankCode
 * @property TblCasteCategory $casteCategoryCode
 * @property TblMember $memberCode
 * @property TblUsers $deletedBy
 * @property TblStaffMemberDesignation[] $tblStaffMemberDesignations
 * @property TblStaffMemberLocal[] $tblStaffMemberLocals
 * @property TblStaffMemberLocalHistory[] $tblStaffMemberLocalHistories
 * @property TblStaffSalary[] $tblStaffSalaries
 * @property TblStaffSalaryHistory[] $tblStaffSalaryHistories
 * @property TblStaffSalaryProcessing[] $tblStaffSalaryProcessings
 * @property TblStaffSalaryProcessingHistory[] $tblStaffSalaryProcessingHistories
 */
class TblStaffMember extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_member';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['department'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'department');
        }, 'on' => 'importCsv'],
            [['blood_group_code'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'blood-group');
        }, 'on' => 'importCsv'],
            [['gender_code'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'gender');
        }, 'on' => 'importCsv'],
            [['caste_category_code'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'caste-category');
        }, 'on' => 'importCsv'],
            [['designation_code'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalData($this, $attribute, 'designation_code');
        }, 'on' => 'importCsv'],
            [['payment_mode'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalStatic($this, $attribute, 'payment_mode');
        }, 'on' => 'importCsv'],
            [['is_on_role'], function ($attribute, $params) {
            Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_on_role');
        }, 'on' => 'importCsv'],
            [['staff_member_name', 'tenure_from_date', 'ex_staff_member_code', 'gender_code', 'caste_category_code', 'district_code', 'sub_district_code', 'hamlet_code', 'state_code', 'village_code', 'designation_code', 'address', 'payment_mode', 'union_code'], 'required', 'except' => ['importCsv']],
            [['staff_member_name', 'tenure_from_date', 'ex_staff_member_code', 'gender_code', 'caste_category_code', 'hamlet_code', 'designation_code', 'address', 'payment_mode', 'union_code'], 'required', 'on' => ['importCsv']],
            [['birth_date', 'created_at', 'tenure_from_date', 'tenure_to_date', 'updated_at', 'qualification_code', 'department', 'ex_staff_member_code', 'union_code', 'ifsc', 'pan_no', 'village_code', 'sub_district_code', 'district_code', 'state_code', 'aadhar_card_no', 'is_on_role', 'uan_no', 'esic_no', 'pf_no', 'approved_date', 'salary', 'member_code'], 'safe'],
            [['is_active', 'payment_mode', 'blood_group_code', 'caste_category_code', 'designation_code', 'gender_code'], 'integer'],
            [['bank_account_no'], 'string', 'max' => 20],
            [['aadhar_card_no'], 'string', 'max' => 16],
            [['address'], 'string', 'max' => 500],
            [['email_id', 'mobile_no'], 'string', 'max' => 255],
            [['branch_code', 'village_code'], 'string', 'max' => 6],
            [['staff_member_name'], 'string', 'max' => 200],
            [['bank_code'], 'string', 'max' => 4],
            [['district_code'], 'string', 'max' => 3],
            [['hamlet_code'], 'string', 'max' => 9, 'min' => 8],
            [['state_code'], 'string', 'max' => 2],
            [['sub_district_code'], 'string', 'max' => 5],
            [['is_active'], 'default', 'value' => 1],
            [['email_id'], 'email'],
            [['mobile_no'], function ($attribute, $params) {
            Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => true],
            [['staff_member_name'], function ($attribute, $params) {
            Yii::$app->general->validateNameWithDash($this, $attribute);
        }, 'skipOnEmpty' => TRUE],
            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
            return $this->is_active;
        }, 'except' => ['saveCreamyData', 'androidsync']],
            [['bank_account_no'], function ($attribute, $params) {
            $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
            if ($error !== TRUE)
                $this->addError($attribute, $error);
        }],
            [['address'], function ($attribute, $params) {
            Yii::$app->general->validateDescription($this, $attribute);
        }, 'skipOnEmpty' => TRUE],
            [['bank_code', 'branch_code', 'bank_account_no'], 'required', 'when' => function ($model) {
                    return $model->payment_mode == '1';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#payment_mode').val() == '1'; 
          }", 'except' => ['importCsv']],
            [['branch_code', 'bank_account_no'], 'required', 'when' => function ($model) {
                    return $model->payment_mode == '1';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#payment_mode').val() == '1'; 
          }", 'on' => ['importCsv']],
            [['ex_staff_member_code'], 'unique'],
            [['birth_date', 'tenure_from_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['birth_date', 'tenure_from_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2020'), 'on' => ['importCsv']],
            [['birth_date', 'tenure_from_date'], 'convertDate', 'on' => ['importCsv']],
            [['tenure_from_date'], 'birthDatevalidate'],
            [['branch_code'], function ($attribute, $params) {
            Yii::$app->general->validateBranch($this, $attribute, $params);
        }, 'skipOnEmpty' => false, 'on' => ['importCsv']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => ['importCsv']],
            [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id'], 'on' => ['importCsv']],
            [['blood_group_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBloodgroup::className(), 'targetAttribute' => ['blood_group_code' => 'blood_group_code'], 'on' => ['importCsv']],
            [['gender_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblGender::className(), 'targetAttribute' => ['gender_code' => 'gender_code'], 'on' => ['importCsv']],
            [['qualification_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblQualification::className(), 'targetAttribute' => ['qualification_code' => 'qualification_code'], 'on' => ['importCsv']],
            [['caste_category_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCasteCategory::className(), 'targetAttribute' => ['caste_category_code' => 'caste_category_code'], 'on' => ['importCsv']],
            [['designation_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDesignation::className(), 'targetAttribute' => ['designation_code' => 'designation_code'], 'on' => ['importCsv']],
            [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code'], 'on' => ['importCsv']],
            [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code'], 'on' => ['importCsv']],
            [['union_code'], 'setImport', 'on' => ['importCsv']],
            [['uan_no'], 'string', 'min' => 12, 'max' => 12],
            [['esic_no'], 'string', 'min' => 10, 'max' => 10],
            [['esic_no', 'uan_no'], 'number'],
            [['pf_no'], function ($attribute, $params) {
            Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
        }, 'skipOnEmpty' => false,],
            //required due to dependency in attendance
            [['is_on_role'], 'required', 'except' => ['androidsync']],
            [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblStaffMember', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'aadhar_card_no' => Yii::t('app', 'Aadhar Card No'),
            'address' => Yii::t('app', 'Address'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'email_id' => Yii::t('app', 'Email ID'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'pincode' => Yii::t('app', 'Pincode'),
            'staff_member_name' => Yii::t('app', 'Staff Member Name'),
            'tenure_from_date' => Yii::t('app', 'Tenure From Date'),
            'tenure_to_date' => Yii::t('app', 'Tenure To Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_code' => Yii::t('app', 'Bank'),
            'blood_group_code' => Yii::t('app', 'Blood Group'),
            'branch_code' => Yii::t('app', 'Branch'),
            'caste_category_code' => Yii::t('app', 'Caste Category'),
            'created_by' => Yii::t('app', 'Created By'),
            'designation_code' => Yii::t('app', 'Designation'),
            'district_code' => Yii::t('app', 'District'),
            'gender_code' => Yii::t('app', 'Gender ID'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'state_code' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village'),
            'union_code' => Yii::t('app', 'Union'),
            'qualification_code' => Yii::t('app', 'Qualification'),
            'uan_no' => Yii::t('app', 'UAN'),
            'esic_no' => Yii::t('app', 'ESIC'),
            'pf_no' => Yii::t('app', 'PF No.'),
            'is_on_role' => Yii::t('app', 'On Role'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMeetingAttendances() {
        return $this->hasMany(TblMeetingAttendance::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAdditionDeductions() {
        return $this->hasMany(TblStaffAdditionDeduction::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAdditionDeductionHistories() {
        return $this->hasMany(TblStaffAdditionDeductionHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAttendances() {
        return $this->hasMany(TblStaffAttendance::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAttendanceHistories() {
        return $this->hasMany(TblStaffAttendanceHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignationCode() {
        return $this->hasOne(TblDesignation::className(), ['designation_code' => 'designation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodGroup() {
        return $this->hasOne(TblBloodgroup::className(), ['blood_group_code' => 'blood_group_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCasteCategoryCode() {
        return $this->hasOne(TblCasteCategory::className(), ['caste_category_code' => 'caste_category_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberDesignations() {
        return $this->hasMany(TblStaffMemberDesignation::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberLocals() {
        return $this->hasMany(TblStaffMemberLocal::className(), ['staffmember_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberLocalHistories() {
        return $this->hasMany(TblStaffMemberLocalHistory::className(), ['staffmember_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaries() {
        return $this->hasMany(TblStaffSalary::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryHistories() {
        return $this->hasMany(TblStaffSalaryHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryProcessings() {
        return $this->hasMany(TblStaffSalaryProcessing::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryProcessingHistories() {
        return $this->hasMany(TblStaffSalaryProcessingHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @inheritdoc
     * @return TblStaffMemberQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffMemberQuery(get_called_class());
    }

    public function birthDatevalidate($attribute, $params) {
        $birth_date = !empty($this->birth_date) ? Yii::$app->controls->view_date($this->birth_date, 'php:Y-m-d') : NULL;
        $tenure_from_date = !empty($this->tenure_from_date) ? Yii::$app->controls->view_date($this->tenure_from_date, 'php:Y-m-d') : NULL;
        $to_date = !empty($this->tenure_to_date) ? Yii::$app->controls->view_date($this->tenure_to_date, 'php:Y-m-d') : NULL;
        if (!empty($birth_date) && !empty($tenure_from_date) && ($birth_date > $tenure_from_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'Tenure From Must be Greater than Birth Date'));
            return false;
        }
        if (!empty($to_date) && !empty($tenure_from_date) && ($tenure_from_date > $to_date)) {
            $this->addError('tenure_to_date', Yii::t('app/validation', 'Tenure To Must be Greater than Tenure From'));
            return false;
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getQualificationCode() {
        return $this->hasOne(TblQualification::className(), ['qualification_code' => 'qualification_code']);
    }

    public function setImport($attribute, $params) {
        $this->village_code = Yii::$app->general->getforeignkey($this->hamletCode, 'village_code');
        $this->sub_district_code = Yii::$app->general->getmultiforeignkey($this->hamletCode, ['villageCode'], 'sub_district_code');
        $this->district_code = Yii::$app->general->getmultiforeignkey($this->hamletCode, ['villageCode', 'subDistrictCode'], 'district_code');
        $this->state_code = Yii::$app->general->getmultiforeignkey($this->hamletCode, ['villageCode', 'subDistrictCode', 'districtCode'], 'state_code');
        $model = new TblBranch();
        $this->ifsc = !empty($this->branch_code) ? $model->getIfcs($this->branch_code) : '';
        $this->bank_code = Yii::$app->general->getforeignkey($this->branchCode, 'bank_code');
    }

    public function getDepartmentCode() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

    public function convertDateDot() {
        try {
            $this->birth_date = Yii::$app->controls->view_date($this->birth_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->birth_date = '-';
        }
        try {
            $this->tenure_from_date = Yii::$app->controls->view_date($this->tenure_from_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->tenure_from_date = '-';
        }
    }

    public function convertDate() {
        $this->birth_date = !empty($this->birth_date) ? Yii::$app->controls->view_date($this->birth_date, 'php:Y-m-d') : NULL;
        $this->tenure_from_date = !empty($this->tenure_from_date) ? Yii::$app->controls->view_date($this->tenure_from_date, 'php:Y-m-d') : NULL;
    }

    public function setChildTable($model, &$modelSave) {
        $desigModel = new TblStaffMemberDesignation;
        $desigModel->staff_member_designation_code = (string) Yii::$app->general->getCodeAutoIncrement($desigModel);
        $desigModel->attributes = $model->attributes;
        array_push($modelSave, $desigModel);
    }
     public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }
}
