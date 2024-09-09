<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;

Url::remember();
$attribute = [
    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'filter' => false],
    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'filter' => false],
    ['attribute' => 'bank_account_no', 'filter' => false],
    ['attribute' => 'ifsc', 'filter' => false],
    ['attribute' => 'beneficiary_name', 'filter' => false],
    ['attribute' => 'is_default', 'value' => function($model) {
            return $model->is_default == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
    ['attribute' => 'is_verified', 'value' => function($model) {
            return $model->is_verified == 1 ? 'Verified' : ( $model->is_verified == 2 ? 'Reject' : 'Pending');
        }, 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'bank-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        //'view' => true,
        'disable' => function ($url, $model) {
            if ($model->is_active == 1) {
                $options = ['data-name' => $model->bank_account_no, 'data-val' => $model->detail_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Deactivate', 'class' => 'deactive', 'data-is-default' => $model->is_default];
                return Html::a('<i class="fa fa-ban"></i>', ['/details/tbl-bank-details/deactivate', 'id' => $model->detail_code], $options);
            } else {
                $options = ['data-name' => $model->beneficiary_name, 'data-val' => $model->detail_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Activate', 'class' => 'react-user ', 'data-is-default' => $model->is_default];
                return Html::a('<i class="fa fa-life-ring"></i>', ['/details/tbl-bank-details/activate', 'id' => $model->detail_code], $options);
            }
        },
        'default' => function ($url, $model) {
            $options = ['data-name' => $model->bank_account_no, 'data-val' => $model->detail_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Set as Default', 'class' => 'set-default'];
            return ($model->is_default == 0 && $model->is_active == 1) ? Html::a('<i class="fa fa-check"></i>', ['/details/tbl-bank-details/set-default', 'id' => $model->detail_code], $options) : '';
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
<?php

$deactivateUrl = Url::to(['deactivate']);
$activateUrl = Url::to(['activate']);

$script = <<< JS

$(document).ready(function(){
    $(document).on('click', '.deactive', function(e) {
        e.preventDefault();
        var trg = $(this);
        var id=$(this).parents('tr').find('td:eq(1)').text();
        var df = trg.attr('data-is-default');
        if(df==1){ var msg = 'This is a Default bank account, Are you sure you want to deactivate bank account'; }
            else { var msg = 'This can not be reactivate, Are you sure you want to deactivate bank account'; }
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
        
    $(document).on('click', '.react-user', function(e) {
        e.preventDefault();
        var trg = $(this);
        var id=$(this).parents('tr').find('td:eq(1)').text();
        var name = $(this).attr('data-name');
        bootbox.confirm({
            message: '<div class="row"><div class="col-sm-12"><div class="bg-info"><i class="fa fa-question"></i></div><span>Are you sure you want to activate the bank account "' + id + '"?</span></div></div>',
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