<?php

namespace app\modules\misreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\misreports\models\ReportsModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `JasperReports` module
 */
class DefaultController extends \app\controllers\ChildController {

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
            }
        }
        return $this->render('index', ['result' => $this->output, 'message' => $this->message, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionFarmerPaymentReport() {
        $this->report = 'FarmerPaymentReport';
        return $this->actionIndex();
    }

    public function actionBmcShiftReport() {
        $this->report = 'BmcShiftReport';
        return $this->actionIndex();
    }

    public function actionBmcConsolidateReport() {
        $this->report = 'BmcConsolidateReport';
        return $this->actionIndex();
    }

    public function actionBmcSummaryReport() {
        $this->report = 'BmcSummaryReport';
        return $this->actionIndex();
    }

    public function actionSocietySummaryReport() {
        $this->report = 'SocietySummaryReport';
        return $this->actionIndex();
    }

    public function actionShiftWiseAutoManual() {
        $this->report = 'ShiftWiseAutoManual';
        return $this->actionIndex();
    }

    public function actionSapReport() {
        $this->report = 'VmReportSap';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'WqReportSap';
            } else if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'SdReportSap';
            }
        }
        return $this->actionIndex();
    }

    public function actionDateShiftBmcCollection() {
        $this->report = 'DateBmcCollection';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'DateShiftBmcCollection';
            }
        }
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

    public function actionEkoMilkCalibration() {
        $this->report = 'EkoMilkCalibration';
        return $this->actionIndex();
    }

    public function actionCalibrationFlag() {
        $this->report = 'CalibrationFlag';
        return $this->actionIndex();
    }

    public function actionCleaningFlagBmc() {
        $this->report = 'CleaningFlagBmc';
        return $this->actionIndex();
    }

    public function actionTotalMilkCollectionDateShift() {
        $this->report = 'TotalMilkCollectionDateShift';
        return $this->actionIndex();
    }

    public function actionRateApplicabilityDetails() {
        $this->report = 'RateApplicabilityDetails';
        return $this->actionIndex();
    }

    public function actionMemberCollectionDayWise() {
        $this->report = 'MemberCollectionDayWise';
        return $this->actionIndex();
    }

    public function actionMemberCollectionPassbook() {
        $this->report = 'MemberCollectionPassbook';
        return $this->actionIndex();
    }

    public function actionMemberCollectionPaymentcycleWise() {
        $this->report = 'MemberCollectionPaymentcycleWise';
        return $this->actionIndex();
    }

    public function actionMemberCollectionMonthWise() {
        $this->report = 'MemberCollectionMonthWise';
        return $this->actionIndex();
    }

    public function actionMemberCollectionSummary() {
        $this->report = 'MemberCollectionSummary';
        return $this->actionIndex();
    }

    public function actionMemberMobileAppDetail() {
        $this->report = 'MemberMobileAppDetail';
        return $this->actionIndex();
    }

    public function actionCollectionDataSummary() {
        $this->report = 'CollectionDataSummary';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'CollectionDataSummaryProductWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionMccShiftCrossTab() {
        $this->report = 'MccShiftCrossTab';
        return $this->actionIndex();
    }

    public function actionRateAcknowledgement() {
        $this->report = 'RateAcknowledgement';
        return $this->actionIndex();
    }

    public function actionVendorPayment() {
        $this->report = 'VendorPayment';
        return $this->actionIndex();
    }

    /* Jasper Call */

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
    }

    /* Reports Configuration */

    private function getLabels($l) {
        $label = [
            'FarmerPaymentReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_FarmerPaymentBankReport',
                'scenario' => '',
                'title' => 'Payment Data[Thirumala]',
                'download_only' => true,
                'download_type' => 'txt'
            ],
            'BmcShiftReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_BMCShiftReport',
                'scenario' => '',
                'title' => '216 - BMC Shift Report',
            ],
            'BmcConsolidateReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_BMCConsolidateReport',
                'scenario' => '',
                'title' => '217 - BMC Consolidate Report',
            ],
            'BmcSummaryReport' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_BMCSummaryReport',
                'scenario' => '',
                'title' => '218 - BMC Summary Report',
            ],
            'SocietySummaryReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_SocietySummaryReport',
                'scenario' => '',
                'title' => '219 - Society Summary Report',
            ],
            'VmReportSap' => [
                'param' => 'union_code,mcc_code:union_code,date:string:shift',
                'sp_name' => 'rpt_MIS_VMSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP VM Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true]
            ],
            'WqReportSap' => [
                'param' => 'union_code,mcc_code:union_code,date:string:shift',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP WQ Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
                'message' => Yii::t('app', 'Sync of data is pending from device.'),
                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true]
            ],
            'SdReportSap' => [
                'param' => 'union_code,mcc_code:union_code,date:string:shift',
                'sp_name' => 'rpt_MIS_SDSAPReport',
                'sp_name2' => 'sp_checkDatacompleteness_TMPL',
                'param2' => 'date:string:shift,union_code,mcc_code',
                'scenario' => 'SapReport',
                'title' => 'SAP SD Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
                'message' => Yii::t('app', 'Data is incomplete, please check dashboard BMC Wise Data Receipt Status.'),
                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true]
            ],
            'DateBmcCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_bmc_wise_soceity_collection_date_wise',
                'scenario' => 'BmcCollection',
                'title' => '220 - Date/Shift wise BMC Collection',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'DateShiftBmcCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_bmc_wise_society_collection_date_shift_wise',
                'scenario' => 'BmcCollection',
                'title' => '220 - Date/Shift wise BMC Collection',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ShiftWiseAutoManual' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_SocietyRawData',
                'scenario' => '',
                'title' => '221 - Shift Wise Auto Manual',
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
            'AnalyzerCleaningReview' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_analyzer_cleaning_review',
                'scenario' => 'AnalyzerCleaningReview',
                'title' => '501 - Analyzer Cleaning Review',
            ],
            'AnalyzerCleaningPendingActivity' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_analyzer_cleaning_pending_activity',
                'scenario' => 'AnalyzerCleaningPendingActivity',
                'title' => '502 - Analyzer Cleaning Pending Activity',
            ],
            'AnalyzerPcbReplacement' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_analyzer_pcb_replacement',
                'scenario' => 'AnalyzerPcbReplacement',
                'title' => '503 - Analyzer PCB Replacement',
            ],
            'CleaningFlag' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string',
                'sp_name' => 'sp_rpt_analyzer_cleaning_flag',
                'scenario' => 'CleaningFlag',
                'title' => '504 - Cleaning Flag Report',
            ],
            'EkoMilkCalibration' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string',
                'sp_name' => 'sp_rpt_eko_calibration',
                'scenario' => 'EkoMilkCalibration',
                'title' => '505 - Eko Milk Calibration',
            ],
            'CalibrationFlag' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,calibration_day:txt',
                'sp_name' => 'sp_rpt_procurment_calibration_flag',
                'scenario' => 'CalibrationFlag',
                'title' => '506 - Calibration Flag',
            ],
            'CleaningFlagBmc' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,calibration_day:txt',
                'sp_name' => 'sp_rpt_procurment_cleaning_flag',
                'scenario' => 'CleaningFlagBmc',
                'title' => '507- Cleaning Flag Bmc',
            ],
            'TotalMilkCollectionDateShift' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_total_milk_collection_date_shift',
                'scenario' => 'TotalMilkCollectionDateShift',
                'title' => '118 - MCC Wise Collection Summary',
            ],
            'RateApplicabilityDetails' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,p_date:string',
                'sp_name' => 'sp_mis_dcs_wise_rate_applicability_details',
                'scenario' => 'RateApplicabilityDetails',
                'title' => '222 - Rate Applicability Details',
            ],
            'MemberCollectionDayWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_app_eipl_v1_member_collection_day_wise_report',
                'scenario' => 'MemberCollectionDayWise',
                'title' => 'M02 - Member Collection Day Wise',
            ],
            'MemberCollectionPassbook' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_app_eipl_v1_member_collection_passbook',
                'scenario' => 'MemberCollectionPassbook',
                'title' => 'M01 - Member Collection Passbook',
            ],
            'MemberCollectionPaymentcycleWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,p_date:string',
                'sp_name' => 'sp_app_eipl_v1_member_collection_paymentcycle_wise_report',
                'scenario' => 'MemberCollectionPaymentcycleWise',
                'title' => 'M04 - Member Collection Payment Cycle Wise',
            ],
            'MemberCollectionMonthWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,p_date:string',
                'sp_name' => 'sp_app_eipl_v1_member_collection_monthwise_report',
                'scenario' => 'MemberCollectionMonthWise',
                'title' => 'M05 - Member Collection Month Wise',
            ],
            'MemberCollectionSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_app_eipl_v1_member_collection_summary',
                'scenario' => 'MemberCollectionSummary',
                'title' => 'M03 - Member Collection Summary',
            ],
            'MemberMobileAppDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_rpt_mis_member_app_detail',
                'scenario' => 'MemberMobileAppDetail',
                'title' => 'M06 - Member Application Detail',
            ],
            'CollectionDataSummary' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_mis_collection_data_summary_mccwise',
                'scenario' => 'CollectionDataSummary',
                'title' => '119 - Collection Data Summary',
                'report_type' => [Yii::t('app', 'MCC Wise'), Yii::t('app', 'Product Wise')],
            ],
            'CollectionDataSummaryProductWise' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_mis_collection_data_summary_productwise',
                'scenario' => 'CollectionDataSummary',
                'title' => '119 - Collection Data Summary',
                'report_type' => [Yii::t('app', 'MCC Wise'), Yii::t('app', 'Product Wise')],
            ],
            'MccShiftCrossTab' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_mis_mcc_shift_wise_dcs_count',
                'scenario' => 'MccShiftCrossTab',
                'title' => '120 - MCC-Shift Collection Count',
            ],
            'RateAcknowledgement' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,rate_type:static:rate_type,p_purchase_rate_code:rate_type,p_organization_type:static:p_organization_type',
                'sp_name' => 'sp_mis_rate_download_acknowledgement',
                'scenario' => 'RateAcknowledgement',
                'title' => '223 - Rate Acknowledgement',
            ],
            'VendorPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,payment_cycle_code',
                'sp_name' => 'sp_mis_vendor_payment',
                'scenario' => 'VendorPayment',
                'title' => '602 - Vendor Payment',
            ],
        ];
        return $label[$l];
    }

}
