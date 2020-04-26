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

/**
 * TblMemberPaymentController implements the CRUD actions for TblMemberPayment model.
 */
class TblMemberPaymentController extends \app\controllers\ChildController {

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
                $dcs_ai = 0;
                $member_ai = 0;
                return $this->redirect(['member-payment-adjust', 'union_code' => $model->union_code, 'payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code]);
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
            $save_model = [];
            $cnt = 0;
            $processFlag = !empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'Process';
            //process_lock_flag

            $summaryModel = new TblMemberPaymentSummaryAlias();
            $summaryModel->attributes = Yii::$app->request->get();
            $summaryModelData = $summaryModel->getRecords(false);

            $adjustmentSummary = [];
            foreach ($adjust_id as $key => $value) {
                $data = TblMemberPaymentAlias::findOne($adjust_id[$key]);
                $oldData = $data->oldAttributes;
                $holdAmount = !empty($hold_amt[$key]) ? $hold_amt[$key] : 0;
                $adjustAmount = !empty($adjust_amt[$key]) ? $adjust_amt[$key] : 0;
                $addition = !empty($data->total_addition) ? $data->total_addition : 0;
                $deduction = !empty($data->total_deduction) ? $data->total_deduction : 0;
                $dcsCode = $data->dcs_code;
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($data, $historyModel, UPDATE);
                $data->additional_pay = $adjustAmount;
                $data->adjust_remark = $adjust_remark[$key];
                $data->hold_amount = $holdAmount;
                $data->final_amount = $data->net_payable + $adjustAmount - $holdAmount;
                $data->payment_status = $processFlag;
                $save_model[] = $historyModel;
                $save_model[] = $data;
                if ($oldData['additional_pay'] != $data->additional_pay) {
                    $cnt++;
                }
                $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
                $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
            }

            $memberPaymentModel = new TblMemberPaymentAlias();
            $memberPaymentModel->attributes = Yii::$app->request->get();
            $memberPaymentModelData = $memberPaymentModel->getExceptData($adjust_id);
            foreach ($memberPaymentModelData as $memberPayment) {
                $historyModel = new TblMemberPaymentAliasHistory();
                Yii::$app->operation->history($memberPayment, $historyModel, UPDATE);
                $memberPayment->payment_status = $processFlag;
                $save_model[] = $historyModel;
                $save_model[] = $memberPayment;
                $holdAmount = !empty($memberPayment->hold_amount) ? $memberPayment->hold_amount : 0;
                $adjustAmount = !empty($memberPayment->additional_pay) ? $memberPayment->additional_pay : 0;
                $adjustmentSummary[$dcsCode]['adjustment'] = !empty($adjustmentSummary[$dcsCode]['adjustment']) ? $adjustmentSummary[$dcsCode]['adjustment'] + $adjustAmount : $adjustAmount;
                $adjustmentSummary[$dcsCode]['hold'] = !empty($adjustmentSummary[$dcsCode]['hold']) ? $adjustmentSummary[$dcsCode]['hold'] + $holdAmount : $holdAmount;
            }

            foreach ($summaryModelData as $summaryData) {
                $historyModel = new TblMemberPaymentSummaryAliasHistory();
                Yii::$app->operation->history($summaryData, $historyModel, UPDATE);
                $summaryData->payment_status = $processFlag;
                $dcs = $summaryData->dcs_code;
                if (!empty($adjustmentSummary[$dcs])) {
                    $summaryData->additional_pay = $adjustmentSummary[$dcs]['adjustment'];
                    $summaryData->hold_amount = $adjustmentSummary[$dcs]['hold'];
                    $summaryData->final_amount = $summaryData->net_payable + $adjustmentSummary[$dcs]['adjustment'] - $adjustmentSummary[$dcs]['hold'];
                }
                $save_model[] = $historyModel;
                $save_model[] = $summaryData;
            }

            $successMsg = 'Payment of ' . $cnt . ' member adjusted succesfully';
            if ($processFlag == 'Lock') {
                $successMsg = Yii::t('app', 'Payment has been Locked Successfully');
            }
            $transaction = $this->generalModel->saveTransaction($save_model, [$successMsg, 'info']);
            if ($transaction !== FALSE && $transaction != 'customRender') {
                return $this->redirect(['index']);
            }
        }
        $query = $model->getRecords();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $aliasModel = new TblMemberPaymentAlias();
        $aliasModel->attributes = Yii::$app->request->get();
        return $this->render('member_payment_adjust', [
                    'model' => $model,
                    'aliasModel' => $aliasModel,
                    'dataProvider' => $dataProvider,
                    'negativeValCount' => $negativeValCount
        ]);
    }

    private function getMemberDcsSpData($model, $reGenerate = 0) {
        // use for generate or regenerate data for Member Payment
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
                    }


                    $transaction = $this->generalModel->saveDeleteTransaction($save_model, [], $deleteModel, ['Member Payment Disburse', 'create']);
                    if ($transaction == 'customRedirect') {
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
        $header = [
            'mime' => 'application/csv',
            'extension' => 'csv',
            'writer' => 'CSV',
        ];

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getDefaultStyle()
                ->getNumberFormat()
                ->setFormatCode(
                        \PHPExcel_Style_NumberFormat::FORMAT_TEXT
        );
        $rowCount = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Society Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Code Ex.');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Society');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Member Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Member Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Account No');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Bank');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Branch');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'IFSC');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'KgFAT');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'KgSNF');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 'Total Qty');
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, 'Milk Amount(+)');
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, 'Addition(+)');
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, 'Deduction(-)');
        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, 'Previous Hold(+)');
        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, 'Previous Due(-)');
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, 'Final Pay');
        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, 'Hold Amount(-)');
        $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, 'Additional Pay(+)');
        $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, 'Net Payable');
        $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, 'Remarks');
        foreach ($query as $row) {
            if ($row->final_amount > 0) {
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->dcs_code);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, Yii::$app->general->getforeignkey($row->dcsCode, 'dcs_code_ex'));
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, Yii::$app->general->getforeignkey($row->dcsCode, 'dcs_name'));
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->member_code);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, Yii::$app->general->getforeignkey($row->memberCode, 'member_name'));
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, '="' . $row->bank_account_no . '"');
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->bank_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->branch_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->ifsc);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->kg_fat);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row->kg_snf);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row->qty);
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row->total_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row->total_addition);
                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row->total_deduction);
                $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row->previous_hold);
                $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row->previous_due);
                $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $row->net_payable); // Final Pay
                $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $row->hold_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $row->additional_pay);
                $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $row->final_amount); //Net Payable
                $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $row->adjust_remark);
            }
        }
        $fileName = "payment_disburse." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function actionIndex() {
        $searchModel = new TblMemberPaymentSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;

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

        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
