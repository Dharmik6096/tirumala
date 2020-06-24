<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_installment".
 *
 * @property string $staff_installment_code
 * @property double $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $deduction_date
 * @property integer $installment_no
 * @property double $previous_due
 * @property boolean $salary_processed
 * @property string $updated_at
 * @property string $updated_by
 * @property string $staff_addition_deduction_no
 *
 * @property TblStaffAdditionDeduction $staffAdditionDeductionNo
 */
class TblStaffInstallment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['staff_installment_code'], 'required'],
            [['amount', 'deduction_date'], 'required'],
            [['amount', 'previous_due'], 'number'],
            [['created_at', 'deduction_date', 'updated_at', 'union_code', 'staff_addition_deduction_no'], 'safe'],
            [['installment_no'], 'integer'],
            [['salary_processed'], 'boolean'],
            [['staff_installment_code'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
//            [['staff_addition_deduction_no'], 'exist', 'skipOnError' => true, 'targetClass' => TblStaffAdditionDeduction::className(), 'targetAttribute' => ['staff_addition_deduction_no' => 'staff_addition_deduction_no']],
            [['salary_processed'], 'default', 'value' => 0],
            [['previous_due'], 'default', 'value' => 0],
            [['amount'], 'DateApplyValidate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_installment_code' => Yii::t('app', 'Staff Installment Code'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deduction_date' => Yii::t('app', 'Deduction Date'),
            'installment_no' => Yii::t('app', 'Installment No'),
            'previous_due' => Yii::t('app', 'Previous Due'),
            'salary_processed' => Yii::t('app', 'Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'staff_addition_deduction_no' => Yii::t('app', 'Staff Addition Deduction No'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffAdditionDeductionNo() {
        return $this->hasOne(TblStaffAdditionDeduction::className(), ['staff_addition_deduction_no' => 'staff_addition_deduction_no']);
    }

    /**
     * @inheritdoc
     * @return TblStaffInstallmentQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblStaffInstallmentQuery(get_called_class());
    }

    public function getStaffData() {
        return $this->find()
                        ->where(['staff_addition_deduction_no' => $this->staff_addition_deduction_no])
                        ->all();
    }

    public function DateApplyValidate($attribute, $params) {
        $tenureTo = Yii::$app->general->getmultiforeignkey($this->staffAdditionDeductionNo, ['staffMemberCode'], 'tenure_to_date');
        if (!empty($tenureTo) && !empty($this->deduction_date) && (date('Y-m-d', strtotime($this->deduction_date)) > $tenureTo)) {
            $this->addError('app_from_date', Yii::t('app/validation', 'Staff Addition Deduction Not Allowed.'));
            return false;
        }

        $fromDate = Yii::$app->general->getforeignkey($this->staffAdditionDeductionNo, 'app_from_date');
        $applyDate = !empty($fromDate) && $fromDate != 'N/A' ? date('d-m-Y', strtotime($fromDate)) : '';

        if (!empty($applyDate) && !empty($this->deduction_date) && ($this->deduction_date < $applyDate)) {
            $this->addError('deduction_date', Yii::t('app/validation', 'Month App From can not smaller than ' . $applyDate));
            return false;
        }
    }

    public function getStaffInstallment($no, $month) {
        $adddedData = $this->find()->where(['staff_addition_deduction_no' => $no, 'deduction_date' => $month])->all();
        $amount = 0;
        if (!empty($adddedData)) {
            foreach ($adddedData as $att) {
                $amount = $amount + $att->amount;
            }
        }
        return $amount;
    }

}
