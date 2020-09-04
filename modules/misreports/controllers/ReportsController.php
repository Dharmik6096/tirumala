<?php

namespace app\modules\misreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\misreports\models\ReportsModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use PHPExcel;

/**
 * Default controller for the `JasperReports` module
 */
class ReportsController extends \app\controllers\ChildController {

    /**
     * Renders the index view for the module
     * @return string
     */
    private $data = [], $type = 'html', $output = '', $report = '', $dataProvider = '', $message = '';

    public function actionIndex() {
        $model = new ReportsModel();
        if ($this->report != '') {
            $this->data = $this->getLabels($this->report);
            if (!empty($this->data['scenario'])) {
                $model->scenario = $this->data['scenario'];
            }
        }
        if ($model->load(Yii::$app->request->queryParams) && $model->validate()) {
            $this->LoadReport($model);
            if (empty($this->output)) {
                $this->output = Yii::t('app', 'No Data Available.');
            } else if (isset($this->data['export_file_name'])) {
                $title_data = array_merge($model->attributes, $this->output[0]);
                $export_file_name = $this->data['export_file_name'];
                foreach ($title_data as $k => $v) {
                    if ($k == 'from_date') {
                        $v = str_replace('-', '_', Yii::$app->controls->view_date($v));
                    }
                    $export_file_name = str_replace($k, $v, $export_file_name);
                }
                $this->data['export_file_name'] = $export_file_name;
            }
        }
        if (isset($this->data['export_file_name']) && empty($this->output)) {
            $this->data['export_file_name'] = $this->data['title'];
        }
        return $this->render('index', ['result' => $this->output, 'message' => $this->message, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionMemberDailyCollection() {
        $this->report = 'MemberPassbook';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberDailyCollection';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionDcsCollDateShiftSummary() {
        $this->report = 'DcsCollDateShiftSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'DcsCollDateWiseSummary';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'DcsCollectionConsolidate';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberCollectionShiftReport() {
        $this->report = 'MemberCollectionShiftReport';
        return $this->actionIndex();
    }

    public function actionMemberWiseMonthlyCollection() {
        $this->report = 'MemberWiseMonthlyCollection';
        return $this->actionIndex();
    }

    public function actionManualMilkEntryMemberDateShiftWise() {
        $this->report = 'ManualMilkEntryMemberDateShiftWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'ManualMilkEntryMemberDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'ManualMilkEntryMemberConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcCollectionShiftReport() {
        $this->report = 'BmcCollectionShiftReport';
        return $this->actionIndex();
    }

    public function actionBmcCollDateShiftWiseSummary() {
        $this->report = 'BmcCollDateShiftWiseSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcCollDateWiseSummary';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'BmcCollConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionUnionCollDateShiftWiseSummary() {
        $this->report = 'UnionCollDateShiftWiseSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'UnionCollDateWiseSummary';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'UnionCollConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberWiseSummary() {
        $this->report = 'MemberWiseSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberWiseProductWiseDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberWiseFromDateToDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '3') {
                $this->report = 'MemberWiseProductWiseFromDateToDateWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorWiseSummary() {
        $this->report = 'VendorWiseSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'VendorWiseProductWiseDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'VendorWiseConsolidated';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '3') {
                $this->report = 'VendorWiseProductWiseDateWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWiseSummary() {
        $this->report = 'BmcWiseSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcWiseProductWiseDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'BmcWiseConsolidated';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '3') {
                $this->report = 'BmcWiseProductWiseConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionUnionWiseSummary() {
        $this->report = 'UnionWiseSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'UnionWiseProductWiseDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'UnionWiseConsolidated';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '3') {
                $this->report = 'UnionWiseProductWiseConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWisePaymentCycleWise() {
        $this->report = 'BmcWisePaymentCycleWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'BMCWiseFromDateToDateSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionCompanyWisePaymentCycleWise() {
        $this->report = 'CompanyWisePaymentCycleWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'ComapanyWiseFromDateToDateSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPaymentCycleWiseBmcWise() {
        $this->report = 'VendorPaymentCycleWiseBmcWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcWiseVendorPaymentConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPaymentCycleWiseUnionWise() {
        $this->report = 'VendorPaymentCycleWiseUnionWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'CompanyWiseVendorPaymentConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionTotalPaymentCompanyWisePaymentCycleWise() {
        $this->report = 'TotalPaymentCompanyWisePaymentCycleWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'TotalPaymentCompanyWiseConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPaymentConsolidated() {
        $this->report = 'VendorPaymentConsolidated';
        return $this->actionIndex();
    }

    public function actionMemberCollectionPaymentCycleWise() {
        $this->report = 'MemberCollectionPaymentCycleWise';
        return $this->actionIndex();
    }

    public function actionUnionWiseCollVsDispatch() {
        $this->report = 'UnionWiseCollVsDispatch';
        return $this->actionIndex();
    }

    public function actionUnionWiseCollVsRecipt() {
        $this->report = 'UnionWiseCollVsRecipt';
        return $this->actionIndex();
    }

    public function actionUnionWiseDispatchVsRecipt() {
        $this->report = 'UnionWiseDispatchVsRecipt';
        return $this->actionIndex();
    }

    public function actionSocietyWiseCda() {
        $this->report = 'SocietyWiseCda';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'SocietyWiseCdaConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionGprsDataReconciliation() {
        $this->report = 'GprsDataReconciliation';
        return $this->actionIndex();
    }

    public function actionManualMilkEntrySocietyDateShiftWise() {
        $this->report = 'ManualMilkEntrySocietyDateShiftWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'ManualMilkEntrySocietyDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'ManualMilkEntrySocietyConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberWiseNoOfPaymentCycle() {
        $this->report = 'MemberWiseNoOfPaymentCycle';
        return $this->actionIndex();
    }

    public function actionDcsWiseFromDateToDateSummary() {
        $this->report = 'DCSWiseFromDateToDateSummary';
        return $this->actionIndex();
    }

    public function actionAnalyzerCleaningReview() {
        $this->report = 'AnalyzerCleaningReview';
        return $this->actionIndex();
    }

    public function actionAnalyzerCleaningPendingActivity() {
        $this->report = 'AnalyzerCleaningPendingActivity';
        return $this->actionIndex();
    }

    public function actionAnalyzerPcbReplacement() {
        $this->report = 'AnalyzerPcbReplacement';
        return $this->actionIndex();
    }

    public function actionCleaningFlag() {
        $this->report = 'CleaningFlag';
        return $this->actionIndex();
    }

    public function actionCleaningFlagBmc() {
        $this->report = 'CleaningFlagBmc';
        return $this->actionIndex();
    }

    public function actionEkoMilkCalibration() {
        $this->report = 'EkoMilkCalibration';
        return $this->actionIndex();
    }

    public function actionCalibrationFlag() {
        $this->report = 'CalibrationFlag';
        return $this->actionIndex();
    }

    public function actionDcsMaster() {
        $this->report = 'DcsMaster';
        return $this->actionIndex();
    }

    public function actionMemberMaster() {
        $this->report = 'MemberMaster';
        return $this->actionIndex();
    }

    public function actionSapStatusReport() {
        $this->report = 'SapStatusReport';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'SapDetailedStatusReport';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapComparisionReport() {
        $this->report = 'SapComparisionReportDateWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'SapComparisionReportDateShiftWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionDispatchVsReceipt() {
        $this->report = 'DispatchVsReceipt';
        return $this->actionIndex();
    }

    public function actionCpmilkSapReport() {
        $this->report = 'CPMemberReportSap';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'CPRmrdReportSap';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPayment() {
        $this->report = 'VendorPayment';
        return $this->actionIndex();
    }

    public function actionMemberPayment() {
        $this->report = 'MemberPaymentDcsWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberPaymentMemberWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorBankPayment() {
        $this->report = 'VendorBankPayment';
        return $this->actionIndex();
    }

    public function actionMemberBankPayment() {
        $this->report = 'MemberBankPayment';
        return $this->actionIndex();
    }

    public function actionMemberOutstandingDetail() {
        $this->report = 'MemberOutstandingDetail';
        return $this->actionIndex();
    }

    public function actionRateApplicabilityDetails() {
        $this->report = 'RateApplicabilityDetails';
        return $this->actionIndex();
    }

    public function actionRateAcknowledgement() {
        $this->report = 'RateAcknowledgement';
        return $this->actionIndex();
    }

    public function actionAmcsSyncPending() {
        $this->report = 'AmcsSyncPending';
        return $this->actionIndex();
    }

    public function actionLocationWiseAssetSummary() {
        $this->report = 'LocationWiseAssetSummary';
        return $this->actionIndex();
    }

    public function actionLocationWiseAssetDetail() {
        $this->report = 'LocationWiseAssetDetail';
        return $this->actionIndex();
    }

    public function actionLocationWiseAssetMovement() {
        $this->report = 'LocationWiseAssetMovement';
        return $this->actionIndex();
    }

    public function actionCustomerMaster() {
        $this->report = 'CustomerMaster';
        return $this->actionIndex();
    }

    /* MIS Call */

    private function LoadReport($model) {
        if (empty($model->union_code)) {
            $model->union_code = !empty(Yii::$app->session->get('organizations_code')) ? ',' . Yii::$app->session->get('organizations_code') . ',' : 0;
        }
        if (empty($model->plant_code)) {
            $model->plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
        }
        if (empty($model->mcc_code)) {
            $model->mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
        }
        if (empty($model->bmc_code)) {
            $model->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
        }
        if (empty($model->dcs_code)) {
            $model->dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
        }
        if (empty($model->customer_code)) {
            $model->customer_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
        }

        $controls = [];
        $param = explode(',', $this->data['param']);
        foreach ($param as $key => $value) {
            $value_array = explode(':', $value);
            $value = $value_array[0];
            if (isset($value_array[1]) && $value_array[1] == 'string') {
                $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                if (isset($value_array[2])) {
                    $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                    $model->{$value} .= ' ' . $shift . '.000';
                }
            }
            $controls[$value] = $model->{$value};
        }
        $sp_name = $this->data['sp_name'];
        $output = \Yii::$app->general->getSpData($sp_name, $controls);
        $this->output = $output;

        if (!empty($this->data['sp_name2'])) {
            $controls = [];
            $param = explode(',', $this->data['param2']);
            foreach ($param as $key => $value) {
                $value_array = explode(':', $value);
                $value = $value_array[0];
                if (isset($value_array[1]) && $value_array[1] == 'string') {
                    $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                    if (isset($value_array[2])) {
                        $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                        $model->{$value} .= ' ' . $shift . '.000';
                    }
                }
                $controls[$value] = $model->{$value};
            }
            $sp_name2 = $this->data['sp_name2'];
            $second_output = \Yii::$app->general->getSpData($sp_name2, $controls);
            $invalid = false;
            foreach ($second_output as $key => $value) {
                if ($value['Pending'] > 0) {
                    $invalid = true;
                    break;
                }
            }
            if ($invalid) {
                $this->message = !empty($this->data['message']) ? $this->data['message'] : '';
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $this->message]);
            }
        }

        if (!empty($output)) {
            $attr = '';
            $decryptParam = !empty($this->data['to_decrypt']) ? $this->data['to_decrypt'] : [];
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $this->dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }

        if (isset($this->data['download_only']) && $this->data['download_only'] == true && !empty($this->output)) {
            $content = '';
            foreach ($this->output as $dataline) {
                if (!empty($dataline['Dataline'])) {
                    $content .= $dataline['Dataline'] . PHP_EOL;
                }
            }
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=farmerpaymentreport.txt');
            header('Content-Length: ' . strlen($content));
            header('Content-Type: text/plain');
            echo $content;
        }
        if ($model->output_type == 'DOWNLOAD') {
            $this->downloadData();
        }
    }

    /* Reports Configuration */

    private function getLabels($l) {
        $label = [
//101
            'MemberDailyCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_day_wise_report',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberPassbook' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_passbook',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            //102
            'DcsCollDateShiftSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_date_shift_wise',
                'scenario' => 'DcsCollDateShiftSummary',
                'title' => '102 - Society Collection Date And Shift Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsCollDateWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_date_wise',
                'scenario' => 'DcsCollDateShiftSummary',
                'title' => '102 - Society Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsCollectionConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_consolidated',
                'scenario' => 'DcsCollDateShiftSummary',
                'title' => '102 - Society Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberCollectionShiftReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,date:string:shift',
                'sp_name' => 'sp_mis_society_collection_shift_report',
                'scenario' => 'MemberCollectionShiftReport',
                'title' => '103 - Member Collection Shift Report',
            ],
            'MemberCollectionPaymentCycleWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,date:string',
                'sp_name' => 'sp_mis_member_collection_paymentcycle_wise_report',
                'scenario' => 'MemberCollectionPaymentCycleWise',
                'title' => '104 - Member Collection Payment Cycle Wise',
            ],
            'MemberWiseMonthlyCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,date:string',
                'sp_name' => 'sp_mis_member_collection_monthwise_report',
                'scenario' => 'MemberWiseMonthlyCollection',
                'title' => '105 - Member Wise Monthly Collection',
            ],
            //106
            'ManualMilkEntryMemberDateShiftWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_member_date_shift_wise',
                'scenario' => 'ManualMilkEntryMemberDateShiftWise',
                'title' => '106 - Manual Milk Entry Member Date Shif wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntryMemberDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_member_date_wise',
                'scenario' => 'ManualMilkEntryMemberDateShiftWise',
                'title' => '106 - Manual Milk Entry Member Date wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntryMemberConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_member_consolidated',
                'scenario' => 'ManualMilkEntryMemberDateShiftWise',
                'title' => '106 - Manual Milk Entry Member Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            //107
            'ManualMilkEntrySocietyDateShiftWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_society_date_shift_wise',
                'scenario' => 'ManualMilkEntrySocietyDateShiftWise',
                'title' => '107 - Manual Milk Entry Society Date Shift wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntrySocietyDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_society_date_wise',
                'scenario' => 'ManualMilkEntrySocietyDateShiftWise',
                'title' => '107 - Manual Milk Entry Society Date wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntrySocietyConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_society_consolidated',
                'scenario' => 'ManualMilkEntrySocietyDateShiftWise',
                'title' => '107 - Manual Milk Entry Society Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollectionShiftReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_collection_shift_report',
                'scenario' => 'BmcCollectionShiftReport',
                'title' => '201 - BMC Collection Shift Report',
            ],
            //202
            'BmcCollDateShiftWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_wise_society_collection_date_shift_wise',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Date And Shift Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollDateWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_wise_soceity_collection_date_wise',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_collection_consolidated',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            //203
            'UnionCollDateShiftWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_collection_date_shift_wise',
                'scenario' => 'UnionCollDateShiftWiseSummary',
                'title' => '203 - Company Collection Date And Shift Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'UnionCollDateWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_collection_date_wise',
                'scenario' => 'UnionCollDateShiftWiseSummary',
                'title' => '203 - Company Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'UnionCollConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_collection_consolidated',
                'scenario' => 'UnionCollDateShiftWiseSummary',
                'title' => '203 - Company  Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'UnionWiseCollVsDispatch' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_collection_vs_dispatch',
                'scenario' => 'UnionWiseCollVsDispatch',
                'title' => '204 - Company Wise Collection Vs Dispatch',
            ],
            'UnionWiseCollVsRecipt' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_collection_vs_receipt',
                'scenario' => 'UnionWiseCollVsRecipt',
                'title' => '205 - Company Wise Collection Vs Recipt',
            ],
            'UnionWiseDispatchVsRecipt' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_dispatch_vs_receipt',
                'scenario' => 'UnionWiseDispatchVsRecipt',
                'title' => '206 - Company Wise Dispatch Vs Recipt',
            ],
            //207
            'SocietyWiseCda' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_shift',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'SocietyWiseCdaDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'SocietyWiseCdaConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_consolidated',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'GprsDataReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_dpu_gprs_data_reconciliation',
                'scenario' => 'GprsDataReconciliation',
                'title' => '208 - DPU-GPRS Data Reconciliation',
            ],
            //301
            'MemberWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_wise_summary',
                'scenario' => 'MemberWiseSummary',
                'title' => '301 - Member Wise Summary',
                'report_type' => [Yii::t('app', 'Member vs Date'), Yii::t('app', 'Member vs Product vs Date'), Yii::t('app', 'Member Vs Consolidated'), Yii::t('app', 'Member vs Product vs Consolidated')],
            ],
            'MemberWiseProductWiseDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_wise_product_wise_date_wise',
                'scenario' => 'MemberWiseSummary',
                'title' => '301 - Member Wise Product Wise Date Wise',
                'report_type' => [Yii::t('app', 'Member vs Date'), Yii::t('app', 'Member vs Product vs Date'), Yii::t('app', 'Member Vs Consolidated'), Yii::t('app', 'Member vs Product vs Consolidated')],
            ],
            'MemberWiseFromDateToDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_wise_from_date_to_date_wise',
                'scenario' => 'MemberWiseSummary',
                'title' => '301 - Member Wise Consolidated',
                'report_type' => [Yii::t('app', 'Member vs Date'), Yii::t('app', 'Member vs Product vs Date'), Yii::t('app', 'Member Vs Consolidated'), Yii::t('app', 'Member vs Product vs Consolidated')],
            ],
            'MemberWiseProductWiseFromDateToDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_wise_product_wise_from_date_to_date_wise',
                'scenario' => 'MemberWiseSummary',
                'title' => '301 - Member Wise Product Wise Consolidated',
                'report_type' => [Yii::t('app', 'Member Vs Date'), Yii::t('app', 'Member Vs Product vs Date'), Yii::t('app', 'Member Vs Consolidated'), Yii::t('app', 'Member Vs Product Vs Consolidated')],
            ],
            //302
            'VendorWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_wise_sumamry',
                'scenario' => 'VendorWiseSummary',
                'title' => '302 - Vendor Wise Summary',
                'report_type' => [Yii::t('app', 'Vendor vs Date'), Yii::t('app', 'Vendor vs Product vs Date'), Yii::t('app', 'Vendor vs Consolidated'), Yii::t('app', 'Vendor vs Product vs Consolidated')],
            ],
            'VendorWiseProductWiseDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_wise_product_wise_date_wise',
                'scenario' => 'VendorWiseSummary',
                'title' => '302 - Vendor Wise Product Wise Date Wise',
                'report_type' => [Yii::t('app', 'Vendor vs Date'), Yii::t('app', 'Vendor vs Product vs Date'), Yii::t('app', 'Vendor vs Consolidated'), Yii::t('app', 'Vendor vs Product vs Consolidated')],
            ],
            'VendorWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_wise_from_date_to_date_wise',
                'scenario' => 'VendorWiseSummary',
                'title' => '302 - Vendor Wise Consolidated',
                'report_type' => [Yii::t('app', 'Vendor vs Date'), Yii::t('app', 'Vendor vs Product vs Date'), Yii::t('app', 'Vendor vs Consolidated'), Yii::t('app', 'Vendor vs Product vs Consolidated')],
            ],
            'VendorWiseProductWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_wise_product_wise_from_date_to_date_wise',
                'scenario' => 'VendorWiseSummary',
                'title' => '302 - Vendor Wise Product Wise Consolidated',
                'report_type' => [Yii::t('app', 'Vendor vs Date'), Yii::t('app', 'Vendor vs Product vs Date'), Yii::t('app', 'Vendor vs Consolidated'), Yii::t('app', 'Vendor vs Product vs Consolidated')],
            ],
            //303
            'BmcWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_summary',
                'scenario' => 'BmcWiseSummary',
                'title' => '303 - BMC Wise Summary',
                'report_type' => [Yii::t('app', 'BMC vs Date'), Yii::t('app', 'BMC vs Product vs Date'), Yii::t('app', 'BMC vs Consolidated'), Yii::t('app', 'BMC vs Product vs Consolidated')],
            ],
            'BmcWiseProductWiseDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_product_wise_date_wise',
                'scenario' => 'BmcWiseSummary',
                'title' => '303 - BMC Wise Product Wise Date Wise',
                'report_type' => [Yii::t('app', 'BMC vs Date'), Yii::t('app', 'BMC vs Product vs Date'), Yii::t('app', 'BMC vs Consolidated'), Yii::t('app', 'BMC vs Product vs Consolidated')],
            ],
            'BmcWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_from_date_to_date_wise',
                'scenario' => 'BmcWiseSummary',
                'title' => '303 - BMC Wise Consolidated',
                'report_type' => [Yii::t('app', 'BMC vs Date'), Yii::t('app', 'BMC vs Product vs Date'), Yii::t('app', 'BMC vs Consolidated'), Yii::t('app', 'BMC vs Product vs Consolidated')],
            ],
            'BmcWiseProductWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_product_wise_from_date_to_date_wise',
                'scenario' => 'BmcWiseSummary',
                'title' => '303 - BMC Wise Product Wise Consolidated',
                'report_type' => [Yii::t('app', 'BMC vs Date'), Yii::t('app', 'BMC vs Product vs Date'), Yii::t('app', 'BMC vs Consolidated'), Yii::t('app', 'BMC vs Product vs Consolidated')],
            ],
            //304
            'UnionWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_union_wise_summary',
                'scenario' => 'UnionWiseSummary',
                'title' => '304 - Union Wise Summary',
                'report_type' => [Yii::t('app', 'Company vs Date'), Yii::t('app', 'Company vs Product vs Date'), Yii::t('app', 'Company vs Consolidated'), Yii::t('app', 'Company vs Product vs Consolidated')],
            ],
            'UnionWiseProductWiseDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_union_wise_product_wise_date_wise',
                'scenario' => 'UnionWiseSummary',
                'title' => '304 - Union Wise Product Wise Date Wise',
                'report_type' => [Yii::t('app', 'Company vs Date'), Yii::t('app', 'Company vs Product vs Date'), Yii::t('app', 'Company vs Consolidated'), Yii::t('app', 'Company vs Product vs Consolidated')],
            ],
            'UnionWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_union_wise_from_date_to_date_wise',
                'scenario' => 'UnionWiseSummary',
                'title' => '304 - Union Wise Consolidated',
                'report_type' => [Yii::t('app', 'Company vs Date'), Yii::t('app', 'Company vs Product vs Date'), Yii::t('app', 'Company vs Consolidated'), Yii::t('app', 'Company vs Product vs Consolidated')],
            ],
            'UnionWiseProductWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_union_wise_product_wise_from_date_to_date_wise',
                'scenario' => 'UnionWiseSummary',
                'title' => '304 - Union Wise Product Wise Consolidated',
                'report_type' => [Yii::t('app', 'Company vs Date'), Yii::t('app', 'Company vs Product vs Date'), Yii::t('app', 'Company vs Consolidated'), Yii::t('app', 'Company vs Product vs Consolidated')],
            ],
            'MemberWiseNoOfPaymentCycle' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,no_of_payment_cycle:txt',
                'sp_name' => 'sp_mis_member_wise_no_of_payment_cycle',
                'scenario' => 'MemberWiseNoOfPaymentCycle',
                'title' => '501 - Member Wise No. Of Payment Cycle',
            ],
            //502
            'BmcWisePaymentCycleWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_payemnt_cycle_wise',
                'scenario' => 'BmcWisePaymentCycleWise',
                'title' => '502 - BMC Member Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BMCWiseFromDateToDateSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_from_date_to_date_summary',
                'scenario' => 'BmcWisePaymentCycleWise',
                'title' => '502 - BMC Member Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'CompanyWisePaymentCycleWise' => [
                'param' => 'union_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_company_wise_payemnt_cycle_wise',
                'scenario' => 'CompanyWisePaymentCycleWise',
                'title' => '503 - Company Member Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ComapanyWiseFromDateToDateSummary' => [
                'param' => 'union_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_company_wise_from_date_to_date_summary',
                'scenario' => 'CompanyWisePaymentCycleWise',
                'title' => '503 - Comapany Wise From Date To Date Summary',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DCSWiseFromDateToDateSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_mpp_wise_from_date_to_date_summary',
                'scenario' => 'DCSWiseFromDateToDateSummary',
                'title' => '504 - DCS Wise From Date To Date Summary',
            ],
            'VendorPaymentConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_or_agent_from_date_to_date',
                'scenario' => 'VendorPaymentConsolidated',
                'title' => '505 - Vendor Payment Consolidated',
            ],
            'VendorPaymentCycleWiseBmcWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_agent_payment_cycle_wise_bmc_wise',
                'scenario' => 'VendorPaymentCycleWiseBmcWise',
                'title' => '506 - Vendor Payment Cycle Wise (BMC Wise)',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcWiseVendorPaymentConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_agent_payment_from_date_to_date_bmc_wise',
                'scenario' => 'VendorPaymentCycleWiseBmcWise',
                'title' => '506 - BMC Wise Vendor Payment Consolidated',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'VendorPaymentCycleWiseUnionWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_agent_payment_cycle_wise_company_wise',
                'scenario' => 'VendorPaymentCycleWiseUnionWise',
                'title' => '507 - Company Vendor Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'CompanyWiseVendorPaymentConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sahayak_agent_payment_from_date_to_date_company_wise',
                'scenario' => 'VendorPaymentCycleWiseUnionWise',
                'title' => '507 - Company Vendor Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'TotalPaymentCompanyWisePaymentCycleWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_total_payment_company_wise_payment_cycle_wise',
                'scenario' => 'TotalPaymentCompanyWisePaymentCycleWise',
                'title' => '508 - Company Total Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            'TotalPaymentCompanyWiseConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_total_payment_company_wise_from_date_to_date',
                'scenario' => 'TotalPaymentCompanyWisePaymentCycleWise',
                'title' => '508 - Company Total Payment',
                'report_type' => [Yii::t('app', 'Payment Cycle Wise'), Yii::t('app', 'Consolidated')],
            ],
            //Cleaning Calibration
            'AnalyzerCleaningReview' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_analyzer_cleaning_review',
                'scenario' => 'AnalyzerCleaningReview',
                'title' => '801 - Analyzer Cleaning Review',
            ],
            'AnalyzerCleaningPendingActivity' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_analyzer_cleaning_pending_activity',
                'scenario' => 'AnalyzerCleaningPendingActivity',
                'title' => '802 - Analyzer Cleaning Pending Activity',
            ],
            'AnalyzerPcbReplacement' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_analyzer_pcb_replacement',
                'scenario' => 'AnalyzerPcbReplacement',
                'title' => '803 - Analyzer PCB Replacement',
            ],
            'CleaningFlag' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,date:string',
                'sp_name' => 'sp_mis_analyzer_cleaning_flag',
                'scenario' => 'CleaningFlag',
                'title' => '804 - Cleaning Flag Report',
            ],
            'EkoMilkCalibration' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,date:string',
                'sp_name' => 'sp_mis_eko_calibration',
                'scenario' => 'EkoMilkCalibration',
                'title' => '805 - Eko Milk Calibration',
            ],
            'CalibrationFlag' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,date:string,calibration_day:txt',
                'sp_name' => 'sp_mis_procurment_calibration_flag',
                'scenario' => 'CalibrationFlag',
                'title' => '806 - Calibration Flag',
            ],
            'CleaningFlagBmc' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,date:string,calibration_day:txt',
                'sp_name' => 'sp_mis_procurment_cleaning_flag',
                'scenario' => 'CleaningFlagBmc',
                'title' => '807- Cleaning Flag Bmc',
            ],
            'DcsMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_mis_dcs_master_register',
                'scenario' => 'DcsMaster',
                'title' => 'DCS Register',
                'to_decrypt' => ['phone_no', 'Phone No', 'pan_no', 'Pan No', 'upi_no', 'Upi No'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
//                'output_type' => FALSE
            ],
            'MemberMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code',
                'sp_name' => 'sp_mis_member_master_register',
                'scenario' => 'MemberMaster',
                'title' => 'Member Register',
                'to_decrypt' => ['pan_no', 'Pan No', 'dob', 'Dob'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
//                'output_type' => FALSE
            ],
            'SapStatusReport' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_status_report',
                'scenario' => 'SapStatusReport',
                'title' => '401 - Status Report',
                'report_type' => [Yii::t('app', 'Date Shift Wise'), Yii::t('app', 'MCC and Date Shift Wise')],
            ],
            'SapDetailedStatusReport' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_detailed_status_report',
                'scenario' => 'SapStatusReport',
                'title' => '401 - Status Report',
                'report_type' => [Yii::t('app', 'Date Shift Wise'), Yii::t('app', 'MCC and Date Shift Wise')],
            ],
            'SapComparisionReportDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_comparision_date_wise',
                'scenario' => 'SapComparisionReport',
                'title' => '402 - Comparision Report',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'SapComparisionReportDateShiftWise' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_comparision_date_shift_wise',
                'scenario' => 'SapComparisionReport',
                'title' => '402 - Comparision Report',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'DispatchVsReceipt' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_dispatch_vs_receipt',
                'scenario' => 'DispatchVsReceipt',
                'title' => '403 - Dispatch vs Receipt Report',
            ],
            'CPMemberReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_rpt_cpmilk_member_collection',
                'scenario' => 'CPReportSap',
                'title' => '404 - SAP Data Export',
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'RMRD')],
                'export_file_name' => 'Plant_Code_VMCC_from_date_from_shift',
            ],
            'CPRmrdReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_rpt_cpmilk_rmrd_collection',
                'scenario' => 'CPReportSap',
                'title' => '404 - SAP Data Export',
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'RMRD')],
                'export_file_name' => 'Plant_Code_WQ_from_date_from_shift',
            ],
            'VendorPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_vendor_payment',
                'scenario' => 'VendorPayment',
                'title' => '602 - Vendor Payment',
            ],
            'MemberPaymentDcsWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_billing_dcs_wise',
                'scenario' => 'MemberPayment',
                'title' => '603 - Member Payment',
                'report_type' => [Yii::t('app', 'DCS Wise'), Yii::t('app', 'Member Wise')],
            ],
            'MemberPaymentMemberWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_billing_member_wise',
                'scenario' => 'MemberPayment',
                'title' => '603 - Member Payment',
                'report_type' => [Yii::t('app', 'DCS Wise'), Yii::t('app', 'Member Wise')],
            ],
            'VendorBankPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,payment_cycle_code,bank_type:static:bank_type',
                'sp_name' => 'sp_mis_vendor_bank_payment',
                'scenario' => 'VendorBankPayment',
                'title' => '606 - Vendor Bank Payment',
            ],
            'MemberBankPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,payment_cycle_code:default:dcs,bank_type:static:bank_type',
                'sp_name' => 'sp_mis_member_bank_payment',
                'scenario' => 'MemberBankPayment',
                'title' => '607 - Member Bank Payment',
            ],
            'MemberOutstandingDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_mis_member_outstanding_detail',
                'scenario' => 'MemberOutstandingDetail',
                'title' => '608 - Member Outstanding Detail',
            ],
            'RateApplicabilityDetails' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,p_date:string',
                'sp_name' => 'sp_mis_dcs_wise_rate_applicability_details',
                'scenario' => 'RateApplicabilityDetails',
                'title' => '901 - Rate Applicability Details',
            ],
            'RateAcknowledgement' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,rate_type:static:rate_type,p_organization_type:static:p_organization_type,p_date:string',
                'sp_name' => 'sp_mis_rate_download_acknowledgement',
                'scenario' => 'RateAcknowledgement',
                'title' => '902 - Rate Acknowledgement',
            ],
            'AmcsSyncPending' => [
                'param' => 'p_organization_type:static:p_organization_type,union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_mis_sentbox_sync_pending_data',
                'scenario' => 'AmcsSyncPending',
                'title' => '903 - AMCS Sync Pending',
            ],
            'LocationWiseAssetSummary' => [
                'param' => 'store_location_type,union_code,plant_code,mcc_code,bmc_code,dcs_code,asset_code:union_code,date:string',
                'sp_name' => 'sp_mis_location_wise_asset_summary',
                'scenario' => 'LocationWiseAssetSummary',
                'title' => '904 - Location Wise Asset Summary',
            ],
            'LocationWiseAssetDetail' => [
                'param' => 'store_location_type,union_code,plant_code,mcc_code,bmc_code,dcs_code,asset_code:union_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_location_wise_asset_details',
                'scenario' => 'LocationWiseAssetDetail',
                'title' => '905 - Location Wise Asset Detail',
            ],
            'LocationWiseAssetMovement' => [
                'param' => 'store_location_type,union_code,plant_code,mcc_code,bmc_code,dcs_code,asset_code:union_code,date:string,sap_code:store_location_type:plant_code:mcc_code:dcs_code,sr_no:txt',
                'sp_name' => 'sp_mis_location_wise_asset_movement_details',
                'scenario' => 'LocationWiseAssetMovement',
                'title' => '906 - Location Wise Asset Movement',
            ],
            'CustomerMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code',
                'sp_name' => 'sp_mis_customer_master_register',
                'scenario' => 'CustomerMaster',
                'title' => 'Customer Master Register',
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
//                'output_type' => FALSE
            ],
        ];
        return $label[$l];
    }

    public function downloadData() {
        $extention = !empty($this->data['extention']) ? $this->data['extention'] : 'xls';
        $header = [
            'mime' => 'application/ms-excel',
            'extension' => $extention,
            'writer' => 'Excel2007',
        ];

        $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
//        header('Content-Type: text/plain');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
//        header("Content-Type: application/xls");
//        header("Content-Disposition: attachment; filename={$fileName}");
//        header("Pragma: no-cache");
//        header("Expires: 0");
        $Output = $this->output;
        $schema_insert = '';
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($labelArray as $a) {
            echo "<td>" . $a . "</td>";
        }
        echo "</tr>";
        foreach ($Output as $row) {
            echo "<tr>";
            foreach ($labelArray as $a) {
                $dispData = '';
                if (isset($row[$a]) && $row[$a] != '' && $row[$a] != null) {
                    $dispData = $row[$a];
                }
                $value = !empty($dispData) ? (Yii::$app->general->decryptData($dispData) !== FALSE ? Yii::$app->general->decryptData($dispData) : $dispData) : (isset($dispData) && $dispData == 0 && $dispData != '' ? 0 : '');
                if (is_numeric($value) && (float) $value <= 100000000) {
                    echo "<td>" . $value . "</td>";
                } else {
                    echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        exit();


//        $header = [
//            'mime' => '	application/vnd.ms-excel',
//            'extension' => 'xls',
//            'writer' => 'Excel2007',
//        ];
//        $objPHPExcel = new PHPExcel();
//        $sheet = $objPHPExcel->getActiveSheet();
//        /* $objPHPExcel->getDefaultStyle()
//          ->getNumberFormat()
//          ->setFormatCode(
//          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
//          ); */
//        $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];
//        /* $file_header = array_map(function($file_header) {
//          return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
//          }, array_values($file_header)); */
//
//        $sheet->fromArray(
//                $file_header, // The data to set
//                NULL, // Array values with this value will not be set
//                'A1'         // Top left coordinate of the worksheet range where
////    we want to set these values (default is A1)
//        );
//        $sheet->fromArray(
//                $this->output, // The data to set
//                NULL, // Array values with this value will not be set
//                'A2'         // Top left coordinate of the worksheet range where
////    we want to set these values (default is A1)
//        );
//        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
//        header('Content-Disposition: attachment;filename=' . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
//        exit();
    }

}
