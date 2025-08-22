<?php

namespace app\modules\tms\models;

use Yii;
use app\models\ChildModel;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_user_attendance_regularization".
 *
 * @property integer $regularization_code
 * @property string $attendance_code
 * @property string $user_code
 * @property string $apply_date
 * @property string $regularization_reason
 * @property string $status
 * @property string $requested_in_time
 * @property string $requested_out_time
 * @property string $approved_by
 * @property string $approved_date
 * @property string $rejected_by
 * @property string $rejected_date
 * @property string $rejection_remark
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
class TblUserAttendanceRegularization extends ChildModel {

    public $actual_in_time, $actual_out_time, $process_approval_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_attendance_regularization';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['apply_date', 'requested_in_time', 'requested_out_time', 'approved_date', 'rejected_date', 'created_at', 'updated_at', 'actual_in_time', 'actual_out_time', 'process_approval_code'], 'safe'],
            [['regularization_reason', 'rejection_remark'], 'string', 'max' => 255],
            [['approved_by', 'rejected_by', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['rejection_remark'], 'required', 'on' => 'attendanceRegularization'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'regularization_code' => Yii::t('app', 'Regularization Code'),
            'attendance_code' => Yii::t('app', 'Attendance Code'),
            'user_code' => Yii::t('app', 'User Code'),
            'apply_date' => Yii::t('app', 'Apply Date'),
            'regularization_reason' => Yii::t('app', 'Regularization Reason'),
            'status' => Yii::t('app', 'Status'),
            'requested_in_time' => Yii::t('app', 'Requested In Time'),
            'requested_out_time' => Yii::t('app', 'Requested Out Time'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approved_date' => Yii::t('app', 'Approved Date'),
            'rejected_by' => Yii::t('app', 'Rejected By'),
            'rejected_date' => Yii::t('app', 'Rejected Date'),
            'rejection_remark' => Yii::t('app', 'Rejection Remark'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'actual_in_time' => Yii::t('app', 'Actual In Time'),
            'actual_out_time' => Yii::t('app', 'Actual Out Time'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::class, ['id' => 'user_code']);
    }

    public function getApprovedUserCode() {
        return $this->hasOne(User::class, ['id' => 'approved_by']);
    }

    public function getRejectUserCode() {
        return $this->hasOne(User::class, ['id' => 'rejected_by']);
    }

    public function getUserAttendance() {
        return $this->hasOne(TblUserAttendance::class, ['attendance_date' => 'apply_date', 'user_code' => 'user_code']);
    }

    public function getUserAttendanceData() {
        $userAttendance = TblUserAttendanceRegularization::findOne(['regularization_code' => $this->regularization_code]);
        return TblUserAttendance::find()
                        ->where(['attendance_date' => $userAttendance->apply_date, 'user_code' => $userAttendance->user_code])
                        ->one();
    }

}
