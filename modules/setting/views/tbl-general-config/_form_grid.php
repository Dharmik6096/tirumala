<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="grid-search">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'config_code', 'filter' => true],
    ['attribute' => 'module_name', 'filter' => true],
];

$grid_option = [
    'id' => 'general-config-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'view' => true,
//        'delete' => ['option' => 'config_code,config_code,tbl-general-config/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>