<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'dcs_code'],
    ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => true],
    ['attribute' => 'dsrn'],
    ['attribute' => 'dtyp'],
    ['attribute' => 'dlock'],
    ['attribute' => 'dscch'],
    ['attribute' => 'dsbch'],
    ['attribute' => 'dsmch'],
    ['attribute' => 'dsfd'],
    ['attribute' => 'dssd'],
    ['attribute' => 'dssdm'],
    ['attribute' => 'dscc'],
    ['attribute' => 'dsai'],
    ['attribute' => 'dsas'],
    ['attribute' => 'dsmf'],
    ['attribute' => 'dsms'],
    ['attribute' => 'dsht'],
    ['attribute' => 'dsct'],
    ['attribute' => 'docfo'],
    ['attribute' => 'docso'],
    ['attribute' => 'docwo'],
    ['attribute' => 'docdo'],
    ['attribute' => 'docpo'],
    ['attribute' => 'doclo'],
    ['attribute' => 'dobfo'],
    ['attribute' => 'dobso'],
    ['attribute' => 'dobwo'],
    ['attribute' => 'dobdo'],
    ['attribute' => 'dobpo'],
    ['attribute' => 'doblo'],
    ['attribute' => 'domfo'],
    ['attribute' => 'domso'],
    ['attribute' => 'domwo'],
    ['attribute' => 'domdo'],
    ['attribute' => 'dompo'],
    ['attribute' => 'domlo'],
    ['attribute' => 'dpp1'],
    ['attribute' => 'dpp2'],
    ['attribute' => 'dpp3'],
    [
        'attribute' => 'download_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->download_datetime);
        }],
    [
        'attribute' => 'processed_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->processed_datetime);
        }],
];
$grid_option = [
    'id' => 'fs-data-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>