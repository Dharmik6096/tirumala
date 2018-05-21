<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'vehicle_code','value'=>function($model){ return isset($model->vehicle) ? $model->vehicle->parsing_no.'/'.$model->vehicle->vehicleType->vehicle_type_name : ''; }, 'filter' => false],
    ['attribute' => 'route_code','value'=>function($model){ return isset($model->routeCode) ? $model->routeCode->route_name:''; }, 'filter' => false],
    ['attribute' => 'transporter_code','value'=>function($model){ return isset($model->transporterCode) ? $model->transporterCode->transporter_name:''; }, 'filter' => false],
    ['attribute' => 'wef_date','value' => function($model){ return Yii::$app->controls->view_date($model->wef_date); },'filter'=>false],
    ['attribute' => 'morning_kms','filter'=>false],
    ['attribute' => 'evening_kms','filter'=>false],
    ['attribute' => 'extra_kms','filter'=>false],
    ['attribute' => 'total_kms','filter'=>false],
    ['attribute' => 'shift_code','filter'=>false,'value' => 'shiftCode.shift'],
];

$grid_option = [
    'id' => 'vehicle-km-info-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $class = (Yii::$app->general->validateVehiclePayment($model)) ? '' : 'disabled' ;
            $options = ['data-name' => $model->km_info_code, 'data-val' => $model->km_info_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/transporter/tbl-vehicle-km-info/update', 'id' => $model->km_info_code], $options);
        },
//        'delete' => ['option' => 'transporter_name,transporter_code,tbl-transporter/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
