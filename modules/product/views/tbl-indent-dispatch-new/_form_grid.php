<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'mcc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'visible' => false],
    // ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code'), 'visible' => false],
    // ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
    //         return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
    //     }, 'vAlign' => 'middle', 'visible' => false],
    ['attribute' => 'lr_no', 'filter' => false, 'visible' => true],
    ['attribute' => 'reference_no', 'visible' => true],
    [
        'attribute' => 'dispatch_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->dispatch_date);
        }, 'visible' => true],
    ['attribute' => 'vehicle_no', 'visible' => true],
    
];

$grid_option = [
    'id' => 'indent-dispatch-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'dcs-wise-challan' => function ($url, $model) {
            $options = ['title' => 'View ' . Yii::t('app', 'DCS') . ' Wise Challan Report', 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/jasperreports/default/milk-chill-bill-center-wise', 'indent_dispatch_code' => $model->indent_dispatch_code], $options);
        },
        'lr-copy' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'View LR Copy Report'), 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/jasperreports/default/milk-chilling-bill-lr-no-wise', 'indent_dispatch_code' => $model->indent_dispatch_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>