<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Farmer Payment Process : Step 3';
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
            <?= $this->title ?>  
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
                ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name'],
                ['attribute' => 'member_code', 'value' => 'memberCode.member_name'],
                ['attribute' => 'qty'],
                ['attribute' => 'avg_fat'],
                ['attribute' => 'avg_snf'],
                ['attribute' => 'avg_rate'],
                ['attribute' => 'total_amount', 'value' => 'total_amount',
                // 'hAlign' => Yii::$app->general->ColoumnAlign(),
                // 'format' => Yii::$app->general->CurrencyFormat(),
                'pageSummary' => true
            ],
                ['attribute' => 'total_deduction', 'value' => 'total_deduction',
                // 'hAlign' => Yii::$app->general->ColoumnAlign(),
                //  'format' => Yii::$app->general->CurrencyFormat(),
                'pageSummary' => true
            ],
                ['attribute' => 'final_amount', 'value' => 'final_amount',
                'value' => function ($model) {
                    return $model->total_amount - $model->total_deduction;
                },
                //  'hAlign' => Yii::$app->general->ColoumnAlign(),
                //   'format' => Yii::$app->general->CurrencyFormat(),
                'pageSummary' => true,
                'contentOptions' => ['class' => 'final-amount'],
            ],
                ['attribute' => 'adjust_amount',
                'format' => 'raw',
                //  'pageSummary' => true,
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']) . Html::activeHiddenInput($model, 'member_payment_alias_code[' . $index . ']', ['value' => $model->member_payment_alias_code]) . $form->field($model, 'adjust_amount[' . $index . ']')->textInput(['value' => $model->adjust_amount, 'class' => 'adjust-amount form-control',])->label(FALSE);
                },
            ],
                ['attribute' => 'net_amount',
                'format' => 'raw',
                //  'pageSummary' => true,
                'value' => function ($model, $key, $index) use ($form) {
                    $val = !empty($model->adjust_amount) ? $model->adjust_amount + $model->total_amount - $model->total_deduction : '';
                    return $form->field($model, 'net_amount[' . $index . ']')->textInput(['class' => 'net-amount form-control', "disabled" => TRUE, 'value' => $val])->label(FALSE);
                },
            ],
                ['attribute' => 'adjust_remark',
                'format' => 'raw',
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
        <?php //Yii::$app->controls->save('Confirm', $model);      ?>
        <?= Html::button(Yii::t('app', 'Process'), ['class' => 'btn btn-primary ', 'id' => 'adjust']); ?>
        <?= Html::button(Yii::t('app', 'Process and Lock'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
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
$script .= " $('.adjust-amount').on('blur',function(){     
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
$script .= " function SumAmount()
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