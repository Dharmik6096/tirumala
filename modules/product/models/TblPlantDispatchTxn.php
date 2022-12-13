<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;

/**
 * This is the model class for table "tbl_plant_dispatch_txn".
 *
 * @property string $plant_dispatch_txn_code
 * @property string $grn_code
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
 */
class TblPlantDispatchTxn extends \app\models\ChildModel {

    public $received_qty, $rejected_qty;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_dispatch_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plant_dispatch_txn_code'], 'required'],
            [['product_code', 'sap_batch_no', 'unit_code', 'rate', 'amount', 'qty'], 'required'],
            [['plant_dispatch_txn_code', 'plant_dispatch_code', 'received_qty', 'rejected_qty'], 'safe'],
            [['union_code', 'unit_code', 'rate', 'amount', 'qty', 'product_code', 'sap_batch_no', 'lr_no'], 'safe'],
            [['originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['sap_batch_no'], 'unique', 'targetAttribute' => ['sap_batch_no', 'product_code', 'plant_dispatch_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['importCsv']],
            [['product_code'], 'unique', 'targetAttribute' => ['product_code', 'sap_batch_no', 'plant_dispatch_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'plant_dispatch_txn_code' => Yii::t('app', 'Plant Dispatch Txn Code'),
            'plant_dispatch_code' => Yii::t('app', 'Plant Dispatch Code'),
            'product_code' => Yii::t('app', 'Product'),
            'unit_code' => Yii::t('app', 'Unit'),
            'sap_batch_no' => Yii::t('app', 'SAP Batch No'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'union_code' => Yii::t('app', 'Union'),
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
        ];
    }

    public function getUnitCode() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

}
