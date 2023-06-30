<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
    ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty'],
    ['attribute' => 'avg_fat'],
    ['attribute' => 'avg_snf'],
    ['attribute' => 'amount'],
    [
        'attribute' => 'data_post_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'data_post_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->data_post_status] : '';
        }],
    ['attribute' => 'picked_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime);
        }, 'filter' => false],
    [
        'attribute' => 'resp_status',
        'value' => function($model) {


            return $model->resp_status == 1 ? 'SUCCESS' : ($model->resp_status == '0' ? 'ERROR' : '');
        }, 'filter' => false],
    ['attribute' => 'resp_desc', 'filter' => false],
    ['attribute' => 'response_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'shift-lock-status-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'repush' => function ($url, $model) {
            $class = 'repush';
            $options = ['data-id' => $model->staging_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Re-Push', 'class' => '' . $class];
            return GhostHtml::a_alert('<i class="fa fa-upload"></i>', ['/collection/tbl-mcc-shift-lock-staging/re-push-data'], $options);
        },
//        'view' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.repush',function(e){
    var id= $(this).attr('data-id');
  
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Re Push Data ?</span></div></div>',
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
                        url: '" . Url::to(['re-push-data']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#shift-lock-status-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully Repush.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not Repush.', timeout: 8000, style: 'errorbar'});
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