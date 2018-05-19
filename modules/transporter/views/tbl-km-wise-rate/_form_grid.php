<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'vehicle_code','value'=>function($model){ return $model->vehicle->parsing_no.'/'.$model->vehicle->vehicleType->vehicle_type_name; },'filter'=>false],
    ['attribute' => 'rate','filter'=>false],
    ['attribute' => 'from_km','filter'=>false],
    ['attribute' => 'to_km','filter'=>false],
    ['attribute' => 'wef_date','value' => function($model){ return Yii::$app->controls->view_date($model->wef_date); },'filter'=>false],
];

$grid_option = [
    'id' => 'km-wise-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,			
        'edit' => function ($url, $model) {
            $class = (Yii::$app->general->validateVehiclePayment($model)) ? '' : 'disabled' ;
            $options = ['data-name' => $model->km_code, 'data-val' => $model->km_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/transporter/tbl-km-wise-rate/update', 'id' => $model->km_code], $options);
        },
//        'delete' => ['option' => 'rate,km_code,tbl-km-wise-rate/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
