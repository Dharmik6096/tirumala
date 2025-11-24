<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'case_type_id', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->caseType, 'case_type_name');
        }, 'filter' => true, 'visible' => true],
    ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => true, 'filter' => true],
    [
        'attribute' => 'wef_date',
        'visible' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }
    ],
];

$grid_option = [
    'id' => 'case-type-fees-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
