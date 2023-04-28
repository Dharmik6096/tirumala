<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductStockAdjustment;
use app\modules\product\models\TblProductStockAdjustmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblProductStockAdjustmentTransaction;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockSearch;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\Model;
use app\modules\product\models\TblProductStockAdjustmentTransactionSearch;

/**
 * TblProductStockAdjustmentController implements the CRUD actions for TblProductStockAdjustment model.
 */
class TblProductStockAdjustmentController extends \app\controllers\ChildController
{

    /**
     * Lists all TblProductStockAdjustment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblProductStockAdjustmentSearch();
        $adjustment_type = 'Good Issue';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $adjustment_type);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'adjustment_type' => $adjustment_type,
        ]);
    }
    
    public function actionReceiptIndex()
    {
        $searchModel = new TblProductStockAdjustmentSearch();
        $adjustment_type = 'Good Receipt';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $adjustment_type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'adjustment_type' => $adjustment_type,
        ]);
    }

    /**
     * Displays a single TblProductStockAdjustment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new TblProductStockAdjustmentTransactionSearch();
        $searchModel->product_stock_adjustment_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        echo "<pre>";
//        print_r($dataProvider);
//        die;
        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductStockAdjustment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */    
    public function actionCreate()
    {
        $this->model = new TblProductStockAdjustment();
        $txModel = new TblProductStockAdjustmentTransaction();

        $this->viewFile = 'create';
        $message = 'Good Issue';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $transaction_type = 'Good Receipt';
            $saveModel = [];
            $this->model->load(Yii::$app->request->post());
            if($this->model->transaction_date == ''){
                $this->model->transaction_date = Yii::$app->formatter->asDate(date('Y-m-d'), DATE_FORMAT);
            } else {
                $this->model->transaction_date = Yii::$app->formatter->asDate($this->model->transaction_date, DATE_FORMAT);
            }
            $txnData = Yii::$app->request->post()['TblProductStockAdjustmentTransaction'];
            $saveModel[] = $this->model;
            unset($txnData['product_code']);
            unset($txnData['sap_batch_no']);
            unset($txnData['stock']);
            unset($txnData['qty']);
            unset($txnData['unit']);
            unset($txnData['reason']);
            unset($txnData['remarks']);
            if($this->model->adjustment_type == 'Good Issue'){
                unset($txnData['reason']);
                $transaction_type = 'Good Issue';
            }
            unset($txnData['remarks']);
            $i = 1;
            foreach ($txnData as $key => $product) {
                $txModel = new TblProductStockAdjustmentTransaction();
                $txModel->product_code = $product['product_code'];
                $txModel->qty = $product['qty'];
                $txModel->stock = $product['stock'];
                $txModel->unit = $product['unit'];
                $txModel->sap_batch_no = $product['sap_batch_no'];
                $txModel->transaction_date = $this->model->transaction_date;
                $txModel->adjustment_type = $this->model->adjustment_type;
                $txModel->reason = '';
                if($transaction_type == 'Good Issue'){
                    $txModel->reason = $product['reason'];
                }
                $txModel->remarks = $product['remarks'];
                $txModel->union_code = $this->model->union_code;
                $saveModel[] = $txModel;
                
                $stockModel = new TblProductStock();
                $stockModel->setCodes($this->model->type, $this->model->code);
                $stockModel->product_code = $txModel->product_code;
                $stockModel->union_code = $txModel->union_code;
                $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
                $existStock = '';
                if($batchNoWiseInventory == 1){
                   $stockModel->sap_batch_no = $product['sap_batch_no'];
                   $existStock = $stockModel->getExistStock($this->model->type, $product['sap_batch_no']);
                   if(empty($existStock)){
                        $record = ['status' => 'success', 'msg' => 'Please enter valid sap batch number'];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return Json::encode($record);
                    }
                } else {
                    $existStock = $stockModel->getExistStock($this->model->type, '');
                }
                $stock = $existStock->stock;
                if($this->model->adjustment_type != 'Good Issue'){
                    $existStock->stock = $stock + $product['qty'];
                } else {
                    $existStock->stock = $stock - $product['qty'];
                }
                
                $stockModel = $existStock;
                $saveModel[] = $stockModel;
                
                $stockTxnModel = new TblProductStockTransaction();
                $stockTxnModel->attributes = $stockModel->attributes;
                unset($stockTxnModel->created_at);
                unset($stockTxnModel->created_by);
                $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode($i);
                $stockTxnModel->old_value = $stock;
                $stockTxnModel->new_value = $product['qty'];
                if($this->model->adjustment_type != 'Good Issue'){
                    $stockTxnModel->final_value = $stock + $product['qty'];
                } else {
                    $stockTxnModel->final_value = $stock - $product['qty'];
                }
                $stockTxnModel->transaction_type = $transaction_type;
                $stockTxnModel->transaction_date = date('Y-m-d');
                $saveModel[] = $stockTxnModel;
                $i++;
            }
            if ($this->model->validate()) {
                $auto_key_config['TblProductStockAdjustmentTransaction'][] = ['self_key' => 'product_stock_adjustment_code', 'parent_key' => 'product_stock_adjustment_code', 'parent_index' => 0];
                $auto_key_config['TblProductStockTransaction'][] = ['self_key' => 'reference_code', 'parent_key' => 'product_stock_adjustment_transaction_code', 'parent_index' => 1];
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, [$transaction_type, 'create'], $auto_key_config);
                if ($transaction == 'customRedirect') {
                    if($transaction_type == 'Good Issue') {
                        return $this->redirect(['index']);
                    } else {
                        return $this->redirect(['receipt-index']);
                    }
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                $msg = '';
                foreach ($this->model->getErrors() as $errorkey => $value) {
                    $msg .= $value[0] . '<br/>';
                }
                $record = ['status' => 'success', 'msg' => $msg];
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'txModel' => $txModel,
        ]);
    }
    
    public function actionReceiptCreate()
    {
        $this->model = new TblProductStockAdjustment();
        $txModel = new TblProductStockAdjustmentTransaction();
        $this->viewFile = 'create';
        $message = 'Inventory Transfer';
        $type = 'create';
        return $this->render('receipt_create', [
                    'model' => $this->model,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblProductStockAdjustment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->product_stock_adjustment_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblProductStockAdjustment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblProductStockAdjustment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblProductStockAdjustment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblProductStockAdjustment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        $batch_no = Yii::$app->request->post('batch_no');

        $stockModel = new TblProductStock();
        $stockModel->setCodes($from_type, $from_code);
        $stockModel->product_code = $product;
        $stockModel->union_code = $union_code;

        $existtoStock = $stockModel->getExistStock($from_type, $batch_no);
        if (!empty($existtoStock->stock)) {
            return Json::encode(['status' => 'success', 'stock' => $existtoStock->stock]);
        } else {
            return Json::encode(['status' => 'success', 'stock' => 0]);
        }
    }
}
