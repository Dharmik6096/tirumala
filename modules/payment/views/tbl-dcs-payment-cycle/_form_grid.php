<?php
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],    
    //'dcs_payment_cycle_code',
    ['attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
        return Yii::$app->controls->view_date($model->from_date);
    }],
    ['attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
        return Yii::$app->controls->view_date($model->to_date);
    }],
    'interval_value',
    ['attribute' => 'lock_data', 'filter' => array('1' => 'Yes','0' => 'No'), 
        'value' => function($model) { return ($model->lock_data == 1)? 'Yes' : 'No';
    }],
];

$grid_option = [
    'id' => 'payment-cycle-grid',
    'attributes' => $attribute,
    'active_column' => TRUE,
    'actions' => [        
        'view' => true,
        'applicabilty' => function ($url, $model) {       
        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
        return GhostHtml::a('<i class="fa fa-plus"></i>', ['/payment/tbl-dcs-payment-cycle/payment-cycle-applicability', 'id' => $model->dcs_payment_cycle_code], $options);
        },
        'data_lock' => function ($url, $model) {
            $url=Url::to(['lock-payment-cycle']);
            $name=Yii::$app->controls->view_date($model->from_date).' - '.Yii::$app->controls->view_date($model->to_date);    
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Lock Data','class'=>'lock-data','data-val'=>$model->dcs_payment_cycle_code,'data-name'=>$name,'data-url'=>$url,'data-flag'=>'data'];
            if($model->lock_data==0)
            return GhostHtml::a_alert('<i class="fa fa-lock"></i>', ['/payment/tbl-dcs-payment-cycle/lock-payment-cycle'], $options);
        },
        'bill_lock' => function ($url, $model) {
        $url=Url::to(['lock-payment-cycle']);
        $name=Yii::$app->controls->view_date($model->from_date).' - '.Yii::$app->controls->view_date($model->to_date);    
        
        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Lock Billing','class'=>'lock-bill','data-val'=>$model->dcs_payment_cycle_code,'data-name'=>$name,'data-url'=>$url,'data-flag'=>'billing'];
        if($model->lock_data==1 && $model->lock_billing_process==0)
            return GhostHtml::a_alert('<i class="fa fa-expeditedssl"></i>', ['/payment/tbl-dcs-payment-cycle/lock-payment-cycle'], $options);
        },
        'delete' => ['option' => 'dcs_payment_cycle_code,dcs_payment_cycle_code,tbl-dcs-payment-cycle/delete,disableDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php
$script = "

$(document).ready(function(){
    $(document).on('click','.lock-data,.lock-bill',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    var url= $(this).attr('data-url');
    var flg= $(this).attr('data-flag');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to lock '+flg+' for \"'+name+'\"?</span></div></div>',
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
                        type: 'post',
                        url: url,
                        data:{'flag':flg,'id':id},
                        success: function(data) {

                            var obj1 = $.parseJSON(data);
                            console.log(obj1);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#payment-cycle-grid'});
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
$this->registerJs($script, View::POS_END, 'cycle-index');