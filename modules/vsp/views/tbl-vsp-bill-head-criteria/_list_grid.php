<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<div class="">
    <h5 class="panel-heading"><?= Yii::t('app', 'Bill Head Criteria Slab') ?></h5>

    <?php
    $attribute = [
        ['attribute' => 'from_val', 'filter' => FALSE],
        ['attribute' => 'to_val', 'filter' => FALSE],
        ['attribute' => 'formula_with_val', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'bill-head-criteria-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'actions' => [
            'delete-slab' => function($url, $model) {
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Delete', 'class' => 'delete-slab', 'data-from' => $model->from_val, 'data-to' => $model->to_val, 'data-id' => $model->vsp_slab_code];
                return GhostHtml::a_alert('<i class="fa fa-trash"></i>', ['delete-slab', 'id' => $model->vsp_slab_code], $options);
            }]
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>

</div>