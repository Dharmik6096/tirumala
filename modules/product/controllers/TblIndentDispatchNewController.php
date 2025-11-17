<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblIndentDispatch;
use app\modules\product\models\TblIndentDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblIndentMasterSearch;
use app\modules\product\models\TblIndentMaster;
use app\modules\product\models\TblIndentMasterHistory;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\product\models\TblProductReceipt;
use app\modules\product\models\TblProductReceiptTransaction;
use app\modules\product\models\TblProductStockSearch;
use app\modules\payment\models\TblProductSale;
use app\modules\payment\models\TblProductSaleTransaction;
use app\modules\payment\models\TblSaleInstallments;
use yii\base\Model;
use yii\helpers\Url;

/**
 * TblIndentDispatchController implements the CRUD actions for TblIndentDispatch model.
 */
class TblIndentDispatchNewController extends \app\controllers\ChildController {

    public $searchModel;
    public $dataProvider;

    /**
     * Lists all TblIndentDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblIndentDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexOther() {
        $searchModel = new TblIndentDispatchSearch();
        $dataProvider = $searchModel->searchNew(Yii::$app->request->queryParams);

        return $this->render('index_other', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblIndentDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($reference_no, $vehicle_no, $dispatch_date) {
        $model = TblIndentDispatch::find()->where(['reference_no' => $reference_no, 'vehicle_no' => $vehicle_no, 'dispatch_date' => $dispatch_date])->one();
        $searchModel = new TblIndentDispatchSearch();
        $param['TblIndentDispatchSearch'] = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->searchNew($param, false);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateOther() {
        $dispatchModel = new TblIndentDispatch();
        $searchModel = new TblIndentMasterSearch();
        $searchModel->scenario = 'indentApprove';
        $param = Yii::$app->request->queryParams;
        $indentMasterParam = !empty($param['TblIndentMasterSearch']) ? $param['TblIndentMasterSearch'] : [];
        $param['TblIndentMasterSearch']['group_by'] = (Yii::$app->request->isPost) ? 0 : (!empty($indentMasterParam['group_by']) ? $indentMasterParam['group_by'] : 0);
        $dataProvider = $searchModel->indentdispatchothernewsearch($param);
        $indentModel = $dataProvider->getModels();
        $stock_detail = $this->getProductDetail($indentMasterParam);
        if (Yii::$app->request->post()) {
            Model::loadMultiple($indentModel, Yii::$app->request->post(), 'TblIndentDispatch');
            if (Model::validateMultiple($indentModel)) {
                $indentPostData = Yii::$app->request->post()['TblIndentDispatch'];
                $DispatchConsiderAs = Yii::$app->general->getUnionConfiguration($indentMasterParam['union_code'], 'dispatch_consider', 'PORTAL');
                $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration($indentMasterParam['union_code'], 'batch_no_wise_inventory', 'PORTAL');
                $batchNoWiseProductRate = Yii::$app->general->getUnionConfiguration($indentMasterParam['union_code'], 'batch_no_wise_product_rate', 'PORTAL');
                $txnType = ($DispatchConsiderAs == 0 || $indentMasterParam['customer_type'] == 'BULKVEN') ? 'PRODUCT SALE' : '';
                $searchModel->load(Yii::$app->request->post());
                if (isset($_REQUEST['selection'])) {
                    $saveModel = [];
                    $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                    $msg = 'Indent Dispatch';
                    $i = 1;
                    $j = 1;
                    $k = 1;
                    $setOldVal = [];
                    $productStockCode = [];
                    $existingProducts = [];
                    $productStock = [];
                    $existfromTotalStock = [];
                    $originalTotalStock = [];
                    foreach ($codes as $code) {
                        $existData = TblIndentMaster::find()->where(['indent_code' => $code, 'status' => 2])->one();
                        $bmc = $existData->bmc_code;
                        $customer_code = $existData->customer_code;
                        $type = $existData->customer_type;
                        $dcs = $existData->dcs_code;
                        $product = $existData->product_code;
                        $approve_qty = $existData->approve_qty;
                        $disp_qty = $indentPostData[$code]['dispatch_qty'] == "" ? 0 : $indentPostData[$code]['dispatch_qty'];
                        $warehouse = isset($existData->warehouse_code) ? $existData->warehouse_code : '';
                        $dispatch = new TblIndentDispatch();
                        $dispatch->setAttributes($searchModel->attributes);
                        $dispatch->indent_dispatch_code = Yii::$app->general->getCodeAutoIncrement($dispatch, $i);
                        $dispatch->indent_code = $code;
                        $dispatch->challan_date = date('Y-m-d');
                        $dispatch->vehicle_no = $_REQUEST['vehicle'];
                        $dispatch->dispatch_date = $dispatch->challan_date;
                        $dispatch->reference_no = $_REQUEST['ref_no'];
                        $dispatch->lr_no = $_REQUEST['lrno'];
                        $dispatch->dcs_code = $dcs;
                        $dispatch->product_code = $product;
                        $dispatch->customer_code = ($type == 'BULKVEN') ? $customer_code : $dcs;
                        $dispatch->customer_type = ($type == 'BULKVEN') ? 'BULKVEN' : 'DCS';
                        $dispatch->status = 5;
                        $dispatch->route_code = $searchModel->route_code;
                        $dispatch->dispatch_qty = $disp_qty;

                        //set from stock
                        $qty = $disp_qty;
                        $batch = '';
                        $batchQuantities = [];

                        if (empty($warehouse)) { //gyandhara plant stock is not available //Sunita 03/03/2023
                            $fstockModel = new TblProductStock();
                            $fstockModel->setCodes('BMC', $dispatch->bmc_code);
                            $fstockModel->product_code = $product;
                            $fstockModel->union_code = $dispatch->union_code;

                            $productUniqueKey = $dispatch->union_code . '#' . $product . '#' . $dispatch->mcc_plant_code;
                            if (!in_array($productUniqueKey, $existingProducts)) {
                                $existfromStock = $fstockModel->getAvailableStock('BMC', $batch);
                                $totalStockData = $fstockModel->getTotalAvailableStock();
                                $existfromTotalStock[$productUniqueKey] = $totalStockData;
                                $originalTotalStock[$productUniqueKey] = $totalStockData;
                                $existingProducts[] = $productUniqueKey;
                            } else {
                                $existfromStock = $fstockModel->getAvailableStock('BMC', $batch, false, $productStockCode);
                            }

                            $initial_stock = 0;

                            $remaining_quantity = $qty;
                            if (!empty($existfromStock)) {
                                foreach ($existfromStock as $existStock) {
                                    if ($remaining_quantity <= 0) {
                                        break;
                                    }
                                    $historyModel = new TblProductStockHistory();
                                    Yii::$app->operation->history($existStock, $historyModel, UPDATE);
                                    $saveModel[] = $historyModel;
                                    if (array_key_exists($existStock->product_stock_code, $productStock)) {
                                        $existStock->stock = $productStock[$existStock->product_stock_code];
                                    }
                                    $initial_stock = $existStock->stock;
                                    $dispatch_quantity = min($remaining_quantity, $initial_stock);
                                    $existStock->stock = $existStock->stock - $dispatch_quantity;
                                    $remaining_quantity = $remaining_quantity - $dispatch_quantity;

                                    $fstockModel = $existStock;

                                    if ($fstockModel->stock == 0) {
                                        $productStockCode[] = $fstockModel->product_stock_code;
                                    }
                                    $productStock[$fstockModel->product_stock_code] = $fstockModel->stock;

                                    $batch = $fstockModel->sap_batch_no;
                                    $saveModel[] = $fstockModel;

                                    $fstockTxnModel = new TblProductStockTransaction();
                                    $fstockTxnModel->attributes = $fstockModel->attributes;
                                    unset($fstockTxnModel->created_at);
                                    unset($fstockTxnModel->created_by);
                                    $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($j);
                                    $fstockTxnModel->old_value = $initial_stock;
                                    $fstockTxnModel->new_value = $dispatch_quantity;
                                    $fstockTxnModel->final_value = $fstockModel->stock;
                                    $fstockTxnModel->transaction_type = !empty($txnType) ? $txnType : 'INVENTORY TRANSFER';
                                    $fstockTxnModel->transaction_date = date('Y-m-d');
                                    $fstockTxnModel->reference_code = $dispatch->indent_dispatch_code;
                                    $fstockTxnModel->x_col2 = 'Indent Dispatch';
                                    $saveModel[] = $fstockTxnModel;

                                    $receipt = new TblProductReceipt();
                                    $receipt->product_receipt_code = Yii::$app->general->getUuid();
                                    $receipt->grn_no = '1234';
                                    $receipt->grn_date = date('Y-m-d');
                                    $receipt->vendor_type = 'BMC';
                                    $receipt->vendor_code = $dispatch->mcc_plant_code;
                                    $receipt->union_code = $fstockModel->union_code;
                                    $receipt->plant_code = $fstockModel->plant_code;
                                    $receipt->mcc_plant_code = $fstockModel->mcc_plant_code;
                                    $receipt->bmc_code = NULL;
                                    $receipt->dcs_code = NULL;
                                    $saveModel[] = $receipt;

                                    $receiptTxn = new TblProductReceiptTransaction();
                                    $receiptTxn->product_receipt_transaction_code = Yii::$app->general->getTransactionCode($receiptTxn, $receipt->product_receipt_code);
                                    $receiptTxn->product_receipt_code = $receipt->product_receipt_code;
                                    $receiptTxn->product_code = $fstockModel->product_code;
                                    $receiptTxn->received_quantity = '-' . $dispatch_quantity;
                                    $receiptTxn->requested_quantity = $receiptTxn->received_quantity;
                                    $receiptTxn->dispatched_quantity = $receiptTxn->received_quantity;
                                    $receiptTxn->rejected_quantity = 0;
                                    $receiptTxn->rate = 0;
                                    $receiptTxn->amount = 0;
                                    $receiptTxn->remark = !empty($txnType) ? $txnType : 'INVENTORY TRANSFER';
                                    $saveModel[] = $receiptTxn;
                                    $j++;
                                    if (isset($batchQuantities[$batch])) {
                                        $batchQuantities[$batch]['qty'] = $remaining_quantity;
                                    } else {
                                        $batchQuantities[$batch]['qty'] = $dispatch_quantity;
                                    }
                                    $batchQuantities[$batch]['rate'] = $existStock->rate;
                                }
                            } else {
                                $productName = Yii::$app->general->getForeignKey($fstockModel->productCode, 'product_name');
                                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => "Stock for product '{$productName}' does not exist. Please try again."]);
                                return $this->redirect(Url::previous());
                            }
                        }

                        foreach ($batchQuantities as $batch => $totalQty) {
                            $checkStockFor = 'DCS';
                            //set to stock
                            $rate = $totalQty['rate'];
                            $totalQty = $totalQty['qty'];

                            $stockModel = new TblProductStock();
                            if ($type == 'BULKVEN') {
                                $stockModel->setCodes('BMC', $bmc);
                                $checkStockFor = 'BMC';
                            } else {
                                $stockModel->setCodes('DCS', $dcs);
                                $checkStockFor = 'DCS';
                            }

                            // $stockModel->setCodes('DCS', $dcs);

                            $stockModel->product_code = $product;
                            $stockModel->union_code = $dispatch->union_code;
                            $stockModel->sap_batch_no = $batch;
                            $existtoStock = $stockModel->getExistStock($checkStockFor, $batch);
                            $key = $stockModel->mcc_plant_code . '_' . $dcs . '_' . $stockModel->product_code . '_' . $batch;
                            $oldQty = 0;

                            if ($type != 'BULKVEN') {
                                if (!empty($existtoStock)) {
                                    if (empty($setOldVal[$key])) {
                                        $setOldVal[$key] = $existtoStock->stock;
                                    }
                                    $oldQty = $setOldVal[$key];
                                    $setOldVal[$key] = $setOldVal[$key] + $totalQty;

                                    $historyModel = new TblProductStockHistory();
                                    Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
                                    $historyModel->stock = $oldQty;
                                    $saveModel[] = $historyModel;
                                    $existtoStock->stock = $oldQty + $totalQty;
                                    $stockModel = $existtoStock;
                                } else {
                                    $stockModel->product_stock_code = $stockModel->getCode($k);
                                    $stockModel->stock = $oldQty + $totalQty;
                                    $stockModel->x_col1 = Yii::$app->general->getUuid();
                                    $stockModel->rate = $rate;
                                    $k++;
                                }
                                $saveModel[] = $stockModel;
                                if (array_key_exists($productUniqueKey, $existfromTotalStock)) {
                                    $existfromTotalStock[$productUniqueKey] -= $disp_qty;
                                }
                            }
                            $reference_code = $dispatch->indent_dispatch_code;
                            if ($type == 'BULKVEN') {
                                $reference_code = Yii::$app->general->getUuid();
                                $saleModel = new TblProductSale();
                                $saleModel->scenario = 'saleProductOnDispatch';
                                $sale_rate = $existData->rate;
                                if ($batchNoWiseInventory && $batchNoWiseProductRate) {
                                    $sale_rate = $existtoStock->rate;
                                }
                                $sale_amount = $disp_qty * $sale_rate;
                                $saleModel->attributes = $existData->attributes;
                                $saleModel->product_sale_code = $reference_code;
                                $saleModel->invoice_date = date('Y-m-d H:i:s');
                                $saleModel->payment_mode = 1;
                                $saleModel->deduction_start_date = date('Y-m-d');
                                $saleModel->amount_due = $sale_amount;
                                $saleModel->amount = $sale_amount;
                                $saleModel->other_amount = 0;
                                $saleModel->paid_amount = $saleModel->payment_mode == 1 ? 0 : $saleModel->amount_due;
                                $saleModel->is_installment = $saleModel->payment_mode == 1 ? 1 : 0;
                                $saleModel->no_of_installment = $saleModel->payment_mode == 1 ? 1 : 0;
                                unset($saleModel->created_at, $saleModel->created_by, $saleModel->updated_at, $saleModel->updated_by, $saleModel->originating_org_code, $saleModel->originating_org_type, $saleModel->originating_type);
                                $instAmount = floatval($saleModel->amount_due / $saleModel->no_of_installment);

                                $detailSaleModel = new TblProductSaleTransaction();
                                $detailSaleModel->scenario = 'saleProductOnDispatch';
                                $detailSaleModel->attributes = $saleModel->attributes;
                                $detailSaleModel->quantity = $disp_qty;
                                $detailSaleModel->rate = $sale_rate;
                                $detailSaleModel->sap_batch_no = $batch;
                                $detailSaleModel->tax_code = 1;
                                $detailSaleModel->available_stock = $existtoStock->stock;
                                $detailSaleModel->product_sale_code = $saleModel->product_sale_code;
                                $detailSaleModel->product_code = $product;
                                $detailSaleModel->product_sale_transaction_code = Yii::$app->general->getTransactionCode($detailSaleModel, $detailSaleModel->product_sale_code);
                                unset($detailSaleModel->created_at, $detailSaleModel->created_by, $detailSaleModel->updated_at, $detailSaleModel->updated_by, $detailSaleModel->originating_org_code, $detailSaleModel->originating_org_type, $detailSaleModel->originating_type);
                                $productSaleUniqueKey = $saleModel->union_code . '#' . $product . '#' . $saleModel->mcc_plant_code;
                                if (array_key_exists($productSaleUniqueKey, $existfromTotalStock)) {
                                    $config = isset(Yii::$app->session->get('unionConfig')[$saleModel->union_code]['stock_check_on_sale']) ? Yii::$app->session->get('unionConfig')[$saleModel->union_code]['stock_check_on_sale'] : '';
                                    $productData = $fstockModel->productCode;
                                    $newQty = $existfromTotalStock[$productSaleUniqueKey] - $totalQty;
                                    if ($newQty < 0 && $config == 1 && $productData->x_col3 != 1) {
                                        Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => "Dispatch Quantity for '$productData->product_name' must be less than Total Stock ($originalTotalStock[$productSaleUniqueKey])."]);
                                        return $this->redirect(Url::previous());
                                    }
                                    $existfromTotalStock[$productSaleUniqueKey] = $newQty;
                                }
                                $installmentModel = new TblSaleInstallments();
                                $installmentModel->attributes = $saleModel->attributes;
                                $installmentModel->main_amount = $saleModel->amount_due;
                                $installmentModel->installment_amount = $instAmount;
                                $installmentModel->payment_cycle_applicability_code = NULL;
                                $installmentModel->payment_cycle_code = NULL;
                                $installmentModel->installment_date = NULL;
                                $installmentModel->installment_status = 0;
                                $installmentModel->product_sale_installment_code = Yii::$app->general->getTransactionCode($installmentModel, $saleModel->product_sale_code, $j);
                                unset($installmentModel->created_at, $installmentModel->created_by, $installmentModel->updated_at, $installmentModel->updated_by, $installmentModel->originating_org_code, $installmentModel->originating_org_type, $installmentModel->originating_type);
                                $saveModel[] = $saleModel;
                                $saveModel[] = $detailSaleModel;
                                $saveModel[] = $installmentModel;
                            }
                            $stockTxnModel = new TblProductStockTransaction();
                            $stockTxnModel->attributes = $stockModel->attributes;
                            unset($stockTxnModel->created_at);
                            unset($stockTxnModel->created_by);
                            $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($j);
                            $stockTxnModel->old_value = $oldQty;
                            $stockTxnModel->new_value = $totalQty;
                            $stockTxnModel->final_value = ($type == 'BULKVEN') ? $totalQty : $stockModel->stock;
                            $stockTxnModel->transaction_type = ($type == 'BULKVEN') ? 'PRODUCT SALE TO BULKVEN' : (!empty($txnType) ? $txnType : 'INVENTORY RECEIVED');
                            $stockTxnModel->transaction_date = date('Y-m-d');
                            $stockTxnModel->reference_code = $reference_code;
                            $stockTxnModel->x_col2 = 'Indent Dispatch';
                            $saveModel[] = $stockTxnModel;

                            $receiptTo = new TblProductReceipt();
                            $receiptTo->product_receipt_code = Yii::$app->general->getUuid();
                            $receiptTo->grn_no = '1234';
                            $receiptTo->grn_date = date('Y-m-d');
                            $receiptTo->vendor_type = ($type == 'BULKVEN') ? 'BMC' : 'DCS';
                            $receiptTo->vendor_code = ($type == 'BULKVEN') ? $bmc : $dcs;
                            $receiptTo->union_code = $stockModel->union_code;
                            $receiptTo->plant_code = $stockModel->plant_code;
                            $receiptTo->mcc_plant_code = $stockModel->mcc_plant_code;
                            $receiptTo->bmc_code = $stockModel->bmc_code;
                            $receiptTo->dcs_code = $stockModel->dcs_code;
                            $saveModel[] = $receiptTo;

                            $receiptTxnTo = new TblProductReceiptTransaction();
                            $receiptTxnTo->product_receipt_transaction_code = Yii::$app->general->getTransactionCode($receiptTxnTo, $receiptTo->product_receipt_code, $j);
                            $receiptTxnTo->product_receipt_code = $receiptTo->product_receipt_code;
                            $receiptTxnTo->product_code = $stockModel->product_code;
                            $receiptTxnTo->received_quantity = $qty;
                            $receiptTxnTo->requested_quantity = $qty;
                            $receiptTxnTo->dispatched_quantity = $qty;
                            $receiptTxnTo->rejected_quantity = 0;
                            $receiptTxnTo->rate = 0;
                            $receiptTxnTo->amount = 0;
                            $receiptTxnTo->remark = ($type == 'BULKVEN') ? 'PRODUCT SALE TO BULKVEN' : (!empty($txnType) ? $txnType : 'INVENTORY RECEIVED');
                            $saveModel[] = $receiptTxnTo;
                            $j++;
                        }

                        $saveModel[] = $dispatch;
                        if (!empty($existData)) {
                            $historyModel = new TblIndentMasterHistory();
                            Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existData->dispatch_qty = $existData->dispatch_qty + $disp_qty;
                            $existData->status_by = \Yii::$app->user->identity->user_code;
                            $existData->status_date = date('Y-m-d H:i:s');

                            if ($indentPostData[$code]['is_close'] == 1 || $existData->dispatch_qty == $approve_qty) {
                                $existData->status = 5;
                                $existData->is_close = 1;
                            }
                            $saveModel[] = $existData;
                        }
                        $i++;
                    }
                    $transaction = $this->generalModel->saveTransaction($saveModel, [$msg, 'create']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index-other']);
                    }
                }
            }
        }
        return $this->render('create_other', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dispatchModel' => $dispatchModel,
                    'stock_detail' => $stock_detail
        ]);
    }

    /**
     * Finds the TblIndentDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblIndentDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblIndentDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getProductDetail($param) {
        $bmc_code = !empty($param['bmc_code']) ? $param['bmc_code'] : '';
        $existData = TblProductStock::find()
                ->select(['tbl_product_stock.product_code', 'tbl_product.product_name', 'SUM(tbl_product_stock.stock) as total_stock'])
                ->innerJoin('tbl_product', 'tbl_product.product_code = tbl_product_stock.product_code')
                ->where(['tbl_product_stock.bmc_code' => $bmc_code])
                ->andWhere(['is', 'tbl_product_stock.dcs_code', NULL]);
        if (!empty($param['product_code'])) {
            $existData = $existData->andWhere(['tbl_product_stock.product_code' => $param['product_code']]);
        }
        // $existData = $existData->andWhere(['>', 'tbl_product_stock.stock', 0])
        $existData = $existData->groupBy(['tbl_product_stock.product_code', 'tbl_product.product_name'])
                ->asArray()
                ->all();
        return $existData;
    }

    public function actionChallen($dispatch_date, $lr_no, $vehicle_no, $report_type) {
        $controls = [];
        $controls['p_date'] = $dispatch_date;
        $controls['p_lr_no'] = $lr_no;
        $controls['p_vehicle_no'] = $vehicle_no;
        if ($report_type == 'MilkChillBillCenterWise') {
            $controls['p_report_name'] = 'Milk Chill Bill Center Wise';
            $this->printDocument($controls, 'vsp/MilkChillBillCenterWise', 'MilkChillBillCenterWise', 'pdf');
        } else if ($report_type == 'MilkChillingBillLrNoWise') {
            $controls['p_report_name'] = 'Milk Chilling Bill Lr No Wise';
            $this->printDocument($controls, 'vsp/MilkChillingBillLrNoWise', 'MilkChillingBillLrNoWise', 'pdf');
        }
    }

    public function actionCloseIndent($id) {
        $indent = TblIndentMaster::findOne(['indent_code' => $id]);
        $history = new TblIndentMasterHistory();
        Yii::$app->operation->history($indent, $history, UPDATE);
        $indent->is_close = 1;

        $transaction = $this->generalModel->saveTransaction([$indent, $history], ['Indent Master', 'edit']);
        $message = $transaction == 'customRedirect' ? ['type' => 'success', 'message' => 'Indent closed successfully.'] : ['type' => 'error', 'message' => 'Failed to close indent. Please try again.'];

        Yii::$app->getSession()->setFlash('success', $message);
        return $this->redirect(Url::previous());
    }

}

?>