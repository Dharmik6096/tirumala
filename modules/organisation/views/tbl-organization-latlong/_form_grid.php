<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'customer_type'],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Customer Code'), 'filter' => true, 'visible' => true],
    ['attribute' => 'ref_code', 'value' => function ($model) {
        return Yii::$app->general->getField($model, $model->customer_type, 'ref_code');
    }, 'filter' => true, 'visible' => true],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function ($model) {
        return Yii::$app->general->getField($model, $model->customer_type);
    }],
    ['attribute' => 'lat_long'],
];
$grid_option = [
    'id' => 'organization-latlong-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'organization_latlong_code,organization_latlong_code,tbl-organization-latlong/delete'],
        'deactive' => function ($url, $model) {
            if ($model->is_active == 1) {
                $msg = 'Are you sure you want to deactivate '.$model->organization_latlong_code;
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'status-change', 'data-val' => $model->organization_latlong_code, 'data-msg' => $msg];
                return GhostHtml::a_alert('<i class="fa fa-close"></i>', [''], $options);
            } else {
                $msg = 'Are you sure you want to activate '.$model->organization_latlong_code;
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Activate', 'class' => 'status-change', 'data-val' => $model->organization_latlong_code, 'data-msg' => $msg];
                return GhostHtml::a_alert('<i class="fa fa-check"></i>', [''], $options);
            }
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php
$script = "
$(document).ready(function(){
    $(document).on('click','.status-change',function(e){
        var id= $(this).attr('data-val');
        var msg = $(this).attr('data-msg');
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>\"'+msg+'\"?</span></div></div>',
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
                        url: '" . Url::to(['change-status']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#organization-latlong-grid'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted..Please try again');
                        }
                    });
                }
            }
        });
    }); 
});";
$this->registerJs($script, View::POS_END, 'organization-latlong-list');
