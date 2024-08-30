<?php

$attribute = [
    ['attribute' => 'mobile_no', 'filter' => FALSE],
    ['attribute' => 'master_name', 'filter' => FALSE],
    ['attribute' => 'master_type', 'filter' => FALSE],
    ['attribute' => 'module_type', 'filter' => FALSE],
    ['attribute' => 'module_name', 'filter' => FALSE],
    ['attribute' => 'module_code', 'filter' => FALSE],
    ['attribute' => 'name', 'filter' => FALSE],
    ['attribute' => 'login_type', 'filter' => FALSE],
    ['attribute' => 'app_login_id', 'filter' => FALSE],
    ['attribute' => 'is_default', 'filter' => FALSE],
];

$grid_option = [
    'id' => 'contact-list',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
