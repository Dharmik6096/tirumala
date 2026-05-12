<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblPlantDispatch;
use app\modules\product\models\TblPlantDispatchHistory;
use app\modules\product\models\TblPlantDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblPlantDispatchTxn;
use app\modules\product\models\TblPlantDispatchTxnHistory;
use app\modules\product\models\TblProduct;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblPlantDispatchController implements the CRUD actions for TblPlantDispatch model.
 */
class TblPlantDispatchController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-unit', 'plant-batch-list', 'check-unique-doc-no'];

    /**
     * Lists all TblPlantDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPlantDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblPlantDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblPlantDispatchSearch();
        $searchModel->plant_dispatch_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblPlantDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblPlantDispatch();
        $txModel = new TblPlantDispatchTxn();
        $model->dispatch_date = date('d-m-Y');
        $batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL') == 1 ? TRUE : FALSE;
        if (Yii::$app->request->post()) {
            $saveModel = [];
            $HisModel = [];
            $model->load(Yii::$app->request->post());
            $model->plant_dispatch_code = Yii::$app->general->getCodeAutoIncrement($model);
            $model->dispatch_date = Yii::$app->formatter->asDate($model->dispatch_date, DATE_FORMAT);
            $model->document_date = Yii::$app->formatter->asDate($model->document_date, DATE_FORMAT);

            $txnData = Yii::$app->request->post()['TblPlantDispatchTxn'];
            if ($batchNoWiseInventory)
                $batchNo = $txnData['sap_batch_no'];
//            $model->sap_batch_no = $batchNo;
            $saveModel[] = $model;
            unset($txnData['product_code']);
            unset($txnData['unit_code']);
            unset($txnData['rate']);
            unset($txnData['qty']);
            unset($txnData['amount']);
            unset($txnData['sap_batch_no']);
            unset($txnData['lr_no']);
            unset($txnData['product_mrp']);
            unset($txnData['distributor_landing_rate']);
            unset($txnData['sachiv_price']);
            unset($txnData['member_price']);
            $i = 1;
            foreach ($txnData as $key => $product) {
                $txModel = new TblPlantDispatchTxn();
                $txModel->product_code = $product['product_code'];
                $txModel->qty = $product['qty'];
                $txModel->grn_missing_qty = $txModel->qty;
                $txModel->rate = $product['rate'];
                $txModel->amount = $product['amount'];
                $txModel->unit_code = $product['unit_code'];
                if ($batchNoWiseInventory)
                    $txModel->sap_batch_no = $product['sap_batch_no'];
                $txModel->lr_no = $product['lr_no'];
                $txModel->product_mrp = isset($product['product_mrp']) ? $product['product_mrp'] : null;
                $txModel->distributor_landing_rate = isset($product['distributor_landing_rate']) ? $product['distributor_landing_rate'] : null;
                $txModel->sachiv_price = isset($product['sachiv_price']) ? $product['sachiv_price'] : null;
                $txModel->member_price = isset($product['member_price']) ? $product['member_price'] : null;
                $txModel->plant_dispatch_txn_code = Yii::$app->general->getCodeAutoIncrement($txModel, $i);
                $txModel->plant_dispatch_code = $model->plant_dispatch_code;
                $txModel->union_code = $model->union_code;
                $saveModel[] = $txModel;
                $i++;
            }
            if ($model->validate()) {
                $transaction = $this->generalModel->saveTransaction($saveModel, $HisModel, ['Plant Dispatch', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                $msg = '';
                foreach ($model->getErrors() as $errorkey => $value) {
                    $msg .= $value[0] . '<br/>';
                }
                $record = ['status' => 'success', 'msg' => $msg];
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblPlantDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->plant_dispatch_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblPlantDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $saveModel = [];
        $dispatchCode = Yii::$app->request->post('id');

        $this->model = $this->findModel($dispatchCode);
        $saveModel[] = $this->model;

        $dispatchTxns = TblPlantDispatchTxn::findAll(['plant_dispatch_code' => $dispatchCode]);

        foreach ($dispatchTxns as $txn) {
            $txnHistory = new TblPlantDispatchTxnHistory();
            Yii::$app->operation->history($txn, $txnHistory, DELETE);
            $saveModel[] = $txnHistory;
            $saveModel[] = $txn;
        }

        $dispatchHistory = new TblPlantDispatchHistory();
        Yii::$app->operation->history($this->model, $dispatchHistory, DELETE);
        $saveModel[] = $dispatchHistory;

        $result = $this->generalModel->deleteTransaction($saveModel);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode($result);
    }

    /**
     * Finds the TblPlantDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblPlantDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPlantDispatch::findOne($id)) !== null) {
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

    public function actionPlantBatchList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $product = new TblPlantDispatchTxn();
                $data = $product->getPlantBatchList($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionCheckUniqueDocNo() {
        $docNo = Yii::$app->request->post('docNo');
        $Model = new TblPlantDispatch();
        $data = $Model->find()->where(['document_no' => $docNo])->one();
        if (empty($data)) {
            return Json::encode(['status' => 'success']);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    /* public function actionCheckUniqueSapNo() {
      $sapNo = Yii::$app->request->post('sapNo');
      $Model = new TblPlantDispatchTxn();
      $data = $Model->find()->where(['sap_batch_no' => $sapNo])->one();
      if (empty($data)) {
      return Json::encode(['status' => 'success']);
      } else {
      return Json::encode(['status' => 'error']);
      }
      } */
}
