<?php

$attribute = [
    ['attribute' => 'unit_code', 'value' => 'unit_code'],
    ['attribute' => 'unit_name', 'value' => 'unit_name'],
    ['attribute' => 'local_name',],
    ['attribute' => 'short_name', 'value' => 'short_name'],
    ['attribute' => 'local_short_name'],
];
$grid_option = [
    'id' => 'units-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'unit_name,unit_code,tbl-units/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>