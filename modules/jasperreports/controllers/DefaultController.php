<?php

namespace app\modules\jasperreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\jasperreports\models\ReportsModel;
use app\modules\usermanagement\models\User;

/**
 * Default controller for the `JasperReports` module
 */
class DefaultController extends \app\controllers\ChildController {

    /**
     * Renders the index view for the module
     * @return string
     */
    private $data = [], $type = 'html', $output = '', $report = '';

    public function actionIndex() {
        $model = new ReportsModel();
        if ($this->report != '') {
            $this->data = $this->getLabels($this->report);
            $client_code = \Yii::$app->session->get('eiplCode');
            if (!empty($client_code) && isset($this->getLabels($this->report)['path'][$client_code])) {
                $this->data['path'] = $this->getLabels($this->report)['path'][$client_code];
            } else if (isset($this->getLabels($this->report)['path']['EIPLCOMMON'])) {
                $this->data['path'] = $this->getLabels($this->report)['path']['EIPLCOMMON'];
            }
            $model->scenario = $this->data['scenario'];
            if (strpos($this->data['param'], 'p_milk_type') !== FALSE) {
                $model->p_milk_type = 0;
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->LoadReport($model);
        }
        return $this->render('index', ['result' => $this->output, 'report' => $this->report, 'data' => $this->data, 'model' => $model]);
    }

    /* Milk Collection Reports */

    public function actionMemberMilkCollectionSummary() {
        $this->report = 'MemberMilkCollectionSummary';
        return $this->actionIndex();
    }

    public function actionMemberMilkCollectionRegister() {
        $this->report = 'MemberMilkCollectionRegister';
        return $this->actionIndex();
    }

    public function actionShiftReportNameWise() {
        $this->report = 'ShiftReportNameWise';
        return $this->actionIndex();
    }

    public function actionConsolidatedMilkCollection() {
        $this->report = 'ConsolidatedMilkCollectionDate';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('ReportsModel')['report_type'] == '1') {
                $this->report = 'ConsolidatedMilkCollectionDateShift';
            }
        }
        return $this->actionIndex();
    }

    public function actionConsolidatedDcsMilkCollection() {

        $this->report = 'ConsolidatedDcsMilkCollectionDate';

        if (Yii::$app->request->post('ReportsModel')['with_and_without_milktype'] == 'With Milk Type') {
            if (Yii::$app->request->post()) {
                if (Yii::$app->request->post('ReportsModel')['report_type'] == '1') {
                    $this->report = 'ConsolidatedDcsMilkCollectionDateShift';
                }
            }
        } else if (Yii::$app->request->post('ReportsModel')['with_and_without_milktype'] == 'Without Milk Type') {
            $this->report = 'ConsolidatedDcsMilkCollectionDateWithout';
            if (Yii::$app->request->post()) {
                if (Yii::$app->request->post('ReportsModel')['report_type'] == '1') {
                    $this->report = 'ConsolidatedDcsMilkCollectionDateShiftWithout';
                }
            }
        }
        return $this->actionIndex();
    }

    public function actionConsolidatedUnionMilkCollection() {

        $this->report = 'ConsolidatedUnionMilkCollectionDate';

        if (Yii::$app->request->post('ReportsModel')['with_and_without_milktype'] == 'With Milk Type') {
            if (Yii::$app->request->post()) {
                if (Yii::$app->request->post('ReportsModel')['report_type'] == '1') {
                    $this->report = 'ConsolidatedUnionMilkCollectionDateShift';
                }
            }
        } else if (Yii::$app->request->post('ReportsModel')['with_and_without_milktype'] == 'Without Milk Type') {
            $this->report = 'ConsolidatedUnionMilkCollectionDateWithout';
            if (Yii::$app->request->post()) {
                if (Yii::$app->request->post('ReportsModel')['report_type'] == '1') {
                    $this->report = 'ConsolidatedUnionMilkCollectionDateShiftWithout';
                }
            }
        }
        return $this->actionIndex();
    }

    public function actionBlockWiseCollection() {
        $this->report = 'BlockWiseCollection';
        return $this->actionIndex();
    }

    public function actionDcsCollectionDispatchDifferenceReport() {
        $this->report = 'DcsCollectionDispatchDifferenceReport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('ReportsModel')['with_and_without_milktype'] == 'With Milk Type') {
                $this->report = 'DcsCollectionDispatchDifferenceReportWithMilkType';
            }
        }
        return $this->actionIndex();
    }

    public function actionDcsCollectionVsDispatchGraph() {
        $this->report = 'DcsCollectionVsDispatchGraph';
        return $this->actionIndex();
    }

    public function actionUnionCollectionDispatchDiffReport() {
        $this->report = 'UnionCollectionDispatchDifferenceReport';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('ReportsModel')['with_and_without_milktype'] == 'Without Milk Type') {
                $this->report = 'UnionCollectionDispatchDifferenceReportWithOutMilkType';
            }
        }
        return $this->actionIndex();
    }

    public function actionSocietyWiseMemberRegister() {
        $this->report = 'SocietyWiseMemberRegister';
        return $this->actionIndex();
    }

    public function actionPaymentAuthorization() {
        $this->report = 'PaymentAuthorization';
        return $this->actionIndex();
    }

    public function actionUnionWiseMemberRegister() {
        $this->report = 'UnionWiseMemberRegister';
        return $this->actionIndex();
    }

    public function actionMemberClassificationRegister() {
        $this->report = 'MemberClassificationRegister';
        return $this->actionIndex();
    }

    public function actionMemberWisePaymentRegister() {
        $this->report = 'MemberWisePaymentRegister';
        return $this->actionIndex();
    }

    public function actionMemberPaymentHeldup() {
        $this->report = 'MemberPaymentHeldup';
        return $this->actionIndex();
    }

    public function actionSocietyDetails() {
        $this->report = 'SocietyDetails';
        return $this->actionIndex();
    }

    public function actionBmcCollection() {
        $this->report = 'BmcCollection';
        return $this->actionIndex();
    }

    public function actionActualBmcCollection() {
        $this->report = 'ActualBmcCollection';
        return $this->actionIndex();
    }

    public function actionRmrdMilkCollection() {
        $this->report = 'RmrdMilkCollection';
        return $this->actionIndex();
    }

    public function actionVariationMilkTypeDateWise() {
        $this->report = 'VariationMilkTypeDateWise';
        return $this->actionIndex();
    }

    public function actionVariationMilkTypeVillageWise() {
        $this->report = 'VariationMilkTypeVillageWise';
        return $this->actionIndex();
    }

    public function actionVariationDateWise() {
        $this->report = 'VariationDateWise';
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

    public function actionDifferenceReport() {
        $this->report = 'DifferenceReport';
        return $this->actionIndex();
    }

    public function actionDifferenceReportDateWise() {
        $this->report = 'DifferenceReportDateWise';
        return $this->actionIndex();
    }

    public function actionBmcSummaryReport() {
        $this->report = 'BmcSummaryReport';
        return $this->actionIndex();
    }

    public function actionDifferenceReportVillageWise() {
        $this->report = 'DifferenceReportVillageWise';
        return $this->actionIndex();
    }

    public function actionGprsDataReconciliation() {
        $this->report = 'GprsDataReconciliation';
        return $this->actionIndex();
    }

    public function actionBmcPayment() {
        $this->report = 'BMCPayment';
        return $this->actionIndex();
    }

    public function actionVendorMilkPayment() {
        $client_code = \Yii::$app->session->get('eiplCode');
        $this->report = 'VendorMilkPayment';
        if ($client_code == 'VARDDAN') {
            $this->report = 'VendorMilkPaymentVarddan';
        }
        if ($client_code == 'ANIG') {
            $this->report = 'VendorMilkPaymentAnig';
        }
        if ($client_code == 'SHUDDH') {
            $this->report = 'VendorMilkPaymentShuddh';
        }
        if ($client_code == 'PARAM') {
            $this->report = 'VendorMilkPaymentShuddh';
        }
        if ($client_code == 'RAMSONS') {
            $this->report = 'VendorMilkPaymentShuddh';
        }
        return $this->actionIndex();
    }

    public function actionMemberMilkPayment() {
        $this->report = 'MemberMilkPayment';
        return $this->actionIndex();
    }

    public function actionVendorMilkBill() {
        $client_code = \Yii::$app->session->get('eiplCode');
        $this->report = 'VendorMilkBill';
        if ($client_code == 'SHUDDH') {
            $this->report = 'VendorMilkBillShuddh';
        }
        return $this->actionIndex();
    }

    public function actionMemberMilkBill() {
        $this->report = 'MemberMilkBill';
        return $this->actionIndex();
    }

    public function actionStaffSalary() {
        $this->report = 'StaffSalary';
        return $this->actionIndex();
    }

    public function actionVendorBill() {
        $this->report = 'VendorBill';
        return $this->actionIndex();
    }

    public function actionInchargeRemuneration() {
        $this->report = 'InchargeRemuneration';
        return $this->actionIndex();
    }

    public function actionMemberBillAbstract() {
        $this->report = 'MemberBillAbstract';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillVarddan() {
        $this->report = 'VendorMilkBillVarddan';
        return $this->actionIndex();
    }

    public function actionVendorBillMmd() {
        $this->report = 'VendorBillMmd';
        return $this->actionIndex();
    }

    public function actionFarmerIncentive() {
        $this->report = 'FarmerIncentive';
        return $this->actionIndex();
    }

    public function actionVlccTransactionDataReport() {
        $this->report = 'VlccTransactionDataReport';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillSnmilk() {
        $this->report = 'VendorMilkBillSnmilk';
        return $this->actionIndex();
    }

    public function actionMccDayBookDispatchHub() {
        $this->report = 'MccDayBookDispatchHub';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillJgf() {
        $this->report = 'VendorMilkBillJgf';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillAnig() {
        $this->report = 'VendorMilkBillAnig';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillShivPrasad() {
        $this->report = 'VendorMilkBillShivPrasad';
        return $this->actionIndex();
    }

    public function actionMemberMilkBillShivPrasad() {
        $this->report = 'MemberMilkBillShivPrasad';
        return $this->actionIndex();
    }

    public function actionVlccTransactionDataReportRegion() {
        $this->report = 'VlccTransactionDataReportRegion';
        return $this->actionIndex();
    }

    public function actionMilkReceiptForMember() {
        $this->report = 'MilkReceiptForMember';
        return $this->actionIndex();
    }

    public function actionMilkReceiptForBmc() {
        $this->report = 'MilkReceiptForBMC';
        return $this->actionIndex();
    }

    public function actionProductSaleInvoiceForMember() {
        $this->report = 'ProductSaleInvoiceForMember';
        return $this->actionIndex();
    }

    public function actionProductSaleInvoiceForCustomer() {
        $this->report = 'ProductSaleInvoiceForCustomer';
        return $this->actionIndex();
    }

    public function actionPaymentSummary() {
        $this->report = 'PaymentSummary';
        return $this->actionIndex();
    }

    public function actionProductSaleSummary() {
        $this->report = 'ProductSaleSummary';
        return $this->actionIndex();
    }

    public function actionBmcCollectionSummary() {
        $this->report = 'BmcCollectionSummary';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillSbd() {
        $this->report = 'VendorMilkBillSbd';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillGlt() {
        $this->report = 'VendorMilkBillGLT';
        return $this->actionIndex();
    }

    public function actionVendorMilkBillSummaryGlt() {
        $this->report = 'VendorMilkBillSummaryGLT';
        return $this->actionIndex();
    }

    public function actionMemberPaymentVrs() {
        $this->report = 'MemberPaymentVrs';
        return $this->actionIndex();
    }

    public function actionVspPaymentVrs() {
        $this->report = 'VspPaymentVrs';
        return $this->actionIndex();
    }

    public function actionVspPaymentOnlineVrs() {
        $this->report = 'VspPaymentOnlineVrs';
        return $this->actionIndex();
    }

    public function actionProvisionalMemberRegister() {
        $this->report = 'ProvisionalMemberRegister';
        return $this->actionIndex();
    }

    public function actionMilkChillBillCenterWise() {
        $this->report = 'MilkChillBillCenterWise';
        return $this->actionIndex();
    }

    public function actionMilkChillingBillLrNoWise() {
        $this->report = 'MilkChillingBillLrNoWise';
        return $this->actionIndex();
    }

    public function actionRptMemberRegisterAll() {
        $this->report = 'RptMemberRegisterAll';
        return $this->actionIndex();
    }

    public function actionVendorBillElanad() {
        $this->report = 'VendorBillElanad';
        return $this->actionIndex();
    }

    public function actionMppSurvey() {
        $this->report = 'MppSurvey';
        return $this->actionIndex();
    }

    public function actionVcgMeeting() {
        $this->report = 'VcgMeeting';
        return $this->actionIndex();
    }

    public function actionMccChillingBill() {
        $this->report = 'MccChillingBill';
        return $this->actionIndex();
    }

    public function actionMccChillingBillInvoice() {
        $this->report = 'MccChillingBillInvoice';
        return $this->actionIndex();
    }

    public function actionMemberPaymentNawasa() {
        $this->report = 'MemberPaymentNawasa';
        return $this->actionIndex();
    }

    public function actionVspPaymentNawasa() {
        $this->report = 'VspPaymentNawasa';
        return $this->actionIndex();
    }

    public function actionVspPaymentOnlineNawasa() {
        $this->report = 'VspPaymentOnlineNawasa';
        return $this->actionIndex();
    }

    public function actionBankAdvice() {
        $this->report = 'BankAdvice';
        return $this->actionIndex();
    }

    public function actionMpgBillStatement() {
        $this->report = 'MpgBillStatement';
        return $this->actionIndex();
    }

    public function actionProductSaleInvoice() {
        $this->report = 'ProductSaleInvoice';
        return $this->actionIndex();
    }

    public function actionPrimaryTransporterMonthlyBill() {
        $this->report = 'PrimaryTransporterMonthlyBill';
        return $this->actionIndex();
    }

    public function actionPartyPaymentBill() {
        $this->report = 'PartyPaymentBill';
        return $this->actionIndex();
    }

    public function actionShiftWiseBill() {
        $this->report = 'ShiftWiseBill';
        return $this->actionIndex();
    }

    public function actionCompleteTrip() {
        $this->report = 'CompleteTrip';
        return $this->actionIndex();
    }

    public function actionCcTruckSlip() {
        $this->report = 'CcTruckSlip';
        return $this->actionIndex();
    }

    public function actionDmrReport() {
        $this->report = 'DmrReport';
        return $this->actionIndex();
    }

    public function actionCcSubStandardMrg() {
        $this->report = 'CcSubStandardMrg';
        return $this->actionIndex();
    }

    public function actionDmrCheckList() {
        $this->report = 'DmrCheckList';
        return $this->actionIndex();
    }

    public function actionDmrWeightedAverage() {
        $this->report = 'DmrWeightedAverage';
        return $this->actionIndex();
    }

    public function actionMccBonusReport() {
        $this->report = 'MccBonusReport';
        return $this->actionIndex();
    }

    public function actionMccMaintanceReport() {
        $this->report = 'MccMaintanceReport';
        return $this->actionIndex();
    }

    public function actionMccVlcRecieptRouteWise() {
        $this->report = 'MccVlcRecieptRouteWise';
        return $this->actionIndex();
    }

    public function actionBmcMilkPaymentVoucher() {
        $this->report = 'BmcMilkPaymentVoucher';
        return $this->actionIndex();
    }

    public function actionDayWiseSummary() {
        $this->report = 'DayWiseSummary';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('ReportsModel')['report_type'] == '1') {
                $this->report = 'RouteWiseSummary';
            }
        }
        return $this->actionIndex();
    }

    public function actionVlccTransactionDataReportRegionAll() {
        $this->report = 'VlccTransactionDataReportRegionAll';
        return $this->actionIndex();
    }

    public function actionUserAttendanceReport() {
        $this->report = 'UserAttendanceReport';
        return $this->actionIndex();
    }

    public function actionMemberBankPaymentReport() {
        $this->report = 'MemberBankPaymentReport';
        return $this->actionIndex();
    }
    
    public function actionVendorCommissionPayment() {
        $this->report = 'VendorCommissionPayment';
        return $this->actionIndex();
    }
    
    public function actionWeighSlip() {
        $this->report = 'WeighSlip';
        return $this->actionIndex();
    }

    /* Jasper Call */

    private function LoadReport($model) {
        if (isset($model->p_plant_code) && $model->p_plant_code == 0 && !empty(Yii::$app->session->get('Plant'))) {
            $model->p_plant_code = Yii::$app->session->get('Plant');
        }
        if (isset($model->p_mcc_code) && $model->p_mcc_code == 0 && !empty(Yii::$app->session->get('MCC'))) {
            $model->p_mcc_code = Yii::$app->session->get('MCC');
        }
        if (isset($model->p_bmc_code) && $model->p_bmc_code == 0 && !empty(Yii::$app->session->get('BMC'))) {
            $model->p_bmc_code = Yii::$app->session->get('BMC');
        }
        if (isset($model->p_dcs_code) && $model->p_dcs_code == 0 && !empty(Yii::$app->session->get('Dcs'))) {
            $model->p_dcs_code = Yii::$app->session->get('Dcs');
        }
        $this->type = Yii::$app->request->post('html');
// var_dump($model);die;
        if (!in_array($this->type, ['tcpdf', 'tcpdf_two'])) {
            $controls = [];
            $param = explode(',', $this->data['param']);
            foreach ($param as $key => $value) {
                $value_array = explode(':', $value);
                $value = $value_array[0];
                if (isset($value_array[1]) && $value_array[1] == 'string') {
                    $model->{$value} = date('Y-m-d', strtotime($model->{$value}));
                    if (isset($value_array[2])) {
                        $shift = \Yii::$app->general->getshift($model->{$value_array[2]});
                        $model->{$value} .= ' ' . $shift . '.000';
                    }
                }
                if (isset($value_array[1]) && $value_array[1] == 'month') {
                    $month = !empty($model->{$value}) ? date('01-') . $model->{$value} : NULL;
                    $model->{$value} = !empty($month) ? date('Y-m', strtotime($month)) : NULL;
                }
                if ($value == 'p_dcs_payment') {
                    $pay_cycle = explode(':', $model->{$value});
                    $controls[$value] = (int) $pay_cycle[1];
                    $controls['p_dcs_payment_date'] = $pay_cycle[0];
                } else {
                    $controls[$value] = is_array($model->{$value}) ? ',' . implode(',', $model->{$value}) . ',' : $model->{$value};
                }
                if (isset($value_array[1]) && $value_array[1] == 'month') {
                    $model->{$value} = !empty($month) ? date('m-Y', strtotime($month)) : NULL;
                }
            }
            //$controls['locale'] = Yii::$app->session->get('LanguageCode');
            $controls['locale'] = !empty($model->locale) ? $model->locale : 'en';
            //$controls['REPORT_LOCALE'] = Yii::$app->session->get('LanguageCode');
            $controls['REPORT_LOCALE'] = (!empty($model->locale) ? $model->locale : 'en') . '_IN';
            //$controls['digit_config'] = Yii::$app->session->get('DigitConfig');
            if (isset(Yii::$app->params['language_mapping']) && isset(Yii::$app->params['language_mapping'][$controls['locale']])) {
                $controls['locale'] = Yii::$app->params['language_mapping'][$controls['locale']];
                $controls['REPORT_LOCALE'] = $controls['locale'] . '_IN';
            }
            $controls['digit_config'] = !empty($model->digit_config) ? $model->digit_config : 0;
//                  var_dump($controls);die;
            if (!isset($this->data['bkg_export']) || User::canRoute('jasperreports/default/jasper-live-report-generation')) {
                $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);
                $clientJasper->setRequestTimeout(300);
                $this->output = $clientJasper->reportService()->runReport(\Yii::$app->params['report_path'] . $this->data['path'], $this->type, null, null, $controls);
                if ($this->type != 'html') {
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Description: File Transfer');
                    $filename = !empty($this->data['filename']) ? $this->data['filename'] : $this->report;
                    header('Content-Disposition: attachment; filename=' . $filename . '.' . $this->type);
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . strlen($this->output));
                    header('Content-Type: application/' . $this->type);
                    echo $this->output;
                }
            } else {
                $msg = $this->RegisterReportRequest('jasper', $this->data, $controls);
                $this->output = '<p><center><b>' . $msg . '<b/></center><p/>';
            }
        } else {
            $client_code = \Yii::$app->session->get('eiplCode');
            if (strtolower($client_code) == 'mmd') {
                if (!in_array($this->type, ['tcpdf_two'])) {
                    \Yii::$app->pdf->generatePdfMMd($model);
                } else {
                    \Yii::$app->pdf->generatePdfMMdTwo($model);
                }
            } else if (strtolower($client_code) == 'elanad') {
                if (!in_array($this->type, ['tcpdf'])) {
                    \Yii::$app->pdf->generatePdfAtmos($model);
                } else {
                    \Yii::$app->pdf->generatePdfElanad($model);
                }
            } else {
                \Yii::$app->pdf->generatePdfAtmos($model);
            }
//            $this->redirect(['/pdf/pdf', 'param' => $model]);
        }
    }

    /* Reports Configuration */

    public static function getLabels($l) {
        $label = [
            'MemberMilkCollectionSummary' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_member_code:p_dcs_code,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/MemberMilkCollectionSummary',
                'scenario' => 'MemberMilkCollectionSummary',
                'title' => '101 - Member Milk Collection Summary',
            ],
            'MemberMilkCollectionRegister' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_union_code,p_report_name,p_member_type',
                'path' => 'milkcollection/MemberMilkCollectionRegister',
                'scenario' => 'MemberMilkCollectionRegister',
                'title' => '104 - Member Milk Collection Register',
            ],
            'ShiftReportNameWise' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_collection_date:string:shift,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/ShiftReportNameWise',
                'scenario' => 'ShiftReportNameWise',
                'title' => '103 - Shift Report (Name Wise)',
            ],
            'ConsolidatedMilkCollectionDate' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/SocietyWiseMilkCollectionDate',
                'scenario' => 'ConsolidatedMilkCollection',
                'title' => '102 - Consolidated Milk Collection',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedMilkCollectionDateShift' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/SocietyWiseMilkCollectonDateAndShiftWise',
                'scenario' => 'ConsolidatedMilkCollection',
                'title' => '102 - Consolidated Milk Collection',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedDcsMilkCollectionDate' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectDateWise',
                'scenario' => 'ConsolidatedDcsMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedDcsMilkCollectionDateShift' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectDateShiftWise',
                'scenario' => 'ConsolidatedDcsMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedDcsMilkCollectionDateWithout' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectDateWiseWithOutMilkType',
                'scenario' => 'ConsolidatedDcsMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedDcsMilkCollectionDateShiftWithout' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectDateShiftWiseWithOutMilkType',
                'scenario' => 'ConsolidatedDcsMilkCollection',
                'title' => '105 - Consolited Milk Collection Union Wise – Table',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedUnionMilkCollectionDate' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_type,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectionDateWiseChart',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '106 - Consolited Milk Collection Union Wise – Graph',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
                'pdf' => true,
            ],
            'ConsolidatedUnionMilkCollectionDateShift' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_type,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectionDateShiftWiseChart',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '106 - Consolited Milk Collection Union Wise – Graph',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
                'pdf' => true,
            ],
            'ConsolidatedUnionMilkCollectionDateWithout' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_type,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectionDateWiseWithoutMilkTypeChart',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '106 - Consolited Milk Collection Union Wise – Graph',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
                'pdf' => true,
            ],
            'ConsolidatedUnionMilkCollectionDateShiftWithout' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_type,p_dcs_name,p_union_name,p_route_name,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkCollectionDateShiftWiseWithoutMilkTypeChart',
                'scenario' => 'ConsolidatedUnionMilkCollection',
                'title' => '106 - Consolited Milk Collection Union Wise – Graph',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
                'pdf' => true,
            ],
            'BlockWiseCollection' => [
                'param' => 'p_district_code:union_code,p_sub_district_code:p_district_code,p_block_name:p_sub_district_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/BlockWise',
                'scenario' => 'BlockWiseMilkCollection',
                'title' => '114 - Consolited Milk Collection Block Wise',
            ],
            'DcsCollectionDispatchDifferenceReport' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/SocietyWiseMilkDispatchDifference',
                'scenario' => 'CollectionDispatchDifferenceReport',
                'title' => '107 - Society Wise Collection and Dispatch Difference Report',
            ],
            'DcsCollectionDispatchDifferenceReportWithMilkType' => [
                'param' => 'p_route_code:union_code,p_dcs_code:p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/SocietyWiseMilkDispatchDifferenceWithMilkType',
                'scenario' => 'CollectionDispatchDifferenceReport',
                'title' => '107 - Society Wise Collection and Dispatch Difference Report',
            ],
            'DcsCollectionVsDispatchGraph' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code:union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_union_code,p_report_name',
                'path' => 'milkcollection/MilkCollectionVsDispatchChart',
                'scenario' => 'CollectionVsDispatchGraph',
                'title' => '116 - Society Wise Collection vs Dispatch - Graph',
                'pdf' => true,
            ],
            'UnionCollectionDispatchDifferenceReport' => [
                'param' => 'p_route_code:union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkDispatchDifference',
                'scenario' => 'UnionCollectionDispatchDifferenceReport',
                'title' => '108 - Union Wise Collection and Dispatch Difference Report',
            ],
            'UnionCollectionDispatchDifferenceReportWithOutMilkType' => [
                'param' => 'p_route_code:union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,with_and_without_milktype,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWiseMilkDispatchDifferenceWithoutMilk',
                'scenario' => 'UnionCollectionDispatchDifferenceReport',
                'title' => '108 - Union Wise Collection and Dispatch Difference Report',
            ],
            'SocietyWiseMemberRegister' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code:union_code,p_dcs_payment,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/SocietyWisePayMentRegister',
                'scenario' => 'MemberRegister',
                'title' => '109 - Society Wise Payment Register',
            ],
            'PaymentAuthorization' => [
                'param' => 'p_dcs_payment,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/PaymentAuthorization',
                'scenario' => 'PaymentAuth',
                'title' => '115 - Payment Authorization',
            ],
            'UnionWiseMemberRegister' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code:union_code,p_dcs_payment,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWisePayMentRegister',
                'scenario' => 'MemberRegister',
                'title' => '110 - Union Wise Payment Register',
            ],
            'MemberWisePaymentRegister' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code:union_code,p_member_code:p_dcs_code,p_dcs_payment,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/MemberWisePayMentRegister',
                'scenario' => 'MemberWisePaymentRegister',
                'title' => '111 - Member Wise Payment Register',
            ],
            'MemberClassificationRegister' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code:union_code,p_member_code:p_dcs_code,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/MemberClassificationRegister',
                'scenario' => 'MemberClassificationRegister',
                'title' => '112 - Member Classification Register',
            ],
            'MemberPaymentHeldup' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code:union_code,p_member_code:p_dcs_code,p_dcs_payment,p_language_code,p_union_code,p_is_bank,p_report_name',
                'path' => 'milkcollection/MemberPaymentHeldUp',
                'scenario' => 'MemberPaymentHeldup',
                'title' => '113 - Member Payment Held Up',
            ],
            'SocietyDetails' => [
                'param' => 'p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_union_code,p_report_name,p_member_type,p_no_of_pouring_day,p_pouring_qty',
                'path' => 'milkcollection/SocietyDetails',
                'scenario' => 'SocietyDetails',
                'title' => '117 - Society Details',
            ],
            'ActualBmcCollection' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_ltr_kg,p_language_code,p_report_name',
                'path' => 'bmccollection/PPWiseActualMilkCollection',
                'scenario' => 'ActualBmcCollection',
                'title' => '301 - Actual Bmc Collection',
            ],
            'RmrdMilkCollection' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_milk_class,p_ltr_kg,p_language_code,p_report_name',
                'path' => 'bmccollection/RMRDMilkCollection',
                'scenario' => 'RmrdMilkCollection',
                'title' => '302 - RMRD Milk Collection',
            ],
            'BmcSummaryReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_ltr_kg,p_language_code,p_report_name',
                'path' => 'bmccollection/BMCSummaryReport',
                'scenario' => 'BmcSummaryReport',
                'title' => '303 - Bmc Summary Report',
            ],
            'VariationMilkTypeDateWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/VariationReportDCSToMCCMilkType',
                'scenario' => 'VariationMilkTypeDateWise',
                'title' => '304 - Milk Type Variation Date Wise',
            ],
            'VariationMilkTypeVillageWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/VariationReportDCSToMCCVillageWiseMilkType',
                'scenario' => 'VariationMilkTypeVillageWise',
                'title' => '305 - Milk Type Variation Village Wise',
            ],
            'VariationDateWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/VariationReportDCSToMCC',
                'scenario' => 'VariationDateWise',
                'title' => '306 - Date Wise Variation',
            ],
            'VariationVillageWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/VariationReportDCSToMCCVillageWise',
                'scenario' => 'VariationVillageWise',
                'title' => '307 - Village Wise Variation',
            ],
            'VariationPercentageWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/VariationReportDCSToMCCFATSNF',
                'scenario' => 'VariationPercentageWise',
                'title' => '308 - Percentage Wise Variation',
            ],
            'DifferenceReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => '',
                'scenario' => 'DifferenceReport',
                'title' => '309 - Difference Report',
            ],
            'DifferenceReportDateWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/DifferenceReportVLCCToMCCForBMC',
                'scenario' => 'DifferenceReportDateWise',
                'title' => '310 - Date Wise Difference Report',
            ],
            'DifferenceReportVillageWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/DifferenceReportVLCCToMCCVillageWise',
                'scenario' => 'DifferenceReportVillageWise',
                'title' => '311 - Village Wise Difference Report',
            ],
            'BmcCollection' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_ltr_kg,p_language_code,p_report_name',
                'path' => 'bmccollection/BMCCollectionReport',
                'scenario' => 'BmcCollection',
                'title' => '312 - Bmc Collection',
            ],
            'GprsDataReconciliation' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_route_code:p_union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'bmccollection/DPUGPRSDataReconciliation',
                'scenario' => 'GprsDataReconciliation',
                'title' => '313 - DPU-GPRS Data Reconciliation',
            ],
            'BMCPayment' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_from_date:string,p_to_date:string,p_language_code,p_report_name',
                'path' => 'bmccollection/BMCPayment',
                'scenario' => 'BMCPayment',
                'title' => '601 - BMC Payment',
            ],
            'VendorMilkPayment' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code,p_language_code,p_report_name',
                'path' => 'vsp/VspPaymentBill',
                'scenario' => 'VendorMilkPayment',
                'title' => '604 - Vendor Milk Payment',
                'bkg_export' => TRUE,
            ],
            'MemberMilkPayment' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => ['EIPLCOMMON' => 'vsp/MemberPaymentBill', 'DHAMALE' => 'vsp/MemberPaymentBillDhamale'],
                'scenario' => 'MemberMilkPayment',
                'title' => '605 - Member Milk Payment',
                'bkg_export' => TRUE,
            ],
            'VendorMilkBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkBill',
                'scenario' => 'VendorMilkBill',
                'title' => '609 - Vendor Milk Bill',
                'bkg_export' => TRUE,
            ],
            'MemberMilkBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/MemberMilkBill',
                'scenario' => 'MemberMilkBill',
                'title' => '610 - Member Milk Bill',
                'bkg_export' => TRUE,
            ],
            'StaffSalary' => [
                'param' => 'p_union_code,p_staff_member_code,p_month:month,p_language_code,p_report_name',
                'path' => 'staff/StaffSalary',
                'scenario' => 'StaffSalary',
                'title' => '701 - Staff Salary',
            ],
            'VendorBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcsc_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/BillReportFormat1',
                'scenario' => 'VendorBill',
                'title' => '612 - Vendor Bill',
                'tcpdf' => true,
            ],
            'InchargeRemuneration' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_from_date:string,p_to_date:string,p_union_code,p_report_name',
                'path' => 'vsp/CCInchargeRemuneration',
                'scenario' => 'InchargeRemuneration',
                'title' => '614 - CC Incharge Remuneration',
                'tcpdf' => true,
            ],
            'VendorMilkPaymentVarddan' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code,p_language_code,p_report_name',
                'path' => 'vsp/VspPaymentBillVardaan',
                'scenario' => 'VendorMilkPayment',
                'title' => '604 - Vendor Milk Payment',
            ],
            'MemberBillAbstract' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code:all_routes,p_dcs_code:route_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/MemberBillAbstract',
                'scenario' => 'MemberBillAbstract',
                'title' => '615 - Member Bill Abstract',
            ],
            'VendorMilkBillVarddan' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkBillVardaan',
                'scenario' => 'VendorMilkBillVardaan',
                'title' => '616 - Milk Bill',
            ],
            'VendorBillMmd' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_billing_for,p_route_code:all_routes,p_dcsc_code:route_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/VendorBillFormated',
                'scenario' => 'VendorBillMmd',
                'title' => '612 - Member and Vendor Milk Bill',
                'tcpdf' => true,
            ],
            'FarmerIncentive' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_report_name',
                'path' => 'vsp/FarmerIncentive',
                'scenario' => 'FarmerIncentive',
                'title' => '110 - Farmer Incentive',
            ],
            'VendorMilkBillSnmilk' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkBillSNMilk',
                'scenario' => 'VendorMilkBillSnmilk',
                'title' => '616 - Milk Bill',
            ],
            'MccDayBookDispatchHub' => [
                'param' => 'p_union_code,p_bmc_code:union_code,p_from_date:string,p_to_date:string',
                'path' => 'TankerMovement/MCCDayBook',
                'scenario' => 'MccDayBookDispatchHub',
                'title' => 'MCC Day Book Dispatch Hub',
            ],
            'VendorMilkBillJgf' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VSPPaymentJGF',
                'scenario' => 'VendorMilkBillJgf',
                'title' => '616 - Milk Bill',
            ],
            'VlccTransactionDataReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'milkcollection/VLCCTransactionDataFTP',
                'scenario' => 'VlccTransactionDataReport',
                'title' => 'VLCC Transaction Data Report',
                'bkg_export' => TRUE
            ],
            'VendorMilkBillAnig' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VspPaymentBillAnig',
                'scenario' => 'VendorMilkBillAnig',
                'title' => '616 - Milk Bill',
            ],
            'VendorMilkBillShivPrasad' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkBillShivPrasad',
                'scenario' => 'VendorMilkBillShivPrasad',
                'title' => '609 - Vendor Milk Bill',
            ],
            'MemberMilkBillShivPrasad' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/MemberMilkBillShivPrasad',
                'scenario' => 'MemberMilkBillShivPrasad',
                'title' => '610 - Member Milk Bill',
            ],
            'VlccTransactionDataReportRegion' => [
                'param' => 'p_union_code,state_code,region_code,area_code,p_bmc_code:area_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'milkcollection/VLCCTransactionDataFTPRegion',
                'scenario' => 'VlccTransactionDataReportRegion',
                'title' => 'VLCC Transaction Data Report',
            ],
            'VendorMilkPaymentAnig' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkPaymentAnig',
                'scenario' => 'VendorMilkPayment',
                'title' => '604 - Vendor Milk Payment',
            ],
            'MilkReceiptForMember' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_from_date:string,p_to_date:string,p_report_name',
                'path' => 'milkcollection/MilkReceiptForMember',
                'scenario' => 'MilkReceiptForMember',
                'title' => 'Milk Receipt For Member',
            ],
            'MilkReceiptForBMC' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcsc_code,p_from_date:string,p_to_date:string,p_report_name',
                'path' => 'milkcollection/MilkReceiptForBMC',
                'scenario' => 'MilkReceiptForBMC',
                'title' => 'Milk Receipt For BMC',
            ],
            'ProductSaleInvoiceForMember' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_from_date:string,p_to_date:string,p_report_name',
                'path' => 'milkcollection/ProductSaleInvoiceForMember',
                'scenario' => 'ProductSaleInvoiceForMember',
                'title' => 'Product Sale Invoice For Member',
            ],
            'ProductSaleInvoiceForCustomer' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcsc_code,p_from_date:string,p_to_date:string,p_report_name',
                'path' => 'milkcollection/ProductSaleInvoiceForCustomer',
                'scenario' => 'ProductSaleInvoiceForCustomer',
                'title' => 'Product Sale Invoice For Customer',
            ],
            'PaymentSummary' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VspPaymentForDevMilk',
                'scenario' => 'PaymentSummary',
                'title' => '627 - Payment Summary',
            ],
            'ProductSaleSummary' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_payment_cycle_code:type_check',
                'path' => 'milkcollection/ProductSaleSummary',
                'scenario' => 'ProductSaleSummary',
                'title' => 'Product Sale Summary',
            ],
            'BmcCollectionSummary' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'bmccollection/BmccollectionSummary',
                'scenario' => 'BmcCollectionSummary',
                'title' => 'Bmc Collection Summary',
            ],
            'VendorMilkBillSbd' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VendorMilkBillSBD',
                'scenario' => 'VendorMilkBillSbd',
                'title' => 'Vendor Milk Bill Sbd',
            ],
            'VendorMilkBillGLT' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_dcs_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VendorMilkBillGLT',
                'scenario' => 'VendorMilkBillGLT',
                'title' => 'Vendor Milk Bill',
                'bkg_export' => TRUE,
            ],
            'VendorMilkBillSummaryGLT' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_dcs_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VendorMilkBillSummaryForGLT',
                'scenario' => 'VendorMilkBillSummaryGLT',
                'title' => 'Vendor Milk Bill Summary',
                'bkg_export' => TRUE,
            ],
            'MemberPaymentVrs' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/MemberPaymentVRS',
                'scenario' => 'MemberPaymentVrs',
                'title' => 'Member Payment',
                'bkg_export' => TRUE,
                'filename' => 'MemberPayment',
            ],
            'VspPaymentVrs' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VSPPaymentVRS',
                'scenario' => 'VspPaymentVrs',
                'title' => 'Vsp Payment',
                'bkg_export' => TRUE,
            ],
            'VspPaymentOnlineVrs' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VSPPaymentOnlineVRS',
                'scenario' => 'VspPaymentOnlineVrs',
                'title' => 'Vsp Payment Online',
                'bkg_export' => TRUE,
            ],
            'VendorMilkBillShuddh' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkBillShuddh',
                'scenario' => 'VendorMilkBill',
                'title' => '609 - Vendor Milk Bill',
                'bkg_export' => TRUE,
            ],
            'ProvisionalMemberRegister' => [
                'param' => 'p_provisional_member_code,p_lang_code,locale,digit_config',
                'path' => 'MemberRegister',
                'scenario' => 'ProvisionalMemberRegister',
                'title' => 'Provisional Member Register',
            ],
            'VendorMilkPaymentShuddh' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code,p_language_code,p_report_name',
                'path' => 'vsp/VspPaymentBillShuddh',
                'scenario' => 'VendorMilkPayment',
                'title' => '604 - Vendor Milk Payment',
                'bkg_export' => TRUE,
            ],
            'MilkChillBillCenterWise' => [
                'param' => 'p_date,p_lr_no,p_vehicle_no',
                'path' => 'vsp/MilkChillBillCenterWise',
                'scenario' => 'MilkChillBillCenterWise',
                'title' => 'Milk Chill Bill Center Wise',
            ],
            'MilkChillingBillLrNoWise' => [
                'param' => 'p_date,p_lr_no,p_vehicle_no',
                'path' => 'vsp/MilkChillingBillLrNoWise',
                'scenario' => 'MilkChillingBillLrNoWise',
                'title' => 'Milk Chilling Bill Lr No Wise',
            ],
            'RptMemberRegisterAll' => [
                'param' => 'p_from_date:string,p_to_date:string,p_lang_code,locale,digit_config',
                'path' => 'MemberRegisterAll',
                'scenario' => 'RptMemberRegisterAll',
                'title' => 'Approve Farmer Data PDF',
                'bkg_export' => TRUE,
            ],
            'VendorBillElanad' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_billing_for,p_route_code:all_routes,p_dcsc_code:route_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/VendorBillFormated',
                'scenario' => 'VendorBillElanad',
                'title' => '633 - Member and Vendor Milk Bill',
                'tcpdf' => true,
            ],
            'MppSurvey' => [
                'param' => 'p_mpp_survey_id,p_lang_code,locale,digit_config',
                'path' => 'MPPSurveyForm',
                'scenario' => 'MppSurvey',
                'title' => 'MPP Survey',
            ],
            'VcgMeeting' => [
                'param' => 'p_VCG_M_Id,p_lang_code,locale,digit_config',
                'path' => 'VCGMeeting',
                'scenario' => 'VcgMeeting',
                'title' => 'VCG Meeting',
            ],
            'MccChillingBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_from_date:string,p_to_date:string',
                'path' => 'vsp/MCCChillingBill',
                'scenario' => 'MccChillingBill',
                'title' => 'Chilling Bill',
            ],
            'MccChillingBillInvoice' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_from_date:string,p_to_date:string',
                'path' => 'vsp/MCCChillingBillInvoice',
                'scenario' => 'MCCChillingBillInvoice',
                'title' => 'MCC Chilling Bill Invoice',
            ],
            'MemberPaymentNawasa' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/MemberPaymentNawasa',
                'scenario' => 'MemberPaymentNawasa',
                'title' => 'Member Payment',
            ],
            'VspPaymentNawasa' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VSPPaymentNawasa',
                'scenario' => 'VSPPaymentNawasa',
                'title' => 'Vsp Payment',
            ],
            'VspPaymentOnlineNawasa' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/VSPPaymentOnlineNawasa',
                'scenario' => 'VSPPaymentOnlineNawasa',
                'title' => 'Vsp Payment Online',
            ],
            'BankAdvice' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/BankAdvice',
                'scenario' => 'BankAdvice',
                'title' => '634 - Bank Advice',
                'bkg_export' => TRUE,
            ],
            'MpgBillStatement' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/MPGBillStatement',
                'scenario' => 'MpgBillStatement',
                'title' => 'MPG Bill Statement',
                'bkg_export' => TRUE,
            ],
            'ProductSaleInvoice' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string,p_to_date:string',
                'path' => 'vsp/ProductSaleInvoice',
                'scenario' => 'ProductSaleInvoice',
                'title' => 'Total Sale Invoice',
                'bkg_export' => TRUE,
            ],
            'PrimaryTransporterMonthlyBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_transporter_code,p_from_date:string,p_to_date:string',
                'path' => 'vsp/PrimaryTransporterMonthlyBill',
                'scenario' => 'PrimaryTransporterMonthlyBill',
                'title' => 'TPT Bill',
                'bkg_export' => TRUE,
            ],
            'PartyPaymentBill' => [
                'param' => 'p_union_code,p_party_master_code,p_from_date:string,p_to_date:string',
                'path' => 'vsp/PartyPaymentBill',
                'scenario' => 'PartyPaymentBill',
                'title' => 'TP Bill',
                'bkg_export' => TRUE,
            ],
            'ShiftWiseBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_billing_for,p_route_code:union_code,p_dcs_code,p_payment_cycle_code:default:dcs,p_lang_code,locale,digit_config',
                'path' => ['ELANAD' => 'vsp/ShiftWiseBill'],
                'scenario' => 'ShiftWiseBill',
                'title' => 'Shift Wise Bill',
                'bkg_export' => TRUE,
            ],
            'CompleteTrip' => [
                'param' => 'p_vehicle_code,p_trip_code:p_vehicle_code,p_from_date:string,p_to_date:string',
                'path' => 'vsp/CompleteTrip',
                'scenario' => 'CompleteTrip',
                'title' => 'Complete Trip Details',
                'bkg_export' => TRUE,
            ],
            'CcTruckSlip' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code:all_routes,p_customer_type,p_customer_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/CCTruckSlip',
                'scenario' => 'CcTruckSlip',
                'title' => 'CC Truck Slip',
                'bkg_export' => TRUE,
            ],
            'DmrReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code:all_routes,p_customer_type,p_customer_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/DRMReport',
                'scenario' => 'DmrReport',
                'title' => 'DMR Report ',
                'bkg_export' => TRUE,
            ],
            'CcSubStandardMrg' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code:all_routes,p_customer_type,p_customer_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_qty_from,p_qty_to,p_fat_from,p_fat_to,p_snf_from,p_snf_to',
                'path' => 'vsp/CCSubStandardMrg',
                'scenario' => 'CcSubStandardMrg',
                'title' => 'MRG Report ',
                'bkg_export' => TRUE,
            ],
            'DmrCheckList' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code,p_customer_type,p_customer_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/DMRCheckList',
                'scenario' => 'DmrCheckList',
                'title' => 'BMC Check List',
            ],
            'DmrWeightedAverage' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/DMRWeightedAverage',
                'scenario' => 'DmrWeightedAverage',
                'title' => 'Route wise Weighted Average',
            ],
            'MccBonusReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/MccBonusReport',
                'scenario' => 'MccBonusReport',
                'title' => 'MCC Bonus',
            ],
            'MccMaintanceReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/MccMaintanceReport',
                'scenario' => 'MccMaintanceReport',
                'title' => 'Mcc maintenance',
            ],
            'MccVlcRecieptRouteWise' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_route_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/MccVlcRecieptRouteWise',
                'scenario' => 'MccVlcRecieptRouteWise',
                'title' => 'Mcc Vlc Reciept Route Wise',
            ],
            'BmcMilkPaymentVoucher' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_payment_cycle_code:default:dcs',
                'path' => 'vsp/BmcMilkPaymentVoucher',
                'scenario' => 'BmcMilkPaymentVoucher',
                'title' => 'BMC Milk Payment Voucher',
            ],
            'DayWiseSummary' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/DayWiseSummary',
                'scenario' => 'DayWiseSummary',
                'title' => 'Day Wise Summary',
                'report_type' => [Yii::t('app', 'Day Wise Summary'), Yii::t('app', 'Route Wise Summary')],
            ],
            'RouteWiseSummary' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/RouteWiseSummary',
                'scenario' => 'DayWiseSummary',
                'title' => 'Route Wise Summary',
                'report_type' => [Yii::t('app', 'Day Wise Summary'), Yii::t('app', 'Route Wise Summary')],
            ],
            'VlccTransactionDataReportRegionAll' => [
                'param' => 'p_union_code,state_code,region_code,area_code,p_bmc_code:area_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'milkcollection/VLCCTransactionDataFTPRegionAll',
                'scenario' => 'VlccTransactionDataReportRegionAll',
                'title' => 'VLCC Transaction Data Report 1',
                'multiArray' => ['state_code', 'region_code', 'area_code', 'p_bmc_code', 'p_dcs_code'],
                'bkg_export' => TRUE,
            ],
            'UserAttendanceReport' => [
                'param' => 'p_union_code,p_login_type,p_from_date:string,p_to_date:string',
                'path' => 'staff/Attendance',
                'scenario' => 'UserAttendanceReport',
                'title' => 'User Attendance Report PDF',
                'bkg_export' => TRUE,
            ],
            'MemberBankPaymentReport' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_payment_cycle_code:default:dcs,p_bank_type',
                'path' => 'vsp/MemberBankPayment',
                'scenario' => 'MemberBankPaymentReport',
                'title' => 'Member Bank Payment - PDF',
            ],
            'VendorCommissionPayment' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift',
                'path' => 'vsp/VendorCommissionPayment',
                'scenario' => 'VendorCommissionPayment',
                'title' => 'VLCC Commission Bill',
                'bkg_export' => TRUE,
            ],
            'WeighSlip' => [
                'param' => 'p_union_code,p_plant_code,p_from_date:string,p_to_date:string,p_product_type',
                'path' => 'vsp/WeighSlip',
                'scenario' => 'WeighSlip',
                'title' => 'Weighment Slip',
                'bkg_export' => TRUE,
            ],
        ];
        return $label[$l];
    }

    public function actionJasperLiveReportGeneration() {
        
    }

}
