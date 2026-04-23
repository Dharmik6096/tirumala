<?php

namespace app\modules\product\controllers;

use app\modules\product\models\TblGrnTxnHistory;
use Yii;
use app\modules\product\models\TblGrn;
use app\modules\product\models\TblGrnSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblGrnTxn;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblGrnTxnSearch;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblPlantDispatchSearch;
use app\modules\product\models\TblPlantDispatch;
use app\modules\product\models\TblPlantDispatchHistory;
use app\modules\product\models\TblPlantDispatchTxn;
use app\modules\product\models\TblPlantDispatchTxnHistory;
use app\modules\product\models\TblGrnInstallment;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\product\models\TblGrnInstallmentSearch;
use app\modules\product\models\TblGrnInstallmentHistory;
use app\modules\product\models\TblGrnHistory;

/**
 * TblGrnController implements the CRUD actions for TblGrn model.
 */
class TblGrnController extends \app\controllers\ChildController {

    public $freeAccessActions = ['list-grid', 'get-unit', 'list-grid-other', 'set-ref-no-data'];

    /**
     * Lists all TblGrn models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblGrnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblGrn model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblGrnTxnSearch();
        $searchModel->grn_code = $id;
        $dataProvider = $searchModel->createsearch(Yii::$app->request->queryParams);
        $grnInstallmentSearchModel = new TblGrnInstallmentSearch();
        $grnInstallmentSearchModel->grn_code = $id;
        $grnInstallmentdataProvider = $grnInstallmentSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'grnInstallmentSearchModel' => $grnInstallmentSearchModel,
                    'grnInstallmentdataProvider' => $grnInstallmentdataProvider,
        ]);
    }

    /**
     * Creates a new TblGrn model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblGrn();
        $this->model->grn_no = rand(1000, 9999);
        $searchModel = new TblGrnTxnSearch();
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $txModel = new TblGrnTxn();
//        $txModel->scenario = 'create';

        $this->viewFile = 'create';
//        $this->model->scenario = 'create';
        $modelSave = [];
        $message = 'GRN';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $masterData = Yii::$app->request->post()['TblGrn'];
            $txnData = Yii::$app->request->post()['TblGrnTxn'];
            $this->model->setAttributes($masterData);
            $grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult($this->model->union_code, 'grn_without_stock_entry');
            $this->model->is_stock_posted = ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') ? 1 : 0;
            if (empty(Yii::$app->request->post()['TblGrn']['grn_code'])) {
                $this->model->grn_code = Yii::$app->general->getPrimaryCode($this->model, 1);
                $this->model->grn_date = !empty($this->model->grn_date) ? date('Y-m-d', strtotime($this->model->grn_date)) : '';
                $this->model->invoice_date = !empty($this->model->invoice_date) ? date('Y-m-d', strtotime($this->model->invoice_date)) : '';
                $this->model->no_of_installment = $this->model->payment_mode == 1 ? $this->model->no_of_installment : 0;
                if ($this->model->payment_mode == 1 && !empty($this->model->deduction_start_date)) {
                    $dedStartDate = date('Y-m-d', strtotime($this->model->deduction_start_date));
                    $this->model->deduction_start_date = $dedStartDate;
                    $this->model->amount = $txnData['gross_amount'];
                }
                $modelSave[] = $this->model;
            } else {
                $this->model = TblGrn::find()->where(['grn_code' => $this->model->grn_code])->one();
                $grnHistoryModel = new TblGrnHistory();
                Yii::$app->operation->history($this->model, $grnHistoryModel, UPDATE);
                $modelSave[] = $grnHistoryModel;
                $this->model->amount = $txnData['gross_amount'] + $this->model->amount;
                $modelSave[] = $this->model;
            }

            $txModel->setAttributes($txnData);
            $txModel->grn_code = $this->model->grn_code;
            $txModel->union_code = $this->model->union_code;
            $txModel->grn_txn_code = Yii::$app->general->getTransactionCode($txModel, $txModel->grn_code);

            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $modelSave[] = $txModel;
                $installmentModel = new TblGrnInstallment();
                $existGrnInstallment = $installmentModel->find()->where(['grn_code' => $this->model->grn_code])->all();

                if (!empty($existGrnInstallment)) {
                    foreach ($existGrnInstallment as $txn) {
                        $historyTxnModel = new TblGrnInstallmentHistory();
                        Yii::$app->operation->history($txn, $historyTxnModel, 'UPDATE');
                        $modelSave[] = $historyTxnModel;
                        $txn->main_amount = $this->model->amount;
                        $no = !empty($this->model->no_of_installment) ? ($this->model->no_of_installment) : 1;
                        $instAmount = floatval($this->model->amount / $no);
                        $txn->installment_amount = $instAmount;
                        $modelSave[] = $txn;
                    }
                }
                if (empty(Yii::$app->request->post()['TblGrn']['grn_code'])) {
                    if (!empty($this->model->payment_mode)) {
                        $this->model->installment($modelSave);
                    }
                }
                if ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') {
                    $stockModel = new TblProductStock();
                    $stockModel->attributes = $this->model->attributes;
                    $stockModel->attributes = $txModel->attributes;
                    unset($stockModel->created_at);
                    unset($stockModel->created_by);
                    $existStock = $stockModel->getExistStock('BMC', $txModel->sap_batch_no);
                    $stock = 0;
                    $rejectedQty = !empty($txModel->rejected_qty) ? $txModel->rejected_qty : 0;
                    //                $qty = $txModel->received_qty - $rejectedQty;
                    $qty = $txModel->received_qty;
                    if (!empty($existStock)) {
                        $historyModel = new TblProductStockHistory();
                        Yii::$app->operation->history($existStock, $historyModel, UPDATE);
                        $modelSave[] = $historyModel;
                        $stock = $existStock->stock;
                        $existStock->stock = $stock + $qty;
                        $stockModel = $existStock;
                    } else {
                        $stockModel->product_stock_code = $stockModel->getCode();
                        $stockModel->stock = $stock + $qty;
                        $stockModel->x_col1 = Yii::$app->general->getUuid();
                        $stockModel->plant_code = Yii::$app->general->getforeignkey($this->model->mccPlantCode, 'plant_code');
                    }
                    $modelSave[] = $stockModel;
                    $stockTxnModel = new TblProductStockTransaction();
                    $stockTxnModel->attributes = $stockModel->attributes;
                    unset($stockTxnModel->created_at);
                    unset($stockTxnModel->created_by);
                    $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode();
                    $stockTxnModel->old_value = $stock;
                    $stockTxnModel->new_value = $qty;
                    $stockTxnModel->final_value = $stockModel->stock;
                    $stockTxnModel->transaction_type = 'GRN';
                    $stockTxnModel->transaction_date = date('Y-m-d');
                    $stockTxnModel->reference_code = $txModel->grn_txn_code;
                    $modelSave[] = $stockTxnModel;
                }
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->grn_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg, 'pk_code' => $this->model->grn_code];
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
     * Deletes an existing TblGrn model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblGrn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblGrn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblGrn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblGrnTxnSearch();
        $searchModel->grn_code = Yii::$app->request->get()['grn_code'];
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

    public function actionCreateOther() {
        $this->model = new TblGrn();
        $this->model->grn_no = rand(1000, 9999);
        $searchModel = new TblGrnTxnSearch();
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $txModel = new TblGrnTxn();
        $this->viewFile = 'create';
        $modelSave = [];
        $errors = [];
        $message = 'GRN';
        $type = 'create';
        $updateDispatch = TRUE;
        $this->model->grn_date = date('d-m-Y');
        $this->model->scenario = 'batchcreate';

        if (Yii::$app->request->post()) {
            $grnData = Yii::$app->request->post()['TblGrn'];
            $txnData = Yii::$app->request->post()['TblPlantDispatchTxn'];
            $this->model->setAttributes($grnData);
            $this->model->grn_code = Yii::$app->general->getPrimaryCode($this->model, 1);
            $dispModel = new TblPlantDispatch();
            $dispatchData = $dispModel->find()->where(['union_code' => $this->model->union_code, 'bmc_code' => $this->model->bmc_code, 'document_no' => $this->model->ref_no])->one();
            $this->model->grn_date = !empty($this->model->grn_date) ? date('Y-m-d', strtotime($this->model->grn_date)) : date('Y-m-d');
            $this->model->invoice_date = !empty($this->model->invoice_date) ? date('Y-m-d', strtotime($this->model->invoice_date)) : $dispatchData->document_date;
            $this->model->invoice_no = !empty($this->model->invoice_no) ? $this->model->invoice_no : $dispatchData->document_no;
            $this->model->no_of_installment = $this->model->payment_mode == 1 ? $this->model->no_of_installment : 0;
            $grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult($this->model->union_code, 'grn_without_stock_entry');
            $this->model->is_stock_posted = ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '') ? 1 : 0;
            $this->model->vendor_master_code = $dispatchData->vendor_master_code;
            if ($this->model->payment_mode == 1 && !empty($this->model->deduction_start_date)) {
                $dedStartDate = date('Y-m-d', strtotime($this->model->deduction_start_date));
                $this->model->deduction_start_date = $dedStartDate;
            }
            $i = 1;
            $totalGrossAmount = 0;
            $pendingQty = 0;
            $receivedQty = 0;
            foreach ($txnData as $txn) {
                $txModel = new TblGrnTxn();
                $txModel->setAttributes($txn);
                if ($pendingQty == 0) {
                    $pendingQty = empty($txModel->missing_qty) ? 0 : $txModel->missing_qty;
                }
                $txModel->union_code = $this->model->union_code;
                $txModel->grn_code = $this->model->grn_code;
                $txModel->is_stock_posted = $this->model->is_stock_posted;
                $txModel->manuf_date = !empty($txModel->manuf_date) ? date('Y-m-d', strtotime($txModel->manuf_date)) : date('Y-m-d');
                $txModel->gross_amount = $txn['amount'];
                $txModel->basic_amount = $txn['amount'];
                $txModel->grn_txn_code = Yii::$app->general->getTransactionCode($txModel, $txModel->grn_code, $i);
                $txModel->scenario = 'batchcreate';
                if ($txModel->dispatch_qty != $txModel->missing_qty) {
                    $modelSave[] = $txModel;
                    $receivedQty++;
                }
                if (!$txModel->validate()) {
                    $errors[] = $txModel->getErrors();
                }
                if ($txModel->dispatch_qty != $txModel->missing_qty && ($grnWithoutStockEntry == 0 || $grnWithoutStockEntry == '')) {
                    $stockModel = new TblProductStock();
                    $stockModel->attributes = $this->model->attributes;
                    $stockModel->attributes = $txModel->attributes;
                    unset($stockModel->created_at);
                    unset($stockModel->created_by);
                    $existStock = $stockModel->getExistStock('BMC', $txModel->sap_batch_no);
                    $stock = 0;
                    $qty = $txModel->received_qty;
                    if (!empty($existStock)) {
                        $historyModel = new TblProductStockHistory();
                        Yii::$app->operation->history($existStock, $historyModel, UPDATE);
                        $modelSave[] = $historyModel;
                        $stock = $existStock->stock;
                        $existStock->stock = $stock + $qty;
                        $stockModel = $existStock;
                    } else {
                        $stockModel->product_stock_code = $stockModel->getCode($i);
                        $stockModel->stock = $stock + $qty;
                        $stockModel->x_col1 = Yii::$app->general->getUuid();
                        $stockModel->plant_code = Yii::$app->general->getforeignkey($this->model->mccPlantCode, 'plant_code');
                    }
                    $modelSave[] = $stockModel;
                    $stockTxnModel = new TblProductStockTransaction();
                    $stockTxnModel->attributes = $stockModel->attributes;
                    unset($stockTxnModel->created_at);
                    unset($stockTxnModel->created_by);
                    $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($i);
                    $stockTxnModel->old_value = $stock;
                    $stockTxnModel->new_value = $qty;
                    $stockTxnModel->final_value = $stockModel->stock;
                    $stockTxnModel->transaction_type = 'GRN';
                    $stockTxnModel->transaction_date = date('Y-m-d');
                    $stockTxnModel->reference_code = $txModel->grn_txn_code;
                    $modelSave[] = $stockTxnModel;
                }
                $i++;
                if ($txModel->dispatch_qty != $txModel->missing_qty) {
                    $dispatchTxnModel = new TblPlantDispatchTxn();
                    $dispatchTxnData = $dispatchTxnModel->find()->where(['plant_dispatch_code' => $dispatchData->plant_dispatch_code, 'plant_dispatch_txn_code' => $txn['plant_dispatch_txn_code']])->one();
                    if (!empty($dispatchTxnData)) {
                        if ($dispatchTxnData->grn_missing_qty != $txModel->dispatch_qty) {
                            $this->model->addError('ref_no', Yii::t('app', 'Product Qty Mismatch with Plant Dispatch'));
                            break;
                        } else {
                            $historyTxnModel = new TblPlantDispatchTxnHistory();
                            Yii::$app->operation->history($dispatchTxnData, $historyTxnModel, 'UPDATE');
                            $modelSave[] = $historyTxnModel;
                            $dispatchTxnData->grn_missing_qty = !empty($txModel->missing_qty) ? $txModel->missing_qty : 0;
                            $modelSave[] = $dispatchTxnData;
                        }
                    } else {
                        $this->model->addError('ref_no', Yii::t('app', 'Ref No. Mismatch with Plant Dispatch'));
                        break;
                    }
                    $totalGrossAmount += $txModel->gross_amount;
                }
            }
            if ($receivedQty > 0) {
                $this->model->amount = $totalGrossAmount;
                $modelSave[] = $this->model;
            } else {
                $this->model->addError('ref_no', 'No valid transactions found.');
            }

            if (!empty($this->model->payment_mode)) {
                $this->model->installment($modelSave);
            }
            if ($updateDispatch) {
                $dispatchModel = new TblPlantDispatch();
                $dispatchData = $dispatchModel->find()->where(['union_code' => $this->model->union_code, 'plant_code' => $this->model->plant_code, 'mcc_plant_code' => $this->model->mcc_plant_code, 'document_no' => $this->model->ref_no])->one();
                if (!empty($dispatchData)) {
                    if ($dispatchData->status == '0' || $dispatchData->status == '2') {
                        $historyModel = new TblPlantDispatchHistory();
                        Yii::$app->operation->history($dispatchData, $historyModel, 'UPDATE');
                        $modelSave[] = $historyModel;
                        $dispatchData->status = ($pendingQty > 0) ? '2' : '1';
                        $modelSave[] = $dispatchData;
                    } else if ($dispatchData->status == '1') {
                        $this->model->addError('ref_no', 'GRN already completed for selected reference number');
                    }
                }
            }
            if (empty($this->model->getErrors()) && $this->model->validate() && empty($errors)) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }

                foreach ($errors as $array) {
                    foreach ($array as $key => $value) {
                        if (isset($err[$key])) {
                            $err[$key] .= $value[0] . '<br>';
                        } else {
                            $err[$key] = $value[0] . '<br>';
                        }
                    }
                }
                return Json::encode($err);
            }
        } else {
            return $this->render('create_other', [
                        'model' => $this->model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'txModel' => $txModel,
            ]);
        }
        return $this->render('create_other', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    public function actionListGridOther() {
        $postData = Yii::$app->request->get()['TblGrn'];
        $searchModel = new TblPlantDispatchSearch();
        $searchModel->document_no = isset($postData['ref_no']) ? $postData['ref_no'] : NULL;
        $dataProvider = $searchModel->docnosearch([]);
        $model = new TblGrn();
        $model->setAttributes($postData);
        return $this->renderAjax('_list_grid_other', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $model]);
    }

    public function actionSetRefNoData() {
        $ref_no = Yii::$app->request->post('ref_no');
        $dispModel = new TblPlantDispatch();
        $dispatchData = $dispModel->find()->where(['document_no' => $ref_no])->one();
        if (!empty($dispatchData->document_date)) {
            $dispatchData->document_date = date('d-m-Y', strtotime($dispatchData->document_date));
        }
        if (!empty($dispatchData)) {
            return Json::encode(['status' => 'success', 'document_date' => $dispatchData->document_date, 'document_no' => $dispatchData->document_no]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionRfcRePush($id) {
        $saveModel = [];
        $this->model = $this->findModel($id);
        $historyModel = new TblGrnHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $saveModel[] = $historyModel;
        $this->model->data_post_status = 0;
        $saveModel[] = $this->model;
        $transactionModels = TblGrnTxn::find()->where(['grn_code' => $id])->all();
        if ($transactionModels) {
            foreach ($transactionModels as $transactionModel) {
                $historyModel = new TblGrnTxnHistory();
                Yii::$app->operation->history($transactionModel, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
                $transactionModel->data_post_status = 0;
                $saveModel[] = $transactionModel;
            }
        }
        $transaction = $this->generalModel->saveTransaction($saveModel, ['Grn', 'edit']);
        \Yii::$app->session->removeAllFlashes();
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'GRN re-pushed successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Failed to re-push GRN.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
