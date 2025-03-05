<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'ref_code');
        }, 'visible' => true, 'filter' => FALSE],
    ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
    ['attribute' => 'chamber_no'],
    ['attribute' => 'trip_code', 'filter' => false],
    ['attribute' => 'arrival_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->arrival_datetime);
        }, 'filter' => false],
    ['attribute' => 'status', 'filter' => false],
    ['attribute' => 'status_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->status_datetime);
        }, 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
];

$grid_option = [
    'id' => 'milk-vehicle-entry-qlty-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#']);
?>