<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblInventoryTransfer;
use app\modules\product\models\TblInventoryTransferTxn;
use app\modules\product\models\TblInventoryTransferTxnHistory;
use app\modules\product\models\TblInventoryTransferHistory;
use app\modules\product\models\TblInventoryTransferSearch;
use app\modules\product\models\TblInventoryTransferTxnSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\product\models\TblProductStockTransactionHistory;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductReceipt;
use app\modules\product\models\TblProductReceiptTransaction;

/**
 * TblInventoryTransferController implements the CRUD actions for TblInventoryTransfer model.
 */
class TblInventoryTransferController extends \app\controllers\ChildController {

    public $freeAccessActions = ['list-grid', 'get-unit', 'get-available-stock'];

    /**
     * Lists all TblInventoryTransfer models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblInventoryTransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblInventoryTransfer model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblInventoryTransferTxnSearch();
        $searchModel->inventory_transfer_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblInventoryTransfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblInventoryTransfer();
        $searchModel = new TblInventoryTransferTxnSearch();
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $txModel = new TblInventoryTransferTxn();

        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Inventory Transfer';
        $type = 'create';

        if (Yii::$app->request->post()) {
            $masterData = Yii::$app->request->post()['TblInventoryTransfer'];
            $txnData = Yii::$app->request->post()['TblInventoryTransferTxn'];
            $this->model->setAttributes($masterData);
            if (empty(Yii::$app->request->post()['TblInventoryTransfer']['inventory_transfer_code'])) {
                $this->model->inventory_transfer_code = Yii::$app->general->getPrimaryCode($this->model, 1);
                $this->model->inventory_transfer_date = !empty($this->model->inventory_transfer_date) ? date('Y-m-d', strtotime($this->model->inventory_transfer_date)) : '';
                $modelSave[] = $this->model;
            }
            $txModel->setAttributes($txnData);
            $txModel->inventory_transfer_code = $this->model->inventory_transfer_code;
            $txModel->union_code = $this->model->union_code;
            $txModel->inventory_transfer_txn_code = Yii::$app->general->getTransactionCode($txModel, $txModel->inventory_transfer_code);

            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $modelSave[] = $txModel;
                //set from stock
                $fstockModel = new TblProductStock();
                $fstockModel->setCodes($this->model->from_type, $this->model->from_code);
                $fstockModel->product_code = $txModel->product_code;
                $fstockModel->union_code = $txModel->union_code;
                $existfromStock = $fstockModel->getExistStock($this->model->from_type);

                $f_stock = 0;
                $qty = $txModel->qty;

                if (!empty($existfromStock)) {
                    $historyModel = new TblProductStockHistory();
                    Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
                    $modelSave[] = $historyModel;
                    $f_stock = $existfromStock->stock;
                    $existfromStock->stock = $f_stock - $qty;
                    $fstockModel = $existfromStock;
                } else {
                    $fstockModel->product_stock_code = $fstockModel->getCode();
                    $fstockModel->stock = $f_stock - $qty;
                    $fstockModel->x_col1 = Yii::$app->general->getUuid();
                }
                $modelSave[] = $fstockModel;

                $i = 1;
                $fstockTxnModel = new TblProductStockTransaction();
                $fstockTxnModel->attributes = $fstockModel->attributes;
                unset($fstockTxnModel->created_at);
                unset($fstockTxnModel->created_by);
                $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($i);
                $fstockTxnModel->old_value = $f_stock;
                $fstockTxnModel->new_value = $qty;
                $fstockTxnModel->final_value = $fstockModel->stock;
                $fstockTxnModel->transaction_type = 'INVENTORY TRANSFER';
                $fstockTxnModel->transaction_date = date('Y-m-d');
                $fstockTxnModel->reference_code = $txModel->inventory_transfer_txn_code;
                $modelSave[] = $fstockTxnModel;

                $receipt = new TblProductReceipt();
                $receipt->product_receipt_code = Yii::$app->general->getPrimaryCode($receipt);
                $receipt->grn_no = '1234';
                $receipt->grn_date = date('Y-m-d');
                $receipt->vendor_type = $this->model->from_type;
                $receipt->vendor_code = $this->model->from_code;
                $receipt->union_code = $fstockModel->union_code;
                $receipt->plant_code = $fstockModel->plant_code;
                $receipt->mcc_plant_code = $fstockModel->mcc_plant_code;
                $receipt->bmc_code = $fstockModel->bmc_code;
                $receipt->dcs_code = $fstockModel->dcs_code;
                $modelSave[] = $receipt;
                $receiptTxn = new TblProductReceiptTransaction();
                $receiptTxn->product_receipt_transaction_code = Yii::$app->general->getTransactionCode($receiptTxn, $receipt->product_receipt_code);
                $receiptTxn->product_receipt_code = $receipt->product_receipt_code;
                $receiptTxn->product_code = $fstockModel->product_code;
                $receiptTxn->received_quantity = '-' . $qty;
                $receiptTxn->requested_quantity = $receiptTxn->received_quantity;
                $receiptTxn->dispatched_quantity = $receiptTxn->received_quantity;
                $receiptTxn->rejected_quantity = 0;
                $receiptTxn->rate = 0;
                $receiptTxn->amount = 0;
                $receiptTxn->remark = 'INVENTORY TRANSFER';
                $modelSave[] = $receiptTxn;

                $i++;

                //set to stock
                $stockModel = new TblProductStock();
                $stockModel->setCodes($this->model->to_type, $this->model->to_code);

                $stockModel->product_code = $txModel->product_code;
                $stockModel->union_code = $txModel->union_code;
                $existtoStock = $stockModel->getExistStock($this->model->to_type);

                $t_stock = 0;
                $valid_avl_stock = isset(Yii::$app->session->get('unionConfig')[$this->model->union_code]['validate_available_stock']) ? Yii::$app->session->get('unionConfig')[$this->model->union_code]['validate_available_stock'] : 0;
                if ($valid_avl_stock == 1 && !empty($existtoStock) && $existtoStock->stock > 0) {
                    $err['qty'] = Yii::t('app/validation', 'Stock Is Already Availble of Product ' . Yii::$app->general->getforeignkey($txModel->productCode, 'product_name'));
                    return Json::encode($err);
                } else if (!empty($existtoStock)) {
                    $historyModel = new TblProductStockHistory();
                    Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
                    $modelSave[] = $historyModel;
                    $t_stock = $existtoStock->stock;
                    $existtoStock->stock = $t_stock + $qty;
                    $stockModel = $existtoStock;
                } else {
                    $stockModel->product_stock_code = $stockModel->getCode($i);
                    $stockModel->stock = $t_stock + $qty;
                    $stockModel->x_col1 = Yii::$app->general->getUuid();
                }
                $modelSave[] = $stockModel;

                $stockTxnModel = new TblProductStockTransaction();
                $stockTxnModel->attributes = $stockModel->attributes;
                unset($stockTxnModel->created_at);
                unset($stockTxnModel->created_by);
                $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($i);
                $stockTxnModel->old_value = $t_stock;
                $stockTxnModel->new_value = $qty;
                $stockTxnModel->final_value = $stockModel->stock;
                $stockTxnModel->transaction_type = 'INVENTORY RECEIVED';
                $stockTxnModel->transaction_date = date('Y-m-d');
                $stockTxnModel->reference_code = $txModel->inventory_transfer_txn_code;
                $modelSave[] = $stockTxnModel;

                $receiptTo = new TblProductReceipt();
                $receiptTo->product_receipt_code = Yii::$app->general->getPrimaryCode($receiptTo, $i);
                $receiptTo->grn_no = '1234';
                $receiptTo->grn_date = date('Y-m-d');
                $receiptTo->vendor_type = $this->model->to_type;
                $receiptTo->vendor_code = $this->model->to_code;
                $receiptTo->union_code = $stockModel->union_code;
                $receiptTo->plant_code = $stockModel->plant_code;
                $receiptTo->mcc_plant_code = $stockModel->mcc_plant_code;
                $receiptTo->bmc_code = $stockModel->bmc_code;
                $receiptTo->dcs_code = $stockModel->dcs_code;
                $modelSave[] = $receiptTo;
                $receiptTxnTo = new TblProductReceiptTransaction();
                $receiptTxnTo->product_receipt_transaction_code = Yii::$app->general->getTransactionCode($receiptTo, $receiptTxnTo->product_receipt_code, $i);
                $receiptTxnTo->product_receipt_code = $receiptTo->product_receipt_code;
                $receiptTxnTo->product_code = $stockModel->product_code;
                $receiptTxnTo->received_quantity = $stockModel->stock;
                $receiptTxnTo->requested_quantity = $receiptTxnTo->received_quantity;
                $receiptTxnTo->dispatched_quantity = $receiptTxnTo->received_quantity;
                $receiptTxnTo->rejected_quantity = $receiptTxnTo->received_quantity;
                $receiptTxnTo->rate = 0;
                $receiptTxnTo->amount = 0;
                $receiptTxnTo->remark = 'INVENTORY RECEIVED';
                $modelSave[] = $receiptTxnTo;

                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->inventory_transfer_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg, 'pk_code' => $this->model->inventory_transfer_code];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                foreach ($txModel->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }

                return Json::encode($err);
//                return Json::encode(ActiveForm::validate($this->model, $txModel));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblInventoryTransfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->inventory_transfer_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblInventoryTransfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $this->model = $this->findModel($id);
        $saveModel = [];
        $deleteModel = [];
        if (!empty($this->model)) {
            $historyModel = new TblInventoryTransferHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $deleteModel[] = $this->model;
            $saveModel[] = $historyModel;
            $txn = new TblInventoryTransferTxn();
            $txModel = $txn->getTransaction($id);

            foreach ($txModel as $key => $value) {
                $ref_code = $value->inventory_transfer_txn_code;
                $txnHistory = new TblInventoryTransferTxnHistory();
                Yii::$app->operation->history($value, $txnHistory, DELETE);
                $deleteModel[] = $txModel[$key];
                $saveModel[] = $txnHistory;

                $productTxn = new TblProductStockTransaction();
                $productTxnmodel = $productTxn->getProductTransactionCode($ref_code);
                foreach ($productTxnmodel as $key => $value) {
                    $productTxnHistory = new TblProductStockTransactionHistory();
                    Yii::$app->operation->history($value, $productTxnHistory, DELETE);
                    $deleteModel[] = $productTxnmodel[$key];
                    $saveModel[] = $productTxnHistory;
                }
            }

            $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Inventory Transfer', 'delete']);

            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblInventoryTransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblInventoryTransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblInventoryTransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblInventoryTransferTxnSearch();
        $searchModel->inventory_transfer_code = Yii::$app->request->get()['inventory_transfer_code'];
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionGetUnit() {
        $product = Yii::$app->request->post('product');
        $productModel = new TblProduct();
        $data = $productModel->find()->where(['product_code' => $product])->one();
        if (!empty($data->unit_code)) {
            return Json::encode(['status' => 'success', 'unit' => $data->unit_code]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionGetAvailableStock() {
        $product = Yii::$app->request->post('product');
        $from_type = Yii::$app->request->post('from_type');
        $from_code = Yii::$app->request->post('from_code');
        $union_code = Yii::$app->request->post('union_code');

        $stockModel = new TblProductStock();
        $stockModel->setCodes($from_type, $from_code);
        $stockModel->product_code = $product;
        $stockModel->union_code = $union_code;

        $existtoStock = $stockModel->getExistStock($from_type);
        if (!empty($existtoStock->stock)) {
            return Json::encode(['status' => 'success', 'stock' => $existtoStock->stock]);
        } else {
            return Json::encode(['status' => 'success', 'stock' => 0]);
        }
    }

}
