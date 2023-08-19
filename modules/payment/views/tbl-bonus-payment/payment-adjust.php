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
<div id='add_recovery_data'></div>
<?php
$script = "$('#adjust-lock-dcs-data').click(function() {
            $('.process_lock_flag').val('locked');
            $('#loadercontent').show();
            $('#pageloader').show();
            postVspProcessData();                           
});
$('#adjust').click(function() {
            $('.process_lock_flag').val('processed');
            $('#loadercontent').show();
            $('#pageloader').show();            
            postVspProcessData();               
            
});
function postVspProcessData(){
            var postVspProcessData = $('#payment-adjust').serializeArray();
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['payment-adjust']) . "',
                    data: postVspProcessData,
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
                            //alert('Your data has not been submitted..Please try again');
                    }
                });
                return false; 
                $('#payment-adjust').submit();
}
";
$script .= " $('.cal-amount').on('blur',function(){     
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
        var adjust_recovery = parseFloat(parent.find('.adjust-recovery').text());
        var recovery = parseFloat(parent.find('.recovery').text());


        if(adjust == '' ||  isNaN(adjust)){
        adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
        hold=0;
        }
        if(adjust_recovery == '' ||  isNaN(adjust_recovery)){
        adjust_recovery=0;
        }
        if(recovery == '' ||  isNaN(recovery)){
        recovery=0;
        }        

        var net = final + adjust - hold + adjust_recovery - recovery;  
         parent.find('.net-amount').val(net.toFixed(2));
         SumAmount(); 
//       if(net != '' && net < 0){
//         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be Negative.</span>',function(){
//                bootbox.hideAll();
//                    $('#'+id).focus().select();
//            });
//            return false;
//        } else {                   
//          SumAmount();    
//       }
    });";
$script .= " function SumAmount()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
   $('.hold-amount').each(function() {
      var hold =  parseFloat($(this).val());
  if(hold != '' &&  !isNaN(hold)){
          total = total - hold;  
          }
        }).get();
        total=$tot_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }      ";

$script .= "$(document).ready(function(){
    $(document).on('click','.view-head',function(e){
    var id= $(this).attr('data-val');
  ViewBillHead(id);
    });
    function ViewBillHead(code){
        if(code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/payment/tbl-vsp-payment/bill-head']) . "',
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
$script .= "$(document).ready(function(){
    $(document).on('click','.add-recovery',function(e){
    var id= $(this).attr('data-val');
         AddRecoveryData(id);
    });
    function AddRecoveryData(code){
        if(code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/payment/tbl-vsp-payment/add-recovery']) . "',
                data: {'code' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#add_recovery_data').html(data);
                   $('#RecoveryModal').modal('toggle');              
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
$script .= "$(document).on('blur','.cal-recovery',function(e){
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var rec = parseFloat(parent.find('.recovery-amount').text());
        var old_rec = parseFloat(parent.find('.old-recovery').text());
        var new_rec = parseFloat(parent.find('.new-recovery').val());
        var tot_rec = parseFloat(parent.find('.total-recovery').text());
        if(rec == '' ||  isNaN(rec)){
        rec=0;
        }
        if(new_rec == '' ||  isNaN(new_rec)){
        new_rec=0;
        }
        if(old_rec == '' ||  isNaN(old_rec)){
        old_rec=0;
        }
         tot_rec = rec + new_rec - old_rec;  
         parent.find('.total-recovery').text(tot_rec.toFixed(2));       
    });";

$this->registerJs($script, View::POS_END, 'payment-adjust-script');
?>
<?php
$script = "$('.kv-panel-before').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>

<?php
$script = "$(document).on('click','#add-installment',function(e){
        $('#error-summary').hide();
        var paymentData = [];
            $('#vendor-installment-form .checkbox-installment').each(function () {
             if(this.checked){
                    paymentData.push($(this).val()); 
                }
            });
       var skipHead=$('#vendor-head-skip-form').serialize();     
        $.ajax({
                    type: 'post',
                    url: $('#vendor-head-skip-form').attr('action'),
                    data: 'paymentData='+paymentData+'&'+skipHead,
                    success: function(data) {
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            location.reload();
                        }else{
                           bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.msg+'</span>');
                        }
                    }
                });           
    })";
$this->registerJs($script, View::POS_END, 'vendor-installment-form-submit');
?>
