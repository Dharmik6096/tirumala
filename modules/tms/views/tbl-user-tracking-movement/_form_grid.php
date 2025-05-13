<?php

$attribute = [
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'User Code'), 'filter' => false, 'enableSorting' => false],
    ['attribute' => 'user_name', 'label' => Yii::t('app', 'Name'), 'filter' => false, 'enableSorting' => false],
    ['attribute' => 'login_type', 'filter' => false, 'enableSorting' => false],
    ['attribute' => 'mobile_no', 'filter' => false, 'enableSorting' => false],
    [
        'attribute' => 'lat_long',
        'format' => 'raw',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->controls->openInGoogleMaps($model['lat_long'], $model['lat_long']);
        },
        'enableSorting' => false
    ],
];
$grid_option = [
    'id' => 'user-tracking-movement-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);