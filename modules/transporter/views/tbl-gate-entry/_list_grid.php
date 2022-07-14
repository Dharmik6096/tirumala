<?php

use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Gate Entry Details') ?></h5>


    <?php
    $attribute = [
            ['attribute' => 'date_time_of_collection',
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => false],
            ['attribute' => 'shift_code', 'filter' => false],
            ['attribute' => 'route_code', 'filter' => false],
            ['attribute' => 'vehicle_code', 'filter' => false],
            ['attribute' => 'actual_arrival_time', 'filter' => false],
            ['attribute' => 'define_arrival_time', 'filter' => false],
            ['attribute' => 'grace_time', 'filter' => false],
    ];


    $grid_option = [
        'id' => 'gate-entry-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE,
        'actions' => [
            'edit' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->gate_entry_code, 'data-name' => $model->gate_entry_code, 'title' => Yii::t('app', 'Edit')];
                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', '#', $options);
            },
        ]
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>