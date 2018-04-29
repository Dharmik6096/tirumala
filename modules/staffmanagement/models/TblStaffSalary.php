<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\staffmanagement\models\TblStaffMember;
/**
 * This is the model class for table "tbl_staff_salary".
 *
 * @property string $staff_salary_code
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property integer $is_active
 * @property integer $is_delete
 * @property integer $net_pay
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $staff_member_code
 * @property string $sub_center_code
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblStaffMember $staffMemberCode
 * @property TblSubCenter $subCenterCode
 * @property TblDcs $dcsCode
 * @property TblUsers $deletedBy
 * @property TblStaffSalaryTransaction[] $tblStaffSalaryTransactions
 * @property TblStaffSalaryTransactionHistory[] $tblStaffSalaryTransactionHistories
 */
class TblStaffSalary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_salary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_salary_code'], 'required'],
            [['created_at', 'deleted_at', 'sync_timestamp', 'updated_at', 'wef_date'], 'safe'],
            [['is_active', 'is_delete', 'net_pay'], 'integer'],
            [['staff_salary_code'], 'string', 'max' => 16],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 10],
            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],
            [['staff_member_code'], 'string', 'max' => 20],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
            [['staff_member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStaffMember::className(), 'targetAttribute' => ['staff_member_code' => 'staff_member_code']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['deleted_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_salary_code' => Yii::t('app', 'Staff Salary Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'net_pay' => Yii::t('app', 'Net Pay'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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
    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffMemberCode()
    {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
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
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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
    public function getTblStaffSalaryTransactions()
    {
        return $this->hasMany(TblStaffSalaryTransaction::className(), ['staff_salary_code' => 'staff_salary_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryTransactionHistories()
    {
        return $this->hasMany(TblStaffSalaryTransactionHistory::className(), ['staff_salary_code' => 'staff_salary_code']);
    }

    /**
     * @inheritdoc
     * @return TblStaffSalaryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStaffSalaryQuery(get_called_class());
    }
}
