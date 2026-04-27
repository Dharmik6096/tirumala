<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_inventory_transfer_txn_history".
 *
 * @property integer $id
 * @property string $inventory_transfer_txn_code
 * @property string $inventory_transfer_code
 * @property string $product_code
 * @property string $available_stock
 * @property integer $unit_code
 * @property string $qty
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInventoryTransferTxnHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_inventory_transfer_txn_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['inventory_transfer_txn_code', 'union_code', 'sap_batch_no', 'is_stock_posted','data_post_status','picked_datetime','response_datetime','response_msg'], 'safe'],
            [['available_stock', 'qty'], 'safe'],
            [['unit_code', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['inventory_transfer_txn_code', 'inventory_transfer_code', 'product_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'inventory_transfer_txn_code' => Yii::t('app', 'Inventory Transfer Txn Code'),
            'inventory_transfer_code' => Yii::t('app', 'Inventory Transfer Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'available_stock' => Yii::t('app', 'Available Stock'),
            'unit_code' => Yii::t('app', 'Unit Code'),
            'qty' => Yii::t('app', 'Qty'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'is_stock_posted' => Yii::t('app', 'Is Stock Posted'),
        ];
    }

}
