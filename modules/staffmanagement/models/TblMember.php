<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_member".
 *
 * @property string $member_code
 * @property string $account_no
 * @property string $created_at
 * @property double $credit_limit
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property string $ifsc
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $member_img
 * @property string $member_name
 * @property string $nominee_name
 * @property integer $payment_mode
 * @property string $pincode
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $bank_code
 * @property string $branch_code
 * @property integer $caste_category_code
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $district_code
 * @property string $federation_code
 * @property integer $gender_id
 * @property string $hamlet_code
 * @property integer $member_type_id
 * @property integer $milk_quality_type_code
 * @property string $state_code
 * @property string $sub_center_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $updated_by
 * @property string $village_code
 *
 * @property TblUsers $updatedBy
 * @property TblDcs $dcsCode
 * @property TblVillages $villageCode
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblDistricts $districtCode
 * @property TblMemberTypes $memberType
 * @property TblSubCenter $subCenterCode
 * @property TblFederations $federationCode
 * @property TblUnions $unionCode
 * @property TblSubDistricts $subDistrictCode
 * @property TblMilkQualityType $milkQualityTypeCode
 * @property TblHamlets $hamletCode
 * @property TblCasteCategory $casteCategoryCode
 * @property TblGender $gender
 * @property TblStates $stateCode
 * @property TblBranch $branchCode
 * @property TblBanks $bankCode
 * @property TblMemberInformation[] $tblMemberInformations
 * @property TblMemberInformation[] $tblMemberInformations0
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories0
 * @property TblMemberLocal[] $tblMemberLocals
 * @property TblMemberLocalHistory[] $tblMemberLocalHistories
 * @property TblStaffMember[] $tblStaffMembers
 * @property TblStaffMemberHistory[] $tblStaffMemberHistories
 */
class TblMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_code'], 'required'],
            [['created_at', 'deleted_at', 'sync_timestamp', 'updated_at'], 'safe'],
            [['credit_limit'], 'number'],
            [['is_active', 'is_delete', 'payment_mode', 'caste_category_code', 'gender_id', 'member_type_id', 'milk_quality_type_code'], 'integer'],
            [['member_code'], 'string', 'max' => 255],
            [['account_no'], 'string', 'max' => 20],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['ifsc', 'created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 10],
            [['member_img', 'member_name', 'nominee_name'], 'string', 'max' => 100],
            [['pincode', 'branch_code', 'village_code'], 'string', 'max' => 6],
            [['bank_code'], 'string', 'max' => 4],
            [['dcs_code'], 'string', 'max' => 9],
            [['district_code', 'union_code'], 'string', 'max' => 3],
            [['federation_code', 'state_code'], 'string', 'max' => 2],
            [['hamlet_code'], 'string', 'max' => 9, 'min' => 8],
            [['sub_center_code'], 'string', 'max' => 11],
            [['sub_district_code'], 'string', 'max' => 5],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['deleted_by' => 'user_id']],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
            [['member_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblMemberTypes::className(), 'targetAttribute' => ['member_type_id' => 'member_type_id']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
            [['federation_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblFederations::className(), 'targetAttribute' => ['federation_code' => 'federation_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
            [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code']],
            [['hamlet_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHamlets::className(), 'targetAttribute' => ['hamlet_code' => 'hamlet_code']],
            [['caste_category_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCasteCategory::className(), 'targetAttribute' => ['caste_category_code' => 'caste_category_code']],
            [['gender_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblGender::className(), 'targetAttribute' => ['gender_id' => 'gender_id']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
            [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code']],
            [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_code' => Yii::t('app', 'Member Code'),
            'account_no' => Yii::t('app', 'Account No'),
            'created_at' => Yii::t('app', 'Created At'),
            'credit_limit' => Yii::t('app', 'Credit Limit'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'member_img' => Yii::t('app', 'Member Img'),
            'member_name' => Yii::t('app', 'Member Name'),
            'nominee_name' => Yii::t('app', 'Nominee Name'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'pincode' => Yii::t('app', 'Pincode'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'caste_category_code' => Yii::t('app', 'Caste Category Code'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'district_code' => Yii::t('app', 'District Code'),
            'federation_code' => Yii::t('app', 'Federation Code'),
            'gender_id' => Yii::t('app', 'Gender ID'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'member_type_id' => Yii::t('app', 'Member Type ID'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village Code'),
        ];
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
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
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
    public function getDistrictCode()
    {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberType()
    {
        return $this->hasOne(TblMemberTypes::className(), ['member_type_id' => 'member_type_id']);
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
    public function getFederationCode()
    {
        return $this->hasOne(TblFederations::className(), ['federation_code' => 'federation_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
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
    public function getMilkQualityTypeCode()
    {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
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
    public function getCasteCategoryCode()
    {
        return $this->hasOne(TblCasteCategory::className(), ['caste_category_code' => 'caste_category_code']);
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
    public function getStateCode()
    {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
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
    public function getBankCode()
    {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations()
    {
        return $this->hasMany(TblMemberInformation::className(), ['member_family_reference_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations0()
    {
        return $this->hasMany(TblMemberInformation::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories0()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['member_family_reference_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberLocals()
    {
        return $this->hasMany(TblMemberLocal::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberLocalHistories()
    {
        return $this->hasMany(TblMemberLocalHistory::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMembers()
    {
        return $this->hasMany(TblStaffMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberHistories()
    {
        return $this->hasMany(TblStaffMemberHistory::className(), ['member_code' => 'member_code']);
    }

    /**
     * @inheritdoc
     * @return TblMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMemberQuery(get_called_class());
    }
}
