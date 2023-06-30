<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;

$client_code = \Yii::$app->session->get('eiplCode');

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Code')), 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'filter' => false,],
    ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Name')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'vehicle_code', 'value' => function($model) use ($client_code) {
            if ($client_code == 'UMANG') {
                return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
            } else {
                return $model->vehicle_code;
            }
        }, 'filter' => false],
    ['attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
    ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'filter' => false],
    ['attribute' => 'actual_arrival_time'],
    ['attribute' => 'define_arrival_time'],
    ['attribute' => 'grace_time'],
    ['attribute' => 'late_by_time'],
    ['attribute' => 'no_of_filled_can'],
    ['attribute' => 'no_of_empty_can'],
    ['attribute' => 'status', 'value' => function($model) {
            return $model->status == '1' ? 'Gate Out' : 'Gate In';
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'status_time',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->status_time);
        }, 'filter' => false, 'visible' => false],
];


$grid_option = [
    'id' => 'gate-entry-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'print-gate-pass' => function ($url, $model) {
            $options = ['target' => '_blank', 'title' => Yii::t('app', 'Print Gate Pass'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Print Gate Pass')];
            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/transporter/tbl-gate-entry/print-gate-pass', 'id' => $model->gate_entry_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
