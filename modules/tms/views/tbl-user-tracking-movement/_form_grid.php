<?php

$attribute = [
    ['attribute' => 'user_code', 'filter' => false],
    ['attribute' => 'user_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false],
    [
        'attribute' => 'lat_long',
        'format' => 'raw',
        'value' => function ($model) {
            return Yii::$app->controls->openInGoogleMaps($model->lat_long, $model->lat_long);
        }
    ],
];
$grid_option = [
    'id' => 'user-tracking-movement-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
