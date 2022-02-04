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
        ['attribute' => 'bmc_lock', 'label' => Yii::t('app', 'BMC Data'), 'value' => function($model) {

            $class = $model['bmc_lock'] == 1 ? 'fa-unlock' : 'fa-lock';
            $title = $model['bmc_lock'] == 1 ? 'Data Unlock - BMC' : 'Data Lock - BMC';
            $url = $model['bmc_lock'] == 1 ? '/collection/tbl-mcc-shift-lock/bmc-data-unlock' : '/collection/tbl-mcc-shift-lock/bmc-data-lock';
            $popupClass = ''; //' disabled ';
            if (User::canRoute($url)) {
                $popupClass = ' generalGridConfirmationPopup ';
            }
            $popupWindowTitle = 'Are you sure you want to ' . ($model['bmc_lock'] == 1 ? 'Unlock BMC Data' : 'Lock BMC Data');
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
        ['attribute' => 'product_sale_lock', 'label' => Yii::t('app', 'Product Sale Data'), 'value' => function($model) {
            $class = $model['product_sale_lock'] == 1 ? 'fa-unlock' : 'fa-lock';
            $title = $model['product_sale_lock'] == 1 ? 'Data Unlock - Product Sale' : 'Data Lock - Product Sale';
            $url = $model['product_sale_lock'] == 1 ? '/collection/tbl-mcc-shift-lock/product-sale-unlock' : '/collection/tbl-mcc-shift-lock/product-sale-lock';
            $popupClass = ''; //' disabled ';
            if (User::canRoute($url)) {
                $popupClass = ' generalGridConfirmationPopup ';
            }
            $popupWindowTitle = 'Are you sure you want to ' . ($model['product_sale_lock'] == 1 ? 'Unlock Product Sale Data' : 'Lock Product Sale Data');
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
                $options = ['data-union' => $model['union_code'], 'data-plant' => $model['plant_code'], 'data-mcc' => $model['mcc_plant_code'], 'data-date' => $model['date_time_of_collection'], 'data-shift' => $model['shift_code'], 'data-qty' => $model['qty'], 'data-fat' => $model['avgFAT'], 'data-snf' => $model['avgSNF'], 'data-amount' => $model['amount'], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'ALL DATA UN-LOCK', 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-unlock"></i>', ['/collection/tbl-mcc-shift-lock/unlock-data-other'], $options);
            } else {
                $class = 'unlock'; //!empty($notAllowUnlock) ? 'unlock disabled' : 'unlock';
                $options = ['data-union' => $model['union_code'], 'data-plant' => $model['plant_code'], 'data-mcc' => $model['mcc_plant_code'], 'data-date' => $model['date_time_of_collection'], 'data-shift' => $model['shift_code'], 'data-qty' => $model['qty'], 'data-fat' => $model['avgFAT'], 'data-snf' => $model['avgSNF'], 'data-amount' => $model['amount'], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'ALL DATA LOCK', 'class' => '' . $class];
                return GhostHtml::a_alert('<i class="fa fa-lock"></i>', ['/collection/tbl-mcc-shift-lock/lock-data-other'], $options);
            }
        },
//        'view-shift' => function ($url, $model) {
//            $lockModel = new TblMccShiftLock();
//            $data = $lockModel->getStatus($model);
//            $class = !empty($data) ? '' : 'disabled';
//            $url = Url::to(['tbl-mcc-shift-lock/view-other', 'mcc' => $model['mcc_plant_code'], 'date' => $model['date_time_of_collection'], 'shift' => $model['shift_code']]);
//            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => '' . $class]);
//        },
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
                        url: '" . Url::to(['lock-data-other']) . "',
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
                        url: '" . Url::to(['unlock-other']) . "',
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