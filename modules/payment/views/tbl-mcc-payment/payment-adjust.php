<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

$this->title = $title;
?>
<?php
$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['net_payable'] + $array['adjust_recovery'] - $array['recovery'];
        }, $array));
$final_amt = array_sum(array_map(function($array) {
            return $array['net_payable'] + $array['adjust_recovery'] - $array['recovery'] + $array['adjust_amount'] - $array['hold_amount'];
        }, $array));



$bmc_info = Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime)
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
                    'id' => 'payment-adjust',
                    'validateOnBlur' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php
        $attribute = [
            ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC Code'), 'filter' => false],
            ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'bmc_code',
                'label' => Yii::t('app', 'BMC Code'),
                'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'bmc_name',
                'label' => Yii::t('app', 'BMC Name'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'total_qty', 'value' => 'total_qty',
                'pageSummary' => true
            ],
            ['attribute' => 'amount', 'value' => 'amount',
                'pageSummary' => true
            ],
            ['attribute' => 'addition', 'value' => 'addition',
                'pageSummary' => true
            ],
            ['attribute' => 'deduction', 'value' => 'deduction',
                'pageSummary' => true
            ],
            ['attribute' => 'previous_hold', 'pageSummary' => true
            ],
            ['attribute' => 'previous_due', 'pageSummary' => true
            ],
            ['attribute' => 'final_pay', 'value' => 'net_payable',
                'pageSummary' => true,
                'contentOptions' => ['class' => 'final-amount'],
            ],
            ['attribute' => 'hold_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'mcc_payment_code[' . $index . ']', ['value' => $model->mcc_payment_code]) . $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
                },
            ],
            ['attribute' => 'adjust_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'mcc_payment_code[' . $index . ']', ['value' => $model->mcc_payment_code]) . $form->field($model, 'adjust_amount[' . $index . ']')->textInput(['value' => $model->adjust_amount, 'class' => 'number-validate adjust-amount cal-amount form-control',])->label(FALSE);
                },
            ],
            ['attribute' => 'net_payable',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'net_payable[' . $index . ']')->textInput(['class' => 'number-validate net-amount form-control', "disabled" => TRUE, 'value' => $model->final_pay])->label(FALSE);
                },
            ],
            ['attribute' => 'adjust_remark',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'adjust_remark[' . $index . ']')->textInput(['value' => $model->adjust_remark])->label(FALSE);
                },
            ],
        ];

        $grid_option = [
            'id' => 'vsp-payment-adjust-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'actions' => [
                'bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-val' => $model->mcc_payment_code];
                    return GhostHtml::a_alert('<i class="fa fa fa-money-bill"></i>', ['/payment/tbl-mcc-payment/bill-head', 'id' => $model->mcc_payment_code], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
        ?>
    </div>
    <div class="panel-footer" >

        <?php
        if (!empty($dataProvider->getModels())) {
            echo Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust']);
        }
        ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
    </div>
</div>
<?php ActiveForm::end(); ?>
<div id='bill_head_view'></div>
<div id='add_recovery_data'></div>
<?php
$script = "$('#adjust').click(function() {
            $('#loadercontent').show();
            $('#pageloader').show();
            var postVspProcessData = $('#payment-adjust').serializeArray();
            var data_ok=1;
            $('.net-amount').each(function() {
                var netamount =  parseFloat($(this).val());
                if(netamount<0){
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                    data_ok=0;
                    bootbox.alert(
                    \"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Net Payable should not be Negative.</span></div></div>\",function(){
                        bootbox.hideAll();
                    });
                    return false; 
                }
            });
            if(data_ok==1){
                
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
});
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
                url: '" . Url::to(['/payment/tbl-mcc-payment/bill-head']) . "',
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
                url: '" . Url::to(['/payment/tbl-mcc-payment/add-recovery']) . "',
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
