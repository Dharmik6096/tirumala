<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\product\models\TblInventoryTransferTxn;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransaction;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_inventory_transfer".
 *
 * @property string $inventory_transfer_code
 * @property string $inventory_transfer_no
 * @property string $inventory_transfer_date
 * @property string $from_type
 * @property string $from_code
 * @property string $to_type
 * @property string $to_code
 * @property string $remarks
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
class TblInventoryTransfer extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $from_dcs_code, $from_mcc_plant_code, $from_bmc_code, $to_dcs_code, $to_mcc_plant_code, $to_bmc_code, $product_code, $qty, $available_stock, $unit_code, $sap_batch_no;

    public static function tableName() {
        return 'tbl_inventory_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['inventory_transfer_no', 'inventory_transfer_date', 'from_type', 'from_code', 'to_type', 'to_code'], 'required'],
            [['inventory_transfer_code'], 'safe'],
            [['inventory_transfer_date', 'created_at', 'updated_at', 'from_mcc_plant_code', 'from_bmc_code', 'from_dcs_code', 'to_mcc_plant_code', 'to_bmc_code', 'to_dcs_code', 'product_code', 'qty', 'available_stock', 'unit_code', 'transaction_date', 'sap_batch_no', 'data_post_status', 'response_msg', 'is_stock_posted'], 'safe'],
            [['remarks'], 'string'],
            [['originating_type'], 'integer'],
            [['inventory_transfer_code', 'inventory_transfer_no'], 'string', 'max' => 30],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['from_type', 'to_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'org_type');
                }, 'on' => 'importCsv'],
            [['from_code'], function ($attribute, $params) {
                    if (strtoupper($this->from_type == 'BMC')) {
                        return Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                    } else if (strtoupper($this->from_type == 'MCC')) {
                        $mcc = new TblMccPlant();
                        $this->from_code = $mcc->getValidMcc($this->from_code);
                        return !empty($this->from_code) ? $this->from_code : $this->addError('from_code', Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is Invalid.'));
                    } else if (strtoupper($this->from_type == 'DCS')) {
                        $dcs = new TblDcs();
                        $this->from_code = $dcs->getValidDcs($this->from_code);
                        return !empty($this->from_code) ? $this->from_code : $this->addError('from_code', Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is Invalid.'));
                    }
                }, 'on' => ['importCsv']],
            [['to_code'], function ($attribute, $params) {
                    if (strtoupper($this->to_type == 'BMC')) {
                        return Yii::$app->general->validateBMC($this, 'to_code', 'bmc_code');
                    } else if (strtoupper($this->to_type == 'MCC')) {
                        $mcc = new TblMccPlant();
                        $this->to_code = $mcc->getValidMcc($this->to_code);
                        return !empty($this->to_code) ? $this->to_code : $this->addError('to_code', Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is Invalid.'));
                    } else if (strtoupper($this->to_type == 'DCS')) {
                        $dcs = new TblDcs();
                        $this->to_code = $dcs->getValidDcs($this->to_code);
                        return !empty($this->to_code) ? $this->to_code : $this->addError('to_code', Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is Invalid.'));
                    }
                }, 'on' => ['importCsv']],
            [['inventory_transfer_no'], 'setImport', 'on' => ['importCsv']],
            [['from_mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['from_mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
            [['from_bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['from_bmc_code' => 'bmc_code'], 'on' => 'importCsv'],
            [['from_dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['from_dcs_code' => 'dcs_code'], 'on' => 'importCsv'],
            [['to_mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['to_mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
            [['to_bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['to_bmc_code' => 'bmc_code'], 'on' => 'importCsv'],
            [['to_dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['to_dcs_code' => 'dcs_code'], 'on' => 'importCsv'],
            [['inventory_transfer_no'], 'checkStock', 'on' => ['importCsv']],
            [['inventory_transfer_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['inventory_transfer_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['inventory_transfer_date'], 'convertDate', 'on' => ['importCsv']],
            [['to_code'], 'validateToTransfer', 'except' => ['androidsync']],
            [
                ['transaction_date'], 'required', 'when' => function ($model) {
                    $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
                    return $batchNoWiseInventory == 1;
                },
            ],
            [['sap_batch_no'], 'validateSapBatchNo', 'on' => ['importCsv']],
            [['transaction_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['transaction_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['transaction_date'], 'convertDate', 'on' => ['importCsv']],
            [['data_post_status'], 'default', 'value' => 0],
            [['is_stock_posted'], 'default', 'value' => function () {
                    $grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult($this->union_code, 'grn_without_stock_entry', $this);
                    return ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') ? 1 : 0;
                }, 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
        return [
            'inventory_transfer_code' => Yii::t('app', 'Inventory Transfer Code'),
            'inventory_transfer_no' => ($batchNoWiseInventory == 1) ? \Yii::t('app', 'Challan No') : \Yii::t('app', 'Inventory Transfer No'),
            'inventory_transfer_date' => ($batchNoWiseInventory == 1) ? \Yii::t('app', 'Challan Date') : \Yii::t('app', 'Inventory Transfer Date'),
//            'inventory_transfer_no' => Yii::t('app', 'Inventory Transfer No'),
//            'inventory_transfer_date' => Yii::t('app', 'Inventory Transfer Date'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_code' => Yii::t('app', 'From Code'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_code' => Yii::t('app', 'To Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'from_mcc_plant_code' => Yii::t('app', 'From MCC'),
            'to_mcc_plant_code' => Yii::t('app', 'To MCC'),
            'from_bmc_code' => Yii::t('app', 'From BMC'),
            'to_bmc_code' => Yii::t('app', 'To BMC'),
            'from_dcs_code' => Yii::t('app', 'From DCS'),
            'to_dcs_code' => Yii::t('app', 'To DCS'),
            'transaction_date' => Yii::t('app', 'Actual Date'),
            'is_stock_posted' => Yii::t('app', 'Is Stock Posted'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccFromCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'from_code']);
    }

    public function getBmcFromCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'from_code']);
    }

    public function getDcsFromCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'from_code']);
    }

    public function getMccToCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'to_code']);
    }

    public function getBmcToCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'to_code']);
    }

    public function getDcsToCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'to_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function checkStock() {
        $product = $this->product_code;
        $from_type = $this->from_type;
        $from_code = $this->from_code;
        $union_code = $this->union_code;

        $stockModel = new TblProductStock();
        $stockModel->setCodes($from_type, $from_code);
        $stockModel->product_code = $product;
        $stockModel->union_code = $union_code;
        $batch_no = '';
        $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration($this->union_code, 'batch_no_wise_inventory', 'PORTAL');
        if ($batchNoWiseInventory == 1) {
            $batch_no = $this->sap_batch_no;
        }
        $existtoStock = $stockModel->getExistStock($from_type, $batch_no);
        if (!empty($existtoStock->stock)) {
            $this->available_stock = $existtoStock->stock;
        } else {
            $this->available_stock = 0;
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->inventory_transfer_code = (string) Yii::$app->general->getPrimaryCode($this, 1);
            $this->unit_code = Yii::$app->general->getforeignkey($this->productCode, 'unit_code');
            if ($this->from_type == 'MCC') {
                $this->from_mcc_plant_code = $this->from_code;
                $this->union_code = Yii::$app->general->getforeignkey($this->mccFromCode, 'union_code');
            } else if ($this->from_type == 'BMC') {
                $this->from_bmc_code = $this->from_code;
                $this->union_code = Yii::$app->general->getforeignkey($this->bmcFromCode, 'union_code');
            } else if ($this->from_type == 'DCS') {
                $this->from_dcs_code = $this->from_code;
                $this->union_code = Yii::$app->general->getforeignkey($this->dcsFromCode, 'union_code');
            }
            if ($this->to_type == 'MCC') {
                $this->to_mcc_plant_code = $this->to_code;
            } else if ($this->to_type == 'BMC') {
                $this->to_bmc_code = $this->to_code;
            } else if ($this->to_type == 'DCS') {
                $this->to_dcs_code = $this->to_code;
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->inventory_transfer_date = Yii::$app->controls->view_date($this->inventory_transfer_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->inventory_transfer_date = '-';
        }
        try {
            $this->transaction_date = Yii::$app->controls->view_date($this->transaction_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->transaction_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->inventory_transfer_date = !empty($this->inventory_transfer_date) ? Yii::$app->controls->view_date($this->inventory_transfer_date, 'php:Y-m-d') : NULL;
            $this->transaction_date = !empty($this->transaction_date) ? Yii::$app->controls->view_date($this->transaction_date, 'php:Y-m-d') : NULL;
        }
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model)) {
            $txModel = new TblInventoryTransferTxn();
            $grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult($this->union_code, 'grn_without_stock_entry', $this);
            $txModel->inventory_transfer_code = $model->inventory_transfer_code;
            $txModel->inventory_transfer_txn_code = Yii::$app->general->getTransactionCode($txModel, $txModel->inventory_transfer_code);
            $txModel->product_code = $model->product_code;
            $txModel->available_stock = $model->available_stock;
            $txModel->unit_code = $model->unit_code;
            $txModel->qty = $model->qty;
            $txModel->union_code = $model->union_code;
            $txModel->sap_batch_no = $model->sap_batch_no;
            $txModel->is_stock_posted = ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') ? 1 : 0;

            if (!$txModel->validate()) {
                $errors[] = $txModel->getErrors();
            }

            if (empty($txModel->getErrors()) && $txModel->validate()) {
                array_push($saveModel, $txModel);
                $fstockModel = new TblProductStock();
                $fstockModel->setCodes($this->from_type, $this->from_code);
                $fstockModel->product_code = $txModel->product_code;
                $fstockModel->union_code = $txModel->union_code;
                $batch = !empty($txModel->sap_batch_no) ? $txModel->sap_batch_no : '';
                $existfromStock = $fstockModel->getExistStock($this->from_type, $batch);

                $f_stock = 0;
                $qty = $txModel->qty;
                if (!empty($existfromStock)) {
                    $historyModel = new TblProductStockHistory();
                    Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
                    array_push($saveModel, $historyModel);
                    $f_stock = $existfromStock->stock;
                    $existfromStock->stock = $f_stock - $qty;
                    $fstockModel = $existfromStock;
                } else {
                    $fstockModel->product_stock_code = $fstockModel->getCode();
                    $fstockModel->stock = $f_stock - $qty;
                    $fstockModel->x_col1 = Yii::$app->general->getUuid();
                }
                array_push($saveModel, $fstockModel);
                $i = 1;
                $fstockTxnModel = new TblProductStockTransaction();
                $fstockTxnModel->attributes = $fstockModel->attributes;
                $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($i);
                $fstockTxnModel->old_value = $f_stock;
                $fstockTxnModel->new_value = $qty;
                $fstockTxnModel->final_value = $fstockModel->stock;
                $fstockTxnModel->transaction_type = 'INVENTORY TRANSFER';
                $fstockTxnModel->transaction_date = date('Y-m-d');
                $fstockTxnModel->reference_code = $txModel->inventory_transfer_txn_code;
                array_push($saveModel, $fstockTxnModel);
                $i++;
                if ($txModel->is_stock_posted) {
                    //set to stock
                    $stockModel = new TblProductStock();
                    $stockModel->setCodes($this->to_type, $this->to_code);
                    $stockModel->product_code = $txModel->product_code;
                    $stockModel->union_code = $txModel->union_code;
                    $stockModel->sap_batch_no = $batch;
                    $existtoStock = $stockModel->getExistStock($this->to_type, $stockModel->sap_batch_no);

                    $t_stock = 0;
                    $valid_avl_stock = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['validate_available_stock']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['validate_available_stock'] : 0;
                    $min_stock_config = Yii::$app->general->getforeignkey($txModel->productCode, 'min_stock');
                    $min_stock = !empty($min_stock_config) ? $min_stock_config : 0;
                    if ($valid_avl_stock == 1 && strtoupper($this->to_type) == 'DCS' && !empty($existtoStock) && $existtoStock->stock > 0 && $existtoStock->stock > $min_stock) {
                        $this->addError('qty', 'Stock Is Already Availble of Product ' . Yii::$app->general->getforeignkey($txModel->productCode, 'product_name'));
                    } else if (!empty($existtoStock)) {
                        $historyModel = new TblProductStockHistory();
                        Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
                        array_push($saveModel, $historyModel);
                        $t_stock = $existtoStock->stock;
                        $existtoStock->stock = $t_stock + $qty;
                        $stockModel = $existtoStock;
                    } else {
                        $stockModel->product_stock_code = $stockModel->getCode($i);
                        $stockModel->stock = $t_stock + $qty;
                        $stockModel->x_col1 = Yii::$app->general->getUuid();
                    }
                    $stockModel->rate = $fstockModel->rate;
                    array_push($saveModel, $stockModel);
                    $stockTxnModel = new TblProductStockTransaction();
                    $stockTxnModel->attributes = $stockModel->attributes;
                    $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($i);
                    $stockTxnModel->old_value = $t_stock;
                    $stockTxnModel->new_value = $qty;
                    $stockTxnModel->final_value = $stockModel->stock;
                    $stockTxnModel->transaction_type = 'INVENTORY RECEIVED';
                    $stockTxnModel->transaction_date = date('Y-m-d');
                    $stockTxnModel->reference_code = $txModel->inventory_transfer_txn_code;
                    array_push($saveModel, $stockTxnModel);
                }
            }
        }
    }

    public function validateToTransfer($attribute, $param) {
        if ($this->from_type == $this->to_type && $this->from_code == $this->to_code) {
            $this->addError('quantity', Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' Not Allow to Transfer to its self.'));
        }
        /* if (strtoupper($this->to_type) == 'BMC') {
          $is_mcc = Yii::$app->general->getforeignkey($this->bmcToCode, 'is_mcc');
          $bmc_name = Yii::$app->general->getforeignkey($this->bmcToCode, 'bmc_name');
          if ($is_mcc == '1') {
          $this->addError('to_code', Yii::t('app/validation', ' Not Allow to Transfer to BMC ' . $bmc_name));
          }
          } else if (strtoupper($this->to_type) == 'DCS') {
          $dcs_detail = $this->dcsToCode;
          if (!empty($dcs_detail) && $dcs_detail->is_bmc == '1') {
          $this->addError('to_code', Yii::t('app/validation', ' Not Allow to Transfer to DCS ' . $dcs_detail->dcs_name));
          }
          } */
    }

    public function validateSapBatchNo($attribute, $param) {
        $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
        $type = $this->from_type;
        $code = $this->from_code;
        $stockModel = new TblProductStock();
        $query = $stockModel->find()->where([
                    'product_code' => $this->product_code])
                ->andWhere(['>', 'tbl_product_stock.stock', 0]);
        if ($batchNoWiseInventory == 1) {
            $query->andWhere(['sap_batch_no' => $this->sap_batch_no]);
        }
        $query->andWhere(['tbl_product_stock.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        if (strtoupper($type) == 'MCC') {
            $query->andWhere(['mcc_plant_code' => $code])
                    ->andWhere(['AND', ['is', 'bmc_code', NULL], ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'BMC') {
            $query->andWhere(['bmc_code' => $code])
                    ->andWhere(['AND', ['is', 'dcs_code', NULL]]);
        } elseif (strtoupper($type) == 'DCS' || strtoupper($type) == 'VLC') {
            $query->andWhere(['dcs_code' => $code]);
        }
        $data = $query->all();
        if (empty($data)) {
            $this->addError('sap_batch_no', 'Batch No Is Invalid');
        }
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
