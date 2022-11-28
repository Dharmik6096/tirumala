<?php

use webvimark\modules\UserManagement\components\GhostHtml;

$client_code = \Yii::$app->session->get('eiplCode');
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Gate Entry Details') ?></h5>

    <?php
    $attribute = [
        ['attribute' => 'route_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
            }, 'filter' => false],
        ['attribute' => 'vehicle_code', 'value' => function($model)use($client_code) {
                if ($client_code == 'UMANG') {
                    return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
                } else {
                    $model->vehicle_code;
                }
            }, 'filter' => false],
        ['attribute' => 'date_time_of_collection',
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => false],
        ['attribute' => 'actual_arrival_time', 'filter' => false],
        ['attribute' => 'define_arrival_time', 'filter' => false],
        ['attribute' => 'grace_time', 'filter' => false],
        ['attribute' => 'no_of_filled_can', 'filter' => false],
        ['attribute' => 'no_of_empty_can', 'filter' => false],
    ];


    $grid_option = [
        'id' => 'gate-entry-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE,
        'actions' => [
            'edit' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->gate_entry_code, 'data-name' => $model->gate_entry_code, 'title' => Yii::t('app', 'Edit')];
                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/transporter/tbl-gate-entry/update'], $options);
            },
            'print-gate-pass' => function ($url, $model) {
                $options = ['target' => '_blank', 'title' => Yii::t('app', 'Print Gate Pass'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'Print Gate Pass')];
                return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/transporter/tbl-gate-entry/print-gate-pass', 'id' => $model->gate_entry_code], $options);
            },
            'gate-out-entry' => function ($url, $model) {
                $name = Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
                $class = $model->status == 1 ? 'link-disable' : '';
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Gate Out Entry', 'class' => 'gate-out ' . $class, 'data-val' => $model->gate_entry_code, 'data-name' => $name];
                return GhostHtml::a_alert('<i class="fa fa-sign-out"></i>', ['/transporter/tbl-gate-entry/get-out-entry'], $options);
            },
        ]
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>