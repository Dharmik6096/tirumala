<?php

namespace app\modules\misreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\misreports\models\ReportsModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use PHPExcel;
use app\modules\configuration\models\TblGenerateReportParam;
use app\modules\bkgprocess\models\TblFtpTxnLog;

/**
 * Default controller for the `JasperReports` module
 */
class ReportsController extends \app\controllers\ChildController {

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

    public function actionSocietyWiseCdaFormat() {
        $this->report = 'SocietyWiseCdaFormat';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWiseFormat';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'BMCAutomationDayWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'BMCAutomationConsolidated';
            }
        }
        return $this->actionIndex();
    }

    public function actionMemberPaymentDrafted() {
        $this->report = 'MemberPaymentWithBank';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberPaymentWoBank';
            }
        }
        return $this->actionIndex();
    }

    public function actionRouteWiseCollection() {
        $this->report = 'RouteWiseFarmerCollection';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'RouteWiseBMCCollection';
            }
        }
        return $this->actionIndex();
    }

    public function actionRouteWiseCollectionSummary() {
        $this->report = 'RouteWiseCollectionSummaryFarmerDateShift';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'RouteWiseCollectionSummaryFarmerConsolidate';
            } else if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'RouteWiseCollectionSummaryBmcDateShift';
            } else if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '3') {
                $this->report = 'RouteWiseCollectionSummaryBmcConsolidate';
            }
        }
        return $this->actionIndex();
    }

    public function actionVendorWiseCollectionSummary() {
        $this->report = 'VendorWiseCollectionSummaryDateShift';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
        if ($model->load(Yii::$app->request->queryParams) && $model->validate()) {
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
        return $this->render('index', ['result' => $this->output, 'message' => $this->message, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionRateMasterRegister() {
        $this->report = 'RateMasterRegister';
        return $this->actionIndex();
    }

    public function actionSocietyWiseCdaTwo() {
        $this->report = 'SocietyWiseCdaTwo';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'SocietyWiseCdaDateWiseTwo';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MilkCollectionNotExistsSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionDayWiseQty() {
        $this->report = 'DayWiseQtyDetail';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberMilkBillDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberMilkBillSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionAgentWiseReconciliation() {
        $this->report = 'AgentWiseReconciliation';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'AgentWiseReconciliationDateWise';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
            $controls[$value] = is_array($model->{$value}) ? ',' . implode(',', $model->{$value}) . ',' : $model->{$value};
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
        if ($showOutPut) {
            $output = \Yii::$app->general->getSpData($sp_name, $controls);
        } else {
            $output[0]['message'] = 'Your Request has been submitted For Report Data. You can download file from Rport Download Screen.';
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
                    if (isset(Yii::$app->request->queryParams['upload_ftp_file']) && Yii::$app->request->queryParams['upload_ftp_file'] == '1') {
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
                $export_file_name = str_replace($k, $v, $export_file_name);
            }
            $this->label = $export_file_name;
        }
        if ($model->output_type == 'DOWNLOAD') {
            if ($this->report == 'SapMilkCollectionData') {
                $this->downloadDataExcel($model);
            } else {
                $this->downloadData();
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MccReceiptVsBmcDispatchDetail';
            }
        }
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'StockRegisterToProduct';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'StockRegisterToSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionStockRegisterMccToSap() {
        $this->report = 'StockRegisterMccToSap';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'StockRegisterMccToProduct';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'StockRegisterMccToSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionBmcWiseSocietyWiseAutoManual() {
        $this->report = 'BmcWiseSocietyWiseAutoManual';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'MemberDailyCollectionRegion';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'MemberConsolidatedRegion';
            }
        }
        return $this->actionIndex();
    }
    public function actionDcsCollDateShiftSummaryRegion() {
        $this->report = 'DcsCollDateShiftSummaryRegion';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'DcsCollDateWiseSummaryRegion';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'DcsCollectionConsolidateRegion';
            }
        }
        return $this->actionIndex();
    }
    public function actionAgentWiseReconciliationRegion() {
        $this->report = 'AgentWiseReconciliationRegion';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'AgentWiseReconciliationDateWiseRegion';
            }
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '2') {
                $this->report = 'AgentWiseReconciliationSummaryRegion';
            }
        }
        return $this->actionIndex();
    }
    public function actionCenterLossGainReportRegion() {
        $this->report = 'CenterLossGainReportRegion';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
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

    /* Reports Configuration */

    public function getLabels($l) {
        $label = [
//101
            'MemberDailyCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_day_wise_report',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
//                'download_day_differe' => '15'
            ],
            'MemberPassbook' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_passbook',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
//                'download_day_differe' => '15'
            ],
            'MemberConsolidated' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_collection_summary',
                'scenario' => 'MemberDailyCollection',
                'title' => '101 - Member Collection Detail',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
//                'download_day_differe' => '15'
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
                'multiArray' => ['mcc_code', 'bmc_code']
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
                'to_decrypt' => ['phone_no', 'Phone No', 'pan_no', 'Pan No', 'upi_no', 'Upi No', 'password', 'password'],
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
//                'output_type' => FALSE
            ],
            'MemberMaster' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,route_code:all_routes,dcs_code:route_code,member_code',
                'sp_name' => 'sp_mis_member_master_register',
                'scenario' => 'MemberMaster',
                'title' => 'Member Register',
                'to_decrypt' => ['pan_no', 'Pan No', 'dob', 'Dob', 'adhar_no'],
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
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'BMC')],
                'export_file_name' => 'Plant_Code_VMCC_from_date_from_shift',
            ],
            'CPRmrdReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_sap_rpt_cpmilk_rmrd_collection',
                'scenario' => 'CPReportSap',
                'title' => '404 - SAP Data Export',
                'report_type' => [Yii::t('app', 'MEMBER'), Yii::t('app', 'BMC')],
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
            ],
            'BmcCollectionRegister' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_bmc_collection_register',
                'scenario' => 'BmcCollectionRegister',
                'title' => 'BMC Collection Register',
                'removeExportType' => ['CSV'],
                'extention' => 'xlsx',
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
            ],
            'MemberPaymentWoBank' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_member_payment_drafted_wo_bank',
                'scenario' => 'MemberPaymentDrafted',
                'title' => '611 - Member Payment(Drafted)',
                'report_type' => [Yii::t('app', 'With Bank Detail'), Yii::t('app', 'W/O Bank Detail')],
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
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'SocietyWiseCdaDateWiseFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_date_format_2',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA Format 2',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'SocietyWiseCdaConsolidatedFormat' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift,report_status:static:report_status',
                'sp_name' => 'sp_mis_cda_consolidated_format_2',
                'scenario' => 'SocietyWiseCda',
                'title' => '207 - Society Wise CDA Format 2',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
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
                'title' => 'Plant Receipt Register',
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
            ],
            'StockAtDcs' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,product_code,p_date:string',
                'sp_name' => 'mis_stock_to_vlcc',
                'scenario' => 'StockAtDcs',
                'title' => 'Stock At DCS',
            ],
            'SaleReportFarmer' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_farmer_product_sale_report',
                'scenario' => 'SaleReportFarmer',
                'title' => 'Farmer Sale Report',
            ],
            'SaleReportVendor' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,customer_type,vendor_code,product_code,from_date:string,to_date:string',
                'sp_name' => 'mis_vendor_product_sale_report',
                'scenario' => 'SaleReportVendor',
                'title' => 'Vendor Sale Report',
            ],
            'SummaryReportMcc' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string,to_date:string',
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
                'title' => '113 - Farmer Wise Milk Bill',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberMilkBillDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_wise_milk_bill_date_wise',
                'scenario' => 'MemberMilkBill',
                'title' => '113 - Farmer Wise Milk Bill',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'MemberMilkBillSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,member_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_farmer_wise_milk_bill_summary',
                'scenario' => 'MemberMilkBill',
                'title' => '113 - Farmer Wise Milk Bill',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
            ],
            'AgentWiseReconciliation' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_details_date_shift_wise_reconcilation',
                'scenario' => 'AgentWiseReconciliation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'AgentWiseReconciliationDateWise' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_details_date_wise_reconcilation',
                'scenario' => 'AgentWiseReconciliation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
            ],
            'AgentWiseReconciliationSummary' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'sp_mis_agent_wise_reconcilation',
                'scenario' => 'AgentWiseReconciliation',
                'title' => '219 - Agent Wise Reconciliation',
                'report_type' => [Yii::t('app', 'Date & Shift Wise'), Yii::t('app', 'Date Wise'), Yii::t('app', 'Consolidated')],
                'multiArray' => ['mcc_code', 'bmc_code']
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
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string,to_date:string',
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
            ],
        ];
        return $label[$l];
    }

    public function downloadData() {
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


        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel2007',
        ];
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        /* $objPHPExcel->getDefaultStyle()
          ->getNumberFormat()
          ->setFormatCode(
          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
          ); */
        $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];
        /* $file_header = array_map(function($file_header) {
          return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
          }, array_values($file_header)); */

        $sheet->fromArray(
                $file_header, // The data to set
                NULL, // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $sheet->fromArray(
                $this->output, // The data to set
                NULL, // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
        $labelT = !empty($this->label) ? $this->label : $this->data['title'] . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
//        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function downloadDataExcel($model) {
        $header = [
            'mime' => 'application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel2007',
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
//        $objPHPExcel = new PHPExcel();
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
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
        exit();
    }

    public function downloadDataLocal($title, $download, &$fileArray) {
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel2007',
        ];
        $objPHPExcel = new PHPExcel();
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

}
