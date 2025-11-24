<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblMedicineStock;
use app\modules\veterinary\models\TblMedicineStockHistory;
use app\modules\veterinary\models\TblMedicineStockSearch;
use app\modules\veterinary\models\TblMedicineStockTransaction;
use app\modules\veterinary\models\TblMedicineStockTransactionSearch;
use app\modules\veterinary\models\TblMedicineStockTransfer;
use app\modules\veterinary\models\TblMedicineStockTransferTxn;
use app\modules\veterinary\models\TblMedicineStockTransferTxnSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMedicineStockController implements the CRUD actions for TblMedicineStock model.
 */
class TblMedicineStockController extends ChildController {

    public $freeAccessActions = ['list-grid', 'get-available-stock', 'user-list', 'medicine-batch-list'];

    /**
     * Lists all TblMedicineStock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMedicineStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMedicineStock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $searchModel = new TblMedicineStockTransactionSearch();
        $searchModel->attributes = $model->attributes;
        $dataProvider = $searchModel->search([]);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $this->model = new TblMedicineStockTransfer();
        $searchModel = new TblMedicineStockTransferTxnSearch();
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $txModel = new TblMedicineStockTransferTxn();

        $this->viewFile = 'create';
        $modelSave = [];
        $searchModel->transaction_date = date('d-m-Y');

        if ($searchModel->medicine_wise == '0') {
            $bulkSearchModel = new TblMedicineStockSearch();
            $bulkSearchModel->union_code = $searchModel->union_code;
            $bulkSearchModel->module_code = $searchModel->from_user_code;
            $bulkDataProvider = $bulkSearchModel->searchForBulk(Yii::$app->request->queryParams);
            $bulkDataProvider->sort = false;
        }

        if (Yii::$app->request->post()) {
            $masterData = Yii::$app->request->post()['TblMedicineStockTransferTxnSearch'];
            $this->model->setAttributes($masterData);
            $this->model->from_type = $this->model->to_type = 'USER';
            $this->model->from_code = $masterData['from_user_code'];
            $this->model->to_code = $masterData['to_user_code'];
            $this->model->transaction_date = !empty($this->model->transaction_date) ? date('Y-m-d', strtotime($this->model->transaction_date)) : NULL;
            if (isset(Yii::$app->request->post()['bulk_ids']) && $masterData['medicine_wise'] == '0') {
                $modelSave[] = $this->model;
                $bulk_ids = explode(',', Yii::$app->request->post()['bulk_ids']);
                $this->handleBulkTransfer($bulk_ids, $modelSave, $auto_key_config);
                $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($modelSave, ['Medicine Stock Transfer', 'create'], $auto_key_config);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
            $txnData = Yii::$app->request->post()['TblMedicineStockTransferTxn'];

            if (empty(Yii::$app->request->post()['TblMedicineStockTransfer']['medicine_stock_transfer_code'])) {
                $modelSave[] = $this->model;
            } else {
                $this->model->medicine_stock_transfer_code = Yii::$app->request->post()['TblMedicineStockTransfer']['medicine_stock_transfer_code'];
            }

            $txModel->setAttributes($txnData);
            $txModel->union_code = $this->model->union_code;
            $txModel->medicine_stock_transfer_code = $this->model->medicine_stock_transfer_code;
            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $modelSave[] = $txModel;
                $this->handleSingleTransfer($modelSave, $txModel);
                $auto_key_config[1] = ['self_key' => 'medicine_stock_transfer_code', 'parent_key' => 'medicine_stock_transfer_code', 'parent_index' => 0];
                $auto_key_config[4] = ['self_key' => 'transfer_ref_code', 'parent_key' => 'medicine_stock_transfer_txn_code', 'parent_index' => 1];
                $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($modelSave, ['Medicine Stock Transfer', 'create'], $auto_key_config);

                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->medicine_stock_transfer_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg, 'pk_code' => $this->model->medicine_stock_transfer_code];
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
            }
        }
        $viewParams = [
            'model' => $this->model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'txModel' => $txModel,
        ];

        if ($searchModel->medicine_wise == '0') {
            $viewParams['bulkSearchModel'] = $bulkSearchModel;
            $viewParams['bulkDataProvider'] = $bulkDataProvider;
        }

        return $this->render('create', $viewParams);
    }

    private function handleSingleTransfer(&$modelSave, $txModel) {
        //set from stock
        $fstockModel = new TblMedicineStock();
        $fstockModel->union_code = $txModel->union_code;
        $fstockModel->medicine_id = $txModel->medicine_id;
        $fstockModel->module_name = 'USER';
        $fstockModel->module_code = $this->model->from_code;
        $fstockModel->batch_no = $txModel->batch_no;
        $existfromStock = $fstockModel->getExistStock();

        $rate = 0;
        $f_stock = 0;
        $qty = $txModel->qty;
        $expireDate = date('Y-m-d');
        if (!empty($existfromStock)) {
            $historyModel = new TblMedicineStockHistory();
            Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
            $modelSave[] = $historyModel;
            $f_stock = $existfromStock->stock;
            $existfromStock->stock = $f_stock - $qty;
            $fstockModel = $existfromStock;
            $rate = $existfromStock->rate;
            $expireDate = $existfromStock->expire_date;
        }

        $modelSave[] = $fstockModel;

        $fstockTxnModel = new TblMedicineStockTransaction();
        $fstockTxnModel->attributes = $fstockModel->attributes;
        unset($fstockTxnModel->created_at);
        unset($fstockTxnModel->created_by);
        unset($fstockTxnModel->updated_at);
        unset($fstockTxnModel->updated_by);
        unset($fstockTxnModel->originating_org_code);
        unset($fstockTxnModel->originating_org_type);
        unset($fstockTxnModel->originating_type);
        $fstockTxnModel->old_value = $f_stock;
        $fstockTxnModel->new_value = $qty;
        $fstockTxnModel->final_value = $fstockModel->stock;
        $fstockTxnModel->entry_type = 'TRANSFER';
        $fstockTxnModel->tran_datetime = date('Y-m-d H:i:s');
        $fstockTxnModel->transfer_ref_name = 'tbl_medicine_stock_transfer_txn';
        $modelSave[] = $fstockTxnModel;

        //set to stock
        $stockModel = new TblMedicineStock();
        $stockModel->union_code = $txModel->union_code;
        $stockModel->medicine_id = $txModel->medicine_id;
        $stockModel->module_name = 'USER';
        $stockModel->module_code = $this->model->to_code;
        $stockModel->batch_no = $txModel->batch_no;
        $existtoStock = $stockModel->getExistStock();

        $t_stock = 0;
        if (!empty($existtoStock)) {
            $historyModel = new TblMedicineStockHistory();
            Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
            $modelSave[] = $historyModel;
            $t_stock = $existtoStock->stock;
            $existtoStock->stock = $t_stock + $qty;
            $stockModel = $existtoStock;
        } else {
            $stockModel->stock = $t_stock + $qty;
            $stockModel->rate = $rate;
            $stockModel->expire_date = $expireDate;
        }
        $modelSave[] = $stockModel;

        $stockTxnModel = new TblMedicineStockTransaction();
        $stockTxnModel->attributes = $stockModel->attributes;
        unset($stockTxnModel->created_at);
        unset($stockTxnModel->created_by);
        unset($stockTxnModel->updated_at);
        unset($stockTxnModel->updated_by);
        unset($stockTxnModel->originating_org_code);
        unset($stockTxnModel->originating_org_type);
        unset($stockTxnModel->originating_type);
        unset($stockTxnModel->transfer_ref_code);
        unset($stockTxnModel->transfer_ref_name);
        $stockTxnModel->old_value = $t_stock;
        $stockTxnModel->new_value = $qty;
        $stockTxnModel->final_value = $stockModel->stock;
        $stockTxnModel->entry_type = 'RECEIVED';
        $stockTxnModel->tran_datetime = date('Y-m-d H:i:s');
        $modelSave[] = $stockTxnModel;
    }

    private function handleBulkTransfer($bulk_ids, &$modelSave, &$auto_key_config) {
        $index = 1;
        $i = 4;
        foreach ($bulk_ids as $id) {
            $auto_key_config[$index] = ['self_key' => 'medicine_stock_transfer_code', 'parent_key' => 'medicine_stock_transfer_code', 'parent_index' => 0];
            $stockModel = TblMedicineStock::findOne($id);
            if (!empty($stockModel) && $stockModel->stock > 0) {
                $txModel = new TblMedicineStockTransferTxn();
                $txModel->union_code = $this->model->union_code;
                $txModel->medicine_stock_transfer_code = $this->model->medicine_stock_transfer_code;
                $txModel->medicine_id = $stockModel->medicine_id;
                $txModel->batch_no = $stockModel->batch_no;
                $txModel->qty = $stockModel->stock;
                $txModel->stock = $stockModel->stock;
                $txModel->available_stock = $stockModel->stock;
                $txModel->rate = $stockModel->rate;
                $txModel->expire_date = $stockModel->expire_date;
                $modelSave[] = $txModel;
                $auto_key_config[$i] = ['self_key' => 'transfer_ref_code', 'parent_key' => 'medicine_stock_transfer_txn_code', 'parent_index' => $index];
                $i = $i + 6;

                // from stock
                $historyModel = new TblMedicineStockHistory();
                Yii::$app->operation->history($stockModel, $historyModel, UPDATE);
                $modelSave[] = $historyModel;
                $old_stock = $stockModel->stock;
                $stockModel->stock = 0;
                $modelSave[] = $stockModel;
                $fstockTxnModel = new TblMedicineStockTransaction();
                $fstockTxnModel->attributes = $stockModel->attributes;
                unset($fstockTxnModel->created_at);
                unset($fstockTxnModel->created_by);
                unset($fstockTxnModel->updated_at);
                unset($fstockTxnModel->updated_by);
                unset($fstockTxnModel->originating_org_code);
                unset($fstockTxnModel->originating_org_type);
                unset($fstockTxnModel->originating_type);
                $fstockTxnModel->old_value = $old_stock;
                $fstockTxnModel->new_value = $txModel->qty;
                $fstockTxnModel->final_value = 0;
                $fstockTxnModel->entry_type = 'TRANSFER';
                $fstockTxnModel->tran_datetime = date('Y-m-d H:i:s');
                $fstockTxnModel->transfer_ref_name = 'tbl_medicine_stock_transfer_txn';
                $modelSave[] = $fstockTxnModel;
                // to stock
                $toStockModel = new TblMedicineStock();
                $toStockModel->union_code = $txModel->union_code;
                $toStockModel->medicine_id = $txModel->medicine_id;
                $toStockModel->module_name = 'USER';
                $toStockModel->module_code = $this->model->to_code;
                $toStockModel->batch_no = $txModel->batch_no;
                $existtoStock = $toStockModel->getExistStock();
                $t_stock = 0;
                $index = $index + 6;
                if (!empty($existtoStock)) {
                    $index = $index + 1;
                    $i = $i + 1;
                    $historyModel = new TblMedicineStockHistory();
                    Yii::$app->operation->history($existtoStock, $historyModel, UPDATE);
                    $modelSave[] = $historyModel;
                    $t_stock = $existtoStock->stock;
                    $existtoStock->stock = $t_stock + $txModel->qty;
                    $toStockModel = $existtoStock;
                } else {
                    $toStockModel->stock = $t_stock + $txModel->qty;
                    $toStockModel->rate = $stockModel->rate;
                    $toStockModel->expire_date = $stockModel->expire_date;
                }
                $modelSave[] = $toStockModel;
                $stockTxnModel = new TblMedicineStockTransaction();
                $stockTxnModel->attributes = $toStockModel->attributes;
                unset($stockTxnModel->created_at);
                unset($stockTxnModel->created_by);
                unset($stockTxnModel->updated_at);
                unset($stockTxnModel->updated_by);
                unset($stockTxnModel->originating_org_code);
                unset($stockTxnModel->originating_org_type);
                unset($stockTxnModel->originating_type);
                unset($stockTxnModel->transfer_ref_code);
                unset($stockTxnModel->transfer_ref_name);
                $stockTxnModel->old_value = $t_stock;
                $stockTxnModel->new_value = $txModel->qty;
                $stockTxnModel->final_value = $toStockModel->stock;
                $stockTxnModel->entry_type = 'RECEIVED';
                $stockTxnModel->tran_datetime = date('Y-m-d H:i:s');
                $modelSave[] = $stockTxnModel;
            }
        }
    }

    /**
     * Finds the TblMedicineStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMedicineStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMedicineStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUserList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                if (!empty($parents[4])) {
                    $type = ['DCS'];
                    $code = $parents[4];
                } else if (!empty($parents[3])) {
                    $type = ['BMC', 'DCS'];
                    $code = [$parents[3], $parents[4]];
                } else if (!empty($parents[2])) {
                    $type = ['MCC', 'BMC', 'DCS'];
                    $code = [$parents[2], $parents[3], $parents[4]];
                } else if (!empty($parents[1])) {
                    $type = ['PLANT', 'MCC', 'BMC', 'DCS'];
                    $code = [$parents[1], $parents[2], $parents[3], $parents[4]];
                } else {
                    $type = ['UNION', 'PLANT', 'MCC', 'BMC', 'DCS'];
                    $code = [$parents[0], $parents[1], $parents[2], $parents[3], $parents[4]];
                }
                $model = new TblMedicineStock();
                $data = $model->getUserList($type, $code);
                foreach ($data as $key => $val) {
                    $out[] = ['id' => $key, 'name' => $val];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }

        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionAllUserList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $model = new TblMedicineStock();
                $data = $model->getAllUserList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = ['id' => $key, 'name' => $val];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMedicineBatchList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2])) {
                $medicineStockModel = new TblMedicineStock();
                $data = $medicineStockModel->getMedicineBatchList($parents[0], $parents[1], $parents[2]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => (string) $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetAvailableStock() {
        $stockModel = new TblMedicineStock();
        $stockModel->medicine_id = Yii::$app->request->post('medicineId');
        $stockModel->union_code = Yii::$app->request->post('union');
        $stockModel->module_name = 'USER';
        $stockModel->module_code = Yii::$app->request->post('from_user_code');
        $stockModel->batch_no = Yii::$app->request->post('batch_no');

        $existtoStock = $stockModel->getExistStock();
        if (!empty($existtoStock->stock)) {
            return Json::encode(['status' => 'success', 'stock' => $existtoStock->stock, 'expireDate' => $existtoStock->expire_date]);
        } else {
            return Json::encode(['status' => 'success', 'stock' => 0, 'expireDate' => '']);
        }
    }

    public function actionListGrid() {
        $searchModel = new TblMedicineStockTransferTxnSearch();
        $searchModel->medicine_stock_transfer_code = Yii::$app->request->get()['medicine_stock_transfer_code'];
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

}
