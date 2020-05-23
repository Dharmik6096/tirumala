<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\staffmanagement\models\TblStaffSalaryProcess;

/**
 * This is the model class for table "tbl_staff_attendance".
 *
 * @property integer $staff_attendance_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $lwp_date
 * @property integer $lwp_type
 * @property string $remark
 * @property integer $salary_processed
 * @property string $updated_at
 * @property string $created_by
 * @property string $staff_member_code
 * @property string $updated_by
 * @property string $union_code
 *
 * @property TblUnions $unionCode
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblDcs $dcsCode
 * @property TblUsers $createdBy
 * @property TblStaffMember $staffMemberCode
 */
class TblStaffAttendance extends \app\models\ChildModel {

    public $staff_member_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'lwp_date', 'updated_at', 'staff_member_name'], 'safe'],
            [['lwp_type'], 'integer'],
            [['lwp_date', 'staff_member_code', 'lwp_type', 'union_code'], 'required'],
            [['remark'], 'string', 'max' => 100],
            [['staff_member_code'], 'string', 'max' => 20],
            [['union_code'], 'string', 'max' => 3],
            [['lwp_date'], 'datevalidate'],
            ['lwp_date', 'unique', 'targetAttribute' => ['lwp_date', 'staff_member_code'], 'message' => Yii::t('app/validation', '{attribute} Can not Assign on same day.')],
            [['salary_processed'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_attendance_code' => Yii::t('app', 'Staff Attendance Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'lwp_date' => Yii::t('app', 'Date of LWP'),
            'lwp_type' => Yii::t('app', 'Type'),
            'remark' => Yii::t('app', 'Remark'),
            'salary_processed' => Yii::t('app', 'Salary Processed'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'staff_member_code' => Yii::t('app', 'Staff Member'),
            'staff_member_name' => Yii::t('app', 'Staff Member Name'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
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
    public function getCreatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffMemberCode() {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

    public function getSalaryProcessCode() {
        return $this->hasOne(TblStaffSalaryProcess::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @inheritdoc
     * @return TblStaffAttendanceQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffAttendanceQuery(get_called_class());
    }

    public function datevalidate($attribute, $params) {
        $date = date('Y-m', strtotime($this->lwp_date));
        $ddate = !empty($this->salaryProcessCode->month) ? date('Y-m', strtotime($this->salaryProcessCode->month)) : NULL;
        if (!empty($date) && !empty($this->salaryProcessCode->disbursement_date) && ($date <= $ddate)) {
            $this->addError($attribute, Yii::t('app/validation', 'Salary Disbursed'));
            return false;
        }

        if (!empty($date) && !empty($this->staffMemberCode->tenure_from_date) && ($this->lwp_date < $this->staffMemberCode->tenure_from_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date of LWP Must be Greater than Tenure From'));
            return false;
        }
    }

    public function getMemberAttendance($member) {
        if (!empty($member)) {
            $attendance = $this->find()->where(['staff_member_code' => $member])->all();
            $leave = 0;
            if (!empty($attendance)) {
                foreach ($attendance as $att) {
                    $leave = $leave + ($att->lwp_type == 1 ? 1 : 0.5);
                }
            }
            return $leave;
        }
    }

}
