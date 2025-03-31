<?php

$attribute = [
        ['attribute' => 'compartment_no', 'filter' => false],
        ['attribute' => 'capacity', 'filter' => false],
];

$grid_option = [
    'id' => 'plant-dock-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'vehicle_compartment_detail_code,vehicle_compartment_detail_code,tbl-vehicle-master/delete-compartment'],
    ]
];
Yii::$app->grid->bind($detaildataProvider, $detailsearchModel, $grid_option);
?>