<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="grid-search">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'log_code'],
    ['attribute' => 'description', 'filter' => false],
];

$grid_option = [
    'id' => 'change-Log',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'log_code,log_code,tbl-change-log/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>