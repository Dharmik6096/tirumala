<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'mcc_bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
        }, 'filter' => false,],
    [
        'attribute' => 'transaction_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }, 'filter' => false],
    ['attribute' => 'no_installment', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];
$grid_option = [
    'id' => 'mcc-bill-head-detail-list-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>