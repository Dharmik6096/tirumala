<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\staffmanagement\models\TblStaffMember;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use app\modules\globalmaster\models\TblDesignation;
use app\modules\staffmanagement\models\TblStaffSalary;
use app\modules\organisation\models\TblUnions;
use app\modules\staffmanagement\models\TblStaffSalaryHoldDue;

/**
 * This is the model class for table "tbl_staff_salary_process".
 *
 * @property string $salary_code
 * @property string $staff_member_code
 * @property string $actual_value
 * @property string $value
 * @property string $disbursement_date
 * @property integer $effective_working_days
 * @property string $lwp
 * @property string $month
 * @property integer $designation_code
 * @property string $account_no
 * @property string $bank_code
 * @property string $branch_code
 * @property string $union_code
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
class TblStaffSalaryProcess extends \app\models\ChildModel {

    public $salary, $Status, $net_payable;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_salary_process';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['month', 'union_code'], 'required', 'on' => ['process']],
            [['month', 'disbursement_date', 'union_code'], 'required', 'on' => ['disburse']],
            [['salary_code', 'staff_member_code', 'account_no', 'bank_code', 'branch_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['actual_value', 'value', 'lwp'], 'number'],
            [['disbursement_date', 'month', 'created_at', 'updated_at', 'salary', 'Status', 'previous_hold', 'previous_due', 'hold_amount', 'additional_pay'], 'safe'],
            [['effective_working_days', 'designation_code', 'originating_type'], 'integer'],
            [['month'], 'validateProcess', 'on' => ['process']],
            [['month'], 'validateDisburseDate', 'on' => ['disburse']],
            [['previous_hold', 'previous_due', 'hold_amount', 'additional_pay'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'salary_code' => Yii::t('app', 'Salary Code'),
            'staff_member_code' => Yii::t('app', 'Staff Member'),
            'actual_value' => Yii::t('app', 'Actual Value'),
            'value' => Yii::t('app', 'Amount'),
            'disbursement_date' => Yii::t('app', 'Disbursement Date'),
            'effective_working_days' => Yii::t('app', 'Working Days'),
            'lwp' => Yii::t('app', 'No. of leave'),
            'month' => Yii::t('app', 'Month'),
            'designation_code' => Yii::t('app', 'Designation'),
            'account_no' => Yii::t('app', 'Account No'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'union_code' => Yii::t('app', 'Union'),
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
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getStaffMemberCode() {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    public function getDesignationCode() {
        return $this->hasOne(TblDesignation::className(), ['designation_code' => 'designation_code']);
    }

    public function getExistData($memberCode) {
        return $member = $this->find()->where(['staff_member_code' => $memberCode, 'union_code' => $this->union_code, 'disbursement_date' => NULL])->one();
    }

    public function validateProcess($attribute, $params) {
        $currentDate = date('Y-m-d');
        $month = $this->month;
        if ($month > $currentDate) {
            $this->addError($attribute, "Future month salary process not allow.");
        }
        $process = $this->checkProcess();
        if ($process == true) {
            $this->addError($attribute, Yii::t('app/validation', 'Not allow to process salary of this month, As post month salary has been processed.'));
        }
        $disburse = $this->checkDisburse();
        if ($disburse == true) {
            $this->addError($attribute, Yii::t('app/validation', 'Salary Already Disbursed.'));
        }
        $memberModel = new TblStaffSalary();
        $memberData = $memberModel->getStaffMember($month, $this->union_code);
        if (empty($memberData)) {
            $this->addError($attribute, "Missing salary for staff member.");
        }
        $Exist = $this->find()->where(['union_code' => $this->union_code])->andWhere(['<', 'month', $this->month])->orderBy('month desc')->one();

        if (!empty($Exist->month)) {
            $fDate = date_create($month);
            $tDate = date_create($Exist->month);
            $diff = date_diff($fDate, $tDate);
            $count = $diff->format("%a");
            if ($count > 31) {
                $this->addError($attribute, Yii::t('app/validation', 'You cannot jump the month to process salary.'));
            }
        }
    }

    public function checkProcess() {
        $count = $this->find()
                ->where(['union_code' => $this->union_code])
                ->andWhere(['>', 'month', $this->month])
                ->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function checkDisburse() {
        $count = $this->find()
                ->where(['union_code' => $this->union_code])
                ->andWhere(['=', 'month', $this->month])
                ->andWhere(['!=', 'disbursement_date', ''])
                ->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function validateDisburseDate($attribute, $params) {
        if ($this->month > $this->disbursement_date) {
            $this->addError($attribute, Yii::t('app/validation', 'Disbursement Date greater than month.'));
        }
    }

    public function getHoldDue() {
        return $this->hasOne(TblStaffSalaryHoldDue::className(), ['staff_member_code' => 'staff_member_code']);
    }

}
