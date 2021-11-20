<?php

namespace app\modules\product\controllers;

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

/**
 * TblGrnController implements the CRUD actions for TblGrn model.
 */
class TblGrnController extends \app\controllers\ChildController {

    public $freeAccessActions = ['list-grid', 'get-unit'];

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
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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
            if (empty(Yii::$app->request->post()['TblGrn']['grn_code'])) {
                $this->model->grn_code = Yii::$app->general->getPrimaryCode($this->model, 1);
                $this->model->grn_date = !empty($this->model->grn_date) ? date('Y-m-d', strtotime($this->model->grn_date)) : '';
                $this->model->invoice_date = !empty($this->model->invoice_date) ? date('Y-m-d', strtotime($this->model->invoice_date)) : '';
                $modelSave[] = $this->model;
            }
            $txModel->setAttributes($txnData);
            $txModel->grn_code = $this->model->grn_code;
            $txModel->union_code = $this->model->union_code;
            $txModel->grn_txn_code = Yii::$app->general->getTransactionCode($txModel, $txModel->grn_code);


            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $modelSave[] = $txModel;

                $stockModel = new TblProductStock();
                $stockModel->attributes = $this->model->attributes;
                $stockModel->attributes = $txModel->attributes;
                $existStock = $stockModel->getExistStock('MCC');
                $stock = 0;
                $rejectedQty = !empty($txModel->rejected_qty) ? $txModel->rejected_qty : 0;
                $qty = $txModel->received_qty - $rejectedQty;
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
                $stockTxnModel->product_stock_transaction_code = $stockTxnModel->getCode();
                $stockTxnModel->old_value = $stock;
                $stockTxnModel->new_value = $qty;
                $stockTxnModel->final_value = $stockModel->stock;
                $stockTxnModel->transaction_type = 'GRN';
                $stockTxnModel->transaction_date = date('Y-m-d');
                $stockTxnModel->reference_code = $txModel->grn_txn_code;
                $modelSave[] = $stockTxnModel;

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

}
