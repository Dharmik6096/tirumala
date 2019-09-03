<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use app\components\GeneralFunctions;
use yii\helpers\Url;
use yii\web\View;
?>
<div class="grid-search clearfix">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
    ['attribute' => 'file_name', 'vAlign' => 'middle'],
    ['attribute' => 'no_of_records', 'vAlign' => 'middle'],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => FALSE, 'vAlign' => 'middle'],
];

$grid_option = [
    'id' => 'pendrive-export-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'download' => function ($url, $model) {
            if ($model->download_counter == 0) {
                $class = 'first-download';
            } else {
                $class = 'multiple-download';
            }
            $options = [ 'title' => 'Download Zip', 'class' => $class, 'data-val' => $model->id, 'data-name' => $model->file_name];
            return GhostHtml::a_alert('<span class="fa fa-download"></span>', ['/syncutility/pendrive-sync/download'], $options);
        },
            ]
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>

        <?php
        $script = "
  $(document).ready(function(){
    $(document).on('click','.first-download',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
    message:'<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-question-circle text-primary\'></i></div><div class=\'col-sm-10 padding-left-0\'><span>Are you sure you want to download \"'+name+'\"?</span></div></div>', 
    buttons: {
            'cancel': {
                            label: '" . Yii::t('app', 'Cancel') . "',
                            className: 'btn btn-default'
              },
            'confirm': {
                           label: '" . Yii::t('app', 'Download') . "',
                           className: 'btn btn-default',
             }
        },
        callback: function(result) {
            if (result) {
                   $('#loadercontent').show();
                   $('#pageloader').show();
                 $.ajax({
                        type: 'post',
                        url: '" . Url::to(['download']) . "',
                        data:{'id':id},
                        success: function(data) {
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                         var data = $.parseJSON(data);
                         if(data.status=='success'){
                             $.pjax.reload({container: '#pendrive-export-list'}); 
                                $(document).on('pjax:success', function () {
//                                    window.open(data.url, '_blank'); 
                                    window.location.href = data.url;
                              });
                           }else{
                    bootbox.alert({
                              message: '<div class=\"row\"><div class=\"col-sm-2 mt10\"><i class=\"fa fa-3x fa-info-circle text-primary\"></i></div><div class=\"col-sm-10 padding-left-0\">'+data.msg+'</div></div>',
                         });                  
                }                        
                        },                       
            });
            }
        }
    });
    });
});";
        $this->registerJs($script, View::POS_END, 'first-download-pendrivesync');
        ?>
        <?php
        $script = "
  $(document).ready(function(e){
    $(document).on('click','.multiple-download',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
       e.preventDefault();
     bootbox.dialog({
         message:'<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-question-circle text-primary\'></i></div><div class=\'col-sm-10 padding-left-0\'><span>Are you sure you want to download \"'+name+'\"?</span></div></div>',
                buttons: {
                 reject: {
                           label: '" . Yii::t('app', 'Remove Permanently') . "',
                           className: 'btn btn-default',
                             callback: function(){  
                             $.ajax({
                        type: 'post',
                        url: '" . Url::to(['download-multiple']) . "',
                        data:{'id':id,'type':'R'},
                        success: function(data) {    
                        var data = $.parseJSON(data);
                         if(data.status=='success'){
                             $.pjax.reload({container: '#pendrive-export-list'});  
                              $(document).on('pjax:success', function () {
                               window.location =  window.location;
                               });
                        }   
//                         bootbox.alert({
//                              message: '<div class=\"row\"><div class=\"col-sm-2 mt10\"><i class=\"fa fa-3x fa-info-circle text-primary\"></i></div><div class=\"col-sm-10 padding-left-0\">'+data.msg+'</div></div>',
//                         });
                        },                       
            });
                      }
                           
                     },
                    cancel: {
                           label:  '" . Yii::t('app', 'Cancel') . "' ,
                           className: 'btn btn-default',
                           callback: function(){          
                      }
                      },
                    confirm: {
                           label:'" . Yii::t('app', 'Download') . "',
                           className: 'btn btn-default',
                             callback: function(){  
                              $.ajax({
                        type: 'post',
                        url: '" . Url::to(['download-multiple']) . "',
                        data:{'id':id,'type':'D'},
                        success: function(data) {     
                         var data = $.parseJSON(data);
                         if(data.status=='success'){
                             $.pjax.reload({container: '#pendrive-export-list'}); 
                              $(document).on('pjax:success', function () {
//                                    window.open(data.url, '_blank'); 
                                    window.location.href = data.url;
                              });
                           }else{
                    bootbox.alert({
                              message: '<div class=\"row\"><div class=\"col-sm-2 mt10\"><i class=\"fa fa-3x fa-info-circle text-primary\"></i></div><div class=\"col-sm-10 padding-left-0\">'+data.msg+'</div></div>',
                         });                  
                }       
                        },                       
            });
                        }
                      }
                 }             
             });  
    });
});";
        $this->registerJs($script, View::POS_END, 'multiple-download-pendrivesync');
        ?>