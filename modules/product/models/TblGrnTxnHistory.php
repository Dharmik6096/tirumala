<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_grn_txn_history".
 *
 * @property integer $id
 * @property string $grn_txn_code
 * @property string $grn_code
 * @property string $product_code
 * @property integer $unit_code
 * @property string $rate
 * @property string $received_qty
 * @property string $rejected_qty
 * @property string $basic_amount
 * @property string $tax
 * @property string $gross_amount
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblGrnTxnHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_grn_txn_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['grn_txn_code', 'is_stock_posted', 'plant_dispatch_txn_code'], 'safe'],
            [['unit_code', 'originating_type'], 'safe'],
            [['rate', 'received_qty', 'rejected_qty', 'basic_amount', 'tax', 'gross_amount', 'sap_batch_no'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['grn_txn_code', 'grn_code', 'product_code'], 'safe'],
            [['union_code', 'dispatch_qty', 'missing_qty', 'rejection_remarks', 'missing_remarks'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'manuf_date', 'posting_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'grn_txn_code' => Yii::t('app', 'Grn Txn Code'),
            'grn_code' => Yii::t('app', 'Grn Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'unit_code' => Yii::t('app', 'Unit Code'),
            'rate' => Yii::t('app', 'Rate'),
            'received_qty' => Yii::t('app', 'Received Qty'),
            'rejected_qty' => Yii::t('app', 'Rejected Qty'),
            'basic_amount' => Yii::t('app', 'Basic Amount'),
            'tax' => Yii::t('app', 'Tax'),
            'gross_amount' => Yii::t('app', 'Gross Amount'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'manuf_date' => Yii::t('app', 'Manufacturing Date'),
            'posting_date' => Yii::t('app', 'Posting Date'),
        ];
    }

}
