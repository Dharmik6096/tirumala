<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'vAlign' => 'middle', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
        ['attribute' => 'bmc_code', 'vAlign' => 'middle', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'grn_no', 'vAlign' => 'middle'],
        ['attribute' => 'challan_no', 'vAlign' => 'middle'],
        ['attribute' => 'bill_no', 'vAlign' => 'middle'],
        [
        'attribute' => 'grn_date', 'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->grn_date);
        }, 'visible' => true],
        ['attribute' => 'vendor_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'vendor_code', 'vAlign' => 'middle', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return isset($model->vendor_type) ? Yii::$app->general->getCustomer($model, $model->vendor_type) : '';
        }, 'vAlign' => 'middle'],
        [
        'attribute' => 'challan_date', 'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->challan_date);
        }, 'visible' => false, 'vAlign' => 'middle'],
        ['attribute' => 'description', 'vAlign' => 'middle'],
        [
        'attribute' => 'challan_verified',
        'vAlign' => 'middle',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_type', $searchModel, 'challan_verified'),
        'value' => function($model) {
            return ($model->challan_verified == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }
    ],
];

$grid_option = [
    'id' => 'product-receipt-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>