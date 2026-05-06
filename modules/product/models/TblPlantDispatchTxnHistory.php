<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_plant_dispatch_txn_history".
 *
 * @property integer $id
 * @property string $plant_dispatch_txn_code
 * @property string $plant_dispatch_code
 * @property string $product_code
 * @property integer $unit_code
 * @property string $sap_batch_no
 * @property string $rate
 * @property string $amount
 * @property string $union_code
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblPlantDispatchTxnHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_dispatch_txn_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plant_dispatch_txn_code', 'plant_dispatch_code'], 'safe'],
            [['union_code', 'unit_code', 'rate', 'amount', 'qty', 'product_code', 'sap_batch_no', 'lr_no', 'grn_missing_qty', 'product_mrp', 'distributor_landing_rate', 'sachiv_price', 'member_price'], 'safe'],
            [['originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by', 'history_created_at'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'po_itemno'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'plant_dispatch_txn_code' => Yii::t('app', 'Plant Dispatch Txn Code'),
            'plant_dispatch_code' => Yii::t('app', 'Grn Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'unit_code' => Yii::t('app', 'Unit Code'),
            'sap_batch_no' => Yii::t('app', 'Sap Batch No'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'union_code' => Yii::t('app', 'Union Code'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'po_itemno' => Yii::t('app', 'PO Item No'),
            'product_mrp' => Yii::t('app', 'Product Mrp'),
            'distributor_landing_rate' => Yii::t('app', 'Distributor Landing Rate'),
            'sachiv_price' => Yii::t('app', 'Sachiv Price'),
            'member_price' => Yii::t('app', 'Member Price'),
        ];
    }

}
