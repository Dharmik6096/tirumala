<?php

use yii\helpers\Html;
?>
<div class="grid-search">
    <?php
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php

$attribute = [
    ['attribute' => 'fuel_type_code', 'value' => 'fuelType.fuel_type','filter'=>false],
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name','filter'=>false],
    ['attribute' => 'rate','filter'=>false],
    ['attribute' => 'wef_date','value' => function($model){ return Yii::$app->controls->view_date($model->wef_date); },'filter'=>false],
];

$grid_option = [
    'id' => 'transporter-list',
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
