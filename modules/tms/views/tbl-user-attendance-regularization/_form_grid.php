<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

$attribute = [
    ['attribute' => 'user_code', 'filter' => false],
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'Name'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'visible' => true, 'filter' => true],
    [
        'attribute' => 'apply_date',
        'attribute' => 'apply_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->apply_date);
        }
    ],
    ['attribute' => 'actual_in_time', 'value' => function ($model) {
            $in_time = Yii::$app->general->getforeignkey($model->userAttendance, 'out_time');
            return Yii::$app->controls->view_time($in_time);
        }, 'filter' => false, 'visible' => true],
    ['attribute' => 'actual_out_time', 'value' => function ($model) {
            $out_time = Yii::$app->general->getforeignkey($model->userAttendance, 'out_time');
            return Yii::$app->controls->view_time($out_time);
        }, 'filter' => false, 'visible' => true],
    ['attribute' => 'requested_in_time', 'value' => function ($model) {
            return Yii::$app->controls->view_time($model->requested_in_time);
        }, 'filter' => false, 'visible' => true],
    ['attribute' => 'requested_out_time', 'value' => function ($model) {
            return Yii::$app->controls->view_time($model->requested_out_time);
        }, 'filter' => false, 'visible' => true],
    ['attribute' => 'regularization_reason', 'filter' => false, 'visible' => false],
    ['attribute' => 'status', 'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('attendances_regularization_status', $model, 'status');
        }, 
        // 'filter' => Yii::$app->dropdown->dropdownfilterStatic('approval_status', $searchModel, 'status')
        'filter' => false
    ],
    ['attribute' => 'approved_date', 'value' => function ($model) {
            return Yii::$app->controls->view_date($model->approved_date);
        }, 'filter' => false, 'visible' => true],
    ['attribute' => 'approved_by', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->approvedUserCode, 'name');
        }, 'visible' => true, 'filter' => true],
    ['attribute' => 'rejected_date', 'value' => function ($model) {
            return Yii::$app->controls->view_date($model->rejected_date);
        }, 'filter' => false, 'visible' => true],
    ['attribute' => 'rejected_by', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->rejectUserCode, 'name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'rejection_remark', 'filter' => false, 'visible' => true],
];
$grid_option = [
    'id' => 'user-attendance-regularization-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'reject' => function ($url, $model) {
            // $class = '';
            $class = ($model->status == '0') ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Reject', 'class' => 'user-attendance-regularization ' . $class, 'data-process_approval_code' => $model->process_approval_code, 'data-regularization_code' => $model->regularization_code];
            return GhostHtml::a_alert('<i class="fa fa-times"></i>', 'javascript:void(0)', $options);
        },
        'approve' => function ($url, $model) {
            $class = ($model->status == '0') ? '' : 'link-disable';
            $name = 'Approve "' . Yii::$app->general->getforeignkey($model->userCode, 'name') . '" Regularization';
            return GhostHtml::a_alert('<i class="fa fa-check"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Approve', 'class' => 'deact-rate ' . $class, 'data-val' => $model->process_approval_code, 'data-name' => $name]);
        },
    ],
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div id="RejectedAttendance"></div>
<?php
$script = " $(document).ready(function(){
        $(document).on('click','.user-attendance-regularization',function(e){
            var id= $(this).attr('data-process_approval_code');
           
            $.ajax({
                type: 'get',
                url: '" . Url::to(['/tms/tbl-user-attendance-regularization/reject-regularization']) . "',
                data:{'id':id},
                success: function(data) {     
                    $('#RejectedAttendance').html(data);
                    $('#RejectedAttendanceModal').modal('toggle');    
                },    
                error: function(data) {    
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        });

    $(document).on('click','.deact-rate',function(e){
        var id= $(this).attr('data-val');
        var name = $(this).attr('data-name');
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to ' + name + '?</span></div></div>',
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
                        url: '" . Url::to(['approve-regularization']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#user-attendance-regularization-list'});
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
$this->registerJs($script, View::POS_END, 'user-attendance-regularization');
?>