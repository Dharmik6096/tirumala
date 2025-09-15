<?php

$attribute = [
        ['attribute' => 'medicine_name', 'filter' => true],
];
$grid_option = [
    'id' => 'medicine-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
