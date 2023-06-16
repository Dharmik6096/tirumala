<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

$attribute = [
        ['attribute' => 'complain_type', 'filter' => true],
        ['attribute' => 'complain_escalation_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->complainEscalationCode, 'escalation_name');
        },
    ],
        [
        'attribute' => 'complain_for',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complain_for', $searchModel, 'complain_for'),
        'value' => function($model) {
            return isset($model->complain_for) ? Yii::$app->dropdown->getRecords('complain_for')['data'][$model->complain_for] : '';
        }
    ],
];

$grid_option = [
    'id' => 'complain-type-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
//        'view' => true,
        'update' => true,
        'delete' => ['option' => 'complain_type,complain_type_code,tbl-complain-type/delete'],
        'deactive' => function ($url, $model) {
            $name = $model->complain_type;
            if ($model->is_active == 1) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-complain-type', 'data-val' => $model->complain_type_code, 'data-name' => $name];
                return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/complaint/tbl-complain-type/deactivate-type'], $options);
            } else {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Activate', 'class' => 'react-complain-type', 'data-val' => $model->complain_type_code, 'data-name' => $name];
                return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['/complaint/tbl-complain-type/activate-type'], $options);
            }
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-complain-type',function(e){
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
                        url: '" . Url::to(['deactivate-type']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#complain-type-grid'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
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
 $(document).on('click','.react-complain-type',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Activate \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['activate-type']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#complain-type-grid'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                        }
            });
            }
        }
    });
    });   
});";
$this->registerJs($script, View::POS_END, 'complain-type-list');

