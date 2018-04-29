<?php
$attribute = [
    ['attribute' => 'vehicle_type_code', 'value' => 'vehicle_type_code'],
    ['attribute' => 'vehicle_type_name', 'value' => 'vehicle_type_name'],
    ['attribute' => 'local_name', 'filter' => false],
    ];
$grid_option = [
    'id' => 'vehicle-type-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'vehicle_type_name,vehicle_type_code,tbl-vehicle-type/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>