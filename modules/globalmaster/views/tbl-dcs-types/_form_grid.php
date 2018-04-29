<?php
$attribute = [
    ['attribute' => 'dcs_type_code',],
    ['attribute' => 'dcs_type_name',],
    ['attribute' => 'local_name',],
];
$grid_option = [
    'id' => 'dcs-type-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'dcs_type_name,dcs_type_code,tbl-dcs-types/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>