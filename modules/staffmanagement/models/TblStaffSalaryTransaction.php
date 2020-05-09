<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_salary_transaction".
 *
 * @property string $staff_salary_transaction_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $lwp_effect
 * @property string $updated_at
 * @property string $updated_by
 * @property string $value
 * @property integer $salary_head_code
 * @property string $staff_salary_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $union_code
 */
class TblStaffSalaryTransaction extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_salary_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_salary_transaction_code'], 'required'],
            [['staff_salary_transaction_code', 'created_by', 'updated_by', 'staff_salary_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code'], 'string'],
            [['created_at', 'updated_at', 'union_code'], 'safe'],
            [['lwp_effect', 'salary_head_code', 'originating_type'], 'integer'],
            [['value'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_salary_transaction_code' => Yii::t('app', 'Staff Salary Transaction Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'lwp_effect' => Yii::t('app', 'Lwp Effect'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'value' => Yii::t('app', 'Value'),
            'salary_head_code' => Yii::t('app', 'Salary Head Code'),
            'staff_salary_code' => Yii::t('app', 'Staff Salary Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    public function getSalaryTrans($salaryCode, $salary_head_code) {
        return $this->find()->select(['salary_head_code', 'value', 'lwp_effect', 'staff_salary_transaction_code'])
                        ->where(['staff_salary_code' => $salaryCode, 'salary_head_code' => $salary_head_code])->one();
    }

}
