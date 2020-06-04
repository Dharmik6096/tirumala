<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'vAlign' => 'middle'],
        [
        'attribute' => 'uom',
        'value' => function($model) {
            return $model->getUom($model->product_code);
        },
    ],
        ['attribute' => 'rate'],
        ['attribute' => 'dispatch_qty'],
        ['attribute' => 'discount_amount'],
        ['attribute' => 'amount'],
        [
        'attribute' => 'dispatch_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'requisition_on_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->dispatch_date);
        }],
];

$grid_option = [
    'id' => 'product-dispatch-transaction-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            if (in_array($model->status, ['Under Dispatch', 'Dispatched'])) {
                $options = ['title' => Yii::t('app', 'Edit Dispatch Transaction'), 'target' => '_blank'];
                return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/product/tbl-product-dispatch-transaction/update', 'id' => $model->dispatch_transaction_code], $options);
            }
        },
        'detailView' => function ($url, $model) {
            $class = '';
            $options = ['class' => $class, 'data-val' => $model->dispatch_transaction_code, 'title' => Yii::t('app', 'View'), 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/product/tbl-product-dispatch-transaction/view', 'id' => $model->dispatch_transaction_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>