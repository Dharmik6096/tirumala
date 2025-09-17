<?php

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'animal_type_code', 'filter' => FALSE],
    ['attribute' => 'animal_type_name', 'filter' => true],
];
$grid_option = [
    'id' => 'member-animal-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'update' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record ' . $class, 'data-val' => $model->animal_type_code];
            return Html::a('<i class="fa fa-pencil-alt"></i>', ['/dcsoperation/tbl-member-animal-type/update', 'id' => $model->animal_type_code], $options);
        },
        'disable' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-val' => $model->animal_type_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deactive ' . $class];
            return Html::a('<i class="fa fa-times"></i>', ['/dcsoperation/tbl-member-animal-type/deactivate', 'id' => $model->animal_type_code], $options);
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