<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'name', 'vAlign' => 'middle'],
    ['attribute' => 'name_local','vAlign' => 'middle'],
    ['attribute' => 'ledger_account','vAlign' => 'middle'],
    ['attribute' => 'qty', 'vAlign' => 'middle', 'hAlign' => 'right'],
    ['attribute' => 'amount', 'vAlign' => 'middle', 'hAlign' => 'right'],
    ['attribute' => 'purchase_date', 'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ], 'value' => function ($model) {
            return Yii::$app->controls->view_date($model->purchase_date);
        }
    ],
    ['attribute' => 'transaction_date', 'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ], 'value' => function ($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }
    ]
];

$grid_option = [
    'id' => 'dead-stock-grid',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
