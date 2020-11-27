<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
//    ['attribute' => 'billing_type', 'value' => function($model) {
//            return isset($model->billing_type) ? Yii::$app->dropdown->getRecords('transporter_type')['data'][$model->billing_type] : '';
//        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('transporter_type', $searchModel, 'billing_type'),],
    ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        },
        'filter' => false],
    ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        },
    ],
    ['attribute' => 'transporter_payment_head_code', 'value' => 'transporterPaymentHead.transporter_payment_head', 'filter' => false],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->wef_date);
}],
    ['attribute' => 'amount'],
    ['attribute' => 'remarks', 'visible' => FALSE, 'filter' => false],
];

$grid_option = [
    'id' => 'biling-type-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true
//        'delete' => ['option' => 'rate,fuel_rate_code,tbl-fuel-rate-master/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
