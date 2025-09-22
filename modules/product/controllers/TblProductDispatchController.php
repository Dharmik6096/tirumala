<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductDispatch;
use app\modules\product\models\TblProductDispatchHistory;
use app\modules\product\models\TblProductDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblProductDispatchTransaction;
use app\modules\product\models\TblProductDispatchTransactionHistory;
use app\modules\product\models\TblProductRequisition;
use app\modules\product\models\TblProductRequisitionSearch;
use app\modules\product\models\TblProductRequisitionHistory;
use app\modules\product\models\TblProductRequisitionTransaction;
use app\modules\product\models\TblProductRequisitionTransactionHistory;
use app\components\Model;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\modules\product\models\TblProductDispatchTransactionSearch;
use app\modules\product\models\TblProductPurchaseRateApplicability;

/**
 * TblProductDispatchController implements the CRUD actions for TblProductDispatch model.
 */
class TblProductDispatchController extends \app\controllers\ChildController {

    public $searchModel;
    public $dataProvider;
    public $flag;
    public $freeAccessActions = ['validate-vehicle', 'validate-product'];

    /**
     * Lists all TblProductDispatch models.
     * @return mixed
     */
    // public function actionIndex() {
    //     $searchModel = new TblProductDispatchSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     $title = 'Product Dispatch with Requisition';
    //     if (Yii::$app->getRequest()->getQueryParam('flag') == 0) {
    //         $title = 'Product Dispatch without Requisition';
    //     }
    //     return $this->render('index', [
    //                 'searchModel' => $searchModel,
    //                 'dataProvider' => $dataProvider,
    //                 'title' => $title
    //     ]);
    // }

    public function actionIndex() {
        $searchModel = new TblProductDispatchTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $title = 'Product Dispatch with Requisition';
        if (Yii::$app->getRequest()->getQueryParam('flag') == 0) {
            $title = 'Product Dispatch without Requisition';
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => $title
        ]);
    }

    /**
     * Displays a single TblProductDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblProductDispatchTransactionSearch();
        $searchModel->challan_no = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'searchModel' => $searchModel, 'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new TblProductDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        //        $this->layout = "@app/themes/nddb/layouts/dashboardLayout.php";
        $this->model = new TblProductDispatch();
        $this->viewFile = 'create';
        $this->searchModel = new TblProductRequisitionSearch();
        $schememodal = new TblProductDispatchTransaction();
        if (Yii::$app->request->queryParams) {
            $this->searchModel->scenario = 'searchdispatch';
        }
        $this->dataProvider = $this->searchModel->searchDispatchRequisition(Yii::$app->request->queryParams);
        $this->flag = 1;
        $modeltransaction = new TblProductDispatchTransaction();

        if (Yii::$app->request->post()) {
            if (!isset($_POST['TblProductDispatchTransaction'][0]['product_requisition_code'])) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', 'Product not found for dispatch')]);
                return $this->redirect(['create']);
            }
        }

        if ($modeltransaction->load(Yii::$app->request->post())) {
            $modelAttributes = Model::createMultiple(TblProductDispatchTransaction::classname(), [], '');
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
            $this->model->load(Yii::$app->request->post());
            $dcsarray = [];
            $list = [];
            $reqlist = [];
            $cnt = 1;
            $dis_auto = 1;

            if (!$validate && $modelAttributes) {
                $requisitionCodes = [];
                foreach ($modelAttributes as $key => $row) {
                    if (!isset($dcsarray[$modelAttributes[$key]->vendor_code])) {
                        $dispatch = new TblProductDispatch();
                        $dispatch->setAttributes($this->model->attributes);
                        $dispatch->challan_no = Yii::$app->general->getPrimaryCode($this->model, $dis_auto); //$this->model->getChallanNo($modelAttributes[$key]->sub_center_code);
                        $dcsarray[$modelAttributes[$key]->vendor_code] = $dispatch->challan_no;
                        $dispatch->union_code = $modelAttributes[$key]->union_code;
                        $dispatch->plant_code = $modelAttributes[$key]->plant_code;
                        $dispatch->mcc_plant_code = $modelAttributes[$key]->mcc_plant_code;
                        $dispatch->bmc_code = $modelAttributes[$key]->bmc_code;
                        $dispatch->dcs_code = $modelAttributes[$key]->dcs_code;
                        $dispatch->challan_date = date('Y-m-d', strtotime($this->model->challan_date));
                        $dispatch->dispatch_date = $dispatch->challan_date;
                        $dispatch->vendor_code = empty($dispatch->vendor_code) ? $modelAttributes[$key]->vendor_code : $dispatch->vendor_code;
                        $dispatch->vendor_type = empty($dispatch->vendor_type) ? $modelAttributes[$key]->vendor_type : $dispatch->vendor_type;
                        //                        $dispatch->is_active = 1;
                        $list[] = $dispatch;
                        $dis_auto++;
                    }
                    $dispatchitem = new TblProductDispatchTransaction();
                    $dispatchitem->setAttributes($modelAttributes[$key]->attributes);
                    $dispatchitem->challan_no = $dcsarray[$modelAttributes[$key]->vendor_code];
                    $dispatchitem->dispatch_transaction_code = $dispatchitem->challan_no . 'T' . $cnt; //str_pad($cnt, 2, '0', STR_PAD_LEFT);
                    $dispatchitem->dispatch_date = date('Y-m-d', strtotime($this->model->challan_date));
                    $dispatchitem->amount = round($dispatchitem->rate * $dispatchitem->dispatch_qty, 2);
                    $cnt++;

                    $reqTransaction = new TblProductRequisitionTransaction();
                    $reqTransaction = $reqTransaction->getRecord($row['requisition_transaction_code']);
                    $requisitionCodes[$row['product_requisition_code']] = $row['product_requisition_code'];
                    $previousQty = $dispatchitem->getPreviousDispQty($dispatchitem->requisition_transaction_code);
                    $historyModel = new TblProductRequisitionTransactionHistory();
                    Yii::$app->operation->history($reqTransaction, $historyModel, UPDATE);
                    $reqlist[] = $historyModel;
                    //                    if ($modelAttributes[$key]->is_close == 1) {
                    //                        $reqTransaction->status = 56;
                    //                        $reqTransaction->is_sentbox = FALSE;
                    //                    } else {
                    //                    if (($reqTransaction->quantity == ($previousQty + $dispatchitem->dispatch_qty))) {
                    //                        $reqTransaction->status = 36;
                    //                    } else {
                    $reqTransaction->status = 'Dispatched'; //26;
                    $dispatchitem->status = 'Dispatched'; //26;
                    //                    }
                    $reqTransaction->operation = FALSE;
                    //                    }
                    $list[] = $dispatchitem;
                    $reqTransaction->scenario = 'dispatchWithReq';
                    $reqlist[] = $reqTransaction;
                }
                foreach ($requisitionCodes as $requisitionCode) {
                    $reqModel = new TblProductRequisition();
                    $reqModelData = $reqModel->findOne($requisitionCode);
                    if (!empty($reqModelData)) {
                        $reqModel = $reqModelData;
                        $historyModel = new TblProductRequisitionHistory();
                        Yii::$app->operation->history($reqModel, $historyModel, UPDATE);
                        $reqModel->status = 'Dispatched';
                        $reqlist[] = $historyModel;
                        $reqlist[] = $reqModel;
                    }
                }
                $transaction = $this->generalModel->saveTransaction($list, $reqlist, ['Product Dispatch with Requisition', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index', 'flag' => 1]);
                }
            }
        }

        return $this->render('create', [
                    'model' => $this->model, 'searchModel' => $this->searchModel,
                    'dataProvider' => $this->dataProvider, 'modeltransaction' => $modeltransaction, 'schememodal' => $schememodal
        ]);
    }

    /**
     * Updates an existing TblProductDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->challan_no]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblProductDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblProductDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDispatchWithoutRequisition() {
//        $this->layout = "@app/themes/nddb/layouts/dashboardLayout.php";
        $this->model = new TblProductDispatch();
        $this->model->scenario = 'without';
        $this->viewFile = '_form_without_req';
        $this->flag = 0;
        $dispatchtransaction = new TblProductDispatchTransaction();
        if (Yii::$app->request->post()) {
            $masterdata = Json::decode($_POST['dispatch_master']);
            $transactiondata = Json::decode($_POST['dispatch_transaction']);
            $dcsarray = [];
            $master = [];
            $list = [];
            $cntarray = [];
            $cnt = 1;
            $challan = '';
            foreach ($transactiondata as $key => $row) {
//                if (!isset($dcsarray[$transactiondata[$key]['vendor_code']])) {
                if ($challan == '') {
//                    $cntarray[$transactiondata[$key]['vendor_code']] = 1;
                    $dispatch = new TblProductDispatch();
                    $dispatch->setAttributes($masterdata[0]);
                    $dispatch->challan_no = Yii::$app->general->getPrimaryCode($dispatch); //$this->model->getChallanNo($transactiondata[$key]['sub_center_code']);
//                    $dcsarray[$transactiondata[$key]['vendor_code']] = $dispatch->challan_no;
                    $challan = $dispatch->challan_no;
                    $dispatch->union_code = $transactiondata[$key]['union_code'];
                    $dispatch->plant_code = $transactiondata[$key]['plant_code'];
                    $dispatch->mcc_plant_code = $transactiondata[$key]['mcc_plant_code'];
                    $dispatch->bmc_code = $transactiondata[$key]['bmc_code'];
                    $dispatch->dcs_code = $transactiondata[$key]['dcs_code'];
//                    $dispatch->operation = 'INSERT';
                    $dispatch->challan_date = date('Y-m-d', strtotime($masterdata[0]['challan_date']));
                    $dispatch->dispatch_date = $dispatch->challan_date;
//                    $dispatch->is_active = 1;
                    $master[] = $dispatch;
                }
                $dispatchitem = new TblProductDispatchTransaction();
                $dispatchitem->setAttributes($transactiondata[$key]);
                $dispatchitem->challan_no = $challan;
                $dispatchitem->dispatch_transaction_code = $dispatchitem->challan_no . 'T' . $cnt;//$cntarray[$transactiondata[$key]['vendor_code']];
                $dispatchitem->dispatch_date = date('Y-m-d', strtotime($masterdata[0]['challan_date']));
                $dispatchitem->status = 'Dispatched';
//                $dispatchitem->operation = 'INSERT';
                $cnt++;
//                $cntarray[$transactiondata[$key]['vendor_code']] = $cntarray[$transactiondata[$key]['vendor_code']] + 1;
                $list[] = $dispatchitem;
            }

            $transaction = $this->generalModel->saveTransaction($master, $list, ['Product Dispatch without Requisition', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index', 'flag' => 0]);
//                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, [
                    'model' => $this->model, 'transaction' => $dispatchtransaction
        ]);
    }

    public function actionValidateProduct() {

        $app = ['rate' => '', 'status' => 'error'];
        if (!empty($_POST['product_code']) && !empty($_POST['customer_type']) && !empty($_POST['customer_code'])) {
            $date = !empty($_POST['challan_date']) ? date('Y-m-d', strtotime($_POST['challan_date'])) : date('Y-m-d');

            $appQuery = TblProductPurchaseRateApplicability::find()->innerJoinWith(['productPurchaseRateCode', 'productCode'])
                    ->select(['tbl_product_purchase_rate.purchase_rate', 'tbl_product_purchase_rate_applicability.wef_date as dt'])->groupBy(['product_purchase_rate_applicability_code', 'tbl_product_purchase_rate.purchase_rate', 'tbl_product_purchase_rate_applicability.wef_date', 'tbl_product.unit_code'])
                    ->having(['<=', '[tbl_product_purchase_rate_applicability].[wef_date]', $date])
                    ->where(['tbl_product_purchase_rate.product_code' => $_POST['product_code'], 'tbl_product_purchase_rate_applicability.applicable_for' => $_POST['customer_type'], 'tbl_product_purchase_rate_applicability.applicable_code' => $_POST['customer_code']]);
            $appData = $appQuery->orderBy(['tbl_product_purchase_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
            if (!empty($appData)) {
                $app = ['rate' => $appData['purchase_rate'], 'status' => 'success'];
            }
        }
        echo json_encode($app);
    }

}
