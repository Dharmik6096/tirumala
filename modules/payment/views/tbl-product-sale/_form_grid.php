<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    //'product_sale_code',
//    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter'=>false],
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'customer_type', 'value' => function($model) {
            return isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') ) : '';
        }, 'vAlign' => 'middle'],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
        ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, true) : '';
        }, 'vAlign' => 'middle'],
        ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true) : '';
        }, 'vAlign' => 'middle'],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '';
        }, 'vAlign' => 'middle'],
//        ['attribute' => 'member_code', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
//        }],
    //'member_code',
    //'invoice_date',
    [
        'attribute' => 'invoice_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->invoice_date);
        }],
        ['attribute' => 'payment_mode', 'value' => function($model) {
            return isset($model->payment_mode) ? Yii::$app->dropdown->getRecords('payment_mode')['data'][$model->payment_mode] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('payment_mode', $searchModel, 'payment_mode'),],
        ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'other_amount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'discount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'amount_due', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'paid_amount', 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'Channel'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc');
        }, 'visible' => true, 'filter' => false],
        ['label' => Yii::t('app', 'Created date'), 'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->created_at);
        }, 'visible' => false],
        ['attribute' => 'created_by', 'filter' => false, 'visible' => false],
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
        'delete' => ['option' => 'product_sale_code,product_sale_code,/payment/tbl-product-sale/delete,checkPaymentCycleLock()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>