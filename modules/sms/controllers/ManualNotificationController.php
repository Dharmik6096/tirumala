<?php

namespace app\modules\sms\controllers;

use Yii;
use yii\web\Controller;
use app\modules\sms\models\ManualNotification;
use yii\data\ArrayDataProvider;

/**
 * ManualNotificationController implements the CRUD actions for ManualNotification model.
 */
class ManualNotificationController extends \app\controllers\ChildController {

    /**
     * Renders the index view for the module
     * @return string
     */
    private $data = [], $output = '', $report = '', $dataProvider = '';

    public function actionIndex() {
        $model = new ManualNotification();
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
            }
        }

        return $this->render('index', ['result' => $this->output, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionRmrdCollectionVsp() {
        $this->report = 'RmrdCollectionVsp';
        return $this->actionIndex();
    }

    private function LoadReport($model) {
        if (empty($model->union_code)) {
            $model->union_code = !empty(Yii::$app->session->get('Unions')) ? ',' . Yii::$app->session->get('Unions') . ',' : 0;
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
            if (isset($value_array[1]) && $value_array[1] == 'dateshift') {
                $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                if (isset($value_array[2])) {
                    $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                    $model->{$value} .= ' ' . $shift . '.000';
                }
            }
            $controls[$value] = $model->{$value};
        }
        $sp_name = $this->data['sp_name'];

        if (!empty(Yii::$app->request->post('selection'))) {
            $checked_key = Yii::$app->request->post('selection');
            $controls['created_by'] = Yii::$app->session->get('UserCode');
            $controls['checked_key'] = ',' . implode(',', $checked_key) . ',';
            Yii::$app->general->getSpData($this->data['sp_process'], $controls, true);
            $this->output = Yii::t('app', 'Alert saved successfully.');
        } else {
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

    /* SMS Configuration */

    private function getLabels($l) {
        $label = [
            'RmrdCollectionVsp' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,date:dateshift:shift,data_type:static:data_type_filter,report_type',
                'sp_name' => 'sp_alert_eipl_manual_rmrd_collection_vsp_list',
                'sp_process' => 'sp_alert_eipl_manual_rmrd_collection_vsp_process',
                'scenario' => 'RmrdCollectionVsp',
                'title' => 'RMRD Collection (VSP)',
                'report_type' => ['SMS' => 'SMS'],
            ],
        ];
        return $label[$l];
    }

}
