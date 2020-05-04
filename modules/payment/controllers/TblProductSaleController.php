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
use app\modules\payment\models\TblProductSaleDetailsSearch;
use app\modules\payment\models\TblProductSaleDetails;
use app\modules\payment\models\TblMemberCreditLimit;
use app\modules\payment\models\TblMemberCreditLimitHistory;
use app\modules\payment\models\TblMemberCreditLimitTransaction;
use app\modules\product\models\TblProductRateApplicability;
use app\modules\product\models\TblProductRate;
use yii\widgets\ActiveForm;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycle;

/**
 * TblProductSaleController implements the CRUD actions for TblProductSale model.
 */
class TblProductSaleController extends \app\controllers\ChildController {

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
        $searchModel = new TblProductSaleDetailsSearch();
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
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        $detailModel = new TblProductSaleDetails();
        $detailModel->scenario = 'saleProduct';
        $searchModel = new TblProductSaleSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->searchSaleDetails(Yii::$app->request->get());
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $detailModel->load(Yii::$app->request->post());
            $model->product_sale_code = Yii::$app->general->getPrimaryCode($model);
            $detailModel->product_sale_code = $model->product_sale_code;
//            $model->sale_type = 'DCS';
            $saleDate = date('Y-m-d', strtotime($model->invoice_date));
            $model->invoice_date = $saleDate;
            if ($model->validate() && $detailModel->validate()) {
                $master = [];
                $child = [];
                $appCycleAppModel = new TblPaymentCycleApplicability();
                $appCycleAppModel->applicable_type = $model->customer_type;
                $appCycleAppModel->applicable_code = $model->bmc_code;
                $appCycleAppModel->applicable_for = 'BMC';
                $appCycleAppModelData = $appCycleAppModel->getApplicablePaymentCycle($saleDate);
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
                    $cycle = $appCycleAppModelData->payment_cycle_code;
                    $appCode = $appCycleAppModelData->payment_cycle_applicabilty_code;
                    $instAmount = floatval($model->amount_due / $no);
                    $ai = 1;
                    for ($i = 0; $i < $no; $i++) {
                        if (empty($cycle)) {
                            $msg = Yii::t('app/validation', 'Payment Cycle Applicability is not available For Future Installment.');
                            $record = ['msg' => $msg];
                            return Json::encode($record);
                        } else {
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
                            $installmentModel->payment_cycle_applicability_code = $appCode;
                            $installmentModel->payment_cycle_code = $cycle;
                            $installmentModel->product_sale_installment_code = Yii::$app->general->getTransactionCode($installmentModel, $model->product_sale_code, $ai);
                            $paymentCycleDate = Yii::$app->general->getforeignkey($installmentModel->tblPaymentCycleCode, 'from_date');
                            $paymentCycleDate = !empty($paymentCycleDate) && $paymentCycleDate != 'N/A' ? date('Y-m-d', strtotime($paymentCycleDate)) : NULL;
                            $installmentModel->installment_date = $paymentCycleDate;
                            $child[] = $installmentModel;
                            $appCycleAppModel = new TblPaymentCycle();
                            $cycle = $appCycleAppModel->getNextCycleCode($installmentModel->payment_cycle_code, $model->bmc_code, $model->customer_type, 'BMC', $appCode);
                            $ai++;
                        }
                    }
                }
                if ($model->validate()) {
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
        return $this->render('_create_product_sale', [
                    'model' => $model,
                    'detailModel' => $detailModel,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoadRate() {
        $app = ['rate' => ''];
        if (!empty($_POST['product_code']) && !empty($_POST['customer_type']) && !empty($_POST['customer_code'])) {
            $date = !empty($_POST['invoice_date']) ? date('Y-m-d', strtotime($_POST['invoice_date'])) : date('Y-m-d');

            $appQuery = TblProductRateApplicability::find()->innerJoinWith('productRateCode')
                    ->select(['product_sale_rate_applicability_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date as dt'])->groupBy(['product_sale_rate_applicability_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date'])
                    ->having(['<=', '[tbl_product_sale_rate_applicability].[wef_date]', $date])
                    ->where(['tbl_product_sale_rate.product_code' => $_POST['product_code'], 'tbl_product_sale_rate_applicability.applicable_for' => $_POST['customer_type'], 'tbl_product_sale_rate_applicability.is_member_rate' => (int) $_POST['is_member_rate'], 'tbl_product_sale_rate_applicability.applicable_code' => $_POST['customer_code']]);
            $app = $appQuery->orderBy(['tbl_product_sale_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
            if (!empty($app)) {
                $app = ['product_sale_rate_applicability_code' => $app['product_sale_rate_applicability_code'], 'sale_rate' => $app['sale_rate']];
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

}
