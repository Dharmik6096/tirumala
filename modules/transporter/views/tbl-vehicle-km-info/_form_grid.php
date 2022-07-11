<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE],
        ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        },
        'filter' => false],
        ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'filter' => false],
        ['attribute' => 'route_code', 'value' => function($model) {
            return isset($model->routeCode) ? $model->routeCode->route_name : '';
        }, 'filter' => false],
        ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'shift_code', 'filter' => false, 'value' => 'shiftCode.shift'],
        ['attribute' => 'morning_arrival_time'],
        ['attribute' => 'morning_grace_time'],
        ['attribute' => 'evening_arrival_time'],
        ['attribute' => 'evening_grace_time'],
        ['attribute' => 'morning_kms'],
        ['attribute' => 'evening_kms'],
        ['attribute' => 'extra_kms'],
        ['attribute' => 'total_kms'],
];

$grid_option = [
    'id' => 'vehicle-km-info-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $class = (Yii::$app->general->validateVehiclePayment($model)) ? '' : 'disabled';
            $options = ['data-name' => $model->km_info_code, 'data-val' => $model->km_info_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/transporter/tbl-vehicle-km-info/update', 'id' => $model->km_info_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
