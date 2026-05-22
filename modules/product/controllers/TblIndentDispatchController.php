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
use yii\base\Model;
use yii\helpers\Url;

/**
 * TblIndentDispatchController implements the CRUD actions for TblIndentDispatch model.
 */
class TblIndentDispatchController extends \app\controllers\ChildController {

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblIndentDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $dispatchModel = new TblIndentDispatch();
        $searchModel = new TblIndentMasterSearch();
        $searchModel->scenario = 'indentApprove';
        if (Yii::$app->request->post()) {
            $searchModel->load(Yii::$app->request->post());
            $DispatchConsiderAs = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'dispatch_consider', 'PORTAL');
            $txnType = $DispatchConsiderAs == 1 ? 'PRODUCT SALE TO MEMBER' : '';
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = 5;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $msg = 'Indent Dispatch';
                $where = [];
                $i = 1;
                $j = 1;
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $dcs = $data[0];
                    $product = $data[1];
                    $disp_qty = $data[2];
                    $dispatch = new TblIndentDispatch();
                    $dispatch->setAttributes($searchModel->attributes);
                    $dispatch->indent_dispatch_code = Yii::$app->general->getCodeAutoIncrement($dispatch, $i);
                    $dispatch->challan_date = date('Y-m-d');
                    $dispatch->vehicle_no = $_REQUEST['vehicle'];
                    $dispatch->dispatch_date = $dispatch->challan_date;
                    $dispatch->reference_no = $_REQUEST['ref_no'];
                    $dispatch->lr_no = $_REQUEST['lrno'];
                    $dispatch->dcs_code = $dcs;
                    $dispatch->product_code = $product;
                    $dispatch->customer_code = $dcs;
                    $dispatch->customer_type = 'DCS';
                    $dispatch->status = 5;
                    $dispatch->route_code = $searchModel->route_code;
                    $dispatch->dispatch_qty = $disp_qty;

                    //set from stock
                    $fstockModel = new TblProductStock();
                    $fstockModel->setCodes('BMC', $dispatch->mcc_plant_code);
                    $fstockModel->product_code = $product;
                    $fstockModel->union_code = $dispatch->union_code;
                    $batch = '';
                    $existfromStock = $fstockModel->getExistStock('BMC', $batch);

                    $f_stock = 0;
                    $qty = $disp_qty;

                    if (!empty($existfromStock)) {
                        $historyModel = new TblProductStockHistory();
                        Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;
                        $f_stock = $existfromStock->stock;
                        $existfromStock->stock = $f_stock - $qty;
                        $fstockModel = $existfromStock;
                    } else {
                        $fstockModel->product_stock_code = $fstockModel->getCode($j);
                        $fstockModel->stock = $f_stock - $qty;
                        $fstockModel->x_col1 = Yii::$app->general->getUuid();
                    }
                    $saveModel[] = $fstockModel;

                    $fstockTxnModel = new TblProductStockTransaction();
                    $fstockTxnModel->attributes = $fstockModel->attributes;
                    unset($fstockTxnModel->created_at);
                    unset($fstockTxnModel->created_by);
                    $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($j);
                    $fstockTxnModel->old_value = $f_stock;
                    $fstockTxnModel->new_value = $qty;
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
                    $receiptTxn->product_receipt_transaction_code = Yii::$app->general->getNextTransactionCode($receiptTxn, $receipt->product_receipt_code);
                    $receiptTxn->product_receipt_code = $receipt->product_receipt_code;
                    $receiptTxn->product_code = $fstockModel->product_code;
                    $receiptTxn->received_quantity = '-' . $qty;
                    $receiptTxn->requested_quantity = $receiptTxn->received_quantity;
                    $receiptTxn->dispatched_quantity = $receiptTxn->received_quantity;
                    $receiptTxn->rejected_quantity = 0;
                    $receiptTxn->rate = 0;
                    $receiptTxn->amount = 0;
                    $receiptTxn->remark = !empty($txnType) ? $txnType : 'INVENTORY TRANSFER';
                    $saveModel[] = $receiptTxn;

                    $j++;

                    //set to stock
                    $stockModel = new TblProductStock();
                    $stockModel->setCodes('DCS', $dcs);

                    $stockModel->product_code = $product;
                    $stockModel->union_code = $dispatch->union_code;
                    $stockModel->sap_batch_no = $batch;
                    $existtoStock = $stockModel->getExistStock('DCS', $batch);

                    $t_stock = 0;
                    if (!empty($existtoStock)) {
                        $historyModel = new TblProductStockHistory();
                        Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;
                        $t_stock = $existtoStock->stock;
                        $existtoStock->stock = $t_stock + $qty;
                        $stockModel = $existtoStock;
                    } else {
                        $stockModel->product_stock_code = $stockModel->getCode($j);
                        $stockModel->stock = $t_stock + $qty;
                        $stockModel->x_col1 = Yii::$app->general->getUuid();
                    }
                    $saveModel[] = $stockModel;

                    $stockTxnModel = new TblProductStockTransaction();
                    $stockTxnModel->attributes = $stockModel->attributes;
                    unset($stockTxnModel->created_at);
                    unset($stockTxnModel->created_by);
                    $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($j);
                    $stockTxnModel->old_value = $t_stock;
                    $stockTxnModel->new_value = $qty;
                    $stockTxnModel->final_value = $stockModel->stock;
                    $stockTxnModel->transaction_type = !empty($txnType) ? $txnType : 'INVENTORY RECEIVED';
                    $stockTxnModel->transaction_date = date('Y-m-d');
                    $stockTxnModel->reference_code = $dispatch->indent_dispatch_code;
                    $stockTxnModel->x_col2 = 'Indent Dispatch';
                    $saveModel[] = $stockTxnModel;

                    $receiptTo = new TblProductReceipt();
                    $receiptTo->product_receipt_code = Yii::$app->general->getUuid();
                    $receiptTo->grn_no = '1234';
                    $receiptTo->grn_date = date('Y-m-d');
                    $receiptTo->vendor_type = 'DCS';
                    $receiptTo->vendor_code = $dcs;
                    $receiptTo->union_code = $stockModel->union_code;
                    $receiptTo->plant_code = $stockModel->plant_code;
                    $receiptTo->mcc_plant_code = $stockModel->mcc_plant_code;
                    $receiptTo->bmc_code = $stockModel->bmc_code;
                    $receiptTo->dcs_code = $stockModel->dcs_code;
                    $saveModel[] = $receiptTo;

                    $receiptTxnTo = new TblProductReceiptTransaction();
                    $receiptTxnTo->product_receipt_transaction_code = Yii::$app->general->getNextTransactionCode($receiptTxnTo, $receiptTo->product_receipt_code, $j);
                    $receiptTxnTo->product_receipt_code = $receiptTo->product_receipt_code;
                    $receiptTxnTo->product_code = $stockModel->product_code;
                    $receiptTxnTo->received_quantity = $qty;
                    $receiptTxnTo->requested_quantity = $qty;
                    $receiptTxnTo->dispatched_quantity = $qty;
                    $receiptTxnTo->rejected_quantity = 0;
                    $receiptTxnTo->rate = 0;
                    $receiptTxnTo->amount = 0;
                    $receiptTxnTo->remark = !empty($txnType) ? $txnType : 'INVENTORY RECEIVED';
                    $saveModel[] = $receiptTxnTo;

                    $saveModel[] = $dispatch;
                    $existIndentData = TblIndentMaster::find()->where(['dcs_code' => $dcs, 'product_code' => $product, 'status' => 2])->all();
                    if (!empty($existIndentData)) {
                        foreach ($existIndentData as $indent) {
                            $historyModel = new TblIndentMasterHistory();
                            Yii::$app->operation->history($indent, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $indent->status = 5;
                            $indent->status_by = \Yii::$app->user->identity->user_code;
                            $indent->status_date = date('Y-m-d H:i:s');
                            $saveModel[] = $indent;
                        }
                    }
                    $i++;
                    $j++;
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, [$msg, 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        $dataProvider = $searchModel->indentdispatchsearch(Yii::$app->request->queryParams);

        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dispatchModel' => $dispatchModel,
        ]);
    }

    public function actionCreateOther() {
        $dispatchModel = new TblIndentDispatch();
        $searchModel = new TblIndentMasterSearch();
        $searchModel->scenario = 'indentApprove';
        $dataProvider = $searchModel->indentdispatchothersearch(Yii::$app->request->queryParams);

        $indentModel = $dataProvider->getModels();
        if (Yii::$app->request->post()) {
            Model::loadMultiple($indentModel, Yii::$app->request->post(), 'TblIndentDispatch');
            if (Model::validateMultiple($indentModel)) {
                $indentPostData = Yii::$app->request->post()['TblIndentDispatch'];
                $DispatchConsiderAs = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'dispatch_consider', 'PORTAL');
                $txnType = $DispatchConsiderAs == 1 ? 'PRODUCT SALE' : '';
                $searchModel->load(Yii::$app->request->post());
                if (isset($_REQUEST['selection'])) {
                    $saveModel = [];
                    $status = 5;
                    $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                    $msg = 'Indent Dispatch';
                    $where = [];
                    $i = 1;
                    $j = 1;
                    $setOldVal = [];
                    $productStockCode = [];
                    $existingProducts = [];
                    $productStock = [];
                    foreach ($codes as $code) {
                        $existData = TblIndentMaster::find()->where(['indent_code' => $code, 'status' => 2])->one();
                        $dcs = $existData->dcs_code;
                        $product = $existData->product_code;
//                        $disp_qty = $data[2];
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
                        $dispatch->customer_code = $dcs;
                        $dispatch->customer_type = 'DCS';
                        $dispatch->status = 5;
                        $dispatch->route_code = $searchModel->route_code;
                        $dispatch->dispatch_qty = $disp_qty;

                        //set from stock
                        $qty = $disp_qty;
                        $batch = '';

                        if (empty($warehouse)) { //gyandhara plant stock is not available //Sunita 03/03/2023
                            $fstockModel = new TblProductStock();
                            $fstockModel->setCodes('BMC', $dispatch->mcc_plant_code);
                            $fstockModel->product_code = $product;
                            $fstockModel->union_code = $dispatch->union_code;

                            $productUniqueKey = $dispatch->union_code . '#' . $product . '#' . $dispatch->mcc_plant_code;
                            if (!in_array($productUniqueKey, $existingProducts)) {
                                $existfromStock = $fstockModel->getAvailableStock('BMC', $batch);
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

                                    if (array_key_exists($existStock->product_stock_code, $productStock)) {
                                        $existStock->stock = $productStock[$existStock->product_stock_code];
                                    }
                                    $initial_stock = $existStock->stock;
                                    $dispatch_quantity = min($remaining_quantity, $initial_stock);
                                    $existStock->stock = $existStock->stock - $dispatch_quantity;
                                    $remaining_quantity = $remaining_quantity - $dispatch_quantity;

                                    $historyModel = new TblProductStockHistory();
                                    Yii::$app->operation->history($existStock, $historyModel, UPDATE);
                                    $saveModel[] = $historyModel;

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
                                    $receiptTxn->product_receipt_transaction_code = Yii::$app->general->getNextTransactionCode($receiptTxn, $receipt->product_receipt_code);
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
                                }
                            } else {
                                $productName = Yii::$app->general->getForeignKey($fstockModel->productCode, 'product_name');
                                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => "Stock for product '{$productName}' does not exist. Please try again."]);
                                return $this->redirect(Url::previous());
                            }
                        }

                        //set to stock
                        $stockModel = new TblProductStock();
                        $stockModel->setCodes('DCS', $dcs);

                        $stockModel->product_code = $product;
                        $stockModel->union_code = $dispatch->union_code;
                        $stockModel->sap_batch_no = $batch;
                        $existtoStock = $stockModel->getExistStock('DCS', $batch);
                        $key = $stockModel->mcc_plant_code . '_' . $dcs . '_' . $stockModel->product_code . '_' . $batch;
                        $oldQty = 0;

                        if (!empty($existtoStock)) {
                            if (empty($setOldVal[$key])) {
                                $setOldVal[$key] = $existtoStock->stock;
                            }
                            $oldQty = $setOldVal[$key];
                            $setOldVal[$key] = $setOldVal[$key] + $qty;

                            $historyModel = new TblProductStockHistory();
                            Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
                            $historyModel->stock = $oldQty;
                            $saveModel[] = $historyModel;
                            $existtoStock->stock = $oldQty + $qty;
                            $stockModel = $existtoStock;
                        } else {
                            $stockModel->product_stock_code = $stockModel->getCode($j);
                            $stockModel->stock = $oldQty + $qty;
                            $stockModel->x_col1 = Yii::$app->general->getUuid();
                        }
                        $saveModel[] = $stockModel;

                        $stockTxnModel = new TblProductStockTransaction();
                        $stockTxnModel->attributes = $stockModel->attributes;
                        unset($stockTxnModel->created_at);
                        unset($stockTxnModel->created_by);
                        $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($j);
                        $stockTxnModel->old_value = $oldQty;
                        $stockTxnModel->new_value = $qty;
                        $stockTxnModel->final_value = $stockModel->stock;
                        $stockTxnModel->transaction_type = !empty($txnType) ? $txnType : 'INVENTORY RECEIVED';
                        $stockTxnModel->transaction_date = date('Y-m-d');
                        $stockTxnModel->reference_code = $dispatch->indent_dispatch_code;
                        $stockTxnModel->x_col2 = 'Indent Dispatch';
                        $saveModel[] = $stockTxnModel;

                        $receiptTo = new TblProductReceipt();
                        $receiptTo->product_receipt_code = Yii::$app->general->getUuid();
                        $receiptTo->grn_no = '1234';
                        $receiptTo->grn_date = date('Y-m-d');
                        $receiptTo->vendor_type = 'DCS';
                        $receiptTo->vendor_code = $dcs;
                        $receiptTo->union_code = $stockModel->union_code;
                        $receiptTo->plant_code = $stockModel->plant_code;
                        $receiptTo->mcc_plant_code = $stockModel->mcc_plant_code;
                        $receiptTo->bmc_code = $stockModel->bmc_code;
                        $receiptTo->dcs_code = $stockModel->dcs_code;
                        $saveModel[] = $receiptTo;

                        $receiptTxnTo = new TblProductReceiptTransaction();
                        $receiptTxnTo->product_receipt_transaction_code = Yii::$app->general->getNextTransactionCode($receiptTxnTo, $receiptTo->product_receipt_code, $j);
                        $receiptTxnTo->product_receipt_code = $receiptTo->product_receipt_code;
                        $receiptTxnTo->product_code = $stockModel->product_code;
                        $receiptTxnTo->received_quantity = $qty;
                        $receiptTxnTo->requested_quantity = $qty;
                        $receiptTxnTo->dispatched_quantity = $qty;
                        $receiptTxnTo->rejected_quantity = 0;
                        $receiptTxnTo->rate = 0;
                        $receiptTxnTo->amount = 0;
                        $receiptTxnTo->remark = !empty($txnType) ? $txnType : 'INVENTORY RECEIVED';
                        $saveModel[] = $receiptTxnTo;

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
                        $j++;
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

}
