<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        },
        'filter' => false],
    ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        },
        'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return isset($model->routeCode) ? $model->routeCode->route_name : '';
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
        }, 'vAlign' => 'middle', 'filter' => FALSE],
    ['attribute' => 'define_arrival_time'],
    ['attribute' => 'grace_time'],
    ['attribute' => 'actual_arrival_time'],
    ['attribute' => 'late_by_time'],
    ['attribute' => 'vts_arrival_time'],
    ['attribute' => 'vts_late_by_time'],
//    ['attribute' => 'responsibility'],
];

$grid_option = [
    'id' => 'vehicle-km-info-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
