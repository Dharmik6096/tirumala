<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblVspPayment;
use app\modules\payment\models\TblVspPaymentSearch;
use app\modules\payment\models\TblPaymentCycleApplicability;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPaymentTransactionSearch;
use app\modules\payment\models\TblVspPaymentHistory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\modules\payment\models\TblPaymentTransaction;
use app\modules\payment\models\TblUnionBankPayment;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\vsp\models\TblBillHead;
use app\modules\vsp\models\TblBillHeadDetail;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblMilkCollection;
use app\modules\payment\models\TblVspPaymentTransaction;
use app\modules\vsp\models\TblMemberPaymentAllow;
use app\modules\payment\models\TblVspOutstanding;
use app\modules\payment\models\TblVspOutstandingHistory;
use yii\helpers\Url;
use app\modules\payment\models\TblVspPaymentRecovery;
use app\modules\payment\models\TblSaleInstallmentsSearch;
use app\modules\payment\models\TblSaleInstallments;
use app\modules\payment\models\TblProductSaleInstallmentHistory;
use app\modules\vsp\models\TblBillHeadInstallment;
use app\modules\vsp\models\TblBillHeadInstallmentHistory;
use app\modules\payment\models\TblPaymentStop;
use app\modules\payment\models\TblPaymentStopHistory;
use app\modules\payment\models\TblRemunerationSummary;
use yii\widgets\ActiveForm;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * TblVspPaymentController implements the CRUD actions for TblVspPayment model.
 */
class TblVspPaymentController extends \app\controllers\ChildController {

    public $freeAccessActions = ['check-mcc-type', 'process-payment'];

    /**
     * Creates a new TblVspPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVspPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $searchModel = new TblVspPaymentTransactionSearch();
        $searchModel->vsp_payment_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new TblVspPayment();
        $customer = new TblCustomerMaster();
        $types_title = '';
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'processpayment';
            if ($model->validate()) {
                if (empty($model->customer_type) && !empty($model->bmc_code)) {
                    $data = $customer->customerType($model->bmc_code);
                    $model->customer_type = array_keys($data);
                    $model->types_title = 'All (' . implode('/', array_values($data)) . ') ';
                }
                $result = 'success';
                $queryParam = [];
                $queryParam[] = 'process-payment';
                $queryParamRegenerate = [];
                $queryParam['TblVspPayment'] = ['plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code,
                    'union_code' => $model->union_code, 'customer_type' => $model->customer_type, 'payment_cycle_code' => $model->payment_cycle_code, 'stop_payment_only' => 0, 'types_title' => $model->types_title];
                $queryParamRegenerate = $queryParam;
                $queryParamRegenerate['reGenerate'] = 0;
                $msg = '';
                $disburseData = $model->getStatusCount(['sent']);
                $lockedData = $model->getStatusCount(['locked']);
                $generatedData = $model->getStatusCount(['generated', 'processed']);
                $stopModel = new TblPaymentStop();
                $stopModel->bmc_code = $model->bmc_code;
                $stopModel->customer_type = 'DCS';
                $stopModel->payment_type = 'VENDOR_PAYMENT';
                $stopMsg = $stopModel->getStatusStop();
                if ($disburseData > 0) {
                    $result = 'displayPopup';
                    $msg = Yii::t('app', 'Payment is Disbursed for Selected Payment Cycle');
                } else if ($lockedData > 0) {
                    $result = 'displayPopup';
                    $msg = Yii::t('app', 'Payment is Locked for Selected Payment Cycle');
                } else if (!empty($stopMsg)) {
                    $result = 'displayPopup';
                    $msg = Yii::t('app', $stopMsg);
                } else if ($generatedData > 0) {
                    $result = 'displayConfirmPopup';
                    $msg = Yii::t('app', 'Payment is already generated for Selected Payment Cycle. Do You want to Regenerate?');
                    $queryParamRegenerate['reGenerate'] = 1;
                } else if ($generatedData == 0) {
                    $queryParam['reGenerate'] = 1;
                }
                $url = Url::to($queryParam);
                $url_regenerate = Url::to($queryParamRegenerate);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return ['status' => $result, 'url' => $url, 'url_regenerate' => $url_regenerate, 'msg' => $msg];
            } else {
                $form_validation = ActiveForm::validate($model);
                $model->bmc_code = is_array($model->bmc_code) ? NULL : $model->bmc_code;
                $model->scenario = 'default';
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $form_validation;
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionCreateStopPayment() {
        $model = new TblVspPayment();
        $multiple_bmc = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_multiselect_in_payment', 'PORTAL') == '1' ? TRUE : FALSE;
        if ($model->load(Yii::$app->request->post())) {
            $bmc_array = [];
            $bmc_array = $model->bmc_code;
            $model->scenario = 'processpayment';
            if ($model->validate()) {
                $model->stop_payment_only = 1;
                $stopModel = new TblPaymentStop();
                $stopModel->bmc_code = $model->bmc_code;
                $stopModel->customer_type = 'DCS';
                $stopModel->payment_type = 'VENDOR_PAYMENT';
                $stopMsg = $stopModel->getStatusStop();
                if (!empty($stopMsg)) {
                    foreach ($bmc_array as $bmc) {
                        $model->bmc_code = $bmc;
                        $this->getVspSpData($model);
                    }
                    $model->bmc_code = $bmc_array;
                    return $this->redirect(['payment-adjust', 'TblVspPayment' => ['multiple_bmc' => $multiple_bmc, 'mcc_plant_code' => $model->mcc_plant_code, 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'customer_type' => $model->customer_type, 'union_code' => $model->union_code, 'types_title' => $model->types_title]]);
                } else {
                    $msg = Yii::t('app', 'Stop Payment data is not available');
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'message' => $msg,
                    ]);
                }
            }
            $model->scenario = 'default';
        }
        return $this->render('create', [
                    'model' => $model, 'post_url' => Url::to(['create-stop-payment']), 'title' => 'Vendor Stop Payment Process : Step 1'
        ]);
    }

    public function actionProcessPayment($reGenerate = 0) {
        $multiple_bmc = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_multiselect_in_payment', 'PORTAL') == '1' ? TRUE : FALSE;

        if (Yii::$app->request->get()) {
            $model = new TblVspPayment();
            $model->load(Yii::$app->request->get());
            if ($reGenerate == 1) {
                $bmc_array = [];
                $bmc_code = $model->bmc_code;

                $customer_array = [];
                $customer = $model->customer_type;

                if (is_array($model->bmc_code)) {
                    $bmc_array = $model->bmc_code;
                } else {
                    $bmc_array[] = $model->bmc_code;
                }

                if (is_array($model->customer_type)) {
                    $customer_array = $model->customer_type;
                } else {
                    $customer_array[0] = $model->customer_type;
                }

                foreach ($bmc_array as $bmc) {
                    foreach ($customer_array as $type) {
                        $model->customer_type = $type;
                        $model->bmc_code = $bmc;
                        $this->getVspSpData($model);
                    }
                }
                $model->bmc_code = $bmc_code;
                $model->customer_type = $customer;
            }
            return $this->redirect(['payment-adjust', 'TblVspPayment' => ['multiple_bmc' => $multiple_bmc, 'mcc_plant_code' => $model->mcc_plant_code, 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'customer_type' => $model->customer_type, 'union_code' => $model->union_code, 'types_title' => $model->types_title]]);
        }
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());
        if (Yii::$app->request->post()) {
            $bmc_array = [];
            $postData = Yii::$app->request->post();
            $processFlag = !empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'processed';
            $is_bank_integrated = Yii::$app->general->getUnionConfiguration($postData['union_code'], 'is_bank_integrated_vendor', 'PORTAL') == 1 ? true : false;
            if ($is_bank_integrated && $processFlag == 'locked') {
                $vspModel = new TblVspPayment();
                $vspModel->load($postData);
                $vspModel->scenario = 'finalize_payment';
                if (!$vspModel->validate()) {
                    $msg = implode("\n", array_map(function($error) {
                                return implode(", ", $error);
                            }, $vspModel->getErrors()));
                    Yii::$app->response->format = trim(Response::FORMAT_JSON);
                    return ['status' => 'error', 'msg' => $msg];
                }
            }
            $auto_adjust_stop_payment_vendor = isset(Yii::$app->session->get('unionConfig')[$postData['union_code']]['auto_adjust_stop_payment_vendor']) ? Yii::$app->session->get('unionConfig')[$postData['union_code']]['auto_adjust_stop_payment_vendor'] : 0;
            $stop_payment_customer = !empty($postData['selection']) ? $postData['selection'] : [];
            $stop_payment_reason = !empty($postData['TblVspPayment']) ? $postData['TblVspPayment'] : [];
            $types_title = !empty($postData['types_title']) ? $postData['types_title'] : '';
//        if (Yii::$app->request->post('TblVspPayment')) {
//            $postData = Yii::$app->request->post();
            $adjust_id = Yii::$app->request->post('TblVspPayment')['vsp_payment_code'];
            $adjust_amt = Yii::$app->request->post('TblVspPayment')['adjust_amount'];
            $adjust_remark = Yii::$app->request->post('TblVspPayment')['adjust_remark'];
            $hold_amt = Yii::$app->request->post('TblVspPayment')['hold_amount'];
            $save_model = [];
            $delete_model = [];
            $cnt = 0;
            $updated_applicability = [];
            foreach ($adjust_id as $key => $value) {
                $data = TblVspPayment::findOne($adjust_id[$key]);
                if ($data->billing_type == 'remuneration') {
                    $data->scenario = 'remuneration';
                }
                $oldData = $data->oldAttributes;
                $historyModel = new TblVspPaymentHistory();
                Yii::$app->operation->history($data, $historyModel, UPDATE);
                if (($adjust_amt[$key] != 0 && $adjust_amt[$key] != '') || ($hold_amt[$key] != 0 && $hold_amt[$key] != '')) {
                    $updateData = false;
                    $data->adjust_amount = $adjust_amt[$key];
                    $data->adjust_remark = $adjust_remark[$key];
                    $data->hold_amount = $hold_amt[$key];
                    $data->final_pay = (float) $data->net_payable + (float) $adjust_amt[$key] - (float) $hold_amt[$key];
                    $data->status = ($auto_adjust_stop_payment_vendor != '1' && in_array($data->customer_code, $stop_payment_customer)) ? 'processed' : $processFlag;
                    if (!empty($oldData) && ($oldData['status'] != $data->status || $oldData['hold_amount'] != $data->hold_amount || $oldData['adjust_amount'] != $data->adjust_amount || $oldData['adjust_remark'] != $data->adjust_remark)) {
                        $updateData = true;
                    }
                    if ($auto_adjust_stop_payment_vendor == '1' && $processFlag == 'locked' && in_array($data->customer_code, $stop_payment_customer)) {
                        $updateData = true;
                        $data->hold_amount = !empty($data->hold_amount) ? ((float) $data->hold_amount + (float) $data->final_pay) : $data->final_pay;
                        $data->final_pay = 0;
                        $data->adjust_remark .= !empty($stop_payment_reason[$data->customer_code]['stop_payment_type']) ? $stop_payment_reason[$data->customer_code]['stop_payment_type'] : 'dispute';
                        $data->adjust_remark .= ' Auto adjust with 0.';
                    }
                    if ($updateData) {
                        $save_model[] = $historyModel;
                        $save_model[] = $data;
                        $cnt++;
                    }
                } else {
                    $data->status = ($auto_adjust_stop_payment_vendor != '1' && in_array($data->customer_code, $stop_payment_customer)) ? 'processed' : $processFlag;
                    if (!empty($oldData) && ($oldData['status'] != $data->status)) {
                        if ($auto_adjust_stop_payment_vendor == '1' && $processFlag == 'locked' && in_array($data->customer_code, $stop_payment_customer)) {
                            $data->hold_amount = !empty($data->hold_amount) ? ((float) $data->hold_amount + (float) $data->final_pay) : $data->final_pay;
                            $data->final_pay = 0;
                            $data->adjust_remark .= !empty($stop_payment_reason[$data->customer_code]['stop_payment_type']) ? $stop_payment_reason[$data->customer_code]['stop_payment_type'] : 'dispute';
                            $data->adjust_remark .= ' Auto adjust with 0.';
                        }
                        $save_model[] = $historyModel;
                        $save_model[] = $data;
                    }
                }
                if ($processFlag == 'locked') {
                    $old_stop_all = TblPaymentStop::find()
                            ->where([
                                'from_datetime' => $data->from_datetime, 'to_datetime' => $data->to_datetime,
                                'customer_code' => $data->customer_code, 'customer_type' => $data->customer_type,
                                'payment_type' => 'VENDOR_PAYMENT', 'bmc_code' => $data->bmc_code])
                            ->andFilterWhere(['payment_cycle_code' => $data->payment_cycle_code])
                            ->all();
                    if (!empty($old_stop_all)) {
                        foreach ($old_stop_all as $old_stop) {
                            $historyModel = new TblPaymentStopHistory();
                            if ($auto_adjust_stop_payment_vendor != '1' && in_array($data->customer_code, $stop_payment_customer)) {
                                Yii::$app->operation->history($old_stop, $historyModel, UPDATE);
                                $old_stop->stop_reason = !empty($stop_payment_reason[$data->customer_code]['stop_payment_type']) ? $stop_payment_reason[$data->customer_code]['stop_payment_type'] : 'dispute';
                                $save_model[] = $old_stop;
                            } else {
                                Yii::$app->operation->history($old_stop, $historyModel, DELETE);
                                $historyModel->lock_datetime = date('Y-m-d H:i:s');
                                $delete_model[] = $old_stop;
                            }
                            $save_model[] = $historyModel;
                        }
                    } else {
                        if ($auto_adjust_stop_payment_vendor != '1' && in_array($data->customer_code, $stop_payment_customer)) {
                            $stop_pay = new TblPaymentStop();
                            $stop_pay->attributes = $data->attributes;
                            $stop_pay->payment_type = 'VENDOR_PAYMENT';
                            $stop_pay->stop_reason = !empty($stop_payment_reason[$data->customer_code]['stop_payment_type']) ? $stop_payment_reason[$data->customer_code]['stop_payment_type'] : 'dispute';
                            $stop_pay->originating_type = $stop_pay->originating_org_type = $stop_pay->originating_org_code = NULL;
                            $stop_pay->created_at = $stop_pay->created_by = $stop_pay->updated_at = $stop_pay->updated_by = NULL;
                            $save_model[] = $stop_pay;
                        }
                    }
                }
                $model->from_datetime = $data->from_datetime;
                $model->to_datetime = $data->to_datetime;
                $bmc_array[$data->bmc_code] = $data->bmc_code;
                $model->union_code = $data->union_code;
                $model->billing_type = $data->billing_type;
                $model->customer_type = $data->customer_type;
                if ($processFlag == 'locked') {
                    $unique_key = $data->payment_cycle_code . '_' . $data->bmc_code . '_' . $data->customer_type;
                    if (!isset($updated_applicability[$unique_key])) {
                        $applicabilityModel = $data->getPaymentCycleApplicabilityForLock($data->payment_cycle_code, $data->bmc_code, $data->customer_type);
                        if (!empty($applicabilityModel)) {
                            $save_model[] = $applicabilityModel;
                        }
                        $updated_applicability[$unique_key] = true;
                    }
                }
            }
            if ($model->billing_type == 'remuneration') {
                $PaymentApp = TblRemunerationSummary::find()
                        ->where(['from_datetime' => $model->from_datetime,
                            'to_datetime' => $model->to_datetime,
                            'bmc_code' => $bmc_array,
                            'union_code' => $model->union_code])
                        ->all();
                foreach ($PaymentApp as $dataApp) {
                    if ($dataApp->status != $processFlag) {
                        $dataApp->status = $processFlag;
                        $save_model[] = $dataApp;
                    }
                }
            }
            $customer_type = '';
            if (!empty($types_title)) {
                $customer_type .= $types_title;
            } else {
                $customer_type .= Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
            }
            $transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $delete_model, ['Payment of ' . $customer_type . ' ' . $processFlag . ' succesfully', 'info']);

            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            $msg = '';
            $url = Url::to(['index']);
            if ($transaction == 'customRedirect') {
                $result = 'success';
            } else {
                $result = 'error';
                $msgData = Yii::$app->session->getFlash('success');
                $msg = !empty($msg['message']) ? $msg['message'] : '';
            }
            return ['status' => $result, 'url' => $url, 'msg' => $msg];
        }

        if ($model->billing_type == 'remuneration') {
            $query = $model->getRemunerationRecords();
            $title = 'Remuneration Payment Process : Step 2';
        } else {
            $query = $model->getRecords();
            $title = 'Vendor Payment Process : Step 2';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        return $this->render('payment-adjust', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => $title,
        ]);
    }

    public function actionBillHead() {
        $searchModel = new TblVspPaymentTransactionSearch();
        $searchModel->vsp_payment_code = Yii::$app->request->get()['code'];
        $model = $this->findModel($searchModel->vsp_payment_code);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $instalSearch = new TblSaleInstallmentsSearch();
        $idataProvider = $instalSearch->vendorInstallment($model);
        if (Yii::$app->request->post()) {
            $paymentData = Yii::$app->request->post()['paymentData'];
            $new_product_inst = !empty($paymentData) ? explode(',', $paymentData) : [];
            $old_product_inst = array_filter(array_map(function ($a) {
                        return !empty($a->installment_date) ? $a->product_sale_installment_code : '';
                    }, $idataProvider->getModels()));
            $add_inst = array_diff($new_product_inst, $old_product_inst);
            $delete_inst = array_diff($old_product_inst, $new_product_inst);
            $process = FALSE;
            $saveModel = [];
            $deleteModel = [];
            $add_amt = 0;
            $sub_amt = 0;
            if (!empty($add_inst) || !empty($delete_inst)) {
                $process = TRUE;
                foreach ($add_inst as $id) {
                    $inst = TblSaleInstallments::findOne($id);
                    $historyModel = new TblProductSaleInstallmentHistory();
                    Yii::$app->operation->history($inst, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $inst->installment_date = date('Y-m-d', strtotime($model->from_datetime));
                    $saveModel[] = $inst;
                    $add_amt = $add_amt + $inst->installment_amount;
                }
                foreach ($delete_inst as $id) {
                    $inst = TblSaleInstallments::findOne($id);
                    $historyModel = new TblProductSaleInstallmentHistory();
                    Yii::$app->operation->history($inst, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $inst->installment_date = NULL;
                    $saveModel[] = $inst;
                    $sub_amt = $sub_amt + $inst->installment_amount;
                }

                $pro_sale_head = TblBillHead::find()->where(['default_bill_head_code' => 6, 'bill_head_for' => 'VENDOR', 'union_code' => $model->union_code])->one();
                $vsp_txn = TblVspPaymentTransaction::find()->where(['vsp_payment_code' => $model->vsp_payment_code, 'bill_head_code' => $pro_sale_head->bill_head_code])->one();
                if (empty($vsp_txn)) {
                    $vsp_txn = new TblVspPaymentTransaction();
                    $vsp_txn->bill_head_code = $pro_sale_head->bill_head_code;
                    $vsp_txn->vsp_payment_code = $model->vsp_payment_code;
                    $vsp_txn->bill_head_type = 1;
                    $vsp_txn->amount = 0;
                }
                $vsp_txn->amount = $vsp_txn->amount + $add_amt - $sub_amt;
                $is_txn_delete = (empty($add_inst) && $vsp_txn->amount == 0) ? TRUE : FALSE;
                if ($is_txn_delete) {
                    $deleteModel[] = $vsp_txn;
                } else {
                    $saveModel[] = $vsp_txn;
                }
            }
            $total_addition = 0;
            $total_deduction = 0;
            $head_detail = !empty(Yii::$app->request->post()['TblVspPaymentTransaction']) ? Yii::$app->request->post()['TblVspPaymentTransaction'] : [];
            if (!empty($head_detail)) {
                foreach ($head_detail as $head) {
                    if ($head['current_cycle'] != $head['payment_cycle_type']) {
                        $new_payment_cycle_type = explode('-', $head['payment_cycle_type'])[0];
                        $p_head = TblVspPaymentTransaction::findOne($head['tbl_vsp_payment_transaction_code']);
                        if (!empty($p_head)) {
                            $process = TRUE;
                            $from_date = date('Y-m-d', strtotime($model->from_datetime));
                            $bill_head_inst = TblBillHeadInstallment::find()->where(['installment_date' => $from_date, 'customer_type' => $model->customer_type, 'bill_head_for' => 'VENDOR', 'customer_code' => $model->customer_code, 'bill_head_code' => $p_head->bill_head_code])->all();
                            foreach ($bill_head_inst as $installment) {
                                $insthistoryModel = new TblBillHeadInstallmentHistory();
                                Yii::$app->operation->history($installment, $insthistoryModel, 'UPDATE');
                                $installment->installment_status = 2;
                                $installment->payment_cycle_type = $new_payment_cycle_type;
                                $saveModel[] = $insthistoryModel;
                                $saveModel[] = $installment;
                            }
                            if ($p_head->bill_head_type == 0) {
                                $total_addition = $total_addition + $p_head->amount;
                            } else {
                                $total_deduction = $total_deduction + $p_head->amount;
                            }
                            $deleteModel[] = $p_head;
                        }
                    }
                }
            }

            if ($process) {
                $model->deduction = $model->deduction + $add_amt - $sub_amt;
                $model->net_payable = $model->net_payable - $add_amt + $sub_amt;
                $model->final_pay = $model->final_pay - $add_amt + $sub_amt;

                $model->addition = $model->addition - $total_addition;
                $model->deduction = $model->deduction - $total_deduction;
                $model->net_payable = $model->net_payable + $total_deduction - $total_addition;
                $model->final_pay = $model->final_pay + $total_deduction - $total_addition;

                $saveModel[] = $model;
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Vendor Payment Head', 'edit']);
                if ($transaction == 'customRedirect') {
                    $record = ['status' => 'success', 'msg' => ''];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
            } else {
                $msg = Yii::t('app', 'No Changes found in data.');
                $record = ['status' => 'error', 'msg' => $msg];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
        return $this->renderAjax('bill-head-view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'instalSearch' => $instalSearch,
                    'idataProvider' => $idataProvider,
                    'model' => $model
        ]);
    }

    /* To bind societies based on selected payment cycle */

    public function actionListSociety() {
        $paymentcycleModel = new TblDcsPaymentCycleApplicability();
        $society_list = $paymentcycleModel->societyList(Yii::$app->request->post('payment_cycle'), false);
        $member_payment = new TblMemberPaymentAllow();
        $society_list['member_payment'] = $member_payment->getMemberPaymentDcs(Yii::$app->request->post('payment_cycle'));
        $society_list['member_payment_msg'] = '';
        if (!empty($society_list['member_payment'])) {
            $message = 'Please Process Member Payment of following Society.<br/>';
            $message .= implode('<br/>', array_values($society_list['member_payment']));
            $society_list['member_payment_msg'] = $message;
            $society_list['member_payment'] = array_keys($society_list['member_payment']);
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $society_list]);
    }

    /**
     * Finds the TblVspPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVspPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVspPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function getVspSpData($model) {
        $paymentCycle = $model->paymentCycleCode;
        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        $data = [];
        $data['union_code'] = $model->union_code;
        $data['bmc_code'] = $model->bmc_code;
        $data['customer_type'] = $model->customer_type;
        $data['payment_cycle_code'] = $model->payment_cycle_code;
        $data['from_datetime'] = date('Y-m-d H:i:s', strtotime($paymentCycle->from_date));
        $data['from_shift'] = $paymentCycle->from_shift;
        $data['to_datetime'] = date('Y-m-d H:i:s', strtotime($paymentCycle->to_date));
        $data['to_shift'] = $paymentCycle->to_shift;
        $data['process_stop_payment'] = $model->stop_payment_only;
        $data['user_code'] = $user;

        /* delete recovery data */
        Yii::$app->db->createCommand("delete from tbl_vsp_payment_recovery
where payment_cycle_code = :payment_cycle_code and bmc_code=:bmc_code and customer_type = :customer_type")
                ->bindValue(':payment_cycle_code', $model->payment_cycle_code)
                ->bindValue(':bmc_code', $model->bmc_code)
                ->bindValue(':customer_type', $model->customer_type)
                ->execute();
        /* delete recovery data */
        return Yii::$app->ClientPaymentConfig->processPayment('vsp_payment', $data);
    }

    public function actionPaymentDisburse() {
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());
        $customer = new TblCustomerMaster();
        $query = $model->find();
        $types_title = '';
        if (!$model->validate()) {
            $query = $query->where('0=1');
        } else {
            $query = $query->where(['payment_cycle_code' => $model->payment_cycle_code,
                'bmc_code' => $model->bmc_code,
                'status' => ['locked', 'rejected']]);
            if (empty($model->customer_type) && !empty($model->bmc_code)) {
                $data = $customer->customerType($model->bmc_code);
                // $model->customer_type = array_keys($data);
                $model->types_title = 'All (' . implode('/', array_values($data)) . ') ';
                $query = $query->andWhere(['customer_type' => array_keys($data)]);
            } else {
                $query = $query->andWhere(['customer_type' => $model->customer_type]);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'Vendor Payment Disburse : Step 1'
        ]);
    }

    public function actionConfirmPayment() {
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        if (Yii::$app->request->post()) {
            $model = new TblVspPayment();

            $model->load(Yii::$app->request->post());
            if (!empty(Yii::$app->request->post()['types_title'])) {
                $model->types_title = Yii::$app->request->post()['types_title'];
            }
            if (!empty($model->payment_cycle_code)) {
                if (Yii::$app->request->post('flag') == 'vsp') {

                    $query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code,
                        'bmc_code' => $model->bmc_code,
                        'status' => ['locked', 'rejected']]);
                    if (!empty($model->customer_type)) {
                        $query = $query->andWhere(['customer_type' => $model->customer_type]);
                    }
                    $query = $query->all();

                    $pay_cnt = count($query);
                    $pay_amount = array_sum(array_column($query, 'final_pay'));
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $query,
                        'pagination' => FALSE
                    ]);
                    $tot_cnt = count($query);
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'Out of (<b>' . $tot_cnt . '</b>) Vendor Payment of (<b>' . $pay_cnt . '</b>)  Vendor will be only done.<br/>Total Payable :: <b>' . $pay_amount . '</b>']);

                    $union_bank = [];
                    if (!empty($model->union_code)) {
                        $is_bank_integrated = Yii::$app->general->getUnionConfiguration($model->union_code, 'is_bank_integrated_vendor', 'PORTAL') == 1 ? true : false;
                        if ($is_bank_integrated) {
                            // $union_bank = TblUnionBankPayment::find()->select(['union_bank_payment_code', 'bank_name'])->where(['union_code' => $model->union_code, 'is_active' => 1])->all();
                            if(!is_array($model->mcc_plant_code)){
                                $moduleCodes[] = $model->mcc_plant_code;
                            } else {
                                $moduleCodes = $model->mcc_plant_code;
                            }
                            $union_bank = TblUnionBankPayment::find()
                                    ->alias('ubp')
                                    ->select(['ubp.union_bank_payment_code', 'ubp.bank_name', 'dbd.module_code'])
                                    ->innerJoin('tbl_debit_bank_detail as dbd', 'dbd.union_bank_payment_code = ubp.union_bank_payment_code')
                                    ->where(['ubp.union_code' => $model->union_code, 'ubp.is_active' => 1])
                                    ->andWhere(['in', 'dbd.module_code', $moduleCodes])
                                    ->groupBy(['ubp.union_bank_payment_code', 'ubp.bank_name', 'dbd.module_code'])
                                    ->asArray()
                                    ->all();
                            $result = array_diff($moduleCodes, array_column($union_bank, 'module_code'));
                            if (!empty($result)) {
                                $union_bank = [];
                            }
                        }
                    }
                    return $this->render('confirm-payment', [
                                'pay_amount' => $pay_amount,
                                'searchModel' => $model,
                                'dataProvider' => $dataProvider,
                                'bank_show' => $union_bank,
                    ]);
                } else {
                    if ($this->exportCSV($model)) {
                        return $this->redirect(\yii\helpers\Url::previous());
                    }
                }
            }
        }
    }

    protected function exportCSV($model) {
        $newModel = new TblVspPayment();
        $query = $newModel->find()->where(['tbl_vsp_payment.payment_cycle_code' => $model->payment_cycle_code,
            'tbl_vsp_payment.bmc_code' => $model->bmc_code,
            'tbl_vsp_payment.status' => ['locked', 'rejected'],
                // 'tbl_vsp_payment.dcs_code' => $_REQUEST['selection']
        ]);
        if (!empty($model->customer_type)) {
            $query = $query->andWhere(['tbl_vsp_payment.customer_type' => $model->customer_type]);
        }
        $query = $query->joinWith(['dcsCode', 'mainCustomerCode'])
                ->all();

        $extention = 'xls';
        $header = [
            'mime' => 'application/ms-excel',
            'extension' => $extention,
            'writer' => IOFactory::WRITER_XLSX,
        ];

        $fileName = "payment_disburse_vsp." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>Vendor Code</td>";
        echo "<td>Vendor Type</td>";
        echo "<td>Vendor Name</td>";
        echo "<td>Account No</td>";
        echo "<td>Bank</td>";
        echo "<td>Branch</td>";
        echo "<td>IFSC</td>";
        echo "<td>Total Amount</td>";
        echo "<td>Adjsut Amount</td>";
        echo "<td>Final Amount</td>";
        echo "<td>Adjsut Remarks</td>";
        echo "</tr>";

        foreach ($query as $row) {
            echo "<tr>";
            echo $this->setVal($row->customer_code);
            echo $this->setVal(Yii::$app->general->getforeignkey($row->customerType, 'customer_desc'));
            echo $this->setVal(Yii::$app->general->getCustomer($row, $row->customer_type));
            echo $this->setVal($row->bank_account_no);
            echo $this->setVal($row->bank_name);
            echo $this->setVal($row->branch_name);
            echo $this->setVal($row->ifsc);
            echo $this->setVal($row->amount);
            echo $this->setVal($row->adjust_amount);
            echo $this->setVal($row->final_pay);
            echo $this->setVal($row->adjust_remark);
            echo "</tr>";
        }
        echo "</table>";
        exit();

//        $header = [
//            'mime' => 'application/csv',
//            'extension' => 'csv',
//            'writer' => 'CSV',
//        ];
//
//        $objPHPExcel = new Spreadsheet();
//        $objPHPExcel->setActiveSheetIndex(0);
//        $objPHPExcel->getDefaultStyle()
//                ->getNumberFormat()
//                ->setFormatCode(
//                        \PHPExcel_Style_NumberFormat::FORMAT_TEXT
//        );
//        $rowCount = 1;
//        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Vendor Code');
//        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Vendor Name');
//        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Account No');
//        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Bank');
//        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Branch');
//        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'IFSC');
//        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Total Amount');
//        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Adjsut Amount');
//        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Final Amount');
//        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Adjsut Remarks');
//        foreach ($query as $row) {
//            // if ($row->final_pay > 0) {
//            $rowCount++;
//            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->customer_code);
//            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, Yii::$app->general->getCustomer($row, $row->customer_type));
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '="' . $row->bank_account_no . '"');
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->bank_name);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->branch_name);
//            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->ifsc);
//            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->amount);
//            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->adjust_amount);
//            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->final_pay);
//            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->adjust_remark);
//            //  }
//        }
//        $fileName = "payment_disburse_vsp." . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
//        header('Content-Disposition: attachment;filename=' . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
//        exit();
    }

    public function actionBankPayment() {
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->post());
        $customer = new TblCustomerMaster();
        if (empty($model->customer_type) && !empty($model->bmc_code)) {
            $data = $customer->customerType($model->bmc_code);
            $model->customer_type = array_keys($data);
        }
        // $model->dcs_code = Yii::$app->request->post('selection');
        $msg = $this->LockBilling($model);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        // $url = Url::to(['index']);
        $result = 'success';
        $paymentcycle = $model->paymentCycleCode;
        $from_date = Yii::$app->controls->view_date($paymentcycle->from_date);
        $to_date = Yii::$app->controls->view_date($paymentcycle->to_date);
        $msg .= '(' . $from_date . ' to ' . $to_date . ') - Payment disbursed successfully';
        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
            'message' => \Yii::t('app', '' . $msg)]);
        // return ['status' => $result, 'url' => $url, 'msg' => $msg];
        return $this->redirect(['index']);
    }

    protected function LockBilling($model) {
        $bmc_array = [];
        $msg = '';
        $bmc_array[] = $model->bmc_code;
        $customer_array[] = $model->customer_type;
        if (is_array($model->bmc_code)) {
            $bmc_array = $model->bmc_code;
        }
        if (is_array($model->customer_type)) {
            $customer_array = $model->customer_type;
        }
        foreach ($bmc_array as $bmc_code) {
            $model->bmc_code = $bmc_code;
            $bmc = $model->bmcCode;
            foreach ($customer_array as $customerType) {
                $param = [];
                $param['customer_type'] = $customerType;
                $param['bmc_code'] = $bmc_code;
                $param['applicable_for'] = 'BMC';
                $param['payment_cycle_code'] = $model->payment_cycle_code;
                $param['user_code'] = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                $param['is_without_release'] = $model->payment_release_type;
                $param['union_bank_payment_code'] = !empty($model->union_bank_payment_code) ? $model->union_bank_payment_code : null;
                Yii::$app->ClientPaymentConfig->processPayment('vsp_payment_disburse', $param);
                $model->customer_type = $customerType;
                $msg .= $model->customerType->customer_desc . ' - ' . $bmc->ref_code . ' ' . $bmc->bmc_name . "<br>";
            }
        }

        return $msg;
    }

    protected function exportTxt($model) {
        $send_notif = FALSE;
        try {
            $this->deletepayment($model);
            $union_bank = TblUnionBankPayment::find()->where(['union_code' => $model->union_code, 'is_active' => 1])->one();
            if ($union_bank->server_type == 'eipl') {
                $filePath = $union_bank->file_path . date('Y-m-d') . '/';
            } else {
                $filePath = $union_bank->file_path . 'in/';
                $temp_filePath = $filePath . 'eipltemp/';
            }
            $newModel = new TblVspPayment();
            $code_cnt = 0;
            $cnt = 0;
            $query = $newModel->find()->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'tbl_vsp_payment.dcs_code' => $model->dcs_code])
                    ->all();
            foreach ($query as $data) {
                if (!empty($data->dcs_code) && in_array($data->dcs_code, $model->dcs_code)) {
                    $dcsPaymentApp = TblDcsPaymentCycleApplicability::find()->where(['dcs_code' => $data->dcs_code, 'dcs_payment_cycle_code' => $data->dcs_payment_cycle_code])->one();
                    if (!empty($dcsPaymentApp)) {
                        $dcsPaymentApp->data_lock_vsp = 1;
                        $save_model[] = $dcsPaymentApp;
                    }
                    $outstanding = TblVspOutstanding::find()->where(['dcs_code' => $data->dcs_code])->one();
                    if (empty($outstanding)) {
                        $outstanding = new TblVspOutstanding();
                        $outstanding->union_code = $data->union_code;
                        $outstanding->dcs_code = $data->dcs_code;
                    } else {
                        $oshistoryModel = new TblVspOutstandingHistory();
                        Yii::$app->operation->history($outstanding, $oshistoryModel, UPDATE);
                        $save_model[] = $oshistoryModel;
                    }
                    $outstanding->scenario = 'payment';
                    $outstanding->dcs_payment_cycle_code = $data->dcs_payment_cycle_code;
                    $outstanding->hold_amount = $data->hold_amount;
                    $outstanding->due_amount = $data->adjust_amount;
                    $save_model[] = $outstanding;

                    $historyModel = new TblVspPaymentHistory();
                    Yii::$app->operation->history($data, $historyModel, UPDATE);
                    $save_model[] = $historyModel;
                    if ($data->status == 'rejected') {
                        $data->disburse_date = NULL;
                        $data->disburse_amount = 0.00;
                        $data->utr_no = NULL;
                        $data->reference_no = NULL;
                        $data->process_date = NULL;
                        $data->reject_reason = NULL;
                        $data->bank_status = NULL;
                    }
                    $data->status = 'sent';
                    $payment_tr = new TblPaymentTransaction();
                    $payment_tr->attributes = $data->attributes;
                    $payment_tr->total_amount = $data->amount;
                    $payment_tr->total_deduction = $data->deduction;
                    $payment_tr->qty = $data->total_qty;
                    $payment_tr->payment_date = date('Y-m-d H:i:s');
                    $payment_tr->final_amount = round($data->final_pay);
                    $payment_tr->mobile_no = !empty($data->dcsCode && $data->dcsCode->defaultContactDetail) ? $data->dcsCode->defaultContactDetail->mobile_no : NULL;
                    $payment_tr->is_file = 0;
                    $payment_tr->payment_transaction_code = $payment_tr->getMaxCode() + $code_cnt;
                    $payment_tr->code = $data->dcs_code;
                    $payment_tr->name = $data->dcsCode->dcs_name;
                    $payment_tr->type = 'vsp';
                    $save_model[] = $payment_tr;
                    $cnt++;
                    $data->payment_transaction_code = $payment_tr->payment_transaction_code;
                    $code_cnt++;
                    $save_model[] = $data;
                }
            }

            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' VSP of ' . count($query) . ' VSP', 'info']);
            if ($transaction != FALSE && $transaction != 'customRender') {
                $file_data = new TblPaymentTransaction();
                $file_data->type = 'vsp';
                $file_data->dcs_code = $model->dcs_code;
                $file_data->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                $file_data = $file_data->getRecords();
                if (count($file_data) > 0) {
                    $proccess_data = FALSE;
                    if ($union_bank->server_type == 'eipl' && Yii::$app->general->checkDirectory($filePath) && Yii::$app->general->checkDirectory($filePath . 'inprocess') && Yii::$app->general->checkDirectory($filePath . 'inputfile') && Yii::$app->general->checkDirectory($filePath . 'mis')) {
                        $proccess_data = TRUE;
                        $bank_type = 'UBI';
                        $file_folder = $filePath . 'inputfile/';
                    } else if (Yii::$app->general->checkDirectory($filePath) && Yii::$app->general->checkDirectory($temp_filePath)) {
                        $proccess_data = TRUE;
                        $bank_type = 'AXIS';
                        $file_folder = $filePath;
                    }
                    if ($proccess_data) {
                        $text = '';
                        $ubi_text = '';
                        $bank_total_dabit = 0;
                        $bank_total_cnt = 0;

                        foreach ($file_data as $row) {
                            if ($bank_type == 'UBI') {
                                $mobile_no = !empty($row->mobile_no) ? $row->mobile_no : '9999999999';
                                $txtrow = $union_bank->bank_account_no . '|' . //Debit Account No
                                        $row->final_amount . '|' . //Amount
                                        $row->ifsc . '|' . //Memebr IFSC
                                        $row->payment_transaction_code . '|' . //Payment Cycleid/ Payment Referenceid
                                        $row->bank_account_no . '|' . //Member Acc No
                                        $row->name . '|' . //Member Name
                                        $row->code . '|' . //Member code
                                        '' . '|' . //Aadhar No
                                        substr($row->bank_name, 0, 35) . '|' . //Bank Name
                                        $model->union_code . '|' . //Union Code
                                        $row->code . '|' . //DCS Code
                                        $union_bank->bank_account_no . '|' . //Charge Debit A/c
                                        'SMS' . '|' . //Mobile/ Email ID
                                        $mobile_no; //mobile no
                                // $row->memberCode->mobile_no . '|' . //mobile no
                                //'NEFT' . '|' . //Transaction Type
                                //date('d.m.Y'); //Date of Transaction
                                if (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) {
                                    $ubi_text .= $txtrow . PHP_EOL;
                                } else {
                                    $text .= $txtrow . PHP_EOL;
                                }
                                $bank_total_dabit = $bank_total_dabit + $row->final_amount;
                                $bank_total_cnt += 1;
                            } else if ($bank_type == 'AXIS') {
                                $char = (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) ? 'FT' : 'NE';
                                $txtrow = 'P^' . //Identifier
                                        $char . '^' . //Payment Mode (axis to axis - FT/ other-NE )
                                        $union_bank->corporate_code . '^' . //Corporate Code
                                        $row->payment_transaction_code . '^' . //Customer Reference Number
                                        $union_bank->bank_account_no . '^' . //Debit Account No
                                        date('Y-m-d') . '^' . // Value Date
                                        'INR^' . // Transaction Currency
                                        $row->final_amount . '^' . // Transaction Amount
                                        $row->name . '^' . // Beneficiary Name
                                        $row->code . '^' . // Beneficiary Code / Vendor Code
                                        $row->bank_account_no . '^' . // Beneficiary Account Number
                                        '10^' . // Benefciary Account Type
                                        '^' . // Beneficiary Address 1
                                        '^' . // Beneficiary Address 2
                                        '^' . // Beneficiary Address 3
                                        '^' . // Beneficiary City
                                        '^' . // Beneficiary State
                                        '^' . // Beneficiary Pin Code
                                        $row->ifsc . '^' . // Beneficiary IFSC Code
                                        $row->bank_name . '^' . // Beneficiary Bank Name
                                        '^' . // Base Code
                                        '^' . // Cheque Number
                                        '^' . // Cheque Date
                                        '^' . // Payable location
                                        '^' . // Print Location
                                        '^' . // Beneficiary Email address 1
                                        '^' . // Beneficiary Email address 2
                                        '^' . // Beneficiary Mobile Number
                                        '^' . // Corp Batch No
                                        '^' . // Company Code
                                        '^' . // Product Code
                                        '^' . // Extra 1
                                        '^' . // Extra 2
                                        '^' . // Extra 3
                                        '^' . // Extra 4
                                        '^' . // Extra 5
                                        '^' . // PayType
                                        $union_bank->email . '^' . // CORP_EMAIL_ADDR
                                        date('Y-m-d H-i-s') . '^' . // TRANSMISSION DATE
                                        '^'; // User ID,USER DEPARTMENT  
                                $text .= $txtrow . PHP_EOL;
                                $bank_total_dabit = $bank_total_dabit + $row->final_amount;
                                $bank_total_cnt += 1;
                            }
                        }

                        $save_model = [];
                        $NEFT = TRUE;
                        $UBI = TRUE;
                        if ($text != '') {
                            $data_write = FALSE;
                            if ($bank_type == 'AXIS') {
                                $f_name = $union_bank->corporate_code . '_H2H_' . date('dmY') . '_' . $union_bank->union_code . date('His') . ".txt";
                                $fileName = $file_folder . $f_name;
                                $temp_fileName = $temp_filePath . $f_name;
                                $vfile = fopen($temp_fileName, "w") or die("Unable to open file!");
                                if (fwrite($vfile, $text)) {
                                    $data_write = TRUE;
                                    fclose($vfile);
                                } else {
                                    fclose($vfile);
                                    unlink($vfile);
                                }
                            } else {
                                $fileName = $file_folder . 'NEFT_' . date('YmdHis') . ".txt";
                                $vfile = fopen($fileName, "w") or die("Unable to open file!");
                                if (fwrite($vfile, $text)) {
                                    $data_write = TRUE;
                                    fclose($vfile);
                                } else {
                                    fclose($vfile);
                                    unlink($vfile);
                                }
                            }
                            if ($data_write) {
                                $neft_log = new TblBankPaymentLog();
                                $neft_log->union_code = $model->union_code;
                                $neft_log->file_path = $fileName;
                                $neft_log->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                                $neft_log->status = 1; //created
                                $neft_log->payment_date = date('Y-m-d');
                                Yii::$app->operation->defaults($neft_log, INSERT);
                                $neft_log->save();
                            } else {
                                $NEFT = FALSE;
                            }
                        }
                        if ($ubi_text != '') {
                            $fileName = $file_folder . 'UBI_' . date('YmdHis') . ".txt";
                            $ubifile = fopen($fileName, "w") or die("Unable to open file!");
                            if (fwrite($ubifile, $ubi_text)) {
                                $ubi_log = new TblBankPaymentLog();
                                $ubi_log->union_code = $model->union_code;
                                $ubi_log->file_path = $fileName;
                                $ubi_log->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                                $ubi_log->status = 1; //created
                                $ubi_log->payment_date = date('Y-m-d');
                                Yii::$app->operation->defaults($ubi_log, INSERT);
                                $ubi_log->save();
                                fclose($ubifile);
                            } else {
                                fclose($ubifile);
                                unlink($ubifile);
                                $UBI = FALSE;
                            }
                        }

                        if ($NEFT && $UBI) {
                            foreach ($file_data as $data) {
                                $data->is_file = 1;
                                $data->file_datetime = date('Y-m-d H:i:s');
                                if ($bank_type == 'UBI' && (substr($union_bank->ifsc, 0, 4) == substr($data->ifsc, 0, 4))) {
                                    $data->file_id = $ubi_log->log_id;
                                } else {
                                    $data->file_id = $neft_log->log_id;
                                }
                                if ($bank_type == 'AXIS') {
                                    $data->file_name = $f_name;
                                }
                                $save_model[] = $data;
                            }
                            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' VSP of ' . count($query) . ' VSP', 'info']);
                            if ($transaction == 'customRedirect') {
                                if ($bank_type == 'AXIS') {
                                    if (copy($temp_fileName, $fileName)) {
                                        $send_notif = TRUE;
                                        unlink($temp_fileName);
                                    } else {
                                        /* update vsp payment data */
                                        Yii::$app->db->createCommand("update mp set mp.status = 'processed', mp.payment_transaction_code = NULL from tbl_vsp_payment mp
inner join tbl_payment_transaction pt on pt.payment_transaction_code = mp.payment_transaction_code
where pt.file_name = :file_name and mp.status = 'sent'")
                                                ->bindValue(':file_name', $f_name)
                                                ->execute();
                                        /* update vsp payment data */
                                        /* delete payment data */
                                        Yii::$app->db->createCommand("delete from tbl_payment_transaction where file_name = :file_name and type = 'vsp'")
                                                ->bindValue(':file_name', $f_name)
                                                ->execute();
                                        /* delete payment data */
                                    }
                                } else {
                                    $send_notif = TRUE;
                                }
                            } else {
                                $this->deletepayment($model);
                            }
                        } else {
                            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                'message' => 'An error occurred on the server when processing file']);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => 'Error while file processing.']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Bank detail not available.']);
                }
            }
        } catch (\Throwable $ex) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'An error occurred on the server when processing file']);
        }
        if ($send_notif) {
            $this->PaymentNotification($union_bank, $bank_total_dabit, $bank_total_cnt, $fileName);
        }


        return TRUE;
    }

    private function insertPaymentData($model) {
        $from_date = date('Y-m-d', strtotime($model->paymentCycleCode->from_date));
        $to_date = date('Y-m-d', strtotime($model->paymentCycleCode->to_date));
        $bill_head = TblBillHead::find()->where(['union_code' => $model->union_code])->all();
        $fat = 0.00;
        $snf = 0.00;
        $qty = 0;
        $amount = 0;
        $main_amount = 0;
        foreach ($model->dcs_code as $dcs_code) {
            $bill_head_data = [];
            foreach ($bill_head as $data) {
                if ($data->bill_head_name == 'Milk Amount') {
                    $fat = 0.00;
                    $snf = 0.00;
                    $qty = 0;
                    $amount = 0;
                    $main_amount = 0;
                    $adddata = TRUE;
                    $sumdata = FALSE;
                    $milkcoll = TblBmcCollection::find()->select(['fat' => 'round(sum(Round(qty * fat/100,4)),2) ', 'snf' => 'round(sum(Round(qty * snf/100,4)),2) ', 'amount' => 'SUM(amount)', 'qty' => 'SUM(qty)'])->where(['dcs_code' => $dcs_code])
                                    ->andWhere(['>=', 'date_time_of_collection', $from_date])
                                    ->andWhere(['<=', 'date_time_of_collection', $to_date])
                                    ->groupBy('dcs_code')->asArray()->one();
                    if (!empty($milkcoll)) {
                        $amount = $milkcoll['amount'];
                        $qty = $milkcoll['qty'];
                        $fat = $milkcoll['fat'];
                        $snf = $milkcoll['snf'];
                    }
                    $main_amount = $amount;
                } else if ($data->bill_head_name == 'Member Payable') {
                    $adddata = TRUE;
                    $sumdata = TRUE;
                    $milkcoll = TblMilkCollection::find()->select(['amount' => 'SUM(amount)', 'qty' => 'SUM(qty)'])->where(['dcs_code' => $dcs_code])
                                    ->andWhere(['>=', 'date_time_of_collection', $from_date])
                                    ->andWhere(['<=', 'date_time_of_collection', $to_date])
                                    ->groupBy('dcs_code')->asArray()->one();
                    $amount = !empty($milkcoll) ? $milkcoll['amount'] : 0;
                } else {
                    $adddata = TRUE;
                    $sumdata = TRUE;
                    $milkcoll = TblBillHeadDetail::find()->select(['amount' => 'SUM(CAST(amount as decimal(18,2)))'])
                                    ->where(['dcs_code' => $dcs_code, 'bill_head_code' => $data->bill_head_code])
                                    ->andWhere(['payment_cycle_code' => $model->dcs_payment_cycle_code])
                                    ->groupBy('dcs_code')->asArray()->one();
                    $amount = !empty($milkcoll) ? $milkcoll['amount'] : 0;
                    if (empty($milkcoll)) {
                        $adddata = FALSE;
                    }
                }
                if ($adddata) {
                    $bill_head_data[] = [
                        'amount' => $amount,
                        'type' => $data->bill_head_type,
                        'code' => $data->bill_head_code,
                        'sumdata' => $sumdata
                    ];
                }
            }
            $payment = new TblVspPayment();
            $payment->vsp_payment_code = Yii::$app->general->getCodeAutoIncrement($payment);
            $payment->union_code = $model->union_code;
            $payment->dcs_code = $dcs_code;
            $payment->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
            $payment->total_qty = $qty;
            $payment->kg_fat = $fat;
            $payment->kg_snf = $snf;
            $payment->amount = $main_amount;
            $payment->payment_date = date('Y-m-d H:i:s');
            $payment->status = 'processed';
            if (isset($payment->dcsCode->defaultBankDetail)) {
                $payment->bank_code = $payment->dcsCode->defaultBankDetail->bank_code;
                $payment->branch_code = $payment->dcsCode->defaultBankDetail->branch_code;
                $payment->bank_name = !empty($payment->dcsCode->defaultBankDetail->bankCode) ? $payment->dcsCode->defaultBankDetail->bankCode->bank_name : NULL;
                $payment->branch_name = !empty($payment->dcsCode->defaultBankDetail->branchCode) ? $payment->dcsCode->defaultBankDetail->branchCode->branch_name : NULL;
                $payment->ifsc = $payment->dcsCode->defaultBankDetail->ifsc;
                $payment->bank_account_no = $payment->dcsCode->defaultBankDetail->bank_account_no;
                $payment->is_verified = 1;
            }
            $tot_qty = 0;
            $tot_addition = 0;
            $tot_deduction = 0;
            $tot_net = 0;
            $tot_final = 0;
            $saveArray = [];
            foreach ($bill_head_data as $inshead) {
                $trn = new TblVspPaymentTransaction();
                $trn->vsp_payment_code = $payment->vsp_payment_code;
                $trn->amount = $inshead['amount'];
                $trn->bill_head_code = $inshead['code'];
                $trn->bill_head_type = $inshead['type'];
                $saveArray[] = $trn;
                if ($inshead['sumdata']) {
                    if ($trn->bill_head_type == 0) {
                        $tot_addition += $trn->amount;
                    } else {
                        $tot_deduction += $trn->amount;
                    }
                }
            }
            $payment->final_pay = ($payment->amount + $tot_addition) - $tot_deduction;
            $payment->net_payable = $payment->final_pay;
            $payment->addition = $tot_addition;
            $payment->deduction = $tot_deduction;
            $saveArray[] = $payment;
            $transaction = $this->generalModel->saveTransaction($saveArray, ['Payment Processed Succesfully.', 'info']);
        }
    }

    public function deletepayment($model) {
        /* update vsp payment data */
        Yii::$app->db->createCommand("update mp set mp.status = 'processed', mp.payment_transaction_code = NULL from tbl_vsp_payment mp
inner join tbl_payment_transaction pt on pt.payment_transaction_code = mp.payment_transaction_code
where pt.dcs_code IN (:dcs_code) and pt.dcs_payment_cycle_code = :dcs_payment_cycle_code and pt.type = 'vsp' and mp.status = 'sent' and pt.is_file = 0")
                ->bindValue(':dcs_code', is_array($model->dcs_code) ? implode(',', $model->dcs_code) : $model->dcs_code)
                ->bindValue(':dcs_payment_cycle_code', $model->dcs_payment_cycle_code)
                ->execute();
        /* update vsp payment data */
        /* delete payment data */
        Yii::$app->db->createCommand("delete from tbl_payment_transaction
where dcs_code IN (:dcs_code) and dcs_payment_cycle_code = :dcs_payment_cycle_code and type = 'vsp' and is_file = 0")
                ->bindValue(':dcs_code', is_array($model->dcs_code) ? implode(',', $model->dcs_code) : $model->dcs_code)
                ->bindValue(':dcs_payment_cycle_code', $model->dcs_payment_cycle_code)
                ->execute();
        /* delete payment data */
    }

    public function actionCheckPaymentProcessed() {
        $model = new TblVspPayment();
        $model->attributes = Yii::$app->request->post();
        $count = $model->getRecords()->count();
        $msg = '';
        if ($count > 0) {
            $msg = Yii::t('app', 'Payment already processed for selected input.');
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['message' => $msg]);
    }

    public function setVal($value) {
        if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
            echo "<td>" . $value . "</td>";
        } else {
            echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
        }
    }

    public function actionAddRecovery() {
        $model = $this->findModel(Yii::$app->request->get()['code']);
        if (Yii::$app->request->post()) {
            $process = FALSE;
            $postData = Yii::$app->request->post()['TblVspPayment'];
            $total_amount = (float) abs($model->net_payable);
            $total_recovery = (float) array_sum(array_column($postData, 'new_recovery'));
            if ($total_recovery <= $total_amount) {
                $saveModel = [];
                $deleteModel = [];
                foreach ($postData as $data) {
                    $is_delete = FALSE;
                    $record = TblVspPayment::findOne($data['vsp_payment_code']);
                    $rec_model = TblVspPaymentRecovery::find()
                            ->where(['payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'customer_type' => $model->customer_type,
                                'from_customer_code' => $record->customer_code, 'for_customer_code' => $model->customer_code])
                            ->one();
                    $old_rec = 0.00;
                    $new_rec = !empty($data['new_recovery']) ? $data['new_recovery'] : 0.00;
                    if (!empty($rec_model)) {
                        $old_rec = $rec_model->recovery_amount;
                        $rec_model->recovery_amount = $new_rec;
                        $is_delete = ($new_rec == 0.00) ? TRUE : FALSE;
                    } else if (!empty($new_rec) && $new_rec != 0.00) {
                        $rec_model = new TblVspPaymentRecovery();
                        $rec_model->attributes = $model->attributes;
                        $rec_model->for_customer_code = $model->customer_code;
                        $rec_model->from_customer_code = $record->customer_code;
                        $rec_model->recovery_amount = $new_rec;
                        $rec_model->created_at = $rec_model->created_by = $model->updated_at = $rec_model->updated_by = $rec_model->originating_org_code = $rec_model->originating_org_type = $rec_model->originating_type = NULL;
                    }
                    if ($old_rec != $new_rec) {
                        $process = TRUE;
                        $record->final_pay = $record->final_pay + $old_rec - $new_rec;
                        $record->recovery = $record->recovery - $old_rec + $new_rec;
                        $saveModel[] = $record;
                        if ($is_delete) {
                            $deleteModel[] = $rec_model;
                        } else {
                            $saveModel[] = $rec_model;
                        }
                    }
                }
                if ($process) {
                    $model->final_pay = $model->final_pay - $model->adjust_recovery + $total_recovery;
                    $model->adjust_recovery = $total_recovery;
                    $saveModel[] = $model;
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Adjust Recovery', 'create']);
                    if ($transaction == 'customRedirect') {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'msg' => $msg];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg];
                    }
                } else {
                    $msg = Yii::t('app', 'No Changes found in data.');
                    $record = ['status' => 'error', 'msg' => $msg];
                }
            } else {
                $msg = Yii::t('app', 'Sum Of New Recovery must not be grater than Total Amount.');
                $record = ['status' => 'error', 'msg' => $msg];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
        $recoveryData = $model->getRecoveryRecords();
        return $this->renderAjax('add-recovery', [
                    'model' => $model,
                    'recoveryData' => $recoveryData]);
    }

    public function actionCheckMccType() {
        $model = new TblVspPayment();
        $model->attributes = Yii::$app->request->post();
        $multiple_bmc = '0';
        $mcc_data = $model->mccPlantCode;
        if (!empty($mcc_data) && $mcc_data->vendor_payment_with_multiple_bmc == 1) {
            $multiple_bmc = '1';
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['multiple_bmc' => $multiple_bmc]);
    }

    public function actionVendorPaymentImport() {
        
    }

}
