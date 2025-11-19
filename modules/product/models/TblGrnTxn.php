<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;

/**
 * This is the model class for table "tbl_grn_txn".
 *
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
 */
class TblGrnTxn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_grn_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code', 'unit_code', 'rate', 'received_qty', 'basic_amount', 'gross_amount'], 'required'],
            [['grn_txn_code', 'sap_batch_no', 'dispatch_qty', 'missing_qty', 'rejection_remarks', 'missing_remarks', 'is_stock_posted', 'plant_dispatch_txn_code'], 'safe'],
            [['unit_code', 'originating_type'], 'integer'],
            [['rate', 'received_qty', 'rejected_qty', 'basic_amount', 'tax', 'gross_amount', 'missing_qty'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['grn_txn_code', 'grn_code', 'product_code'], 'string', 'max' => 30],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'manuf_date', 'posting_date'], 'safe'],
            [['rejected_qty', 'received_qty', 'missing_qty'], 'default', 'value' => 0],
            [['rejected_qty'], 'number', 'min' => 0],
            [['received_qty', 'rate', 'tax', 'basic_amount', 'gross_amount'], 'number', 'min' => 0, 'except' => ['batchcreate']],
            [['received_qty'], 'number', 'min' => 0],
            [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code']],
            [['unit_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnits::className(), 'targetAttribute' => ['unit_code' => 'unit_code']],
            [['sap_batch_no'], 'required', 'when' => function ($model) {
                    $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
                    $withoutDispatch = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'without_dispatch_grn', 'PORTAL');
                    $grnWithoutStockEntry = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'grn_without_stock_entry', 'PORTAL');
                    return $batchNoWiseInventory == 1 && $withoutDispatch == 1 && $grnWithoutStockEntry != 1;
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'grn_txn_code' => Yii::t('app', 'Grn Txn Code'),
            'grn_code' => Yii::t('app', 'Grn Code'),
            'product_code' => Yii::t('app', 'Product'),
            'unit_code' => Yii::t('app', 'Unit'),
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
            'manuf_date' => Yii::t('app', 'Manufacturing Date'),
            'posting_date' => Yii::t('app', 'Posting Date'),
        ];
    }

    public function getUnitCode() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

}
