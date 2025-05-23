<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductRequisitionTransaction;
use app\modules\product\models\TblProductRequisitionTransactionHistory;
use app\modules\product\models\TblProductRequisitionTransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblProductRequisition;
use app\modules\product\models\TblProductRequisitionHistory;
use app\modules\product\models\TblProductRequisitionSearch;
use yii\widgets\ActiveForm;
use app\components\Model;
use yii\helpers\Json;
use app\modules\product\models\TblProductPurchaseRateApplicability;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;

/**
 * TblProductRequisitionTransactionController implements the CRUD actions for TblProductRequisitionTransaction model.
 */
class TblProductRequisitionTransactionController extends \app\controllers\ChildController {

    public $searchModel;
    public $dataProvider;
    public $jsonEncoded;
    public $scheme;
    public $freeAccessActions = ['validate-vehicle', 'validate-product', 'validate-product-data'];

    /**
     * Lists all TblProductRequisitionTransaction models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductRequisitionTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductRequisitionTransaction model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $requisition = new TblProductRequisition();
        return $this->render('view', [
                    'model' => $this->findModel($id), 'requisition' => $requisition
        ]);
    }

    /**
     * Creates a new TblProductRequisitionTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblProductRequisitionTransaction();
        $this->viewFile = 'create';
        $this->searchModel = new TblProductRequisitionTransactionSearch();
        $this->searchModel->product_requisition_code = Yii::$app->getRequest()->getQueryParam('id');
        $this->dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);
        $reqModel = new TblProductRequisition();
        if (Yii::$app->request->get('id') != -1) {
            $reqModel = $reqModel->getRecord(Yii::$app->request->get('id'));
            $this->jsonEncoded = Json::encode($reqModel->attributes);
        }
        if (Yii::$app->request->post()) {
            if (Yii::$app->getRequest()->getQueryParam('id') == -1) {
                $jsonData = Json::decode($_POST['product_req']);
                $list = [];
                $reqModel->addProductRequisition($jsonData);
                $reqCode = $reqModel->product_requisition_code;
                $reqModel->req_date = !empty($reqModel->req_date) ? Yii::$app->controls->save_datetime($reqModel->req_date) : NULL;
                $product_code = Yii::$app->request->post()['TblProductRequisitionTransaction']['product_code'];
                $dispatch_center = Yii::$app->general->getDispatchCenter($reqModel->dcs_code, 'DCS', $product_code);
                if(empty($dispatch_center)){
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Dispatch center not mapped']);
                    return $this->redirect(Yii::$app->request->referrer);
                }
            } else {
                $reqCode = Yii::$app->getRequest()->getQueryParam('id');
            }
            $transaction = FALSE;
            if ($this->model->load(Yii::$app->request->post()) && Yii::$app->request->post('submit') === 'save') {
                $reqModel->status = 'Draft'; //1
                $this->model->status = 'Draft'; //1
                $reqModel->is_sentbox = FALSE;
                $this->model->scenario = 'addProduct';
                $this->model->is_sentbox = FALSE;
                $this->model->product_requisition_code = $reqCode;
                $this->model->requisition_transaction_code = Yii::$app->general->getTransactionCode($this->model, $this->model->product_requisition_code);
                $this->model->requisition_on_date = !empty($this->model->requisition_on_date) ? Yii::$app->formatter->asDate($this->model->requisition_on_date, DATE_FORMAT) : NULL;
                $this->model->dispatch_center_code =  !empty($dispatch_center) ? $dispatch_center['dispatch_center_code'] : NULL;
                if (Yii::$app->request->get('id') == -1) {
                    $transaction = $this->generalModel->saveTransaction([$reqModel], [$this->model], ['Product Requisition transaction', 'create']);
                } else {
                    $transaction = $this->generalModel->saveTransaction([$this->model], ['Product Requisition transaction', 'create']);
                }
            } else if (Yii::$app->request->post('submit') === 'submit') {
                $reqlist = [];
                $historyModel = new TblProductRequisitionHistory();
                Yii::$app->operation->history($reqModel, $historyModel, UPDATE);

                $this->model->scenario = 'submit';
                $reqModel->status = 'Sent'; //6
                $reqModel->operation = 'INSERT';
                $allreq = TblProductRequisitionTransaction::find()
                        ->where(['product_requisition_code' => $reqModel->product_requisition_code])
                        ->all();
                for ($i = 0; $i < count($allreq); $i++) {

                    $TransactionhistoryModel = new TblProductRequisitionTransactionHistory();
                    Yii::$app->operation->history($allreq[$i], $TransactionhistoryModel, UPDATE);
                    $reqlist[] = $TransactionhistoryModel;
                    $allreq[$i]->operation = 'INSERT';
                    $allreq[$i]->status = 'Sent'; //6
                    $allreq[$i]->scenario = 'addProduct';
                    $reqlist[] = $allreq[$i];
                }
                $transaction = $this->generalModel->saveTransaction([$reqModel, $historyModel], $reqlist, ['Product Requisition', 'create']);
            }

            if ($transaction == 'customRedirect') {
                if (Yii::$app->request->post('submit') == 'submit') {
                    return $this->redirect(['tbl-product-requisition/index']);
                }
                $this->model->requisition_on_date = date('d-m-Y', strtotime($this->model->requisition_on_date));
                return $this->{$transaction}();
            } else {
                $this->model->uom = $this->model->getUom($this->model->product_code);
            }
        }

        return $this->customRender();
    }

    protected function customRender() {
        return $this->render($this->viewFile, [
                    'model' => $this->model, 'searchModel' => $this->searchModel,
                    'dataProvider' => $this->dataProvider, 'jsonEncoded' => $this->jsonEncoded,
                    'scheme' => !empty($this->scheme) ? $this->scheme->discription : '',
        ]);
    }

    /**
     * Updates an existing TblProductRequisitionTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->searchModel = new TblProductRequisitionTransactionSearch();
        $this->searchModel->product_requisition_code = $this->model->product_requisition_code;
        $this->dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);
        $this->model->product_name = $this->model->productCode->product_name;

        $reqModel = new TblProductRequisition();
        $reqModel = $reqModel->getRecord($this->model->product_requisition_code);

        $this->model->uom = $this->model->getUom($this->model->product_code);
        $this->jsonEncoded = Json::encode($reqModel->attributes);
        if (Yii::$app->request->post()) {

            $historyModel = new TblProductRequisitionTransactionHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());

            $reqModel = new TblProductRequisition();
            $reqModel = $reqModel->getRecord($this->model->product_requisition_code);
            $this->model->requisition_on_date = Yii::$app->formatter->asDate($this->model->requisition_on_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Product Requisition transaction', 'edit']);
            if ($transaction == 'customRedirect') {
                $this->model->requisition_on_date = date('d-m-Y', strtotime($this->model->requisition_on_date));
                return $this->{$transaction}();
            } else {
                $this->model->uom = $this->model->getUom($this->model->product_code);
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblProductRequisitionTransaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function customRedirect() {
        return $this->redirect(['create', 'id' => $this->model->product_requisition_code]);
    }

    public function actionValidateProductOld() {

        $array = ['status' => 'error'];
        if (!empty(Yii::$app->request->post('id'))) {
            $model = new TblProductRequisitionTransaction();
            $array = $model->checkProductAvailabel(Yii::$app->request->post('id'), Yii::$app->request->post('rid'), Yii::$app->request->post('date'));
        }
        return Json::encode($array);
        return;
    }

    /**
     * Finds the TblProductRequisitionTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductRequisitionTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductRequisitionTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /////old code rename function name only//////
    public function actionAcceptRequisitionOld($id) {
        //        $this->layout = "@app/themes/nddb/layouts/dashboardLayout.php";
        $this->model = new TblProductRequisition();
        $this->model = $this->model->getRecord($id);
        $this->viewFile = 'accept_requisition_old';
        $this->searchModel = new TblProductRequisitionSearch();
        //        $this->dataProvider = $this->searchModel->searchRequisition(Yii::$app->request->queryParams);
        $schememodal = new TblProductRequisitionTransaction();
        if ((Yii::$app->request->post())) {
            $approvedByUser = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            $modelAttributes = Model::createMultiple(TblProductRequisitionTransaction::classname(), [], '');
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
            $app = 1;
            if (!$validate && $modelAttributes) {
                $checked = Yii::$app->request->post()['TblProductRequisitionTransaction'];
                $totalItems = count($this->model->tblProductRequisitionTransactions);

                $modelAttributes = $_POST['TblProductRequisitionTransaction'];
                $list = [];
                $rcnt = 0;
                $cnt = 0;
                foreach ($checked as $key => $row) {
                    $modelNew = TblProductRequisitionTransaction::findOne($modelAttributes[$key]['requisition_transaction_code']);
                    if (isset($modelAttributes[$key]['req_action']) && $modelAttributes[$key]['req_action'] != '' && empty($modelNew->parent_product_code)) {
                        if ($modelNew->status == 'Sent' || $modelAttributes[$key]['req_action'] == 2 || $modelNew->approved_quantity != $modelAttributes[$key]['approved_quantity'] || $modelNew->discount_amount != $modelAttributes[$key]['discount_amount']) {
                            if (!empty($modelNew->approved_date)) {
                                $old_scheme = ''; //$modelNew->getProductScheme($modelNew->approved_date, $modelNew->approved_quantity);
                            } else {
                                $old_scheme = '';
                            }
                            $historyModel = new TblProductRequisitionTransactionHistory();
                            Yii::$app->operation->history($modelNew, $historyModel, UPDATE);
                            array_push($list, $historyModel);
                            $modelNew->approved_quantity = $modelAttributes[$key]['approved_quantity'];
                            $modelNew->discount_amount = $modelAttributes[$key]['discount_amount'];
                            $modelNew->approved_date = date('Y-m-d');
                            $modelNew->is_approved = $app;
                            $modelNew->approved_by = $approvedByUser;
                            $modelNew->provisional_amount = round($modelNew->provisional_rate * $modelNew->approved_quantity, 2);
                            if ($modelAttributes[$key]['req_action'] == '2') {
                                $modelNew->status = 'Rejected'; //11
                                $rcnt++;
                            } else {
                                $modelNew->status = 'Under Dispatch'; //46
                                if ($modelNew->approved_quantity == 0) {
                                    $modelNew->status = 'Rejected'; //11
                                    $rcnt++;
                                }
                            }
                            $modelNew->operation = FALSE;
                            array_push($list, $modelNew);
                        }
                    } else {
                        if (isset($modelAttributes[$key]['approved_quantity']) && $modelNew->approved_quantity != $modelAttributes[$key]['approved_quantity'] && $modelAttributes[$key]['req_action'] == '1') {
                            $historyModel = new TblProductRequisitionTransactionHistory();
                            Yii::$app->operation->history($modelNew, $historyModel, UPDATE);
                            array_push($list, $historyModel);
                            $modelNew->approved_quantity = $modelAttributes[$key]['approved_quantity'];
                            $modelNew->provisional_amount = round($modelNew->provisional_rate * $modelNew->approved_quantity, 2);

                            if ($modelNew->approved_quantity == 0) {
                                $modelNew->status = 'Rejected'; //11
                            }
                            $modelNew->approved_date = date('Y-m-d');
                            $modelNew->is_approved = $app;
                            $modelNew->approved_by = $approvedByUser;
                            $modelNew->operation = FALSE;
                            array_push($list, $modelNew);
                        }

                        if ($modelNew->status == 'Rejected') {
                            $rcnt++;
                        }
                    }
                }

                if ($rcnt == $totalItems) {
                    $status = 'Rejected'; //11;
                } else {
                    $status = 'Under Dispatch'; //21;
                }


                if (!empty($list)) {
                    if ($this->model->status != $status) {
                        $historyModel = new TblProductRequisitionHistory();
                        Yii::$app->operation->history($this->model, $historyModel, UPDATE);

                        $this->model->operation = FALSE;
                        $this->model->status = $status;

                        $master = [$this->model, $historyModel];
                    } else {
                        $master = [];
                    }

                    $transaction = $this->generalModel->saveTransaction($list, $master, ['product requisition', 'create']);
                    if ($transaction !== FALSE) {
                        if ($transaction == 'customRedirect') {
                            return $this->redirect(['tbl-product-requisition/index']);
                        }
                    }
                } else {
                    return $this->redirect(['tbl-product-requisition/index']);
                }
            }
        }
        return $this->render($this->viewFile, [
                    'model' => $this->model, 'schememodal' => $schememodal
        ]);
    }

    public function actionAcceptRequisition() {
        $searchModel = new TblProductRequisitionTransactionSearch();
        $searchModel->scenario = 'acceptRequisition';
        $dataProvider = $searchModel->searchAcceptRequisition(Yii::$app->request->queryParams);
        $this->model = $dataProvider->getModels();
        $this->viewFile = 'accept_requisition';
        $selectedArr = [];
        if ((Yii::$app->request->post())) {
            $approvedByUser = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            $app = 1;
            if (true) {
                $modelAttributes = $_POST['TblProductRequisitionTransaction'];
                $list = [];
                $rcnt = 0;
                $cnt = 0;
                $validData = true;
                foreach ($modelAttributes as $key => $row) {
                    $selectedArr[] = $key;
                    $modelNew = TblProductRequisitionTransaction::findOne($modelAttributes[$key]['requisition_transaction_code']);
                    if (isset($modelAttributes[$key]['req_action']) && $modelAttributes[$key]['req_action'] != '' && empty($modelNew->parent_product_code)) {
                        if ($modelNew->status == 'Sent' || $modelAttributes[$key]['req_action'] == 2 || $modelNew->approved_quantity != $modelAttributes[$key]['approved_quantity'] || $modelNew->discount_amount != $modelAttributes[$key]['discount_amount']) {
                            if (!empty($modelNew->approved_date)) {
                                $old_scheme = ''; //$modelNew->getProductScheme($modelNew->approved_date, $modelNew->approved_quantity);
                            } else {
                                $old_scheme = '';
                            }
                            $historyModel = new TblProductRequisitionTransactionHistory();
                            Yii::$app->operation->history($modelNew, $historyModel, UPDATE);
                            array_push($list, $historyModel);
                            $modelNew->approved_quantity = $modelAttributes[$key]['approved_quantity'];
                            $modelNew->discount_amount = $modelAttributes[$key]['discount_amount'];
                            $modelNew->x_col2 = $modelAttributes[$key]['x_col2'];
                            $modelNew->approved_date = date('Y-m-d');
                            $modelNew->is_approved = $app;
                            $modelNew->approved_by = $approvedByUser;
                            $modelNew->provisional_amount = round($modelNew->provisional_rate * $modelNew->approved_quantity, 2);

                            $modelNew->operation = FALSE;
                            if ($modelNew->validate()) {
                                if ($modelAttributes[$key]['req_action'] == '2') {
                                    $modelNew->status = 'Rejected'; //11
                                    $rcnt++;
                                } else {
                                    $modelNew->status = 'Under Dispatch'; //46
                                    if ($modelNew->approved_quantity == 0) {
                                        $modelNew->status = 'Rejected'; //11
                                        $rcnt++;
                                    }
                                }
                                array_push($list, $modelNew);
                                $masterProductRequisition = TblProductRequisition::find()->where(['product_requisition_code' => $modelNew->product_requisition_code, 'status' => 'Sent'])->one();
                                if($modelNew->status == 'Under Dispatch' && !empty($masterProductRequisition)){
                                    $masterProductRequisition->status = 'Under Dispatch';
                                    array_push($list, $masterProductRequisition);
                                } else if($modelNew->status == 'Rejected' && !empty($masterProductRequisition)){
                                    $allTractionCheck = TblProductRequisitionTransaction::find()->where(['product_requisition_code' => $modelNew->product_requisition_code])->andWhere(['<>','requisition_transaction_code', $modelNew->requisition_transaction_code])->andWhere(['status' => ['Sent','Under Dispatch','Dispatched']])->all();
                                    if(empty($allTractionCheck)){
                                        $masterProductRequisition->status = 'Rejected';
                                        array_push($list, $masterProductRequisition);
                                    }
                                }
                            } else {
                                $validData = false;
                                $this->model[$key] = $modelNew;
                                $this->model[$key]->req_action = $modelAttributes[$key]['req_action'];
                            }
                        }
                    } else {
                        if (isset($modelAttributes[$key]['approved_quantity']) && $modelNew->approved_quantity != $modelAttributes[$key]['approved_quantity'] && $modelAttributes[$key]['req_action'] == '1') {
                            $historyModel = new TblProductRequisitionTransactionHistory();
                            Yii::$app->operation->history($modelNew, $historyModel, UPDATE);
                            array_push($list, $historyModel);
                            $modelNew->approved_quantity = $modelAttributes[$key]['approved_quantity'];
                            $modelNew->x_col2 = $modelAttributes[$key]['x_col2'];
                            $modelNew->provisional_amount = round($modelNew->provisional_rate * $modelNew->approved_quantity, 2);

                            $modelNew->approved_date = date('Y-m-d');
                            $modelNew->is_approved = $app;
                            $modelNew->approved_by = $approvedByUser;
                            $modelNew->operation = FALSE;
                            if ($modelNew->validate()) {
                                if ($modelNew->approved_quantity == 0) {
                                    $modelNew->status = 'Rejected'; //11
                                }
                                array_push($list, $modelNew);
                                $masterProductRequisition = TblProductRequisition::find()->where(['product_requisition_code' => $modelNew->product_requisition_code, 'status' => 'Sent'])->one();
                                if($modelNew->status == 'Under Dispatch' && !empty($masterProductRequisition)){
                                    $masterProductRequisition->status = 'Under Dispatch';
                                    array_push($list, $masterProductRequisition);
                                } else if($modelNew->status == 'Rejected' && !empty($masterProductRequisition)){
                                    $allTractionCheck = TblProductRequisitionTransaction::find()->where(['product_requisition_code' => $modelNew->product_requisition_code])->andWhere(['<>','requisition_transaction_code', $modelNew->requisition_transaction_code])->andWhere(['status' => ['Sent','Under Dispatch','Dispatched']])->all();
                                    if(empty($allTractionCheck)){
                                        $masterProductRequisition->status = 'Rejected';
                                        array_push($list, $masterProductRequisition);
                                    }
                                }
                            } else {
                                $validData = false;
                                $this->model[$key] = $modelNew;
                                $this->model[$key]->req_action = $modelAttributes[$key]['req_action'];
                            }
                        }

                        if ($modelNew->status == 'Rejected') {
                            $rcnt++;
                        }
                    }
                }
                if (!empty($list)) {
                    $master = [];
                    $transaction = $this->generalModel->saveTransaction($list, $master, ['product requisition', 'create']);
                    if ($transaction !== FALSE) {
                        if ($transaction == 'customRedirect') {
                            return $this->redirect(['tbl-product-requisition/index']);
                        }
                    }
                } else {
                    return $this->redirect(['tbl-product-requisition/index']);
                }
            }
        }
        return $this->render($this->viewFile, [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectedArr' => $selectedArr,
        ]);
    }

    public function actionValidateProduct() {

//        $array = ['status' => 'error'];
//        if (!empty(Yii::$app->request->post('id'))) {
//            $model = new TblProductRequisitionTransaction();
//            $array = $model->checkProductAvailabel(Yii::$app->request->post('id'), Yii::$app->request->post('rid'), Yii::$app->request->post('date'));
//        }
//        return Json::encode($array);
//        return;


        $app = ['status' => 'error'];
        if (!empty($_POST['id']) && !empty($_POST['customer_type']) && !empty($_POST['customer_code'])) {
            $date = !empty($_POST['date']) ? date('Y-m-d', strtotime($_POST['date'])) : date('Y-m-d');

            $appQuery = TblProductPurchaseRateApplicability::find()->joinWith(['productPurchaseRateCode', 'productCode', 'productCode.primaryUom'])
                    ->select(['tbl_product_purchase_rate.purchase_rate', 'tbl_product.product_name', 'tbl_units.unit_name', 'tbl_product.tax_code', 'tbl_product_purchase_rate_applicability.wef_date as dt'])
//                    ->groupBy(['product_purchase_rate_applicability_code', 'tbl_product_purchase_rate.purchase_rate', 'tbl_product_purchase_rate_applicability.wef_date', 'tbl_product.unit_code'])
                    ->where(['<=', '[tbl_product_purchase_rate_applicability].[wef_date]', $date])
                    ->andWhere(['tbl_product_purchase_rate.product_code' => $_POST['id'], 'tbl_product_purchase_rate_applicability.applicable_for' => $_POST['customer_type'], 'tbl_product_purchase_rate_applicability.applicable_code' => $_POST['customer_code']]);
            $appData = $appQuery->orderBy(['tbl_product_purchase_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
            if (!empty($appData)) {
                $app = ['status' => 'success', 'name' => $appData['product_name'], 'rate' => $appData['purchase_rate'], 'unit' => $appData['unit_name'], 'tax_code' => $appData['tax_code']];
            }
        }
        echo json_encode($app);
    }

    public function actionValidateProductData() {

        $app = ['status' => 'error'];
        if (!empty($_POST['id']) && !empty($_POST['customer_type']) && !empty($_POST['customer_code'])) {
            $date = !empty($_POST['date']) ? date('Y-m-d', strtotime($_POST['date'])) : date('Y-m-d');

            $productData = TblProduct::find()
                            ->where(['product_code' => $_POST['id']])->one();
            if (!empty($productData)) {
                $unit = !empty($productData->unitCode) ? $productData->unitCode->unit_name : '';
                $app = ['status' => 'success', 'name' => $productData->product_name, 'unit' => $unit];
            }
        }
        echo json_encode($app);
    }
    
    public function actionCheckDispatchCenterApplicability() {
        $post_data = Yii::$app->request->post();
        $dispatch_center = Yii::$app->general->getDispatchCenter($post_data['dcs_code'], 'DCS', $post_data['product_code']);
        $app = ['status' => 'success', 'message' => 'Dispatch center mapped'];
        if(empty($dispatch_center)){
            $app = ['status' => 'error', 'message' => 'Dispatch center not mapped'];
        }
        echo json_encode($app);
    }

}
