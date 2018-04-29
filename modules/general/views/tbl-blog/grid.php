<?php
use yii\helpers\Html;
?>

<?php

$attribute = [
//    ['attribute' => 'id'],
    ['attribute' => 'title'],
    ['attribute' => 'description'],
    ['attribute' => 'user_id'],
    ['attribute' => 'created_at']
];

$grid_option = [
    'id' => 'blog-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'view' => true,
//        'delete' => ['option' => ''],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>