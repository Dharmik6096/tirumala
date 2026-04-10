<?php

namespace app\modules\dynamicreport\controllers;

use yii\web\Controller;
use app\modules\dynamicreport\models\DynamicForm;
use app\modules\dynamicreport\Dynamicreport;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\modules\usermanagement\models\User;
use app\modules\misreports\controllers\ReportsController;

/**
 * Default controller for the `dynamicreport` module
 */
class DefaultController extends \app\controllers\ChildController {

    public $freeAccessActions = ['generate-form', 'get-sp-data-drop-list'];
    private $data = [], $output = '', $dataProvider = '';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $model = new DynamicForm(['report_code']);
        $model->addRule('report_code', 'safe');
        if ($model->load(Yii::$app->request->queryParams)) {
            $data = Dynamicreport::getLabels($model->report_code);
            if (!empty($data)) {
                $control = \yii\helpers\ArrayHelper::map($data['controls'], 'control_code', 'control_name');
                $control = array_values($control);
                $control[] = 'report_code';
                $control[] = 'output_type';
                $model = new DynamicForm($control);
                foreach ($data['report_rule'] as $rule) {
                    $model->addRule(explode(',', $rule->attribute), $rule->rule);
                }
                $model->addRule($control, 'safe');
                if ($model->load(Yii::$app->request->queryParams) && $model->validate()) {
                    $this->data = $data;
                    $this->LoadReport($model);
                    if (empty($this->output)) {
                        $this->output = Yii::t('app', 'No Data Available.');
                    }
                }
            }
        }
        return $this->render('index', ['result' => $this->output, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionGenerateForm() {
        $model = new DynamicForm(['report_code']);
        $data = [];
        if (!empty($_POST['id'])) {
            $data = Dynamicreport::getLabels($_POST['id']);
            if (!empty($data)) {
                $control = \yii\helpers\ArrayHelper::map($data['controls'], 'control_code', 'control_name');
                $control = array_values($control);
                $control[] = 'report_code';
                $control[] = 'output_type';
                $model = new DynamicForm($control);
                foreach ($data['report_rule'] as $rule) {
                    $model->addRule(explode(',', $rule->attribute), $rule->rule);
                }
                $model->addRule($control, 'safe');
            }
            $model->report_code = $_POST['id'];
        }
        return $this->renderAjax('@app/modules/dynamicreport/views/default/_form', ['model' => $model, 'data' => $data]);
    }

    private function LoadReport($model) {
        $config = is_object($this->data['config']) ? (array) $this->data['config'] : $this->data['config'];
        if (!empty($config['bkg_export']) && empty($this->data['output_type']) && !User::canRoute('misreports/reports/dynamic-live-report-generation')) {
            $this->data['output_type'] = $model->output_type = 'BACKGROUND';
        }
        if (isset($model->union_code) && empty($model->union_code)) {
            $model->union_code = !empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : 0;
        }
        if (isset($model->plant_code) && empty($model->plant_code)) {
            $model->plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
        }
        if (isset($model->mcc_code) && empty($model->mcc_code)) {
            $model->mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
        }
        if (isset($model->bmc_code) && empty($model->bmc_code)) {
            $model->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
        }
        if (isset($model->dcs_code) && empty($model->dcs_code)) {
            $model->dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
        }
        $controls = [];
        $param = explode(',', $this->data['sp_param']);
        foreach ($param as $key => $value) {
            $value_array = explode(':', $value);
            $value = $value_array[0];
            if (isset($value_array[1]) && $value_array[1] == 'date') {
                $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                if (isset($value_array[2])) {
                    $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                    $model->{$value} .= ' ' . $shift . '.000';
                }
            }
            $controls[$value] = empty($model->{$value}) ? '0' : $model->{$value};
        }
        $sp_name = $this->data['sp_name'];
        if ($model->output_type != 'BACKGROUND') {
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
        } else {
            if ($this->RegisterReportRequest('mis', $this->data, $controls)) {
                $this->output = 'Your Request has been submitted for Report Data.<br/>You can download the file from My Report Request screen after some time.';
            } else {
                $this->output = 'Error While Request Submit.';
            }
        }
        if (isset($model->output_type) && $model->output_type == 'DOWNLOAD') {
            if (isset($config['excel_readonly']) && $config['excel_readonly']) {
                ReportsController::downloadDataReadonly($this->output, $this->data);
            } else {
                $this->downloadData();
            }
        }
    }

    public function actionGetSpDataDropList() {
        $out = [];
        $sp_param = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents)) {
                $sp_name = $parents[0];
                $param = explode(',', $parents[1]);
                foreach ($param as $key => $value) {
                    if (strstr($value, 'session_')) {
                        $paramVal = $this->getSessionName($value);
                        $val = !empty($paramVal) ? Yii::$app->session->get($paramVal) : '';
                        $sp_param[] = !empty($val) ? ',' . $val . ',' : 0;
                    } else {
                        $sp_param[] = !empty($parents[$key + 2]) ? $parents[$key + 2] : '';
                    }
                }
                $data = \Yii::$app->general->getSpData($sp_name, $sp_param);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['id'], 'name' => $val['name']);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    private function getSessionName($var) {
        $val = '';
        switch ($var) {
            case 'session_union':
                $val = 'Unions';
                break;
            case 'session_plant':
                $val = 'Plant';
                break;
            case 'session_mcc':
                $val = 'MCC';
                break;
            case 'session_bmc':
                $val = 'BMC';
                break;
            case 'session_dcs':
                $val = 'Dcs';
                break;
            default:
                $val = '';
                break;
        }
        return $val;
    }

    public function downloadData() {
        $extention = !empty($this->data['extention']) ? $this->data['extention'] : 'xls';
        $header = [
            'mime' => 'application/ms-excel',
            'extension' => $extention,
            'writer' => IOFactory::WRITER_XLSX,
        ];

        $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $Output = $this->output;
        $schema_insert = '';
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($labelArray as $a) {
            echo "<td>" . $a . "</td>";
        }
        echo "</tr>";
        foreach ($Output as $row) {
            echo "<tr>";
            foreach ($labelArray as $a) {
                $dispData = '';
                if (isset($row[$a]) && $row[$a] != '' && $row[$a] != null) {
                    $dispData = $row[$a];
                }
                $value = $dispData;
//                if (!empty($this->data['to_decrypt']) && in_array($a, $this->data['to_decrypt'])) {
                $value = !empty($dispData) ? (Yii::$app->general->decryptData($dispData) !== FALSE ? Yii::$app->general->decryptData($dispData) : $dispData) : (isset($dispData) && $dispData == 0 && $dispData != '' ? 0 : '');
//                }
                if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
                    echo "<td>" . $value . "</td>";
                } else {
                    echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        exit();
    }

    public function actionDynamicLiveReportGeneration() {
        
    }

}
