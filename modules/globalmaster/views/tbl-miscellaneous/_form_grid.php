<?php

$attribute = [
    ['attribute' => 'miscellaneous_code',],
    ['attribute' => 'miscellaneous_name',],
    ['attribute' => 'local_name',],
];
$grid_option = [
    'id' => 'miscellaneous-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'miscellaneous_name,miscellaneous_code,tbl-miscellaneous/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>