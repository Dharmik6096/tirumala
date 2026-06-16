<?php

use yii\helpers\Html;
use kartik\grid\GridView;

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
    ['attribute' => 'message', 'vAlign' => 'middle'],
    ['attribute' => 'message_local', 'vAlign' => 'middle'],
    ['attribute' => 'from_date', 'filter' => false,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    ['attribute' => 'from_shift', 'value' => function($model) {
        if ($model->from_shift == 1) return Yii::t('app', 'Morning');
        if ($model->from_shift == 2) return Yii::t('app', 'Evening');
        return '';
    }, 'filter' => false],
    ['attribute' => 'to_date', 'filter' => false, 'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }
    ],
    ['attribute' => 'to_shift', 'value' => function($model) {
        if ($model->to_shift == 1) return Yii::t('app', 'Morning');
        if ($model->to_shift == 2) return Yii::t('app', 'Evening');
        return '';
    }, 'filter' => false],
];

$grid_option = [
    'id' => 'message-grid',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
