<?php

namespace app\modules\misreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\misreports\models\ReportsModelOld;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use PHPExcel;
use app\modules\bkgprocess\models\TblFtpTxnLog;

/**
 * Default controller for the `JasperReports` module
 */
class DefaultController extends \app\controllers\ChildController {

    /**
     * Renders the index view for the module
     * @return string
     */
    private $data = [], $type = 'html', $output = '', $report = '', $dataProvider = '', $message = '', $fileDownloadArr = [];

    public function actionIndex() {
        $model = new ReportsModelOld();
        if ($this->report != '') {
            $client_code = \Yii::$app->session->get('eiplCode');
            if (!empty($client_code) && !empty($this->getLabels($this->report)[$client_code])) {
                $this->data = $this->getLabels($this->report)[$client_code];
            } else if (!empty($client_code) && !empty($this->getLabels($this->report)['EIPLCOMMON'])) {
                $this->data = $this->getLabels($this->report)['EIPLCOMMON'];
            } else {
                $this->data = $this->getLabels($this->report);
            }
            if (!empty($this->data['scenario'])) {
                $model->scenario = $this->data['scenario'];
            }
        }
        if (isset($this->data['bkg_export']) && (!isset($this->data['output_type']) && !User::canRoute('misreports/default/mis-live-report-generation'))) {
            $this->data['output_type'] = $model->output_type = 'BACKGROUND';
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
        $model->upload_ftp_file = '0';
        return $this->render('index', ['result' => $this->output, 'message' => $this->message, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider, 'fileDownloadArr' => $this->fileDownloadArr]);
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
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'WqReportSap';
            } else if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '2') {
                $this->report = 'SdReportSap';
            }
        }

        return $this->actionIndex();
    }

    public function actionDateShiftBmcCollection() {
        $this->report = 'DateBmcCollection';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'DateShiftBmcCollection';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapStatusReport() {
        $this->report = 'SapStatusReport';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'SapDetailedStatusReport';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapComparisionReport() {
        $this->report = 'SapComparisionReportDateWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
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
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
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

    public function actionMemberPayment() {
        $this->report = 'MemberPaymentDcsWise';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'MemberPaymentMemberWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionShiftReportNameWise() {
        $this->report = 'ShiftReportNameWise';
        return $this->actionIndex();
    }

    public function actionMemberMilkCollection() {
        $this->report = 'MemberMilkCollection';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'MemberMilkCollectionDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '2') {
                $this->report = 'MemberMilkCollectionConsolidate';
            }
        }
        return $this->actionIndex();
    }

    public function actionConsolidatedUnionMilkCollection() {
        $this->report = 'ConsolidatedUnionMilkCollection';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'ConsolidatedUnionMilkCollectionDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '2') {
                $this->report = 'ConsolidatedUnionMilkCollectionConsolidate';
            }
        }
        return $this->actionIndex();
    }

    public function actionDcsWiseMilkCollection() {
        $this->report = 'DcsWiseMilkCollection';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'DcsWiseMilkCollectionDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '2') {
                $this->report = 'DcsWiseMilkCollectionConsolidate';
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

    public function actionMemberMilkCollectionRegister() {
        $this->report = 'MemberMilkCollectionRegister';
        return $this->actionIndex();
    }

    public function actionDcsCollectionVsDispatchGraph() {
        $this->report = 'DcsCollectionVsDispatchGraph';
        return $this->actionIndex();
    }

    public function actionUnionWiseCollectionVsDispatch() {
        $this->report = 'UnionWiseCollectionVsDispatch';
        return $this->actionIndex();
    }

    public function actionCdaDateAndShiftWise() {
        $this->report = 'CdaDateAndShiftWise';
        return $this->actionIndex();
    }

    public function actionCdaDateWise() {
        $this->report = 'CdaDateWise';
        return $this->actionIndex();
    }

    public function actionCdaConsolidated() {
        $this->report = 'CdaConsolidated';
        return $this->actionIndex();
    }

    public function actionBmcCollectionConsolidated() {
        $this->report = 'BmcCollectionConsolidated';
        return $this->actionIndex();
    }

    public function actionVariationVillageWise() {
        $this->report = 'VariationVillageWise';
        return $this->actionIndex();
    }

    public function actionVariationPercentageWise() {
        $this->report = 'VariationPercentageWise';
        return $this->actionIndex();
    }

    public function actionAmcsSyncPending() {
        $this->report = 'AmcsSyncPending';
        return $this->actionIndex();
    }

    public function actionCpmilkSapReport() {
        $this->report = 'CPMemberReportSap';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'CPRmrdReportSap';
            }
        }
        return $this->actionIndex();
    }

    public function actionGprsDataReconciliation() {
        $this->report = 'GprsDataReconciliation';
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

    public function actionSapWqFile() {
        $this->report = 'SapWqFile';
        return $this->actionIndex();
    }

    public function actionSapReportExport() {
        $this->report = 'VmReportSapExport';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '1') {
                $this->report = 'WqReportSapExport';
            } else if (Yii::$app->request->queryParams['ReportsModelOld']['report_type'] == '2') {
                $this->report = 'SdReportSapExport';
            }
        }

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
            $controls[$value] = is_array($model->{$value}) ? ',' . implode(',', $model->{$value}) . ',' : $model->{$value};
        }
//        validateReport

        $validateReport = true;
        if (!empty($this->data['validateReport'])) {
            $sp_nameValidate = $this->data['validateReport'];
            $outputData = \Yii::$app->general->getSpData($sp_nameValidate, $controls);
            if (!empty($outputData) && count($outputData) > 0) {
                $validateReport = false;
                $validationMsg = '';
                $header = !empty($outputData[0]['errorHeader']) ? $outputData[0]['errorHeader'] : '';
                $validationMsg .= $header;
                foreach ($outputData as $key => $outputD) {
                    $errMsg = !empty($outputD['errorContent']) ? $outputD['errorContent'] : '';
                    $validationMsg .= '<br/>' . $errMsg;
                }
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $validationMsg]);
            }
        }
        if ($validateReport) {
            $sp_name = $this->data['sp_name'];
            if ($model->output_type != 'BACKGROUND') {
                $output = \Yii::$app->general->getSpData($sp_name, $controls);
            } else {
                $output = $this->RegisterReportRequest('mis', $this->data, $controls);
            }
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
                    $controls[$value] = is_array($model->{$value}) ? ',' . implode(',', $model->{$value}) . ',' : $model->{$value};
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

            $fileArray = [];
            if ($model->output_type != 'BACKGROUND' && !empty($output)) {
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
                if (!empty($this->data['sap_download'])) {
                    $downLoadArray = [];
                    foreach ($this->output as $detail) {
                        $plant = ($model->report_type == 1) ? 'Plant Code' : 'Plant';
                        if (!empty($detail[$plant]) && strtolower($detail[$plant]) != 'total') {
                            if (empty($downLoadArray[$detail[$plant]])) {
                                $downLoadArray[$detail[$plant]] = [];
                            }
                            $downLoadArray[$detail[$plant]][] = $detail;
                        }
                    }
                    foreach ($downLoadArray as $bmc => $download) {
                        $report_type = ($model->report_type == 0) ? Yii::t('app', 'VM') : (($model->report_type == 1) ? Yii::t('app', 'WQ') : Yii::t('app', 'SD'));
                        $title = $bmc . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($model->from_date)) . '_' . $model->from_shift;
                        if (isset(Yii::$app->request->queryParams['upload_ftp_file']) && Yii::$app->request->queryParams['upload_ftp_file'] == '1') {
                            $this->uploadFTPData($title, $download, $model, $bmc);
                        }
                        $this->downloadData($title, $download, $fileArray);
                    }
                    $this->fileDownloadArr = $fileArray;
                }
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
    }

    public function downloadData($title, $download, &$fileArray) {
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel2007',
        ];
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $skip_header = (isset($this->data['skip_header']) && $this->data['skip_header'] == TRUE) ? TRUE : FALSE;

        if ($skip_header) {
            $sheet->fromArray(
                    $download, // The data to set
                    NULL, // Array values with this value will not be set
                    'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
            );
        } else {
            $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];
            $sheet->fromArray(
                    $file_header, // The data to set
                    NULL, // Array values with this value will not be set
                    'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
            );
            $sheet->fromArray(
                    $download, // The data to set
                    NULL, // Array values with this value will not be set
                    'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
            );
        }
        $file_name = $title . '.' . 'xls';
        $path = Yii::$app->basePath . '/web/sap_data_files/';
        Yii::$app->general->checkDirectory($path);
        $fileArray[] = $file_name;
        $fileName = $path . '/' . $file_name;
        fopen($fileName, "w+");
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($fileName);
    }

    public function uploadFTPData($title, $output, $model, $bmc) {
        $data_array = [];
        if (empty($this->data['module_name'])) {
            $data_array['module_name'] = ($model->report_type == '1' ? 'TblBmcCollection' : 'TblMilkCollection');
        } else {
            $data_array['module_name'] = $this->data['module_name'];
        }
// tblmilkcollection_collection
        $data_array['module_code'] = $bmc;
        $data_array['mcc_plant_code'] = $bmc;
        $data_array['union_code'] = $model->union_code;
        $data_array['applicable_date'] = $model->from_date;
        $data_array['shift_code'] = $model->from_shift;
        $data_array['bmc_code'] = NULL;
        $data_array['from_date'] = $model->from_date;
        $data_array['to_date'] = $model->to_date;
//        if (in_array($bmc, ['7300', '7304', '7350'])) {
        $ftp_model = new TblFtpTxnLog();
        $ftp_model->exportData($data_array, $title, $output);
//        }
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
//                'param' => 'union_code,mcc_code:union_code,bmc_code,date:string:shift',
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_VMSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP VM Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                'sap_download' => true,
                'multiArray' => ['mcc_code', 'bmc_code'],
            ],
            'WqReportSap' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP WQ Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'export_title' => true,
                'message' => Yii::t('app', 'Sync of data is pending from device.'),
                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                'sap_download' => true,
                'validateReport' => 'rpt_MIS_WQSAPReport_validate',
                'multiArray' => ['mcc_code', 'bmc_code'],
            ],
            'SdReportSap' => [
                'EIPLCOMMON' => [
                    'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                    'sp_name' => 'rpt_MIS_SDSAPReport',
                    'sp_name2' => 'sp_checkDatacompleteness_TMPL',
                    'param2' => 'date:string:shift,union_code,mcc_code',
                    'scenario' => 'SapReport',
                    'title' => 'SAP SD Report',
                    'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                    'export_title' => true,
                    'message' => Yii::t('app', 'Data is incomplete, please check dashboard BMC Wise Data Receipt Status.'),
                    'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                    'sap_download' => true,
                    'multiArray' => ['mcc_code', 'bmc_code'],
                ],
                'ANANDA' => [
                    'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                    'sp_name' => 'rpt_MIS_SDSAPReport_Ananda',
                    'scenario' => 'SapReport',
                    'title' => 'SAP SD Report',
                    'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                    'export_title' => true,
                    'message' => Yii::t('app', 'Data is incomplete, please check dashboard BMC Wise Data Receipt Status.'),
                    'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                    'sap_download' => true,
                    'multiArray' => ['mcc_code', 'bmc_code'],
                    'module_name' => 'TblMilkCollection_Ananda',
                    'skip_header' => TRUE,
                ],
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,rate_type:static:rate_type,p_organization_type:static:p_organization_type,p_date:string',
                'sp_name' => 'sp_mis_rate_download_acknowledgement',
                'scenario' => 'RateAcknowledgement',
                'title' => '223 - Rate Acknowledgement',
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
            'ShiftReportNameWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,p_date:string:from_shift',
                'sp_name' => 'rpt_mis_shift_report_name_wise',
                'scenario' => 'ShiftReportNameWise',
                'title' => '103 - Shift Report (Name Wise)',
            ],
            'MemberMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_member_milk_colleciton_summary_date_shift_wise',
                'scenario' => 'MemberMilkCollection',
                'title' => '101 -Member Milk Collection',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberMilkCollectionDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_member_milk_colleciton_summary_date_wise',
                'scenario' => 'MemberMilkCollection',
                'title' => '101 -Member Milk Collection',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberMilkCollectionConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_member_milk_colleciton_summary_consolidated',
                'scenario' => 'MemberMilkCollection',
                'title' => '101 -Member Milk Collection',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ConsolidatedUnionMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_company_wise_collection_date_shift_wise',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ConsolidatedUnionMilkCollectionDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_company_wise_collection_date_wise',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ConsolidatedUnionMilkCollectionConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_company_wise_collection_consolidated',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsWiseMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_society_wise_milk_collection_date_shift_wise',
                'scenario' => 'DcsWiseMilkCollection',
                'title' => '102 - Society Wise Milk Collection',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsWiseMilkCollectionDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_society_wise_milk_collection_date_wise',
                'scenario' => 'DcsWiseMilkCollection',
                'title' => '102 - Society Wise Milk Collection',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsWiseMilkCollectionConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_society_wise_milk_collection_consolidated',
                'scenario' => 'DcsWiseMilkCollection',
                'title' => '102 - Society Wise Milk Collection',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberMilkCollectionRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift,member_type',
                'sp_name' => 'rpt_mis_member_collection_register',
                'scenario' => 'MemberMilkCollectionRegister',
                'title' => '104 - Member Milk Collection Register',
            ],
            'DcsCollectionVsDispatchGraph' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,route_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_milk_collection_vs_dispatch',
                'scenario' => 'CollectionVsDispatchGraph',
                'title' => '116 - Society Wise Collection vs Dispatch - Graph',
            ],
            'UnionWiseCollectionVsDispatch' => [
                'param' => 'union_code,route_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_company_wise_collection_vs_dispatch',
                'scenario' => 'UnionWiseCollectionVsDispatch',
                'title' => '108 - Company Wise Collection vs Dispatch',
            ],
            'CdaDateAndShiftWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_cda_date_shift',
                'scenario' => 'CdaDateAndShiftWise',
                'title' => '121 - CDA Date And Shift Wise',
            ],
            'CdaDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_cda_date_shift',
                'scenario' => 'CdaDateWise',
                'title' => '122 - CDA Date Wise',
            ],
            'CdaConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_cda_consolidated',
                'scenario' => 'CdaConsolidated',
                'title' => '123 - CDA Consolidated',
            ],
            'VariationVillageWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_variation_date_wise_without_milktype',
                'scenario' => 'VariationVillageWise',
                'title' => '307 - Village Wise Variation',
            ],
            'VariationPercentageWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_variation_date_wise_without_milktype_qtype',
                'scenario' => 'VariationPercentageWise',
                'title' => '308 - Percentage Wise Variation',
            ],
            'BmcCollectionConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'rpt_mis_bmc_collection_consolidated',
                'scenario' => 'BmcCollectionConsolidated',
                'title' => '124 - BMC Collection Consolidated',
            ],
            'AmcsSyncPending' => [
                'param' => 'p_organization_type:static:p_organization_type,union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_mis_sentbox_sync_pending_data',
                'scenario' => 'AmcsSyncPending',
                'title' => '224 - AMCS Sync Pending',
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
            'GprsDataReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_dpu_gprs_data_reconciliation',
                'scenario' => 'GprsDataReconciliation',
                'title' => '313 - DPU-GPRS Data Reconciliation',
            ],
            'DcsMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_mis_dcs_master_register',
                'scenario' => '',
                'title' => 'DCS Register',
                'to_decrypt' => ['phone_no', 'Phone No', 'pan_no', 'Pan No', 'upi_no', 'Upi No'],
                'removeExportType' => ['CSV']
            ],
            'MemberMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code',
                'sp_name' => 'sp_mis_member_master_register',
                'scenario' => '',
                'title' => 'Member Register',
                'to_decrypt' => ['pan_no', 'Pan No', 'dob', 'Dob'],
                'removeExportType' => ['CSV']
            ],
            'SapWqFile' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_wq_vrs_newasa',
                'scenario' => 'SapWqFile',
                'title' => 'SAP WQ File',
                'export_title' => true,
                'message' => Yii::t('app', 'Sync of data is pending from device.'),
                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                'sap_download' => true,
                'multiArray' => ['mcc_code', 'bmc_code'],
            ],
            'VmReportSapExport' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_VMSAPReport',
                'scenario' => 'SapReportExport',
                'title' => 'SAP VM Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE
            ],
            'WqReportSapExport' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'scenario' => 'SapReportExport',
                'title' => 'SAP WQ Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE
            ],
            'SdReportSapExport' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_SDSAPReport',
                'scenario' => 'SapReportExport',
                'title' => 'SAP SD Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ'), Yii::t('app', 'SD')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE
            ],
        ];
        return $label[$l];
    }

}
