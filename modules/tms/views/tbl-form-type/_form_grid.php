<?php

$attribute = [
    ['attribute' => 'union_code', 'filter' => true, 'visible' => false],
    ['attribute' => 'task_type_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->taskType, 'task_type');
    }],
    ['attribute' => 'form_name', 'filter' => true],
    ['attribute' => 'remarks', 'filter' => true],
];

$grid_option = [
    'id' => 'task-type-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
