<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMemberPayment;
use app\modules\payment\models\TblMemberPaymentSearch;
use app\modules\payment\models\TblTmpMemberPayment;
use app\modules\collection\models\TblMilkCollection;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\modules\payment\models\TblDcsPayment;
use yii\web\Response;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\modules\payment\models\TblDcsPaymentHistory;
use app\modules\payment\models\TblMemberPaymentHistory;
use app\modules\payment\models\TblUnionBankPayment;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\payment\models\TblPaymentOtpVerification;
use app\modules\organisation\models\TblDcs;
use app\modules\verification\models\TblVerification;
use app\modules\payment\models\TblPaymentTransaction;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentAlias;
use app\modules\payment\models\TblMemberPaymentAliasHistory;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\payment\models\TblMemberPaymentSummary;
use app\modules\payment\models\TblMemberPaymentSummaryAlias;
use app\modules\payment\models\TblMemberPaymentSummaryHistory;
use app\modules\payment\models\TblMemberPaymentSummaryAliasHistory;
use app\modules\payment\models\TblPaymentCycleApplicabilityHistory;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\payment\models\TblMemberPaymentSummarySearch;
use app\modules\payment\models\TblMemberPaymentSummaryAliasSearch;
use app\modules\payment\models\TblMemberOutstanding;
use app\modules\payment\models\TblMemberOutstandingHistory;
use app\modules\payment\models\TblMemberPaymentAliasSearch;
use app\modules\payment\models\TblMemberPaymentHeadSummarySearch;
use app\modules\payment\models\TblMemberPaymentHeadSearch;
use app\modules\payment\models\TblSaleInstallmentsSearch;
use app\modules\payment\models\TblMemberPaymentInstallment;
use app\modules\payment\models\TblSaleInstallments;
use app\modules\payment\models\TblMemberPaymentInstallmentHistory;
use app\modules\sms\models\TblAlertNotification;
use app\modules\sms\models\TblAlertTemplate;
use app\modules\payment\models\TblMemberPaymentRecovery;
use app\modules\payment\models\TblMemberPaymentRecoveryHistory;
use app\modules\payment\models\TblMemberPaymentHead;
use app\modules\vsp\models\TblBillHeadInstallment;
use app\modules\vsp\models\TblBillHeadInstallmentHistory;
use app\modules\payment\models\TblMemberPaymentHeadSummary;
use app\modules\payment\models\TblPaymentStop;
use app\modules\payment\models\TblPaymentStopHistory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\modules\vsp\models\TblBillHead;
use app\modules\payment\models\TblVspPayment;

/**
 * TblMemberPaymentController implements the CRUD actions for TblMemberPayment model.
 */
class TblMemberPaymentController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-total-recovery', 'member-payment-adjust-list', 'list-member-payment-summary-data', 'validate-bank-details', 'send-otp', 'verify-otp'];

    /**
     * Finds the TblMemberPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMemberPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreatePayment() {
        // Farmer Payment Process : Step 1
        $model = new TblMemberPaymentAlias();
        $paymentcycleAppModel = new TblPaymentCycleApplicability();
        if (Yii::$app->request->post()) {
            $result = 'success';
            $model->scenario = 'processpayment';
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $appModel = new TblPaymentCycleApplicability();
                $appModel->payment_cycle_code = $model->payment_cycle_code;
                $appModel->applicable_code = $model->bmc_code;
                $appModel->applicable_for = 'BMC';
                $appModel->applicable_type = 'DCS';
                $disburseCount = $appModel->getStatusCount(['billing_lock_member' => 1]);
                $lockedData = $model->getStatusLockedCount(['Lock']);
                $generatedData = $model->getStatusLockedCount(['Generated', 'Process']);
                $stopModel = new TblPaymentStop();
                $stopModel->bmc_code = $model->bmc_code;
                $stopModel->customer_type = 'DCS';
                $stopModel->payment_type = 'MEMBER_PAYMENT';
                $stopMsg = $stopModel->getStatusStop();

                $msg = '';
                $queryParam = [];
                $queryParam[] = 'list-member-payment-summary';
                $queryParamRegenerate = [];
                $queryParam['TblMemberPaymentAlias'] = ['payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'union_code' => $model->union_code];
                $queryParamRegenerate = $queryParam;
                $queryParamRegenerate['reGenerate'] = 0;
                if ($disburseCount > 0) {
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
//                return $this->redirect(['list-member-payment-summary', 'TblMemberPaymentAlias' => ['payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'union_code' => $model->union_code]]);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->render('create_payment', [
                    'model' => $model,
        ]);
    }

    public function actionCreateStopPayment() {
        // Farmer Stop Payment Process : Step 1
        $model = new TblMemberPaymentAlias();
        $paymentcycleAppModel = new TblPaymentCycleApplicability();
        if (Yii::$app->request->post()) {
            $result = 'success';
            $model->scenario = 'processpayment';
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $appModel = new TblPaymentCycleApplicability();
                $appModel->payment_cycle_code = $model->payment_cycle_code;
                $appModel->applicable_code = $model->bmc_code;
                $appModel->applicable_for = 'BMC';
                $appModel->applicable_type = 'DCS';
                $stopModel = new TblPaymentStop();
                $stopModel->bmc_code = $model->bmc_code;
                $stopModel->customer_type = 'DCS';
                $stopModel->payment_type = 'MEMBER_PAYMENT';
                $stopMsg = $stopModel->getStatusStop();
                $generatedData = $model->getStatusLockedCount(['Generated', 'Process']);

                $msg = '';
                $queryParam = [];
                $queryParam[] = 'list-member-payment-summary';
                $queryParamRegenerate = [];
                $queryParam['TblMemberPaymentAlias'] = ['payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'union_code' => $model->union_code];
                $queryParamRegenerate = $queryParam;
                $queryParamRegenerate['reGenerate'] = 0;
                $queryParamRegenerate['stop_payment_only'] = 1;
                $queryParam['stop_payment_only'] = 1;

                if (empty($stopMsg)) {
                    $result = 'displayPopup';
                    $msg = Yii::t('app', 'Stop Payment data is not available');
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
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->render('create_payment', [
                    'model' => $model, 'post_url' => Url::to(['create-stop-payment']), 'title' => Yii::t('app', 'Member Stop Payment Process : Step 1')
        ]);
    }

    public function actionListMemberPaymentSummary($reGenerate = 0) {
        // Farmer Payment Process : Step 2 (Display DCS Wise Data)
        if (Yii::$app->request->get()) {
            $stop_payment_only = isset(Yii::$app->request->get()['stop_payment_only']) ? 1 : 0;
            $model = new TblMemberPaymentAlias();
            $model->load(Yii::$app->request->get());
            if ($reGenerate == 1) {
                $query = $this->getMemberDcsSpData($model, $reGenerate, $stop_payment_only);
            }
            $searchModel = new TblMemberPaymentSummaryAliasSearch();
            $searchModel->attributes = $model->attributes;
            $dataProvider = $searchModel->search([], $stop_payment_only);
            $dataProvider->pagination = false;
            $negativeValCount = 0;
            $memberPaymentModel = new TblMemberPaymentAlias();
            $memberPaymentModel->attributes = $model->attributes;
            $negativeValCount = $memberPaymentModel->getNegativeValCount();
            return $this->render('process_lock_dcs_payment', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'negativeValCount' => $negativeValCount
            ]);
        }
    }

    public function actionListMemberPaymentSummaryData() {
        // Farmer Payment Process : Step 2 (Display DCS Wise Data)
        if (Yii::$app->request->post('TblMemberPaymentAlias')) {
            $postData = Yii::$app->request->post();
            $getParam = Yii::$app->request->post('TblMemberPaymentAlias');
            $getParam['dcs_code'] = json_decode($getParam['dcs_code']);
            $stop_payment_dcs = !empty($postData['selection']) ? $postData['selection'] : [];
            $stop_payment_reason = !empty($postData['TblMemberPaymentSummaryAlias']) ? $postData['TblMemberPaymentSummaryAlias'] : [];
//            $adjust_id = Yii::$app->request->post('TblMemberPaymentAlias')['member_payment_alias_code'];
//            $adjust_amt = Yii::$app->request->post('TblMemberPaymentAlias')['additional_pay'];
//            $adjust_remark = Yii::$app->request->post('TblMemberPaymentAlias')['adjust_remark'];
//            $hold_amt = Yii::$app->request->post('TblMemberPaymentAlias')['hold_amount'];
//            $reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['recovery'] : 0;
//            $adjust_reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery'] : 0;
            $save_model = [];
            $delete_model = [];
            $cnt = 0;
            $processFlag = !empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'Process';
            //process_lock_flag

            $summaryModel = new TblMemberPaymentSummaryAlias();
            $summaryModel->attributes = $getParam;
            $summaryModelData = $summaryModel->getRecords(false);

            $adjustmentSummary = [];
//            foreach ($adjust_id as $key => $value) {
//                $data = TblMemberPaymentAlias::findOne($adjust_id[$key]);
//                $oldData = $data->oldAttributes;
//                $holdAmount = !empty($hold_amt[$key]) ? $hold_amt[$key] : 0;
//                $adjustAmount = !empty($adjust_amt[$key]) ? $adjust_amt[$key] : 0;
//                $recovery = !empty($reco[$key]) ? $reco[$key] : 0;
//                $adjust_recovery = !empty($adjust_reco[$key]) ? $adjust_reco[$key] : 0;
//                $addition = !empty($data->total_addition) ? $data->total_addition : 0;
//                $deduction = !empty($data->total_deduction) ? $data->total_deduction : 0;
//                $dcsCode = $data->dcs_code;
//                $historyModel = new TblMemberPaymentAliasHistory();
//                Yii::$app->operation->history($data, $historyModel, UPDATE);
//                $data->additional_pay = $adjustAmount;
//                $data->adjust_remark = $adjust_remark[$key]; //!empty($adjust_remark[$key]) ? $adjust_remark[$key] : '';
//                $data->hold_amount = $holdAmount;
//                $data->final_amount = $data->net_payable + $adjustAmount - $holdAmount + $adjust_recovery - $recovery;
//                $data->payment_status = $processFlag;
//                $save_model[] = $historyModel;
//                $save_model[] = $data;
//                if ($oldData['additional_pay'] != $data->additional_pay) {
//                    $cnt++;
//                }
//                $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
//                $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
//                $adjustmentSummary[$dcsCode]['recovery'] = !empty($adjustmentSummary[$dcsCode]['recovery']) ? $adjustmentSummary[$dcsCode]['recovery'] + $recovery : $recovery;
//                $adjustmentSummary[$dcsCode]['adjust_recovery'] = !empty($adjustmentSummary[$dcsCode]['adjust_recovery']) ? $adjustmentSummary[$dcsCode]['adjust_recovery'] + $adjust_recovery : $adjust_recovery;
//
//                if ($processFlag == 'Lock') {
//                    $installmentModel = new TblMemberPaymentInstallment();
//                    $existInstallment = $installmentModel->find()->where(['bmc_code' => $data->bmc_code, 'customer_type' => 'Member', 'customer_code' => $data->member_code, 'payment_cycle_code' => $data->payment_cycle_code])->all();
//                    if (!empty($existInstallment)) {
//                        foreach ($existInstallment as $data) {
//                            $saleModel = new TblSaleInstallments();
//                            $existData = $saleModel->find()->where(['product_sale_installment_code' => $data->product_sale_installment_code])->one();
//                            $existData->installment_date = Yii::$app->general->getforeignkey($data->paymentCycleCode, 'from_date');
//                            $save_model[] = $existData;
//                        }
//                    }
//                }
//            }
            $adjust_id = [];
            $memberPaymentModel = new TblMemberPaymentAlias();
            $memberPaymentModel->attributes = $getParam;
            $auto_adjust_stop_payment_member = isset(Yii::$app->session->get('unionConfig')[$memberPaymentModel->union_code]['auto_adjust_stop_payment_member']) ? Yii::$app->session->get('unionConfig')[$memberPaymentModel->union_code]['auto_adjust_stop_payment_member'] : 0;
            $is_validate = true;
            $is_bank_integrated = Yii::$app->general->getUnionConfiguration($memberPaymentModel->union_code, 'is_bank_integrated', 'PORTAL') == 1 ? true : false;
            if ($is_bank_integrated && $processFlag == 'Lock') {
                $memberPaymentModel->scenario = 'finalize_payment';
                if (!$memberPaymentModel->validate()) {
                    $is_validate = false;
                }
            }
            if ($is_validate) {
                $memberPaymentModelData = $memberPaymentModel->getRecords(false)->all();
                foreach ($memberPaymentModelData as $memberPayment) {
                    $historyModel = new TblMemberPaymentAliasHistory();
                    Yii::$app->operation->history($memberPayment, $historyModel, UPDATE);
                    $dcsCode = $memberPayment->dcs_code;
                    $memberPayment->payment_status = ($auto_adjust_stop_payment_member != '1' && in_array($dcsCode, $stop_payment_dcs)) ? 'Process' : $processFlag;
                    if ($auto_adjust_stop_payment_member == '1' && $processFlag == 'Lock' && in_array($dcsCode, $stop_payment_dcs)) {
                        $memberPayment->hold_amount = !empty($memberPayment->hold_amount) ? ((float) $memberPayment->hold_amount + (float) $memberPayment->final_amount) : $memberPayment->final_amount;
                        $memberPayment->final_amount = 0;
                        $memberPayment->adjust_remark .= !empty($stop_payment_reason[$dcsCode]['stop_payment_type']) ? $stop_payment_reason[$dcsCode]['stop_payment_type'] : 'dispute';
                        $memberPayment->adjust_remark .= ' Auto adjust with 0.';
                    }
                    $save_model[] = $historyModel;
                    $save_model[] = $memberPayment;
                    $holdAmount = !empty($memberPayment->hold_amount) ? $memberPayment->hold_amount : 0;
                    $adjustAmount = !empty($memberPayment->additional_pay) ? $memberPayment->additional_pay : 0;
                    $adjustAmount = !empty($memberPayment->additional_pay) ? $memberPayment->additional_pay : 0;
                    $recovery = !empty($memberPayment->recovery) ? $memberPayment->recovery : 0;
                    $adjust_recovery = !empty($memberPayment->adjust_recovery) ? $memberPayment->adjust_recovery : 0;
                    $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
                    $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
                    $adjustmentSummary[$dcsCode]['recovery'] = !empty($adjustmentSummary[$dcsCode]['recovery']) ? $adjustmentSummary[$dcsCode]['recovery'] + $recovery : $recovery;
                    $adjustmentSummary[$dcsCode]['adjust_recovery'] = !empty($adjustmentSummary[$dcsCode]['adjust_recovery']) ? $adjustmentSummary[$dcsCode]['adjust_recovery'] + $adjust_recovery : $adjust_recovery;
                }

                $updated_applicability = [];
                foreach ($summaryModelData as $summaryData) {
                    $historyModel = new TblMemberPaymentSummaryAliasHistory();
                    Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                    $dcs = $summaryData->dcs_code;
                    $summaryData->payment_status = ($auto_adjust_stop_payment_member != '1' && in_array($dcs, $stop_payment_dcs)) ? 'Process' : $processFlag;
                    if (!empty($adjustmentSummary[$dcs])) {
                        $summaryData->additional_pay = $adjustmentSummary[$dcs]['adjustment'];
                        $summaryData->hold_amount = $adjustmentSummary[$dcs]['hold'];
                        $summaryData->recovery = $adjustmentSummary[$dcs]['recovery'];
                        $summaryData->adjust_recovery = $adjustmentSummary[$dcs]['adjust_recovery'];
                        $net_payable = number_format((float) $summaryData->net_payable + (float) $adjustmentSummary[$dcs]['adjustment'] + (float) $adjustmentSummary[$dcs]['adjust_recovery'], 2, '.', '');
                        $hold_amount = number_format((float) $adjustmentSummary[$dcs]['hold'] + (float) $adjustmentSummary[$dcs]['recovery'], 2, '.', '');
                        $final_amount = $net_payable - $hold_amount;
                        $summaryData->final_amount = number_format($final_amount, 2, '.', '');
                        // $summaryData->final_amount = $summaryData->net_payable + $adjustmentSummary[$dcs]['adjustment'] - $adjustmentSummary[$dcs]['hold'] + $adjustmentSummary[$dcs]['adjust_recovery'] - $adjustmentSummary[$dcs]['recovery'];
                    }
                    $save_model[] = $historyModel;
                    $save_model[] = $summaryData;
                    if ($processFlag == 'Lock') {
                        $unique_key = $summaryData->payment_cycle_code . '_' . $summaryData->bmc_code;
                        if (!isset($updated_applicability[$unique_key])) {
                            $applicabilityModels = $summaryData->getPaymentCycleApplicabilityForMemberLock($summaryData->payment_cycle_code, $summaryData->bmc_code);
                            if (!empty($applicabilityModels)) {
                                foreach ($applicabilityModels as $appModel) {
                                    $save_model[] = $appModel;
                                }
                            }
                            $updated_applicability[$unique_key] = true;
                        }

                        $old_stop_all = TblPaymentStop::find()
                                ->where(['payment_cycle_code' => $summaryData->payment_cycle_code, 'customer_code' => $dcs, 'customer_type' => 'DCS', 'payment_type' => 'MEMBER_PAYMENT', 'bmc_code' => $summaryData->bmc_code])
                                ->all();
                        if (!empty($old_stop_all)) {
                            foreach ($old_stop_all as $old_stop) {
                                $historyModel = new TblPaymentStopHistory();
                                if ($auto_adjust_stop_payment_member != '1' && in_array($dcs, $stop_payment_dcs)) {
                                    Yii::$app->operation->history($old_stop, $historyModel, UPDATE);
                                    $old_stop->stop_reason = !empty($stop_payment_reason[$dcs]['stop_payment_type']) ? $stop_payment_reason[$dcs]['stop_payment_type'] : 'dispute';
                                    $save_model[] = $old_stop;
                                } else {
                                    Yii::$app->operation->history($old_stop, $historyModel, DELETE);
                                    $historyModel->lock_datetime = date('Y-m-d H:i:s');
                                    $delete_model[] = $old_stop;
                                }
                                $save_model[] = $historyModel;
                            }
                        } else {
                            if ($auto_adjust_stop_payment_member != '1' && in_array($dcs, $stop_payment_dcs)) {
                                $stop_pay = new TblPaymentStop();
                                $stop_pay->attributes = $summaryData->attributes;
                                $stop_pay->customer_type = 'DCS';
                                $stop_pay->customer_code = $dcs;
                                $stop_pay->payment_type = 'MEMBER_PAYMENT';
                                $stop_pay->stop_reason = !empty($stop_payment_reason[$dcs]['stop_payment_type']) ? $stop_payment_reason[$dcs]['stop_payment_type'] : 'dispute';
                                $stop_pay->originating_type = $stop_pay->originating_org_type = $stop_pay->originating_org_code = NULL;
                                $stop_pay->created_at = $stop_pay->created_by = $stop_pay->updated_at = $stop_pay->updated_by = NULL;
                                $save_model[] = $stop_pay;
                            }
                        }
                    }
                }

                $successMsg = 'Payment adjusted succesfully';
                if ($processFlag == 'Lock') {
                    $successMsg = Yii::t('app', 'Payment has been Locked Successfully');
                }
                $transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $delete_model, [$successMsg, 'info']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return Json::encode($record);
//            if ($transaction !== FALSE && $transaction != 'customRender') {
//                return $this->redirect(['index']);
//            }
            // if (Yii::$app->request->get()) {
            $model = new TblMemberPaymentAlias();
//            $model->load(Yii::$app->request->get());
            $model->attributes = $getParam;
            if (!$is_validate) {
                $model = $memberPaymentModel;
            }
//            if ($reGenerate == 1) {
//                $query = $this->getMemberDcsSpData($model, $reGenerate);
//            }
            $searchModel = new TblMemberPaymentSummaryAliasSearch();
            $searchModel->attributes = $model->attributes;
            $dataProvider = $searchModel->search([]);
            $dataProvider->pagination = false;
            $negativeValCount = 0;
            $memberPaymentModel = new TblMemberPaymentAlias();
            $memberPaymentModel->attributes = $model->attributes;
//            $negativeValCount = $memberPaymentModel->getNegativeValCount();
            $negativeValCount = $memberPaymentModel->getNegativeValDcs();
            $negativeDcsCode = ArrayHelper::getColumn($negativeValCount, 'dcs_code');
            $model->dcs_code = json_encode($model->dcs_code);
            return $this->render('process_lock_dcs_payment_data', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'negativeValCount' => $negativeValCount,
                        'removeCheckBox' => true,
                        'negativeDcsCode' => $negativeDcsCode
            ]);
            //   }
        }
    }

    public function actionListMemberPayment() {
        // Farmer Payment Process : Step 3 (Display Member Wise Data for Adjustment)
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $processFlag = !empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'Process';

            $save_model = [];
            $child_model = [];

            if ($processFlag == 'Lock') {
                $model = new TblMemberPaymentAlias();
                $model->load(Yii::$app->request->post());
                $modelData = $model->getRecords(false)->all();
                $summaryModel = new TblMemberPaymentSummaryAlias();
                $summaryModel->attributes = $model->attributes;
                $summaryModelData = $summaryModel->getRecords(false);
                foreach ($summaryModelData as $summaryData) {
                    $historyModel = new TblMemberPaymentSummaryAliasHistory();
                    Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                    $summaryData->payment_status = $processFlag;
                    $save_model[] = $historyModel;
                    $save_model[] = $summaryData;
                }

                foreach ($modelData as $summaryData) {
                    $modelData = new TblMemberPaymentAliasHistory();
                    Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                    $summaryData->payment_status = $processFlag;
                    $save_model[] = $historyModel;
                    $save_model[] = $summaryData;
                }
                $transaction = $this->generalModel->saveTransaction($save_model, ['Member Payment ' . $processFlag, 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
                $model = new TblMemberPaymentAlias();
                $model->load(Yii::$app->request->post());
                $data = Yii::$app->request->post();
                $model->dcs_code = !empty($data['selection']) ? $data['selection'] : $model->dcs_code;
                $searchModel = new TblMemberPaymentSummaryAliasSearch();
                $searchModel->attributes = $model->attributes;
                $dataProvider = $searchModel->search([]);
                $dataProvider->pagination = false;
                $negativeValCount = 0;
                $memberPaymentModel = new TblMemberPaymentAlias();
                $memberPaymentModel->attributes = $model->attributes;
                $negativeValCount = $memberPaymentModel->getNegativeValDcs();
                $negativeDcsCode = ArrayHelper::getColumn($negativeValCount, 'dcs_code');
                $model->dcs_code = json_encode($model->dcs_code);
                $dataProvider->query->orderBy('CASE WHEN (select COUNT(*) from tbl_member_payment_alias where tbl_member_payment_summary_alias.dcs_code=tbl_member_payment_alias.dcs_code and tbl_member_payment_summary_alias.payment_cycle_code=tbl_member_payment_alias.payment_cycle_code and final_amount < 0 ) > 0 THEN 0 ELSE 1 END');
                return $this->render('process_lock_dcs_payment_data', [
                            'model' => $model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'negativeValCount' => $negativeValCount,
                            'removeCheckBox' => true,
                            'negativeDcsCode' => $negativeDcsCode
                ]);
            }
        }
    }

    public function actionMemberPaymentAdjust() {
        // Farmer Payment Process: Process or Lock Data
        $model = new TblMemberPaymentAlias();
        $model->attributes = Yii::$app->request->get();

        $negativeValCount = 0;
        $memberPaymentModel = new TblMemberPaymentAlias();
        $memberPaymentModel->attributes = $model->attributes;
        $negativeValCount = $memberPaymentModel->getNegativeValCount();

        if (Yii::$app->request->post('TblMemberPaymentAlias')) {
            $postData = Yii::$app->request->post();
            $adjust_id = Yii::$app->request->post('TblMemberPaymentAlias')['member_payment_alias_code'];
            $adjust_amt = Yii::$app->request->post('TblMemberPaymentAlias')['additional_pay'];
            $adjust_remark = Yii::$app->request->post('TblMemberPaymentAlias')['adjust_remark'];
            $hold_amt = Yii::$app->request->post('TblMemberPaymentAlias')['hold_amount'];
            $reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['recovery'] : [];
            $adjust_reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery'] : [];
            $shortage_amt = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['shortage_amount']) ? Yii::$app->request->post('TblMemberPaymentAlias')['shortage_amount'] : [];
            $shortage_head = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['shortage_head_code']) ? Yii::$app->request->post('TblMemberPaymentAlias')['shortage_head_code'] : [];
            $hold_type = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['hold_type']) ? Yii::$app->request->post('TblMemberPaymentAlias')['hold_type'] : [];

            $save_model = [];
            $cnt = 0;
            $processFlag = 'Process'; //!empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'Process';
            //process_lock_flag

            $summaryModel = new TblMemberPaymentSummaryAlias();
            $summaryModel->attributes = Yii::$app->request->get();
            $summaryModelData = $summaryModel->getRecords(true);

            $adjustmentSummary = [];
            foreach ($adjust_id as $key => $value) {
                $data = TblMemberPaymentAlias::findOne($adjust_id[$key]);
                $oldData = $data->oldAttributes;
                $shortage = 0;
                $shortage_head_code = '';
                $holdAmount = !empty($hold_amt[$key]) ? $hold_amt[$key] : 0;
                $adjustAmount = !empty($adjust_amt[$key]) ? $adjust_amt[$key] : 0;
                $recovery = !empty($reco[$key]) ? $reco[$key] : 0;
                $adjust_recovery = !empty($adjust_reco[$key]) ? $adjust_reco[$key] : 0;
                $addition = !empty($data->total_addition) ? $data->total_addition : 0;
                $deduction = !empty($data->total_deduction) ? $data->total_deduction : 0;
                $dcsCode = $data->dcs_code;
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($data, $historyModel, UPDATE);

                if (!empty($shortage_head[$key])) {
                    $shortage_head_code = $shortage_head[$key];
                    $shortage_amt[$key] = empty($shortage_amt[$key]) ? 0 : $shortage_amt[$key];
                    $shortage = $shortage_amt[$key];
                    $shortage_model = TblMemberPaymentHead::find()
                                    ->where(['payment_cycle_code' => $data->payment_cycle_code, 'member_code' => $data->member_code, 'bill_head_code' => $shortage_head[$key], 'bmc_code' => $data->bmc_code, 'dcs_code' => $data->dcs_code])->one();
                    $old_shortage = 0;
                    if (!empty($shortage_model)) {
                        $old_shortage = $shortage_model->amount;
                        if ($shortage_model->amount != $shortage_amt[$key]) {
                            $shortage_model->amount = $shortage_amt[$key];
                            $save_model[] = $shortage_model;
                        }
                    } else if ($shortage > 0) {
                        $shortage_model = new TblMemberPaymentHead();
                        $shortage_model->member_code = $data->member_code;
                        $shortage_model->bmc_code = $data->bmc_code;
                        $shortage_model->dcs_code = $data->dcs_code;
                        $shortage_model->payment_cycle_code = $data->payment_cycle_code;
                        $shortage_model->bill_head_code = $shortage_head_code;
                        $shortage_model->bill_head_type = 1;
                        $shortage_model->amount = $shortage_amt[$key];
                        $shortage_model->payment_cycle_type = 'consecutive';
                        $shortage_model->is_hold = $shortage_model->is_skippable = $shortage_model->is_editable = 0;
                        $save_model[] = $shortage_model;
                    }
                    $data->total_deduction = number_format((float) $data->total_deduction + (float) $shortage - (float) $old_shortage, 2, '.', '');
                    $data->net_payable = number_format((float) $data->net_payable - (float) $shortage + (float) $old_shortage, 2, '.', '');
                }
                $data->additional_pay = $adjustAmount;
                $data->adjust_remark = $adjust_remark[$key]; //!empty($adjust_remark[$key]) ? $adjust_remark[$key] : '';
                $data->hold_amount = $holdAmount;
                $data->final_amount = number_format((float) $data->net_payable + (float) $adjustAmount - (float) $holdAmount + (float) $adjust_recovery - (float) $recovery, 2, '.', '');
                $data->payment_status = $processFlag;
                $data->hold_type = !empty($hold_type[$key]) ? $hold_type[$key] : 'next_payment';
                $save_model[] = $historyModel;
                $save_model[] = $data;
                if ($oldData['final_amount'] != $data->final_amount) {
                    $cnt++;
                }
                $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
                $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
                $adjustmentSummary[$dcsCode]['recovery'] = !empty($adjustmentSummary[$dcsCode]['recovery']) ? $adjustmentSummary[$dcsCode]['recovery'] + $recovery : $recovery;
                $adjustmentSummary[$dcsCode]['adjust_recovery'] = !empty($adjustmentSummary[$dcsCode]['adjust_recovery']) ? $adjustmentSummary[$dcsCode]['adjust_recovery'] + $adjust_recovery : $adjust_recovery;
                $adjustmentSummary[$dcsCode]['shortage'] = !empty($adjustmentSummary[$dcsCode]['shortage']) ? $adjustmentSummary[$dcsCode]['shortage'] + $shortage : $shortage;
                $adjustmentSummary[$dcsCode]['shortage_head_code'] = $shortage_head_code;

                if ($processFlag == 'Lock') {
                    $installmentModel = new TblMemberPaymentInstallment();
                    $existInstallment = $installmentModel->find()->where(['bmc_code' => $data->bmc_code, 'customer_type' => 'Member', 'customer_code' => $data->member_code, 'payment_cycle_code' => $data->payment_cycle_code])->all();
                    if (!empty($existInstallment)) {
                        foreach ($existInstallment as $data) {
                            $saleModel = new TblSaleInstallments();
                            $existData = $saleModel->find()->where(['product_sale_installment_code' => $data->product_sale_installment_code])->one();
                            $existData->installment_date = Yii::$app->general->getforeignkey($data->paymentCycleCode, 'from_date');
                            $save_model[] = $existData;
                        }
                    }
                }
            }
            $memberPaymentModel = new TblMemberPaymentAlias();
            $memberPaymentModel->attributes = Yii::$app->request->get();
            $memberPaymentModelData = []; //$memberPaymentModel->getExceptData($adjust_id);
            foreach ($memberPaymentModelData as $memberPayment) {
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($memberPayment, $historyModel, UPDATE);
                $memberPayment->payment_status = $processFlag;
                $save_model[] = $historyModel;
                $save_model[] = $memberPayment;
                $dcsCode = $memberPayment->dcs_code;
                $holdAmount = !empty($memberPayment->hold_amount) ? $memberPayment->hold_amount : 0;
                $adjustAmount = !empty($memberPayment->additional_pay) ? $memberPayment->additional_pay : 0;
                $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
                $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
                $adjustmentSummary[$dcsCode]['recovery'] = !empty($adjustmentSummary[$dcsCode]['recovery']) ? $adjustmentSummary[$dcsCode]['recovery'] + $recovery : $recovery;
                $adjustmentSummary[$dcsCode]['adjust_recovery'] = !empty($adjustmentSummary[$dcsCode]['adjust_recovery']) ? $adjustmentSummary[$dcsCode]['adjust_recovery'] + $adjust_recovery : $adjust_recovery;
            }

            foreach ($summaryModelData as $summaryData) {
                $historyModel = new TblMemberPaymentSummaryAliasHistory();
                Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                $summaryData->payment_status = $processFlag;
                $dcs = $summaryData->dcs_code;
                if (!empty($adjustmentSummary[$dcs])) {
                    if (!empty($adjustmentSummary[$dcs]['shortage_head_code'])) {
                        $old_shortage = 0;
                        $shortage_summary_model = TblMemberPaymentHeadSummary::find()
                                        ->where(['payment_cycle_code' => $summaryData->payment_cycle_code, 'dcs_code' => $summaryData->dcs_code, 'bill_head_code' => $adjustmentSummary[$dcs]['shortage_head_code'], 'bmc_code' => $summaryData->bmc_code])->one();
                        if (!empty($shortage_summary_model)) {
                            $old_shortage = $shortage_summary_model->amount;
                            if ($shortage_summary_model->amount != $adjustmentSummary[$dcs]['shortage']) {
                                $shortage_summary_model->amount = $adjustmentSummary[$dcs]['shortage'];
                                $save_model[] = $shortage_summary_model;
                            }
                        } else {
                            $shortage_summary_model = new TblMemberPaymentHeadSummary();
                            $shortage_summary_model->bmc_code = $summaryData->bmc_code;
                            $shortage_summary_model->dcs_code = $summaryData->dcs_code;
                            $shortage_summary_model->payment_cycle_code = $summaryData->payment_cycle_code;
                            $shortage_summary_model->bill_head_code = $adjustmentSummary[$dcs]['shortage_head_code'];
                            $shortage_summary_model->bill_head_type = 1;
                            $shortage_summary_model->amount = $adjustmentSummary[$dcs]['shortage'];
                            $shortage_summary_model->is_hold = 0;
                            $save_model[] = $shortage_summary_model;
                        }
                        $summaryData->total_deduction = number_format((float) $summaryData->total_deduction + (float) $adjustmentSummary[$dcs]['shortage'] - (float) $old_shortage, 2, '.', '');
                        $summaryData->net_payable = number_format((float) $summaryData->net_payable - (float) $adjustmentSummary[$dcs]['shortage'] + (float) $old_shortage, 2, '.', '');
                    }
                    $summaryData->additional_pay = $adjustmentSummary[$dcs]['adjustment'];
                    $summaryData->hold_amount = $adjustmentSummary[$dcs]['hold'];
                    $summaryData->recovery = $adjustmentSummary[$dcs]['recovery'];
                    $summaryData->adjust_recovery = $adjustmentSummary[$dcs]['adjust_recovery'];
                    $summaryData->final_amount = number_format((float) $summaryData->net_payable + (float) $adjustmentSummary[$dcs]['adjustment'] - (float) $adjustmentSummary[$dcs]['hold'] + (float) $adjustmentSummary[$dcs]['adjust_recovery'] - (float) $adjustmentSummary[$dcs]['recovery'], 2, '.', '');
                }
                $save_model[] = $historyModel;
                $save_model[] = $summaryData;
            }

            $successMsg = 'Payment of ' . $cnt . ' member adjusted succesfully';
            if ($processFlag == 'Lock') {
                $successMsg = Yii::t('app', 'Payment has been Locked Successfully');
            }
            $transaction = $this->generalModel->saveTransaction($save_model, [$successMsg, 'info']);
            if ($transaction == 'customRedirect') {
                $msg = '';
//                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $record = ['status' => 'success', 'msg' => $msg];
            } else {
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $record = ['status' => 'error', 'msg' => $msg];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
//            if ($transaction !== FALSE && $transaction != 'customRender') {
//                return $this->redirect(['index']);
//            }
        } else {
            $msg = ''; //Yii::$app->getSession()->getFlash('success')['message'];
            $record = ['status' => 'error', 'msg' => $msg];
            return Json::encode($record);
        }
//        $query = $model->getRecords();
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => FALSE,
//        ]);

        $aliasModel = new TblMemberPaymentAlias();
        $aliasModel->attributes = Yii::$app->request->get();
        return $this->render('member_payment_adjust', [
                    'model' => $model,
                    'aliasModel' => $aliasModel,
//                    'dataProvider' => $dataProvider,
                    'negativeValCount' => $negativeValCount
        ]);
    }

    public function actionMemberPaymentAdjustList() {
        $model = new TblMemberPaymentAlias();
        $negativeValCount = 0;
//        $memberPaymentModel = new TblMemberPaymentAlias();
//        $memberPaymentModel->attributes = $model->attributes;
//        $negativeValCount = $memberPaymentModel->getNegativeValCount();
//        $query = $model->getRecords();  
        if (!empty($_POST['payment_cycle_code']) && !empty($_POST['bmc_code']) && !empty($_POST['dcs_code'])) {
            $model->attributes = $_POST;
//            $memberPaymentModel = new TblMemberPaymentAlias();
//            $memberPaymentModel->attributes = $model->attributes;
//            $negativeValCount = $memberPaymentModel->getNegativeValCount();
//            $query = $model->getRecords();
            $spName = 'sp_portal_member_payment_alias_data';
            $spParam = [];
            $spParam[] = $_POST['payment_cycle_code'];
            $spParam[] = $_POST['union_code'];
            $spParam[] = $_POST['plant_code'];
            $spParam[] = $_POST['mcc_plant_code'];
            $spParam[] = $_POST['bmc_code'];
            $spParam[] = ',' . $_POST['dcs_code'] . ',';
            $dataProvider = \Yii::$app->general->getSpData($spName, $spParam);
            $aliasModel = new TblMemberPaymentAlias();
            $aliasModel->attributes = $_POST;
            return $this->renderAjax('member_payment_adjust_list', [
                        'model' => $model,
                        'aliasModel' => $aliasModel,
                        'dataProvider' => $dataProvider,
                        'negativeValCount' => $negativeValCount
            ]);
        }
    }

    private function getMemberDcsSpData($model, $reGenerate = 0, $stop_payment_only = 0) {
        // use for generate or regenerate data for Member Payment
        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
//        $data = [];
//        $data['from_datetime'] = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->from_date));
//        $data['to_datetime'] = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->to_date));
//        $data['union_code'] = $model->union_code;
//        $data['bmc_code'] = $model->bmc_code;
//        $data['payment_cycle_code'] = $model->payment_cycle_code;
//        $data['process_stop_payment'] = $stop_payment_only;
//        $data['user_code'] = $user;
//
//        return Yii::$app->ClientPaymentConfig->processPayment('member_payment', $data);
        $bmc_array = [];

        if (is_array($model->bmc_code)) {
            $bmc_array = $model->bmc_code;
        } else {
            $bmc_array[] = $model->bmc_code;
        }

        foreach ($bmc_array as $bmc) {
            $data = [];
            $data['from_datetime'] = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->from_date));
            $data['to_datetime'] = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->to_date));
            $data['union_code'] = $model->union_code;
            $data['bmc_code'] = $bmc;
            $data['payment_cycle_code'] = $model->payment_cycle_code;
            $data['process_stop_payment'] = $stop_payment_only;
            $data['user_code'] = $user;
            $result[] = Yii::$app->ClientPaymentConfig->processPayment('member_payment', $data);
        }
        return $result;
        /*
          $fromDate = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->from_date));
          $toDate = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->to_date));
          $spname = 'sp_member_dcs_payment_processing_data';
          $spParam = [];
          $spParam[] = $fromDate;
          $spParam[] = $toDate;
          $spParam[] = $model->union_code;
          //        $spParam[] = $model->plant_code;
          //        $spParam[] = $model->mcc_plant_code;
          $spParam[] = $model->bmc_code;
          $spParam[] = $model->payment_cycle_code;
          //        $spParam[] = $reGenerate;
          return \Yii::$app->general->getSpData($spname, $spParam, true);

         */
    }

    public function actionMemberPaymentDisburse() {
        //Farmer Payment Disburse : Step 1
        $model = new TblMemberPaymentAlias();
        $model->load(Yii::$app->request->get());
        $query = [];
        $searchModel = new TblMemberPaymentSummaryAliasSearch();
        $searchModel->attributes = $model->attributes;
        $searchModel->payment_status = 'Lock';
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $d = $dataProvider->getModels();
        $finalP = 0;
        $moduleCodes = [];
        foreach ($d as $p) {
            if (!in_array($p->mcc_plant_code, $moduleCodes)) {
                array_push($moduleCodes, $p->mcc_plant_code);
            }
            $pAmt = !empty($p->final_amount) ? $p->final_amount : 0;
            $finalP = $finalP + $pAmt;
        }

        //  $dataProvider->query->andWhere("(1=CASE WHEN (select COUNT(*) from tbl_member_payment_alias where tbl_member_payment_summary_alias.bmc_code=tbl_member_payment_alias.bmc_code and tbl_member_payment_summary_alias.payment_cycle_code=tbl_member_payment_alias.payment_cycle_code and payment_status != 'Lock' ) > 0 THEN 0 ELSE 1 END)");
//        return $this->render('process_lock_dcs_payment', [
//                    'model' => $model,
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider
//        ]);
        $union_bank = [];
        if (!empty($model->union_code)) {
            $is_bank_integrated = Yii::$app->general->getUnionConfiguration($model->union_code, 'is_bank_integrated', 'PORTAL') == 1 ? true : false;
            if ($is_bank_integrated) {
                // $union_bank = TblUnionBankPayment::find()->select(['union_bank_payment_code', 'bank_name'])->where(['union_code' => $model->union_code, 'is_active' => 1])->all();
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
        $memberPaymentModel = new TblMemberPaymentAlias();
        $memberPaymentModel->attributes = $model->attributes;
        $negativeValCount = $memberPaymentModel->getNegativeValCount();
        return $this->render('member_payment_disburse', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => 'Member Payment Disburse : Step 1',
                    'negativeValCount' => $negativeValCount,
                    'finalP' => $finalP,
                    'bank_show' => $union_bank
        ]);
    }

    public function actionPaymentMembersList($cycle, $dcs_code) {
        //Farmer Payment Disburse : Display member wise data
        $searchModel = new TblMemberPaymentAliasSearch();
        $searchModel->payment_cycle_code = $cycle;
        $searchModel->dcs_code = $dcs_code;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('member_list_index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDisburseMemberPayment() {
        if (Yii::$app->request->post()) {
            $model = new TblMemberPaymentAlias();
            $model->load(Yii::$app->request->post());
            $fileName = '';
            $union_bank = '';
            if (!empty($model->union_bank_payment_code)) {
                $union_bank = TblUnionBankPayment::find()->where(['union_code' => $model->union_code, 'is_active' => 1, 'union_bank_payment_code' => $model->union_bank_payment_code])->one();
            }
            if (!empty($model->union_code)) {
                $is_bank_integrated = Yii::$app->general->getUnionConfiguration($model->union_code, 'is_bank_integrated', 'PORTAL') == 1 ? true : false;
            }
            if ($is_bank_integrated && empty($union_bank)) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Please select Bank for disbursement.']);
                return $this->redirect(\yii\helpers\Url::previous());
            }
            if (!empty($model->payment_cycle_code)) {
                if (Yii::$app->request->post('flag') == 'member') {
                    if (false) {
                        $saveAllData = true;
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {

                            $summaryFields = ['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_count', 'payment_cycle_code', 'from_datetime', 'from_shift', 'to_datetime', 'to_shift', 'payment_cycle_applicabilty_code', 'qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'disburse_date', 'payment_date', 'payment_status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'additional_pay', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type', 'adjust_recovery', 'recovery'];
                            $summaryRecords = [];
                            $memberFields = ['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'payment_cycle_code', 'from_datetime', 'from_shift', 'to_datetime', 'to_shift', 'payment_cycle_applicabilty_code', 'qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'additional_pay', 'adjust_remark', 'disburse_date', 'payment_date', 'payment_status', 'approved_by', 'transfer_mode', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'is_verified', 'vsp_payment_reference_no', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type', 'adjust_recovery', 'recovery'];
                            $memberRecords = [];
                            $outStandFields = ['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'transaction_date', 'payment_cycle_code', 'hold_amount', 'due_amount', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'];
                            $outStandRecords = [];
                            $historyOutStandFields = ['member_outstanding_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'transaction_date', 'payment_cycle_code', 'hold_amount', 'due_amount', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by'];
                            $historyOutStandRecords = [];
                            $alertFields = ['receiver_detail', 'receiver_type', 'message', 'header_info', 'status', 'send_status', 'response_status', 'content_id', 'refecence_code', 'module_type', 'language_code', 'entry_datetime', 'pick_datetime', 'response_datetime', 'parent_code', 'has_attachment', 'filename', 'file_path', 'send_mail', 'created_by', 'activity_type', 'template_id'];
                            $alertRecords = [];
                            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                            $defaultCreateFields = [
                                'flg_sentbox_entry' => 'Y',
                                'sync_status' => 'U',
                                'created_by' => $user,
                                'created_at' => date('Y-m-d H:i:s'),
                                'originating_org_code' => \Yii::$app->session->get('organizations_code'),
                                'originating_org_type' => 'PORTAL',
                                'originating_type' => 0,
                            ];
                            $saveModel = [];
                            $deleteModel = [];
                            $summaryModel = new TblMemberPaymentSummaryAlias();
                            $summaryModelData = $summaryModel->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code])
                                    ->all();

                            if (!empty($summaryModelData)) {
                                $paymentCycleApplicabilitycode = $summaryModelData[0]->payment_cycle_applicabilty_code;
                                $payCycleModel = new TblPaymentCycleApplicability();
                                $payCycleModelData = $payCycleModel->findOne($paymentCycleApplicabilitycode);
                                if (!empty($payCycleModelData)) {
                                    $historyModel = new TblPaymentCycleApplicabilityHistory();
                                    Yii::$app->operation->history($payCycleModelData, $historyModel, UPDATE);
                                    //$save_model[] = $historyModel;
                                    $historyModel->save();
                                    $model->from_datetime = $payCycleModelData->from_date;
                                    $model->to_datetime = $payCycleModelData->to_date;
                                    $payCycleModelData->billing_lock_member = 1;
                                    //$save_model[] = $payCycleModelData;
                                    $payCycleModelData->save(false);
                                }
                            }

                            foreach ($summaryModelData as $summaryData) {
                                //$historyModel = new TblMemberPaymentSummaryAliasHistory();
                                //Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                                $mainModel = new TblMemberPaymentSummary();
                                $mainModel->attributes = $summaryData->attributes;
                                $mainModel->created_at = NULL;
                                $mainModel->created_by = NULL;
                                $mainModel->payment_status = 'Disburse';
                                $mainModel->payment_date = date('Y-m-d H:i:s');
                                $summaryRecord = [];
                                foreach ($summaryFields as $summaryField) {
                                    if ($mainModel->hasAttribute($summaryField)) {
                                        $summaryRecord[$summaryField] = $mainModel->$summaryField;
                                    } else {
                                        $summaryRecord[$summaryField] = null;
                                    }
                                }
                                $this->setDefaultFieldsArr($summaryRecord, $summaryFields, $mainModel, $defaultCreateFields);
                                $summaryRecords[] = $summaryRecord;
                                if (count($summaryRecords) >= 100) {
                                    \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment_summary', $summaryFields, $summaryRecords)->execute();
                                    $summaryRecords = [];
                                }
                                //$save_model[] = $mainModel;
                                //$save_model[] = $historyModel;
                                //$deleteModel[] = $summaryData;
                            }

                            $query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code])
                                    ->all();
                            $templateModel = new TblAlertTemplate();
                            $templateData = $templateModel->getTemplateData('member_payment', 'SMS', $model->union_code);
                            foreach ($query as $Data) {
                                //$historyModel = new TblMemberPaymentAliasHistory();
                                //Yii::$app->operation->history($Data, $historyModel, UPDATE);
                                $mainModel = new TblMemberPayment();
                                $mainModel->attributes = $Data->attributes;
                                $mainModel->created_at = NULL;
                                $mainModel->created_by = NULL;
                                $mainModel->payment_status = 'Disburse';
                                $mainModel->payment_date = date('Y-m-d H:i:s');

                                $memberRecord = [];
                                foreach ($memberFields as $memberField) {
                                    if ($mainModel->hasAttribute($memberField)) {
                                        $memberRecord[$memberField] = $mainModel->$memberField;
                                    } else {
                                        $memberRecord[$memberField] = null;
                                    }
                                }
                                $this->setDefaultFieldsArr($memberRecord, $memberFields, $mainModel, $defaultCreateFields);
                                $memberRecords[] = $memberRecord;
                                if (count($memberRecords) >= 100) {
                                    if (!empty($summaryRecords)) {
                                        \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment_summary', $summaryFields, $summaryRecords)->execute();
                                        $summaryRecords = [];
                                    }
                                    \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment', $memberFields, $memberRecords)->execute();
                                    $memberRecords = [];
                                }
                                //$save_model[] = $mainModel;
                                //$save_model[] = $historyModel;
                                //$deleteModel[] = $Data;



                                $outstanding = new TblMemberOutstanding();
                                $outstanding->attributes = $Data->attributes;
                                $outstanding->created_at = NULL;
                                $outstanding->created_by = NULL;
                                $outstandingData = $outstanding->getRecord();
                                if (!empty($outstandingData)) {
                                    $outstanding = $outstandingData;
                                    $oshistoryModel = new TblMemberOutstandingHistory();
                                    Yii::$app->operation->history($outstanding, $oshistoryModel, 'UPDATE');
                                    $historyOutStandRecord = [];
                                    foreach ($historyOutStandFields as $historyOutStandField) {
                                        if ($oshistoryModel->hasAttribute($historyOutStandField)) {
                                            $historyOutStandRecord[$historyOutStandField] = $oshistoryModel->$historyOutStandField;
                                        } else {
                                            $historyOutStandRecord[$historyOutStandField] = null;
                                        }
                                    }
                                    $this->setDefaultFieldsArr($historyOutStandRecord, $historyOutStandFields, $oshistoryModel, $defaultCreateFields);
                                    $historyOutStandRecords[] = $historyOutStandRecord;
                                    $outstanding->payment_cycle_code = $Data->payment_cycle_code;
                                    $outstanding->hold_amount = $Data->hold_amount;
                                    $outstanding->due_amount = $Data->additional_pay;
                                    $outstanding->transaction_date = date('Y-m-d');
                                    $outstanding->save(false);
                                    //$save_model[] = $oshistoryModel;
                                } else {
                                    $outstanding->payment_cycle_code = $Data->payment_cycle_code;
                                    $outstanding->hold_amount = $Data->hold_amount;
                                    $outstanding->due_amount = $Data->additional_pay;
                                    $outstanding->transaction_date = date('Y-m-d');
                                    $outStandRecord = [];
                                    foreach ($outStandFields as $outStandField) {
                                        if ($outstanding->hasAttribute($outStandField)) {
                                            $outStandRecord[$outStandField] = $outstanding->$outStandField;
                                        } else {
                                            $outStandRecord[$outStandField] = null;
                                        }
                                    }
                                    $this->setDefaultFieldsArr($outStandRecord, $outStandFields, $outstanding, $defaultCreateFields);
                                    $outStandRecords[] = $outStandRecord;
                                    if (count($outStandRecords) >= 100) {
                                        if (!empty($summaryRecords)) {
                                            \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment_summary', $summaryFields, $summaryRecords)->execute();
                                            $summaryRecords = [];
                                        }
                                        \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment', $memberFields, $memberRecords)->execute();
                                        $memberRecords = [];
                                        \Yii::$app->db->createCommand()->batchInsert('tbl_member_outstanding', $outStandFields, $outStandRecords)->execute();
                                        $outStandRecords = [];
                                        if (!empty($historyOutStandRecords)) {
                                            \Yii::$app->db->createCommand()->batchInsert('tbl_member_outstanding_history', $historyOutStandFields, $historyOutStandRecords)->execute();
                                            $historyOutStandRecords = [];
                                        }
                                    }
                                }
                                //$save_model[] = $outstanding;
                                //send sms

                                if (Yii::$app->session->get('eiplCode') == 'MMD') {
                                    $memberData = isset($Data->memberCode) && !empty($Data->memberCode) ? $Data->memberCode : [];
                                    $mobilNo = !empty($memberData->mobile_no) ? $memberData->mobile_no : ''; //Yii::$app->general->getforeignkey($Data->memberCode, 'mobile_no');
                                    $name = !empty($memberData->member_name) ? $memberData->member_name : ''; //Yii::$app->general->getforeignkey($Data->memberCode, 'member_name');
                                    $dcs_ex = Yii::$app->general->getmultiforeignkey($memberData, ['dcsCode'], 'dcs_code_ex');
                                    $mcc_ex = Yii::$app->general->getmultiforeignkey($memberData, ['dcsCode', 'mccPlantCode'], 'mcc_plant_code_ex');
                                    $ex_code = !empty($memberData->ex_member_code) ? $memberData->ex_member_code : ''; //Yii::$app->general->getforeignkey($Data->memberCode, 'ex_member_code');
                                    $f_date = Yii::$app->controls->view_date($Data->from_datetime);
                                    $t_date = Yii::$app->controls->view_date($Data->to_datetime);
                                    $t_date = Yii::$app->controls->view_date($Data->to_datetime);
                                    if (!empty($mobilNo)) {
                                        //$templateModel = new TblAlertTemplate();
                                        //$templateData = $templateModel->getTemplateData('member_payment', 'SMS', $Data->union_code);
                                        if (!empty($templateData)) {
                                            $arrFrom = array("{member_name}", "{mcc_code_ex}", "{dcs_code_ex}", "{member_code_ex}", "{from_date}", "{to_date}", "{qty}", "{amt}", "{deduction}", "{net_amount}");
                                            $arrTo = array(substr($name, 0, 10), $mcc_ex, $dcs_ex, $ex_code, $f_date, $t_date, $Data->qty, $Data->total_amount, $Data->total_deduction, $Data->net_payable);
                                            $word = $templateData->message;
                                            $message = str_replace($arrFrom, $arrTo, $word);

                                            $notificationmodel = new TblAlertNotification();
                                            $datetime = date('Y-m-d H:i:s');
                                            $notificationmodel->module_type = 'member_payment';
                                            $notificationmodel->content_id = !empty($templateData->api_master_id) ? $templateData->api_master_id : '1';
                                            $notificationmodel->receiver_detail = $mobilNo;
                                            $notificationmodel->receiver_type = 'SMS';
                                            $notificationmodel->message = $message;
                                            $notificationmodel->send_status = '0';
                                            $notificationmodel->entry_datetime = $datetime;
                                            $notificationmodel->pick_datetime = NULL;
                                            $notificationmodel->response_datetime = NULL;
                                            $notificationmodel->response_status = 0;
                                            $notificationmodel->template_id = $templateData->header_info;
                                            $alertRecord = [];
                                            foreach ($alertFields as $alertField) {
                                                if ($notificationmodel->hasAttribute($alertField)) {
                                                    $alertRecord[$alertField] = $notificationmodel->$alertField;
                                                } else {
                                                    $alertRecord[$alertField] = null;
                                                }
                                            }
                                            $this->setDefaultFieldsArr($alertRecord, $alertFields, $notificationmodel, $defaultCreateFields);
                                            $alertRecords[] = $alertRecord;
                                            if (count($alertRecords) >= 100) {
                                                if (!empty($summaryRecords)) {
                                                    \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment_summary', $summaryFields, $summaryRecords)->execute();
                                                    $summaryRecords = [];
                                                }
                                                if (!empty($memberRecords)) {
                                                    \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment', $memberFields, $memberRecords)->execute();
                                                    $memberRecords = [];
                                                }
                                                if (!empty($outStandRecords)) {
                                                    \Yii::$app->db->createCommand()->batchInsert('tbl_member_outstanding', $outStandFields, $outStandRecords)->execute();
                                                    $outStandRecords = [];
                                                }
                                                if (!empty($historyOutStandRecords)) {
                                                    \Yii::$app->db->createCommand()->batchInsert('tbl_member_outstanding_history', $historyOutStandFields, $historyOutStandRecords)->execute();
                                                    $historyOutStandRecords = [];
                                                }
                                                \Yii::$app->db->createCommand()->batchInsert('tbl_alert_notification', $alertFields, $alertRecords)->execute();
                                                $alertRecords = [];
                                            }
                                            //$save_model[] = $notificationmodel;
                                        }
                                    }
                                }
                            }
                            if (!empty($summaryRecords)) {
                                \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment_summary', $summaryFields, $summaryRecords)->execute();
                                $summaryRecords = [];
                            }
                            if (!empty($memberRecords)) {
                                \Yii::$app->db->createCommand()->batchInsert('tbl_member_payment', $memberFields, $memberRecords)->execute();
                                $memberRecords = [];
                            }
                            if (!empty($outStandRecords)) {
                                \Yii::$app->db->createCommand()->batchInsert('tbl_member_outstanding', $outStandFields, $outStandRecords)->execute();
                                $outStandRecords = [];
                            }
                            if (!empty($historyOutStandRecords)) {
                                \Yii::$app->db->createCommand()->batchInsert('tbl_member_outstanding_history', $historyOutStandFields, $historyOutStandRecords)->execute();
                                $historyOutStandRecords = [];
                            }
                            if (!empty($alertRecords)) {
                                \Yii::$app->db->createCommand()->batchInsert('tbl_alert_notification', $alertFields, $alertRecords)->execute();
                                $alertRecords = [];
                            }
                            //$query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code])
                            //        ->all();
                            //$summaryModelData = $summaryModel->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code])
                            //        ->all();
                            $summaryModel->deleteAll(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code]);
                            $model->deleteAll(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code]);
                            $transaction->commit();
                            //$transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $deleteModel, ['Member Payment Disburse', 'create']);
                        } catch (\yii\base\UserException $e) {
                            $transaction->rollback();
                            $saveAllData = false;
                            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                'message' => $e->getMessage()]);
                        } catch (\yii\db\Exception $e) {
                            $transaction->rollback();
                            $saveAllData = false;
                            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
                        }
                        //if ($transaction == 'customRedirect') {
                        if ($saveAllData) {
                            $msg_content = Yii::t('app', 'Member Payment Successfully Disbursed.');
                            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                                'message' => $msg_content]);
                            $param = [];
                            $param['from_datetime'] = $model->from_datetime;
                            $param['customer_type'] = 'MEMBER';
                            $param['bmc_code'] = $model->bmc_code;
                            Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
                            $this->redirect(['index']);
                        }
                    } else {

                        $payCycleModel = new TblPaymentCycle();
                        $payCycleModelData = $payCycleModel->findOne($model->payment_cycle_code);
                        if (!empty($payCycleModelData)) {
                            $model->from_datetime = $payCycleModelData->from_date;
                        } else {
                            $summaryModel = new TblMemberPaymentSummaryAlias();
                            $summaryModelData = $summaryModel->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code])
                                    ->all();
                            if (!empty($summaryModelData)) {
                                $paymentCycleApplicabilitycode = $summaryModelData[0]->payment_cycle_applicabilty_code;
                                $payCycleModel = new TblPaymentCycleApplicability();
                                $payCycleModelData = $payCycleModel->findOne($paymentCycleApplicabilitycode);
                                if (!empty($payCycleModelData)) {
                                    $model->from_datetime = $payCycleModelData->from_date;
                                }
                            }
                        }

                        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                        $originating_org_code = \Yii::$app->session->get('organizations_code');
                        //$param = [];
                        //$param['union_code'] = $model->union_code;
                        //$param['bmc_code'] = $model->bmc_code;
                        //$param['payment_cycle_code'] = $model->payment_cycle_code;
                        //$param['user_code'] = $user;
                        //$param['org_code'] = $originating_org_code;
                        //$param['org_type'] = 'PORTAL';
                        //$param['is_without_release'] = $model->payment_release_type;
                        //Yii::$app->ClientPaymentConfig->processPayment('member_payment_disburse', $param);
                        //$param = [];
                        //$param['from_datetime'] = $model->from_datetime;
                        //$param['customer_type'] = 'MEMBER';
                        //$param['bmc_code'] = $model->bmc_code;
                        //$param['user_code'] = $user;
                        //Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
                        $bmc_array = [];
                        if (is_array($model->bmc_code)) {
                            $bmc_array = $model->bmc_code;
                        } else {
                            $bmc_array[] = $model->bmc_code;
                        }
                        $unionBankPaymentCode = ($is_bank_integrated && !empty($union_bank)) ? $model->union_bank_payment_code : null;
                        foreach ($bmc_array as $bmc) {
                            $param = [];
                            $param['union_code'] = $model->union_code;
                            $param['bmc_code'] = $bmc;
                            $param['payment_cycle_code'] = $model->payment_cycle_code;
                            $param['user_code'] = $user;
                            $param['org_code'] = $originating_org_code;
                            $param['org_type'] = 'PORTAL';
                            $param['is_without_release'] = $model->payment_release_type;
                            $param['p_union_bank_payment_code'] = $unionBankPaymentCode;
                            Yii::$app->ClientPaymentConfig->processPayment('member_payment_disburse', $param);

                            $param = [];
                            $param['from_datetime'] = $model->from_datetime;
                            $param['customer_type'] = 'MEMBER';
                            $param['bmc_code'] = $bmc;
                            $param['user_code'] = $user;
                            Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
                        }
                        $msg_content = Yii::t('app', 'Member Payment Successfully Disbursed.');
                        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                            'message' => $msg_content]);
                        $this->redirect(['index']);
                    }
                } else {
                    if ($this->exportMemberCSV($model)) {
                        return $this->redirect(\yii\helpers\Url::previous());
                    }
                }
            }
        }
    }

    protected function exportMemberCSV($model) {
        //Export Member File from Disburse Screen
        $newModel = new TblMemberPaymentAlias();
        $query = $newModel->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'tbl_member_payment_alias.bmc_code' => $model->bmc_code])
                ->joinWith(['dcsCode', 'memberCode'])->joinWith(['memberCode.bankCode', 'memberCode.branchCode'])
                ->all();
        $extention = 'xls';
        $header = [
            'mime' => 'application/ms-excel',
            'extension' => $extention,
            'writer' => IOFactory::WRITER_XLSX,
        ];

        $fileName = "payment_disburse." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>" . Yii::t('app', 'Society Code') . "</td>";
        echo "<td>" . Yii::t('app', 'Code Ex.') . "</td>";
        echo "<td>" . Yii::t('app', 'Society') . "</td>";
        echo "<td>" . Yii::t('app', 'Member Code') . "</td>";
        echo "<td>" . Yii::t('app', 'Member Code Ex') . "</td>";
        echo "<td>" . Yii::t('app', 'Member Name') . "</td>";
        echo "<td>" . Yii::t('app', 'Account No') . "</td>";
        echo "<td>" . Yii::t('app', 'Bank') . "</td>";
        echo "<td>" . Yii::t('app', 'Branch') . "</td>";
        echo "<td>" . Yii::t('app', 'IFSC') . "</td>";
        echo "<td>" . Yii::t('app', 'KgFAT') . "</td>";
        echo "<td>" . Yii::t('app', 'KgSNF') . "</td>";
        echo "<td>" . Yii::t('app', 'Total Qty') . "</td>";
        echo "<td>" . Yii::t('app', 'Milk Amount(+)') . "</td>";
        echo "<td>" . Yii::t('app', 'Addition(+)') . "</td>";
        echo "<td>" . Yii::t('app', 'Deduction(-)') . "</td>";
        echo "<td>" . Yii::t('app', 'Previous Hold(+)') . "</td>";
        echo "<td>" . Yii::t('app', 'Previous Due(-)') . "</td>";
        echo "<td>" . Yii::t('app', 'Final Pay') . "</td>";
        echo "<td>" . Yii::t('app', 'Hold Amount(-)') . "</td>";
        echo "<td>" . Yii::t('app', 'Additional Pay(+)') . "</td>";
        echo "<td>" . Yii::t('app', 'Net Payable') . "</td>";
        echo "<td>" . Yii::t('app', 'Remarks') . "</td>";
        echo "</tr>";

        foreach ($query as $row) {
            if ($row->final_amount > 0) {
                echo "<tr>";
                $this->setVal($row->dcs_code);
                $this->setVal(Yii::$app->general->getforeignkey($row->dcsCode, 'dcs_code_ex'));
                $this->setVal(Yii::$app->general->getforeignkey($row->dcsCode, 'dcs_name'));
                $this->setVal($row->member_code);
                $this->setVal(Yii::$app->general->getforeignkey($row->memberCode, 'ex_member_code'));
                $this->setVal(Yii::$app->general->getforeignkey($row->memberCode, 'member_name'));
                $this->setVal($row->bank_account_no);
                $this->setVal($row->bank_name);
                $this->setVal($row->branch_name);
                $this->setVal($row->ifsc);
                $this->setVal($row->kg_fat);
                $this->setVal($row->kg_snf);
                $this->setVal($row->qty);
                $this->setVal($row->total_amount);
                $this->setVal($row->total_addition);
                $this->setVal($row->total_deduction);
                $this->setVal($row->previous_hold);
                $this->setVal($row->previous_due);
                $this->setVal($row->net_payable);
                $this->setVal($row->hold_amount);
                $this->setVal($row->additional_pay);
                $this->setVal($row->final_amount);
                $this->setVal($row->adjust_remark);
                echo "</tr>";
            }
        }
        echo "</table>";
        exit();

//        $header = [
//            'mime' => 'application/ms-excel',
//            'extension' => 'xls',
//            'writer' => 'Excel2007',
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
//        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Society Code');
//        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Code Ex.');
//        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Society');
//        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Member Code');
//        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Member Name');
//        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Account No');
//        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Bank');
//        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Branch');
//        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'IFSC');
//        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'KgFAT');
//        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'KgSNF');
//        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 'Total Qty');
//        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, 'Milk Amount(+)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, 'Addition(+)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, 'Deduction(-)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, 'Previous Hold(+)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, 'Previous Due(-)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, 'Final Pay');
//        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, 'Hold Amount(-)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, 'Additional Pay(+)');
//        $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, 'Net Payable');
//        $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, 'Remarks');
//        foreach ($query as $row) {
//            if ($row->final_amount > 0) {
//                $rowCount++;
//                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->dcs_code);
//                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, Yii::$app->general->getforeignkey($row->dcsCode, 'dcs_code_ex'));
//                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, Yii::$app->general->getforeignkey($row->dcsCode, 'dcs_name'));
//                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->member_code);
//                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, Yii::$app->general->getforeignkey($row->memberCode, 'member_name'));
//                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, '="' . $row->bank_account_no . '"');
//                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->bank_name);
//                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->branch_name);
//                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->ifsc);
//                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->kg_fat);
//                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row->kg_snf);
//                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row->qty);
//                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row->total_amount);
//                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row->total_addition);
//                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row->total_deduction);
//                $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row->previous_hold);
//                $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row->previous_due);
//                $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $row->net_payable); // Final Pay
//                $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $row->hold_amount);
//                $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $row->additional_pay);
//                $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $row->final_amount); //Net Payable
//                $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $row->adjust_remark);
//            }
//        }
//        $fileName = "payment_disburse." . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
//        header('Content-Disposition: attachment;filename=' . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
//        exit();
    }

    public function actionIndex() {
        $searchModel = new TblMemberPaymentSummarySearch();
//        $searchModel->scenario = 'search_dialog';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $dataProvider->pagination = false;

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView() {
        $model = new TblMemberPaymentSummary();
        $model->setAttributes(Yii::$app->request->get());
        $searchModel = new TblMemberPaymentSearch();
        if ($model->payment_status != 'Disburse') {
            $model = new TblMemberPaymentSummaryAlias();
            $model->setAttributes(Yii::$app->request->get());
            $searchModel = new TblMemberPaymentAliasSearch();
        }
        $modelData = $model->getData();
        if (!empty($modelData)) {
            $model = $modelData;
        }
        $searchModel->setAttributes(Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $headSearchModel = new TblMemberPaymentHeadSummarySearch();
        $headSearchModel->setAttributes(Yii::$app->request->get());
        $headDataProvider = $headSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'headSearchModel' => $headSearchModel,
                    'headDataProvider' => $headDataProvider,
        ]);
    }

    public function actionBillHead() {
        if (!empty($_POST['payment_cycle_code']) && !empty($_POST['bmc_code']) && !empty($_POST['dcs_code'])) {
            $searchModel = new TblMemberPaymentHeadSummarySearch();
            $searchModel->setAttributes(Yii::$app->request->post());
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->renderAjax('bill-head-view', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionMemberBillHead() {
        if (!empty($_POST['payment_cycle_code']) && !empty($_POST['bmc_code']) && !empty($_POST['dcs_code']) && !empty($_POST['member_code'])) {
            $searchModel = new TblMemberPaymentHeadSearch();
            $searchModel->setAttributes(Yii::$app->request->post());
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->renderAjax('member-bill-head-view', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'allow_update' => !empty($_POST['allow_update']) ? TRUE : FALSE
            ]);
        }
    }

    public function setVal($value) {
        if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
            echo "<td>" . $value . "</td>";
        } else {
            echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
        }
    }

    public function actionRecoveryAdjust() {
        $model = new TblMemberPaymentAlias();
        $model->attributes = Yii::$app->request->get();
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saveModel = [];
            $postData = Yii::$app->request->post()['TblMemberPaymentAlias'];
            $pkCode = $postData['member_payment_alias_code'];
            $adjust = $postData['adjust_recovery'];
            unset($postData['member_payment_alias_code']);
            unset($postData['adjust_recovery']);
            unset($postData['recovery_dcs']);
            $recoverModel = $model->find()->where(['member_payment_alias_code' => $pkCode])->one();
            if (!empty($recoverModel)) {
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($recoverModel, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
                $oldAdjustRecovery = !empty($recoverModel->oldAttributes['adjust_recovery']) ? $recoverModel->oldAttributes['adjust_recovery'] : 0;
                $recoverModel->adjust_recovery = $oldAdjustRecovery + $adjust;
                $saveModel[] = $recoverModel;
                foreach ($postData as $key => $value) {
                    $RecModel = new TblMemberPaymentAlias();
                    $modelData = $RecModel->find()->where(['member_payment_alias_code' => $key])->one();
                    $historyModel = new TblMemberPaymentAliasHistory();
                    Yii::$app->operation->history($modelData, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $oldRec = !empty($value['old_recovery']) ? $value['old_recovery'] : 0;
                    $newRec = !empty($value['recovery']) ? $value['recovery'] : 0;
                    $modelData->recovery = $oldRec + $newRec;
                    $modelData->final_amount = $modelData->final_amount - $newRec;
                    $saveModel[] = $modelData;
                    if (!empty($value['recovery'])) {
                        $recoveryModel = new TblMemberPaymentRecovery();
                        $recoveryModel->attributes = $modelData->attributes;
                        $recoveryModel->from_member_code = $modelData->member_code;
                        $recoveryModel->for_member_code = $recoverModel->member_code;

                        $existRecovery = $recoveryModel->gerRecovery();
                        if (!empty($existRecovery)) {
                            $rhistoryModel = new TblMemberPaymentRecoveryHistory();
                            Yii::$app->operation->history($existRecovery, $rhistoryModel, UPDATE);
                            $saveModel[] = $rhistoryModel;
                            $existRecovery->recovery_amount = $existRecovery->recovery_amount + $value['recovery'];
                            $saveModel[] = $existRecovery;
                        } else {
                            $recoveryModel->recovery_amount = $value['recovery'];
                            $saveModel[] = $recoveryModel;
                        }
                    }
                }
            }

            $transaction = $this->generalModel->saveTransaction($saveModel, ['Adjust Recovery', 'create']);
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
        $recoverMember = $model->getRecoverData();
        $aliasmodel = new TblMemberPaymentAlias();
        $aliasmodel->attributes = Yii::$app->request->get();
        $aliasmodel->adjust_recovery = Yii::$app->request->get()['adjust_recovery'];
        $aliasmodel->member_payment_alias_code = Yii::$app->request->get()['member_payment_alias_code'];
        $dcs = $model->getRecoverDcs();
        $recoverDcs = ArrayHelper::map($dcs, 'dcs_code', function ($dcs) {
                    return $dcs['dcs_name'] . '(' . $dcs['ref_code'] . ')';
                });
        return $this->renderAjax('_recovery', [
                    'recoverMember' => $recoverMember,
                    'aliasmodel' => $aliasmodel,
                    'recoverDcs' => $recoverDcs
        ]);
    }

    public function actionValidateTotalRecovery() {
        $response = [];
        $response['status'] = 'success';
        $response['recovery'] = '';
        $model = new TblMemberPaymentAlias();
        $model->attributes = Yii::$app->request->get();
        $model->adjust_recovery = Yii::$app->request->get()['adjust_recovery'];
        $model->member_payment_alias_code = Yii::$app->request->get()['member_payment_alias_code'];
        $recoverMember = $model->getMemberWiseData();
        if (!empty($recoverMember)) {
            $newrec = !empty($model->adjust_recovery) ? $model->adjust_recovery : 0;
            $oldRec = !empty($recoverMember->adjust_recovery) ? $recoverMember->adjust_recovery : 0;
            $totalRec = ($newrec) + ($oldRec);
            $totalRemain = $totalRec + ($recoverMember->final_amount);
            if (!empty($totalRemain) && $totalRemain > 0) {
                $mustRec = $oldRec + ($recoverMember->final_amount);
                $response['status'] = 'error';
                $response['recovery'] = abs($mustRec);
                $response['old_recovery'] = $oldRec;
            }
        }
        return Json::encode($response);
    }

    public function actionMemberInstallment() {
        $selectedCheckbox = [];
        $instModel = new TblMemberPaymentInstallment();
        $instModel->setAttributes(Yii::$app->request->get());
        $instModel->customer_code = Yii::$app->request->get()['member_code'];
        $existData = $instModel->getExistingData();
        foreach ($existData as $exist) {
            $selectedCheckbox[] = $exist->product_sale_installment_code;
        }
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post()['paymentData'];
            $netPay = Yii::$app->request->post()['netPay'];
            $aliasModel = new TblMemberPaymentAlias();
            $existAliasData = $aliasModel->getExistingData($instModel);
            if ($existAliasData->net_payable >= 0) {
                $saveModel = [];
                $deleteModel = [];
                $existAmount = 0;
                if (!empty($existData)) {
                    foreach ($existData as $delete) {
                        $historyModel = new TblMemberPaymentInstallmentHistory();
                        Yii::$app->operation->history($delete, $historyModel, 'DELETE');
                        $saveModel[] = $historyModel;
                        $deleteModel[] = $delete;
                        $existAmount = $existAmount + $delete->installment_amount;
                    }
                }
                $details = explode(',', $postData);

                $totalAmount = 0;
                if (!empty($details[0])) {
                    foreach ($details as $data) {
                        $save = explode('###', $data);
                        $instModel = new TblMemberPaymentInstallment();
                        $instModel->product_sale_installment_code = $save[0];
                        $instModel->payment_cycle_code = $save[1];
                        $instModel->bmc_code = $save[2];
                        $instModel->dcs_code = $save[3];
                        $instModel->customer_code = $save[4];
                        $instModel->customer_type = 'Member';
                        $instModel->main_amount = $save[5];
                        $instModel->installment_amount = $save[6];
                        $instModel->product_sale_code = Yii::$app->general->getforeignkey($instModel->saleInstallment, 'product_sale_code');
                        $instModel->installment_date = Yii::$app->general->getmultiforeignkey($instModel->saleInstallment, ['saleCode'], 'invoice_date');
                        $instModel->installment_date = Yii::$app->general->getmultiforeignkey($instModel->saleInstallment, ['saleCode'], 'invoice_date');
                        $instModel->mcc_plant_code = Yii::$app->general->getforeignkey($instModel->saleInstallment, 'mcc_plant_code');
                        $instModel->plant_code = Yii::$app->general->getforeignkey($instModel->saleInstallment, 'plant_code');
                        $instModel->union_code = Yii::$app->general->getforeignkey($instModel->saleInstallment, 'union_code');
                        $existData = $instModel->getExistingData();
                        $saveModel[] = $instModel;
//                $saleModel = new TblSaleInstallments();
//                $existData = $saleModel->find()->where(['product_sale_installment_code' => $instModel->product_sale_installment_code])->one();
//                $existData->installment_date = Yii::$app->general->getforeignkey($instModel->paymentCycleCode, 'from_date');
//                $saveModel[] = $existData;

                        $totalAmount = $totalAmount + $instModel->installment_amount;
                    }
                }

                //Alias table update
                if ((($existAmount + $existAliasData->net_payable) - $totalAmount ) >= 0) {

                    $aliashistoryModel = new TblMemberPaymentAliasHistory();
                    Yii::$app->operation->history($existAliasData, $aliashistoryModel, 'UPDATE');
                    $saveModel[] = $aliashistoryModel;

                    $existAliasData->total_deduction = $existAliasData->total_deduction - $existAmount + $totalAmount;
                    $existAliasData->final_amount = $existAmount + $existAliasData->final_amount - $totalAmount;
                    $existAliasData->net_payable = $existAmount + $existAliasData->net_payable - $totalAmount;

                    $saveModel[] = $existAliasData;

                    //Summary Alias table update
                    $summryModel = new TblMemberPaymentSummaryAlias();
                    $existSummaryData = $summryModel->getExistingData($instModel);

                    $summaryhistoryModel = new TblMemberPaymentSummaryAliasHistory();
                    Yii::$app->operation->history($existSummaryData, $summaryhistoryModel, 'UPDATE');
                    $saveModel[] = $summaryhistoryModel;

                    $existSummaryData->total_deduction = $existSummaryData->total_deduction - $existAmount + $totalAmount;
                    $existSummaryData->final_amount = $existAmount + $existSummaryData->final_amount - $totalAmount;
                    $existSummaryData->net_payable = $existAmount + $existSummaryData->net_payable - $totalAmount;
                    $saveModel[] = $existSummaryData;

                    $instModel = !empty($instModel) ? $instModel : $delete;
                    $union_code = !empty($instModel->union_code) ? $instModel->union_code : $delete->union_code;

                    // head table update
                    $pro_sale_head = TblBillHead::find()->where(['default_bill_head_code' => 6, 'bill_head_for' => 'MEMBER', 'union_code' => $union_code])->one();
                    $headSummary = new TblMemberPaymentHeadSummary();
                    $headSummaryModel = $headSummary::find()
                            ->where(['bmc_code' => $instModel->bmc_code, 'dcs_code' => $instModel->dcs_code, 'payment_cycle_code' => $instModel->payment_cycle_code, 'bill_head_code' => $pro_sale_head->bill_head_code])
                            ->one();
                    $headSummaryModel->amount = $headSummaryModel->amount - $existAmount + $totalAmount;
                    $saveModel[] = $headSummaryModel;

                    $memberHead = new TblMemberPaymentHead();
                    $memberHeadModel = $memberHead::find()
                            ->where(['bmc_code' => $instModel->bmc_code, 'dcs_code' => $instModel->dcs_code, 'member_code' => $instModel->customer_code, 'payment_cycle_code' => $instModel->payment_cycle_code, 'bill_head_code' => $pro_sale_head->bill_head_code])
                            ->one();
                    $memberHeadModel->amount = $memberHeadModel->amount - $existAmount + $totalAmount;
                    $saveModel[] = $memberHeadModel;

                    $response = [];
                    $response['status'] = 'error';
                    $response['message'] = 'error';
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Member Installment Deduction', 'create']);
                    if ($transaction == 'customRedirect') {
                        $response['status'] = 'success';
                    } else {
                        $response['message'] = 'error';
                    }
                } else {
                    $response['message'] = 'Installment recovery amount is more than Final Pay';
                }
            } else {
                $response['message'] = 'Final Pay must be postive';
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($response);
        }
        $searchModel = new TblMemberPaymentHeadSearch();
        $searchModel->setAttributes(Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $instalSearch = new TblSaleInstallmentsSearch();
        $instalSearch->setAttributes(Yii::$app->request->get());
        $idataProvider = $instalSearch->installsearch(Yii::$app->request->queryParams);
        $netPay = Yii::$app->request->get()['netPay'];
        $redirectUrl = [];
        $redirectUrl[] = 'member-installment';
        foreach (Yii::$app->request->get() as $key => $value) {
            $redirectUrl[$key] = $value;
        }
        return $this->renderAjax('_member_installment', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'instalSearch' => $instalSearch,
                    'idataProvider' => $idataProvider,
                    'selectedCheckbox' => $selectedCheckbox,
                    'netPay' => $netPay,
                    'redirectUrl' => $redirectUrl
        ]);
    }

    public function actionViewMemberInstallment() {
        $instalSearch = new TblSaleInstallmentsSearch();
        $instalSearch->setAttributes(Yii::$app->request->get());
        $idataProvider = $instalSearch->viewinstallsearch(Yii::$app->request->queryParams);

        return $this->renderAjax('_member_installment_view', [
                    'searchModel' => $instalSearch,
                    'dataProvider' => $idataProvider,
        ]);
    }

    public function setDefaultFieldsArr(&$setArr, $setArrFields, $modelCheck, $defaultCreateFields) {
        foreach ($defaultCreateFields as $k => $v) {
            if (in_array($k, $setArrFields) && $modelCheck->hasAttribute($k)) {
                $setArr[$k] = $v;
            }
        }
    }

    public function actionSkipHead() {
        if (Yii::$app->request->post() && !empty(Yii::$app->request->post()['TblMemberPaymentHead'])) {
            $head_detail = Yii::$app->request->post()['TblMemberPaymentHead'];
            $process = FALSE;
            $total_addition = 0;
            $total_deduction = 0;
            $saveModel = [];
            $deleteModel = [];
            foreach ($head_detail as $head) {
                if ($head['current_cycle'] != $head['payment_cycle_type']) {
                    $new_payment_cycle_type = explode('-', $head['payment_cycle_type'])[0];
                    $p_head = TblMemberPaymentHead::findOne($head['member_payment_head_code']);
                    if (!empty($p_head)) {
                        $process = TRUE;
                        $member_code = $p_head->member_code;
                        $dcs_code = $p_head->dcs_code;
                        $payment_cycle_code = $p_head->payment_cycle_code;
                        $from_date = date('Y-m-d', strtotime($p_head->paymentCycleCode->from_date));
                        $bill_head_inst = TblBillHeadInstallment::find()->where(['installment_date' => $from_date, 'customer_type' => 'MEMBER', 'bill_head_for' => 'MEMBER', 'customer_code' => $p_head->member_code, 'bill_head_code' => $p_head->bill_head_code])->all();
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

                        $dcs_head = TblMemberPaymentHeadSummary::find()->where(['payment_cycle_code' => $p_head->payment_cycle_code, 'dcs_code' => $p_head->dcs_code, 'bill_head_code' => $p_head->bill_head_code])->one();
                        $dcs_head->amount = $dcs_head->amount - $p_head->amount;
                        $saveModel[] = $dcs_head;
                    }
                }
            }
            if ($process) {
                $member_alias = TblMemberPaymentAlias::find()->where(['member_code' => $p_head->member_code, 'payment_cycle_code' => $p_head->payment_cycle_code])->one();
                $member_alias->total_addition = $member_alias->total_addition - $total_addition;
                $member_alias->total_deduction = $member_alias->total_deduction - $total_deduction;
                $member_alias->net_payable = $member_alias->net_payable + $total_deduction - $total_addition;
                $member_alias->final_amount = $member_alias->final_amount + $total_deduction - $total_addition;
                $saveModel[] = $member_alias;

                $dcs_summary = TblMemberPaymentSummaryAlias::find()->where(['payment_cycle_code' => $payment_cycle_code, 'dcs_code' => $dcs_code])->one();
                $dcs_summary->total_addition = $dcs_summary->total_addition - $total_addition;
                $dcs_summary->total_deduction = $dcs_summary->total_deduction - $total_deduction;
                $dcs_summary->net_payable = $dcs_summary->net_payable + $total_deduction - $total_addition;
                $dcs_summary->final_amount = $dcs_summary->final_amount + $total_deduction - $total_addition;
                $saveModel[] = $dcs_summary;

                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Member Payment Head', 'edit']);
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
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
    }

    public function actionValidateBankDetails() {
        if (Yii::$app->request->post()) {
            $model = new TblUnionBankPayment();
            $model->union_code = Yii::$app->request->post('union_code');
            $model->union_bank_payment_code = !empty(Yii::$app->request->post('union_bank_payment_code')) ? Yii::$app->request->post('union_bank_payment_code') : '';
            $modelData = $model->getUnionBankRecord();
            $bmcCode = Yii::$app->request->post('bmc_code');

            $type = !empty(Yii::$app->request->post('type')) ? Yii::$app->request->post('type') : 'Member';
//            !empty($bmcCode) && $bmcCode == '004' && 
            $msg_type = '';
            $msg = '';
            $is_send_otp = 'yes';
            $payment_disburse_with_workflow = Yii::$app->general->getUnionConfiguration($model->union_code, 'payment_disburse_with_workflow', 'PORTAL') == 1 ? true : false;
            if ($payment_disburse_with_workflow) {
                $is_send_otp = 'no';
            }
            if (!empty($bmcCode) && !empty($modelData) && (!empty($modelData->file_path) || $modelData->integration_mode == 'API') && !empty($modelData->mobile_no)) {

                try {
                    if (strtolower($type) == 'member') {
                        $paymentModel = new TblMemberPaymentAlias();
                        $paymentModel->payment_cycle_code = Yii::$app->request->post('payment_cycle_code');
                        $paymentModel->bmc_code = Yii::$app->request->post('bmc_code');
                        $paymentModelData = $paymentModel->getBmcWiseData();
                        $totalMemberCount = count($paymentModelData);
                        $hasBankDetailMemberCount = 0;
                        $hasVerifiedBankDetailMemberCount = 0;
                        $msg_type = 'Members';
                        foreach ($paymentModelData as $member) {
                            if (!empty($member->bank_account_no) && !empty($member->bank_code) && !empty($member->branch_code)) {
                                $hasBankDetailMemberCount++;
                            }
                            if (!empty($member->bank_account_no) && !empty($member->bank_code) && !empty($member->branch_code) && !empty($member->is_verified)) {
                                $hasVerifiedBankDetailMemberCount++;
                            }
                        }
                    } else if (strtolower($type) == 'vsp') {

                        $vspModel = new TblVspPayment();
                        $vspModel->union_code = Yii::$app->request->post('union_code');
                        $vspModel->payment_cycle_code = Yii::$app->request->post('payment_cycle_code');
                        $vspModel->bmc_code = Yii::$app->request->post('bmc_code');
                        $vspModelData = $vspModel->getVendorPaymentRecords();
                        $totalMemberCount = count($vspModelData);
                        $hasBankDetailMemberCount = 0;
                        $hasVerifiedBankDetailMemberCount = 0;
                        $msg_type = 'Vendors';
                        foreach ($vspModelData as $member) {
                            if (!empty($member->bank_account_no) && !empty($member->bank_code) && !empty($member->branch_code)) {
                                $hasBankDetailMemberCount++;
                            }
                            if (!empty($member->bank_account_no) && !empty($member->bank_code) && !empty($member->branch_code) && !empty($member->is_verified)) {
                                $hasVerifiedBankDetailMemberCount++;
                            }
                        }
                    }

                    if ($hasBankDetailMemberCount == $totalMemberCount && $hasVerifiedBankDetailMemberCount == $hasBankDetailMemberCount) {
                        $status = 'success';
                        $msg = '';
                    } else {
                        if ($hasBankDetailMemberCount < $totalMemberCount) {
                            $errorCount = $totalMemberCount - $hasBankDetailMemberCount;
                            $status = 'validate_member_bank_detail_confirmation';
                            $msg .= 'Bank Details not Available for ' . $errorCount . ' out of ' . $totalMemberCount . ' ' . $msg_type . ' . Are you sure you want to Continue?' . '<br>';
                        }
                        if ($hasBankDetailMemberCount > $hasVerifiedBankDetailMemberCount || $hasBankDetailMemberCount < $hasVerifiedBankDetailMemberCount) {
                            $errorCount = $hasBankDetailMemberCount - $hasVerifiedBankDetailMemberCount;
                            $status = 'validate_member_bank_detail_verification_confirmation';
                            $msg .= 'Bank Details not Verified for ' . $errorCount . ' out of ' . $hasBankDetailMemberCount . ' ' . $msg_type . '  .  Are you sure you want to Continue?' . '<br>';
                        }
                    }
                } catch (\Throwable $ex) {
                    $msg = 'SMS Service Not Availabe.';
                    $status = 'error';
                }
            } else {
                $status = 'allow_without_otp';
                $msg = '';
//                $status = 'error';
//                $msg = 'Bank intigration not yet done.';
            }


            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg, 'is_send_otp' => $is_send_otp]);
        }
    }

    public function actionSendOtp() {
        if (Yii::$app->request->post()) {
            $status = 'error';
            $msg = 'SMS Service Not Availabel.';
            try {
                Yii::$app->session->set('otp_id', NULL);
                $model = new TblUnionBankPayment();
                $model->union_code = Yii::$app->request->post('union_code');
                $model->union_bank_payment_code = !empty(Yii::$app->request->post('union_bank_payment_code')) ? Yii::$app->request->post('union_bank_payment_code') : '';
                $f_date = !empty(Yii::$app->request->post('from_date')) ? Yii::$app->request->post('from_date') : '';
                $t_date = !empty(Yii::$app->request->post('to_date')) ? Yii::$app->request->post('to_date') : '';
                $bmc = !empty(Yii::$app->request->post('bmc_name')) ? Yii::$app->request->post('bmc_name') : '';
                $amount = !empty(Yii::$app->request->post('amount')) ? Yii::$app->request->post('amount') : '';
                $from_date = !empty($f_date) ? date('d-m-Y', strtotime($f_date)) : '';
                $to_date = !empty($t_date) ? date('d-m-Y', strtotime($t_date)) : '';
                $payment = $from_date . ' to ' . $to_date;
                $bmcName = $bmc;
                $model = $model->getUnionBankRecord();
                $otp_model = new TblPaymentOtpVerification();
                $otp_model->attributes = $model->attributes;
                $otp_model->otp_code = rand(1000, 9999);
                if ($otp_model->save()) {
                    Yii::$app->session->set('otp_id', $otp_model->id);
//                    $mobile = '919879468958';
                    $mobile = '91' . $model->mobile_no;
//                            $message = 'Your OTP for Payment is ' . $otp_model->otp_code;
//                            Yii::$app->bsmartsms->sendSmsPOST($mobile, $message);

                    $templateModel = new TblAlertTemplate();
                    $templateData = $templateModel->getTemplateData('farmer_payment');

                    if (!empty($templateData)) {
                        $arrFrom = array("{OTP}", "{payment_cycle}", "{bmc}", "{name}", "{amount}");
                        $arrTo = array($otp_model->otp_code, $payment, $bmcName, 'farmers', $amount);
                        $word = $templateData->message;
                        $message = str_replace($arrFrom, $arrTo, $word);

                        $sms_data = [];
                        $sms_data['refecence_code'] = (string) $otp_model->id;
                        $sms_data['module_type'] = 'farmer_payment';
                        if (false && YII_ENV_DEV) {
                            //Yii::$app->general->saveAlertNotification($temp_model->mobile_no, $message, $sms_data, true, $templateData->header_info);
                        } else {
                            $contentId = !empty($templateData->api_master_id) ? $templateData->api_master_id : '1';
                            Yii::$app->general->saveAlertNotification($mobile, $message, $sms_data, true, $templateData->header_info, $contentId);
                        }
                    }
                    $status = 'success';
                    $msg = '';
                } else {
                    $status = 'error';
                    $msg = 'SMS Service Not Availabel.';
                }
            } catch (\Throwable $ex) {
                $msg = 'SMS Service Not Available.';
                $status = 'error';
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg]);
        }
    }

    public function actionVerifyOtp() {
        if (Yii::$app->session->get('otp_id') != NULL && Yii::$app->request->post()) {
            try {
                $msg = 'Please Enter valid otp.';
                $status = 'error';
                $otp_data = TblPaymentOtpVerification::findOne(Yii::$app->session->get('otp_id'));
                if ($otp_data->attempt < 3) {
                    $otp_data->attempt += 1;
                    if ($otp_data->otp_code == Yii::$app->request->post('otp_code')) {
                        $otp_data->status = 'Verified';
                        $status = 'success';
                    } else {
                        $otp_data->status = 'Notverified';
                        $status = 'error';
                        $msg = 'Please Enter valid otp.';
                    }
                    $otp_data->save();
                } else {
                    $status = 'error';
                    $msg = 'You have already attempted 3 time.';
                }
            } catch (\Throwable $ex) {
                $msg = 'An error occurred on the server while verify opt.';
                $status = 'error';
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg]);
        }
    }

    public function actionMemberPaymentImport() {
        
    }

    public function actionDraftPayment() {
        
    }

    public function actionFinalizePayment() {
        
    }

}
