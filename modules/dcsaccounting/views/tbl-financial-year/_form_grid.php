<?php

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;

$attribute = [
        ['attribute' => 'code', 'vAlign' => 'middle'],
        [
        'attribute' => 'starting_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->starting_date);
        }
    ],
        [
        'attribute' => 'ending_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->ending_date);
        }
    ],
];

$grid_option = [
    'id' => 'financial-year-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', Url::to(['tbl-financial-year/update', 'id' => $model->id]), ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit']);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>