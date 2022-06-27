<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use app\modules\collection\models\TblMccShiftLock;
use app\modules\collection\models\TblMccShiftLockStaging;
use webvimark\modules\UserManagement\models\User;
?>

<?php

$attribute = [
        ['attribute' => 'mcc_plant_code', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'ref_code', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'mcc', 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Collection Date'), 'attribute' => 'date_time_of_collection',
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model['date_time_of_collection']);
//        }, 
        'filter' => FALSE],
        ['attribute' => 'shift', 'filter' => false],
        ['attribute' => 'qty', 'filter' => FALSE],
        ['attribute' => 'avgFAT', 'filter' => FALSE],
        ['attribute' => 'avgSNF', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => FALSE],
        ['attribute' => 'member_lock', 'label' => Yii::t('app', 'Member Data'), 'value' => function($model) {
            $class = $model['member_lock'] == 1 ? 'fa-unlock' : 'fa-lock';
            $title = $model['member_lock'] == 1 ? 'Data Unlock - Member' : 'Data Lock - Member';
            $url = $model['member_lock'] == 1 ? '/collection/tbl-mcc-shift-lock/member-data-unlock' : '/collection/tbl-mcc-shift-lock/member-data-lock';
            $popupClass = ''; //' disabled ';
            if (User::canRoute($url)) {
                $popupClass = ' generalGridConfirmationPopup ';
            }
            $popupWindowTitle = 'Are you sure you want to ' . ($model['member_lock'] == 1 ? 'Unlock Member Data' : 'Lock Member Data');
            $options = [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'data-original-title' => $title,
                'data-popup-message' => $popupWindowTitle,
                'class' => $popupClass,
                'data-post-url' => Url::to([$url, 'mcc' => $model['mcc_plant_code'], 'date' => date('Y-m-d', strtotime($model['date_time_of_collection'])), 'shift' => $model['shift_code'], 'qty' => $model['qty'], 'fat' => $model['avgFAT'], 'snf' => $model['avgSNF'], 'amount' => $model['amount']])
            ];
            return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'mcc_plant_code' => $model['mcc_plant_code'], 'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($model['date_time_of_collection']))], $options);
        },
        'format' => 'raw',
        'contentOptions' => function($model) {
            return ['class' => 'text-center'];
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'shift-lock-member-list',
    'attributes' => $attribute,
    'active_column' => false,
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
                        url: '" . Url::to(['lock-data-other']) . "',
                        data:{'mcc':mcc,'date':date,'union':union,'plant':plant,'shift':shift,'qty':qty,'amount':amount,'fat':fat,'snf':snf},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#shift-lock-member-list'});
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
                        url: '" . Url::to(['unlock-other']) . "',
                         data:{'mcc':mcc,'date':date,'union':union,'plant':plant,'shift':shift,'qty':qty,'amount':amount,'fat':fat,'snf':snf},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#shift-lock-member-list'});
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
$this->registerJs($script, View::POS_END, 'shift-lock-member');
?>