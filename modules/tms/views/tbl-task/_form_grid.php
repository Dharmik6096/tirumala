<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'task_code', 'filter' => true],
    'task_performed_for',
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'filter' => true],
    ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => true],
    ['attribute' => 'task_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->task_datetime);
        }],
    ['attribute' => 'user_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => true],
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'Department'), 'value' => function($model) {
        return Yii::$app->general->getmultiforeignkey($model->userCode, ['departmentCode'], 'department');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'task_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->taskTypeCode, 'task_type');
        }, 'filter' => false],
    ['attribute' => 'form_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->formTypeCode, 'form_name');
        }, 'filter' => false],
    'title',
    'description',
    'status',
    [
        'attribute' => 'is_cancel',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_cancel'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->is_cancel, 'boolean_value');
        }],
    [
        'attribute' => 'is_notified',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_notified'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->is_notified, 'boolean_value');
        }, 'visible' => false],
    ['attribute' => 'notified_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->notified_datetime);
        }, 'visible' => false, 'filter' => false],
];
$grid_option = [
    'id' => 'task-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'cancel' => function ($url, $model) {
            $name = $model->title . ' of ' . date('d-m-Y', strtotime($model->task_datetime));
            $class = ($model->status == 'OPEN' && $model->is_cancel == 0) ? '' : 'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Cancel', 'class' => 'deact-task ' . $class, 'data-val' => $model->task_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/tms/tbl-task/cancel'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-task',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to cancel \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['cancel']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#task-detail-list'});
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
   });";
$this->registerJs($script, View::POS_END, 'task-list-index');
?> 