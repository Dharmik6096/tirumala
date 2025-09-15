<?php

$attribute = [
        ['attribute' => 'breed_name', 'filter' => true],
];
$grid_option = [
    'id' => 'Breed-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
