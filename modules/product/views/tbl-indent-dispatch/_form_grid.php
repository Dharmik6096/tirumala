<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
    ['attribute' => 'plant_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'mcc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'visible' => true],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code')],
    ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'reference_no'],
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
//    ['attribute' => 'vehicle_no', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
//        }],
    ['attribute' => 'vehicle_no'],
    ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }],
    [
        'attribute' => 'challan_date', 'visible' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->challan_date);
        }, 'filter' => false],
    ['attribute' => 'dispatch_qty'],
    ['attribute' => 'lr_no', 'filter' => false]
];

$grid_option = [
    'id' => 'indent-dispatch-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>