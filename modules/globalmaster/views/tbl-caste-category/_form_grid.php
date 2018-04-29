<?php

$attribute = [
    ['attribute' => 'caste_category_code', 'value' => 'caste_category_code'],
    ['attribute' => 'caste_category_name', 'value' => 'caste_category_name'],
    ['attribute' => 'local_name'],
];
$grid_option = [
    'id' => 'caste-category-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'caste_category_name,caste_category_code,tbl-caste-category/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
