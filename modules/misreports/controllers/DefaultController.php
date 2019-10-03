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
            if ((int) $second_output[0]['RecordCount'] > 0) {
                $this->message = 'Data is incomplete, please check dashboard BMC Wise Data Receipt Status.';
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_VMSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP VM Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
            ],
            'WqReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP WQ Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
            ],
            'SdReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_SDSAPReport',
                'sp_name2' => 'sp_checkDatacompleteness_TMPL',
                'param2' => 'from_date:string:from_shift,to_date:string:to_shift,union_code,bmc_code',
                'scenario' => 'SapReport',
                'title' => 'SAP SD Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
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
        ];
        return $label[$l];
    }

}
