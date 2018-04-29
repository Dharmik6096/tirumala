<?php
$attribute = [
    ['attribute' => 'state_code'],
    ['attribute' => 'state_name'],
    ['attribute' => 'local_name'],
];
$grid_option = [
    'id' => 'state-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'state_name,state_code,tbl-states/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
