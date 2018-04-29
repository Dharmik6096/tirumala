<?php
$attribute = [
    ['attribute' => 'mcc_plant_code','filter'=>false],
    ['attribute' => 'name','filter'=>false],
    ['attribute' => 'capacity', 'value' => 'capacity0.value','filter'=>false],
];

$grid_option = [
    'id' => 'mcc-plant-list',
    'attributes' => $attribute,
    'active_column' => false,
    
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>