<?php

use yii\helpers\Html;
?>
<div class="grid-search">
    <?php
//        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php

$attribute = [
    ['attribute' => 'vehicle_code','value'=>function($model){ return isset($model->vehicle) ? $model->vehicle->parsing_no.'/'.$model->vehicle->vehicleType->vehicle_type_name : ''; }, 'filter' => false],
    ['attribute' => 'route_code', 'value' => 'routeCode.route_name','filter'=>false],
    ['attribute' => 'wef_date','value' => function($model){ return Yii::$app->controls->view_date($model->wef_date); },'filter'=>false],
];

$grid_option = [
    'id' => 'biling-type-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
//        'delete' => ['option' => 'rate,fuel_rate_code,tbl-fuel-rate-master/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
