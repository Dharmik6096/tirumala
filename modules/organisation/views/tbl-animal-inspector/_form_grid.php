<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'ai_name'],
        ['attribute' => 'ai_mobile_no'],
        ['attribute' => 'ai_address'],
];

$grid_option = [
    'id' => 'animal-inspector-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        //  'view' => true,
        'update' => true,
        'deactive' => function ($url, $model) {
            $name = 'ai_name';
            if ($model->is_active == 1) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-animal', 'data-val' => $model->animal_inspector_code, 'data-name' => $name];
                return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/organisation/tbl-animal-inspector/deactivate-doctor'], $options);
            } else {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Activate', 'class' => 'react-animal', 'data-val' => $model->animal_inspector_code, 'data-name' => $name];
                return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['/organisation/tbl-animal-inspector/activate-doctor'], $options);
            }
        },
        'applicability' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/organisation/tbl-animal-inspector/animal-inspector-applicability', 'id' => $model->animal_inspector_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-animal',function(e){
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
                        url: '" . Url::to(['deactivate-doctor']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#animal-inspector-grid'});
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
 $(document).on('click','.react-animal',function(e){
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
                        url: '" . Url::to(['activate-doctor']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#animal-inspector-grid'});
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
$this->registerJs($script, View::POS_END, 'animal-inspector-list');

