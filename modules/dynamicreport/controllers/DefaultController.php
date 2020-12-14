<?php

namespace app\modules\dynamicreport\controllers;

use yii\web\Controller;
use app\modules\dynamicreport\models\DynamicForm;
use app\modules\dynamicreport\Dynamicreport;
use Yii;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `dynamicreport` module
 */
class DefaultController extends \app\controllers\ChildController {

    public $freeAccessActions = ['generate-form'];
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
//        if (isset($model->dcs_code) && empty($model->dcs_code)) {
//            $model->dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
//        }
        $controls = [];
        $param = explode(',', $this->data['sp_param']);
        foreach ($param as $key => $value) {
            $value_array = explode(':', $value);
            $value = $value_array[0];
            if (isset($value_array[1]) && $value_array[1] == 'date') {
                $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                if (isset($value_array[2])) {
                    $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                    $model->{$value} .=' ' . $shift . '.000';
                }
            }
            $controls[$value] = empty($model->{$value}) ? '0' : $model->{$value};
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
    }

}
