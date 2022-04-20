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
use PHPExcel;
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

/**
 * TblMemberPaymentController implements the CRUD actions for TblMemberPayment model.
 */
class TblMemberPaymentController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-total-recovery', 'member-payment-adjust-list', 'list-member-payment-summary-data'];

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

    public function actionListMemberPaymentSummary($reGenerate = 0) {
        // Farmer Payment Process : Step 2 (Display DCS Wise Data)
        if (Yii::$app->request->get()) {
            $model = new TblMemberPaymentAlias();
            $model->load(Yii::$app->request->get());
            if ($reGenerate == 1) {
                $query = $this->getMemberDcsSpData($model, $reGenerate);
            }
            $searchModel = new TblMemberPaymentSummaryAliasSearch();
            $searchModel->attributes = $model->attributes;
            $dataProvider = $searchModel->search([]);
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
//            $adjust_id = Yii::$app->request->post('TblMemberPaymentAlias')['member_payment_alias_code'];
//            $adjust_amt = Yii::$app->request->post('TblMemberPaymentAlias')['additional_pay'];
//            $adjust_remark = Yii::$app->request->post('TblMemberPaymentAlias')['adjust_remark'];
//            $hold_amt = Yii::$app->request->post('TblMemberPaymentAlias')['hold_amount'];
//            $reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['recovery'] : 0;
//            $adjust_reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery'] : 0;
            $save_model = [];
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
            $memberPaymentModelData = $memberPaymentModel->getRecords(false)->all();
            foreach ($memberPaymentModelData as $memberPayment) {
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($memberPayment, $historyModel, UPDATE);
                $memberPayment->payment_status = $processFlag;
                $save_model[] = $historyModel;
                $save_model[] = $memberPayment;
                $dcsCode = $memberPayment->dcs_code;
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

            foreach ($summaryModelData as $summaryData) {
                $historyModel = new TblMemberPaymentSummaryAliasHistory();
                Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                $summaryData->payment_status = $processFlag;
                $dcs = $summaryData->dcs_code;
                if (!empty($adjustmentSummary[$dcs])) {
                    $summaryData->additional_pay = $adjustmentSummary[$dcs]['adjustment'];
                    $summaryData->hold_amount = $adjustmentSummary[$dcs]['hold'];
                    $summaryData->recovery = $adjustmentSummary[$dcs]['recovery'];
                    $summaryData->adjust_recovery = $adjustmentSummary[$dcs]['adjust_recovery'];
                    $summaryData->final_amount = $summaryData->net_payable + $adjustmentSummary[$dcs]['adjustment'] - $adjustmentSummary[$dcs]['hold'] + $adjustmentSummary[$dcs]['adjust_recovery'] - $adjustmentSummary[$dcs]['recovery'];
                }
                $save_model[] = $historyModel;
                $save_model[] = $summaryData;
            }

            $successMsg = 'Payment adjusted succesfully';
            if ($processFlag == 'Lock') {
                $successMsg = Yii::t('app', 'Payment has been Locked Successfully');
            }
            $transaction = $this->generalModel->saveTransaction($save_model, [$successMsg, 'info']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
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
                /*  $model = new TblMemberPaymentAlias();
                  $model->load(Yii::$app->request->post());
                  $data = Yii::$app->request->post();
                  $model->dcs_code = !empty($data['selection']) ? $data['selection'] : $model->dcs_code;
                  $dcs_ai = 0;
                  $member_ai = 0;
                  return $this->redirect(['list-member-payment-summary-data', 'union_code' => $model->union_code, 'payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code]); */

                $model = new TblMemberPaymentAlias();
                $model->load(Yii::$app->request->post());
                $data = Yii::$app->request->post();
                $model->dcs_code = json_decode($model->dcs_code);
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
        return $this->redirect(\yii\helpers\Url::previous());
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
            $reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['recovery'] : 0;
            $adjust_reco = !empty(Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery']) ? Yii::$app->request->post('TblMemberPaymentAlias')['adjust_recovery'] : 0;
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
                $holdAmount = !empty($hold_amt[$key]) ? $hold_amt[$key] : 0;
                $adjustAmount = !empty($adjust_amt[$key]) ? $adjust_amt[$key] : 0;
                $recovery = !empty($reco[$key]) ? $reco[$key] : 0;
                $adjust_recovery = !empty($adjust_reco[$key]) ? $adjust_reco[$key] : 0;
                $addition = !empty($data->total_addition) ? $data->total_addition : 0;
                $deduction = !empty($data->total_deduction) ? $data->total_deduction : 0;
                $dcsCode = $data->dcs_code;
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($data, $historyModel, UPDATE);
                $data->additional_pay = $adjustAmount;
                $data->adjust_remark = $adjust_remark[$key]; //!empty($adjust_remark[$key]) ? $adjust_remark[$key] : '';
                $data->hold_amount = $holdAmount;
                $data->final_amount = $data->net_payable + $adjustAmount - $holdAmount + $adjust_recovery - $recovery;
                $data->payment_status = $processFlag;
                $save_model[] = $historyModel;
                $save_model[] = $data;
                if ($oldData['additional_pay'] != $data->additional_pay) {
                    $cnt++;
                }
                $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
                $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
                $adjustmentSummary[$dcsCode]['recovery'] = !empty($adjustmentSummary[$dcsCode]['recovery']) ? $adjustmentSummary[$dcsCode]['recovery'] + $recovery : $recovery;
                $adjustmentSummary[$dcsCode]['adjust_recovery'] = !empty($adjustmentSummary[$dcsCode]['adjust_recovery']) ? $adjustmentSummary[$dcsCode]['adjust_recovery'] + $adjust_recovery : $adjust_recovery;

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
                    $summaryData->additional_pay = $adjustmentSummary[$dcs]['adjustment'];
                    $summaryData->hold_amount = $adjustmentSummary[$dcs]['hold'];
                    $summaryData->recovery = $adjustmentSummary[$dcs]['recovery'];
                    $summaryData->adjust_recovery = $adjustmentSummary[$dcs]['adjust_recovery'];
                    $summaryData->final_amount = $summaryData->net_payable + $adjustmentSummary[$dcs]['adjustment'] - $adjustmentSummary[$dcs]['hold'] + $adjustmentSummary[$dcs]['adjust_recovery'] - $adjustmentSummary[$dcs]['recovery'];
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

    private function getMemberDcsSpData($model, $reGenerate = 0) {
        // use for generate or regenerate data for Member Payment

        $data = [];
        $data['from_datetime'] = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->from_date));
        $data['to_datetime'] = date('Y-m-d H:i:s', strtotime($model->paymentCycleCode->to_date));
        $data['union_code'] = $model->union_code;
        $data['bmc_code'] = $model->bmc_code;
        $data['payment_cycle_code'] = $model->payment_cycle_code;
        return Yii::$app->ClientPaymentConfig->processPayment('member_payment', $data);

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
//        return $this->render('process_lock_dcs_payment', [
//                    'model' => $model,
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider
//        ]);

        $memberPaymentModel = new TblMemberPaymentAlias();
        $memberPaymentModel->attributes = $model->attributes;
        $negativeValCount = $memberPaymentModel->getNegativeValCount();
        return $this->render('member_payment_disburse', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => 'Member Payment Disburse : Step 1',
                    'negativeValCount' => $negativeValCount
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
//            if (isset($_REQUEST['selection'])) {
            $model->load(Yii::$app->request->post());
            if (!empty($model->payment_cycle_code)) {
                if (Yii::$app->request->post('flag') == 'member') {
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
                            $save_model[] = $historyModel;
                            $model->from_datetime = $payCycleModelData->from_date;
                            $payCycleModelData->billing_lock_member = 1;
                            $save_model[] = $payCycleModelData;
                        }
                    }

                    foreach ($summaryModelData as $summaryData) {
                        $historyModel = new TblMemberPaymentSummaryAliasHistory();
                        Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                        $mainModel = new TblMemberPaymentSummary();
                        $mainModel->attributes = $summaryData->attributes;
                        $mainModel->created_at = NULL;
                        $mainModel->created_by = NULL;
                        $mainModel->payment_status = 'Disburse';
                        $mainModel->payment_date = date('Y-m-d H:i:s');
                        $save_model[] = $mainModel;
                        $save_model[] = $historyModel;
                        $deleteModel[] = $summaryData;
                    }

                    $query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code, 'payment_status' => ['Lock'], 'bmc_code' => $model->bmc_code])
                            ->all();
                    foreach ($query as $Data) {
                        $historyModel = new TblMemberPaymentAliasHistory();
                        Yii::$app->operation->history($Data, $historyModel, UPDATE);
                        $mainModel = new TblMemberPayment();
                        $mainModel->attributes = $Data->attributes;
                        $mainModel->created_at = NULL;
                        $mainModel->created_by = NULL;
                        $mainModel->payment_status = 'Disburse';
                        $mainModel->payment_date = date('Y-m-d H:i:s');
                        $save_model[] = $mainModel;
                        $save_model[] = $historyModel;
                        $deleteModel[] = $Data;



                        $outstanding = new TblMemberOutstanding();
                        $outstanding->attributes = $Data->attributes;
                        $outstanding->created_at = NULL;
                        $outstanding->created_by = NULL;
                        $outstandingData = $outstanding->getRecord();
                        if (!empty($outstandingData)) {
                            $outstanding = $outstandingData;
                            $oshistoryModel = new TblMemberOutstandingHistory();
                            Yii::$app->operation->history($outstanding, $oshistoryModel, UPDATE);
                            $save_model[] = $oshistoryModel;
                        }
                        $outstanding->payment_cycle_code = $Data->payment_cycle_code;
                        $outstanding->hold_amount = $Data->hold_amount;
                        $outstanding->due_amount = $Data->additional_pay;
                        $outstanding->transaction_date = date('Y-m-d');
                        $save_model[] = $outstanding;

                        //send sms

                        if (Yii::$app->session->get('eiplCode') == 'MMD') {
                            $mobilNo = Yii::$app->general->getforeignkey($Data->memberCode, 'mobile_no');
                            $name = Yii::$app->general->getforeignkey($Data->memberCode, 'member_name');
                            $dcs_ex = Yii::$app->general->getmultiforeignkey($Data->memberCode, ['dcsCode'], 'dcs_code_ex');
                            $mcc_ex = Yii::$app->general->getmultiforeignkey($Data->memberCode, ['dcsCode', 'mccPlantCode'], 'mcc_plant_code_ex');
                            $ex_code = Yii::$app->general->getforeignkey($Data->memberCode, 'ex_member_code');
                            $f_date = Yii::$app->controls->view_date($Data->from_datetime);
                            $t_date = Yii::$app->controls->view_date($Data->to_datetime);
                            $t_date = Yii::$app->controls->view_date($Data->to_datetime);
                            if (!empty($mobilNo)) {
                                $templateModel = new TblAlertTemplate();
                                $templateData = $templateModel->getTemplateData('member_payment', 'SMS', $Data->union_code);
                                if (!empty($templateData)) {
                                    $arrFrom = array("{member_name}", "{mcc_code_ex}", "{dcs_code_ex}", "{member_code_ex}", "{from_date}", "{to_date}", "{qty}", "{amt}", "{deduction}", "{net_amount}");
                                    $arrTo = array(substr($name, 0, 10), $mcc_ex, $dcs_ex, $ex_code, $f_date, $t_date, $Data->qty, $Data->total_amount, $Data->total_deduction, $Data->net_payable);
                                    $word = $templateData->message;
                                    $message = str_replace($arrFrom, $arrTo, $word);

                                    $notificationmodel = new TblAlertNotification();
                                    $datetime = date('Y-m-d H:i:s');
                                    $notificationmodel->module_type = 'member_payment';
                                    $notificationmodel->content_id = 1;
                                    $notificationmodel->receiver_detail = $mobilNo;
                                    $notificationmodel->receiver_type = 'SMS';
                                    $notificationmodel->message = $message;
                                    $notificationmodel->send_status = '0';
                                    $notificationmodel->entry_datetime = $datetime;
                                    $notificationmodel->pick_datetime = NULL;
                                    $notificationmodel->response_datetime = NULL;
                                    $notificationmodel->response_status = 0;
                                    $notificationmodel->template_id = $templateData->header_info;
                                    $save_model[] = $notificationmodel;
                                }
                            }
                        }
                    }

                    $transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $deleteModel, ['Member Payment Disburse', 'create']);
                    if ($transaction == 'customRedirect') {
                        $param = [];
                        $param['from_datetime'] = $model->from_datetime;
                        $param['customer_type'] = 'MEMBER';
                        $param['bmc_code'] = $model->bmc_code;
                        Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
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
            'writer' => 'Excel2007',
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
//        $objPHPExcel = new PHPExcel();
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
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
//        exit();
    }

    public function actionIndex() {
        $searchModel = new TblMemberPaymentSummarySearch();
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
        $recoverDcs = ArrayHelper::map($dcs, 'dcs_code', function($dcs) {
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
                //Alias table update
                $aliasModel = new TblMemberPaymentAlias();
                $existAliasData = $aliasModel->getExistingData($delete);

                $aliashistoryModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($existAliasData, $aliashistoryModel, 'UPDATE');
                $saveModel[] = $aliashistoryModel;

                $existAliasData->total_deduction = $existAliasData->total_deduction - $existAmount;
                $existAliasData->final_amount = $existAliasData->final_amount + $existAmount;
                $existAliasData->net_payable = $existAliasData->net_payable + $existAmount;
                $saveModel[] = $existAliasData;

                //Summary Alias table update
                $summryModel = new TblMemberPaymentSummaryAlias();
                $existSummaryData = $summryModel->getExistingData($delete);

                $summaryhistoryModel = new TblMemberPaymentSummaryAliasHistory();
                Yii::$app->operation->history($existSummaryData, $summaryhistoryModel, 'UPDATE');
                $saveModel[] = $summaryhistoryModel;

                $existSummaryData->total_deduction = $existSummaryData->total_deduction - $existAmount;
                $existSummaryData->final_amount = $existSummaryData->final_amount + $existAmount;
                $existSummaryData->net_payable = $existSummaryData->net_payable + $existAmount;
                $saveModel[] = $existSummaryData;
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

                //Alias table update
                $aliasModel = new TblMemberPaymentAlias();
                $existAliasData = $aliasModel->getExistingData($instModel);

                $aliashistoryModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($existAliasData, $aliashistoryModel, 'UPDATE');
                $saveModel[] = $aliashistoryModel;

                $existAliasData->total_deduction = $existAliasData->total_deduction - $existAmount + $totalAmount;
                $existAliasData->final_amount = $netPay - $totalAmount;
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
            }
            $response = [];
            $response['status'] = 'error';
            $response['message'] = 'error';
            $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Member Installment Deduction', 'create']);
            if ($transaction == 'customRedirect') {
                $response['status'] = 'success';
            } else {
                $response['message'] = 'error';
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

}
