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
        $this->layout = "@app/themes/pcdf/layouts/dashboardLayout.php";
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
    
    
    /* Call Crystal Report */
    private function LoadReport($model) {
//        $model->p_village_code = '001003';
        $report_path = Yii::$app->params['crystal_report_path'];
        $cmd = "F:\Hardik\Software\CrystalReportsNinja-master\Deployment\CrystalReportsNinja -U sa -P !!EiPl@2017 -S 182.73.178.90,14033 -D TIRUMALA";
        $cmd .= " -F ".$report_path.'\\'.$this->data['report_name'].".rpt -O C:\wamp64\www\\tirumala\modules\crystalreports\html";
        $cmd .= "\\".$this->data['file_name'].'.html -E htm';
        $data = Yii::$app->request->post()['ReportsModel'];
        $data['date1'] = date('Y-m-d', strtotime($data['date1'])).' '.Yii::$app->general->getshift($data['from_shift']);
        $data['date2'] = date('Y-m-d', strtotime($data['date2'])).' '.Yii::$app->general->getshift($data['to_shift']);
        foreach ($data as $key=>$value){
            $cmd.= ' -a "@'.str_replace('p_', '', $key).':'.$value.'"';
        }
//        echo $cmd;die;
        exec($cmd,$out,$retval);
//        var_dump($out);die;
        if(!empty($out)){
            $this->output = $this->data['report_name'].'/'.$this->data['file_name'];
        }
    }

    /* Reports Configuration */

    private function getLabels($l) {
        $label = [
            'BmcCollection' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid,CattleType',
                'report_name' => 'rptBMCCollectionReport',
                'file_name' => 'bmc_collection',
                'scenario' => 'BmcCollection',
                'title' => 'Bmc Collection',
            ],
            'ActualBmcCollection' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,routeid',
                'report_name' => 'rptPPWiseActualBMCCollection',
                'file_name' => 'actual_bmc_collection',
                'scenario' => 'ActualBmcCollection',
                'title' => 'Actual Bmc Collection',
            ],
            'RmrdMilkCollection' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid,CattleType,MilkQualityType',
                'report_name' => 'rptRMRDMilkCollection',
                'file_name' => 'rmrd_milk_collection',
                'scenario' => 'RmrdMilkCollection',
                'title' => 'RMRD Milk Collection',
            ],
            'BmcSummaryReport' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid,CattleType,MilkQualityType',
                'report_name' => 'rptBMCSummaryReport',
                'file_name' => 'bmc_summary_report',
                'scenario' => 'BmcSummaryReport',
                'title' => 'Bmc Summary Report',
            ],
            'VariationMilkTypeDateWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCMilkType',
                'file_name' => 'variation_milk_type_date_wise',
                'scenario' => 'VariationMilkTypeDateWise',
                'title' => 'Variation Milk Type Date Wise',
            ],
            'VariationMilkTypeVillageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCVillageWiseMilkType',
                'file_name' => 'variation_milk_type_village_wise',
                'scenario' => 'VariationMilkTypeVillageWise',
                'title' => 'Variation Milk Type Village Wise',
            ],
            'VariationDateWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCC',
                'file_name' => 'variation_date_wise',
                'scenario' => 'VariationDateWise',
                'title' => 'Variation Date Wise',
            ],
            'VariationVillageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCVillageWise',
                'file_name' => 'variation_village_wise',
                'scenario' => 'VariationVillageWise',
                'title' => 'Variation Village Wise',
            ],
            'VariationPercentageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCFATSNF',
                'file_name' => 'variation_percentage_wise',
                'scenario' => 'VariationPercentageWise',
                'title' => 'Variation Percentage Wise',
            ],
            'DifferenceReport' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptDifferenceReportVLCCToMCCForBMC',
                'file_name' => 'difference_report',
                'scenario' => 'DifferenceReport',
                'title' => 'Difference Report',
            ],
            'DifferenceReportDateWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptDifferenceReportVLCCToMCC',
                'file_name' => 'difference_report_date_wise',
                'scenario' => 'DifferenceReportDateWise',
                'title' => 'Difference Report Date Wise',
            ],
            'DifferenceReportVillageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptDifferenceReportVLCCToMCCVillageWise',
                'file_name' => 'difference_report_village_wise',
                'scenario' => 'DifferenceReportVillageWise',
                'title' => 'Difference Report Village Wise',
            ],
        ];
        return $label[$l];
    }

}
