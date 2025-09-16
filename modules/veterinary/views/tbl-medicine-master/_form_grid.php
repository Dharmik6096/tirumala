<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'medicine_id', 'filter' => FALSE],
    ['attribute' => 'medicine_name', 'filter' => true],
];
$grid_option = [
    'id' => 'medicine-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'update' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $class, 'data-val' => $model->medicine_id];
            return Html::a('<i class="fa fa-pencil"></i>', ['/veterinary/tbl-medicine-master/update', 'id' => $model->medicine_id], $options);
        },
        'mapping' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-name' => $model->medicine_name, 'data-val' => $model->medicine_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Medicine Mapping', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/veterinary/tbl-medicine-master/map-symptom', 'id' => $model->medicine_id], $options);
        },
        'disable' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-val' => $model->medicine_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deactive ' . $class];
            return Html::a('<i class="fa fa-close"></i>', ['/veterinary/tbl-medicine-master/deactivate', 'id' => $model->medicine_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php
$script = <<< JS

$(document).ready(function(){
    $(document).on('click', '.deactive', function(e) {
        e.preventDefault();
        var trg = $(this);
        var id=$(this).parents('tr').find('td:eq(1)').text();
        var msg = 'Are you sure you want to deactivate';
        bootbox.confirm({
                message: '<div class="row"><div class="col-sm-12"><div class="bg-info"><i class="fa fa-question"></i></div><span> '+msg+' "'+id+'"?</span></div></div>',
            buttons: {
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                },
                confirm: {
                    label: 'Yes',
                    className: 'btn-primary'
                }
            },
            callback: function(result) {
                if (result) {
                    window.location.href = trg.attr('href');
                }
            }
        });
    });
});
JS;
$this->registerJs($script, \yii\web\View::POS_READY);
?>
