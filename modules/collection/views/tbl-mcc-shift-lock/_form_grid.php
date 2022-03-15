<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use app\modules\collection\models\TblMccShiftLock;
use app\modules\collection\models\TblMccShiftLockStaging;
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
    ['attribute' => 'amount', 'filter' => FALSE],
];

$grid_option = [
    'id' => 'shift-lock-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'status' => function ($url, $model) {
            $lockModel = new TblMccShiftLock();
            $data = $lockModel->getStatus($model);
            $stagModel = new TblMccShiftLockStaging();
            $notAllowUnlock = '';
            if (!empty($data)) {
                $notAllowUnlock = $stagModel->find()->where(['shift_lock_code' => $data->shift_lock_code, 'data_post_status' => 1])->one();
            }
            if (!empty($data) && $data->data_lock == 1) {
                $class = 'lock';
                $options = ['data-union' => $model['union_code'], 'data-plant' => $model['plant_code'], 'data-mcc' => $model['mcc_plant_code'], 'data-date' => $model['date_time_of_collection'], 'data-shift' => $model['shift_code'], 'data-qty' => $model['qty'], 'data-fat' => $model['avgFAT'], 'data-snf' => $model['avgSNF'], 'data-amount' => $model['amount'], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'LOCK', 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-lock"></i>', ['/collection/tbl-mcc-shift-lock/unlock-data'], $options);
            } else {
                $class = 'unlock'; //!empty($notAllowUnlock) ? 'unlock disabled' : 'unlock';
                $options = ['data-union' => $model['union_code'], 'data-plant' => $model['plant_code'], 'data-mcc' => $model['mcc_plant_code'], 'data-date' => $model['date_time_of_collection'], 'data-shift' => $model['shift_code'], 'data-qty' => $model['qty'], 'data-fat' => $model['avgFAT'], 'data-snf' => $model['avgSNF'], 'data-amount' => $model['amount'], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'UN-LOCK', 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-unlock"></i>', ['/collection/tbl-mcc-shift-lock/lock-data'], $options);
            }
        },
        'view-shift' => function ($url, $model) {
            $lockModel = new TblMccShiftLock();
            $data = $lockModel->getStatus($model);
            $class = !empty($data) ? '' : 'disabled';
            $url = Url::to(['tbl-mcc-shift-lock/view', 'mcc' => $model['mcc_plant_code'], 'date' => $model['date_time_of_collection'], 'shift' => $model['shift_code']]);
            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => '' . $class]);
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
    var qty = $(this).attr('data-qty');
    var amount = $(this).attr('data-amount');
    var fat = $(this).attr('data-fat');
    var snf = $(this).attr('data-snf');
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
                        data:{'mcc':mcc,'date':date,'union':union,'plant':plant,'shift':shift,'qty':qty,'amount':amount,'fat':fat,'snf':snf},
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
    var qty = $(this).attr('data-qty');
    var amount = $(this).attr('data-amount');
    var fat = $(this).attr('data-fat');
    var snf = $(this).attr('data-snf');
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
                         data:{'mcc':mcc,'date':date,'union':union,'plant':plant,'shift':shift,'qty':qty,'amount':amount,'fat':fat,'snf':snf},
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