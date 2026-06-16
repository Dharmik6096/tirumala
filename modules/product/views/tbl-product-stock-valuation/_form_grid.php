<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'product_name','vAlign' => 'middle'],
    ['attribute' => 'stock', 'vAlign' => 'middle', 'hAlign' => 'right'],
    ['attribute' => 'valuation', 'vAlign' => 'middle', 'hAlign' => 'right'],
    ['attribute' => 'unit', 'vAlign' => 'middle'],
    ['attribute' => 'generated_at', 'filter' => false,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ], 'value' => function ($model) {
            return Yii::$app->controls->view_date($model->generated_at);
        }
    ]
];

$grid_option = [
    'id' => 'product-stock-valuation-grid',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
