<?php

$attribute = [
    ['attribute' => 'project_name'],
    ['attribute' => 'description', 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'project-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'project_name,project_code,tbl-project/delete,projectDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>