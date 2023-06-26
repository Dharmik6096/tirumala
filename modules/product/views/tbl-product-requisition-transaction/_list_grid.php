<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'quantity', 'vAlign' => 'middle'],
        ['attribute' => 'approved_quantity', 'vAlign' => 'middle'],
        ['attribute' => 'provisional_rate', 'vAlign' => 'middle'],
        ['attribute' => 'provisional_amount', 'vAlign' => 'middle'],
        [
        'attribute' => 'uom',
        'value' => function($model) {
            return $model->getUom($model->product_code);
        },
    ],
        [
        'attribute' => 'requisition_on_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'requisition_on_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->requisition_on_date);
        }],
        [
        'attribute' => 'is_approved',
        'vAlign' => 'middle',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_type', $searchModel, 'is_approved'),
        'value' => function($model) {
            return ($model->is_approved == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }
    ],
        ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_status', $searchModel, 'status'),],
];

$grid_option = [
    'id' => 'product-requisition-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'detailView' => function ($url, $model) {
            $class = '';
            $options = ['class' => $class, 'data-val' => $model->requisition_transaction_code, 'title' => Yii::t('app', 'View'), 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/product/tbl-product-requisition-transaction/view', 'id' => $model->requisition_transaction_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>