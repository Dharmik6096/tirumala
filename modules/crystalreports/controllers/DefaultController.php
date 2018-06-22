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
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
    
    public function actionGprsDataReconciliation() {
        $this->report = 'GprsDataReconciliation';
        return $this->actionIndex();
    }

    /* Call Crystal Report */

    private function LoadReport($model) {
        $data = Yii::$app->request->post()['ReportsModel'];
        $file_name = $data['file_name'];
        $type = 'htm';
        $out_type = Yii::$app->request->post('submit');
        if ($out_type != 'html') {
            $type = $out_type;
        }
        $project_path = Yii::$app->params['projectPath'];
        $convert_crystal_report_path = Yii::$app->params['convert_crystal_report_path'];
        $crystal_report_path = Yii::$app->params['crystal_report_path'];
        $rptHtmlPath = Yii::$app->params['rptHtmlPath'];
        $cmd = "CrystalReportsNinja -U sa -P !!EiPl@2017 -S 182.73.178.90,14033 -D TIRUMALA";
        $cmd .= " -F " . $project_path . $crystal_report_path . '\\' . $this->data['report_name'] . ".rpt -O " . $project_path . $rptHtmlPath;
        $cmd .= "\\" . $file_name . '.' . $out_type . ' -E ' . $type;
        $data['date1'] = date('Y-m-d', strtotime($data['date1'])) . ' ' . Yii::$app->general->getshift($data['from_shift']);
        $data['date2'] = date('Y-m-d', strtotime($data['date2'])) . ' ' . Yii::$app->general->getshift($data['to_shift']);
        foreach ($data as $key => $value) {
            $cmd.= ' -a "@' . str_replace('p_', '', $key) . ':' . $value . '"';
        }
        if(!isset($data['vlccid'])){
            $cmd.= ' -a "@' . 'vlccid' . ':0"';
        }
        if(!isset($data['routeid'])){
            $cmd.= ' -a "@' . 'routeid' . ':0"';
        }
        chdir(Yii::$app->params['convert_crystal_report_path']);
        exec($cmd, $out, $retval);
        if (!empty($out) && isset($out[2]) && $out[2] == 'Completed') {
            if ($out_type != 'html') {
                $file = $project_path . $rptHtmlPath . '/' . $file_name . '.' . $out_type;
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Description: File Transfer');
                header("Content-Disposition: attachment; filename=" . $file_name . '.' . $out_type);
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Description: File Transfer");
                header("Content-Length: " . filesize($file));
                $fp = fopen($file, "r");
                while (!feof($fp)) {
                    echo fread($fp, 65536);
                    flush(); // this is essential for large downloads
                }
                fclose($fp);
            }
            $this->output = $this->data['report_name'] . '/' . $file_name;
        }
    }

    /* Reports Configuration */

    private function getLabels($l) {
        $label = [
            'BmcCollection' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid,CattleType',
                'report_name' => 'rptBMCCollectionReport',
                'file_name' => 'bmc_collection',
                'scenario' => 'BmcCollection',
                'title' => '312 - Bmc Collection',
            ],
            'ActualBmcCollection' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,MCCId,bmcid,routeid',
                'report_name' => 'rptPPWiseActualBMCCollection',
                'file_name' => 'actual_bmc_collection',
                'scenario' => 'ActualBmcCollection',
                'title' => '301 - Actual Bmc Collection',
            ],
            'RmrdMilkCollection' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid,CattleType,MilkQualityType,VLCTotal',
                'report_name' => 'rptRMRDMilkCollection',
                'file_name' => 'rmrd_milk_collection',
                'scenario' => 'RmrdMilkCollection',
                'title' => '302 - RMRD Milk Collection',
            ],
            'BmcSummaryReport' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid,CattleType,MilkQualityType',
                'report_name' => 'rptBMCSummaryReport',
                'file_name' => 'bmc_summary_report',
                'scenario' => 'BmcSummaryReport',
                'title' => '303 - Bmc Summary Report',
            ],
            'VariationMilkTypeDateWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCMilkType',
                'file_name' => 'variation_milk_type_date_wise',
                'scenario' => 'VariationMilkTypeDateWise',
                'title' => '304 - Milk Type Variation Date Wise',
            ],
            'VariationMilkTypeVillageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCVillageWiseMilkType',
                'file_name' => 'variation_milk_type_village_wise',
                'scenario' => 'VariationMilkTypeVillageWise',
                'title' => '305 - Milk Type Variation Village Wise',
            ],
            'VariationDateWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCC',
                'file_name' => 'variation_date_wise',
                'scenario' => 'VariationDateWise',
                'title' => '306 - Date Wise Variation',
            ],
            'VariationVillageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCVillageWise',
                'file_name' => 'variation_village_wise',
                'scenario' => 'VariationVillageWise',
                'title' => '307 - Village Wise Variation',
            ],
            'VariationPercentageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptVariationReportVLCCToMCCFATSNF',
                'file_name' => 'variation_percentage_wise',
                'scenario' => 'VariationPercentageWise',
                'title' => '308 - Percentage Wise Variation',
            ],
            'DifferenceReport' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptDifferenceReportVLCCToMCCForBMC',
                'file_name' => 'difference_report',
                'scenario' => 'DifferenceReport',
                'title' => '309 - Difference Report',
            ],
            'DifferenceReportDateWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptDifferenceReportVLCCToMCC',
                'file_name' => 'difference_report_date_wise',
                'scenario' => 'DifferenceReportDateWise',
                'title' => '310 - Date Wise Difference Report',
            ],
            'DifferenceReportVillageWise' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid,vlccid,routeid',
                'report_name' => 'rptDifferenceReportVLCCToMCCVillageWise',
                'file_name' => 'difference_report_village_wise',
                'scenario' => 'DifferenceReportVillageWise',
                'title' => '311 - Village Wise Difference Report',
            ],
            'GprsDataReconciliation' => [
                'param' => 'date1:string:from_shift,date2:string:to_shift,union_code,plant_code,mccid,bmcid',
                'report_name' => 'rptDPUGPRSDataReconciliation',
                'file_name' => 'gprs_data_reconciliation',
                'scenario' => 'GprsDataReconciliation',
                'title' => '313 - DPU-GPRS Data Reconciliation',
            ],
        ];
        return $label[$l];
    }

}
