<?php

namespace app\modules\misreports\controllers;

use app\components\Worksheet;
use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\misreports\models\ReportsModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\modules\configuration\models\TblGenerateReportParam;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\modules\usermanagement\models\User;
use PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Default controller for the `JasperReports` module
 */
class ReportsController extends \app\controllers\ChildController {

    public $freeAccessActions = ['download-data-readonly'];

    /**
     * Renders the index view for the module
     * @return string
     */
    private $data = [], $type = 'html', $output = '', $report = '', $dataProvider = '', $message = '', $label = '', $fileDownloadArr = [];

    public function actionIndex() {
        $model = new ReportsModel();
        if ($this->report != '') {
            $this->data = $this->getLabels($this->report);
            if (!empty($this->data['scenario'])) {
                $model->scenario = $this->data['scenario'];
            }
        }
        if (isset($this->data['bkg_export']) && (!isset($this->data['output_type']) && !User::canRoute('misreports/reports/mis-live-report-generation'))) {
            $this->data['output_type'] = $model->output_type = 'BACKGROUND';
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->LoadReport($model);
            if (empty($this->output)) {

                $this->output = Yii::t('app', 'No Data Available.');
            } else if (isset($this->data['export_file_name']) && is_array($this->output)) {

                $title_data = array_merge($model->attributes, $this->output[0]);
                $export_file_name = $this->data['export_file_name'];
                foreach ($title_data as $k => $v) {
                    if ($k == 'from_date') {
                        $v = str_replace('-', '_', Yii::$app->controls->view_date($v));
                    }
                    if (!is_array($v) && !empty($v)) {
                        $export_file_name = str_replace($k, $v, $export_file_name);
                    }
                }
                $this->data['export_file_name'] = $export_file_name;
            }
        }
        if (isset($this->data['export_file_name']) && empty($this->output)) {
            $this->data['export_file_name'] = $this->data['title'];
        }
        $model->upload_ftp_file = '0';
        $header_labels = Yii::$app->request->post('header_labels');
        $header_labels_arr = !empty($header_labels) ? json_decode($header_labels, true) : [];
        $companyName = !empty($header_labels_arr['union_code']) ? $header_labels_arr['union_code'] : (!empty(Yii::$app->session->get('OrganizationName')) ? Yii::$app->session->get('OrganizationName') : 'Everest Instruments Pvt. Ltd.');
        $searchParams = $this->getSearchParams($header_labels_arr, $model);

        return $this->render('index', [
                    'result' => $this->output,
                    'message' => $this->message,
                    'report' => $this->report,
                    'data' => $this->data,
                    'model' => $model,
                    'dataProvider' => $this->dataProvider,
                    'fileDownloadArr' => $this->fileDownloadArr,
                    'companyName' => $companyName,
                    'searchParams' => $searchParams,
        ]);
    }

    public function actionMemberDailyCollection() {
        $this->report = 'MemberPassbook';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberDailyCollection';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberConsolidated';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'MemberConsolidatedWithBank';
            }
        }
        return $this->actionIndex();
    }

    public function actionDcsCollDateShiftSummary() {
        $this->report = 'DcsCollDateShiftSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'DcsCollDateWiseSummary';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'DcsCollectionConsolidate';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'ConsolidatedWithBank';
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'ManualMilkEntryMemberDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcCollDateWiseSummary';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'BmcCollConsolidated';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'BmcConsolidatedWithBank';
            }
        }
        return $this->actionIndex();
    }

    public function actionUnionCollDateShiftWiseSummary() {
        $this->report = 'UnionCollDateShiftWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'UnionCollDateWiseSummary';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'UnionCollConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberWiseSummary() {
        $this->report = 'MemberWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberWiseProductWiseDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberWiseFromDateToDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'MemberWiseProductWiseFromDateToDateWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorWiseSummary() {
        $this->report = 'VendorWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'VendorWiseProductWiseDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'VendorWiseConsolidated';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'VendorWiseProductWiseDateWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWiseSummary() {
        $this->report = 'BmcWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcWiseProductWiseDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'BmcWiseConsolidated';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'BmcWiseProductWiseConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionUnionWiseSummary() {
        $this->report = 'UnionWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'UnionWiseProductWiseDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'UnionWiseConsolidated';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'UnionWiseProductWiseConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWisePaymentCycleWise() {
        $this->report = 'BmcWisePaymentCycleWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BMCWiseFromDateToDateSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionCompanyWisePaymentCycleWise() {
        $this->report = 'CompanyWisePaymentCycleWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'ComapanyWiseFromDateToDateSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPaymentCycleWiseBmcWise() {
        $this->report = 'VendorPaymentCycleWiseBmcWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcWiseVendorPaymentConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPaymentCycleWiseUnionWise() {
        $this->report = 'VendorPaymentCycleWiseUnionWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'CompanyWiseVendorPaymentConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionTotalPaymentCompanyWisePaymentCycleWise() {
        $this->report = 'TotalPaymentCompanyWisePaymentCycleWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'SocietyWiseCdaConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionSocietyWiseCdaFormat() {
        $this->report = 'SocietyWiseCdaFormat';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWiseFormat';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'SocietyWiseCdaConsolidatedFormat';
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'ManualMilkEntrySocietyDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SapDetailedStatusReport';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapComparisionReport() {
        $this->report = 'SapComparisionReportDateWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'CPRmrdReportSap';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'CPMemberReportSapWithDateTime';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'CPRmrdReportSapWithDateTime';
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
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
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

    public function actionMilkCollectionRegister() {
        $this->report = 'MilkCollectionRegister';
        return $this->actionIndex();
    }

    public function actionBmcCollectionRegister() {
        $this->report = 'BmcCollectionRegister';
        return $this->actionIndex();
    }

    public function actionTransporterMaster() {
        $this->report = 'TransporterMaster';
        return $this->actionIndex();
    }

    public function actionVehicleMaster() {
        $this->report = 'VehicleMaster';
        return $this->actionIndex();
    }

    public function actionSocietyCollectionData() {
        $this->report = 'SocietyCollectionData';
        return $this->actionIndex();
    }

    public function actionBmcAutomationReport() {
        $this->report = 'BMCAutomationReport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BMCAutomationDayWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'BMCAutomationConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberPaymentDrafted() {
        $this->report = 'MemberPaymentWithBank';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberPaymentWoBank';
            }
        }
        return $this->actionIndex();
    }

    public function actionRouteWiseCollection() {
        $this->report = 'RouteWiseFarmerCollection';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'RouteWiseBMCCollection';
            }
        }
        return $this->actionIndex();
    }

    public function actionRouteWiseCollectionSummary() {
        $this->report = 'RouteWiseCollectionSummaryFarmerDateShift';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'RouteWiseCollectionSummaryFarmerConsolidate';
            } else if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'RouteWiseCollectionSummaryBmcDateShift';
            } else if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'RouteWiseCollectionSummaryBmcConsolidate';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorWiseCollectionSummary() {
        $this->report = 'VendorWiseCollectionSummaryDateShift';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'VendorWiseCollectionSummaryConsolidate';
            }
        }
        return $this->actionIndex();
    }

    public function actionPaymentAbstract() {
        $this->report = 'PaymentAbstract';
        return $this->actionIndex();
    }

    public function actionMilkCollectionData() {
        $this->report = 'MilkCollectionData';
        return $this->actionIndex();
    }

    public function actionBmcCollectionData() {
        $this->report = 'BmcCollectionData';
        return $this->actionIndex();
    }

    public function actionShiftWiseAutoManual() {
        $this->report = 'ShiftWiseAutoManual';
        return $this->actionIndex();
    }

    public function actionMilkAndBmcCollectionMonthlyComparision() {
        $this->report = 'MilkAndBmcCollectionMonthlyComparision';
        return $this->actionIndex();
    }

    public function actionSapMilkCollectionData() {
        $this->report = 'SapMilkCollectionData';
        return $this->actionIndex();
    }

    public function actionAutoManualMilkCollection() {
        $this->report = 'AutoManualMilkCollection';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AutoManualBMCCollection';
            }
        }
        return $this->actionIndex();
    }

    public function actionCollectionPendriveFile() {
        $this->report = 'CollectionPendriveFile';
        $model = new ReportsModel();
        $this->data = $this->getLabels($this->report);
        if (!empty($this->data['scenario'])) {
            $model->scenario = $this->data['scenario'];
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
                $controls[$value] = is_array($model->{$value}) ? ',' . implode(',', $model->{$value}) . ',' : $model->{$value};
            }
            $sp_name = $this->data['sp_name'];
            $output = \Yii::$app->general->getSpData($sp_name, $controls);
            $this->output = $output;
            if (empty($this->output)) {
                $this->output = Yii::t('app', 'No Data Available.');
            } else {
                $content = '';
                foreach ($this->output as $dataline) {
                    if (!empty($dataline['Dataline'])) {
                        $content .= $dataline['Dataline'] . "\r\n";
                    }
                }
                $file_name = substr($model->date, 8, 2) . substr($model->date, 5, 2) . substr($model->date, 2, 2) . (($model->shift == '1') ? 'M' : 'E') . '.EIP';
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename=' . $file_name);
                header('Content-Length: ' . strlen($content));
                header('Content-Type: text/plain');
                echo $content;
                exit();
            }
        }
        return $this->render('index', ['result' => $this->output, 'message' => $this->message, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider, 'fileDownloadArr' => $this->fileDownloadArr]);
    }

    public function actionRateMasterRegister() {
        $this->report = 'RateMasterRegister';
        return $this->actionIndex();
    }

    public function actionSocietyWiseCdaTwo() {
        $this->report = 'SocietyWiseCdaTwo';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWiseTwo';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'SocietyWiseCdaConsolidatedTwo';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberData() {
        $this->report = 'MemberData';
        return $this->actionIndex();
    }

    public function actionMemberReceptionStatus() {
        $this->report = 'MemberReceptionStatus';
        return $this->actionIndex();
    }

    public function actionProductSaleRateMasterRegister() {
        $this->report = 'ProductSaleRateMasterRegister';
        return $this->actionIndex();
    }

    public function actionWeightCollectionList() {
        $this->report = 'WeightCollectionList';
        return $this->actionIndex();
    }

    public function actionMilkCollectionNotExists() {
        $this->report = 'MilkCollectionNotExistsDetail';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MilkCollectionNotExistsSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionDayWiseQty() {
        $this->report = 'DayWiseQtyDetail';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'DayWiseQtySummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionAdvancePm() {
        $this->report = 'AdvancePm';
        return $this->actionIndex();
    }

    public function actionCcMilkPayment() {
        $this->report = 'CcMilkPayment';
        return $this->actionIndex();
    }

    public function actionFarmerFarmPayment() {
        $this->report = 'FarmerFarmPayment';
        return $this->actionIndex();
    }

    public function actionRateApplicabilityDetailsHistory() {
        $this->report = 'RateApplicabilityDetailsHistory';
        return $this->actionIndex();
    }

    public function actionMissingShift() {
        $this->report = 'MissingShift';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MissingBmcShift';
            }
        }
        return $this->actionIndex();
    }

    public function actionAlertNotification() {
        $this->report = 'AlertNotification';
        return $this->actionIndex();
    }

    public function actionStockSummary() {
        $this->report = 'StockSummary';
        return $this->actionIndex();
    }

    public function actionStockDetail() {
        $this->report = 'StockDetail';
        return $this->actionIndex();
    }

    public function actionStockDetailSummary() {
        $this->report = 'StockDetailSummary';
        return $this->actionIndex();
    }

    public function actionRecoveryFromOtherMember() {
        $this->report = 'RecoveryFromOtherMember';
        return $this->actionIndex();
    }

    public function actionCenterLossGainReport() {
        $this->report = 'CenterLossGainReport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BMCLossGainReport';
            }
        }
        return $this->actionIndex();
    }

    public function actionMissingCollectionShiftBmcCrossTab() {
        $this->report = 'MissingCollectionShiftBmcCrossTab';
        return $this->actionIndex();
    }

    public function actionProductStockDetailSummarySocietyWise() {
        $this->report = 'ProductStockDetailSummarySocietyWise';
        return $this->actionIndex();
    }

    public function actionProductStockDetailSummaryMccWise() {
        $this->report = 'ProductStockDetailSummaryMccWise';
        return $this->actionIndex();
    }

    public function actionSdFileSummary() {
        $this->report = 'SdFileSummary';
        return $this->actionIndex();
    }

    public function actionSocietyWiseRateDifferenceReport() {
        $this->report = 'SocietyWiseRateDifferenceReport';
        return $this->actionIndex();
    }

    public function actionStockDispatchToMccFromStore() {
        $this->report = 'StockDispatchToMccFromStore';
        return $this->actionIndex();
    }

    public function actionStockReceivedToMcc() {
        $this->report = 'StockReceivedToMcc';
        return $this->actionIndex();
    }

    public function actionStockTransferToDcs() {
        $this->report = 'StockTransferToDcs';
        return $this->actionIndex();
    }

    public function actionStockAtMcc() {
        $this->report = 'StockAtMcc';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'StockAtMccAmount';
            }
        }
        return $this->actionIndex();
    }

    public function actionStockAtDcs() {
        $this->report = 'StockAtDcs';
        return $this->actionIndex();
    }

    public function actionSaleReportFarmer() {
        $this->report = 'SaleReportFarmer';
        return $this->actionIndex();
    }

    public function actionSaleReportVendor() {
        $this->report = 'SaleReportVendor';
        return $this->actionIndex();
    }

    public function actionSummaryReportMcc() {
        $this->report = 'SummaryReportMcc';
        return $this->actionIndex();
    }

    public function actionSummaryReportDcs() {
        $this->report = 'SummaryReportDcs';
        return $this->actionIndex();
    }

    public function actionRmrdMilkCollectionForSap() {
        $this->report = 'RmrdMilkCollectionForSap';
        return $this->actionIndex();
    }

    public function actionStockDispatchToSale() {
        $this->report = 'StockDispatchToSale';
        return $this->actionIndex();
    }

    public function actionMemberMilkBill() {
        $this->report = 'MemberMilkBill';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberMilkBillDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberMilkBillSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionAgentWiseReconciliation() {
        $this->report = 'AgentWiseReconciliation';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AgentWiseReconciliationDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'AgentWiseReconciliationSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionRouteWiseReconciliation() {
        $this->report = 'RouteWiseReconciliation';
        return $this->actionIndex();
    }

    public function actionViewHistory() {
        $data = [];
        if (!empty($_POST)) {
            $data = $_POST;
        }

        $controls = [];
        $controls['id'] = $data['id'];
        $sp_name = 'portal_history_' . $data['table'];
        $output = \Yii::$app->general->getSpData($sp_name, $controls);
        $this->output = $output;
        $model = new ReportsModel();
        if (!empty($output)) {
            $attr = '';
            $decryptParam = !empty($this->data['to_decrypt']) ? $this->data['to_decrypt'] : [];
            $dataToDecrypt = !empty($this->data['to_decrypt']) ? $this->data['to_decrypt'] : [];
            $dataToDecryptCheck = false;
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
                if (!$dataToDecryptCheck && !empty($dataToDecrypt) && in_array($att, $dataToDecrypt)) {
                    $dataToDecryptCheck = true;
                }
            }
            if ($dataToDecryptCheck && !empty($dataToDecrypt)) {
                for ($i = 0; $i < count($output); $i++) {
                    foreach ($dataToDecrypt as $decKey) {
                        if (!empty($output[$i]) && !empty($output[$i][$decKey])) {
                            $output[$i][$decKey] = Yii::$app->general->decryptData($output[$i][$decKey]) !== FALSE ? Yii::$app->general->decryptData($output[$i][$decKey]) : $output[$i][$decKey];
                        }
                    }
                }
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
        return $this->renderAjax('view_history_list', ['result' => $this->output, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionSapReportDodla() {
        $this->report = 'VmReportSap';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'WqReportSap';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapReportCdpl() {
        $this->report = 'SapReportCdpl';
        return $this->actionIndex();
    }

    public function actionVlccTransactionDataReport() {
        $this->report = 'VlccTransactionDataReport';
        return $this->actionIndex();
    }

    public function actionMpgPaymentBillStatement() {
        $this->report = 'MpgPaymentBillStatement';
        return $this->actionIndex();
    }

    public function actionAutoManualQtyDateShiftWiseSummary() {
        $this->report = 'AutoManualQtyDateShiftWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AutoManualQtyDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'AutoManualQtyConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionSampleTimeMilkCollection() {
        $this->report = 'SampleTimeMilkCollection';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SampleTimeBmcCollection';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'SampleTimeComparision';
            }
        }
        return $this->actionIndex();
    }

    public function actionTopRegionsMilkCollectionReport() {
        $this->report = 'TopRegionsMilkCollectionReport';
        return $this->actionIndex();
    }

    public function actionDailySummaryReport() {
        $this->report = 'DailySummaryReport';
        return $this->actionIndex();
    }

    public function actionTrucksheetComparisionReport() {
        $this->report = 'TrucksheetComparisionReport';
        return $this->actionIndex();
    }

    public function actionTrucksheetDetailReport() {
        $this->report = 'TrucksheetDetailReport';
        return $this->actionIndex();
    }

    public function actionFarmerFatAndWtDeviationReport() {
        $this->report = 'FarmerFatAndWtDeviationReport';
        return $this->actionIndex();
    }

    public function uploadFTPData($title, $output, $model, $bmc) {
        $data_array = [];
        $data_array['module_name'] = $model->report_type == '1' ? 'TblBmcCollection_dodla_WQ' : 'TblBmcCollection_dodla_VM';
        $data_array['module_code'] = $bmc;
        $data_array['mcc_plant_code'] = $bmc;
        $data_array['union_code'] = $model->union_code;
        $data_array['applicable_date'] = $model->date;
        $data_array['shift_code'] = $model->shift;
        $data_array['bmc_code'] = NULL;
        $data_array['from_date'] = $model->date;
        $ftp_model = new TblFtpTxnLog();

        $ftp_model->exportData($data_array, $title, $output, $bmc);
        //        }
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
            if ($value == 'date_payment_cycle' && !empty($model->{$value})) {
                $pay_cycle_date = explode('to', $model->{$value});
                $controls['from_date'] = date('Y-m-d', strtotime($pay_cycle_date[0])) . ' 06:00:00';
                $controls['to_date'] = (trim(date('Y-m-d', strtotime($pay_cycle_date[1])))) . ' 18:00:00';
            } else {
                $controls[$value] = is_array($model->{$value}) ? ',' . implode(',', $model->{$value}) . ',' : $model->{$value};
            }
        }
        $showOutPut = TRUE;
        if (!empty($this->data['download_day_differe'])) {
            if (!empty($controls['from_date']) && !empty($controls['to_date'])) {
                $count = $this->data['download_day_differe'];
                $fDate = date('Y-m-d', strtotime($controls['from_date']));
                $tDate = date('Y-m-d', strtotime($controls['to_date']));

                $fDate = date_create($fDate);
                $tDate = date_create($tDate);
                $diff = date_diff($fDate, $tDate);
                $DayCount = $diff->format("%a");
                $DayCount = $DayCount + 1;
                if ($DayCount > $count) {
                    $showOutPut = false;
                    $reportParam = new TblGenerateReportParam();
                    $reportParam->setAttributes($controls);
                    $reportParam->report_key = $this->report;
                    $reportParam->data_post_status = 0;
                    $reportParam->report_name = $this->data['title'];
                    $exist = $reportParam->getExistData();
                    if (!empty($exist)) {
                        $reportParam->ref_code = $exist->report_param_code;
                    }
                    $reportParam->save(FALSE);
                }
            }
        }
        $sp_name = $this->data['sp_name'];
        if ($model->output_type != 'BACKGROUND') {
            if ($showOutPut) {
                $output = \Yii::$app->general->getSpData($sp_name, $controls);
            } else {
                $output[0]['message'] = 'Your Request has been submitted For Report Data. You can download file from Rport Download Screen.';
            }
        } else {
            $headerIncluded = isset($this->data['header_included']) && $this->data['header_included'] === true ? true : false;
            if ($headerIncluded) {
                $header_labels = Yii::$app->request->post('header_labels');
                $header_labels_arr = !empty($header_labels) ? json_decode($header_labels, true) : [];
                $header_info = [
                    'header_included' => $headerIncluded,
                    'organization_name' => !empty($header_labels_arr['union_code']) ? $header_labels_arr['union_code'] : (!empty(Yii::$app->session->get('OrganizationName')) ? Yii::$app->session->get('OrganizationName') : 'Everest Instruments Pvt. Ltd.'),
                    'search_params' => $this->getSearchParams($header_labels_arr, $model)
                ];
                $header_info = json_encode($header_info);
            } else {
                $header_info = NULL;
            }

            $output = $this->RegisterReportRequest('mis', $this->data, $controls, $header_info);
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
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => $this->message
                ]);
            }
        }

        if ($model->output_type != 'BACKGROUND' && !empty($output)) {
            $attr = '';
            $decryptParam = !empty($this->data['to_decrypt']) ? $this->data['to_decrypt'] : [];
            $dataToDecrypt = !empty($this->data['to_decrypt']) ? $this->data['to_decrypt'] : [];
            $dataToDecryptCheck = false;
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
                if (!$dataToDecryptCheck && !empty($dataToDecrypt) && in_array($att, $dataToDecrypt)) {
                    $dataToDecryptCheck = true;
                }
            }
            if ($dataToDecryptCheck && !empty($dataToDecrypt)) {
                for ($i = 0; $i < count($output); $i++) {
                    foreach ($dataToDecrypt as $decKey) {
                        $formateChange = '';
                        if (strpos($decKey, '##') !== false) {
                            $formate = explode('##', $decKey);
                            $decKey = $formate[0];
                            $formateChange = $formate[1];
                        }
                        if (!empty($output[$i]) && !empty($output[$i][$decKey])) {
                            $plain = Yii::$app->general->decryptData($output[$i][$decKey]);
                            $output[$i][$decKey] = $plain == null || $plain == false ? $output[$i][$decKey] : $plain;
                            $output[$i][$decKey] = Yii::$app->general->decryptData($output[$i][$decKey]) !== FALSE ? Yii::$app->general->decryptData($output[$i][$decKey]) : $output[$i][$decKey];
                            if (!empty($formateChange)) {
                                $output[$i][$decKey] = date($formateChange, strtotime($output[$i][$decKey]));
                            }
                            if (isset($this->data['mask_data']) && in_array($decKey, $this->data['mask_data'])) {
                                $output[$i][$decKey] = Yii::$app->general->maskAadhar($output[$i][$decKey]);
                            }
                        }
                    }
                }
            }
            $this->output = $output;
            $dataPro = [];
            $dataPro = [
                'allModels' => $output,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ];

            if (!empty($this->data['kartik_grid_view'])) {
                //                $dataPro['pagination'] = ['pageSize' => 500, 'defaultPageSize' => 500];
            } else {
                $dataPro['pagination'] = false;
            }
            $this->dataProvider = new ArrayDataProvider($dataPro);
            //            $this->dataProvider->refresh();
//            if (!empty($this->data['kartik_grid_view'])) {
//                $this->dataProvider->pagination->pageSize = 10;
//            }
//            $this->dataProvider = new ArrayDataProvider([
//                'allModels' => $output,
//                'pagination' => false,
//                'sort' => [
//                    'defaultOrder' => [],
//                    'attributes' => [
//                        $attr
//                    ],
//                ],
//            ]);
            if (!empty($this->data['sap_download'])) {
                $downLoadArray = [];
                foreach ($this->output as $detail) {
                    $plant = $this->report == 'SapReportCdpl' ? 'Agent_Code' : (($model->report_type == 1) ? 'PLANT_CODE' : 'Plant');
                    if (!empty($detail[$plant]) && strtolower($detail[$plant]) != 'total') {
                        if (empty($downLoadArray[$detail[$plant]])) {
                            $downLoadArray[$detail[$plant]] = [];
                        }
                        $downLoadArray[$detail[$plant]][] = $detail;
                    }
                }
                foreach ($downLoadArray as $bmc => $download) {
                    if ($this->report == 'SapReportCdpl') {
                        $title = $download[0]['Plant_Code'] . '_' . $bmc . '_VMCC_' . str_replace('-', '_', Yii::$app->controls->view_date($model->date)) . '_' . $model->shift;
                    } else {
                        $report_type = ($model->report_type == 0) ? Yii::t('app', 'VM') : (($model->report_type == 1) ? Yii::t('app', 'WQ') : Yii::t('app', 'SD'));
                        $title = $bmc . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($model->date)) . '_' . $model->shift;
                    }
                    if (isset(Yii::$app->request->post()['upload_ftp_file']) && Yii::$app->request->post()['upload_ftp_file'] == '1') {
                        $this->uploadFTPData($title, $download, $model, $bmc);
                    }

                    $this->downloadDataLocal($title, $download, $fileArray);
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
        if (isset($this->data['dynamic_label']) && !empty($this->output)) {
            $codeToAppend = $model->getMccCode($model->mcc_code);
            $fromShift = $model->from_shift == 1 ? 'MORNING' : 'EVENING';
            $toShift = $model->to_shift == 1 ? 'MORNING' : 'EVENING';
            $this->label = $codeToAppend->name . '_' . $codeToAppend->ref_code . '_' . Yii::$app->controls->view_date($model->from_date, 'php:Y-m-d') . ' to ' . Yii::$app->controls->view_date($model->to_date, 'php:Y-m-d') . '_' . $fromShift . '_' . $toShift;
        }

        if (isset($this->data['export_file_name']) && !empty($this->output)) {
            $title_data = array_merge($model->attributes, $this->output[0]);
            $export_file_name = $this->data['export_file_name'];

            foreach ($title_data as $k => $v) {
                if ($k == 'from_date') {
                    $v = str_replace('-', '_', Yii::$app->controls->view_date($v));
                }
                if (!is_array($v) && !empty($v)) {
                    $export_file_name = str_replace($k, $v, $export_file_name);
                }
            }
            $this->label = $export_file_name;
        }
        $this->output = $output;
        if ($model->output_type == 'DOWNLOAD') {
            if ($this->report == 'SapMilkCollectionData') {
                $this->downloadDataExcel($model);
            } else if (isset($this->data['excel_readonly'])) {
                $this->downloadDataReadonly($this->output, $this->data, $this->label);
            } else {
                $this->downloadData($controls, $model);
            }
        }
    }

    public function actionCleaningFormat() {
        $this->report = 'CleaningFormat';
        return $this->actionIndex();
    }

    public function actionRecoveryFromDifferentVendor() {
        $this->report = 'RecoveryFromDifferentVendor';
        return $this->actionIndex();
    }

    public function actionMemberWiseOutstanding() {
        $this->report = 'MemberWiseOutstanding';
        return $this->actionIndex();
    }

    public function actionDcsWiseOutstanding() {
        $this->report = 'DcsWiseOutstanding';
        return $this->actionIndex();
    }

    public function actionVendorWiseOutstanding() {
        $this->report = 'VendorWiseOutstanding';
        return $this->actionIndex();
    }

    public function actionMccWiseOutstanding() {
        $this->report = 'MccWiseOutstanding';
        return $this->actionIndex();
    }

    public function actionMemberProductSaleForPaidInstallment() {
        $this->report = 'MemberProductSaleForPaidInstallment';
        return $this->actionIndex();
    }

    public function actionDcsProductSaleForPaidInstallment() {
        $this->report = 'DcsProductSaleForPaidInstallment';
        return $this->actionIndex();
    }

    public function actionVendorProductSaleForPaidInstallment() {
        $this->report = 'VendorProductSaleForPaidInstallment';
        return $this->actionIndex();
    }

    public function actionMccProductSaleForPaidInstallment() {
        $this->report = 'MccProductSaleForPaidInstallment';
        return $this->actionIndex();
    }

    public function actionNewMemberPouringMilk() {
        $this->report = 'NewMemberPouringMilk';
        return $this->actionIndex();
    }

    public function actionNewCustomerPouringMilk() {
        $this->report = 'NewCustomerPouringMilk';
        return $this->actionIndex();
    }

    public function actionUmangSapReport() {
        $this->report = 'UmangSapReportDaily';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'UmangSapReportWeekly';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcCollectionHistory() {
        $this->report = 'BmcCollectionHistory';
        return $this->actionIndex();
    }

    public function actionSocietyCompositeVsActual() {
        $this->report = 'SocietyCompositeVsActual';
        return $this->actionIndex();
    }

    public function actionTankerReport() {
        $this->report = 'TankerReport';
        return $this->actionIndex();
    }

    public function actionPaymentDifference() {
        $this->report = 'PaymentDifference';
        return $this->actionIndex();
    }

    public function actionSapUploadSummary() {
        $this->report = 'SapUploadSummary';
        return $this->actionIndex();
    }

    public function actionFileGenerateStatus() {
        $this->report = 'FileGenerateStatus';
        return $this->actionIndex();
    }

    public function actionMccBilling() {
        $this->report = 'MccBilling';
        return $this->actionIndex();
    }

    public function actionBmcRegister() {
        $this->report = 'BmcRegister';
        return $this->actionIndex();
    }

    public function actionPlantRegister() {
        $this->report = 'PlantRegister';
        return $this->actionIndex();
    }

    public function actionTankerReceiptNote() {
        $this->report = 'TankerReceiptNote';
        return $this->actionIndex();
    }

    public function actionMccDayBookDispatchHubHorizontal() {
        $this->report = 'MccDayBookDispatchHubHorizontal';
        return $this->actionIndex();
    }

    public function actionMccReceiptVsBmcDispatch() {
        $this->report = 'MccReceiptVsBmcDispatch';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MccReceiptVsBmcDispatchDetail';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberCollectionReportForSap() {
        $this->report = 'MemberCollectionReportForSap';
        return $this->actionIndex();
    }

    public function actionMilkCollectionProc() {
        $this->report = 'MilkCollectionProc';
        return $this->actionIndex();
    }

    public function actionMilkCollectionProcDetail() {
        $this->report = 'MilkCollectionProcDetail';
        return $this->actionIndex();
    }

    public function actionMilkCollectionAbsent() {
        $this->report = 'MilkCollectionAbsent';
        return $this->actionIndex();
    }

    public function actionLeftPourer() {
        $this->report = 'LeftPourer';
        return $this->actionIndex();
    }

    public function actionMilkCollectionNegativeGroth() {
        $this->report = 'MilkCollectionNegativeGroth';
        return $this->actionIndex();
    }

    public function actionBmcMilkCollectionProc() {
        $this->report = 'BmcMilkCollectionProc';
        return $this->actionIndex();
    }

    public function actionBmcMilkCollectionProcDetail() {
        $this->report = 'BmcMilkCollectionProcDetail';
        return $this->actionIndex();
    }

    public function actionAntibioticReport() {
        $this->report = 'AntibioticReport';
        return $this->actionIndex();
    }

    public function actionMccRecipationSummary() {
        $this->report = 'MccRecipationSummary';
        return $this->actionIndex();
    }

    public function actionMccRecipationDetail() {
        $this->report = 'MccRecipationDetail';
        return $this->actionIndex();
    }

    public function actionStockRegisterToSap() {
        $this->report = 'StockRegisterToSap';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'StockRegisterToProduct';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'StockRegisterToSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionStockRegisterMccToSap() {
        $this->report = 'StockRegisterMccToSap';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'StockRegisterMccToProduct';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'StockRegisterMccToSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWiseSocietyWiseAutoManual() {
        $this->report = 'BmcWiseSocietyWiseAutoManual';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcWiseSocietyWiseAutoManualSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWiseAutoManualSummary() {
        $this->report = 'BmcWiseAutoManualSummary';
        return $this->actionIndex();
    }

    public function actionMilkCollectionHistory() {
        $this->report = 'MilkCollectionHistory';
        return $this->actionIndex();
    }

    public function actionMemberHistory() {
        $this->report = 'MemberHistory';
        return $this->actionIndex();
    }

    public function actionDcsHistory() {
        $this->report = 'DcsHistory';
        return $this->actionIndex();
    }

    public function actionRmrdDataExport() {
        $this->report = 'RmrdDataExport';
        return $this->actionIndex();
    }

    public function actionCpliabilityReport() {
        $this->report = 'CpliabilityReport';
        return $this->actionIndex();
    }

    public function actionBonusReport() {
        $this->report = 'BonusReport';
        return $this->actionIndex();
    }

    public function actionMemberPaymentBankFormat() {
        $this->report = 'MemberPaymentBankFormat';
        return $this->actionIndex();
    }

    public function actionMemberDailyCollectionRegion() {
        $this->report = 'MemberPassbookRegion';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberDailyCollectionRegion';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberConsolidatedRegion';
            }
        }
        return $this->actionIndex();
    }

    public function actionDcsCollDateShiftSummaryRegion() {
        $this->report = 'DcsCollDateShiftSummaryRegion';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'DcsCollDateWiseSummaryRegion';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'DcsCollectionConsolidateRegion';
            }
        }
        return $this->actionIndex();
    }

    public function actionAgentWiseReconciliationRegion() {
        $this->report = 'AgentWiseReconciliationRegion';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AgentWiseReconciliationDateWiseRegion';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'AgentWiseReconciliationSummaryRegion';
            }
        }
        return $this->actionIndex();
    }

    public function actionCenterLossGainReportRegion() {
        $this->report = 'CenterLossGainReportRegion';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BMCLossGainReportRegion';
            }
        }
        return $this->actionIndex();
    }

    public function actionMilkCollectionProcDetailRegion() {
        $this->report = 'MilkCollectionProcDetailRegion';
        return $this->actionIndex();
    }

    public function actionMilkShortageRecovery() {
        $this->report = 'MilkShortageRecovery';
        return $this->actionIndex();
    }

    public function actionCdaReport() {
        $this->report = 'CdaReport';
        return $this->actionIndex();
    }

    public function actionVehicleMasterHistory() {
        $this->report = 'VehicleMasterHistory';
        return $this->actionIndex();
    }

    public function actionTallyReport() {
        $this->report = 'TallyReport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'TallyConsolidatedReport';
            }
        }
        return $this->actionIndex();
    }

    public function actionBiplData() {
        $this->report = 'BiplData';
        return $this->actionIndex();
    }

    public function actionDetailsReport() {
        $this->report = 'DetailsReport';
        return $this->actionIndex();
    }

    public function actionAllReportRequest() {
        $this->report = 'AllReportRequest';
        return $this->actionIndex();
    }

    public function actionRegionWiseUserAttendanceReport() {
        $this->report = 'RegionWiseUserAttendanceReport';
        return $this->actionIndex();
    }

    public function actionBiplDataAdmin() {
        $this->report = 'BiplDataAdmin';
        return $this->actionIndex();
    }

    public function actionPurchaseSummary() {
        $this->report = 'PurchaseSummary';
        return $this->actionIndex();
    }

    public function actionPurchaseSummaryFormat() {
        $this->report = 'PurchaseSummaryFormat';
        return $this->actionIndex();
    }

    public function actionMilkVan() {
        $this->report = 'MilkVan';
        return $this->actionIndex();
    }

    public function actionDcsWiseBillHeadApplicability() {
        $this->report = 'DcsWiseBillHeadApplicability';
        return $this->actionIndex();
    }

    public function actionQualityCollectionReport() {
        $this->report = 'QualityCollectionReport';
        return $this->actionIndex();
    }

    public function actionPaymentSummary() {
        $this->report = 'PaymentSummary';
        return $this->actionIndex();
    }

    public function actionBmcCollectionSummary() {
        $this->report = 'BmcCollectionSummary';
        return $this->actionIndex();
    }

    public function actionMemberDailyCollectionCommon() {
        $this->report = 'MemberPassbookCommon';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberDailyCollectionCommon';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberConsolidatedCommon';
            }
        }
        return $this->actionIndex();
    }

    public function actionDcsCollDateShiftSummaryCommon() {
        $this->report = 'DcsCollDateShiftSummaryCommon';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'DcsCollDateWiseSummaryCommon';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'DcsCollectionConsolidateCommon';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcCollectionShiftReportCommon() {
        $this->report = 'BmcCollectionShiftReportCommon';
        return $this->actionIndex();
    }

    public function actionBmcCollDateShiftWiseSummaryCommon() {
        $this->report = 'BmcCollDateShiftWiseSummaryCommon';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcCollDateWiseSummaryCommon';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'BmcCollConsolidatedCommon';
            }
        }
        return $this->actionIndex();
    }

    public function actionSocietyWiseCdaCommon() {
        $this->report = 'SocietyWiseCdaCommon';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWiseCommon';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'SocietyWiseCdaConsolidatedCommon';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapWqFile() {
        $this->report = 'SapWqFile';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'SapWqFileXls';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorPaymentFormat() {
        $this->report = 'VendorPaymentFormat';
        return $this->actionIndex();
    }

    public function actionRootWiseDifference() {
        $this->report = 'RootWiseDifference';
        return $this->actionIndex();
    }

    public function actionBmcCollectionSummaryRahema() {
        $this->report = 'BmcCollectionSummaryRahema';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'BmcCollectionDateWiseRheman';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'BmcCollectionConsolidatedRaheman';
            }
        }
        return $this->actionIndex();
    }

    public function actionMobileAppReport() {
        $this->report = 'MobileAppReport';
        return $this->actionIndex();
    }

    public function actionFarmerRegister() {
        $this->report = 'FarmerRegister';
        return $this->actionIndex();
    }

    public function actionFieldStaffActivity() {
        $this->report = 'FieldStaffActivity';
        return $this->actionIndex();
    }

    public function actionMemberProvisionalFamilyDetail() {
        $this->report = 'MemberProvisionalFamilyDetail';
        return $this->actionIndex();
    }

    public function actionMemberProvisionalSapExport() {
        $this->report = 'MemberProvisionalSapExport';
        return $this->actionIndex();
    }

    public function actionExportProvisionalMemberBankReceipt() {
        $this->report = 'ExportProvisionalMemberBankReceipt';
        return $this->actionIndex();
    }

    public function actionMilkCollectionStatusReport() {
        $this->report = 'MilkCollectionStatusReport';
        return $this->actionIndex();
    }

    public function actionMilkCollectionFilterBased() {
        $this->report = 'MilkCollectionFilterBased';
        return $this->actionIndex();
    }

    public function actionMilkCollectionAudit() {
        $this->report = 'MilkCollectionAudit';
        return $this->actionIndex();
    }

    public function actionMemberMilkCollection() {
        $this->report = 'MemberMilkCollection';
        return $this->actionIndex();
    }

    public function actionMemberMilkCollectionDcsWise() {
        $this->report = 'MemberMilkCollectionDcsWise';
        return $this->actionIndex();
    }

    public function actionDcsBmcMemberWiseTopCollection() {
        $this->report = 'DcsBmcMemberWiseTopCollection';
        return $this->actionIndex();
    }

    public function actionFatAnalysisReport() {
        $this->report = 'FatAnalysisReport';
        return $this->actionIndex();
    }

    public function actionDcsAndMemberWiseQtyCompare() {
        $this->report = 'DcsAndMemberWiseQtyCompare';
        return $this->actionIndex();
    }

    public function actionSapDataExportForDeduction() {
        $this->report = 'SapDataExportForDeduction';
        return $this->actionIndex();
    }

    public function actionSapDataExportForVlcReplacement() {
        $this->report = 'SapDataExportForVlcReplacement';
        return $this->actionIndex();
    }

    public function actionStockRegisterBmcToSap() {
        $this->report = 'StockRegisterBmcToSap';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'StockRegisterBmcToProduct';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'StockRegisterBmcToSummary';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'StockRegisterBmcAndTypeWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionAssetDetailsReport() {
        $this->report = 'AssetDetailsReport';
        return $this->actionIndex();
    }

    public function actionUserOrganizationMappingReport() {
        $this->report = 'UserOrganizationMappingReport';
        return $this->actionIndex();
    }

    public function actionAppStartupReport() {
        $this->report = 'AppStartupReport';
        return $this->actionIndex();
    }

    public function actionMilkCollectionStatusDetail() {
        $this->report = 'MilkCollectionStatusDetail';
        return $this->actionIndex();
    }

    public function actionEiplInstalledUsersDetails() {
        $this->report = 'EiplInstalledUsersDetails';
        return $this->actionIndex();
    }

    public function actionSapDataExportFeedSaleMember() {
        $this->report = 'SapDataExportFeedSaleMember';
        return $this->actionIndex();
    }

    public function actionPaymentCycleApplicabilityStatus() {
        $this->report = 'PaymentCycleApplicabilityStatus';
        return $this->actionIndex();
    }

    public function actionIndentSummaryDetail() {
        $this->report = 'IndentSummaryDetail';
        return $this->actionIndex();
    }

    public function actionGheeGroupIndentReport() {
        $this->report = 'GheeGroupIndentReport';
        return $this->actionIndex();
    }

    public function actionCfGroupIndentReport() {
        $this->report = 'CfGroupIndentReport';
        return $this->actionIndex();
    }

    public function actionSapGheeGroupIndentReport() {
        $this->report = 'SapGheeGroupIndentReport';
        return $this->actionIndex();
    }

    public function actionSapCfGroupIndentReport() {
        $this->report = 'SapCfGroupIndentReport';
        return $this->actionIndex();
    }

    public function actionBillHeadDetail() {
        $this->report = 'BillHeadDetail';
        return $this->actionIndex();
    }

    public function actionApprovedAttachmentDetails() {
        $this->report = 'ApprovedAttachmentDetails';
        return $this->actionIndex();
    }

    public function actionBmcCollectionRouteWise() {
        $this->report = 'BmcCollectionRouteWise';
        return $this->actionIndex();
    }

    public function actionPlantWiseMilkCollectionTracking() {
        $this->report = 'PlantWiseMilkCollectionTracking';
        return $this->actionIndex();
    }

    public function actionVlcQtySlabWiseCategory() {
        $this->report = 'VlcQtySlabWiseCategory';
        return $this->actionIndex();
    }

    public function actionAvgPerVlcMilkQtySlabWiseCategory() {
        $this->report = 'AvgPerVlcMilkQtySlabWiseCategory';
        return $this->actionIndex();
    }

    public function actionIndentMemberDetail() {
        $this->report = 'IndentMemberDetail';
        return $this->actionIndex();
    }

    public function actionCompanyWiseMilkCollection() {
        $this->report = 'CompanyWiseMilkCollection';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'PlantWiseMilkCollection';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'DcsWiseMilkCollection';
            }
        }
        return $this->actionIndex();
    }

    public function actionRateRecalculationWefDateWise() {
        $this->report = 'RateRecalculationWefDateWise';
        return $this->actionIndex();
    }

    public function actionInsuranceDetail() {
        $this->report = 'InsuranceDetail';
        return $this->actionIndex();
    }

    public function actionInsuranceDetailReconciliation() {
        $this->report = 'InsuranceDetailReconciliation';
        return $this->actionIndex();
    }

    public function actionInsuranceSummaryDcsWise() {
        $this->report = 'InsuranceSummaryDcsWise';
        return $this->actionIndex();
    }

    public function actionTpCostDetail() {
        $this->report = 'TpCostDetail';
        return $this->actionIndex();
    }

    public function actionTpCostSummary() {
        $this->report = 'TpCostSummary';
        return $this->actionIndex();
    }

    public function actionAreBmcCollectionShiftReport() {
        $this->report = 'AreBmcCollectionShiftReport';
        return $this->actionIndex();
    }

    public function actionAreBmcCollDateShiftWiseSummary() {
        $this->report = 'AreBmcCollDateShiftWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AreBmcCollDateWiseSummary';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'AreBmcCollConsolidated';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'AreConsolidatedWithBank';
            }
        }
        return $this->actionIndex();
    }

    public function actionAreSocietyWiseCda() {
        $this->report = 'AreSocietyWiseCda';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AreSocietyWiseCdaDateWise';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'AreSocietyWiseCdaConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionAreVendorPayment() {
        $this->report = 'AreVendorPayment';
        return $this->actionIndex();
    }

    public function actionAreMemberPayment() {
        $this->report = 'AreMemberPaymentDcsWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AreMemberPaymentMemberWise';
            }
        }
        return $this->actionIndex();
    }

    public function actionAreVendorBankPayment() {
        $this->report = 'AreVendorBankPayment';
        return $this->actionIndex();
    }

    public function actionAreMemberBankPayment() {
        $this->report = 'AreMemberBankPayment';
        return $this->actionIndex();
    }

    public function actionVspTransitRecovery() {
        $this->report = 'VspTransitRecovery';
        return $this->actionIndex();
    }

    public function actionComplainActivityList() {
        $this->report = 'ComplainActivityList';
        return $this->actionIndex();
    }

    public function actionRouteWiseCdaFormat() {
        $this->report = 'RouteWiseCdaFormat';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'RouteWiseCdaDateWiseFormat';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'RouteWiseCdaConsolidatedFormat';
            }
        }
        return $this->actionIndex();
    }

    public function actionMilkDispatchList() {
        $this->report = 'MilkDispatchList';
        return $this->actionIndex();
    }

    public function actionMilkRejectList() {
        $this->report = 'MilkRejectList';
        return $this->actionIndex();
    }

    public function actionChillerCostSummary() {
        $this->report = 'ChillerCostSummary';
        return $this->actionIndex();
    }

    public function actionMonthlySahayakIncome() {
        $this->report = 'MonthlySahayakIncome';
        return $this->actionIndex();
    }

    public function actionMisCcWiseClosingBalance() {
        $this->report = 'MisCcWiseClosingBalance';
        return $this->actionIndex();
    }

    public function actionProcMisLotWiseDetails() {
        $this->report = 'ProcMisLotWiseDetails';
        return $this->actionIndex();
    }

    public function actionComparisonReport() {
        $this->report = 'ComparisonReport';
        return $this->actionIndex();
    }

    public function actionLocalMilkSale() {
        $this->report = 'LocalMilkSale';
        return $this->actionIndex();
    }

    public function actionMemberBilling() {
        $this->report = 'MemberBilling';
        return $this->actionIndex();
    }

    public function actionMemberBillingDcsWise() {
        $this->report = 'MemberBillingDcsWise';
        return $this->actionIndex();
    }

    public function actionProductDispatchCenterWiseDetail() {
        $this->report = 'ProductDispatchCenterWiseDetail';
        return $this->actionIndex();
    }

    public function actionCmpReport() {
        $this->report = 'CmpReport';
        return $this->actionIndex();
    }

    public function actionMccMilkBillDetailsWithIncentiveRouteWise() {
        $this->report = 'MccMilkBillDetailsWithIncentiveRouteWise';
        return $this->actionIndex();
    }

    public function actionMccMilkBillDetailsMccDayWise() {
        $this->report = 'MccMilkBillDetailsMccDayWise';
        return $this->actionIndex();
    }

    public function actionMisMilkPurchase() {
        $this->report = 'MisMilkPurchase';
        return $this->actionIndex();
    }

    public function actionUserAttendanceDetails() {
        $this->report = 'UserAttendanceDetails';
        return $this->actionIndex();
    }

    public function actionAssetDetailSummary() {
        $this->report = 'AssetDetailSummary';
        return $this->actionIndex();
    }

    public function actionFarmerPaymentWiseMilkWise() {
        $this->report = 'FarmerPaymentWiseMilkWise';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'FarmerPaymentWiseMilkWiseSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionPaymentCycleReport() {
        $this->report = 'PaymentCycleReport';
        return $this->actionIndex();
    }

    public function actionYearlyFarmerCollectionReport() {
        $this->report = 'YearlyFarmerCollectionReport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'YearlyVspCollectionReport';
            }
        }
        return $this->actionIndex();
    }

    public function actionAadeshLatter() {
        $this->report = 'AadeshLatter';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'AadeshLatterSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionProductSaleLogHistory() {
        $this->report = 'ProductSaleLogHistory';
        return $this->actionIndex();
    }

    public function actionFarmerMilkBillConsolidatedSummary() {
        $this->report = 'FarmerMilkBillConsolidatedSummary';
        return $this->actionIndex();
    }

    public function actionMisFeedReport() {
        $this->report = 'MisFeedReport';
        return $this->actionIndex();
    }

    public function actionVspOutstandingDetail() {
        $this->report = 'VspOutstandingDetail';
        return $this->actionIndex();
    }

    public function actionVehicleStatusReport() {
        $this->report = 'VehicleStatusReport';
        return $this->actionIndex();
    }

    public function actionTpCostSummaryNewFormat() {
        $this->report = 'TpCostSummaryNewFormat';
        return $this->actionIndex();
    }

    public function actionMemberPaymentShortageRecovery() {
        $this->report = 'MemberPaymentShortageRecovery';
        return $this->actionIndex();
    }

    public function actionInwardBillSummary() {
        $this->report = 'InwardBillSummary';
        return $this->actionIndex();
    }

    public function actionPaymentAdviceInwardSummary() {
        $this->report = 'PaymentAdviceInwardSummary';
        return $this->actionIndex();
    }

    public function actionMemberDailyCollectionSecond() {
        $this->report = 'MemberPassbookSecond';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberDailyCollectionSecond';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberConsolidatedSecond';
            }
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '3') {
                $this->report = 'MemberConsolidatedWithBankSecond';
            }
        }
        return $this->actionIndex();
    }

    public function actionVlccCommission() {
        $this->report = 'VlccCommission';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'CommissionReportSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionSapReportExport() {
        $this->report = 'VmReportSapExport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post()['ReportsModel']['report_type'] == '1') {
                $this->report = 'WqReportSapExport';
            } else if (Yii::$app->request->post()['ReportsModel']['report_type'] == '2') {
                $this->report = 'SdReportSapExport';
            }
        }

        return $this->actionIndex();
    }

    public function actionUnifiedWeighmentReport() {
        $this->report = 'UnifiedWeighmentReport';
        return $this->actionIndex();
    }

    public function actionDpuRateComparision() {
        $this->report = 'DpuRateComparision';
        return $this->actionIndex();
    }

    public function actionComplaintSummaryDetailReport() {
        $this->report = 'ComplaintSummaryDetailReport';
        return $this->actionIndex();
    }

    public function actionBankVerificationReport() {
        $this->report = 'BankVerificationReport';
        return $this->actionIndex();
    }

    public function actionMilkPurchaseRegisterReport() {
        $this->report = 'MilkPurchaseRegisterReport';
        return $this->actionIndex();
    }

    public function actionFarmerLedgerReport() {
        $this->report = 'FarmerLedgerReport';
        return $this->actionIndex();
    }

    public function actionMemberWiseSummaryReport() {
        $this->report = 'MemberWiseSummaryReport';
        return $this->actionIndex();
    }

    public function actionLocalSaleReport() {
        $this->report = 'LocalSaleReport';
        return $this->actionIndex();
    }

    public function actionLocalSaleDetailReport() {
        $this->report = 'LocalSaleDetailReport';
        return $this->actionIndex();
    }

    public function actionMilkRateDetailReport() {
        $this->report = 'MilkRateDetailReport';
        return $this->actionIndex();
    }

    public function actionMilkEditReport() {
        $this->report = 'MilkEditReport';
        return $this->actionIndex();
    }

    public function actionDateWiseMilkPurchaseSummary() {
        $this->report = 'DateWiseMilkPurchaseSummary';
        return $this->actionIndex();
    }

    public function actionMilkPurchaseAnalysisReport() {
        $this->report = 'MilkPurchaseAnalysis';
        return $this->actionIndex();
    }

    public function actionFarmerListReport() {
        $this->report = 'FarmerListReport';
        return $this->actionIndex();
    }

    public function actionMilkEditSummary() {
        $this->report = 'MilkEditSummary';
        return $this->actionIndex();
    }

    public function actionFatWiseQtyAnalysis() {
        $this->report = 'FatWiseQtyAnalysis';
        return $this->actionIndex();
    }

    public function actionMilkCompare() {
        $this->report = 'MilkCompare';
        return $this->actionIndex();
    }

    public function actionSocietyList() {
        $this->report = 'SocietyList';
        return $this->actionIndex();
    }

    public function actionFarmerAppDetailsReport() {
        $this->report = 'FarmerAppDetailsReport';
        return $this->actionIndex();
    }

    public function actionUnionWiseMessageDetailReport() {
        $this->report = 'UnionWiseMessageDetailReport';
        return $this->actionIndex();
    }

    public function actionUnionWiseMessageReport() {
        $this->report = 'UnionWiseMessageReport';
        return $this->actionIndex();
    }

    public function actionOnlineOfflineSocietyReport() {
        $this->report = 'OnlineOfflineSocietyReport';
        return $this->actionIndex();
    }

    public function actionMilkRatePublishReport() {
        $this->report = 'MilkRatePublishReport';
        return $this->actionIndex();
    }

    public function actionSmsDetailReport() {
        $this->report = 'SmsDetailReport';
        return $this->actionIndex();
    }

    public function actionTopSocietyMilkCollectionReport() {
        $this->report = 'TopSocietyMilkCollectionReport';
        return $this->actionIndex();
    }

    public function actionTopFarmerMilkCollectionReport() {
        $this->report = 'TopFarmerMilkCollectionReport';
        return $this->actionIndex();
    }

    public function actionFarmerManualEntryReport() {
        $this->report = 'FarmerManualEntryReport';
        return $this->actionIndex();
    }

    public function actionSocietyWiseSummaryReport() {
        $this->report = 'SocietyWiseSummaryReport';
        return $this->actionIndex();
    }

    public function actionFarmerNotSubmittingMilkReport() {
        $this->report = 'FarmerNotSubmittingMilkReport';
        return $this->actionIndex();
    }

    public function actionManualCollectionSummaryReport() {
        $this->report = 'ManualCollectionSummaryReport';
        return $this->actionIndex();
    }

    public function actionMilkEditForFarmerReport() {
        $this->report = 'MilkEditForFarmerReport';
        return $this->actionIndex();
    }

    public function actionMuAppVdcsAppUserReport() {
        $this->report = 'MuAppVdcsAppUserReport';
        return $this->actionIndex();
    }

    public function actionSocietySampleReport() {
        $this->report = 'SocietySampleReport';
        return $this->actionIndex();
    }

    public function actionFarmerWiseYearlyEditReport() {
        $this->report = 'FarmerWiseYearlyEditReport';
        return $this->actionIndex();
    }

    public function actionRdoSalaryStructure() {
        $this->report = 'RdoSalaryStructure';
        return $this->actionIndex();
    }

    /* Reports Configuration */

    public function getLabels($l) {
        $label = [
            //101
            'MemberDailyCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_day_wise_report',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
//                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberPassbook' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_passbook',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
//                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
//                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberConsolidatedWithBank' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary_with_bank',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'to_decrypt' => ['aadhar_no'],
                'to_text' => ['aadhar_no'],
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                'bkg_export' => TRUE
            ],
            //102
            'DcsCollDateShiftSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_date_shift_wise',
                'scenario' => 'DcsCollDateShiftSummary',
                'title' => '102 - Society Collection Date And Shift Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'DcsCollDateWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_date_wise',
                'scenario' => 'DcsCollDateShiftSummary',
                'title' => '102 - Society Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'DcsCollectionConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_consolidated',
                'scenario' => 'DcsCollDateShiftSummary',
                'title' => '102 - Society Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
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
                'title' => '106 - Manual Milk Entry Member Date Shift Wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntryMemberDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_member_date_wise',
                'scenario' => 'ManualMilkEntryMemberDateShiftWise',
                'title' => '106 - Manual Milk Entry Member Date Wise',
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_society_date_shift_wise',
                'scenario' => 'ManualMilkEntrySocietyDateShiftWise',
                'title' => '107 - Manual Milk Entry Society Date Shift wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntrySocietyDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_society_date_wise',
                'scenario' => 'ManualMilkEntrySocietyDateShiftWise',
                'title' => '107 - Manual Milk Entry Society Date wise',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'ManualMilkEntrySocietyConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_manual_milk_entry_society_consolidated',
                'scenario' => 'ManualMilkEntrySocietyDateShiftWise',
                'title' => '107 - Manual Milk Entry Society Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollectionShiftReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,route_type_trans:static:route_type_trans',
                'sp_name' => 'sp_mis_bmc_collection_shift_report',
                'scenario' => 'BmcCollectionShiftReport',
                'title' => '201 - BMC Collection Shift Report',
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            //202
            'BmcCollDateShiftWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_wise_society_collection_date_shift_wise',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Date And Shift Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
            ],
            'BmcCollDateWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_wise_soceity_collection_date_wise',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
            ],
            'BmcCollConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_collection_consolidated',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
            ],
            'BmcConsolidatedWithBank' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_collection_consolidated_with_bank',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
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
                'title' => '205 - Company Wise Collection Vs Receipt',
            ],
            'UnionWiseDispatchVsRecipt' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_company_wise_dispatch_vs_receipt',
                'scenario' => 'UnionWiseDispatchVsRecipt',
                'title' => '206 - Company Wise Dispatch Vs Receipt',
            ],
            //207
            'SocietyWiseCda' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_shift',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'SocietyWiseCdaDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'SocietyWiseCdaConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_consolidated',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'GprsDataReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_dpu_gprs_data_reconciliation',
                'scenario' => 'GprsDataReconciliation',
                'title' => '908 - DPU-GPRS Data Reconciliation',
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,dcs_code:route_code',
                'sp_name' => 'sp_mis_dcs_master_register',
                'scenario' => 'DcsMaster',
                'title' => 'DCS Register',
                'to_decrypt' => ['phone_no', 'Phone No', 'pan_no', 'Pan No', 'upi_no', 'Upi No', 'password', 'password', 'adhar_no', 'aadhaar_no'],
                'to_text' => ['bank_account_no', 'adhar_no', 'aadhaar_no'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
            //                'output_type' => FALSE
            ],
            'MemberMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,dcs_code:route_code,member_code',
                'sp_name' => 'sp_mis_member_master_register',
                'scenario' => 'MemberMaster',
                'title' => 'Member Register',
                'to_decrypt' => ['pan_no', 'Pan No', 'dob', 'Dob', 'adhar_no', 'aadhaar_no'],
                'to_text' => ['bank_account_no', 'adhar_no', 'aadhaar_no'],
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
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'BMC'), Yii::t('app', 'MEMBER With Date Time'), Yii::t('app', 'BMC With Date Time')],
                'export_file_name' => 'Plant_Code_VMCC_from_date_from_shift',
            ],
            'CPRmrdReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_rpt_cpmilk_rmrd_collection',
                'scenario' => 'CPReportSap',
                'title' => '404 - SAP Data Export',
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'BMC'), Yii::t('app', 'MEMBER With Date Time'), Yii::t('app', 'BMC With Date Time')],
                'export_file_name' => 'Plant_Code_WQ_from_date_from_shift',
            ],
            'CPMemberReportSapWithDateTime' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_rpt_cpmilk_member_collection_rec_datetime',
                'scenario' => 'CPReportSap',
                'title' => '404 - SAP Data Export',
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'BMC'), Yii::t('app', 'MEMBER With Date Time'), Yii::t('app', 'BMC With Date Time')],
                'export_file_name' => 'Plant_Code_VMCC_from_date_from_shift_With_Date_Time',
            ],
            'CPRmrdReportSapWithDateTime' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_rpt_cpmilk_rmrd_collection_rec_datetime',
                'scenario' => 'CPReportSap',
                'title' => '404 - SAP Data Export',
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'BMC'), Yii::t('app', 'MEMBER With Date Time'), Yii::t('app', 'BMC With Date Time')],
                'export_file_name' => 'Plant_Code_WQ_from_date_from_shift_With_Date_Time',
            ],
            'VendorPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_vendor_payment',
                'scenario' => 'VendorPayment',
                'title' => '602 - Vendor Payment',
                'bkg_export' => TRUE,
                'to_text' => ['Account No']
            ],
            'MemberPaymentDcsWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_billing_dcs_wise',
                'scenario' => 'MemberPayment',
                'title' => '603 - Member Payment',
                'report_type' => [Yii::t('app', 'DCS Wise'), Yii::t('app', 'Member Wise')],
                'bkg_export' => TRUE,
            ],
            'MemberPaymentMemberWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_billing_member_wise',
                'scenario' => 'MemberPayment',
                'title' => '603 - Member Payment',
                'report_type' => [Yii::t('app', 'DCS Wise'), Yii::t('app', 'Member Wise')],
                'bkg_export' => TRUE,
            ],
            'VendorBankPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,payment_cycle_code:type_check,bank_type:static:bank_type',
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,rate_type:static:rate_type,customer_type,vendor_code,p_organization_type:static:p_organization_type,p_date:string',
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,main_customer_type,vendor_code:main_customer_type',
                'sp_name' => 'sp_mis_customer_master_register',
                'scenario' => 'CustomerMaster',
                'title' => 'Customer Master Register',
                'to_decrypt' => ['adhar_no'],
                'to_text' => ['aadhaar_no', 'adhar_no', 'bank_account_no'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
            ],
            'MilkCollectionRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_milk_collection_register',
                'scenario' => 'MilkCollectionRegister',
                'title' => 'Milk Collection Register',
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
                'bkg_export' => TRUE,
            ],
            'BmcCollectionRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_collection_register',
                'scenario' => 'BmcCollectionRegister',
                'title' => 'BMC Collection Register',
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
                'bkg_export' => TRUE,
            ],
            'TransporterMaster' => [
                'param' => 'union_code,transporter_code:union_code',
                'sp_name' => 'sp_mis_transporter_master_register',
                'scenario' => '',
                'title' => 'Transporter Master Register',
                'to_decrypt' => ['phone_no', 'Phone No', 'pan_no', 'Pan No'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
            ],
            'VehicleMaster' => [
                'param' => 'union_code,transporter_code:union_code,vehicle_code:transporter_code',
                'sp_name' => 'sp_mis_vehicle_master_register',
                'scenario' => '',
                'title' => 'Vehicle Master Register',
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
            ],
            'SocietyCollectionData' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_society_collection_data',
                'scenario' => 'SocietyCollectionData',
                'title' => '907 - Society Collection Data',
            ],
            'BMCAutomationReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_autmation_date_shift_wise',
                'scenario' => 'BMCAutomationReport',
                'title' => '208 - BMC Automation Report',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BMCAutomationDayWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_autmation_date_wise',
                'scenario' => 'BMCAutomationReport',
                'title' => '208 - BMC Automation Report',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BMCAutomationConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_autmation_consolidation',
                'scenario' => 'BMCAutomationReport',
                'title' => '208 - BMC Automation Report',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberPaymentWithBank' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_payment_drafted_with_bank',
                'scenario' => 'MemberPaymentDrafted',
                'title' => '611 - Member Payment(Drafted)',
                'to_decrypt' => ['Account No', 'IFSC'],
                'report_type' => [Yii::t('app', 'With Bank Detail'), Yii::t('app', 'W/O Bank Detail')],
                'bkg_export' => TRUE,
            ],
            'MemberPaymentWoBank' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_payment_drafted_wo_bank',
                'scenario' => 'MemberPaymentDrafted',
                'title' => '611 - Member Payment(Drafted)',
                'report_type' => [Yii::t('app', 'With Bank Detail'), Yii::t('app', 'W/O Bank Detail')],
                'bkg_export' => TRUE,
            ],
            'CollectionPendriveFile' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,dcs_code:route_code,date:string:shift',
                'sp_name' => 'sp_mis_collection_pendrive_file_format',
                'scenario' => 'CollectionPendriveFile',
                'title' => 'Collection Pendrive File',
                'output_type' => ''
            ],
            'RouteWiseFarmerCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_farmer_collection',
                'scenario' => 'RouteWiseCollection',
                'title' => '209 - Route Wise Collection',
                'report_type' => [Yii::t('app', 'Farmer Collection'), Yii::t('app', 'BMC Collection')],
            ],
            'RouteWiseBMCCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_bmc_collection',
                'scenario' => 'RouteWiseCollection',
                'title' => '209 - Route Wise Collection',
                'report_type' => [Yii::t('app', 'Farmer Collection'), Yii::t('app', 'BMC Collection')],
            ],
            'RouteWiseCollectionSummaryFarmerDateShift' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_collection_summary_farmer_date_shift',
                'scenario' => 'RouteWiseCollectionSummary',
                'title' => '210 - Route Wise Collection Summary',
                'report_type' => [Yii::t('app', 'Farmer Collection Date Shift Wise'), Yii::t('app', 'Farmer Collection Consolidated'), Yii::t('app', 'BMC Collection Date Shift Wise'), Yii::t('app', 'BMC Collection Consolidated')],
            ],
            'RouteWiseCollectionSummaryFarmerConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_collection_summary_farmer_consolidated',
                'scenario' => 'RouteWiseCollectionSummary',
                'title' => '210 - Route Wise Collection Summary',
                'report_type' => [Yii::t('app', 'Farmer Collection Date Shift Wise'), Yii::t('app', 'Farmer Collection Consolidated'), Yii::t('app', 'BMC Collection Date Shift Wise'), Yii::t('app', 'BMC Collection Consolidated')],
            ],
            'RouteWiseCollectionSummaryBmcDateShift' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_collection_summary_bmc_date_shift',
                'scenario' => 'RouteWiseCollectionSummary',
                'title' => '210 - Route Wise Collection Summary',
                'report_type' => [Yii::t('app', 'Farmer Collection Date Shift Wise'), Yii::t('app', 'Farmer Collection Consolidated'), Yii::t('app', 'BMC Collection Date Shift Wise'), Yii::t('app', 'BMC Collection Consolidated')],
            ],
            'RouteWiseCollectionSummaryBmcConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_collection_summary_bmc_consolidated',
                'scenario' => 'RouteWiseCollectionSummary',
                'title' => '210 - Route Wise Collection Summary',
                'report_type' => [Yii::t('app', 'Farmer Collection Date Shift Wise'), Yii::t('app', 'Farmer Collection Consolidated'), Yii::t('app', 'BMC Collection Date Shift Wise'), Yii::t('app', 'BMC Collection Consolidated')],
            ],
            'VendorWiseCollectionSummaryDateShift' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_vendor_wise_date_shift_wise_collection_summary',
                'scenario' => 'VendorWiseCollectionSummary',
                'title' => '211 - Vendor Wise Collection Summary',
                'report_type' => [Yii::t('app', 'Date Shift Wise'), Yii::t('app', 'Consolidated')],
            ],
            'VendorWiseCollectionSummaryConsolidate' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_vendor_wise_consolidated_collection_summary',
                'scenario' => 'VendorWiseCollectionSummary',
                'title' => '211 - Vendor Wise Collection Summary',
                'report_type' => [Yii::t('app', 'Date Shift Wise'), Yii::t('app', 'Consolidated')],
            ],
            'PaymentAbstract' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,payment_cycle_code',
                'sp_name' => 'sp_mis_payment_abstract',
                'scenario' => 'PaymentAbstract',
                'title' => '613 - Payment Abstract',
            ],
            'RateMasterRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code',
                'sp_name' => 'sp_mis_rate_master_register',
                'scenario' => 'RateMasterRegister',
                'title' => 'Rate Master Register',
                'removeExportType' => ['CSV'],
            ],
            'SocietyWiseCdaTwo' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_shift_gyan',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise Variance',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'kartik_grid_view' => 'SocietyWiseCdaTwo'
            ],
            'SocietyWiseCdaDateWiseTwo' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_gyan',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise Variance',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'kartik_grid_view' => 'SocietyWiseCdaDateWiseTwo'
            ],
            'SocietyWiseCdaConsolidatedTwo' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_consolidated_gyan',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise Variance',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'kartik_grid_view' => 'SocietyWiseCdaConsolidatedTwo'
            ],
            'MemberData' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code',
                'sp_name' => 'sp_mis_member_data_report',
                'scenario' => 'MemberData',
                'title' => '909 - Member Data',
            ],
            'MemberReceptionStatus' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_reception_data',
                'scenario' => 'MemberReceptionStatus',
                'title' => '910 - Member Reception Status',
            ],
            'ProductSaleRateMasterRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code',
                'sp_name' => 'sp_mis_sale_rate_applicability_register',
                'scenario' => 'ProductSaleRateMasterRegister',
                'title' => 'Product Sale Rate Register',
                'removeExportType' => ['CSV'],
            ],
            'MilkCollectionData' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift,originating_type:static:originating_type',
                'sp_name' => 'sp_mis_milk_collection_list',
                'scenario' => 'MilkCollectionData',
                'title' => '108 - Milk Collection Data',
            //                'download_day_differe' => '15'
            ],
            'BmcCollectionData' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_collection_list',
                'scenario' => 'BmcCollectionData',
                'title' => '212 - BMC Collection Data',
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'ShiftWiseAutoManual' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_society_raw_data',
                'scenario' => '',
                'title' => '911 - Shift Wise Auto Manual',
            ],
            'MilkAndBmcCollectionMonthlyComparision' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,report_collection_type,type_wise_report:static:type_wise_report',
                'sp_name' => 'sp_mis_milk_and_bmc_collection_monthly_comparision',
                'scenario' => 'MilkAndBmcCollectionMonthlyComparision',
                'title' => '912 - Monthly Comparision Report',
                'custom_report' => 'custom_report',
            ],
            'WeightCollectionList' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_weight_collection_list',
                'scenario' => 'WeightCollectionList',
                'title' => 'BMC Weight Data List',
                'removeExportType' => ['CSV'],
            ],
            'MilkCollectionNotExistsDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_milk_collection_not_exists_detail',
                'scenario' => 'MilkCollectionNotExistsDetail',
                'title' => '109- Milk Collection Not Exists',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
            ],
            'MilkCollectionNotExistsSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_milk_collection_not_exists_summary',
                'scenario' => 'MilkCollectionNotExistsSummary',
                'title' => '109 - Milk Collection Not Exists',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
            ],
            'DayWiseQtyDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_day_wise_qty_detail',
                'scenario' => 'DayWiseQtyDetail',
                'title' => '213 - Day Wise Qty',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
            ],
            'DayWiseQtySummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_day_wise_qty_summary',
                'scenario' => 'DayWiseQtySummary',
                'title' => '213 - Day Wise Qty',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
            ],
            'AdvancePm' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_pm_advance',
                'scenario' => 'AdvancePm',
                'title' => 'PM Advance',
                'bkg_export' => TRUE,
            ],
            'CcMilkPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_cc_milk_payment',
                'scenario' => 'CcMilkPayment',
                'title' => '617 - CC Milk Payment',
            ],
            'FarmerFarmPayment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_farm_payment',
                'scenario' => 'FarmerFarmPayment',
                'title' => '618 - Farmer And Farm Payment',
            ],
            'RateApplicabilityDetailsHistory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_rate_applicability_details_history',
                'scenario' => 'RateApplicabilityDetailsHistory',
                'title' => '913 - Rate Applicability Details History',
            ],
            'MissingShift' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_missing_collection_shift',
                'scenario' => 'MissingShift',
                'title' => 'Missing Shift',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'BMC Collection')],
            ],
            'MissingBmcShift' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_missing_bmc_collection_shift',
                'scenario' => 'MissingShift',
                'title' => 'Missing Shift',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'BMC Collection')],
            ],
            'SapMilkCollectionData' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_milk_collection_data',
                'scenario' => 'SapMilkCollectionData',
                'title' => '405 - SAP Data Export (HATSUN)',
                'dynamic_label' => TRUE,
            ],
            'CleaningFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_cleaning_format',
                'scenario' => 'CleaningFormat',
                'title' => '808 - Cleaning Format',
            ],
            'AlertNotification' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string,module_type',
                'sp_name' => 'sp_alert_notification_list',
                'scenario' => 'AlertNotification',
                'title' => 'Alert Notification',
            ],
            'StockSummary' => [
                'param' => 'union_code,mcc_code:union_code,product_code,p_date:string',
                'sp_name' => 'sp_product_stock_summary',
                'scenario' => 'StockSummary',
                'title' => 'Stock Summary',
            ],
            'StockDetail' => [
                'param' => 'org_type,union_code,mcc_code:union_code,bmc_code,dcs_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'sp_product_stock_detail',
                'scenario' => 'StockDetail',
                'title' => 'Stock Detail',
            ],
            'StockDetailSummary' => [
                'param' => 'org_type,union_code,mcc_code:union_code,bmc_code,dcs_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'sp_product_stock_detail_summary',
                'scenario' => 'StockDetailSummary',
                'title' => 'Stock Detail Summary',
            ],
            'RecoveryFromOtherMember' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,payment_cycle_code:default:dcs',
                'sp_name' => 'sp_recovery_from_other_member',
                'scenario' => 'RecoveryFromOtherMember',
                'title' => '619 - Recovery From Other Member',
            ],
            'RecoveryFromDifferentVendor' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_recovery_from_different_vendor',
                'scenario' => 'RecoveryFromDifferentVendor',
                'title' => '620 - Recovery From Different Vendor',
            ],
            'CenterLossGainReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_mis_CentertoCCLossGainReport',
                'scenario' => 'CenterLossGainReport',
                'title' => '214 - Loss Gain Report',
                'report_type' => [Yii::t('app', 'Loss Gain - Center'), Yii::t('app', 'Loss Gain - BMC')],
            ],
            'BMCLossGainReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_mis_prabhat_loss_gain',
                'scenario' => 'CenterLossGainReport',
                'title' => '214 - Center Loss Gain Report',
                'report_type' => [Yii::t('app', 'Loss Gain - Center'), Yii::t('app', 'Loss Gain - BMC')],
            ],
            'MissingCollectionShiftBmcCrossTab' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_missing_collection_shift_bmc_cross_tab',
                'scenario' => 'MissingCollectionShiftBmcCrossTab',
                'title' => 'Tracking Report',
            ],
            'ProductStockDetailSummarySocietyWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,p_date:string',
                'sp_name' => 'sp_mis_product_stock_detail_summary_society_wise',
                'scenario' => 'ProductStockDetailSummarySocietyWise',
                'title' => 'Product Stock Detail Summary Society Wise',
            ],
            'ProductStockDetailSummaryMccWise' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,p_date:string',
                'sp_name' => 'sp_mis_product_stock_detail_summary_mcc_wise',
                'scenario' => 'ProductStockDetailSummaryMccWise',
                'title' => 'Product Stock Detail Summary MCC Wise',
            ],
            'MemberWiseOutstanding' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,product_type,product_code:product_type,p_date:string',
                'sp_name' => 'sp_mis_member_wise_loan_outstanding',
                'scenario' => 'MemberWiseOutstanding',
                'title' => '305 - Member Wise Outstanding',
            ],
            'DcsWiseOutstanding' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_type,product_code:product_type,p_date:string',
                'sp_name' => 'sp_mis_society_wise_loan_outstanding',
                'scenario' => 'DcsWiseOutstanding',
                'title' => '306 - Society Wise Outstanding',
            ],
            'VendorWiseOutstanding' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,vendor_code:BULKVEN,product_type,product_code:product_type,p_date:string',
                'sp_name' => 'sp_mis_vendor_wise_loan_outstanding',
                'scenario' => 'VendorWiseOutstanding',
                'title' => '307 - Vendor Wise Outstanding',
            ],
            'MccWiseOutstanding' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,product_type,product_code:product_type,p_date:string',
                'sp_name' => 'sp_mis_mcc_wise_loan_outstanding',
                'scenario' => 'MccWiseOutstanding',
                'title' => '308 - MCC Wise Outstanding',
            ],
            'MemberProductSaleForPaidInstallment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string,to_date:string',
                'sp_name' => 'mis_product_sale_member_wise_for_paid_installment',
                'scenario' => 'MemberProductSaleForPaidInstallment',
                'title' => '309 - Member Wise Product Sale for Paid Installment',
            ],
            'DcsProductSaleForPaidInstallment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_product_sale_society_wise_for_paid_installment',
                'scenario' => 'DcsProductSaleForPaidInstallment',
                'title' => '310 - DCS Wise Product Sale for Paid Installment',
            ],
            'VendorProductSaleForPaidInstallment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,vendor_code:BULKVEN,from_date:string,to_date:string',
                'sp_name' => 'mis_product_sale_customer_wise_for_paid_installment',
                'scenario' => 'VendorProductSaleForPaidInstallment',
                'title' => '311 - Vendor Wise Product Sale for Paid Installment',
            ],
            'MccProductSaleForPaidInstallment' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_mcc_wise_all_product_sale',
                'scenario' => 'MccProductSaleForPaidInstallment',
                'title' => '312 - MCC Wise Product Sale for Paid Installment',
            ],
            'NewMemberPouringMilk' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_new_member_pouring_milk',
                'scenario' => 'NewMemberPouringMilk',
                'title' => '313 - New Member Pouring Milk',
            ],
            'NewCustomerPouringMilk' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_collection_shift_for_new_customer',
                'scenario' => 'NewCustomerPouringMilk',
                'title' => '215 - New Customer Pouring Milk',
            ],
            'UmangSapReportDaily' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_data_daily_umang',
                'scenario' => 'UmangSapReport',
                'title' => '406 - Daily Data Export(UMANG)',
                'report_type' => [Yii::t('app', 'Daily'), Yii::t('app', 'Weekly')],
                'kartik_grid_view' => 'UmangSapReportDaily',
            ],
            'UmangSapReportWeekly' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sap_data_weekly_umang',
                'scenario' => 'UmangSapReport',
                'title' => '406 - Weekly Data Export(UMANG)',
                'report_type' => [Yii::t('app', 'Daily'), Yii::t('app', 'Weekly')],
                'kartik_grid_view' => 'UmangSapReportWeekly',
            ],
            'BmcCollectionHistory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift,action_perform',
                'sp_name' => 'sp_mis_history_tbl_bmc_collection',
                'scenario' => 'BmcCollectionHistory',
                'title' => '216 - BMC Collection History',
            ],
            'SocietyCompositeVsActual' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_cda_date_shift_mmd',
                'scenario' => 'SocietyCompositeVsActual',
                'title' => '217 - Society Composite Vs Actual Report',
            ],
            'SdFileSummary' => [
                'param' => 'union_code,channel_code,bmc_code:channel_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_mcc_shift_lock_data',
                'scenario' => 'SdFileSummary',
                'title' => '407 - SD File Summary',
            ],
            'SocietyWiseCdaFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_shift_format_2',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA Format 2',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            'SocietyWiseCdaDateWiseFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_format_2',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA Format 2',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            'SocietyWiseCdaConsolidatedFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_consolidated_format_2',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA Format 2',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            'VmReportSap' => [
                //                'param' => 'union_code,mcc_code:union_code,bmc_code,date:string:shift',
                'param' => 'union_code,mcc_code:union_code,bmc_code,date:string:shift',
                'sp_name' => 'mis_bmc_collection_vm',
                'scenario' => 'SapReport',
                'title' => 'SAP VM Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ')],
                'export_title' => true,
                //                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                'sap_download' => true,
                'output_type' => false,
            ],
            'WqReportSap' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,date:string:shift',
                'sp_name' => 'mis_bmc_collection_wq',
                'scenario' => 'SapReport',
                'title' => 'SAP WQ Report',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ')],
                'export_title' => true,
                //                'message' => Yii::t('app', 'Sync of data is pending from device.'),
//                'url1' => ['SAP Files Process', '/bkgprocess/tbl-ftp-txn-log/index', true],
                'sap_download' => true,
                'output_type' => false,
            ],
            'SapReportCdpl' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_vmcc_collection_date_wise',
                'scenario' => 'SapReportCdpl',
                'title' => 'SAP VM Report',
                'export_title' => true,
                'sap_download' => true,
                'output_type' => false,
                'multiArray' => ['dcs_code']
            ],
            'TankerReport' => [
                'param' => 'union_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_tanker_dispatch',
                'scenario' => 'TankerReport',
                'title' => 'Tanker Report',
            ],
            'PaymentDifference' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,payment_cycle_code:type_check',
                'sp_name' => 'sp_mis_payment_difference',
                'scenario' => 'PaymentDifference',
                'title' => '621 - Payment Difference',
            ],
            'SapUploadSummary' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift,sap_file:static:sap_file',
                'sp_name' => 'sp_mis_WQ_file_summary',
                'scenario' => 'SapUploadSummary',
                'title' => 'WQ/VM File Summary',
            ],
            'AutoManualMilkCollection' => [
                'param' => 'union_code,channel_code,bmc_code:channel_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_auto_manual_milk_collection_report',
                'scenario' => 'AutoManualMilkCollection',
                'title' => 'Auto Manual Report',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'BMC Collection')],
            ],
            'AutoManualBMCCollection' => [
                'param' => 'union_code,channel_code,bmc_code:channel_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_auto_manual_bmc_collection_report',
                'scenario' => 'AutoManualMilkCollection',
                'title' => 'Auto Manual Report',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'BMC Collection')],
            ],
            'FileGenerateStatus' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_file_status',
                'scenario' => 'FileGenerateStatus',
                'title' => '914 - File Generate Status',
            ],
            'SocietyWiseRateDifferenceReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_society_wise_rate_different',
                'scenario' => 'SocietyWiseRateDifferenceReport',
                'title' => '915 - Society Wise Rate Difference Report',
            ],
            'MccBilling' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'process_remuneration_payment_row_pivoting ',
                'scenario' => 'MccBilling',
                'title' => '225 - Mcc Billing',
                'multiArray' => ['mcc_code', 'bmc_code'],
            ],
            'BmcRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_bmc_register',
                'scenario' => 'BmcRegister',
                'title' => 'BMC Dispatch Register',
            ],
            'PlantRegister' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string,plant_register_type',
                'sp_name' => 'mis_plant_register',
                'scenario' => 'PlantRegister',
                'title' => 'Plant Receipt Register'
            ],
            'TankerReceiptNote' => [
                'param' => 'union_code,from_date:string,to_date:string,trip_code,grn_no',
                'sp_name' => 'mis_tanker_receipt_note',
                'scenario' => 'TankerReceiptNote',
                'title' => 'Tanker Receipt Report',
            ],
            'MccReceiptVsBmcDispatch' => [
                'param' => 'union_code,from_date:string,to_date:string,bmc_code:union_code',
                'sp_name' => 'mis_mcc_receipt_vs_bmc_dispatch',
                'scenario' => 'MccReceiptVsBmcDispatch',
                'title' => 'Trip Wise Detail',
                'report_type' => [Yii::t('app', 'Summary Wise'), Yii::t('app', 'Detail Wise')],
            ],
            'MccReceiptVsBmcDispatchDetail' => [
                'param' => 'union_code,from_date:string,to_date:string,bmc_code:union_code',
                'sp_name' => 'mis_mcc_receipt_vs_bmc_dispatch_detail',
                'scenario' => 'MccReceiptVsBmcDispatch',
                'title' => 'Trip Wise Detail',
                'report_type' => [Yii::t('app', 'Summary Wise'), Yii::t('app', 'Detail Wise')],
            ],
            'MccDayBookDispatchHubHorizontal' => [
                'param' => 'union_code,bmc_code:union_code,from_date:string,to_date:string',
                'sp_name' => 'mis_mcc_day_book_dispatch_hub_horizontal',
                'scenario' => 'MccDayBookDispatchHubHorizontal',
                'title' => 'MCC Day Book',
            ],
            'StockDispatchToMccFromStore' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_dispatch_to_cc_from',
                'scenario' => 'StockDispatchToMccFromStore',
                'title' => 'Stock Dispatch To MCC',
            ],
            'StockReceivedToMcc' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_received_to_cc_from',
                'scenario' => 'StockReceivedToMcc',
                'title' => 'Stock Received To MCC',
            ],
            'StockTransferToDcs' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,p_date:string',
                'sp_name' => 'mis_stock_transfer_from_to_destination',
                'scenario' => 'StockTransferToDcs',
                'title' => 'Stock Transfer To DCS',
            ],
            'StockAtMcc' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,p_date:string',
                'sp_name' => 'mis_stock_to_cc',
                'scenario' => 'StockAtMcc',
                'title' => 'Stock At MCC',
                'report_type' => [Yii::t('app', 'Without Amount'), Yii::t('app', 'With Amount')],
            ],
            'StockAtMccAmount' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,p_date:string',
                'sp_name' => 'mis_stock_to_cc_amount',
                'scenario' => 'StockAtMcc',
                'title' => 'Stock At MCC',
                'report_type' => [Yii::t('app', 'Without Amount'), Yii::t('app', 'With Amount')],
            ],
            'StockAtDcs' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,p_date:string',
                'sp_name' => 'mis_stock_to_vlcc',
                'scenario' => 'StockAtDcs',
                'title' => 'Stock At DCS',
            ],
            'SaleReportFarmer' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,product_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_farmer_product_sale_report',
                'scenario' => 'SaleReportFarmer',
                'title' => 'Farmer Sale Report',
                'report_type' => ['2' => Yii::t('app', 'All'), '0' => Yii::t('app', 'Unlock'), '1' => Yii::t('app', 'Lock')],
            ],
            'SaleReportVendor' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,product_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_vendor_product_sale_report',
                'scenario' => 'SaleReportVendor',
                'title' => 'Vendor Sale Report',
                'report_type' => ['2' => Yii::t('app', 'All'), '0' => Yii::t('app', 'Unlock'), '1' => Yii::t('app', 'Lock')],
            ],
            'SummaryReportMcc' => [
                'param' => 'union_code,plant_code,mcc_code,as_on_date:string',
                'sp_name' => 'mis_summary_report_at_cc',
                'scenario' => 'SummaryReportMcc',
                'title' => 'Summary Report - MCC',
            ],
            'SummaryReportDcs' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_vlcc_stock_summary_report',
                'scenario' => 'SummaryReportDcs',
                'title' => 'Summary Report - DCS',
            ],
            'MemberCollectionReportForSap' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,route_code:all_routes,dcs_code:route_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'portal_export_milk_collection',
                'scenario' => 'MemberCollectionReportForSap',
                'title' => '111 - Member Collection Report For SAP',
            ],
            'RmrdMilkCollectionForSap' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'portal_export_bmc_collection',
                'scenario' => 'RmrdMilkCollectionForSap',
                'title' => '218 - BMC Collection Report For SAP',
            ],
            'MilkCollectionProc' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_milk_collection_proc_per',
                'scenario' => 'MilkCollectionProc',
                'title' => '112 - VLCs comparison summary',
            ],
            'MilkCollectionProcDetail' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_milk_collection_proc_per_detail',
                'scenario' => 'MilkCollectionProcDetail',
                'title' => '113 - VLCs comparison details',
            ],
            'MilkCollectionAbsent' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_milk_collection_absent_last_few_days',
                'scenario' => 'MilkCollectionAbsent',
                'title' => '114 - Absent Pouring VLCs',
            ],
            'LeftPourer' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_left_pourers',
                'scenario' => 'LeftPourer',
                'title' => '115 - VLC Left Pourers',
            ],
            'MilkCollectionNegativeGroth' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_milk_collection_negative_growth',
                'scenario' => 'MilkCollectionNegativeGroth',
                'title' => '116 - Negative Growth VLCs',
            ],
            'BmcMilkCollectionProc' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_bmc_collection_proc_per',
                'scenario' => 'BmcMilkCollectionProc',
                'title' => '117 - MCC comparison summary',
            ],
            'BmcMilkCollectionProcDetail' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_bmc_collection_proc_per_detail',
                'scenario' => 'BmcMilkCollectionProcDetail',
                'title' => '118 - MCC comparison details',
            ],
            'AntibioticReport' => [
                'param' => 'union_code,mcc_code:union_code,channel_code,bmc_code:channel_code:false,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_antibiotic',
                'scenario' => 'AntibioticReport',
                'title' => 'Antibiotic Report',
            ],
            'MccRecipationSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,payment_cycle_code',
                'sp_name' => 'mis_mcc_recipation_summary_jgf',
                'scenario' => 'MccRecipationSummary',
                'title' => 'MCC Recipation Summary',
            ],
            'MccRecipationDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,payment_cycle_code',
                'sp_name' => 'mis_mcc_recipation_detail_jgf',
                'scenario' => 'MccRecipationDetail',
                'title' => 'MCC Recipation Detail',
            ],
            'StockDispatchToSale' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_mcc_wise_grn_stock_summary',
                'scenario' => 'StockDispatchToSale',
                'title' => 'Stock Dispatch To Sale Report',
            ],
            'StockRegisterToSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_dcs_wise_sap_batch_wise',
                'scenario' => 'StockRegisterToSap',
                'title' => 'DCS Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary')],
            ],
            'StockRegisterToProduct' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_dcs_wise_product_wise',
                'scenario' => 'StockRegisterToSap',
                'title' => 'DCS Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary')],
            ],
            'StockRegisterToSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_dcs_wise_summary',
                'scenario' => 'StockRegisterToSap',
                'title' => 'DCS Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary')],
            ],
            'StockRegisterMccToSap' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_mcc_wise_sap_batch_wise',
                'scenario' => 'StockRegisterMccToSap',
                'title' => 'MCC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary')],
            ],
            'StockRegisterMccToProduct' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_mcc_wise_product_wise',
                'scenario' => 'StockRegisterMccToSap',
                'title' => 'MCC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary')],
            ],
            'StockRegisterMccToSummary' => [
                'param' => 'union_code,plant_code,mcc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_mcc_wise_summary',
                'scenario' => 'StockRegisterMccToSap',
                'title' => 'MCC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary')],
            ],
            'MemberMilkBill' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_wise_milk_bill_date_shift_wise',
                'scenario' => 'MemberMilkBill',
                'title' => '119 - Farmer Wise Milk Bill',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'MemberMilkBillDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_wise_milk_bill_date_wise',
                'scenario' => 'MemberMilkBill',
                'title' => '119 - Farmer Wise Milk Bill',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'MemberMilkBillSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_wise_milk_bill_summary',
                'scenario' => 'MemberMilkBill',
                'title' => '119 - Farmer Wise Milk Bill',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'AgentWiseReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_details_date_shift_wise_reconcilation',
                'scenario' => 'AgentWiseReconciliation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE
            ],
            'AgentWiseReconciliationDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_details_date_wise_reconcilation',
                'scenario' => 'AgentWiseReconciliation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE
            ],
            'AgentWiseReconciliationSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_agent_wise_reconcilation',
                'scenario' => 'AgentWiseReconciliation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE
            ],
            'RouteWiseReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_route_wise_reconcilation',
                'scenario' => 'RouteWiseReconciliation',
                'title' => '220 - Route Wise Reconciliation',
            ],
            'VlccTransactionDataReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_vlcc_transaction_ftp_data',
                'scenario' => 'VlccTransactionDataReport',
                'title' => 'VLCC Transaction Data Report',
                'bkg_export' => TRUE
            ],
            'BmcWiseSocietyWiseAutoManual' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_society_wise_auto_manual_details',
                'scenario' => 'BmcWiseSocietyWiseAutoManual',
                'title' => 'BMC Wise Society Wise Auto Manual',
                'report_type' => [Yii::t('app', 'Details '), Yii::t('app', 'Summary')],
            ],
            'BmcWiseSocietyWiseAutoManualSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_society_wise_auto_manual_summary',
                'scenario' => 'BmcWiseSocietyWiseAutoManual',
                'title' => 'BMC Wise Society Wise Auto Manual',
                'report_type' => [Yii::t('app', 'Details '), Yii::t('app', 'Summary')],
            ],
            'BmcWiseAutoManualSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_wise_auto_manual_summary',
                'scenario' => 'BmcWiseAutoManualSummary',
                'title' => 'BMC Wise Auto Manual Summary',
            ],
            'MpgPaymentBillStatement' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_mpg_payment_bill_statement',
                'scenario' => 'MpgPaymentBillStatement',
                'title' => 'MPG Payment Bill Statement',
            ],
            'MilkCollectionHistory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_history_tbl_milk_collection',
                'scenario' => 'MilkColllectionHistory',
                'title' => 'Milk Collection History',
            ],
            'MemberHistory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,p_date:string',
                'sp_name' => 'sp_mis_history_tbl_member_master',
                'scenario' => 'MemberHistory',
                'title' => 'Member History',
            ],
            'DcsHistory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,p_date:string',
                'sp_name' => 'sp_mis_history_tbl_dcs_master',
                'scenario' => 'DcsHistory',
                'title' => 'Dcs History',
            ],
            'RmrdDataExport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_date_shift_wise_vrs',
                'scenario' => 'RMRDDataExport',
                'title' => 'RMRD Data Export',
            ],
            'CpliabilityReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,from_date:string,to_date:string',
                'sp_name' => 'mis_cp_wise_liability_wdpl',
                'scenario' => 'CpliabilityReport',
                'title' => 'CP liability Report',
            ],
            'BonusReport' => [
                'param' => 'report_type,union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string,to_date:string',
                'sp_name' => 'mis_member_bonus_payment_summary_vrs',
                'scenario' => 'BonusReport',
                'title' => 'Bonus Report',
                'report_type' => ['1' => Yii::t('app', 'Member Wise Detail'), '2' => Yii::t('app', 'Member Wise Consolidate'), '3' => Yii::t('app', 'Society Wise Detail'), '4' => Yii::t('app', 'Society Wise Consolidate')],
            ],
            'MemberPaymentBankFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_member_payment_bank_format',
                'scenario' => 'MemberPaymentBankFormat',
                'title' => '624 - Member Payment Bank Format',
            ],
            'MemberPassbookRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_member_collection_passbook',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            //                'download_day_differe' => '15'
            ],
            'MemberDailyCollectionRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_member_collection_day_report',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            //                'download_day_differe' => '15'
            ],
            'MemberConsolidatedRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_member_collection_summary',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            //                'download_day_differe' => '15'
            ],
            //102
            'DcsCollDateShiftSummaryRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'mis_are_wise_society_wise_milk_collection_date_shift_wise',
                'title' => '102 - Society Collection Date And Shift Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsCollDateWiseSummaryRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'mis_are_wise_society_wise_milk_collection_date_wise',
                'title' => '102 - Society Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'DcsCollectionConsolidateRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'mis_are_wise_society_wise_milk_collection_consolidated',
                'title' => '102 - Society Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MilkCollectionProcDetailRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,from_date:string,to_date:string',
                'sp_name' => 'mis_are_wise_milk_collection_proc_per_detail',
                'title' => '113 - VLCs comparison details',
            ],
            'AgentWiseReconciliationRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_date_shift_wise_reconcilation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'AgentWiseReconciliationDateWiseRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_details_date_wise_reconcilation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'AgentWiseReconciliationSummaryRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_agent_wise_reconcilation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'CenterLossGainReportRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_CentertoCCLossGainReport',
                'title' => '214 - Loss Gain Report',
                'report_type' => [Yii::t('app', 'Loss Gain - Center'), Yii::t('app', 'Loss Gain - BMC')],
            ],
            'BMCLossGainReportRegionRegion' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_are_wise_prabhat_loss_gain',
                'title' => '214 - Center Loss Gain Report',
                'report_type' => [Yii::t('app', 'Loss Gain - Center'), Yii::t('app', 'Loss Gain - BMC')],
            ],
            'MilkShortageRecovery' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_milk_shortage_recovery',
                'scenario' => 'MilkShortageRecovery',
                'title' => '626 - Milk Shortage Recovery',
                'bkg_export' => TRUE,
            ],
            'CdaReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_cda_date_shift_wise_shivprasad',
                'scenario' => 'CDAReport',
                'title' => '626 - CDA Report',
                'bkg_export' => TRUE,
            ],
            'VehicleMasterHistory' => [
                'param' => 'transporter_code',
                'sp_name' => 'portal_history_tbl_vehicle_master',
                'title' => 'Vehicle Master History',
            ],
            'TallyReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,date_payment_cycle,report_collection_type',
                'sp_name' => 'mis_milk_collection_bill_shivprasad',
                'scenario' => 'TallyReport',
                'title' => '916 - Tally Report',
                'report_type' => [Yii::t('app', 'Tally Report'), Yii::t('app', 'Tally Consolidated Report')],
            ],
            'TallyConsolidatedReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,date_payment_cycle,report_collection_type',
                'sp_name' => 'mis_milk_collection_bill_shivprasad_consolidated',
                'scenario' => 'TallyReport',
                'title' => '916 - Tally Consolidated Report',
                'report_type' => [Yii::t('app', 'Tally Report'), Yii::t('app', 'Tally Consolidated Report')],
            ],
            'VehicleMasterHistory' => [
                'param' => 'transporter_code',
                'sp_name' => 'portal_history_tbl_vehicle_master',
                'title' => 'Vehicle Master History',
            ],
            'BiplData' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_ftp_bipl_data',
                'scenario' => 'BiplData',
                'title' => 'Bipl Data',
                'report_type' => ['All' => Yii::t('app', 'All'), 'Online' => Yii::t('app', 'Online'), 'Pendrive' => Yii::t('app', 'Pendrive')],
            ],
            'DetailsReport' => [
                'param' => 'union_code,login_user_code,from_date:string,to_date:string',
                'sp_name' => 'mis_user_attendance',
                'scenario' => 'DetailsReport',
                'title' => 'Details Report',
            ],
            'AllReportRequest' => [
                'param' => 'from_date:string,to_date:string,user_code,report_req_status',
                'sp_name' => 'mis_all_report_request',
                'scenario' => 'AllReportRequest',
                'title' => 'All Report Request',
            ],
            'RegionWiseUserAttendanceReport' => [
                'param' => 'union_code,state_code,region_code,area_code,user_code,from_date:string,to_date:string',
                'sp_name' => 'mis_user_attendance_region_wise',
                'scenario' => 'RegionWiseUserAttendanceReport',
                'title' => 'Region-wise User Attendance Report',
            ],
            'BiplDataAdmin' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_ftp_bipl_data_admin',
                'scenario' => 'BiplDataAdmin',
                'title' => 'Bipl Data Admin',
                'report_type' => ['All' => Yii::t('app', 'All'), 'Online' => Yii::t('app', 'Online'), 'Pendrive' => Yii::t('app', 'Pendrive')],
            ],
            'PurchaseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_vsp_payment_for_glt',
                'scenario' => 'PurchaseSummary',
                'title' => '628 - Purchase Summary',
                'bkg_export' => TRUE,
            ],
            'PurchaseSummaryFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,payment_cycle_code:type_check',
                'sp_name' => 'mis_vendor_payment_for_devmilk',
                'scenario' => 'PurchaseSummaryFormat',
                'title' => '629 - Purchase Summary format 2',
            ],
            'MilkVan' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_type',
                'sp_name' => 'mis_milk_van',
                'scenario' => 'MilkVan',
                'title' => 'MilkVan',
                'report_type' => ['Time management' => Yii::t('app', 'Time management'), 'capacity utilizations' => Yii::t('app', 'capacity utilizations')],
            ],
            'DcsWiseBillHeadApplicability' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,payment_type,from_date:string,to_date:string',
                'sp_name' => 'mis_dcs_bill_head_applicability',
                'scenario' => 'DcsWiseBillHeadApplicability',
                'title' => '509 - DCS Wise Bill Head Applicability',
            ],
            'QualityCollectionReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_quality_collection',
                'scenario' => 'QualityCollectionReport',
                'title' => 'Quality Collection Report',
            ],
            'PaymentSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_type',
                'sp_name' => 'sp_mis_member_vsp_billing_consolidate',
                'scenario' => 'PaymentSummary',
                'title' => '630 - Payment Summary',
                'report_type' => [
                    'all' => Yii::t('app', 'All'),
                    'dcswise' => Yii::t('app', 'DCS Wise'),
                    'vspwise' => Yii::t('app', 'VSP Wise'),
                    'remuneration' => Yii::t('app', 'Remuneration')
                ],
                'bkg_export' => TRUE,
            ],
            'SapWqFile' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_wq_vrs_newasa',
                'scenario' => 'SapWqFile',
                'title' => 'SAP WQ File',
                'export_file_name' => 'Plant_Code_WQ_from_date_from_shift',
                'multiArray' => ['mcc_code', 'bmc_code'],
                'downloadFormat' => 'csv',
                'report_type' => [Yii::t('app', 'CSV'), Yii::t('app', 'Excel')],
            ],
            'SapWqFileXls' => [
                'param' => 'union_code,mcc_code:union_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_wq_vrs_newasa_xls',
                'scenario' => 'SapWqFile',
                'title' => 'SAP WQ File',
                'export_file_name' => 'Plant_Code_WQ_from_date_from_shift',
                'multiArray' => ['mcc_code', 'bmc_code'],
                'report_type' => [Yii::t('app', 'CSV'), Yii::t('app', 'Excel')],
            ],
            'MemberDailyCollectionCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_day_wise_report_common',
                'scenario' => 'MemberDailyCollectionCommon',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                //                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberPassbookCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_passbook_common',
                'scenario' => 'MemberDailyCollectionCommon',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                //                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberConsolidatedCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary_common',
                'scenario' => 'MemberDailyCollectionCommon',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                //                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'DcsCollDateShiftSummaryCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_date_shift_wise_common',
                'scenario' => 'DcsCollDateShiftSummaryCommon',
                'title' => '102 - Society Collection Date And Shift Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'DcsCollDateWiseSummaryCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_date_wise_common',
                'scenario' => 'DcsCollDateShiftSummaryCommon',
                'title' => '102 - Society Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'DcsCollectionConsolidateCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_society_wise_milk_collection_consolidated_common',
                'scenario' => 'DcsCollDateShiftSummaryCommon',
                'title' => '102 - Society Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'BmcCollectionShiftReportCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,route_type_trans:static:route_type_trans',
                'sp_name' => 'sp_mis_bmc_collection_shift_report_common',
                'scenario' => 'BmcCollectionShiftReportCommon',
                'title' => '201 - BMC Collection Shift Report',
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'BmcCollDateShiftWiseSummaryCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_wise_society_collection_date_shift_wise_common',
                'scenario' => 'BmcCollDateShiftWiseSummaryCommon',
                'title' => '202 - BMC Collection Date And Shift Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
            ],
            'ConsolidatedWithBank' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_collection_consolidated_with_bank',
                'scenario' => 'BmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
            ],
            'BmcCollDateWiseSummaryCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_wise_soceity_collection_date_wise_common',
                'scenario' => 'BmcCollDateShiftWiseSummaryCommon',
                'title' => '202 - BMC Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollConsolidatedCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_bmc_collection_consolidated_common',
                'scenario' => 'BmcCollDateShiftWiseSummaryCommon',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'SocietyWiseCdaCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_shift_common',
                'scenario' => 'SocietyWiseCdaCommon',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'SocietyWiseCdaDateWiseCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_common',
                'scenario' => 'SocietyWiseCdaCommon',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'SocietyWiseCdaConsolidatedCommon' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_consolidated_common',
                'scenario' => 'SocietyWiseCdaCommon',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'BmcCollectionSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_summary_devmilk',
                'scenario' => 'BmcCollectionSummary',
                'title' => 'Bmc Collection Summary',
            ],
            'VendorPaymentFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_type',
                'sp_name' => 'mis_vendor_payment_for_cargil',
                'scenario' => 'VendorPaymentFormat',
                'title' => '631 - Vendor Payment Format 2',
                'report_type' => ['all' => Yii::t('app', 'All'), 'dcswise' => Yii::t('app', 'DCS Wise'), 'vspwise' => Yii::t('app', 'VSP Wise'), 'remuneration' => Yii::t('app', 'Remuneration')
                ],
            ],
            'RootWiseDifference' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_summary_anig',
                'scenario' => 'RootWiseDifference',
                'title' => 'Root Wise Difference',
            ],
            'BmcCollectionSummaryRahema' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_date_shift_wise_rheman',
                'scenario' => 'BmcCollectionSummaryRahema',
                'title' => 'BMC Collection Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollectionDateWiseRheman' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_date_wise_rheman',
                'scenario' => 'BmcCollectionSummaryRahema',
                'title' => 'BMC Collection Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'BmcCollectionConsolidatedRaheman' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_consolidated_rheman',
                'scenario' => 'BmcCollectionSummaryRahema',
                'title' => 'BMC Collection Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MobileAppReport' => [
                'param' => 'user_login_type,login_user_code,from_date:string:from_shift,to_date:string:to_shift,department',
                'sp_name' => 'proc_flutter_app_tracking',
                'scenario' => 'MobileAppReport',
                'title' => 'Log report of  the Mobile App',
            ],
            'FarmerRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_member_register_format_2',
                'scenario' => 'FarmerRegister',
                'title' => 'Farmer Register Format 2',
                'to_decrypt' => ['pan_no', 'Pan No', 'dob', 'Dob', 'adhar_no', 'aadhaar_no'],
                'to_text' => ['aadhaar_no', 'adhar_no', 'bank_account_no'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
            //                'output_type' => FALSE
            ],
            'FieldStaffActivity' => [
                'param' => 'union_code,state_code,region_code,area_code,user_code,from_date:string,to_date:string',
                'sp_name' => 'mis_field_staff_activity',
                'scenario' => 'FieldStaffActivity',
                'title' => 'User Task Activity MIS',
            ],
            'MemberProvisionalFamilyDetail' => [
                'param' => 'union_code,from_date:string,to_date:string',
                'sp_name' => 'mis_import_member_provisional_data_saahaj',
                'scenario' => 'MemberProvisionalFamilyDetail',
                'title' => 'Member Provisional Family Detail',
            ],
            'MemberProvisionalSapExport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_member_pib_upload_saahaj',
                'scenario' => 'MemberProvisionalSapExport',
                'title' => 'Member Provisional SAP Export',
                'to_decrypt' => ['pan_no', 'Pan No', 'dob##d.m.Y', 'Dob', 'adhar_no', 'aadhaar_no'],
                'to_text' => ['aadhaar_no', 'adhar_no'],
            ],
            'ExportProvisionalMemberBankReceipt' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,as_on_date:string',
                'sp_name' => 'mis_member_bank_receipt_data_saahaj',
                'scenario' => 'ExportProvisionalMemberBankReceipt',
                'title' => 'Export Provisional Member Bank Receipt',
            ],
            'MilkCollectionStatusReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,report_type',
                'sp_name' => 'mis_milk_collection_status_report',
                'scenario' => 'MilkCollectionStatusReport',
                'title' => 'Milk Collection Status Report',
                'report_type' => [Yii::t('app', 'BMC Wise'), Yii::t('app', 'Company Wise')],
            ],
            'MilkCollectionFilterBased' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift,report_type,from_value:txt,to_value:txt',
                'sp_name' => 'mis_milk_collection_filter_based',
                'scenario' => 'MilkCollectionFilterBased',
                'title' => 'Milk Collection Filter Based Report',
                'report_type' => ['Attendance' => Yii::t('app', 'Attendance'), 'qty' => Yii::t('app', 'Qty'), 'fat' => Yii::t('app', 'FAT'), 'snf' => Yii::t('app', 'SNF'), 'amount' => Yii::t('app', 'Amount')],
            ],
            'MilkCollectionAudit' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_mis_member_collection_audit',
                'scenario' => 'MilkCollectionAudit',
                'title' => 'Milk Collection Audit',
            ],
            'MemberMilkCollection' => [
                'param' => 'basis_on,union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_mis_member_milk_collection_all_report',
                'scenario' => 'MemberMilkCollection',
                'title' => 'Member Milk Collection',
            ],
            'MemberMilkCollectionDcsWise' => [
                'param' => 'basis_on,union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_mis_member_milk_collection_society_wise_all_report',
                'scenario' => 'MemberMilkCollectionDcsWise',
                'title' => 'Member Milk Collection Society wise',
            ],
            'DcsBmcMemberWiseTopCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string,top_collection_on:static:top_collection_on,top_value:txt,param_type:static:param_type',
                'sp_name' => 'rpt_mis_top_society_and_member_all_report',
                'scenario' => 'DcsBmcMemberWiseTopCollection',
                'title' => 'DCS/BMC/Member Wise Top Collection',
            ],
            'FatAnalysisReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string,milk_type',
                'sp_name' => 'mis_dcs_wise_fat_analysis_report',
                'scenario' => 'FatAnalysisReport',
                'title' => 'FAT Analysis Report',
                'kartik_grid_view' => 'FatAnalysisReport'
            ],
            'DcsAndMemberWiseQtyCompare' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,f_spr_date:string,t_spr_date:string,f_cmpr_date:string,t_cmpr_date:string,animal_type',
                'sp_name' => 'mis_society_and_member_wise_qty_compair_report',
                'scenario' => 'DcsAndMemberWiseQtyCompare',
                'title' => 'DCS & Member Wise Qty Compare',
            ],
            'SapDataExportForDeduction' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_sap_data_export_for_deduction',
                'scenario' => 'SapDataExportForDeduction',
                'title' => 'SAP Data export for Deduction',
            ],
            'SapDataExportForVlcReplacement' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_sap_data_export_for_vlc_replacement',
                'scenario' => 'SapDataExportForVlcReplacement',
                'title' => 'SAP Data export for VLC Replacement',
            ],
            'StockRegisterBmcToSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_bmc_wise_sap_batch_wise',
                'scenario' => 'StockRegisterBmcToSap',
                'title' => 'BMC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary'), Yii::t('app', 'Product and Type Wise')],
            ],
            'StockRegisterBmcToProduct' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_bmc_wise_product_wise',
                'scenario' => 'StockRegisterBmcToSap',
                'title' => 'BMC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary'), Yii::t('app', 'Product and Type Wise')],
            ],
            'StockRegisterBmcToSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_bmc_wise_summary',
                'scenario' => 'StockRegisterBmcToSap',
                'title' => 'BMC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary'), Yii::t('app', 'Product and Type Wise')],
            ],
            'StockRegisterBmcAndTypeWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_stock_register_bmc_wise_product_wise_type_wise',
                'scenario' => 'StockRegisterBmcToSap',
                'title' => 'BMC Wise Stock',
                'report_type' => [Yii::t('app', 'SAP Batch Wise'), Yii::t('app', 'Product Wise'), Yii::t('app', 'Summary'), Yii::t('app', 'Product and Type Wise')],
            ],
            'AssetDetailsReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,current_status:static:asset_detail_status',
                'sp_name' => 'mis_asset_details_report',
                'scenario' => 'AssetDetailsReport',
                'title' => 'Asset Details Report',
            ],
            'UserOrganizationMappingReport' => [
                'param' => 'login_type:static:login_type,department',
                'sp_name' => 'mis_user_organization_mapping_report',
                'title' => 'User Organization Mapping Report',
            ],
            'AppStartupReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_milk_collection_app_startup_status',
                'scenario' => 'AppStartupReport',
                'title' => 'App Startup Report',
            ],
            'MilkCollectionStatusDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_milk_collection_status_summary',
                'scenario' => 'MilkCollectionStatusDetail',
                'title' => 'Milk Collection Status Detail',
            ],
            'EiplInstalledUsersDetails' => [
                'param' => 'union_code,state_code,region_code,area_code,user_login_type,department',
                'sp_name' => 'mis_eipl_installed_users_details',
                'scenario' => 'EiplInstalledUsersDetails',
                'title' => 'Eipl Installed Users Details',
            ],
            'SapDataExportFeedSaleMember' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_sap_data_export_feed_sale_member',
                'scenario' => 'SapDataExportFeedSaleMember',
                'title' => 'SAP Data Export Feed Sale Member',
            ],
            'PaymentCycleApplicabilityStatus' => [
                'param' => 'union_code,plant_code,mcc_code,customer_type,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_payment_cycle_applicability',
                'scenario' => 'PaymentCycleApplicabilityStatus',
                'title' => 'Payment Cycle Applicabilit Status',
            ],
            'IndentSummaryDetail' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_mcc_wise_indent_summary',
                'scenario' => 'IndentSummaryDetail',
                'title' => 'Indent Summary Detail',
            ],
            'GheeGroupIndentReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_ghee_indent_download_sap',
                'scenario' => 'GheeGroupIndentReport',
                'title' => 'Ghee Group Indent Report',
            ],
            'CfGroupIndentReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_cf_indent_download_sap',
                'scenario' => 'CfGroupIndentReport',
                'title' => 'CF Group Indent Report',
            ],
            'SapGheeGroupIndentReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_ghee_indent_upload_sap',
                'scenario' => 'SapGheeGroupIndentReport',
                'title' => 'SAP Ghee Group Indent Report',
            ],
            'SapCfGroupIndentReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_cf_indent_upload_sap',
                'scenario' => 'SapCfGroupIndentReport',
                'title' => 'SAP CF Group Indent Report',
            ],
            'BillHeadDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,bill_head_code,from_date:string,to_date:string',
                'sp_name' => 'mis_bill_head_detail',
                'scenario' => 'BillHeadDetail',
                'title' => 'Bill Head Detail',
            ],
            'ApprovedAttachmentDetails' => [
                'param' => 'from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_approved_attachment_details',
                'scenario' => 'ApprovedAttachmentDetails',
                'report_type' => [Yii::t('app', 'tbl_member_provisional'), Yii::t('app', 'tbl_dcs_provisional'), Yii::t('app', 'tbl_customer_master_provisional')],
                'title' => 'Approved Attachment Details',
            //                'append_link' => TRUE
            ],
            'BmcCollectionRouteWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_bmc_collection_route_wise',
                'scenario' => 'BmcCollectionRouteWise',
                'title' => 'Bmc Wise Milk Collection',
            ],
            'PlantWiseMilkCollectionTracking' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string',
                'sp_name' => 'mis_plant_wise_milk_collection_tracking',
                'scenario' => 'PlantWiseMilkCollectionTracking',
                'title' => 'Plant Wise Milk Collection',
            ],
            'VlcQtySlabWiseCategory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_vlc_qty_slab_wise_category',
                'scenario' => 'VlcQtySlabWiseCategory',
                'title' => 'Qty Slab Report Format 1',
                'report_type' => [Yii::t('app', 'Member Collection'), Yii::t('app', 'Bmc Collection')],
            ],
            'AvgPerVlcMilkQtySlabWiseCategory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'mis_avg_per_vlc_milk_qty_slab_wise_category',
                'scenario' => 'AvgPerVlcMilkQtySlabWiseCategory',
                'title' => 'Qty Slab Report Format 2',
                'report_type' => [Yii::t('app', 'Member Collection'), Yii::t('app', 'Bmc Collection')],
            ],
            'IndentMemberDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'proc_member_indent_report',
                'scenario' => 'IndentMemberDetail',
                'title' => 'Indent Member Detail',
            ],
            'CompanyWiseMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'sp_mis_milk_collection_company_wise_report',
                'scenario' => 'CompanyWiseMilkCollection',
                'title' => 'Company Wise Collection',
                'report_type' => [Yii::t('app', 'Union wise Report'), Yii::t('app', 'Plant wise Report'), Yii::t('app', 'DCS wise Report')],
            ],
            'PlantWiseMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'sp_mis_milk_collection_plant_wise_report',
                'scenario' => 'CompanyWiseMilkCollection',
                'title' => 'Company Wise Collection',
                'report_type' => [Yii::t('app', 'Union wise Report'), Yii::t('app', 'Plant wise Report'), Yii::t('app', 'DCS wise Report')],
            ],
            'DcsWiseMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,report_type',
                'sp_name' => 'sp_mis_milk_collection_dcs_wise_report',
                'scenario' => 'CompanyWiseMilkCollection',
                'title' => 'Company Wise Collection',
                'report_type' => [Yii::t('app', 'Union wise Report'), Yii::t('app', 'Plant wise Report'), Yii::t('app', 'DCS wise Report')],
            ],
            'RateRecalculationWefDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,rate_cal_for,customer_type,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_Portal_Process_Recalculation_bkg_wefdate',
                'scenario' => 'RateRecalculationWefDateWise',
                'title' => 'Rate Recalculation(Custom)',
                'bkg_export' => TRUE,
            ],
            'InsuranceDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,insurance_master_code',
                'sp_name' => 'mis_insurance_detail',
                'to_decrypt' => ['adhar_no', 'dob', 'nominee_adhar_no'],
                'mask_data' => ['adhar_no', 'nominee_adhar_no'],
                'scenario' => 'InsuranceDetail',
                'title' => 'Insurance Detail',
            ],
            'InsuranceDetailReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,insurance_master_code,p_organization_type:static:originating_org_type,operation_type',
                'sp_name' => 'mis_insurance_detail_reconciliation',
                'to_decrypt' => ['current_adhar_no', 'current_dob', 'previous_adhar_no', 'previous_dob', 'adhar_no', 'dob', 'nominee_adhar_no'],
                'mask_data' => ['current_adhar_no', 'previous_adhar_no', 'adhar_no', 'nominee_adhar_no'],
                'scenario' => 'InsuranceDetailReconciliation',
                'title' => 'Insurance Detail Change Log',
            ],
            'InsuranceSummaryDcsWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,insurance_master_code',
                'sp_name' => 'mis_insurance_summary_dcs_wise',
                'to_decrypt' => ['current_adhar_no', 'current_dob', 'previous_adhar_no', 'previous_dob'],
                'mask_data' => ['current_adhar_no', 'previous_adhar_no'],
                'scenario' => 'InsuranceSummaryDcsWise',
                'title' => 'Insurance Summary DCS Wise',
            ],
            'TpCostDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,transporter_code:union_code',
                'sp_name' => 'mis_tpt_cost',
                'scenario' => 'TpCostDetail',
                'title' => 'Tp Cost Detail',
                'bkg_export' => TRUE,
            ],
            'TpCostSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,transporter_code:union_code',
                'sp_name' => 'mis_tpt_cost_summary',
                'scenario' => 'TpCostSummary',
                'title' => 'Tp Cost Summary',
                'bkg_export' => TRUE,
            ],
            'AreBmcCollectionShiftReport' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,from_date:string:from_shift,to_date:string:to_shift,route_type_trans:static:route_type_trans',
                'sp_name' => 'sp_mis_are_bmc_collection_shift_report',
                'scenario' => 'AreBmcCollectionShiftReport',
                'title' => '201 - BMC Collection Shift Report',
                'bkg_export' => TRUE,
            ],
            'AreBmcCollDateShiftWiseSummary' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_bmc_wise_society_collection_date_shift_wise',
                'scenario' => 'AreBmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Date And Shift Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                'bkg_export' => TRUE,
            ],
            'AreBmcCollDateWiseSummary' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_bmc_wise_soceity_collection_date_wise',
                'scenario' => 'AreBmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                'bkg_export' => TRUE,
            ],
            'AreBmcCollConsolidated' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_bmc_collection_consolidated',
                'scenario' => 'AreBmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                'bkg_export' => TRUE,
            ],
            'AreConsolidatedWithBank' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_bmc_collection_consolidated_with_bank',
                'scenario' => 'AreBmcCollDateShiftWiseSummary',
                'title' => '202 - BMC Collection Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
            ],
            'AreSocietyWiseCda' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_cda_date_shift',
                'scenario' => 'AreSocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE,
            ],
            'AreSocietyWiseCdaDateWise' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_cda_date',
                'scenario' => 'AreSocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE,
            ],
            'AreSocietyWiseCdaConsolidated' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_are_cda_consolidated',
                'scenario' => 'AreSocietyWiseCda',
                'title' => '207 - Society Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE,
            ],
            'AreVendorPayment' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_are_vendor_payment',
                'scenario' => 'AreVendorPayment',
                'title' => '602 - Vendor Payment',
                'bkg_export' => TRUE,
            ],
            'AreMemberPaymentDcsWise' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_are_member_billing_dcs_wise',
                'scenario' => 'AreMemberPaymentDcsWise',
                'title' => '603 - Member Payment',
                'report_type' => [Yii::t('app', 'DCS Wise'), Yii::t('app', 'Member Wise')],
                'bkg_export' => TRUE,
            ],
            'AreMemberPaymentMemberWise' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_are_member_billing_member_wise',
                'scenario' => 'AreMemberPaymentDcsWise',
                'title' => '603 - Member Payment',
                'report_type' => [Yii::t('app', 'DCS Wise'), Yii::t('app', 'Member Wise')],
                'bkg_export' => TRUE,
            ],
            'AreVendorBankPayment' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,customer_type,payment_cycle_code:type_check,bank_type:static:bank_type',
                'sp_name' => 'sp_mis_are_vendor_bank_payment',
                'scenario' => 'AreVendorBankPayment',
                'title' => '606 - Vendor Bank Payment',
                'bkg_export' => TRUE,
            ],
            'AreMemberBankPayment' => [
                'param' => 'union_code,state_code,region_code,area_code,bmc_code:area_code,dcs_code,payment_cycle_code:default:dcs,bank_type:static:bank_type',
                'sp_name' => 'sp_mis_are_member_bank_payment',
                'scenario' => 'AreMemberBankPayment',
                'title' => '607 - Member Bank Payment',
                'bkg_export' => TRUE,
            ],
            'VspTransitRecovery' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_vsp_transit_recovery',
                'scenario' => 'VspTransitRecovery',
                'title' => '921 - TS Recovery Report',
                'bkg_export' => TRUE,
            ],
            'ComplainActivityList' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_complain_activity_list',
                'scenario' => 'ComplainActivityList',
                'title' => 'Complain Activity Report',
                'bkg_export' => TRUE,
            ],
            'RouteWiseCdaFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'mis_route_wise_cda_date_shift',
                'scenario' => 'SocietyWiseCda',
                'title' => '226 - Route Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            'RouteWiseCdaDateWiseFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'mis_route_wise_cda_date',
                'scenario' => 'SocietyWiseCda',
                'title' => '226 - Route Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            'RouteWiseCdaConsolidatedFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'mis_route_wise_cda_consolidated',
                'scenario' => 'SocietyWiseCda',
                'title' => '226 - Route Wise CDA',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code'],
                'bkg_export' => TRUE,
            ],
            'MilkDispatchList' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_milk_dispatch_list',
                'scenario' => 'MilkDispatchList',
                'title' => '227 - Milk Dispatch List',
                'bkg_export' => TRUE,
            ],
            'MilkRejectList' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_milk_reject_list',
                'scenario' => 'MilkRejectList',
                'title' => '228 - Milk Reject List',
                'bkg_export' => TRUE,
            ],
            'ChillerCostSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_chiller_cost_summary',
                'scenario' => 'ChillerCostSummary',
                'title' => '514 - Handling & Storage Charges(chiller) Summary',
                'to_decrypt' => ['pan_no'],
                'bkg_export' => TRUE,
            ],
            'MonthlySahayakIncome' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_monthly_sahayak_income',
                'scenario' => 'MonthlySahayakIncome',
                'title' => 'CC Incharge Remuneration',
                'bkg_export' => TRUE,
            ],
            'MisCcWiseClosingBalance' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'proc_mis_cc_wise_closing_balance',
                'scenario' => 'MisCcWiseClosingBalance',
                'title' => 'CC Wise Closing Balance',
                'bkg_export' => TRUE,
            ],
            'ProcMisLotWiseDetails' => [
                'param' => 'vehicle_code,trip_code:vehicle_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'proc_mis_lot_wise_details',
                'scenario' => 'ProcMisLotWiseDetails',
                'title' => 'Vehicle wise Quality Report',
                'bkg_export' => TRUE,
            ],
            'ComparisonReport' => [
                'param' => 'vehicle_code,trip_code:vehicle_code,trip_status,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'proc_mis_quantity_and_quality_comparing',
                'scenario' => 'ComparisonReport',
                'title' => 'Comparison Report ',
                'bkg_export' => TRUE,
            ],
            'LocalMilkSale' => [
                'param' => 'milk_sale_on:static:milk_sale_on,union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_mis_local_milk_sale_all_report',
                'scenario' => 'LocalMilkSale',
                'title' => 'Local Milk Sale',
            ],
            'MemberBilling' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string,to_date:string,billing_on:static:billing_on',
                'sp_name' => 'rpt_mis_member_billing_all_report',
                'scenario' => 'MemberBilling',
                'title' => 'Member Billing',
            ],
            'MemberBillingDcsWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,to_date:string,billing_on:static:billing_on',
                'sp_name' => 'rpt_mis_member_billing_society_all_report',
                'scenario' => 'MemberBillingDcsWise',
                'title' => 'Member Billing DCS Wise',
            ],
            'ProductDispatchCenterWiseDetail' => [
                'param' => 'dispatch_center_type,dispatch_center,from_date:string,to_date:string,dispatch_type',
                'sp_name' => 'mis_product_dispatch_zser',
                'scenario' => 'ProductDispatchCenterWiseDetail',
                'title' => 'Product Dispatch Center Wise Detail',
            ],
            'CmpReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_rpt_bmc_compare_date_wise',
                'scenario' => 'CmpReport',
                'title' => 'CMP Report ',
                'bkg_export' => TRUE,
            ],
            'MccMilkBillDetailsWithIncentiveRouteWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_mcc_milk_bill_details_with_incentive_route_wise',
                'scenario' => 'MccMilkBillDetailsWithIncentiveRouteWise',
                'title' => 'Mcc Milk Bill Details With Incentive Route Wise',
                'bkg_export' => TRUE,
            ],
            'MccMilkBillDetailsMccDayWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_mcc_milk_bill_details_mcc_day_wise',
                'scenario' => 'MccMilkBillDetailsMccDayWise',
                'title' => 'MCC Day wise Summary',
                'bkg_export' => TRUE,
            ],
            'MisMilkPurchase' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,customer_type,customer_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_milk_purchase',
                'scenario' => 'MisMilkPurchase',
                'title' => 'Milk Purchase',
                'bkg_export' => TRUE,
            ],
            'UserAttendanceDetails' => [
                'param' => 'union_code,login_type:static:login_type,department,from_date:string,to_date:string',
                'sp_name' => 'get_user_attendance_details',
                'scenario' => 'UserAttendanceDetails',
                'title' => 'User Attendance Details',
                'bkg_export' => TRUE,
            ],
            'AssetDetailSummary' => [
                'param' => 'store_location_type_all,store_location_code,is_groupbyserial:static:boolean_value',
                'sp_name' => 'get_asset_location_data',
                'scenario' => 'AssetDetailSummary',
                'title' => '922 - Asset Detail Summary',
            ],
            'FarmerPaymentWiseMilkWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_payment_wise_milk_wise',
                'scenario' => 'FarmerPaymentWiseMilkWise',
                'title' => '119 - Farmer Wise Milk Bill 2',
                'to_decrypt' => ['adhar_no'],
                'report_type' => [Yii::t('app', 'Register'), Yii::t('app', 'Summary')],
                'bkg_export' => TRUE
            ],
            'FarmerPaymentWiseMilkWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_payment_wise_milk_wise_summary',
                'scenario' => 'FarmerPaymentWiseMilkWise',
                'title' => '119 - Farmer Wise Milk Bill 2',
                'to_decrypt' => ['adhar_no'],
                'report_type' => [Yii::t('app', 'Register'), Yii::t('app', 'Summary')],
                'bkg_export' => TRUE
            ],
            'PaymentCycleReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_get_payment_cycle_report',
                'scenario' => 'PaymentCycleReport',
                'title' => '515 - Payment Cycle Report',
                'bkg_export' => TRUE
            ],
            'YearlyFarmerCollectionReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'get_yearly_farmer_collection_report',
                'scenario' => 'YearlyFarmerCollectionReport',
                'title' => 'Supply Status Reports',
                'report_type' => [Yii::t('app', 'Farmer'), Yii::t('app', 'VSP')],
                'bkg_export' => TRUE
            ],
            'YearlyVspCollectionReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'get_yearly_vsp_collection_report',
                'scenario' => 'YearlyFarmerCollectionReport',
                'title' => 'Supply Status Reports',
                'report_type' => [Yii::t('app', 'Farmer'), Yii::t('app', 'VSP')],
                'bkg_export' => TRUE
            ],
            'AadeshLatter' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_group_code,from_date:string,to_date:string,header_reference:txt,assignment:txt',
                'sp_name' => 'sp_mis_aadesh_latter',
                'scenario' => 'AadeshLatter',
                'title' => 'Aadesh Patra',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
                'bkg_export' => TRUE
            ],
            'AadeshLatterSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_group_code,from_date:string,to_date:string,header_reference:txt,assignment:txt',
                'sp_name' => 'sp_mis_aadesh_latter_summary',
                'scenario' => 'AadeshLatter',
                'title' => 'Aadesh Patra',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
                'bkg_export' => TRUE
            ],
            'ProductSaleLogHistory' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'sp_mis_product_sale_log_history',
                'scenario' => 'ProductSaleLogHistory',
                'title' => 'Product Sale Log',
                'bkg_export' => TRUE
            ],
            'FarmerMilkBillConsolidatedSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_payment_wise_milk_wise',
                'multiple_sheet' => ['summary' => 'sp_mis_farmer_payment_wise_milk_wise_summary'],
                'scenario' => 'FarmerMilkBillConsolidatedSummary',
                'title' => '119 - Farmer Wise Milk Bill Register With Summary',
                'to_decrypt' => ['adhar_no', 'bank_account_no', 'ifsc', 'mobile_no'],
            ],
            'MisFeedReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_feed_report',
                'scenario' => 'MisFeedReport',
                'title' => 'Member product sale Taken/Not Taken',
                'bkg_export' => TRUE
            ],
            'VspOutstandingDetail' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code',
                'sp_name' => 'sp_mis_vsp_outstanding_detail',
                'scenario' => 'VspOutstandingDetail',
                'title' => '923 - Vsp Outstanding',
            ],
            'MemberPaymentShortageRecovery' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_payment_shortage_recovery_pending_import',
                'scenario' => 'MemberPaymentShortageRecovery',
                'title' => 'Member Payment Shortage Recovery',
                'report_type' => [Yii::t('app', 'Pending')],
            ],
            'VehicleStatusReport' => [
                'param' => 'vehicle_code',
                'sp_name' => 'sp_portal_dashboard_vehicle_wise_tanker_activity_report',
                'title' => 'Vehicle Status Report',
            ],
            'AutoManualQtyDateShiftWiseSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_collection_type',
                'sp_name' => 'sp_mis_bmc_wise_society_wise_auto_manual_qty_date_shift_wise',
                'scenario' => 'AutoManualQtyDateShiftWiseSummary',
                'title' => 'Auto Manual Qty Date And Shift Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'AutoManualQtyDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_collection_type',
                'sp_name' => 'sp_mis_bmc_wise_society_wise_auto_manual_qty_date_wise',
                'scenario' => 'AutoManualQtyDateShiftWiseSummary',
                'title' => 'Auto Manual Qty Date Wise Summary',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'AutoManualQtyConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_collection_type',
                'sp_name' => 'sp_mis_bmc_wise_society_wise_auto_manual_qty_consolidate',
                'scenario' => 'AutoManualQtyDateShiftWiseSummary',
                'title' => 'Auto Manual Qty Consolidated',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'bkg_export' => TRUE
            ],
            'SampleTimeMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sample_time_milk_collection',
                'scenario' => 'SampleTimeMilkCollection',
                'title' => 'Sample Time Milk Collection',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'Bmc Collection'), Yii::t('app', 'Comparision')],
                'bkg_export' => TRUE
            ],
            'SampleTimeBmcCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sample_time_bmc_collection',
                'scenario' => 'SampleTimeMilkCollection',
                'title' => 'Sample Time BMC Collection',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'Bmc Collection'), Yii::t('app', 'Comparision')],
                'bkg_export' => TRUE
            ],
            'SampleTimeComparision' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_sample_time_comparision',
                'scenario' => 'SampleTimeMilkCollection',
                'title' => 'Sample Time Comparision',
                'report_type' => [Yii::t('app', 'Milk Collection'), Yii::t('app', 'Bmc Collection'), Yii::t('app', 'Comparision')],
                'bkg_export' => TRUE
            ],
            'TpCostSummaryNewFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string,transporter_code:union_code',
                'sp_name' => 'mis_tpt_cost_summary_new_format',
                'scenario' => 'TpCostSummaryNewFormat',
                'title' => 'Tp Cost Summary 2',
                'bkg_export' => TRUE,
            ],
            'InwardBillSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,transporter_code:union_code,from_date:string,to_date:string',
                'sp_name' => 'mis_inward_bill_summary',
                'scenario' => 'InwardBillSummary',
                'title' => 'Inward Bill Summary',
                'to_decrypt' => ['PAN_No'],
            ],
            'PaymentAdviceInwardSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
                'sp_name' => 'mis_payment_advice_inward_summary',
                'scenario' => 'PaymentAdviceInwardSummary',
                'title' => 'Payment Advice - Inward',
                'bkg_export' => TRUE,
            ],
            'MemberDailyCollectionSecond' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_day_wise_report',
                'scenario' => 'MemberDailyCollectionSecond',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                //                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberPassbookSecond' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_passbook',
                'scenario' => 'MemberDailyCollectionSecond',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                //                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberConsolidatedSecond' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary',
                'scenario' => 'MemberDailyCollectionSecond',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                //                'download_day_differe' => '15'
                'bkg_export' => TRUE
            ],
            'MemberConsolidatedWithBankSecond' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary_with_bank',
                'scenario' => 'MemberDailyCollectionSecond',
                'title' => '101 - Member Collection Detail',
                'to_decrypt' => ['aadhar_no'],
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated'), Yii::t('app', 'Consolidated With Bank')],
                'bkg_export' => TRUE
            ],
            'VlccCommission' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_commission_detail_report',
                'scenario' => 'VlccCommission',
                'title' => '517 - VlCC Commission',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
                'bkg_export' => TRUE
            ],
            'CommissionReportSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_commission_report_summary',
                'scenario' => 'VlccCommission',
                'title' => '517 - VlCC Commission',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
                'bkg_export' => TRUE
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
            'UnifiedWeighmentReport' => [
                'param' => 'union_code,plant_code,from_date:string,to_date:string,p_product_type:static:p_product_type',
                'sp_name' => 'sp_mis_Unified_Weighment_Report',
                'scenario' => 'UnifiedWeighmentReport',
                'title' => 'Weighment Report',
                'bkg_export' => TRUE
            ],
            'DpuRateComparision' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'MIS_SP_DPU_rate_Comparision',
                'scenario' => 'DpuRateComparision',
                'title' => 'DPU Rate Comparision',
                'bkg_export' => TRUE
            ],
            'ComplaintSummaryDetailReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string,report_type',
                'sp_name' => 'sp_complaint_summary_detail_report_email_trigger',
                'scenario' => 'ComplaintSummaryDetailReport',
                'title' => 'Complaint Status Report',
                'report_type' => [Yii::t('app', 'Detail'), Yii::t('app', 'Summary')],
            ],
            'BankVerificationReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,date:string,master_type:static:master_type',
                'to_decrypt' => ['aadhaar_no'],
                'sp_name' => 'portal_master_data_verification',
                'scenario' => 'BankVerificationReport',
                'title' => 'Bank Verification Report',
            // 'excel_readonly' => TRUE,
            // 'editable_columns' => ['is_verified'],
            // 'extension' => 'xlsx',
            ],
            'MilkPurchaseRegisterReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,date:string,shift_code,milk_type_code,member_types:static:member_types,from_code:txt,to_code:txt,sort_type:static:sort_type',
                'sp_name' => 'mis_milk_purchase_register',
                'multiple_sheet' => ['summary' => 'mis_milk_purchase_register_summary'],
                'scenario' => 'MilkPurchaseRegisterReport',
                'title' => 'M - 101 - Milk Purchase Register Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FarmerLedgerReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,from_date:string:from_shift,to_date:string:to_shift,milk_type_code,member_types:static:member_types,from_code:txt,to_code:txt',
                'sp_name' => 'mis_farmer_ledger',
                'scenario' => 'FarmerLedgerReport',
                'title' => 'M - 103 - Farmer Ledger Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MemberWiseSummaryReport' => [
                'param' => 'language_code,union_code,from_date:string:from_shift,to_date:string:to_shift,from_code:txt,to_code:txt,milk_type_code,dcs_code:union_code:addAll',
                'sp_name' => 'mis_member_wise_summary_register',
                'multiple_sheet' => ['summary' => 'mis_member_wise_summary'],
                'scenario' => 'MemberWiseSummaryReport',
                'title' => 'M - 102 - Member Wise Summary Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'LocalSaleReport' => [
                'param' => 'language_code,union_code,from_code:txt,to_code:txt,from_date:string:from_shift,to_date:string:to_shift,milk_type_code,is_show_zero_val',
                'sp_name' => 'mis_local_sale',
                'scenario' => 'LocalSaleReport',
                'title' => 'M - 105 - Local sale Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'LocalSaleDetailReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,from_date:string:from_shift,to_date:string:to_shift,milk_type_code,payment_method:static:payment_method',
                'sp_name' => 'mis_local_sales_detail_report',
                'scenario' => 'LocalSaleDetailReport',
                'title' => 'M - 106 - Local Sale Detail Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkRateDetailReport' => [
                'param' => 'language_code,union_code,from_date:string,to_date:string,from_code:txt,to_code:txt,report_rate_type:static:report_rate_type,milk_type_code,last_rate',
                'sp_name' => 'mis_milk_rate_detail_report',
                'scenario' => 'MilkRateDetailReport',
                'title' => 'M - 110 - Milk Rate Detail Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkEditReport' => [
                'param' => 'language_code,union_code,search_by:static:search_by,region_code:union_code,dcs_code:union_code,from_date:string:from_shift,to_date:string:to_shift,edit_type:static:edit_type,from_code:txt,to_code:txt,sort_by:static:sort_by,amount_variation:static:amount_variation,is_group_by_society',
                'sp_name' => 'mis_milk_edit',
                'scenario' => 'MilkEditReport',
                'title' => 'M - 104 - Milk Edit Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'DateWiseMilkPurchaseSummary' => [
                'param' => 'language_code,union_code,region_code:union_code:all,search_type:static:search_type,from_code:txt,to_code:txt,from_date:string:from_shift,to_date:string:to_shift,milk_type_code',
                'sp_name' => 'mis_date_wise_milk_purchase_summary_register',
                'multiple_sheet' => ['summary' => 'mis_date_wise_milk_purchase_summary'],
                'scenario' => 'DateWiseMilkPurchaseSummary',
                'title' => 'M - 107 - Date wise Milk Purchase Summary Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkPurchaseAnalysis' => [
                'param' => 'language_code,union_code,dcs_code:union_code,date:string,shift_code,milk_type_code,member_types:static:member_types,from_code:txt,to_code:txt,report_sort_by:static:report_sort_by,sort_direction:static:sort_direction',
                'sp_name' => 'mis_milk_purchase_analysis',
                'scenario' => 'MilkPurchaseAnalysis',
                'title' => 'M - 111 - Milk Purchase Analysis Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FarmerListReport' => [
                'param' => 'language_code,union_code,from_soc:txt,to_soc:txt,report_member_type:static:report_member_type,member_types:static:member_types,from_code:txt,to_code:txt,farmer_type:static:farmer_type',
                'sp_name' => 'mis_farmer_list',
                'scenario' => 'FarmerListReport',
                'title' => 'S - 102 - Farmer List Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkEditSummary' => [
                'param' => 'language_code,union_code,search_by:static:search_by,region_code:union_code,dcs_code:union_code,from_date:string:from_shift,to_date:string:to_shift,edit_type:static:edit_type',
                'sp_name' => 'mis_milk_edit_summary',
                'scenario' => 'MilkEditSummary',
                'title' => 'M - 112 - Milk Edit Summary Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FatWiseQtyAnalysis' => [
                'param' => 'language_code,union_code,region_code:union_code:all,from_code:txt,to_code:txt,from_date:string:from_shift,to_date:string:to_shift,milk_type_code,filter_type:static:filter_type',
                'sp_name' => 'mis_Fat_wise_qty_analysis',
                'scenario' => 'FatWiseQtyAnalysis',
                'title' => 'M - 113 - Fat Wise Qty Analysis Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkCompare' => [
                'param' => 'language_code,union_code,dcs_code:union_code,from_code:txt,to_code:txt,from_date:string:from_shift,to_date:string:to_shift,milk_type_code,milk_sort_by:static:milk_sort_by,sort_direction:static:sort_direction',
                'sp_name' => 'mis_society_and_member_wise_milk_compair_report',
                'scenario' => 'MilkCompare',
                'title' => 'M - 117 - Milk Compare Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'SocietyList' => [
                'param' => 'language_code,union_code,search_by:static:search_by,region_type:static:region_type,region_code:union_code,dcs_code:union_code,status_type:static:status_type,society_type:static:society_type',
                'sp_name' => 'mis_Society_list_report',
                'scenario' => 'SocietyList',
                'title' => 'S - 101 - Society List Report',
                'header_included' => TRUE,
                'to_decrypt' => ['VDCS_Pan_No'],
                'bkg_export' => TRUE
            ],
            'FarmerAppDetailsReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,registered_type:static:registered_type,farmer_sort_type:static:farmer_sort_type',
                'sp_name' => 'mis_farmer_app_details',
                'multiple_sheet' => ['summary' => 'mis_farmer_app_details_summary'],
                'scenario' => 'FarmerAppDetailsReport',
                'title' => 'S - 103 - Farmer App Details Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'UnionWiseMessageDetailReport' => [
                'param' => 'language_code,union_code,region_code:union_code:all,from_code:txt,to_code:txt,from_date:string,to_date:string,group_by_region',
                'sp_name' => 'mis_union_wise_message_detail',
                'scenario' => 'UnionWiseMessageDetailReport',
                'title' => 'S - 105 - Union Wise Message Detail Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'UnionWiseMessageReport' => [
                'param' => 'language_code,union_code,from_date:string,to_date:string',
                'sp_name' => 'mis_union_wise_message',
                'scenario' => 'UnionWiseMessageReport',
                'title' => 'O - 101 - Union Wise Message Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'OnlineOfflineSocietyReport' => [
                'param' => 'language_code,union_code,region_type:static:region_type,region_code:union_code,soc_type:static:soc_type,show_only_received_data',
                'sp_name' => 'mis_online_offline_society',
                'scenario' => 'OnlineOfflineSocietyReport',
                'title' => 'O - 102 - Online Offline Society Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkRatePublishReport' => [
                'param' => 'language_code,union_code,from_soc:txt,to_soc:txt,date:string:shift_code,report_rate_type:static:report_rate_type,report_status_type:static:report_status_type',
                'sp_name' => 'mis_milk_rate_publish',
                'scenario' => 'MilkRatePublishReport',
                'title' => 'S - 106 - Milk Rate Publish Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'SmsDetailReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,date:string,sms_type:static:sms_type,mobile_no:txt,shift_code,member_types:static:member_types,from_code:txt,to_code:txt',
                'sp_name' => 'mis_sms_detail',
                'scenario' => 'SmsDetailReport',
                'title' => 'O - 103 - Sms Detail Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'TopSocietyMilkCollectionReport' => [
                'param' => 'language_code,union_code,from_date:string,to_date:string,top:txt',
                'sp_name' => 'mis_top_society_milk_collection',
                'scenario' => 'TopSocietyMilkCollectionReport',
                'title' => 'SP - 101 - Top Society Milk Collection Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'TopFarmerMilkCollectionReport' => [
                'param' => 'language_code,union_code,search_by_soc:static:search_by_soc,dcs_code:union_code,from_date:string,to_date:string,top:txt,report_gender:static:report_gender',
                'sp_name' => 'mis_top_farmer_milk_collection',
                'scenario' => 'TopFarmerMilkCollectionReport',
                'title' => 'SP - 102 - Top Farmer Collection Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FarmerManualEntryReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,from_date:string,to_date:string,manual_type:static:manual_type,show_val',
                'sp_name' => 'mis_farmer_manual_entry',
                'scenario' => 'FarmerManualEntryReport',
                'title' => 'SP - 109 - Farmer Manual Entry Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'SocietyWiseSummaryReport' => [
                'param' => 'language_code,union_code,from_code:txt,to_code:txt,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_society_wise_summary',
                'scenario' => 'SocietyWiseSummaryReport',
                'title' => 'SP - 104 - Society Wise Summary Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FarmerNotSubmittingMilkReport' => [
                'param' => 'language_code,union_code,from_code:txt,to_code:txt,from_date:string,to_date:string,show_only_received_data',
                'sp_name' => 'mis_farmer_not_submitting_milk',
                'scenario' => 'FarmerNotSubmittingMilkReport',
                'title' => 'SP - 105 - Farmer Not Submitting Milk Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'ManualCollectionSummaryReport' => [
                'param' => 'language_code,union_code,from_soc:txt,to_soc:txt,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'mis_manual_collection_summary',
                'scenario' => 'ManualCollectionSummaryReport',
                'title' => 'SP - 111 - Manual Collection Summary Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MilkEditForFarmerReport' => [
                'param' => 'language_code,union_code,region_code:union_code:all,from_soc:txt,to_soc:txt,from_date:string,to_date:string,no_of_farmer_edit:txt,no_of_individual_farmer_edit:txt,edit_type:static:edit_type',
                'sp_name' => 'mis_milk_edit_for_farmer_register',
                'multiple_sheet' => ['summary' => 'mis_milk_edit_for_farmer_summary'],
                'scenario' => 'MilkEditForFarmerReport',
                'title' => 'SP - 112 - Milk Edit For Farmer Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'MuAppVdcsAppUserReport' => [
                'param' => 'language_code,union_code,report_app_type:static:report_app_type,dcs_code:union_code,registered_type:static:registered_type,status_type:static:status_type',
                'sp_name' => 'mis_vdcs_app_user_register',
                'multiple_sheet' => ['summary' => 'mis_vdcs_app_user_summary'],
                'scenario' => 'MuAppVdcsAppUserReport',
                'title' => 'App - 101 - MU App VDCS APP User Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'SocietySampleReport' => [
                'param' => 'language_code,union_code,region_code:union_code:all,dcs_code:union_code,from_date:string:from_shift,to_date:string:to_shift,is_group_by_society,is_show_zero_val,from_time,to_time,last_rate',
                'sp_name' => 'mis_Society_sample_report',
                'scenario' => 'SocietySampleReport',
                'title' => 'SP - 106 - Society Sample Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'TopRegionsMilkCollectionReport' => [
                'param' => 'language_code,union_code,region_code:union_code:all,from_date:string,to_date:string,top:txt',
                'sp_name' => 'mis_top_region_milk_collection_report',
                'scenario' => 'TopRegionsMilkCollectionReport',
                'title' => 'SP - 103 - Top Regions Milk Collection Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'DailySummaryReport' => [
                'param' => 'language_code,union_code,date:string,storage_type',
                'sp_name' => 'mis_daily_summary',
                'scenario' => 'DailySummaryReport',
                'title' => 'SP - 108 - Daily Summary Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'TrucksheetComparisionReport' => [
                'param' => 'language_code,union_code,from_code:txt,to_code:txt,from_date:string,to_date:string',
                'sp_name' => 'mis_trucksheet_comparision_detail',
                'scenario' => 'TrucksheetComparisionReport',
                'title' => 'SP - 114 - Trucksheet Comparision Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'TrucksheetDetailReport' => [
                'param' => 'language_code,union_code,plant_code,from_code:txt,to_code:txt,from_date:string:from_shift,to_date:string:to_shift,generation_type:static:generation_type',
                'sp_name' => 'mis_trucksheet_detail',
                'scenario' => 'TrucksheetDetailReport',
                'title' => 'SP - 115 - Trucksheet Detail Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FarmerFatAndWtDeviationReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,date:string,shift_code,milk_type_code,deviation_days:txt,deviation_value:txt,deviation_type,member_code',
                'sp_name' => '',
                'scenario' => 'FarmerFatAndWtDeviationReport',
                'title' => 'SP - 110 - Farmer Fat And Wt Deviation Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'FarmerWiseYearlyEditReport' => [
                'param' => 'language_code,union_code,dcs_code:union_code,financial_year,from_code:txt,to_code:txt',
                'sp_name' => 'mis_farmer_wise_yearly_edit_report',
                'scenario' => 'FarmerWiseYearlyEditReport',
                'title' => 'S - 107 - Farmer Wise Yearly Edit Report',
                'header_included' => TRUE,
                'bkg_export' => TRUE
            ],
            'RdoSalaryStructure' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_Rdo_Salary_abt',
                'scenario' => 'RdoSalaryStructure',
                'title' => 'Rdo Salary Structure',
            ]
        ];
        return $label[$l];
    }

    public function downloadData($controls, $model) {
//        $extention = 'xls';
//        $header = [
//            'mime' => 'application/ms-excel',
//            'extension' => $extention,
//            'writer' => 'Excel2007',
//        ];
//
//        $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
//        $labelT = !empty($this->label) ? $this->label : $this->data['title'] . '-' . date('Ymdhis');
//        $fileName = $labelT . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
////        header('Content-Type: text/plain');
//        header('Content-Disposition: attachment;filename=' . $fileName);
//        header('Cache-Control: max-age=0');
////        header("Content-Type: application/xls");
////        header("Content-Disposition: attachment; filename={$fileName}");
////        header("Pragma: no-cache");
////        header("Expires: 0");
//        $Output = $this->output;
//        $schema_insert = '';
//        echo "<table border='1'>";
//        echo "<tr>";
//        foreach ($labelArray as $a) {
//            echo "<td>" . $a . "</td>";
//        }
//        echo "</tr>";
//        foreach ($Output as $row) {
//            echo "<tr>";
//            foreach ($labelArray as $a) {
//                $dispData = '';
//                if (isset($row[$a]) && $row[$a] != '' && $row[$a] != null) {
//                    $dispData = $row[$a];
//                }
//                $value = $dispData;
//                if (!empty($this->data['to_decrypt']) && in_array($a, $this->data['to_decrypt'])) {
//                    $value = !empty($dispData) ? (Yii::$app->general->decryptData($dispData) !== FALSE ? Yii::$app->general->decryptData($dispData) : $dispData) : (isset($dispData) && $dispData == 0 && $dispData != '' ? 0 : '');
//                }
//                if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
//                    echo "<td>" . $value . "</td>";
//                } else if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && (substr($value, 0, 1) == '.' || (substr($value, 0, 1) == '0' && substr($value, 1, 1) == '.'))) {
//                    if (substr($value, 0, 1) == '.') {
//                        echo "<td>0" . $value . "</td>";
//                    } else {
//                        echo "<td>" . substr($value, 0, 2) . "</td>";
//                    }
//                } else {
//                    echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
//                }
//            }
//            echo "</tr>";
//        }
//        echo "</table>";
//        exit();

        if (isset($this->data['downloadFormat']) && $this->data['downloadFormat'] == 'csv') {
            $header = [
                'mime' => 'text/csv',
                'extension' => 'csv',
                'writer' => 'CSV',
            ];
            $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
            $labelT = !empty($this->label) ? $this->label : $this->data['title'] . '-' . date('Ymdhis');
            $fileName = $labelT . '.' . $header['extension'];

            header('Content-Type: ' . $header['mime']);
            header('Content-Disposition: attachment;filename=' . $fileName);
            header('Cache-Control: max-age=0');

            $output = fopen('php://output', 'w');

            fwrite($output, implode(',', $labelArray) . "\n");

            foreach ($this->output as $row) {
                fwrite($output, implode(',', $row) . "\n");
            }
            fclose($output);
            exit();
        } else {
            $header = [
                'mime' => 'application/vnd.ms-excel',
                'extension' => 'xlsx',
                'writer' => IOFactory::WRITER_XLSX,
            ];
        }
        $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];
        foreach ($file_header as $key => $value) {
            $file_header[$key] = \Yii::t('app', $value);
        }
        $isZip = isset($this->data['append_link']) && $this->data['append_link'] == true;
        if ($isZip && !in_array('attachment_link', $file_header)) {
            $file_header[] = 'attachment_link';
        }
        $objPHPExcel = new Spreadsheet();
        $customWorksheet = new Worksheet($objPHPExcel, 'Sheet1');
        $objPHPExcel->addSheet($customWorksheet);
        $objPHPExcel->removeSheetByIndex(0);
        $header_rows = 1;
        if (isset($this->data['header_included']) && $this->data['header_included'] === true) {
            $colCount = count($file_header);
            $lastCol = ($colCount > 0) ? Coordinate::stringFromColumnIndex($colCount) : 'A';
            $header_rows = 4;
            $header_labels = Yii::$app->request->post('header_labels');
            $header_labels_arr = !empty($header_labels) ? json_decode($header_labels, true) : [];
            $companyName = !empty($header_labels_arr['union_code']) ? $header_labels_arr['union_code'] : (!empty(Yii::$app->session->get('OrganizationName')) ? Yii::$app->session->get('OrganizationName') : 'Everest Instruments Pvt. Ltd.');
            $reportTitle = isset($this->data['title']) ? $this->data['title'] : 'Report';
            $searchParams = $this->getSearchParams($header_labels_arr, $model);
            $customWorksheet->setCellValue('A1', $companyName);
            $customWorksheet->setCellValue('A2', $reportTitle);
            $customWorksheet->setCellValue('A3', $searchParams);
            $customWorksheet->mergeCells("A1:{$lastCol}1");
            $customWorksheet->mergeCells("A2:{$lastCol}2");
            $customWorksheet->mergeCells("A3:{$lastCol}3");
            $customWorksheet->getStyle("A1:{$lastCol}3")->getFont()->setBold(true);
            $customWorksheet->getStyle("A1:{$lastCol}2")->getFont()->setSize(14);
            $customWorksheet->getStyle("A1:{$lastCol}3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        $customWorksheet->fromArray($file_header, NULL, 'A' . $header_rows);
        $customWorksheet->fromArray($this->output, NULL, 'A' . ($header_rows + 1));
        if (isset($this->data['header_included']) && $this->data['header_included'] === true) {
            $customWorksheet->getStyle("A{$header_rows}:{$lastCol}{$header_rows}")->getFont()->setBold(true);
        }
        //         $objPHPExcel = new Spreadsheet();
//         $sheet = $objPHPExcel->getActiveSheet();
//         /* $objPHPExcel->getDefaultStyle()
//           ->getNumberFormat()
//           ->setFormatCode(
//           \PHPExcel_Style_NumberFormat::FORMAT_TEXT
//           ); */
//         $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];
//         /* $file_header = array_map(function($file_header) {
//           return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
//           }, array_values($file_header)); */
//         $sheet->fromArray(
//                 $file_header, // The data to set
//                 NULL, // Array values with this value will not be set
//                 'A1'         // Top left coordinate of the worksheet range where
// //    we want to set these values (default is A1)
//         );
        // $dataToText = !empty($this->data['to_text']) ? $this->data['to_text'] : [];
        // if (!empty($dataToText)) {
        //     foreach ($dataToText as $columnName) {
        //         $columnIndex = array_search($columnName, $file_header);
        //         if ($columnIndex !== false) {
        //             $accountNoColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
        //             $sheet->getStyle($accountNoColumn)
        //                     ->getNumberFormat()
        //                     ->setFormatCode('00000000000');
        //         }
        //     }
        // }
        // array_walk_recursive($this->output, function(&$value) {
        //     $value = is_numeric($value) && strlen($value) >= 10 && preg_match('/^([0-9]+)$/', $value) ? '="' . $value . '"' : $value;
        // });
        // $columnIndex = 1;
        // $rowIndex = 2;
        // array_walk_recursive($this->output, function (&$value, $key) use ($sheet, &$columnIndex, &$rowIndex) {
        //     $cell = $sheet->getCellByColumnAndRow($columnIndex, $rowIndex);
        //     if (is_numeric($value) && preg_match('/^([0-9]+)$/', $value)) {
        //         $sheet->setCellValueExplicit($cell->getCoordinate(), $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        //     } else {
        //         $sheet->setCellValue($cell->getCoordinate(), $value);
        //     }
        //     $columnIndex++;
        // });
//         $columnIndex = 1;
//         $columnKey = '';
//         array_walk_recursive($this->output, function (&$value, $key) use ($sheet, &$columnIndex, &$columnKey) {
//             if ($columnKey == $key || $columnKey == '') {
//                 $columnKey = $key;
//                 $columnIndex = 1;
//             }
//             if (is_numeric($value) && strlen($value) >= 10 && preg_match('/^([0-9]+)$/', $value)) {
//                 $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
//                 $sheet->getStyle($columnLetter)
//                         ->getNumberFormat()
//                         ->setFormatCode(
//                                 \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
//                 );
//             }
//             $columnIndex++;
//         });
//         $sheet->fromArray(
//                 $this->output, // The data to set
//                 NULL, // Array values with this value will not be set
//                 'A2'         // Top left coordinate of the worksheet range where
// //    we want to set these values (default is A1)
//         );
        if ($isZip && !empty($this->output)) {
            $rowIndex = 2;
            foreach ($this->output as $row) {
                $moduleCode = isset($row['module_code']) ? $row['module_code'] : '';
                $moduleName = isset($row['module_name']) ? $row['module_name'] : '';
                $zipUrl = yii\helpers\Url::to([
                            '/document/tbl-attachment/zip-attachment-download',
                            'user_code' => Yii::$app->user->id,
                            'module_code' => $moduleCode,
                            'module_name' => $moduleName,
                                ], true);
                $row['attachment_link'] = 'Download';
                if (!empty($row['attachment_link'])) {
                    $columnIndex = count($row) - 1;
                    $cell = $customWorksheet->getCellByColumnAndRow($columnIndex, $rowIndex);
                    $cellCoordinate = $cell->getCoordinate();
                    $customWorksheet->setCellValue($cellCoordinate, 'Download');
                    $customWorksheet->getCell($cellCoordinate)->getHyperlink()->setUrl($zipUrl);
                    $customWorksheet->getStyle($cellCoordinate)->getFont()->setUnderline(true)->getColor()->setRGB('0000FF');
                }
                $rowIndex++;
            }
        }
        if (isset($this->data['multiple_sheet'])) {
            foreach ($this->data['multiple_sheet'] as $new_sheet_name => $new_sp_name) {
                $newsheet = new Worksheet($objPHPExcel, $new_sheet_name);
                $objPHPExcel->addSheet($newsheet);
                $newoutput = \Yii::$app->general->getSpData($new_sp_name, $controls);
                $new_file_header = !empty($newoutput) ? array_keys($newoutput[0]) : [];
                foreach ($new_file_header as $key => $value) {
                    $new_file_header[$key] = \Yii::t('app', $value);
                }
                $new_header_rows = 1;
                if (isset($this->data['header_included']) && $this->data['header_included'] === true) {
                    $newColCount = count($new_file_header);
                    $newLastCol = ($newColCount > 0) ? Coordinate::stringFromColumnIndex($newColCount - 1) : 'A';
                    $new_header_rows = 4;
                    $newsheet->setCellValue('A1', $companyName);
                    $newsheet->setCellValue('A2', $reportTitle);
                    $newsheet->setCellValue('A3', $searchParams);
                    $newsheet->mergeCells("A1:{$lastCol}1");
                    $newsheet->mergeCells("A2:{$lastCol}2");
                    $newsheet->mergeCells("A3:{$lastCol}3");
                    $newsheet->getStyle("A1:{$lastCol}3")->getFont()->setBold(true);
                    $newsheet->getStyle("A1:{$lastCol}2")->getFont()->setSize(14);
                    $newsheet->getStyle("A1:{$lastCol}3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
                $newsheet->fromArray($new_file_header, NULL, 'A' . $new_header_rows);
                $newsheet->fromArray($newoutput, NULL, 'A' . ($new_header_rows + 1));

                if (isset($this->data['header_included']) && $this->data['header_included'] === true && isset($newLastCol)) {
                    $newsheet->getStyle("A{$new_header_rows}:{$newLastCol}{$new_header_rows}")->getFont()->setBold(true);
                }
            }
        }
        $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
        $labelT = !empty($this->label) ? $this->label : $this->data['title'] . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        //        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_start();
        $objWriter->save('php://output');
        exit();
    }

    public function downloadDataExcel($model) {
        $header = [
            'mime' => 'application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => IOFactory::WRITER_XLS,
        ];
        $labelT = !empty($this->label) ? $this->label : $this->data['title'] . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'];
        //        header('Content-Type: ' . $header['mime']);
//        $str = "http://stagging.emilkpro.in:8199/export.aspx?q=sp_sap_milk_collection_data ";
        $str = "http://10.10.20.196:8199/export.aspx?q=sp_sap_milk_collection_data ";
        $str .= "'" . $model->union_code . "', ";
        $str .= "'" . $model->plant_code . "', ";
        $str .= "'" . $model->mcc_code . "', ";
        $str .= "'" . $model->bmc_code . "', ";
        $str .= "'" . $model->dcs_code . "', ";
        $str .= "'" . $model->from_date . "', ";
        $str .= "'" . $model->to_date . "'";
        $str .= "&f=" . $fileName . "";

        $serverUrl = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl;
        $dirPath = Yii::$app->basePath;
        $savePath = '/web/sapFiles';
        Yii::$app->general->checkDirectory($dirPath . $savePath);
        $fp = fopen($dirPath . $savePath . '/' . $fileName, 'w+');
        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ", "%20", $str));
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        // write curl response to file
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // get curl response
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        header('Location: ' . $serverUrl . $savePath . '/' . $fileName);
        exit();
        //        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => $str,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'GET',
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
////        echo $str;
////        die;
//
//        ob_start();
////        header('Location: ' . $str);
////        header('Content-Disposition: attachment;filename=' . $fileName);
////        header('Cache-Control: max-age=0');
//        ob_end_flush();
//        var_dump($stream);
//        die;
//        $header = [
//            'mime' => 'application/vnd.ms-excel',
//            'extension' => 'xls',
//            'writer' => 'Excel2007',
//        ];
//        $objPHPExcel = new Spreadsheet();
//        $sheet = $objPHPExcel->getActiveSheet();
//        $objPHPExcel->getActiveSheet()->getStyle('C2:C100')
//                ->getNumberFormat()
//                ->setFormatCode('h:mm:ss');
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
//        $labelT = !empty($this->label) ? $this->label : $this->data['title'] . '-' . date('Ymdhis');
//        $fileName = $labelT . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
//        header('Content-Disposition: attachment;filename=' . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
        exit();
    }

    public function downloadDataLocal($title, $download, &$fileArray) {
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => IOFactory::WRITER_XLS,
        ];
        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
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
        // Adjust column widths
        $highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(strlen($objPHPExcel->getActiveSheet()->getCell($col . '1')->getValue()) + 2);
        }
        $file_name = $title . '.' . 'xls';
        $path = Yii::$app->basePath . '/web/sap_data_files/';
        Yii::$app->general->checkDirectory($path);
        $fileArray[] = $file_name;
        $fileName = $path . '/' . $file_name;
        fopen($fileName, "w+");
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');
        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
        $objWriter->save($fileName);
    }

    public function actionMisLiveReportGeneration() {
        
    }

    public static function downloadDataReadonly($output, $data, $label = '') {
        $ext = !empty($data['extension']) ? $data['extension'] : 'xls';
        $mime = ($ext == 'xlsx') ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/vnd.ms-excel';
        $header = [
            'mime' => $mime,
            'extension' => $ext,
            'writer' => ($ext == 'xlsx') ? IOFactory::WRITER_XLSX : IOFactory::WRITER_XLS,
        ];
        $objPHPExcel = new Spreadsheet();
        $sheet = new Worksheet($objPHPExcel, 'Sheet1');
        $file_header = !empty($output) ? array_keys($output[0]) : [];
        $objPHPExcel->addSheet($sheet);
        $objPHPExcel->removeSheetByIndex(0);
        $sheet->fromArray($file_header, NULL, 'A1');
        $sheet->fromArray($output, NULL, 'A2');

        $protection = $sheet->getProtection();
        $protection->setPassword('MyStrongPassword2026');
        $protection->setSheet(true);
        $protection->setSelectLockedCells(true);
        $protection->setSelectUnlockedCells(false);
        $sheet->getStyle("A1:{$sheet->getHighestColumn()}{$sheet->getHighestRow()}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        foreach ($file_header as $i => $key) {
            $colLetter = Coordinate::stringFromColumnIndex($i + 1);
            $columnData = array_column($output, $key);
            $columnData[] = $key;
            $maxLength = !empty($columnData) ? max(array_map('strlen', $columnData)) : 10;
            $sheet->getColumnDimension($colLetter)->setWidth($maxLength + 6);
            if (isset($data['editable_columns']) && in_array($key, $data['editable_columns'])) {
                $sheet->getStyle($colLetter . '2:' . $colLetter . $sheet->getHighestRow())->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            }
        }
        $labelT = !empty($label) ? $label : $data['title'] . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'];
        ob_start();
        header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function getSearchParams($header_labels_arr, $model) {
        $attributeLabels = $model->attributeLabels();
        $searchParams = "";
        if (!empty($header_labels_arr)) {
            foreach ($header_labels_arr as $key => $val) {
                if (isset($header_labels_arr[$key]) && $header_labels_arr[$key] !== '' && $key !== "output_type") {
                    $label = isset($attributeLabels[$key]) ? $attributeLabels[$key] : ucwords(str_replace(['_'], [' '], $key));
                    $displayValue = is_array($val) ? implode(', ', $val) : $val;
                    $searchParams .= trim($label) . ": " . $displayValue . "  ";
                }
            }
        }
        return trim($searchParams);
    }

}
