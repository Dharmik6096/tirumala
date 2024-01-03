<?php

namespace app\modules\tms\models;

use Yii;

/**
 * This is the model class for table "tbl_user_attendance_detail".
 *
 * @property integer $attendance_detail_code
 * @property string $union_code
 * @property string $user_code
 * @property string $attendance_date
 * @property string $reference_type
 * @property string $reference_code
 * @property string $in_time
 * @property string $out_time
 * @property string $in_lat_long
 * @property string $out_lat_long
 * @property string $in_desc
 * @property string $out_desc
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
            [['attendance_detail_code', 'union_code', 'user_code', 'attendance_date', 'reference_type', 'reference_code', 'in_time', 'out_time', 'in_lat_long', 'out_lat_long', 'in_desc', 'out_desc', 'remarks'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
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
            'reference_type' => Yii::t('app', 'Reference Type'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'in_time' => Yii::t('app', 'In Time'),
            'out_time' => Yii::t('app', 'Out Time'),
            'in_lat_long' => Yii::t('app', 'In Lat Long'),
            'out_lat_long' => Yii::t('app', 'Out Lat Long'),
            'in_desc' => Yii::t('app', 'Check In Address'),
            'out_desc' => Yii::t('app', 'Check Out Address'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

}
