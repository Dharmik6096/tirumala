<?php

namespace app\modules\tms\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\usermanagement\models\User;
use app\modules\general\models\TblAttachment;

/**
 * This is the model class for table "tbl_user_attendance_detail".
 *
 * @property integer $attendance_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $user_code
 * @property string $attendance_date
 * @property string $in_time
 * @property string $out_time
 * @property string $day_count
 * @property string $in_lat_long
 * @property string $out_lat_long
 * @property string $in_desc
 * @property string $out_desc
 * @property string $status
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblUserAttendanceDetail extends \app\models\ChildModel {
//    public $mobile_no, $Attendance_hours;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_attendance_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['attendance_date', 'in_time', 'out_time', 'created_at', 'updated_at', 'reference_type', 'reference_code'], 'safe'],
            [['day_count'], 'number'],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 20],
            [['user_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['in_lat_long', 'out_lat_long', 'in_desc', 'out_desc', 'remarks'], 'string', 'max' => 255],
            [['attendance_date', 'user_code'], 'unique', 'targetAttribute' => ['attendance_date', 'user_code'], 'message' => 'The combination of User Code and Attendance Date has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'attendance_detail_code' => Yii::t('app', 'Attendance Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'user_code' => Yii::t('app', 'User Name'),
            'attendance_date' => Yii::t('app', 'Attendance Date'),
            'in_time' => Yii::t('app', 'In Time'),
            'out_time' => Yii::t('app', 'Out Time'),
            'day_count' => Yii::t('app', 'Day Count'),
            'in_lat_long' => Yii::t('app', 'In Lat Long'),
            'out_lat_long' => Yii::t('app', 'Out Lat Long'),
            'in_desc' => Yii::t('app', 'Check In Address'),
            'out_desc' => Yii::t('app', 'Check Out Address'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'reference_type' => Yii::t('app', 'Reference Type'),
            'reference_code' => Yii::t('app', 'Reference Code'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::class, ['union_code' => 'union_code']);
    }

    public function getAttachment() {
        return $this->hasOne(TblAttachment::class, ['module_code' => 'attendance_code']);
    }

    public function getUserAttendanceDetailList($user_code, $attendance_date) {
        $attendanceDetailCodes = [];
        $data = $this->find()
                ->where(['user_code' => $user_code, 'attendance_date' => $attendance_date])
                ->all();
        foreach ($data as $record) {
            $attendanceDetailCodes[] = $record->attendance_detail_code;
        }
        return $attendanceDetailCodes;
    }

}
