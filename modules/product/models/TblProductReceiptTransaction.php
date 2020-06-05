<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;

/**
 * This is the model class for table "tbl_product_receipt_transaction".
 *
 * @property string $product_receipt_transaction_code
 * @property string $product_requisition_code
 * @property string $requisition_transaction_code
 * @property string $product_code
 * @property string $product_receipt_code
 * @property string $requested_quantity
 * @property string $dispatched_quantity
 * @property string $received_quantity
 * @property string $rejected_quantity
 * @property string $rate
 * @property string $amount
 * @property string $discount
 * @property string $remark
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
class TblProductReceiptTransaction extends \app\models\ChildModel {

    public $uom;
    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_receipt_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_receipt_transaction_code'], 'required', 'on' => ['androidsync']],
                [['product_receipt_transaction_code', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'product_receipt_code', 'remark', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['requested_quantity', 'dispatched_quantity', 'received_quantity', 'rejected_quantity', 'rate', 'amount', 'discount'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_receipt_transaction_code' => Yii::t('app', 'Product Receipt Transaction Code'),
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'requisition_transaction_code' => Yii::t('app', 'Requisition Transaction Code'),
            'product_code' => Yii::t('app', 'Product'),
            'product_receipt_code' => Yii::t('app', 'Product Receipt Code'),
            'requested_quantity' => Yii::t('app', 'Requested Quantity'),
            'dispatched_quantity' => Yii::t('app', 'Dispatched Quantity'),
            'received_quantity' => Yii::t('app', 'Received Quantity'),
            'rejected_quantity' => Yii::t('app', 'Rejected Quantity'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount (Rs.)'),
            'discount' => Yii::t('app', 'Discount'),
            'remark' => Yii::t('app', 'Remarks'),
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
            'uom' => Yii::t('app', 'UOM'),
        ];
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

}
