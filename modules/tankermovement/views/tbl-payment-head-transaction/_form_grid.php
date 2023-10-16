<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false, 'visible' => false],
    ['attribute' => 'payment_type', 'value' => function($model) {
            return isset($model->payment_type) ? Yii::$app->dropdown->getRecords('tanker_rate_for')['data'][$model->payment_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('tanker_rate_for', $searchModel, 'payment_type'),],
    ['attribute' => 'payment_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->paymentHeadType, 'payment_head_name');
        }, 'filter' => true],
    ['attribute' => 'applicable_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->partyName, 'party_name');
        }, 'filter' => true],
    ['attribute' => 'applicable_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->applicable_date);
        }],
    ['attribute' => 'amount', 'visible' => TRUE, 'filter' => false],
    ['attribute' => 'remarks', 'visible' => FALSE, 'filter' => false],
];

$grid_option = [
    'id' => 'payment-type-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => true
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
