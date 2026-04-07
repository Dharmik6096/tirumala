<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_voucher_sub_ledger_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $voucher_sub_ledger_code
 * @property string $amount
 * @property integer $credit_debit
 * @property string $narration
 * @property string $sub_ledger_code
 * @property string $voucher_code
 * @property string $voucher_transaction_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVoucherSubLedgerHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_voucher_sub_ledger_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'operation_type', 'history_created_by', 'created_by', 'updated_by', 'sub_ledger_code', 'originating_org_code', 'originating_org_type', 'voucher_sub_ledger_code', 'narration', 'voucher_code', 'voucher_transaction_code', 'created_at', 'updated_at', 'credit_debit', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'voucher_sub_ledger_code' => Yii::t('app', 'Voucher Sub Ledger Code'),
            'amount' => Yii::t('app', 'Amount'),
            'credit_debit' => Yii::t('app', 'Credit Debit'),
            'narration' => Yii::t('app', 'Narration'),
            'sub_ledger_code' => Yii::t('app', 'Sub Ledger Code'),
            'voucher_code' => Yii::t('app', 'Voucher Code'),
            'voucher_transaction_code' => Yii::t('app', 'Voucher Transaction Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
