<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_local_milk_sale_history".
 *
 * @property integer $id
 * @property integer $local_milk_sale_code
 * @property string $datetime_of_sale
 * @property integer $milk_type_code
 * @property integer $milk_class
 * @property integer $shift_code
 * @property string $qty
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $rate
 * @property string $discount
 * @property string $amount
 * @property string $credit
 * @property string $coupon
 * @property string $cash
 * @property string $member_code
 * @property string $payment_mode
 * @property string $union_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $consumer_code
 * @property string $consumer_type
 */
class TblLocalMilkSaleHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_local_milk_sale_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_milk_sale_code', 'milk_type_code', 'milk_class', 'shift_code', 'qty_mode', 'converted_qty_mode', 'originating_type'], 'integer'],
            [['datetime_of_sale', 'created_at', 'updated_at', 'history_created_at', 'voucher_code'], 'safe'],
            [['qty', 'converted_qty', 'rate', 'discount', 'amount', 'credit', 'coupon', 'cash'], 'number'],
            [['member_code'], 'string', 'max' => 20],
            [['payment_mode', 'operation_type'], 'string', 'max' => 10],
            [['union_code'], 'string', 'max' => 3],
            [['dcs_code'], 'string', 'max' => 12],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['consumer_code', 'consumer_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'local_milk_sale_code' => Yii::t('app', 'Local Milk Sale Code'),
            'datetime_of_sale' => Yii::t('app', 'Datetime Of Sale'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_class' => Yii::t('app', 'Milk Class'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'qty' => Yii::t('app', 'Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'rate' => Yii::t('app', 'Rate'),
            'discount' => Yii::t('app', 'Discount'),
            'amount' => Yii::t('app', 'Amount'),
            'credit' => Yii::t('app', 'Credit'),
            'coupon' => Yii::t('app', 'Coupon'),
            'cash' => Yii::t('app', 'Cash'),
            'member_code' => Yii::t('app', 'Member Code'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'consumer_code' => Yii::t('app', 'Consumer Code'),
            'consumer_type' => Yii::t('app', 'Consumer Type'),
        ];
    }
}
