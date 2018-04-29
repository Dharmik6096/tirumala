<?php
$attribute = [
    ['attribute' => 'capacity_code', 'value' => 'capacity_code'],
    ['attribute' => 'value', 'value' => 'value'],   
    ];
$grid_option = [
    'id' => 'capacity-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'value,capacity_code,tbl-capacity/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
