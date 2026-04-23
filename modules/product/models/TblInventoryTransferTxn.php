<?php

namespace app\modules\product\models;

use app\modules\organisation\models\TblUnions;
use Yii;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;

/**
 * This is the model class for table "tbl_inventory_transfer_txn".
 *
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInventoryTransferTxn extends \app\models\ChildModel {

    public $sap_vendor_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_inventory_transfer_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code', 'unit_code', 'qty', 'available_stock'], 'required'],
            [['inventory_transfer_txn_code', 'union_code', 'sap_batch_no', 'is_stock_posted', 'sap_vendor_code','data_post_status','picked_datetime','response_datetime','response_msg'], 'safe'],
            [['available_stock', 'qty'], 'number'],
            [['unit_code', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['inventory_transfer_txn_code', 'inventory_transfer_code', 'product_code'], 'string', 'max' => 30],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['qty'], 'validateQty'],
            [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code']],
            [['unit_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnits::className(), 'targetAttribute' => ['unit_code' => 'unit_code']],
            [
                ['sap_batch_no'], 'required', 'when' => function ($model) {
                    $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
                    return $batchNoWiseInventory == 1;
                },
            ],
            [['product_code'],
                'unique',
                'targetAttribute' => array_merge(
                        ['inventory_transfer_code', 'product_code'], Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL') == 1 ? ['sap_batch_no'] : []
                ),
                'message' => 'Product already exists for this transaction.'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'inventory_transfer_txn_code' => Yii::t('app', 'Inventory Transfer Txn Code'),
            'inventory_transfer_code' => Yii::t('app', 'Inventory Transfer Code'),
            'product_code' => Yii::t('app', 'Product'),
            'available_stock' => Yii::t('app', 'Available Stock'),
            'unit_code' => Yii::t('app', 'Unit'),
            'qty' => Yii::t('app', 'Qty'),
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
            'is_stock_posted' => Yii::t('app', 'Is Stock Posted'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getInventoryTxnCode() {
        return $this->hasOne(TblInventoryTransferTxn::className(), ['inventory_transfer_code' => 'inventory_transfer_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function validateQty($attribute, $params) {
        if (!empty($this->qty) && $this->qty > $this->available_stock) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' can not be Greater Than Available Stock.'));
            return false;
        }
    }

    public function getUnitCode() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    public function getTransaction($id) {
        return $this->find()->where(['inventory_transfer_code' => $id])->all();
    }

}
