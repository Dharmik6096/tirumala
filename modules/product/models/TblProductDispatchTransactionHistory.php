<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_dispatch_transaction_history".
 *
 * @property integer $id
 * @property string $dispatch_transaction_code
 * @property string $vendor_type
 * @property string $vendor_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $challan_no
 * @property string $dispatch_date
 * @property string $product_requisition_code
 * @property string $requisition_transaction_code
 * @property string $product_code
 * @property string $status
 * @property string $rate
 * @property string $amount
 * @property string $discount_amount
 * @property string $dispatch_qty
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
class TblProductDispatchTransactionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_dispatch_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dispatch_transaction_code', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'challan_no', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'status', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['dispatch_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['rate', 'amount', 'discount_amount', 'dispatch_qty'], 'number'],
            [['originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dispatch_transaction_code' => Yii::t('app', 'Dispatch Transaction Code'),
            'vendor_type' => Yii::t('app', 'Vendor Type'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'requisition_transaction_code' => Yii::t('app', 'Requisition Transaction Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'status' => Yii::t('app', 'Status'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'discount_amount' => Yii::t('app', 'Discount Amount'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
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
