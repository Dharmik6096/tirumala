<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
    ['attribute' => 'mcc', 'vAlign' => 'middle', 'filter' => false],
    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model['date_time_of_collection']);
//        }, 
                'filter' => FALSE],
    ['attribute' => 'shift', 'filter' => false],
    ['attribute' => 'qty', 'filter' => FALSE],
    ['attribute' => 'avgFAT', 'filter' => FALSE],
    ['attribute' => 'avgSNF', 'filter' => FALSE],
];

$grid_option = [
    'id' => 'shift-lock-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'status' => function ($url, $model) {
            $lockModel = new app\modules\collection\models\TblMccShiftLock();
            $status = $lockModel->getStatus($model);
            if ($status == 1) {
                $class = 'lock';
                $options = ['data-union' => $model['union_code'], 'data-plant' => $model['plant_code'], 'data-mcc' => $model['mcc_plant_code'], 'data-date' => $model['date_time_of_collection'], 'data-shift' => $model['shift_code'], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'LOCK', 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-lock"></i>', ['/collection/tbl-mcc-shift-lock/unlock-data'], $options);
            } else {
                $class = 'unlock';
                $options = ['data-union' => $model['union_code'], 'data-plant' => $model['plant_code'], 'data-mcc' => $model['mcc_plant_code'], 'data-date' => $model['date_time_of_collection'], 'data-shift' => $model['shift_code'], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'UN-LOCK', 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-unlock"></i>', ['/collection/tbl-mcc-shift-lock/lock-data'], $options);
            }
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.unlock',function(e){
    var union= $(this).attr('data-union');
    var plant= $(this).attr('data-plant');
    var mcc= $(this).attr('data-mcc');
    var date = $(this).attr('data-date');
    var shift = $(this).attr('data-shift');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to LOCK Data ?</span></div></div>',
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
                        url: '" . Url::to(['lock-data']) . "',
                        data:{'mcc':mcc,'date':date,'union':union,'plant':plant,'shift':shift},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#shift-lock-list'});
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
    $(document).on('click','.lock',function(e){
    var union= $(this).attr('data-union');
    var plant= $(this).attr('data-plant');
    var mcc= $(this).attr('data-mcc');
    var date = $(this).attr('data-date');
     var shift = $(this).attr('data-shift');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to UN-LOCK \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['unlock']) . "',
                         data:{'mcc':mcc,'date':date,'union':union,'plant':plant,'shift':shift},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#shift-lock-list'});
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
   
});";
$this->registerJs($script, View::POS_END, 'shift-lock');
?>