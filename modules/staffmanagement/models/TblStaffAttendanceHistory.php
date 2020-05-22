<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_attendance_history".
 *
 * @property integer $id
 * @property string $staff_attendance_code
 * @property string $created_at
 * @property string $created_by
 * @property string $lwp_date
 * @property integer $lwp_type
 * @property string $remark
 * @property integer $salary_processed
 * @property string $updated_at
 * @property string $updated_by
 * @property string $staff_member_code
 * @property string $union_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblStaffAttendanceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_attendance_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_attendance_code', 'created_by', 'remark', 'updated_by', 'staff_member_code', 'union_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_by'], 'safe'],
            [['created_at', 'lwp_date', 'updated_at', 'history_created_at'], 'safe'],
            [['lwp_type', 'salary_processed', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_attendance_code' => Yii::t('app', 'Staff Attendance Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'lwp_date' => Yii::t('app', 'Lwp Date'),
            'lwp_type' => Yii::t('app', 'Lwp Type'),
            'remark' => Yii::t('app', 'Remark'),
            'salary_processed' => Yii::t('app', 'Salary Processed'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }
}
