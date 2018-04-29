<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_staff_attendance".
 *
 * @property integer $staff_attendance_id
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $lwp_date
 * @property integer $lwp_type
 * @property string $remark
 * @property integer $salary_processed
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $staff_member_code
 * @property string $updated_by
 * @property string $dcs_code
 * @property string $sub_center_code
 * @property string $union_code
 *
 * @property TblUnions $unionCode
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblDcs $dcsCode
 * @property TblUsers $createdBy
 * @property TblSubCenter $subCenterCode
 * @property TblStaffMember $staffMemberCode
 */
class TblStaffAttendance extends \yii\db\ActiveRecord
{
    public $staff_member_name;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at', 'lwp_date', 'sync_timestamp', 'updated_at','staff_member_name'], 'safe'],
            [['is_active', 'is_delete', 'lwp_type', 'salary_processed'], 'integer'],
            [['lwp_date', 'staff_member_code'], 'required'],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 10],
            [['staff_member_code'], 'string', 'max' => 20],
            [['dcs_code'], 'string', 'max' => 9],
            [['sub_center_code'], 'string', 'max' => 11],
            [['union_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_attendance_id' => Yii::t('app', 'Staff Attendance ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'lwp_date' => Yii::t('app', 'Lwp Date'),
            'lwp_type' => Yii::t('app', 'Lwp Type'),
            'remark' => Yii::t('app', 'Remark'),
            'salary_processed' => Yii::t('app', 'Salary Processed'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
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
    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
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
     * @return TblStaffAttendanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStaffAttendanceQuery(get_called_class());
    }
}
