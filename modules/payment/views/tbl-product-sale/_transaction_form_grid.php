<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode->unionCode, 'union_name');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_type', 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? (strtolower($model->productSaleCode->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->productSaleCode->customerType, 'customer_desc') ) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode, 'customer_code');
        }, 'vAlign' => 'middle'],
    ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type, true) : '';
        }, 'vAlign' => 'middle'],
    ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type, FALSE, FALSE, true) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'name'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? (strtolower($model->productSaleCode->customer_type) == 'member' ? Yii::$app->general->getforeignkey($model->productSaleCode->mainDcsCode, 'ref_code') : (strtolower($model->productSaleCode->customer_type) == 'dcs' ? Yii::$app->general->getforeignkey($model->productSaleCode->dcsCode, 'ref_code') : '')) : '';
        }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Name'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? (strtolower($model->productSaleCode->customer_type) == 'member' ? Yii::$app->general->getforeignkey($model->productSaleCode->mainDcsCode, 'dcs_name') : (strtolower($model->productSaleCode->customer_type) == 'dcs' ? Yii::$app->general->getforeignkey($model->productSaleCode->dcsCode, 'dcs_name') : '')) : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'product_name', 'label' => Yii::t('app', 'Product') . ' ' . Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'product_desc', 'label' => Yii::t('app', 'Product Desc'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_desc');
        }, 'vAlign' => 'middle', 'filter' => true],
    [
        'attribute' => 'invoice_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->productSaleCode->invoice_date);
        }],
    ['attribute' => 'payment_mode', 'value' => function($model) {
            return isset($model->productSaleCode->payment_mode) ? Yii::$app->dropdown->getRecords('payment_mode')['data'][$model->productSaleCode->payment_mode] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('payment_mode', $searchModel, 'payment_mode'),],
    ['attribute' => 'rate', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'quantity', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'discount', 'value' => function($model) {
            return $model->productSaleCode->other_amount;
        }, 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'tax_amount', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'commission', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'Channel'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productSaleCode->bmcCode, ['channelMaster'], 'channel_desc');
        }, 'visible' => true, 'filter' => false],
    ['label' => Yii::t('app', 'Created date'), 'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->productSaleCode->created_at);
        }, 'visible' => false],
    [
        'attribute' => 'created_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode->userCode, 'name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'originating_org_type', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('originating_type_flag', $model, 'originating_type');
        },],
    [
        'attribute' => 'originating_type',
        'value' => function($model) {
            return Yii::$app->general->getOriginatingType($model, 'originating_type');
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'product-sale-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>