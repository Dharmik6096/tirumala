<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "tbl_member_bill".
 *
 * @property int $code
 * @property string|null $society_code
 * @property string|null $union_code
 * @property string|null $member_code
 * @property string|null $society_payment_cycle_code
 * @property float|null $milk_qty
 * @property float|null $avg_fat
 * @property float|null $avg_snf
 * @property float|null $avg_clr
 * @property float|null $kg_fat
 * @property float|null $kg_snf
 * @property float|null $milk_amount
 * @property float|null $product_sale_amount
 * @property float|null $local_sale_amount
 * @property float|null $loan_amount
 * @property float|null $other_add_amount
 * @property float|null $other_ded_amount
 * @property float|null $net_amount
 * @property int|null $status
 * @property string|null $payment_mode
 * @property string|null $bank_code
 * @property string|null $bank_acno
 * @property string|null $ifsc
 * @property string|null $payment_ref
 * @property int|null $is_disbursed
 * @property string|null $disbursed_date
 * @property string|null $voucher_no
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property string|null $x_col1
 * @property string|null $x_col2
 * @property string|null $x_col3
 * @property string|null $x_col4
 * @property string|null $x_col5
 * @property string|null $originating_type
 * @property string|null $originating_org_code
 * @property string|null $originating_org_type
 */
class MemberBill extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_member_bill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'code', 'society_code', 'union_code', 'member_code', 'society_payment_cycle_code',
                'milk_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'kg_fat', 'kg_snf',
                'milk_amount', 'product_sale_amount', 'local_sale_amount', 'loan_amount',
                'other_add_amount', 'other_ded_amount', 'net_amount', 'status', 'payment_mode',
                'bank_code', 'bank_acno', 'ifsc', 'payment_ref', 'is_disbursed', 'disbursed_date',
                'voucher_no', 'created_at', 'created_by', 'updated_at', 'updated_by',
                'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5',
                'originating_type', 'originating_org_code', 'originating_org_type'
            ], 'safe'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'society_code' => 'Society Code',
            'union_code' => 'Union Code',
            'member_code' => 'Member Code',
            'society_payment_cycle_code' => 'Society Payment Cycle Code',
            'milk_qty' => 'Milk Qty',
            'avg_fat' => 'Avg Fat',
            'avg_snf' => 'Avg Snf',
            'avg_clr' => 'Avg Clr',
            'kg_fat' => 'Kg Fat',
            'kg_snf' => 'Kg Snf',
            'milk_amount' => 'Milk Amount',
            'product_sale_amount' => 'Product Sale Amount',
            'local_sale_amount' => 'Local Sale Amount',
            'loan_amount' => 'Loan Amount',
            'other_add_amount' => 'Other Add Amount',
            'other_ded_amount' => 'Other Ded Amount',
            'net_amount' => 'Net Amount',
            'status' => 'Status',
            'payment_mode' => 'Payment Mode',
            'bank_code' => 'Bank Code',
            'bank_acno' => 'Bank Acno',
            'ifsc' => 'Ifsc',
            'payment_ref' => 'Payment Ref',
            'is_disbursed' => 'Is Disbursed',
            'disbursed_date' => 'Disbursed Date',
            'voucher_no' => 'Voucher No',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'x_col1' => 'X Col1',
            'x_col2' => 'X Col2',
            'x_col3' => 'X Col3',
            'x_col4' => 'X Col4',
            'x_col5' => 'X Col5',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }
}
