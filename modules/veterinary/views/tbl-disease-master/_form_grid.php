<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'disease_id', 'filter' => FALSE],
    ['attribute' => 'disease_name', 'filter' => true],
];
$grid_option = [
    'id' => 'disease-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'update' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record ' . $class, 'data-val' => $model->disease_id];
            return Html::a('<i class="fa fa-pencil-alt"></i>', ['/veterinary/tbl-disease-master/update', 'id' => $model->disease_id], $options);
        },
        'mapping' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-name' => $model->disease_name, 'data-val' => $model->disease_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Symptom Mapping', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/veterinary/tbl-disease-master/map-symptom', 'id' => $model->disease_id], $options);
        },
        'disable' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-val' => $model->disease_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deactive ' . $class];
            return Html::a('<i class="fa fa-times"></i>', ['/veterinary/tbl-disease-master/deactivate', 'id' => $model->disease_id], $options);
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
