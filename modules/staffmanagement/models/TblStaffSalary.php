<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\staffmanagement\models\TblStaffMember;
use app\models\TblUsers;

/**
 * This is the model class for table "tbl_staff_salary".
 *
 * @property string $staff_salary_code
 * @property string $addition
 * @property string $deduction
 * @property string $created_at
 * @property integer $is_active
 * @property integer $net_pay
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
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
class TblStaffSalary extends \app\models\ChildModel {

    public $total_value;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_salary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_salary_code', 'wef_date', 'staff_member_code'], 'required'],
            [['created_at', 'updated_at', 'deduction', 'wef_date', 'addition', 'total_value', 'union_code'], 'safe'],
            [['staff_salary_code'], 'string', 'max' => 17],
            [['staff_member_code'], 'string', 'max' => 20],
            [['staff_member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStaffMember::className(), 'targetAttribute' => ['staff_member_code' => 'staff_member_code']],
            ['staff_member_code', 'unique', 'targetAttribute' => ['staff_member_code', 'wef_date'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['wef_date'], 'memberJoinDate'],
            [['wef_date'], 'validatePreDate'],
            [['addition', 'deduction'], 'default', 'value' => 0],
            [['total_value'], 'number'],
            [['total_value'], 'double', 'min' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_salary_code' => Yii::t('app', 'Staff Salary Code'),
            'addition' => Yii::t('app', 'Earning'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'net_pay' => Yii::t('app', 'Net Pay'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Name'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'total_value' => Yii::t('app', 'Value'),
        ];
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
    public function getUpdatedBy() {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffMemberCode() {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode() {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryTransactions() {
        return $this->hasMany(TblStaffSalaryTransaction::className(), ['staff_salary_code' => 'staff_salary_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffSalaryTransactionHistories() {
        return $this->hasMany(TblStaffSalaryTransactionHistory::className(), ['staff_salary_code' => 'staff_salary_code']);
    }

    /**
     * @inheritdoc
     * @return TblStaffSalaryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffSalaryQuery(get_called_class());
    }

    public function getSalaryData($code, $date) {
        $data = $this->find()
                ->where(['staff_member_code' => $code])
                ->andWhere(['<', 'wef_date', $date])
                ->orderBy('wef_date desc')
                ->one();
        return $data;
    }

    public function getCodeWeb($staff) {
        $len = strlen($staff);
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`staff_salary_code` FROM " . $len . " +1)) AS UNSIGNED)) as staff_salary_code")
                ->from('tbl_staff_salary')
                ->where('(CAST(trim(SUBSTRING(staff_salary_code, 1,' . $len . ')) AS UNSIGNED))="' . trim($staff) . '"')
                ->one();
        $code = (int) $val['staff_salary_code'] + 1;

        $value = $staff . str_pad($code, 4, '0', STR_PAD_LEFT);
        return $value;
    }

    public function disableEdit() {
        $count = $this->find()
                ->where(['union_code' => $this->union_code, 'staff_member_code' => $this->staff_member_code])
                ->andWhere(['!=', 'staff_salary_code', $this->staff_salary_code])
                ->andWhere(['>=', 'wef_date', $this->wef_date])
                ->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function memberJoinDate($attribute, $params) {
        $member = $this->staffMemberCode->tenure_from_date;
        if (!empty($member) && !empty($this->wef_date) && ($this->wef_date < $member)) {
            $this->addError($attribute, Yii::t('app/validation', 'Salary before Joining is not allow'));
            return false;
        }
    }

    public function validatePreDate($attribute, $params) {
        $disable = $this->disableEdit();
        if ($disable == true) {
            $this->addError($attribute, Yii::t('app/validation', 'WTF Date must be greater than last Date'));
            return false;
        }
    }

}
