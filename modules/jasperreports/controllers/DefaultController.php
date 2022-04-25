<?php

namespace app\modules\jasperreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\jasperreports\models\ReportsModel;

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
        return $this->actionIndex();
    }

    public function actionMemberMilkPayment() {
        $this->report = 'MemberMilkPayment';
        return $this->actionIndex();
    }

    public function actionVendorMilkBill() {
        $this->report = 'VendorMilkBill';
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
        if ($this->type != 'tcpdf') {
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
                    $controls[$value] = $model->{$value};
                }
                if (isset($value_array[1]) && $value_array[1] == 'month') {
                    $model->{$value} = !empty($month) ? date('m-Y', strtotime($month)) : NULL;
                }
            }
            //$controls['locale'] = Yii::$app->session->get('LanguageCode');
            $controls['locale'] = 'en';
            //$controls['REPORT_LOCALE'] = Yii::$app->session->get('LanguageCode');
            $controls['REPORT_LOCALE'] = 'en';
            //$controls['digit_config'] = Yii::$app->session->get('DigitConfig');
            $controls['digit_config'] = 0;

            //      var_dump($controls);die;
            $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);

            $this->output = $clientJasper->reportService()->runReport(\Yii::$app->params['report_path'] . $this->data['path'], $this->type, null, null, $controls);
            if ($this->type != 'html') {
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename=' . $this->report . '.' . $this->type);
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . strlen($this->output));
                header('Content-Type: application/' . $this->type);
                echo $this->output;
            }
        } else {
            $client_code = \Yii::$app->session->get('eiplCode');
            if (strtolower($client_code) == 'mmd') {
                \Yii::$app->pdf->generatePdfMMd($model);
            } else {
                \Yii::$app->pdf->generatePdfAtmos($model);
            }
//            $this->redirect(['/pdf/pdf', 'param' => $model]);
        }
    }

    /* Reports Configuration */

    private function getLabels($l) {
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
            ],
            'MemberMilkPayment' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/MemberPaymentBill',
                'scenario' => 'MemberMilkPayment',
                'title' => '605 - Member Milk Payment',
            ],
            'VendorMilkBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_customer_type,p_customer_code,p_payment_cycle_code:type_check,p_language_code,p_report_name',
                'path' => 'vsp/VendorMilkBill',
                'scenario' => 'VendorMilkBill',
                'title' => '609 - Vendor Milk Bill',
            ],
            'MemberMilkBill' => [
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_member_code:p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
                'path' => 'vsp/MemberMilkBill',
                'scenario' => 'MemberMilkBill',
                'title' => '610 - Member Milk Bill',
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
                'param' => 'p_union_code,p_plant_code,p_mcc_code,p_bmc_code,p_dcs_code,p_payment_cycle_code:default:dcs,p_language_code,p_report_name',
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
        ];
        return $label[$l];
    }

}
