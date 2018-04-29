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
use app\modules\staffmanagement\models\TblBloodgroup;
use app\modules\staffmanagement\models\TblGender;
use app\modules\staffmanagement\models\TblMember;

/**
 * This is the model class for table "tbl_staff_member".
 *
 * @property string $staff_member_code
 * @property string $aadhar_card_no
 * @property string $address
 * @property string $bank_account_no
 * @property string $birth_date
 * @property string $created_at
 * @property string $deleted_at
 * @property string $email_id
 * @property string $flg_sentbox_entry
 * @property string $ifsc
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $mobile_no
 * @property string $pan_no
 * @property integer $payment_mode
 * @property string $pincode
 * @property string $staff_member_name
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $tenure_from_date
 * @property string $tenure_to_date
 * @property string $updated_at
 * @property string $bank_code
 * @property integer $blood_group_id
 * @property string $branch_code
 * @property integer $caste_category_code
 * @property string $created_by
 * @property string $deleted_by
 * @property string $designation_code
 * @property string $district_code
 * @property integer $gender_id
 * @property string $hamlet_code
 * @property string $member_code
 * @property string $state_code
 * @property string $sub_center_code
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
class TblStaffMember extends \yii\db\ActiveRecord
{
    public  $local_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_member_code', 'staff_member_name', 'tenure_from_date'], 'required'],
            [['birth_date', 'created_at', 'deleted_at', 'sync_timestamp', 'tenure_from_date', 'tenure_to_date', 'updated_at','local_name'], 'safe'],
            [['is_active', 'is_delete', 'payment_mode', 'blood_group_id', 'caste_category_code', 'designation_code', 'gender_id'], 'integer'],
            [['staff_member_code', 'bank_account_no'], 'string', 'max' => 20],
            [['aadhar_card_no'], 'string', 'max' => 16],
            [['address'], 'string', 'max' => 500],
            [['email_id', 'mobile_no'], 'string', 'max' => 255],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['ifsc', 'pan_no', 'created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 10],
            [['pincode', 'branch_code', 'village_code'], 'string', 'max' => 6],
            [['staff_member_name'], 'string', 'max' => 200],
            [['bank_code'], 'string', 'max' => 4],
            [['district_code'], 'string', 'max' => 3],
            [['hamlet_code'], 'string', 'max' => 8],
            [['member_code', 'sub_center_code'], 'string', 'max' => 11],
            [['state_code'], 'string', 'max' => 2],
            [['sub_district_code'], 'string', 'max' => 5],
//            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
//            [['designation_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDesignation::className(), 'targetAttribute' => ['designation_code' => 'designation_code']],
//            [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
//            [['gender_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblGender::className(), 'targetAttribute' => ['gender_id' => 'gender_id']],
//            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
//            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
//            [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code']],
//            [['blood_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblBloodgroup::className(), 'targetAttribute' => ['blood_group_id' => 'blood_group_id']],
//            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
//            [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
//            [['caste_category_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCasteCategory::className(), 'targetAttribute' => ['caste_category_code' => 'caste_category_code']],
//            [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
//            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['deleted_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'local_name' => Yii::t('app', 'Local Name'),
            'aadhar_card_no' => Yii::t('app', 'Aadhar Card No'),
            'address' => Yii::t('app', 'Address'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'email_id' => Yii::t('app', 'Email ID'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'pincode' => Yii::t('app', 'Pincode'),
            'staff_member_name' => Yii::t('app', 'Staff Member Name'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'tenure_from_date' => Yii::t('app', 'Tenure From Date'),
            'tenure_to_date' => Yii::t('app', 'Tenure To Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'blood_group_id' => Yii::t('app', 'Blood Group'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'caste_category_code' => Yii::t('app', 'Caste Category Code'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'designation_code' => Yii::t('app', 'Designation Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'gender_id' => Yii::t('app', 'Gender ID'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMeetingAttendances()
    {
        return $this->hasMany(TblMeetingAttendance::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAdditionDeductions()
    {
        return $this->hasMany(TblStaffAdditionDeduction::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAdditionDeductionHistories()
    {
        return $this->hasMany(TblStaffAdditionDeductionHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAttendances()
    {
        return $this->hasMany(TblStaffAttendance::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffAttendanceHistories()
    {
        return $this->hasMany(TblStaffAttendanceHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode()
    {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignationCode()
    {
        return $this->hasOne(TblDesignation::className(), ['designation_code' => 'designation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHamletCode()
    {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(TblGender::className(), ['gender_id' => 'gender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode()
    {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode()
    {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodGroup()
    {
        return $this->hasOne(TblBloodgroup::className(), ['blood_group_id' => 'blood_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode()
    {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode()
    {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode()
    {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCasteCategoryCode()
    {
        return $this->hasOne(TblCasteCategory::className(), ['caste_category_code' => 'caste_category_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCode()
    {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberDesignations()
    {
        return $this->hasMany(TblStaffMemberDesignation::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberLocals()
    {
        return $this->hasMany(TblStaffMemberLocal::className(), ['staffmember_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberLocalHistories()
    {
        return $this->hasMany(TblStaffMemberLocalHistory::className(), ['staffmember_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaries()
    {
        return $this->hasMany(TblStaffSalary::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryHistories()
    {
        return $this->hasMany(TblStaffSalaryHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryProcessings()
    {
        return $this->hasMany(TblStaffSalaryProcessing::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryProcessingHistories()
    {
        return $this->hasMany(TblStaffSalaryProcessingHistory::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @inheritdoc
     * @return TblStaffMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStaffMemberQuery(get_called_class());
    }
}
