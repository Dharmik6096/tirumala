<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
        ['attribute' => 'plant_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false],
        ['attribute' => 'mcc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'visible' => false],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'visible' => true],
        ['attribute' => 'vendor_type', 'value' => function($model) {
            return isset($model->vendor_type) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_type', $searchModel, 'vendor_type'),],
        ['attribute' => 'vendor_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return $model->getEntityName();
        }, 'vAlign' => 'middle'],
        ['attribute' => 'reference_no'],
        ['attribute' => 'challan_no'],
        [
        'attribute' => 'dispatch_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->dispatch_date);
        }],
        ['attribute' => 'vehicle_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }],
        [
        'attribute' => 'challan_date', 'visible' => false,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->challan_date);
        }],
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
    'id' => 'product-requisition-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>