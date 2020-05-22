<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\globalmaster\models\TblSalaryHeads;

/**
 * This is the model class for table "tbl_staff_salary_process_transaction".
 *
 * @property string $salary_transaction_code
 * @property string $salary_code
 * @property integer $salary_head_code
 * @property integer $type_of_head
 * @property string $actual_value
 * @property string $value
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
class TblStaffSalaryProcessTransaction extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_salary_process_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['salary_transaction_code'], 'required'],
            [['salary_transaction_code', 'salary_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['salary_head_code', 'type_of_head', 'originating_type'], 'integer'],
            [['actual_value', 'value'], 'number'],
            [['actual_value', 'value'], 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'salary_transaction_code' => Yii::t('app', 'Salary Transaction Code'),
            'salary_code' => Yii::t('app', 'Salary Code'),
            'salary_head_code' => Yii::t('app', 'Salary Head'),
            'type_of_head' => Yii::t('app', 'Type Of Head'),
            'actual_value' => Yii::t('app', 'Actual Value'),
            'value' => Yii::t('app', 'Amount'),
            'union_code' => Yii::t('app', 'Union Code'),
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

    public function getSalaryHeadCode() {
        return $this->hasOne(TblSalaryHeads::className(), ['salary_head_code' => 'salary_head_code']);
    }

}
