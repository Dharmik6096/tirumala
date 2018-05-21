<?php
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    //'product_sale_code',
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter'=>false],
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name','filter'=>false],
    ['attribute' => 'member_code', 'value' => 'memberCode.member_name'],
    //'member_code',
    //'sale_date_time',
    [
        'attribute' => 'sale_date_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->sale_date_time);
}],
        ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'other_amount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'discount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'paid_amount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'amount_due', 'format' => Yii::$app->general->CurrencyFormat(),],
        // 'is_installment',
        // 'no_of_installment',
        // 'created_at',
        // 'created_by',
        // 'updated_at',
        // 'updated_by',
];

$grid_option = [
    'id' => 'product-sale-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'installment' => function ($url, $model) {       
        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Installments'];
        return GhostHtml::a('<i class="fa fa-money"></i>', ['/payment/tbl-product-sale/sale-installments', 'id' => $model->product_sale_code], $options);
        },
        
//        'delete' => ['option' => ''],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>