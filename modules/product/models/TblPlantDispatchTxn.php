<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;
use yii\helpers\ArrayHelper;

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

    public $received_qty, $rejected_qty, $missing_qty, $rejection_remarks, $missing_remarks, $manuf_date;

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
        $union_code = empty($this->union_code) ? explode(',', Yii::$app->session->get('Unions')) : $this->union_code;
        $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration($union_code, 'batch_no_wise_inventory', 'PORTAL');
        $grnWithoutStockEntry = Yii::$app->general->getUnionConfiguration($union_code, 'grn_without_stock_entry', 'PORTAL');
        $main_rules = [
                [['plant_dispatch_txn_code'], 'required'],
                [['qty', 'rate'], 'number', 'on' => ['clienterp_cargill']],
                [['product_code', 'rate', 'qty'], 'required', 'on' => ['clienterp_cargill']],
                [['product_code'], 'validateProduct', 'on' => ['clienterp_cargill']],
                [['product_code', 'unit_code', 'rate', 'amount', 'qty'], 'required', 'except' => ['clienterp_cargill']],
                [['plant_dispatch_txn_code', 'plant_dispatch_code', 'received_qty', 'rejected_qty', 'grn_missing_qty', 'missing_qty', 'rejection_remarks', 'missing_remarks', 'manuf_date'], 'safe'],
                [['union_code', 'unit_code', 'rate', 'amount', 'qty', 'product_code', 'sap_batch_no', 'lr_no', 'product_mrp', 'distributor_landing_rate', 'sachiv_price', 'member_price'], 'safe'],
                [['originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'po_itemno'], 'safe'],
                [['sap_batch_no'], 'required', 'when' => function ($model) use ($batchNoWiseInventory, $grnWithoutStockEntry) {
                    return $batchNoWiseInventory == 1 && $grnWithoutStockEntry != 1;
                }],
                [['sap_batch_no'], 'unique', 'targetAttribute' => ['sap_batch_no', 'product_code', 'plant_dispatch_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['importCsv'], 'when' => function ($model) use ($batchNoWiseInventory) {
                    return $batchNoWiseInventory == 1;
                }],
                [['product_code'], 'unique', 'targetAttribute' => ['product_code', 'plant_dispatch_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['importCsv'], 'when' => function ($model)use ($batchNoWiseInventory) {
                    return $batchNoWiseInventory == 0;
                }],
                [['product_code'], 'unique', 'targetAttribute' => ['product_code', 'sap_batch_no', 'plant_dispatch_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['importCsv'], 'when' => function ($model)use ($batchNoWiseInventory) {
                    return $batchNoWiseInventory == 1;
                }],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblPlantDispatchTxn', 'default');
        return array_merge($client_rules, $main_rules);
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
            'po_itemno' => Yii::t('app', 'PO Item No'),
            'manuf_date' => Yii::t('app', 'Manufacturing Date'),
            'product_mrp' => Yii::t('app', 'Product Mrp'),
            'distributor_landing_rate' => Yii::t('app', 'Distributor Landing Rate'),
            'sachiv_price' => Yii::t('app', 'Sachiv Price'),
            'member_price' => Yii::t('app', 'Member Price'),
        ];
    }

    public function getUnitCode() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getPlantBatchList($plant, $product) {
        $query = $this->find()->select('sap_batch_no')->where([
                    'tbl_plant_dispatch_txn.product_code' => $product,
                    'pd.plant_code' => $plant])
                ->join('LEFT JOIN', 'tbl_plant_dispatch pd', 'pd.plant_dispatch_code=tbl_plant_dispatch_txn.plant_dispatch_code');

        $data = $query->all();
        if (!empty($data)) {
            $data = ArrayHelper::map($data, 'sap_batch_no', 'sap_batch_no');
            asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        }
        return $data;
    }

    public function validateProduct($attribute) {
        $record = TblProduct::find()->select(['product_code', 'unit_code'])->where(['union_code' => $this->union_code])
                        ->andWhere(['or', ['ref_code' => $this->product_code], ['product_code' => $this->product_code]])->andWhere(['is_active' => 1])->one();
        if (!empty($record)) {
            $this->product_code = $record->product_code;
            $this->unit_code = $record->unit_code;
        } else {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' Is Invalid'));
        }
    }

}
