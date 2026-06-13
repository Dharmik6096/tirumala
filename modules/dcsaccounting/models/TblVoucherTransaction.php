<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\dcsaccounting\models\TblLedgers;

/**
 * This is the model class for table "tbl_voucher_transaction".
 *
 * @property string $voucher_transaction_code
 * @property string $amount
 * @property integer $credit_debit
 * @property string $narration
 * @property string $ledger_code
 * @property string $voucher_code
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
class TblVoucherTransaction extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_voucher_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'updated_at', 'credit_debit', 'originating_type', 'voucher_transaction_code', 'narration', 'voucher_code', 'ledger_code', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'auto_posted_screen'], 'safe'],
                [['voucher_transaction_code'], 'required'],
                [['amount'], 'number', 'except' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'voucher_transaction_code' => Yii::t('app', 'Voucher Transaction Code'),
            'amount' => Yii::t('app', 'Amount'),
            'credit_debit' => Yii::t('app', 'Credit/Debit'),
            'narration' => Yii::t('app', 'Narration'),
            'ledger_code' => Yii::t('app', 'Ledger'),
            'voucher_code' => Yii::t('app', 'Voucher Code'),
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
            'auto_posted_screen' => Yii::t('app', 'Auto Posted Screen'),
        ];
    }

    public function getLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'ledger_code']);
    }

}
