<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_requisition_transaction_history".
 *
 * @property integer $id
 * @property string $requisition_transaction_code
 * @property string $product_requisition_code
 * @property string $requisition_on_date
 * @property string $quantity
 * @property string $provisional_rate
 * @property string $provisional_amount
 * @property string $discount_amount
 * @property string $product_code
 * @property string $status
 * @property integer $is_approved
 * @property string $approved_by
 * @property string $approved_quantity
 * @property string $approved_date
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
class TblProductRequisitionTransactionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_requisition_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['requisition_transaction_code', 'product_requisition_code', 'product_code', 'status', 'approved_by', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['quantity', 'provisional_rate', 'provisional_amount', 'discount_amount', 'approved_quantity'], 'safe'],
                [['is_approved', 'originating_type'], 'safe'],
                [['approved_date', 'created_at', 'updated_at', 'history_created_at', 'requisition_on_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'requisition_transaction_code' => Yii::t('app', 'Requisition Transaction Code'),
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'quantity' => Yii::t('app', 'Quantity'),
            'provisional_rate' => Yii::t('app', 'Provisional Rate'),
            'provisional_amount' => Yii::t('app', 'Provisional Amount'),
            'discount_amount' => Yii::t('app', 'Discount Amount'),
            'product_code' => Yii::t('app', 'Product Code'),
            'status' => Yii::t('app', 'Status'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approved_quantity' => Yii::t('app', 'Approved Quantity'),
            'approved_date' => Yii::t('app', 'Approved Date'),
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
