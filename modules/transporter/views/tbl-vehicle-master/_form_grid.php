<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    //'vehicle_code',
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'transporter_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporter, 'transporter_name');
        },
        'visible' => true, 'filter' => false],
    ['attribute' => 'parsing_no'],
    ['attribute' => 'capacity_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->capacityCode, 'value');
        },
        'visible' => true, 'filter' => false],
    ['attribute' => 'vehicle_type_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleType, 'vehicle_type_name');
        },
        'visible' => true, 'filter' => Yii::$app->dropdown->dropdownfilter('vehicle_type_code', $searchModel, 'vehicle_type_code')],
    ['attribute' => 'billing_method',
        'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('billing_method', $model, 'billing_method');
        },
        'visible' => true, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('billing_method', $searchModel, 'billing_method')],
    ['attribute' => 'registration_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'applicable_rto', 'visible' => false, 'filter' => false],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'driver_name', 'visible' => true, 'filter' => false],
    ['attribute' => 'driver_contact_no', 'visible' => false, 'filter' => false],
];

$grid_option = [
    'id' => 'vehicle-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'km-wise-rate' => function ($url, $model) {
            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Km Wise Rate', 'class' => ''];
            return GhostHtml::a('<i class="fa fa-road"></i>', ['/transporter/tbl-km-wise-rate/create', 'vehicle_code' => $model->vehicle_code], $options);
        },
//        'mobile-oil-rate' => function ($url, $model) {
//            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Mobile Oil Rate', 'class' => ''];
//            return GhostHtml::a('<i class="fa fa-mobile"></i>', ['/transporter/tbl-mobile-oil-rate-master/create', 'vehicle_code' => $model->vehicle_code], $options);
//        },
//        'vehicle-billing-type' => function ($url, $model) {
//            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Vehicle Billing Type', 'class' => ''];
//            return GhostHtml::a('<i class="fa fa-money"></i>', ['/transporter/tbl-vehicle-billing-type/create', 'vehicle_code' => $model->vehicle_code], $options);
//        },
        'transporter-payment-head' => function ($url, $model) {
            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Vehicle Transporter Payment Head', 'class' => ''];
            return GhostHtml::a('<i class="fa fa-truck"></i>', ['/transporter/tbl-vehicle-transporter-head-mapping/create', 'vehicle_code' => $model->vehicle_code], $options);
        },
//        'delete' => ['option' => 'vehicle_code,vehicle_code,tbl-vehicle-master/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>