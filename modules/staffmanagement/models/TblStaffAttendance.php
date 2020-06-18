<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\staffmanagement\models\TblStaffSalaryProcess;
use app\modules\staffmanagement\models\TblStaffLeaveMaster;

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

    public $staff_member_name, $leave_for;

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
            [['created_at', 'leave_from', 'updated_at', 'staff_member_name', 'leave_from', 'leave_to', 'leave_count', 'leave_type', 'leave_for'], 'safe'],
            [['lwp_type'], 'integer'],
            [['leave_from', 'staff_member_code', 'lwp_type', 'union_code', 'leave_type'], 'required'],
            [['remark'], 'string', 'max' => 100],
            [['staff_member_code'], 'string', 'max' => 20],
            [['union_code'], 'string', 'max' => 3],
            [['leave_from'], 'datevalidate'],
            ['lwp_date', 'unique', 'targetAttribute' => ['lwp_date', 'staff_member_code'], 'message' => Yii::t('app/validation', '{attribute} Can not Assign on same day.')],
            [['salary_processed'], 'default', 'value' => 0],
            [['leave_to'], 'required', 'when' => function ($model) {
                    return $model->lwp_type != 0;
                }, 'whenClient' => "function (attribute, value) {
              return $('#tblstaffattendance-lwp_type').val() != '0';
          }"],
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
        return $this->hasOne(TblStaffSalaryProcess::className(), ['staff_member_code' => 'staff_member_code'])->andOnCondition(['IS NOT', 'disbursement_date', NULL])->orderBy(['month' => SORT_DESC]);
    }

    /**
     * @inheritdoc
     * @return TblStaffAttendanceQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffAttendanceQuery(get_called_class());
    }

    public function datevalidate($attribute, $params) {
        $date = date('Y-m', strtotime($this->leave_from));
        $ddate = !empty($this->salaryProcessCode->month) ? date('Y-m', strtotime($this->salaryProcessCode->month)) : NULL;
        if (!empty($date) && !empty($this->salaryProcessCode->disbursement_date) && ($date <= $ddate)) {
            $this->addError($attribute, Yii::t('app/validation', 'Salary Disbursed'));
            return false;
        }

        if (!empty($date) && !empty($this->staffMemberCode->tenure_from_date) && ($this->leave_from < $this->staffMemberCode->tenure_from_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date of LWP Must be Greater than Tenure From'));
            return false;
        }
        $dateData = $this->find()
//                ->where('union_code=\'' . $this->union_code . '\' and staff_member_code=\'' . $this->staff_member_code . '\'  and  (leave_from != \'' . $this->leave_from . '\' OR  leave_to != \'' . $this->leave_to . '\')')
                ->where('union_code=\'' . $this->union_code . '\' and staff_member_code=\'' . $this->staff_member_code . '\'')
                ->andWhere('((\'' . $this->leave_from . '\'  between leave_from and leave_to) OR (\'' . $this->leave_to . '\' between leave_from  and leave_to) OR (leave_from between \'' . $this->leave_from . '\' and  \'' . $this->leave_to . '\') OR (leave_to between \'' . $this->leave_from . '\' and \'' . $this->leave_to . '\'))')
                ->andfilterWhere(['!=', 'staff_attendance_code', $this->staff_attendance_code])
                ->all();

        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
        if ($this->leave_from > $this->leave_to) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
        $dayDiff = Yii::$app->general->getDateDifference($this->leave_from, $this->leave_to);

        $tenure_from = date_create($this->leave_from);
        $tenure_to = date_create($this->leave_to);
        $diff = date_diff($tenure_to, $tenure_from);
        $dayDiff = $diff->format("%a");

        $dayDiff = $dayDiff + 1;
        if ($dayDiff > 7 || $dayDiff < 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
        if ($this->lwp_type == '0') {
            $this->leave_for = Yii::$app->general->getforeignkey($this->staffMemberCode, 'is_on_role');
            $is_half = Yii::$app->general->getforeignkey($this->leaveData, 'is_half');
            if ($is_half != 1) {
                $this->addError('lwp_type', Yii::t('app/validation', 'Type is invalid'));
                return false;
            }
            $this->leave_count = '0.5';
        } else {
            $this->leave_count = $dayDiff;
        }
    }

    public function getLeaveData() {
        return $this->hasOne(TblStaffLeaveMaster::className(), ['leave_type' => 'leave_type', 'union_code' => 'union_code'])->andwhere(['leave_for' => $this->leave_for]);
    }

    public function getMemberAttendance($member, $month) {
        if (!empty($member) && !empty($month)) {
            $from_date = date('Y-m-d', strtotime($month));
            $to_date = date('Y-m-d', strtotime("+1 month", strtotime($from_date)));
            $attendance = $this->find()->where(['staff_member_code' => $member, 'leave_type' => '5'])
                    ->andWhere(['and', ['>=', 'leave_from', $month], ['<', 'leave_to', $to_date]])
                    ->all();
            $leave = 0;
            if (!empty($attendance)) {
                foreach ($attendance as $att) {
                    $leave = $leave + ($att->leave_count);
                }
            }
            return $leave;
        }
    }

    public function salaryDisburse() {
        $modelSalary = new TblStaffSalaryProcess();
        $count = $modelSalary->find()
                ->where(['union_code' => $this->union_code, 'staff_member_code' => $this->staff_member_code])
                ->andWhere(['>=', 'month', $this->leave_from])
                ->andWhere(['IS NOT', 'disbursement_date', NULL])
                ->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

}
