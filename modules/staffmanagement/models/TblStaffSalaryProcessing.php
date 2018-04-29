<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\staffmanagement\models\TblStaffMember;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblSubCenter;
use app\modules\globalmaster\models\TblDesignation;
use app\modules\organisation\models\TblBranch;
use app\modules\globalmaster\models\TblSalaryHeads;

/**
 * This is the model class for table "tbl_staff_salary_processing".
 *
 * @property string $month
 * @property string $account_no
 * @property string $created_at
 * @property string $disbursement_date
 * @property integer $effective_working_days
 * @property string $flg_sentbox_entry
 * @property integer $is_delete
 * @property double $lwp
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property integer $type_of_head
 * @property string $updated_at
 * @property double $value
 * @property string $union_code
 * @property string $sub_center_code
 * @property string $staff_member_code
 * @property string $designation_code
 * @property string $dcs_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $created_by
 * @property string $salary_head_code
 * @property string $updated_by
 *
 * @property TblDcs $dcsCode
 * @property TblDesignation $designationCode
 * @property TblUsers $updatedBy
 * @property TblUnions $unionCode
 * @property TblBanks $bankCode
 * @property TblUsers $createdBy
 * @property TblSalaryHeads $salaryHeadCode
 * @property TblBranch $branchCode
 * @property TblSubCenter $subCenterCode
 * @property TblStaffMember $staffMemberCode
 */
class TblStaffSalaryProcessing extends \yii\db\ActiveRecord
{
    public $staff_member_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_salary_processing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['month', 'union_code', 'sub_center_code', 'staff_member_code', 'designation_code', 'dcs_code'], 'required'],
            [['created_at', 'disbursement_date', 'sync_timestamp', 'updated_at','staff_member_name'], 'safe'],
            [['effective_working_days', 'is_delete', 'type_of_head', 'designation_code'], 'integer'],
            [['lwp', 'value'], 'number'],
            [['month'], 'string', 'max' => 7],
            [['account_no', 'staff_member_code'], 'string', 'max' => 20],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['union_code'], 'string', 'max' => 3],
            [['sub_center_code'], 'string', 'max' => 11],
            [['dcs_code'], 'string', 'max' => 9],
            [['bank_code'], 'string', 'max' => 4],
            [['branch_code'], 'string', 'max' => 6],
            [['created_by', 'salary_head_code', 'updated_by'], 'string', 'max' => 10],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['designation_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDesignation::className(), 'targetAttribute' => ['designation_code' => 'designation_code']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['salary_head_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSalaryHeads::className(), 'targetAttribute' => ['salary_head_code' => 'salary_head_code']],
            [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
            [['staff_member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStaffMember::className(), 'targetAttribute' => ['staff_member_code' => 'staff_member_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'month' => Yii::t('app', 'Month'),
            'account_no' => Yii::t('app', 'Account No'),
            'created_at' => Yii::t('app', 'Created At'),
            'disbursement_date' => Yii::t('app', 'Disbursement Date'),
            'effective_working_days' => Yii::t('app', 'Effective Working Days'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'lwp' => Yii::t('app', 'Lwp'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'type_of_head' => Yii::t('app', 'Type Of Head'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'value' => Yii::t('app', 'Amount'),
            'union_code' => Yii::t('app', 'Union Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'designation_code' => Yii::t('app', 'Designation Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'created_by' => Yii::t('app', 'Created By'),
            'salary_head_code' => Yii::t('app', 'Salary Head Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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
    public function getDesignationCode()
    {
        return $this->hasOne(TblDesignation::className(), ['designation_code' => 'designation_code']);
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
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalaryHeadCode()
    {
        return $this->hasOne(TblSalaryHeads::className(), ['salary_head_code' => 'salary_head_code']);
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
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffMemberCode()
    {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @inheritdoc
     * @return TblStaffSalaryProcessingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStaffSalaryProcessingQuery(get_called_class());
    }
}
