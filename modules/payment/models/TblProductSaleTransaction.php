<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\payment\models\TblSaleInstallments;
use app\modules\payment\models\TblLoanProductSaleDetails;
use app\modules\syncutility\models\TblSentbox;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransactionHistory;

/**
 * This is the model class for table "tbl_product_sale_details".
 *
 * @property integer $product_sale_transaction_code
 * @property string $product_sale_code
 * @property integer $product_code
 * @property string $product_sale_rate_applicability_code
 * @property double $rate
 * @property string $quantity
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblProduct $productCode
 * @property TblProduct $productCode0
 * @property TblProductSale $productSaleCode
 */
class TblProductSaleTransaction extends \app\models\ChildModel {

    public $is_sentbox = TRUE;
    public $saveDeleteChildRecords = TRUE;
    public $available_stock;
    public $union_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
//        return 'tbl_product_sale_details';
        return 'tbl_product_sale_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_transaction_code', 'product_sale_code', 'product_code', 'quantity'], 'required', 'except' => ['saleProduct', 'androidsync', 'androidsyncsplit', 'saleProductOnDispatch']],
                [['product_code'], 'validProduct', 'on' => ['saleProductOnDispatch']],
                [['product_sale_code', 'product_code', 'quantity', 'rate', 'unit_code', 'tax_code'], 'required', 'on' => ['saleProduct', 'saleProductOnDispatch']],
//            [['quantity'], 'integer', 'except' => ['androidsync']],
            [['product_sale_rate_applicability_code', 'created_by', 'updated_by'], 'string', 'except' => ['androidsync', 'androidsyncsplit']],
                [['rate', 'quantity', 'amount'], 'number', 'min' => 0, 'except' => ['androidsync', 'androidsyncsplit']],
                [['created_at', 'updated_at', 'product_sale_rate_applicability_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'product_sale_transaction_code', 'discount', 'unit_code', 'tax_code', 'tax_amount', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'product_code', 'product_sale_code', 'available_stock', 'remarks', 'mcc_plant_code', 'bmc_code'], 'safe'],
                [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code'], 'except' => ['androidsync', 'androidsyncsplit']],
//            [['rate'], 'integer', 'min' => 1, 'on' => ['saleProduct']],
            [['quantity'], 'validateQty', 'on' => ['saleProduct']],
                [['union_code', 'sap_batch_no', 'data_lock', 'lock_date', 'reference_code'], 'safe'],
                [['sap_batch_no'], 'required', 'when' => function ($model) {
                    $product = $model->productCode;
                    $batchNoWiseInventory = !empty($product) ? Yii::$app->general->getUnionConfigResult($product->union_code, 'batch_no_wise_inventory', $this) : '';
                    // $batchNoWiseInventory = !empty($product) ? Yii::$app->general->getUnionConfiguration($product->union_code, 'batch_no_wise_inventory', 'PORTAL') : '';
                    $product_type = !empty($product) ? $product->x_col3 : '';
                    return ($batchNoWiseInventory == 1 && $product_type == 2);
                },
                'on' => ['saleProduct', 'SaleImport', 'saleProductOnDispatch']],
                [['data_lock'], 'default', 'value' => 0],
                [['transaction_no', 'sales_order_no', 'delivery_no', 'billing_no'], 'safe'],
            //   [['quantity'], 'integer', 'except' => ['locksale', 'androidsync', 'androidsyncsplit']],
            [['product_sale_transaction_code'], 'validateDuplicate', 'on' => ['androidsync']],
                [['quantity'], 'validateDispatchQty', 'on' => ['saleProductOnDispatch']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_transaction_code' => Yii::t('app', 'Sale Detail Code'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'product_code' => Yii::t('app', 'Product'),
            'product_sale_rate_applicability_code' => Yii::t('app', 'Rate App Code'),
            'rate' => Yii::t('app', 'Rate'),
            'quantity' => Yii::t('app', 'Quantity'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'amount_due' => Yii::t('app', 'Total Amount'),
            'unit_code' => Yii::t('app', 'Unit'),
            'tax_code' => Yii::t('app', 'Tax'),
            'tax_amount' => Yii::t('app', 'Tax Amount'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCode0() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSaleCode() {
        return $this->hasOne(TblProductSale::className(), ['product_sale_code' => 'product_sale_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleTransactionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductSaleTransactionQuery(get_called_class());
    }

    public function getTransData($invoice_no) {
        return $this->find()->select(['product_sale_transaction_code', 'amount',
                    'quantity', 'rate', 'product_sale_code', 'product_code',
                ])->where(['product_sale_code' => $invoice_no])->all();
    }

    public function productSaleDetail($product_info, $dcs_code) {
        $sale_detail = $this->find()
                ->joinWith('productSaleCode')
                ->select(['tbl_product_sale_details.*', 'tbl_product_sale.*'])
                ->where(['tbl_product_sale_details.product_code' => $product_info['product_code']])
                ->andWhere(['tbl_product_sale.dcs_code' => $dcs_code])
                ->andFilterWhere(['>=', 'tbl_product_sale.sale_date_time', $product_info['from_date']])
                ->andFilterWhere(['<=', 'tbl_product_sale.sale_date_time', $product_info['to_date']])
                ->all();
        $sale_details = [];
        $sale_product = [];
        if (!empty($sale_detail)) {
            foreach ($sale_detail as $product_detail) {
                $product_name = Yii::$app->general->getforeignkey($product_detail->productCode, 'product_name');
                $sale_product['dcs_code'] = $product_detail['productSaleCode']->dcs_code;
                $type = Yii::$app->general->getforeignkey($product_detail->productSaleCode, 'type');
                $sale_product['dcs_name'] = '';
                $sale_product['member_name'] = '';
                if ($type = 'MEMBER') {
                    $sale_product['dcs_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->dcsCode, 'dcs_name');
                    $sale_product['member_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->memberCode, 'member_name');
                }
                if ($type = 'DCS') {
                    $sale_product['dcs_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->dcsBmcCode, 'bmc_name');
                    $sale_product['member_name'] = Yii::$app->general->getforeignkey($product_detail['productSaleCode']->memberDcsCode, 'dcs_name');
                }
                $sale_product['product_name'] = $product_name;
                $sale_product['rate'] = $product_detail->rate;
                $sale_product['quantity'] = $product_detail->quantity;
                $sale_product['amount'] = $product_detail->amount;
                $sale_product['sale_date_time'] = $product_detail['productSaleCode']->sale_date_time;
                $sale_details[] = $sale_product;
            }
        }
        return $sale_details;
    }

    public function getSaleInstallments() {
        return $this->hasMany(TblSaleInstallments::className(), ['product_sale_code' => 'product_sale_code']);
    }

    public function setTransactionSaveDeleteData(&$model, $json, &$childModel, &$delete) {
        $scenario = $model->scenario;
        $scenario .= 'split';
        $saleModel = new TblProductSale();
        $saleModel->x_col2 = $model->product_sale_code;
        $saleModelData = $saleModel->find()->where(['x_col2' => $saleModel->x_col2])->orderBy('created_at desc')->one();
        if (!empty($saleModelData)) {
            $model->x_col2 = $model->product_sale_transaction_code;
            $model->product_sale_code = $saleModelData->product_sale_code;
            $model->product_sale_transaction_code = Yii::$app->general->getTransactionCode($model, $model->product_sale_code);
        } else {
            $modelData = $model->find()->where(['product_sale_transaction_code' => $model->product_sale_transaction_code])->one();
            if (!empty($modelData)) {
                $model->x_col2 = $model->product_sale_transaction_code;
                $model->product_sale_transaction_code = Yii::$app->general->getTransactionCode($model, $model->product_sale_code);
            }
        }
        $productSaleData = $model->productSaleCode;
        if (!empty($productSaleData)) {
            $product = $model->productCode;
            $union = !empty($product) ? $product->unionCode : [];
            $manageStock = (!empty($product) && $product->x_col3 == '2') ? TRUE : FALSE;
            if (!empty($union) && $union->eipl_code == 'PRABHAT' && $product->dpu_product_code == '994') {
                $loanModel = new TblLoanProductSaleDetails();
                $loanModel->attributes = $model->attributes;
                $updateData = FALSE;
                if (!empty($productSaleData->union_code) && $productSaleData->union_code == '003') {
                    $updateData = TRUE;
                    $loanModel->union_code = $productSaleData->union_code;
                    $loanModel->dcs_code = $productSaleData->dcs_code;
                    $loanModel->sale_date_time = $productSaleData->invoice_date;
                    if ($productSaleData->customer_type == 'MEMBER') {
                        $loanModel->member_code = $productSaleData->customer_code;
                    }
                    $loanModel->product_code = 1;
                    $saleInstModel = new TblSaleInstallments();
                    $saleInstModelData = $saleInstModel->getProductSaleInstData($model->product_sale_code);
                    if (!empty($saleInstModelData)) {
                        foreach ($saleInstModelData as $saleInstModelRecord) {
                            $delete[] = $saleInstModelRecord;
                        }
                    }
                }
                if ($updateData) {
                    $loanModel->send_status = 0;
                    $loanModel->entry_type = 2;
                    $loanModel->created_by = 'CRON';
                    $model = $loanModel;
                    $manageStock = FALSE;
                }
            }
            if ($manageStock) {
                $setTxnCode = $model->product_sale_transaction_code;
                $config = Yii::$app->general->getUnionConfiguration($productSaleData->union_code, 'stock_check_on_sale', 'PORTAL');
                $stock_config_on = ($config == '1') ? TRUE : FALSE;
                // if ($stock_config_on && date('Y-m-d', strtotime($productSaleData->invoice_date)) >= '2023-05-01') {
                if ($stock_config_on) {
                    $qty = !empty($model->quantity) ? $model->quantity : 0;
                    $stockManageIndex = 0;
                    if (!empty($qty) && !empty($productSaleData->product_sale_code)) {
                        $fstockModel = new TblProductStock();
                        if ($model->originating_org_type != 'PORTAL') {
                            $sale_type = $model->originating_org_type;
                            $sale_code = $model->originating_org_code;
                        } else {
//                    $sale_type = strtoupper($productSaleData->customer_type) == 'MEMBER' ? 'DCS' : 'BMC';
                            $sale_type = 'BMC';
                            $sale_code = $productSaleData->bmc_code;
                            if (strtoupper($productSaleData->customer_type) == 'MEMBER') {
                                if ($model->originating_org_type == 'DCS' || $model->originating_org_type == 'VLC') {
                                    $sale_type = 'DCS';
//                        $sale_code = !empty($productSaleData->memberCode) ? $productSaleData->memberCode->dcs_code : $sale_code;
                                    $sale_code = !empty($model->originating_org_code) ? $model->originating_org_code : $sale_code;
                                }
                            }
                        }
                        $fstockModel->setCodes($sale_type, $sale_code);
                        $fstockModel->product_code = $model->product_code;
                        $fstockModel->union_code = $productSaleData->union_code;
                        $txn_type = strtoupper($productSaleData->customer_type) == 'MEMBER' ? 'PRODUCT SALE TO MEMBER' : 'PRODUCT SALE';
                        $existfromStock = $fstockModel->getAvailableStock($sale_type);
                        $considerQty = 0;
                        $i = 2;
                        $i_stock = 1;
                        foreach ($existfromStock as $stockData) {
                            if ($qty > 0) {
                                $considerQty = $stockData->stock;
                                if ($considerQty >= $qty) {
                                    $considerQty = $qty;
                                }
                                $qty = $qty - $considerQty;

                                $historyModel = new TblProductStockHistory();
                                Yii::$app->operation->history($stockData, $historyModel, 'UPDATE');
                                $childModel[] = $historyModel;

                                $stockData->setCodes($sale_type, $sale_code);
                                $stockData->product_code = $model->product_code;
                                $stockData->union_code = $productSaleData->union_code;
                                $f_stock = $stockData->stock;
                                $stockData->stock = $f_stock - $considerQty;
                                $setTxnCode = $model->product_sale_transaction_code;

                                if ($stockManageIndex > 0) {
                                    $saleTxnModel = new TblProductSaleTransaction();
                                    $saleTxnModel->attributes = $model->attributes;
                                    $saleTxnModel->scenario = $scenario;
                                    unset($saleTxnModel->created_at);
                                    unset($saleTxnModel->created_by);
                                    $saleTxnModel->quantity = $considerQty;
                                    $saleTxnModel->sap_batch_no = $stockData->sap_batch_no;
                                    $saleTxnModel->amount = $saleTxnModel->quantity * $saleTxnModel->rate;
                                    $saleTxnModel->product_sale_transaction_code = Yii::$app->general->getTransactionCode($model, $model->product_sale_code, $i);
                                    $setTxnCode = $saleTxnModel->product_sale_transaction_code;
                                    $childModel[] = $saleTxnModel;
                                } else {
                                    $model->quantity = $considerQty;
                                    $model->sap_batch_no = $stockData->sap_batch_no;
                                    $model->amount = $model->quantity * $model->rate;
                                }
                                $stockManageIndex++;

                                $loopStockTxnModel = new TblProductStockTransaction();
                                $loopStockTxnModel->attributes = $stockData->attributes;
                                unset($loopStockTxnModel->created_at);
                                unset($loopStockTxnModel->created_by);
                                $loopStockTxnModel->product_stock_transaction_code = $loopStockTxnModel->getCode($i_stock);
                                $loopStockTxnModel->old_value = $f_stock;
                                $loopStockTxnModel->new_value = $considerQty;
                                $loopStockTxnModel->final_value = $stockData->stock;
                                $loopStockTxnModel->transaction_type = $txn_type;
                                $loopStockTxnModel->transaction_date = date('Y-m-d');
                                $loopStockTxnModel->reference_code = $setTxnCode; //$model->product_sale_transaction_code;
                                $childModel[] = $stockData;
                                $childModel[] = $loopStockTxnModel;
                                $i++;
                                $i_stock++;
                            } else {
                                break;
                            }
                        }

                        if ($qty > 0) {
                            $exist_null_stock = $fstockModel->getExistStockDelete($sale_type);
                            if (!empty($exist_null_stock)) {
                                $fstockModel = $exist_null_stock;
                                $historyModel = new TblProductStockHistory();
                                Yii::$app->operation->history($fstockModel, $historyModel, 'UPDATE');
                                $childModel[] = $historyModel;
                            } else {
                                $fstockModel->product_stock_code = $fstockModel->getCode();
                                $fstockModel->stock = 0;
                                $fstockModel->x_col1 = Yii::$app->general->getUuid();
                            }

                            if (!empty($existfromStock)) {
                                $saleTxnModel = new TblProductSaleTransaction();
                                $saleTxnModel->scenario = $scenario;
                                $saleTxnModel->attributes = $model->attributes;
                                $saleTxnModel->quantity = $qty;
                                $saleTxnModel->sap_batch_no = NULL; //$stockData->sap_batch_no;
                                $saleTxnModel->amount = $saleTxnModel->quantity * $saleTxnModel->rate;
                                $saleTxnModel->product_sale_transaction_code = Yii::$app->general->getTransactionCode($model, $model->product_sale_code, $i);
                                $setTxnCode = $saleTxnModel->product_sale_transaction_code;
                                $childModel[] = $saleTxnModel;
                            }

                            $fstockTxnModel = new TblProductStockTransaction();
                            $fstockTxnModel->attributes = $fstockModel->attributes;
                            unset($fstockTxnModel->created_at);
                            unset($fstockTxnModel->created_by);
                            $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($i_stock);
                            $fstockTxnModel->old_value = $fstockModel->stock;
                            $fstockTxnModel->new_value = $qty;
                            $fstockModel->stock = $fstockModel->stock - $qty;
                            $fstockTxnModel->final_value = $fstockModel->stock;
                            $fstockTxnModel->transaction_type = $txn_type;
                            $fstockTxnModel->transaction_date = date('Y-m-d');
                            $fstockTxnModel->reference_code = $setTxnCode;
                            $childModel[] = $fstockModel;
                            $childModel[] = $fstockTxnModel;
                        }
                    }
                }
            }
        }
    }

    public function validateQty($attribute, $param) {
        $config = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['stock_check_on_sale']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['stock_check_on_sale'] : '';
        $productType = Yii::$app->general->getforeignkey($this->productCode, 'x_col3');
        if ($config == 1 && $productType != 1) {
            if ($this->available_stock < $this->quantity) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be less than Available Stock ' . $this->available_stock));
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];

        if (!empty($this->productSaleCode->customer_code)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->productSaleCode->customer_code);
        } else if (!empty($this->productSaleCode->bmc_code)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->productSaleCode->bmc_code, '', '');
        } else if (!empty($this->productSaleCode->mcc_plant_code)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->productSaleCode->mcc_plant_code, '', '', '');
        }
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->productSaleCode->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function validateDuplicate($attribute, $param) {
        $productSaleData = TblProductSale::find()->where(['x_col2' => $this->product_sale_code])->orderBy('created_at desc')->one();
        if (empty($productSaleData)) {
            $productSaleData = $this->productSaleCode;
        }
        if (!empty($productSaleData)) {
            $productSaleData->invoice_date = date('Y-m-d H:i:s', strtotime($productSaleData->invoice_date)) . '.000000';
            $product = $this->productCode;
            $union = !empty($product) ? $product->unionCode : [];
            if (!empty($union) && $union->eipl_code == 'PRABHAT' && $product->dpu_product_code == '994') {
                $cnt = TblLoanProductSaleDetails::find()
                        ->where(['sale_date_time' => $productSaleData->invoice_date, 'dcs_code' => $productSaleData->dcs_code])
                        ->andWhere(["ISNULL(member_code,'')" => ($productSaleData->customer_type == 'MEMBER') ? $productSaleData->customer_code : ''])
                        ->count();
                if (!empty($cnt) && $cnt > 0) {
                    $this->addError($attribute, Yii::t('app/validation', 'Duplplicate Record Found-Loan.'));
                }
            } else {
                $cnt = $this->find()
                        ->innerJoinWith(['productSaleCode'])
                        ->where(['tbl_product_sale.invoice_date' => $productSaleData->invoice_date])
                        ->andWhere(['tbl_product_sale.customer_code' => $productSaleData->customer_code, 'tbl_product_sale.customer_type' => $productSaleData->customer_type])
                        ->andWhere(['tbl_product_sale_transaction.product_code' => $this->product_code])
                        ->count();
                if (!empty($cnt) && $cnt > 0) {
                    $this->addError($attribute, Yii::t('app/validation', 'Duplplicate Record Found.'));
                }
            }
        } else {
            $this->addError($attribute, Yii::t('app/validation', 'Product Sale Record Not Found.'));
        }
    }

    public function validProduct($attribute, $param) {
        $product = TblProduct::find()->where(['product_code' => $this->product_code, 'is_active' => 1])->one();
        if (!empty($product)) {
            $this->unit_code = $product->unit_code;
            return true;
        }
        $this->addError($attribute, Yii::t('app/validation', 'Product Record Not Found'));
        return false;
    }

    public function validateDispatchQty($attribute, $param) {
        $config = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['stock_check_on_sale']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['stock_check_on_sale'] : '';
        $productType = Yii::$app->general->getforeignkey($this->productCode, 'x_col3');
        if ($config == 1 && $productType != 1) {
            $totalAvailableStock = TblProductStock::find()->where(['union_code' => $this->union_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'product_code' => $this->product_code])->andWhere(['AND', ['is', 'dcs_code', NULL]])->andWhere(['>', 'stock', 0])->sum('stock');
            if ($totalAvailableStock < $this->quantity) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be less than Available Stock ' . $totalAvailableStock));
            }
        }
    }

}
