<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        },
        'filter' => false],
        ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'filter' => false],
        ['attribute' => 'from_qty'],
        ['attribute' => 'to_qty'],
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
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'qty-wise-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $class = (Yii::$app->general->validateVehiclePayment($model)) ? '' : 'disabled';
            $options = ['data-name' => $model->qty_code, 'data-val' => $model->qty_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/transporter/tbl-qty-wise-rate/update', 'id' => $model->qty_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
