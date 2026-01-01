<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'scheme_rate_code', 'vAlign' => 'middle'],
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
        ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
        ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShift, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'rtpl', 'vAlign' => 'middle'],
        ['attribute' => 'rate_class', 'value' => function($model) {
            return empty($model->rate_class) ? 'All' : Yii::$app->general->getforeignkey($model->rateClass, 'rate_class');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'is_mcc_wise_rate', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_mcc_wise_rate');
        },],
        ['attribute' => 'description', 'vAlign' => 'middle'],
        ['attribute' => 'is_member_rate', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_member_rate');
        },],
];

$grid_option = [
    'id' => 'scheme-rate-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'mapping' => function ($url, $model) {
            $disable = '';
            if ($model->is_active == 1) {
                $url = '/dcsoperation/tbl-scheme-rate/scheme-rate-applicability';
                $icon = '<i class="fa fa-plus"></i>';
                $title = 'Applicability';
            } else {
                $url = '/dcsoperation/tbl-scheme-rate/view';
                $icon = '<i class="fa fa-eye"></i>';
                $title = 'View';
            }
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $title, 'class' => $disable];
            return GhostHtml::a($icon, [$url, 'id' => $model->scheme_rate_code], $options);
        },
        'deactivate' => function ($url, $model) {
            $urls = 'deactivate';
            $name = $model->scheme_rate_code;
            $icon_class = 'fa-times';
            $title = 'Deactivate';
            $name = 'Deactivate';
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $class = 'deact-rate ' . $disable;
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $title, 'class' => $class, 'data-val' => $model->scheme_rate_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa ' . $icon_class . '""></i>', $urls, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], true);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-rate',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to  \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['deactivate']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#scheme-rate-grid'});
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
});
$(document).ready(function(){
    $(document).on('click','.act-rate',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to  \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['activate']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#scheme-rate-grid'});
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
})
";
$this->registerJs($script, View::POS_END, 'scheme-rate-avtivate-deactivate');

