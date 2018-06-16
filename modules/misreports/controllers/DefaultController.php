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
    private $data = [], $type = 'html', $output = '', $report = '', $dataProvider = '';

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
        }
        return $this->render('index', ['result' => $this->output, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
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

    public function actionSapReport() {
        $this->report = 'VmReportSap';
        if (Yii::$app->request->queryParams) {
            if (Yii::$app->request->queryParams['ReportsModel']['report_type'] == '1') {
                $this->report = 'WqReportSap';
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
        $controls = [];
        $param = explode(',', $this->data['param']);
        foreach ($param as $key => $value) {
            $value_array = explode(':', $value);
            $value = $value_array[0];
            if (isset($value_array[1]) && $value_array[1] == 'string') {
                $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                if (isset($value_array[2])) {
                    $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                    $model->{$value} .=' ' . $shift . '.000';
                }
            }
            $controls[$value] = $model->{$value};
        }
        $sp_name = $this->data['sp_name'];
        $output = \Yii::$app->general->getSpData($sp_name, $controls);
        $this->output = $output;
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
                'title' => 'BMC Shift Report',
            ],
            'BmcConsolidateReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,dcs_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_BMCConsolidateReport',
                'scenario' => '',
                'title' => 'BMC Consolidate Report',
            ],
            'BmcSummaryReport' => [
                'param' => 'union_code,plant_code,mcc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_BMCSummaryReport',
                'scenario' => '',
                'title' => 'BMC Summary Report',
            ],
            'SocietySummaryReport' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_SocietySummaryReport',
                'scenario' => '',
                'title' => 'Society Summary Report',
            ],
            'VmReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_VMSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP Data Export',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ')],
            ],
            'WqReportSap' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'scenario' => 'SapReport',
                'title' => 'SAP Data Export',
                'report_type' => [Yii::t('app', 'VM'), Yii::t('app', 'WQ')],
            ],
        ];
        return $label[$l];
    }

}
