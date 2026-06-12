<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

$attribute = [
    ['attribute' => 'owner_name', 'filter' => FALSE],
    ['attribute' => 'rate_type', 'filter' => FALSE],
    ['attribute' => 'chilling_capacity', 'filter' => FALSE],
    ['attribute' => 'min_qty', 'filter' => FALSE],
    ['attribute' => 'pan_no', 'filter' => FALSE],
    ['attribute' => 'tds_percentage', 'filter' => FALSE],
    [
        'attribute' => 'installation_date',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->installation_date);
        },
    ],
    ['attribute' => 'agreement_no', 'filter' => FALSE],
    [
        'attribute' => 'agreement_from_date',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->agreement_from_date);
        },
    ],
    [
        'attribute' => 'agreement_to_date',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->agreement_to_date);
        },
    ],
    ['attribute' => 'chiller_name', 'filter' => FALSE],
    ['attribute' => 'fix_rent', 'filter' => FALSE],
    ['attribute' => 'billing_method',
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('billing_method_chiller_info')['data'][$model->billing_method]) ? Yii::$app->dropdown->getRecords('billing_method_chiller_info')['data'][$model->billing_method] : '';
        }],
    ['attribute' => 'sap_vendor_code', 'filter' => FALSE],
    ['attribute' => 'is_default', 'value' => function($model) {
            return $model->is_default == 1 ? 'Yes' : 'No';
        }, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'bmc-chiller-info',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            $class = ($model->is_active === 0) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => 'edit-record ' . $class, 'data-val' => $model->chiller_info_code, 'data-name' => $model->chiller_info_code, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a_alert('<i class="fa fa-pencil-alt"></i>', ['/organisation/tbl-dcs-bmc/update-chiller-info'], $options);
        },
        'deactive' => function ($url, $model) {
            $name = $model->owner_name;
            $class = ($model->is_active === 0) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Deactivate', 'class' => 'deact-member ' . $class, 'data-val' => $model->chiller_info_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/organisation/tbl-dcs-bmc/deactivate-bmc-chiller'], $options);
        },
        'default' => function ($url, $model) {
            $class = empty($model->is_default) && $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-name' => $model->owner_name, 'data-val' => $model->chiller_info_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Set as Default', 'class' => 'set-default ' . $class];
            return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['/organisation/tbl-dcs-bmc/set-default-bmc-chiller', 'id' => $model->chiller_info_code], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php

$script = "$('.kv-panel-before').hide();";
$script .= "
    $(document).ready(function(){
        $(document).on('click','.deact-member',function(e){
            var id= $(this).attr('data-val');
            var name = $(this).attr('data-name');
            bootbox.confirm({
                message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>',
                buttons: {
                    'cancel': {
                                    label: 'Cancel',
                                    className: 'btn btn-danger'
                    },
                    'confirm': {
                                    label: 'Ok',
                                    className: 'btn btn-primary'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $('#loader').show();
                        $.ajax({
                            type: 'get',
                            url: '" . Url::to(['deactivate-bmc-chiller']) . "',
                            data:{'id':id},
                            success: function(data) {
                                var obj1 = $.parseJSON(data);
                                console.log(obj1);
                                if (obj1.status == 'success')
                                {
                                    $.pjax.reload({container: '#bmc-chiller-info'});
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                }
                                else if (obj1.status == 'error'){
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                }
                            },
                        });
                    }
                }
            });
        });

        $(document).on('click','.set-default',function(e){
            var id= $(this).attr('data-val');
            var name = $(this).attr('data-name');
            bootbox.confirm({
                message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to set \"'+name+'\" as default chiller?</span></div></div>',
                buttons: {
                    'cancel': {
                        label: 'Cancel',
                        className: 'btn btn-danger'
                    },
                    'confirm': {
                        label: 'Ok',
                        className: 'btn btn-primary'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $('#loader').show();
                        $.ajax({
                            type: 'get',
                            url: '" . Url::to(['set-default']) . "',
                            data:{'id':id},
                            success: function(data) {
                                var obj1 = $.parseJSON(data);
                                console.log(obj1);
                                if (obj1.status == 'success') {
                                    $.pjax.reload({container: '#bmc-chiller-info'});
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                } else if (obj1.status == 'error'){
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                }
                            },
                        });
                    }
                }
            });
        });
    });
";
$this->registerJs($script, View::POS_END, 'bmc-chiller');
