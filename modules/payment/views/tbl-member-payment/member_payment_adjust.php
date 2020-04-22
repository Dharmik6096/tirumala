<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Member Payment Process : Step 3');
$bmc_info = Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_code') . ' > ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' > ' .
        Yii::$app->controls->view_date($aliasModel->from_datetime) . ' to ' . Yii::$app->controls->view_date($aliasModel->to_datetime);
?>
<?php
$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['final_amount'];
        }, $array));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">      
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>   
            <div id="total-payment">
                Total Payable :: <?= $tot_amt; ?>
            </div>
        </div>     
        <?php
        $form = ActiveForm::begin([
                    'id' => 'payment-adjust',
                    'validateOnBlur' => TRUE,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php
        $attribute = [
                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                }],
                ['attribute' => 'dcs_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }],
                ['attribute' => 'member_code', 'value' => function($model) {
                    return substr($model->member_code, -4);
                }, 'label' => Yii::t('app', 'Member Code')],
                ['attribute' => 'member_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                }],
                ['attribute' => 'kg_fat'],
                ['attribute' => 'kg_snf'],
                ['attribute' => 'qty', 'pageSummary' => true],
                ['attribute' => 'total_amount', 'value' => 'total_amount', 'pageSummary' => true],
                ['attribute' => 'total_addition', 'value' => 'total_addition', 'pageSummary' => true],
                ['attribute' => 'total_deduction', 'value' => 'total_deduction', 'pageSummary' => true],
                ['attribute' => 'previous_hold', 'pageSummary' => true],
                ['attribute' => 'previous_due', 'pageSummary' => true],
                ['attribute' => 'net_payable', 'pageSummary' => true, 'contentOptions' => ['class' => 'final-amount'],],
                ['attribute' => 'hold_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'member_payment_alias_code[' . $index . ']', ['value' => $model->member_payment_alias_code]) . $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
                },
            ],
                ['attribute' => 'additional_pay',
                'format' => 'raw',
                //  'pageSummary' => true,
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']) . $form->field($model, 'additional_pay[' . $index . ']')->textInput(['value' => $model->additional_pay, 'class' => 'adjust-amount form-control cal-amount number-validate',])->label(FALSE);
                },
            ],
                ['attribute' => 'final_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                //  'pageSummary' => true,
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'final_amount[' . $index . ']')->textInput(['class' => 'net-amount form-control', "disabled" => TRUE, 'value' => $model->final_amount])->label(FALSE);
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
            'id' => 'member-payment-adjust-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
        ];

        Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
        ?>
    </div>
    <div class="panel-footer" >
        <?php //Yii::$app->controls->save('Confirm', $model);        ?>
        <?= Html::button(Yii::t('app', 'Process'), ['class' => 'btn btn-primary ', 'id' => 'adjust']); ?>
        <?= Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?> 
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = '$("#adjust").click(function() {
    $(".process_lock_flag").val("Process");
    $("#payment-adjust").submit();
});
$("#adjust-lock").click(function() {
    $(".process_lock_flag").val("Lock");
    $("#payment-adjust").submit();
});
';
$script .= " 
    
    $('.cal-amount').on('blur',function(){
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
        parent.find('.net-amount').val('');
        if(adjust == '' ||  isNaN(adjust)){
            adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
            hold=0;
        }
        var net = final + adjust - hold;  
        if(net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(net != '' &&  !isNaN(net)){
         parent.find('.net-amount').val(net.toFixed(2));
          SumAmount();
        }
       }
    });



function SumAmount()
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
 }     


$('.adjust-amountasd').on('blur',function(){     
        var adjust = parseFloat($(this).val());
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var final = parseFloat(parent.find('.final-amount').text());
         parent.find('.net-amount').val('');
        var net = final + adjust ;  
        if(net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Adjust Amount should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(net != '' &&  !isNaN(net)){
         parent.find('.net-amount').val(net.toFixed(2));
          SumAmount();
        }
       }
    });";
$script .= " function SumAmountold()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
        total=$tot_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }      ";

$this->registerJs($script, View::POS_END, 'payment-adjust-script');
?>
<?php
$script = "$('.kv-panel-before').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>