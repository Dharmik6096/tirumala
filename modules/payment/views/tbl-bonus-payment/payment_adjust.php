<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$this->title = $title;
?>
<?php
$array = $dataProvider->getModels();
$final_amt = array_sum(array_map(function($array) {
            return $array['net_payable'];
        }, $array));
$mcc_data = $model->mccPlantCode;
$bmc_info = $mcc_data->mcc_plant_code . ' > ' . $mcc_data->name . ' > ';
$bmc_info .= Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') . ' > ' .
        (Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime) );
$message = Yii::t('app', 'Payment data will be Locked for (' . $bmc_info . '). Are you sure ?');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">      
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>  
            <div id="total-payment">
                Total Payable :: <?= $final_amt; ?>
            </div>
        </div>     
        <?php
        $form = ActiveForm::begin([
                    'id' => 'bonus-payment-adjust',
                    'validateOnBlur' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?= Html::hiddenInput('process_lock_flag', 'processed', ['class' => 'process_lock_flag']); ?>
        <?= Html::activeHiddenInput($model, 'from_datetime'); ?>
        <?= Html::activeHiddenInput($model, 'to_datetime'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
        <?php foreach ($model->bmc_code as $bmc_code) { ?>
            <?= Html::activeHiddenInput($model, 'bmc_code[]', ['value' => $bmc_code]); ?>
        <?php } ?>

        <?= Html::activeHiddenInput($model, 'customer_type'); ?>
        <?= Html::activeHiddenInput($model, 'payment_type'); ?>
        <?php
        $attribute = [
                ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
                ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
                }, 'filter' => false],
                ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                    return !empty($model->customer_name) ? $model->customer_name : Yii::$app->general->getCustomer($model, $model->customer_type);
                }],
                ['attribute' => 'kg_fat'],
                ['attribute' => 'kg_snf'],
                ['attribute' => 'qty', 'pageSummary' => true],
                ['attribute' => 'amount', 'pageSummary' => true],
                ['attribute' => 'addition', 'pageSummary' => true],
                ['attribute' => 'deduction', 'pageSummary' => true],
                ['attribute' => 'net_payable', 'pageSummary' => true],
        ];

        $grid_option = [
            'id' => 'bonus-payment-adjust-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'actions' => [
                'bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-val' => $model->bonus_payment_summary_code];
                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-bonus-payment/summary-bill-head', 'id' => $model->bonus_payment_summary_code], $options);
                },
                'payment-detail' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'target' => '_blank', 'data-original-title' => 'View Detail', 'data-val' => $model->bonus_payment_summary_code];
                    return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-bonus-payment/payment-detail', 'id' => $model->bonus_payment_summary_code], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
        ?>
    </div>
    <div class="panel-footer" >
        <?php
        if (!empty($dataProvider->getModels())) {
            echo Html::button(Yii::t('app', 'Save as Draft'), ['class' => 'btn btn-primary ', 'id' => 'adjust']);
            echo Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock-dcs-data']);
        }
        ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
    </div>
</div>
<?php ActiveForm::end(); ?>
<div id='bill_head_view'></div>
<?php
$script = "$('.kv-panel-before').hide();";
$script .= "$('#adjust-lock-dcs-data').click(function() {
            $('.process_lock_flag').val('locked');
            var message = '" . $message . "';
             bootbox.confirm({
                    message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+message+'</span>',
                    buttons: {
                        confirm: {
                            label: '" . Yii::t('app', 'Yes') . " ',
                            className: 'btn-primary'
                        },
                        cancel: {
                            label: '" . Yii::t('app', 'No') . "' ,
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){                           
                                 $('#loadercontent').show();
                                 $('#pageloader').show();
                                 postProcessData(); 
                        }
                    }
                });                          
});
$('#adjust').click(function() {
            $('.process_lock_flag').val('processed');
            $('#loadercontent').show();
            $('#pageloader').show();            
            postProcessData();               
            
});
function postProcessData(){
            var postProcessData = $('#bonus-payment-adjust').serializeArray();
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['payment-adjust']) . "',
                    data: postProcessData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 'success') {  
                            window.location=data.url;
                        } else {
                            $('#loadercontent').hide();
                            $('#pageloader').hide();
                            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+data.msg+\"</span></div></div>\");
                        }
                    },
                    error:function(data){
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                        return false;
                    }
                });
                return false; 
}
";
$script .= "$(document).ready(function(){
    $(document).on('click','.view-head',function(e){
    var id= $(this).attr('data-val');
  ViewBillHead(id);
    });
    function ViewBillHead(code){
        if(code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/payment/tbl-bonus-payment/summary-bill-head']) . "',
                data: {'code' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#bill_head_view').html(data);
                   $('#BillHeadModal').modal('toggle');              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
});";

$this->registerJs($script, View::POS_END, 'bonus-payment-adjust-script');
?>

