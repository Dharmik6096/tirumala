<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_debit_bank_detail".
 *
 * @property integer $debit_bank_detail_code
 * @property integer $union_bank_payment_code
 * @property string $union_code
 * @property string $module_code
 * @property string $module_name
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property string $account_holder_name
 * @property string $bank_email
 * @property string $bank_mobile
 * @property string $mobile_no
 * @property string $email
 * @property integer $is_active
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
class TblDebitBankDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_debit_bank_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_bank_payment_code'], 'required'],
            [['union_bank_payment_code', 'is_active', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['union_code'], 'string', 'max' => 3],
            [['module_code'], 'string', 'max' => 25],
            [['module_name', 'branch_name'], 'string', 'max' => 100],
            [['branch_code'], 'string', 'max' => 9],
            [['ifsc', 'bank_account_no', 'account_holder_name', 'bank_email', 'bank_mobile', 'mobile_no', 'email', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['module_code'], 'unique', 'targetAttribute' => ['module_code', 'module_name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'debit_bank_detail_code' => Yii::t('app', 'Debit Bank Detail Code'),
            'union_bank_payment_code' => Yii::t('app', 'Union Bank Payment Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'module_code' => Yii::t('app', 'Module Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'account_holder_name' => Yii::t('app', 'Account Holder Name'),
            'bank_email' => Yii::t('app', 'Bank Email'),
            'bank_mobile' => Yii::t('app', 'Bank Mobile'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'is_active' => Yii::t('app', 'Is Active'),
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

}
