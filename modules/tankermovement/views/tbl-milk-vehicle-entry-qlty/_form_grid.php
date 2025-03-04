<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'label' => (Yii::t('app', 'Plant Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'plant_code');
        }, 'filter' => false],
    ['attribute' => 'plant_code', 'label' => (Yii::t('app', 'Plant')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'chamber_no'],
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
        'view' => false,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#']);
?>