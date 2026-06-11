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
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\product\models\TblGrnInstallment;

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
            [['bmc_code', 'grn_date', 'vendor_code', 'invoice_date', 'invoice_no', 'product_code', 'rate', 'received_qty', 'tax', 'rejected_qty'], 'required', 'on' => 'importCsv'],
            [['vendor_code'], 'checkVendorCode', 'on' => ['importCsv']],
            [['grn_date', 'mcc_plant_code', 'bmc_code', 'invoice_date'], 'required', 'except' => ['importCsv']],
            [['plant_code'], 'required', 'on' => ['batchcreate']],
            [['plant_code'], 'required', 'when' => function ($model) {
                    $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
                    $withoutDispatch = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'without_dispatch_grn', 'PORTAL');
                    return $batchNoWiseInventory == 1 && $withoutDispatch == 1;
                }, 'except' => ['batchcreate', 'importCsv']],
            [['vendor_master_code'], 'required', 'when' => function ($model) {
                    $withoutDispatch = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'without_dispatch_grn', 'PORTAL');
                    return $withoutDispatch != 1;
                }, 'except' => ['batchcreate']],
            [['grn_code', 'grn_date', 'invoice_date', 'created_at', 'updated_at', 'product_code', 'unit_code', 'rate', 'received_qty', 'tax', 'rejected_qty', 'vendor_code', 'ref_no', 'plant_code'], 'safe'],
            [['remarks', 'originating_type', 'union_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['grn_no', 'invoice_no'], 'string', 'max' => 30],
            [['vendor_master_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVendorMaster::className(), 'targetAttribute' => ['vendor_master_code' => 'vendor_master_code'], 'on' => 'importCsv'],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, TRUE);
                }, 'on' => ['importCsv']],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['received_qty', 'rejected_qty'], 'number'],
            [['bmc_code'], 'setImport', 'on' => ['importCsv']],
            [['grn_date', 'invoice_date', 'deduction_start_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['grn_date', 'invoice_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['grn_date', 'invoice_date', 'deduction_start_date'], 'convertDate', 'on' => ['importCsv']],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'amount', 'payment_mode', 'no_of_installment', 'deduction_start_date', 'is_stock_posted', 'data_post_status', 'pick_datetime', 'response_datetime', 'response_msg'], 'safe'],
            [['deduction_start_date', 'no_of_installment'], 'required', 'when' => function ($model) {
                    return $this->payment_mode == 1;
                },
                'whenClient' => "function (attribute, value) { return $('#tblgrn-payment_mode').is(':checked') }"
            ],
            [['deduction_start_date'], 'dateValidate'],
            [['invoice_date'], 'validateInvoiceDate'],
            [['data_post_status'], 'default', 'value' => 0],
            [['ref_no'], 'unique', 'targetAttribute' => ['grn_no', 'ref_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
        return [
            'grn_code' => Yii::t('app', 'Grn Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'grn_date' => ($batchNoWiseInventory == 1) ? \Yii::t('app', 'Entry Date') : \Yii::t('app', 'Grn Date'),
//            'grn_date' => Yii::t('app', 'Grn Date'),
            'vendor_master_code' => Yii::t('app', 'Vendor'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'invoice_date' => ($batchNoWiseInventory == 1) ? \Yii::t('app', 'Document Date') : \Yii::t('app', 'Invoice Date'),
            'invoice_no' => ($batchNoWiseInventory == 1) ? \Yii::t('app', 'Document No') : \Yii::t('app', 'Invoice No'),
//            'invoice_date' => Yii::t('app', 'Invoice Date'),
//            'invoice_no' => Yii::t('app', 'Invoice No'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'plant_code' => Yii::t('app', 'Plant'),
            'payment_mode' => Yii::t('app', 'is Credit Sale?'),
            'deduction_start_date' => Yii::t('app', 'Deduction Start Date *'),
            'bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(\app\modules\organisation\models\TblPlant::className(), ['plant_code' => 'plant_code']);
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
            $this->grn_no = (string) rand(1000, 9999);
            $this->no_of_installment = $this->payment_mode == 1 ? $this->no_of_installment : 0;
            if ($this->payment_mode == 1 && !empty($this->deduction_start_date)) {
                $dedStartDate = date('Y-m-d', strtotime($this->deduction_start_date));
                $this->deduction_start_date = $dedStartDate;
            }
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
            $totalGrossAmount = 0;
            $totalGrossAmount += $txn_model->gross_amount;
            $this->amount = $totalGrossAmount;
            if (empty($txn_model->getErrors()) && $txn_model->validate()) {
                array_push($saveModel, $txn_model);

                if (!empty($this->payment_mode)) {
                    $this->installment($saveModel);
                }
                $grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult($this->union_code, 'grn_without_stock_entry', $this);
                $this->is_stock_posted = ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') ? 1 : 0;
                $txn_model->is_stock_posted = $this->is_stock_posted;
                if ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') {
                    $stockModel = new TblProductStock();
                    $stockModel->attributes = $this->attributes;
                    $stockModel->attributes = $txn_model->attributes;
                    $existStock = $stockModel->getExistStock('BMC');
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
        try {
            $this->deduction_start_date = Yii::$app->controls->view_date($this->deduction_start_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->deduction_start_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->grn_date = !empty($this->grn_date) ? Yii::$app->controls->view_date($this->grn_date, 'php:Y-m-d') : NULL;
            $this->invoice_date = !empty($this->invoice_date) ? Yii::$app->controls->view_date($this->invoice_date, 'php:Y-m-d') : NULL;
            $this->deduction_start_date = !empty($this->deduction_start_date) ? Yii::$app->controls->view_date($this->deduction_start_date, 'php:Y-m-d') : NULL;
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

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function installment(&$modelSave) {
        $no = !empty($this->no_of_installment) ? ($this->no_of_installment) : 1;
        $instAmount = floatval($this->amount / $no);
        $ai = 1;

        for ($i = 0; $i < $no; $i++) {
            $installmentModel = new TblGrnInstallment();
            $installmentModel->grn_code = $this->grn_code;
            $installmentModel->union_code = $this->union_code;
            $installmentModel->plant_code = $this->plant_code;
            $installmentModel->mcc_plant_code = $this->mcc_plant_code;
            $installmentModel->bmc_code = $this->bmc_code;
            $installmentModel->main_amount = $this->amount;
            $installmentModel->installment_amount = $instAmount;
            $installmentModel->installment_status = 0;
            $installmentModel->grn_installment_code = Yii::$app->general->getTransactionCode($installmentModel, $this->grn_code, $ai);
            $installmentModel->installment_date = NULL;
            $modelSave[] = $installmentModel;
            $ai++;
        }
    }

    public function dateValidate() {
        if (empty($this->getErrors())) {
            if ($this->deduction_start_date < $this->invoice_date) {
                $this->addError('deduction_start_date', Yii::t('app/validation', 'Deduction Start Date must be greater than or equal to Invoice Date'));
            }
        }
    }

    public function validateInvoiceDate() {
        if (empty($this->getErrors('invoice_date'))) {
            if (!empty($this->ref_no)) {
                $dispModel = new TblPlantDispatch();
                $dispatchData = $dispModel->find()->where(['union_code' => $this->union_code, 'bmc_code' => $this->bmc_code, 'document_no' => $this->ref_no])->one();
                if (!empty($dispatchData) && !empty($dispatchData->document_date)) {
                    if ($this->invoice_date < $dispatchData->document_date) {
                        $this->addError('invoice_date', \Yii::t('app/validation', 'Invoice Date cannot be less than Document Date (' . date('d-m-Y', strtotime($dispatchData->document_date)) . ')'));
                    }
                }
            }
        }
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

}
