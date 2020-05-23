<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\staffmanagement\models\TblStaffSalaryProcess;

/**
 * This is the model class for table "tbl_staff_addition_deduction".
 *

 * @property double $amount
 * @property string $app_from_date
 * @property string $created_at
 * @property integer $installment_no
 * @property integer $is_active
 * @property string $remark
 * @property string $tr_date
 * @property integer $type
 * @property string $updated_at
 * @property string $created_by
 * @property string $staff_member_code
 * @property string $union_code
 * @property string $updated_by
 *
 * @property TblUnions $unionCode
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblStaffMember $staffMemberCode
 * @property TblSubCenter $subCenterCode
 * @property TblStaffInstallment[] $tblStaffInstallments
 * @property TblStaffInstallmentHistory[] $tblStaffInstallmentHistories
 */
class TblStaffAdditionDeduction extends \app\models\ChildModel {

    public $staff_member_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_addition_deduction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['amount'], 'number'],
            [['app_from_date', 'tr_date', 'staff_member_code', 'amount', 'installment_no', 'type', 'union_code'], 'required'],
            [['created_at', 'staff_addition_deduction_no', 'tr_date', 'updated_at', 'staff_member_name', 'installment_no'], 'safe'],
            [['type'], 'integer'],
            [['app_from_date'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 200],
            [['created_by', 'updated_by'], 'string', 'max' => 10],
            [['staff_member_code'], 'string', 'max' => 20],
            [['union_code'], 'string', 'max' => 3],
            [['is_active'], 'default', 'value' => 1],
            [['amount', 'installment_no'], 'string', 'min' => 1],
            [['staff_member_code'], 'memberJoinDate'],
            [['amount', 'installment_no'], 'double', 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'amount' => Yii::t('app', 'Amount'),
            'app_from_date' => Yii::t('app', 'Month App From'),
            'created_at' => Yii::t('app', 'Created At'),
            'installment_no' => Yii::t('app', 'Nos.of Installment'),
            'is_active' => Yii::t('app', 'Is Active'),
            'remark' => Yii::t('app', 'Remark'),
            'staff_addition_deduction_no' => Yii::t('app', 'Staff Addition Deduction No'),
            'tr_date' => Yii::t('app', 'Txn Date'),
            'type' => Yii::t('app', 'Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'staff_member_code' => Yii::t('app', 'Staff Member'),
            'union_code' => Yii::t('app', 'Union Name'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'staff_member_name' => Yii::t('app', 'Staff Member Name'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffInstallments() {
        return $this->hasMany(TblStaffInstallment::className(), ['staff_addition_deduction_no' => 'staff_addition_deduction_no']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffInstallmentHistories() {
        return $this->hasMany(TblStaffInstallmentHistory::className(), ['staff_addition_deduction_no' => 'staff_addition_deduction_no']);
    }

    /**
     * @inheritdoc
     * @return TblStaffAdditionDeductionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffAdditionDeductionQuery(get_called_class());
    }

    public function memberJoinDate($attribute, $params) {
        $date = date('Y-m', strtotime($this->app_from_date));
        $ddate = !empty($this->salaryProcessCode->month) ? date('Y-m', strtotime($this->salaryProcessCode->month)) : NULL;
        if (!empty($date) && !empty($this->salaryProcessCode->disbursement_date) && ($date <= $ddate)) {
            $this->addError($attribute, Yii::t('app/validation', 'Salary Disbursed'));
            return false;
        }


        $member = $this->staffMemberCode->tenure_from_date;

        if (!empty($member) && !empty($this->app_from_date) && ($this->app_from_date < $member)) {
            $this->addError('app_from_date', Yii::t('app/validation', 'Month App From not in Tenure Date'));
            return false;
        }
    }

    public function getStaffAddDed($member) {
        return $this->find()->where(['staff_member_code' => $member, 'is_active' => 1])->all();
    }

    public function getSalaryProcessCode() {
        return $this->hasOne(TblStaffSalaryProcess::className(), ['staff_member_code' => 'staff_member_code']);
    }

}
