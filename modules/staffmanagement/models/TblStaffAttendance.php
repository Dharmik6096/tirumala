<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_staff_attendance".
 *
 * @property integer $staff_attendance_code
 * @property string $created_at
 * @property string $deleted_at
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
 * @property TblSubCenter $subCenterCode
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
            [['created_at', 'deleted_at', 'lwp_date', 'updated_at', 'staff_member_name'], 'safe'],
            [['lwp_type'], 'integer'],
            [['lwp_date', 'staff_member_code', 'lwp_type'], 'required'],
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
            'deleted_at' => Yii::t('app', 'Deleted At'),
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

    /**
     * @inheritdoc
     * @return TblStaffAttendanceQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffAttendanceQuery(get_called_class());
    }

    public function datevalidate($attribute, $params) {

        if (!empty($this->lwp_date) && !empty($this->staffMemberCode->tenure_from_date) && ($this->lwp_date < $this->staffMemberCode->tenure_from_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date of LWP Must be Greater than Tenure From'));
            return false;
        }
    }

    public function getCodeWeb($dcs) {
        $len = strlen($dcs);
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`staff_attendance_code` FROM " . $len . " +1)) AS UNSIGNED)) as staff_attendance_code")
                ->from('tbl_staff_attendance')
                ->where('(CAST(trim(SUBSTRING(staff_attendance_code, 1,7)) AS UNSIGNED))="' . trim($dcs) . '"')
                ->one();
        $code = (int) $val['staff_attendance_code'] + 1;
        $value = $dcs . str_pad($code, 3, '0', STR_PAD_LEFT);
        return $value;
    }

}
