<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [

    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
    ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        },
        'filter' => false],
    ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'filter' => false],
    ['attribute' => 'from_km'],
    ['attribute' => 'to_km'],
    ['attribute' => 'rate'],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->wef_date);
}],
];

$grid_option = [
    'id' => 'km-wise-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $class = (Yii::$app->general->validateVehiclePayment($model)) ? '' : 'disabled';
            $options = ['data-name' => $model->km_code, 'data-val' => $model->km_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/transporter/tbl-km-wise-rate/update', 'id' => $model->km_code], $options);
        },
//        'delete' => ['option' => 'rate,km_code,tbl-km-wise-rate/delete'],
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
