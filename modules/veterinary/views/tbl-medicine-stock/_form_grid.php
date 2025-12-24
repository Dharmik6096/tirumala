<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'module_code', 'label' => Yii::t('app', 'User Code'), 'filter' => true],
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'Name'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'medicine_id', 'label' => Yii::t('app', 'Medicine Name'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->medicineMasterCode, 'medicine_name');
        }, 'filter' => false],
    ['attribute' => 'stock', 'filter' => true],
    ['attribute' => 'batch_no', 'filter' => true],
    [
        'attribute' => 'expire_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->expire_date);
        }
    ],
    ['attribute' => 'rate', 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'medicine-stock-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
