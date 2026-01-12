<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
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
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'name'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? Yii::$app->general->getforeignkey($model->mainDcsCode, 'ref_code') : (strtolower($model->customer_type) == 'dcs' ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : '')) : '';
        }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Name'), 'value' => function($model) {
            return isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? Yii::$app->general->getforeignkey($model->mainDcsCode, 'dcs_name') : (strtolower($model->customer_type) == 'dcs' ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '')) : '';
        }, 'vAlign' => 'middle', 'filter' => false],
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
    [
        'attribute' => 'created_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false, 'visible' => false],
    [
        'attribute' => 'originating_type',
        'value' => function($model) {
            return isset($model->originating_type) ? Yii::$app->dropdown->getRecords('originating_type_options')['data'][$model->originating_type] : '';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'product-sale-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'installment' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Installments'];
            return GhostHtml::a('<i class="fa fa fa-money-bill"></i>', ['/payment/tbl-product-sale/sale-installments', 'id' => $model->product_sale_code], $options);
        },
        'delete' => [
            'option' => 'customer_type###customer_code###invoice_date~date,product_sale_code,/payment/tbl-product-sale/delete,checkPaymentCycleLock()',
        ],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>