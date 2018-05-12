<?php

namespace app\modules\crystalreports\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\crystalreports\models\ReportsModel;

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
            if(strpos( $this->data['param'], 'p_milk_type') !== FALSE){
                $model->p_milk_type = 0;
            }
        }
        if ($model->load(Yii::$app->request->post()) &&  $model->validate()) {
            $this->LoadReport($model);
        }
        return $this->render('index', ['result' => $this->output, 'report' => $this->report, 'data' => $this->data, 'model' => $model]);
    }

    /* Milk Collection Reports */

    public function actionMemberData() {
        $this->report = 'MemberData';
        return $this->actionIndex();
    }
    
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
    
    /* Call Crystal Report */
    private function LoadReport($model) {
        $model->p_village_code = '001003';
        $cmd = "F:\Hardik\Software\CrystalReportsNinja-master\Deployment\CrystalReportsNinja -U sa -P !!EiPl@2017 -S 182.73.178.90,14033 -D TIRUMALA";
        $cmd .= " -F ".$this->data['path']." -O C:\wamp64\www\\tirumala\modules\crystalreports\html";
        $cmd .= "\\".$this->data['file_name'].'.html -E htm';
        $data = Yii::$app->request->post()['ReportsModel'];
        foreach ($data as $key=>$value){
            $cmd.= ' -a "@'.str_replace('p_', '', $key).':'.$value.'"';
        }
        exec($cmd,$out,$retval);
        if(!empty($out)){
            $this->output = $this->data['file_name'];
        }
    }

    /* Reports Configuration */

    private function getLabels($l) {
        $label = [
            'MemberData' => [
                'param' => 'p_dcs_code,p_village_code',
                'path' => 'C:\wamp64\www\tirumala\modules\crystalreports\reports\RptCrystalFarmer.rpt',
                'file_name' => 'member_data',
                'scenario' => 'MemberData',
                'title' => '101 - Member Data',
            ],
            'MemberMilkCollectionSummary' => [
                'param' => 'p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_union_code,p_report_name,p_member_type',
                'path' => 'milkcollection/MemberMilkCollectionRegister',
                'scenario' => 'MemberMilkCollectionSummary',
                'title' => '101 - Member Milk Collection Summary',
            ],
            'MemberMilkCollectionRegister' => [
                'param' => 'p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_member_code:p_dcs_code,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/MemberMilkCollectionSummary',
                'scenario' => 'MemberMilkCollectionRegister',
                'title' => '104 - Member Milk Collection Register',
            ],
            'ShiftReportNameWise' => [
                'param' => 'p_dcs_code,p_collection_date:string:shift,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/ShiftReportNameWise',
                'scenario' => 'ShiftReportNameWise',
                'title' => '103 - Shift Report (Name Wise)',
            ],
            'ConsolidatedMilkCollectionDate' => [
                'param' => 'p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/SocietyWiseMilkCollectionDate',
                'scenario' => 'ConsolidatedMilkCollection',
                'title' => '102 - Consolidated Milk Collection',
                'report_type' => [Yii::t('app', 'Date Wise'), Yii::t('app', 'Date & Shift Wise')],
            ],
            'ConsolidatedMilkCollectionDateShift' => [
                'param' => 'p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_milk_type,p_language_code,p_union_code,p_report_name',
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
                'param' => 'p_dcs_code:union_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_union_code,p_report_name',
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
                'param' => 'p_dcs_code:union_code,p_dcs_payment,p_language_code,p_union_code,p_report_name',
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
                'param' => 'p_dcs_code:union_code,p_dcs_payment,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/UnionWisePayMentRegister',
                'scenario' => 'MemberRegister',
                'title' => '110 - Union Wise Payment Register',
            ],
            'MemberWisePaymentRegister' => [
                'param' => 'p_dcs_code:union_code,p_member_code:p_dcs_code,p_dcs_payment,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/MemberWisePayMentRegister',
                'scenario' => 'MemberWisePaymentRegister',
                'title' => '111 - Member Wise Payment Register',
            ],
            'MemberClassificationRegister' => [
                'param' => 'p_dcs_code:union_code,p_member_code:p_dcs_code,p_language_code,p_union_code,p_report_name',
                'path' => 'milkcollection/MemberClassificationRegister',
                'scenario' => 'MemberClassificationRegister',
                'title' => '112 - Member Classification Register',
            ],
            'MemberPaymentHeldup' => [
                'param' => 'p_dcs_code:union_code,p_member_code:p_dcs_code,p_dcs_payment,p_language_code,p_union_code,p_is_bank,p_report_name',
                'path' => 'milkcollection/MemberPaymentHeldUp',
                'scenario' => 'MemberPaymentHeldup',
                'title' => '113 - Member Payment Held Up',
            ],
            'SocietyDetails' => [
                'param' => 'p_dcs_code,p_from_date:string:from_shift,p_to_date:string:to_shift,p_language_code,p_union_code,p_report_name,p_member_type,p_no_of_pouring_day,p_pouring_qty',
                'path' => 'milkcollection/SocietyDetails',
                'scenario' => 'SocietyDetails',
                'title' => '117 - Society Details',
            ],
        ];
        return $label[$l];
    }

}
