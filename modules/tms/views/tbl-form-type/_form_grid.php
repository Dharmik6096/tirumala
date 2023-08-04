<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'filter' => false, 'visible' => false],
    ['attribute' => 'task_type_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->taskType, 'task_type');
    }],
    ['attribute' => 'form_name', 'filter' => true],
    ['attribute' => 'remarks', 'filter' => true],
];

$grid_option = [
    'id' => 'form-type-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
