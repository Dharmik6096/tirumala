<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_product_sale_history".
 *
 * @property integer $id
 * @property string $product_sale_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $invoice_date
 * @property string $invoice_no
 * @property string $amount
 * @property string $other_amount
 * @property string $discount
 * @property string $paid_amount
 * @property string $amount_due
 * @property integer $payment_mode
 * @property integer $is_installment
 * @property integer $no_of_installment
 * @property integer $is_cancel
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblProductSaleHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['invoice_date', 'created_at', 'updated_at', 'history_created_at', 'send_status', 'picked_datetime', 'response_datetime','resp_desc'], 'safe'],
            [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due'], 'safe'],
            [['payment_mode', 'is_installment', 'no_of_installment', 'is_cancel', 'originating_type'], 'safe'],
            [['product_sale_code', 'invoice_no'], 'safe'],
            [['customer_type', 'customer_code'], 'safe'],
            [['dcs_code', 'bmc_code'], 'safe'],
            [['mcc_plant_code', 'plant_code'], 'safe'],
            [['union_code', 'is_cash_sale'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['operation_type'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
            'amount' => Yii::t('app', 'Amount'),
            'other_amount' => Yii::t('app', 'Other Amount'),
            'discount' => Yii::t('app', 'Discount'),
            'paid_amount' => Yii::t('app', 'Paid Amount'),
            'amount_due' => Yii::t('app', 'Amount Due'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'is_installment' => Yii::t('app', 'Is Installment'),
            'no_of_installment' => Yii::t('app', 'No Of Installment'),
            'is_cancel' => Yii::t('app', 'Is Cancel'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
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
