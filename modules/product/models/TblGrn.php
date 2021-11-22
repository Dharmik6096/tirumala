<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\product\models\TblVendorMaster;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransaction;

/**
 * This is the model class for table "tbl_grn".
 *
 * @property string $grn_code
 * @property string $grn_no
 * @property string $grn_date
 * @property string $vendor_master_code
 * @property string $mcc_plant_code
 * @property string $invoice_date
 * @property string $invoice_no
 * @property string $remarks
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblGrn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $product_code, $unit_code, $rate, $received_qty, $tax, $rejected_qty, $vendor_code;

    public static function tableName() {
        return 'tbl_grn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['mcc_plant_code', 'grn_date', 'vendor_code', 'invoice_date', 'invoice_no', 'product_code', 'rate', 'received_qty', 'tax', 'rejected_qty'], 'required', 'on' => 'importCsv'],
                [['vendor_code'], 'checkVendorCode', 'on' => ['importCsv']],
                [['grn_date', 'mcc_plant_code', 'vendor_master_code', 'invoice_date'], 'required'],
                [['grn_code', 'grn_date', 'invoice_date', 'created_at', 'updated_at', 'product_code', 'unit_code', 'rate', 'received_qty', 'tax', 'rejected_qty', 'vendor_code'], 'safe'],
                [['remarks', 'originating_type', 'union_code'], 'safe'],
                [['grn_no', 'invoice_no'], 'string', 'max' => 30],
                [['vendor_master_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVendorMaster::className(), 'targetAttribute' => ['vendor_master_code' => 'vendor_master_code'], 'on' => 'importCsv'],
                [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['mcc_plant_code'], 'setImport', 'on' => ['importCsv']],
                [['grn_date', 'invoice_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['grn_date', 'invoice_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['grn_date', 'invoice_date'], 'convertDate', 'on' => ['importCsv']],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'grn_code' => Yii::t('app', 'Grn Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'grn_date' => Yii::t('app', 'Grn Date'),
            'vendor_master_code' => Yii::t('app', 'Vendor'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getVendorCode() {
        return $this->hasOne(TblVendorMaster::className(), ['vendor_master_code' => 'vendor_master_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->grn_code = (string) Yii::$app->general->getPrimaryCode($this, 1);
            $this->unit_code = Yii::$app->general->getforeignkey($this->productCode, 'unit_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'union_code');
            $this->grn_no = (string) rand(1000, 9999);
        }
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model->grn_code)) {
            $txn_model = new TblGrnTxn();
            $txn_model->grn_code = $model->grn_code;
            $txn_model->grn_txn_code = Yii::$app->general->getTransactionCode($txn_model, $txn_model->grn_code);
            $txn_model->product_code = $model->product_code;
            $txn_model->union_code = $model->union_code;
            $txn_model->unit_code = $model->unit_code;
            $txn_model->rate = $model->rate;
            $txn_model->received_qty = $model->received_qty;
            $txn_model->rejected_qty = $model->rejected_qty;
            $txn_model->tax = $model->tax;
            $txn_model->basic_amount = ($txn_model->received_qty - $txn_model->rejected_qty) * $txn_model->rate;
            $txn_model->gross_amount = $txn_model->basic_amount + $txn_model->tax;
            if (!$txn_model->validate()) {
                $errors[] = $txn_model->getErrors();
            }

            if (empty($txn_model->getErrors()) && $txn_model->validate()) {
                array_push($saveModel, $txn_model);

                $stockModel = new TblProductStock();
                $stockModel->attributes = $this->attributes;
                $stockModel->attributes = $txn_model->attributes;
                $existStock = $stockModel->getExistStock('MCC');
                $stock = 0;
                $rejectedQty = !empty($txn_model->rejected_qty) ? $txn_model->rejected_qty : 0;
                $qty = $txn_model->received_qty - $rejectedQty;
                if (!empty($existStock)) {
                    $historyModel = new TblProductStockHistory();
                    Yii::$app->operation->history($existStock, $historyModel, UPDATE);
                    array_push($saveModel, $historyModel);
                    $stock = $existStock->stock;
                    $existStock->stock = $stock + $qty;
                    $stockModel = $existStock;
                } else {
                    $stockModel->product_stock_code = $stockModel->getCode();
                    $stockModel->stock = $stock + $qty;
                    $stockModel->x_col1 = Yii::$app->general->getUuid();
                    $stockModel->plant_code = Yii::$app->general->getforeignkey($model->mccPlantCode, 'plant_code');
                }
                array_push($saveModel, $stockModel);
                $stockTxnModel = new TblProductStockTransaction();
                $stockTxnModel->attributes = $stockModel->attributes;
                $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode();
                $stockTxnModel->old_value = $stock;
                $stockTxnModel->new_value = $qty;
                $stockTxnModel->final_value = $stockModel->stock;
                $stockTxnModel->transaction_type = 'GRN';
                $stockTxnModel->transaction_date = date('Y-m-d');
                $stockTxnModel->reference_code = $txn_model->grn_txn_code;
                array_push($saveModel, $stockTxnModel);
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->grn_date = Yii::$app->controls->view_date($this->grn_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->grn_date = '-';
        }
        try {
            $this->invoice_date = Yii::$app->controls->view_date($this->invoice_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->invoice_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->grn_date = !empty($this->grn_date) ? Yii::$app->controls->view_date($this->grn_date, 'php:Y-m-d') : NULL;
            $this->invoice_date = !empty($this->invoice_date) ? Yii::$app->controls->view_date($this->invoice_date, 'php:Y-m-d') : NULL;
        }
    }

    public function checkVendorCode() {
        $vendor = new TblVendorMaster();
        $data = $vendor->find()->where(['vendor_code' => $this->vendor_code])->one();
        if (!empty($data)) {
            $this->vendor_master_code = $data->vendor_master_code;
        } else {
            $this->addError('vendor_master_code', Yii::t('app/validation', $this->getAttributeLabel('vendor_master_code') . ' is invalid'));
        }
    }

}
