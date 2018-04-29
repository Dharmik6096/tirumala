<?php

$attribute = [
    ['attribute' => 'milk_quality_type_code', 'value' => 'milk_quality_type_code'],
    ['attribute' => 'milk_quality_type_name', 'value' => 'milk_quality_type_name'],
    ['attribute' => 'local_name',],
];
$grid_option = [
    'id' => 'milk-quality-type-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'milk_quality_type_name,milk_quality_type_code,tbl-milk-quality-type/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>