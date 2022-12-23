<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblProductSale;
use app\modules\payment\models\TblProductSaleSearch;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblUnions;
use app\modules\payment\models\TblSaleInstallments;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\payment\models\TblSaleInstallmentsSearch;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\modules\payment\models\TblProductSaleTransactionSearch;
use app\modules\payment\models\TblProductSaleTransaction;
use app\modules\payment\models\TblMemberCreditLimit;
use app\modules\payment\models\TblMemberCreditLimitHistory;
use app\modules\payment\models\TblMemberCreditLimitTransaction;
use app\modules\product\models\TblProductSaleRateApplicability;
use app\modules\product\models\TblProductSaleRate;
use yii\widgets\ActiveForm;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\dcsaccounting\models\TblTaxDetail;
use app\modules\dcsaccounting\models\TblTaxDepends;
use app\modules\configuration\models\TblDcsGeneralConfig;
use app\modules\payment\models\TblProductSaleTaxCalculated;
use app\modules\payment\models\TblProductSaleTaxCalculatedHistory;
use app\modules\organisation\models\TblDcs;
use app\modules\payment\models\TblProductSaleHistory;
use app\modules\payment\models\TblProductSaleTransactionHistory;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblMilkCollection;

/**
 * TblProductSaleController implements the CRUD actions for TblProductSale model.
 */
class TblProductSaleController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-calculation', 'validate-customer', 'load-rate', 'get-available-stock'];

    /**
     * Lists all TblProductSale models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductSaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductSale model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblProductSaleTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblProductSale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblProductSale();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->product_sale_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new TblProductSale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSalePayment($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'payment';
        $this->model->scenario = 'validate_credit';
//        $this->model->scenario='payment';        
        $appCycleModel = new TblDcsPaymentCycleApplicability();
        $cycleModel = new \app\modules\payment\models\TblDcsPaymentCycle();
        if (Yii::$app->request->post()) {
            // $historyModel = new TblProductHistory();
            //Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            //var_dump($this->model);exit;
            if ($this->model->validate()) {
                $this->model->is_installment = 1;
                $this->model->no_of_installment = 1;
                $installments = [];
                if ($this->model->is_installment == 1 && $this->model->amount_due > 0 && $this->model->no_of_installment > 0) {
                    $installment_amt = ceil($this->model->amount_due / $this->model->no_of_installment);
                    $cycle = $this->model->payment_cycle_code;
                    for ($i = 0; $i < $this->model->no_of_installment; $i++) {
                        if ($cycle == 0) {
                            $this->model->addError('payment_cycle_code', 'Enough payment cycles not available. Please add payment cycles.');
                            return $this->render('payment', [
                                        'model' => $this->model,
                                        'paymentCycle' => $appCycleModel->dcsPaymentCycle($this->model->dcs_code)
                            ]);
                        }

                        $installmentModel = new TblSaleInstallments();
//                        $installmentModel->installment_code=Yii::$app->general->getCodeAutoIncrement($installmentModel)+$i;
                        $installmentModel->sale_type = 'product';
                        $installmentModel->sale_code = $this->model->product_sale_code;
                        $installmentModel->member_code = $this->model->member_code;
                        $installmentModel->dcs_code = $this->model->dcs_code;
                        $installmentModel->union_code = $this->model->union_code;
                        $installmentModel->main_amount = $this->model->amount_due;
                        $installmentModel->installment_amount = $installment_amt;
                        $installmentModel->installment_status = 0;
                        $installmentModel->is_active = 1;
                        $installmentModel->dcs_payment_cycle_code = $cycle;
                        $installmentModel->payment_cycle_applicability_code = $appCycleModel->dcsPaymentCycleAppCode($this->model->dcs_code);
                        $cycle = $cycleModel->getNextCycleCode($installmentModel->dcs_payment_cycle_code, $this->model->dcs_code);
                        array_push($installments, $installmentModel);
                    }
                    //exit;
                }

                $credit_limit_model = new TblMemberCreditLimit();
                $credit_limit_data = $credit_limit_model->find()->where(['member_code' => $this->model->member_code])->one();

                if (!empty($credit_limit_data)) {
                    $credit_limit_history_model = new TblMemberCreditLimitHistory();
                    Yii::$app->operation->history($credit_limit_data, $credit_limit_history_model, UPDATE);

                    $old_balance = $credit_limit_data->balance;
                    $due = $this->model->amount_due;
                    $new_balance = $old_balance - $due;
                    $credit_limit_data->balance = $new_balance;
                    array_push($installments, $credit_limit_data);
                    array_push($installments, $credit_limit_history_model);
                    $credit_limit_transaction_model = new TblMemberCreditLimitTransaction();
                    $credit_limit_transaction_model->member_credit_limit_code = $credit_limit_data->member_credit_limit_code;
                    $credit_limit_transaction_model->old_value = $old_balance;
                    $credit_limit_transaction_model->transaction_type = 2;
                    $credit_limit_transaction_model->new_value = $due;
                    $credit_limit_transaction_model->balance = $new_balance;
                    array_push($installments, $credit_limit_transaction_model);
                }

                if (!empty($installments)) {
                    $transaction = $this->generalModel->saveTransaction([$this->model], $installments, ['Product Sale', 'edit']);
                } else {
                    $this->model->is_installment = 0;
                    $this->model->no_of_installment = 0;
                    $transaction = $this->generalModel->saveTransaction([$this->model], $installments, ['Product Sale', 'edit']);
                }
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('payment', [
                    'model' => $this->model,
                    'paymentCycle' => $appCycleModel->dcsPaymentCycle($this->model->dcs_code)
        ]);
    }

    public function actionSaleInstallments($id) {
        $searchModel = new TblSaleInstallmentsSearch();
        $searchModel->product_sale_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_installment_grid', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

//    public function actionSkipInstallment($id)
//    {
//        if (($model = TblSaleInstallments::find()->where(['installment_code'=>$id])->one()) !== null) {
//            $cycleModel= new TblDcsPaymentCycle();
//            $max= TblSaleInstallments::find()->select(['max(installment_code) as max'])->where(['sale_code'=>$model->sale_code,'is_active'=>1])->one();
//            $lastCycle=TblSaleInstallments::findOne(['installment_code'=>$max->max]);
//            $cycle=$cycleModel->getNextCycleCode($lastCycle->dcs_payment_cycle_code, $model->dcs_code);
//            if($cycle==0)
//            {
//                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
//                    'message' => 'Can not skip installment because no new payment cycle available.']);
//                return $this->redirect(['sale-installments','id'=>$model->sale_code]);
//            }
//            else {
//                $installmentModel=new TblSaleInstallments();
//                $installmentModel->installment_code=Yii::$app->general->getCodeAutoIncrement($installmentModel);
//                $installmentModel->sale_type=$model->sale_type;
//                $installmentModel->sale_code=$model->sale_code;
//                $installmentModel->member_code=$model->member_code;
//                $installmentModel->dcs_code=$model->dcs_code;
//                $installmentModel->union_code=$model->union_code;
//                $installmentModel->main_amount=$model->main_amount;
//                $installmentModel->installment_amount=$model->installment_amount;
//                $installmentModel->installment_status=0;
//                $installmentModel->is_active=1;
//                $installmentModel->dcs_payment_cycle_code=$cycle;
//                $installmentModel->payment_cycle_applicabilty_code=$appCycleModel->dcsPaymentCycleAppCode($this->model->dcs_code);
//                $model->is_active=0;
//                $transaction = $this->generalModel->saveTransaction([$model,$installmentModel], ['skip installment', 'edit']);
//                if ($transaction !== FALSE) {
//                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
//                    'message' => 'Skipped and moved installment to new payment cycle.']);
//                    return $this->redirect(['sale-installments','id'=>$model->sale_code]);
//                }
//            }
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//        
//
//        
//    }

    /**
     * Updates an existing TblProductSale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->product_sale_code]);
        } else {
            
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblProductSale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $deleteModel = [];
        $saveModel = [];

        $historyModel = new TblProductSaleHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $details = TblProductSaleTransaction::find()->where(['product_sale_code' => $this->model->product_sale_code])->all();
        foreach ($details as $key => $id) {
            $detailHistory = new TblProductSaleTransactionHistory();
            Yii::$app->operation->history($id, $detailHistory, DELETE);
            $deleteModel[] = $details[$key];
            $saveModel[] = $detailHistory;
        }
        $InstallmentModel = TblSaleInstallments::find()->where(['product_sale_code' => $this->model->product_sale_code])->all();
        foreach ($InstallmentModel as $key => $id) {
            $deleteModel[] = $InstallmentModel[$key];
        }
        $taxmodel = TblProductSaleTaxCalculated::find()->where(['product_sale_code' => $this->model->product_sale_code])->all();
        foreach ($taxmodel as $key => $id) {
            $taxmodelHistory = new TblProductSaleTaxCalculatedHistory();
            Yii::$app->operation->history($id, $taxmodelHistory, DELETE);
            $deleteModel[] = $taxmodel[$key];
            $saveModel[] = $taxmodelHistory;
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Product Sale', 'edit']);

        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblProductSale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductSale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductSale::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetSociety() {
        if (!empty($_POST['union_code'])) {
            $union = TblUnions::findOne($_POST['union_code']);
            $dcs = $union->tblDcs;
            $arr = ArrayHelper::map($dcs, 'dcs_code', 'dcs_name');
            $record = ['status' => 'success', 'data' => $arr];
        } else {
            $record = ['status' => 'error', 'msg' => 'Can not load society'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
        //json_encode($record);
    }

    public function actionGetMember() {
        if (!empty($_POST['society'])) {
            $member = TblMember::findAll(['dcs_code' => $_POST['society'], 'is_active' => 1]);
            $arr = ArrayHelper::map($member, 'member_code', 'member_name');
            $record = ['status' => 'success', 'data' => $arr];
        } else {
            $record = ['status' => 'error', 'msg' => 'Can not load society'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
        //json_encode($record);
    }

    public function actionCreateProductSale() {
        $model = new TblProductSale();
        $model->scenario = 'saleProduct';
        $detailModel = new TblProductSaleTransaction();
        $detailModel->scenario = 'saleProduct';
        $searchModel = new TblProductSaleSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->searchSaleDetails(Yii::$app->request->get());
        $message = 'Product Sale';
        if (Yii::$app->request->post()) {
            return $this->createProductSaleData($model, $detailModel, $message);
        } else {
            return $this->render('_create_product_sale', [
                        'model' => $model,
                        'detailModel' => $detailModel,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'type' => 'vendorWiseSale',
                        'message' => $message
            ]);
        }
    }

    public function actionLoadRate() {
        $app = ['rate' => '', 'sale_rate' => '', 'product_sale_rate_applicability_code' => '', 'unit_code' => ''];
        if (!empty($_POST['product_code']) && !empty($_POST['customer_type']) && !empty($_POST['customer_code'])) {
            $date = !empty($_POST['invoice_date']) ? date('Y-m-d', strtotime($_POST['invoice_date'])) : date('Y-m-d');

            $appQuery = TblProductSaleRateApplicability::find()->innerJoinWith(['productRateCode', 'productCode'])
                    ->select(['product_sale_rate_applicability_code', 'tbl_product.unit_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date as dt'])->groupBy(['product_sale_rate_applicability_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date', 'tbl_product.unit_code'])
                    ->having(['<=', '[tbl_product_sale_rate_applicability].[wef_date]', $date])
                    ->where(['tbl_product_sale_rate.product_code' => $_POST['product_code'], 'tbl_product_sale_rate_applicability.applicable_for' => $_POST['customer_type'], 'tbl_product_sale_rate_applicability.is_member_rate' => (int) $_POST['is_member_rate'], 'tbl_product_sale_rate_applicability.applicable_code' => $_POST['customer_code']]);
            $app = $appQuery->orderBy(['tbl_product_sale_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
            if (!empty($app)) {
                $app = ['product_sale_rate_applicability_code' => $app['product_sale_rate_applicability_code'], 'sale_rate' => $app['sale_rate'], 'unit_code' => $app['unit_code']];
            }
        }
        echo json_encode($app);
    }

    public function actionListGrid() {
        $searchModel = new TblProductSaleSearch();
        $searchModel->grid_filter = false;
        $searchModel->setAttributes(Yii::$app->request->get('TblProductSale'));
        $dataProvider = $searchModel->searchSaleDetails(Yii::$app->request->get());

        return $this->renderAjax('_list_grid', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetCalculation() {
        $tax = $_POST['tax'];
        $value = $_POST['amount'];
        $flag = isset($_POST['flag']) ? true : false;
        $incTax = (isset($_POST['incTax']) && (!empty($_POST['incTax']) || $_POST['incTax'] == 0)) ? $_POST['incTax'] : '';
        $incTax = ($incTax == true || $incTax == 1) ? 1 : (($incTax == false || $incTax == 0) ? 0 : $incTax);
        $model = new TblTaxDetail();
        $data = $model->getDetail($tax);
        $dependModel = new TblTaxDepends();
        $records = [];
        $calculation = null;
        $configModel = new TblDcsGeneralConfig();
        $configModel->union_code = $_POST['unionCode'];
        $configModelData = $configModel->getData();

        $total = 0;
        $rateWithTax = 0;
        $per = 0;
        foreach ($data as $key => $d) {
            if ($incTax != '') {
                $rateWithTax = $incTax;
            } else {
                $rateWithTax = !empty($configModelData->sale_rate_with_tax) ? $configModelData->sale_rate_with_tax : 0;
            }
            $records[$key]['tax_code'] = $d->basic_tax_code;
            $records[$key]['tax_name'] = Yii::$app->general->getforeignkey($d->basicTaxCode, 'basic_tax_name');
            $records[$key]['tax_val'] = $d->percentage;
            $records[$key]['operation'] = ($d->type == 0) ? 'Addition' : 'Substraction';

            if ($key == 0) {
                $calculation = $value * $d->percentage / 100;
            } else {
                $sum = 0;
                $depend_data = $dependModel->getDepends($d->tax_detail_code);
                foreach ($depend_data as $depend) {
                    $sum += $value;
                }
                $calculation = $sum * $d->percentage / 100;
            }
            $records[$key]['amount'] = $calculation;
            if ($d->type == 0) {
                if ($d->percentage != 100) {
                    $per += $d->percentage;
                }
                $total += $records[$key]['amount'];
            } else {
                if ($d->percentage != 100) {
                    $per -= $d->percentage;
                }
                $total -= $records[$key]['amount'];
            }
        }
        if ($rateWithTax == 1 && !$flag) {
            $total = ($value * 100) / ($per + 100);
            $value = $total;
        }
        return Json::encode(['status' => 'success', 'rateWithTax' => $rateWithTax, 'changedAmount' => (float) $value, 'total' => $total, 'data' => $records]);
    }

    public function actionValidateCustomer() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $bmc = Yii::$app->request->post('bmc_code');
        $customer_code = Yii::$app->request->post('customer_code');
        $dcs_code = Yii::$app->request->post('dcsCode');
        $union = Yii::$app->request->post('union_code');
        $type = Yii::$app->request->post('customer_type');
        $mcc = Yii::$app->request->post('mcc');
        $plant = Yii::$app->request->post('plant');
        $date = Yii::$app->request->post('date');
        $headModel = new TblProductSale();
        $headModel->union_code = $union;
        $headModel->customer_type = $type;
        $headModel->plant_code = $plant;
        $headModel->mcc_plant_code = $mcc;
        $headModel->bmc_code = $bmc;
        $headModel->invoice_date = $date;
        if (!empty($type) && strtolower($type) == 'member') {
            $model = new TblMember();
            $memberCode = str_pad($customer_code, 4, '0', STR_PAD_LEFT);
            $data = $model->validateMember($dcs_code, $memberCode);
            $headModel->dcs_code = $dcs_code;
            $headModel->customer_code = !empty($data) ? $data->member_code : '';
            if (!empty($data)) {
                $detail = Yii::$app->general->validateDeactivateDcs($headModel, $headModel->invoice_date, '', TRUE, $headModel->customer_code);
                if ($detail === false) {
                    $data = '';
                }
            }
        } else if (!empty($type) && strtolower($type) != 'dcs') {
            $headModel->customer_code = $customer_code;
            $data = Yii::$app->general->validateCustomerCode($headModel);
            $headModel->customer_code = $data;
        } else {
            $model = new TblDcs();
            $data = $model->validDcs($customer_code, $bmc);
            $headModel->dcs_code = $data;
            $detail = Yii::$app->general->validateDeactivateDcs($headModel, $headModel->invoice_date);
            if ($detail === false) {
                $data = '';
            }
            $headModel->customer_code = $data;
        }
        if (!empty($data)) {
            $name = Yii::$app->general->getCustomer($headModel, $type);
            $response['status'] = 'success';
            $response['data'] = $name;
            $response['code'] = $headModel->customer_code;
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionCreateProductSaleToMember() {
        $model = new TblProductSale();
        $model->scenario = 'saleProduct';
        $model->customer_type = 'Member';
        $detailModel = new TblProductSaleTransaction();
        $detailModel->scenario = 'saleProduct';
        $searchModel = new TblProductSaleSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->searchSaleDetails(Yii::$app->request->get());
        $message = Yii::t('app', 'Product Sale to Member');
        if (Yii::$app->request->post()) {
            return $this->createProductSaleData($model, $detailModel, $message);
        } else {
            return $this->render('_create_product_sale', [
                        'model' => $model,
                        'detailModel' => $detailModel,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'type' => 'memberWiseSale',
                        'message' => $message
            ]);
        }
    }

    public function createProductSaleData($model, $detailModel, $message = 'Product Sale') {
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $detailModel->load(Yii::$app->request->post());
            $model->product_sale_code = Yii::$app->general->getUuid();
            $detailModel->product_sale_code = $model->product_sale_code;
            $detailModel->union_code = $model->union_code;
            $model->product_code = $detailModel->product_code;
//            $model->sale_type = 'DCS';
            $saleDate = date('Y-m-d', strtotime($model->invoice_date));
            $model->invoice_date = $saleDate;
            if ($model->validate() && $detailModel->validate()) {
                $master = [];
                $child = [];
//                $appCycleAppModel = new TblPaymentCycleApplicability();
//                $appCycleAppModel->applicable_type = $model->customer_type;
//                $appCycleAppModel->applicable_code = $model->bmc_code;
//                $appCycleAppModel->applicable_for = 'BMC';
//                $appCycleAppModelData = $appCycleAppModel->getApplicablePaymentCycle($saleDate);
                $model->other_amount = 0;
                $model->paid_amount = $model->payment_mode == 1 ? 0 : $model->amount_due;
                $model->is_installment = $model->payment_mode == 1 ? 1 : 0;
                $model->no_of_installment = $model->payment_mode == 1 ? $model->no_of_installment : 0;
                $detailModel->product_sale_transaction_code = Yii::$app->general->getTransactionCode($detailModel, $detailModel->product_sale_code);
                $detailModel->amount = $model->amount;
                $detailModel->discount = $model->discount;
                $master[] = $model;
                $child[] = $detailModel;
                if (!empty($model->payment_mode)) {
                    $no = !empty($model->no_of_installment) ? ($model->no_of_installment) : 1;
                    $cycle = NULL; //$appCycleAppModelData->payment_cycle_code;
                    $appCode = NULL; //$appCycleAppModelData->payment_cycle_applicabilty_code;
                    $instAmount = floatval($model->amount_due / $no);
                    $ai = 1;
                    for ($i = 0; $i < $no; $i++) {
//                        if (empty($cycle)) {
//                            $msg = Yii::t('app/validation', 'Payment Cycle Applicability is not available For Future Installment.');
//                            $record = ['msg' => $msg];
//                            return Json::encode($record);
//                        } else {
                        $installmentModel = new TblSaleInstallments();
//                            $installmentModel->sale_type = 'product';
                        $installmentModel->product_sale_code = $model->product_sale_code;
//                            $installmentModel->member_code = $model->member_code;
                        $installmentModel->customer_code = $model->customer_code;
                        $installmentModel->customer_type = $model->customer_type;
                        $installmentModel->dcs_code = $model->dcs_code;
                        $installmentModel->union_code = $model->union_code;
                        $installmentModel->plant_code = $model->plant_code;
                        $installmentModel->mcc_plant_code = $model->mcc_plant_code;
                        $installmentModel->bmc_code = $model->bmc_code;
                        $installmentModel->main_amount = $model->amount_due;
                        $installmentModel->installment_amount = $instAmount;
                        $installmentModel->installment_status = 0;
//                            $installmentModel->is_active = 1;
                        $installmentModel->payment_cycle_applicability_code = NULL; //$appCode;
                        $installmentModel->payment_cycle_code = NULL; //$cycle;
                        $installmentModel->product_sale_installment_code = Yii::$app->general->getTransactionCode($installmentModel, $model->product_sale_code, $ai);
//                            $paymentCycleDate = Yii::$app->general->getforeignkey($installmentModel->tblPaymentCycleCode, 'from_date');
//                            $paymentCycleDate = !empty($paymentCycleDate) && $paymentCycleDate != 'N/A' ? date('Y-m-d', strtotime($paymentCycleDate)) : NULL;
                        $installmentModel->installment_date = NULL; //$paymentCycleDate;
                        $child[] = $installmentModel;
//                            $appCycleAppModel = new TblPaymentCycle();
//                            $cycle = $appCycleAppModel->getNextCycleCode($installmentModel->payment_cycle_code, $model->bmc_code, $model->customer_type, 'BMC', $appCode);
                        $ai++;
//                        }
                    }
                }

                $discount_val = !empty($detailModel->discount) ? $detailModel->discount : 0;
                $totalAmount = 0;
                $totalAmount = $totalAmount + $detailModel->amount - $discount_val;
                $credit = $model->amount_due;
                $configModel = new TblDcsGeneralConfig();
                $configModel->union_code = $model->union_code;
                $configModelData = $configModel->getData();
                $rateWithTax = !empty($configModelData->sale_rate_with_tax) ? $configModelData->sale_rate_with_tax : 0;
                $taxCode = $detailModel->tax_code;
                $totalAmt = !empty($model->amount_due) ? $model->amount_due : 0;
                $discount = !empty($detailModel->discount) ? $detailModel->discount : 0;
                $quantity = !empty($detailModel->quantity) ? $detailModel->quantity : 1;
                $amount = !empty($detailModel->x_col1) ? $detailModel->x_col1 : $detailModel->rate;
                $taxModel = new TblTaxDetail();
                $taxdata = $taxModel->getDetail($taxCode);
                $j = 1;
                if (!empty($taxdata) && $detailModel->tax_amount > 0) {
                    foreach ($taxdata as $key => $d) {
                        if ($d->percentage != 100) {
                            $disc = 0;
                            $percent = 0;
                            $count = 0;
                            foreach ($taxdata as $keys => $per) {
                                if ($per->percentage != 100) {
                                    $percent += $per->percentage;
                                    $count++;
                                }
                            }
                            if ($rateWithTax == 1 || $rateWithTax == true) {
                                $val = ($amount * 100) / ($percent + 100);
                                $val = $amount - $val;
                                $disc = $discount * $percent / 100;
                            } else {
                                $val = $amount * $percent / 100;
                                $disc = $discount * $percent / 100;
                            }
                            $val = $val / $count;
                            $disc = $disc / $count;
                            $val = $val * $detailModel->quantity;
                            $val = $val - $disc;
                            $val = round($val, 2);
                            $totalAmount = $totalAmount + $val;
                            $taxModel = new TblProductSaleTaxCalculated();
                            $taxModel->product_sale_code = $detailModel->product_sale_code;
                            $taxModel->product_sale_transaction_code = $detailModel->product_sale_transaction_code;
                            $taxModel->tax_detail_code = $d->tax_detail_code;
                            $taxModelData = $taxModel->getRecords();
                            if (!empty($taxModelData)) {
                                $taxModel = $taxModelData;
                                $historyModel = new TblProductSaleTaxCalculatedHistory();
                                Yii::$app->operation->history($taxModel, $historyModel, UPDATE);
                                $child[] = $historyModel;
                            } else {
                                $taxModel->product_sale_tax_calculated_code = Yii::$app->general->getTransactionCode($taxModel, $taxModel->product_sale_code, $j);
                                $j++;
                            }
                            $taxModel->value = $val;
                            $child[] = $taxModel;
                        }
                    }
                }
                if ($model->validate()) {
                    $fstockModel = new TblProductStock();
                    $sale_type = strtoupper($model->customer_type) == 'MEMBER' ? 'DCS' : 'BMC';
                    $sale_code = strtoupper($model->customer_type) == 'MEMBER' ? $model->dcs_code : $model->bmc_code;
                    $fstockModel->setCodes($sale_type, $sale_code);
                    $fstockModel->product_code = $detailModel->product_code;
                    $fstockModel->union_code = $model->union_code;
                    $txn_type = strtoupper($model->customer_type) == 'MEMBER' ? 'PRODUCT SALE TO MEMBER' : 'PRODUCT SALE';
                    $existfromStock = $fstockModel->getExistStock($sale_type);

                    $f_stock = 0;
                    $qty = $detailModel->quantity;
                    if (!empty($existfromStock)) {
                        $historyModel = new TblProductStockHistory();
                        Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
                        $child[] = $historyModel;
                        $f_stock = $existfromStock->stock;
                        $existfromStock->stock = $f_stock - $qty;
                        $fstockModel = $existfromStock;
                        $child[] = $fstockModel;

                        $i = 1;
                        $fstockTxnModel = new TblProductStockTransaction();
                        $fstockTxnModel->attributes = $fstockModel->attributes;
                        $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($i);
                        $fstockTxnModel->old_value = $f_stock;
                        $fstockTxnModel->new_value = $qty;
                        $fstockTxnModel->final_value = $fstockModel->stock;
                        $fstockTxnModel->transaction_type = $txn_type;
                        $fstockTxnModel->transaction_date = date('Y-m-d');
                        $fstockTxnModel->reference_code = $detailModel->product_sale_transaction_code;
                        $child[] = $fstockTxnModel;

                        $i++;
                    }

                    $transaction = $this->generalModel->saveTransaction($master, $child, ['Product Sale', 'create']);
                    if ($transaction == 'customRedirect') {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'msg' => $msg];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg];
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode(array_merge(ActiveForm::validate($model), ActiveForm::validate($detailModel)));
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(array_merge(ActiveForm::validate($model), ActiveForm::validate($detailModel)));
            }
        }
    }

    public function actionGetAvailableStock() {
        $product = Yii::$app->request->post('product');
        $from_type = Yii::$app->request->post('type');
        $from_code = Yii::$app->request->post('code');
        $union_code = Yii::$app->request->post('union_code');
        $sap_batch_no = Yii::$app->request->post('sap_batch_no');
        $sale_type = strtoupper($from_type) == 'MEMBER' ? 'DCS' : 'BMC';

        $stockModel = new TblProductStock();
        $stockModel->setCodes(strtoupper($sale_type), $from_code);
        $stockModel->product_code = $product;
        $stockModel->union_code = $union_code;
        $existtoStock = $stockModel->getExistStock($sale_type, $sap_batch_no);
        if (!empty($existtoStock->stock)) {
            return Json::encode(['status' => 'success', 'stock' => $existtoStock->stock]);
        } else {
            return Json::encode(['status' => 'success', 'stock' => 0]);
        }
    }

    public function actionSetAvailableCredit() {
        $type = Yii::$app->request->post('type');
        $code = Yii::$app->request->post('code');
        $union = Yii::$app->request->post('union');
        $bmc = Yii::$app->request->post('bmc');
        $date = Yii::$app->request->post('date');
        $pay_mode = Yii::$app->request->post('pay_mode');
        $amount_due = Yii::$app->request->post('amount_due');
        $no_of_installment = Yii::$app->request->post('noi');
        if (!empty($date) && $pay_mode == 1) {

            $model = new TblPaymentCycleApplicability();
            $model->applicable_type = strtolower($type) == 'member' ? 'DCS' : $type;
            $model->applicable_code = $bmc;
            $model->applicable_for = 'BMC';
            $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($date)));
            if (!empty($modelData)) {

                $fromDate = date('Y-m-d', strtotime($modelData->from_date));
                $toDate = date('Y-m-d', strtotime($modelData->to_date));
                $saledAmount = 0;
                $where = [];
                $where = ['payment_mode' => $pay_mode, 'customer_type' => $type, 'customer_code' => $code];
                $model = new TblProductSale();
                $data = $model->find()
                        ->select(['amount_due' => 'ISNULL(SUM(ISNULL(amount_due, 0)),0)'])
                        ->where(['between', 'cast(invoice_date as date)', $fromDate, $toDate])
                        ->andWhere($where)
                        ->one();
                if (!empty($data->amount_due)) {
                    $saledAmount = $data->amount_due;
                }
                $model = new TblBmcCollection();
                $collWhere = [];
                $collWhere = ['customer_type' => $type, 'customer_code' => $code];
                if (strtolower($type) == 'member') {
                    $model = new TblMilkCollection();
                    $collWhere = ['member_code' => $code];
                }

                $modelData = $model->find()
                        ->select(['amount' => 'ISNULL(SUM(ISNULL(amount, 0)), 0)'])
                        ->where(['between', 'date_time_of_collection', $modelData->from_date, $modelData->to_date])
                        ->andWhere($collWhere)
                        ->one();
                $creditAmount = 0;
                if (!empty($modelData->amount)) {
                    $creditAmount = $modelData->amount;
                }
                $availableCredit = $creditAmount - $saledAmount;
                return Json::encode(['status' => 'success', 'credit' => $availableCredit]);
            } else {
                return Json::encode(['status' => 'error', 'credit' => 0]);
            }
        }
    }

    public function actionCreateProductSaleCash() {
        $model = new TblProductSale();
        $model->scenario = 'saleProduct';
        $detailModel = new TblProductSaleTransaction();
        $detailModel->scenario = 'saleProduct';
        $searchModel = new TblProductSaleSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->searchSaleDetails(Yii::$app->request->get());
        $message = 'Product Sale';
        if (Yii::$app->request->post()) {
            return $this->createProductSaleData($model, $detailModel, $message);
        } else {
            return $this->render('_create_product_sale', [
                        'model' => $model,
                        'detailModel' => $detailModel,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'type' => 'vendorWiseSale',
                        'message' => $message,
                        'cashSale' => TRUE,
            ]);
        }
    }

}
