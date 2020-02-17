<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->unionpaymentcycle($model, $form, 'tblbillheaddetail-union_code', 'payment_cycle_code', 'Payment Cycle'); ?>

    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->unionpaymentcycledcs($model, $form, 'tblbillheaddetail-payment_cycle_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->bill_head($model, $form, 'tblbillheaddetail-dcs_code', 'bill_head_code', 'Bill Head', 'D'); ?>       
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'amount')->textInput() ?>       
    </div>

    <div class="col-sm-3">
        <?= $form->field($model, 'no_installment')->textInput() ?>       
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'installment_amount')->textInput(['class' => 'form-control', 'min' => 0]) ?>       
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
    $(document).ready(function(){
         $('#tblbillheaddetail-no_installment').prop('disabled', true);
        $('#tblbillheaddetail-installment_amount').prop('disabled', true); 
    });
     $('#tblbillheaddetail-bill_head_code').on('change',function(){
     var bill_head_code= $(this).val();
     if(bill_head_code !=''){
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/vsp/tbl-bill-head-detail/bill-head-type']) . "',
            data: {'bill_head_code' : bill_head_code},            
            success: function(data) {
                var type = $.parseJSON(data);
                if(type.status == 'success' && type.data.bill_head_type==0){
                     $('#tblbillheaddetail-no_installment').prop('disabled', false);
                }
                 else {
                     $('#tblbillheaddetail-no_installment').prop('disabled', true);
                     $('#tblbillheaddetail-no_installment').val('');
                     $('#tblbillheaddetail-installment_amount').val('');
                 }
            },
        });
      } else {
            $('#tblbillheaddetail-no_installment').prop('disabled', true);
            $('#tblbillheaddetail-no_installment').val('');
            $('#tblbillheaddetail-installment_amount').val('');
      }
    });
  
    $('#tblbillheaddetail-no_installment').on('change',function(){
        dispDefBillHead();
    });
    $('#tblbillheaddetail-amount').on('change',function(){
        dispDefBillHead();
    });
    
    function dispDefBillHead(){
        var amount = $('#tblbillheaddetail-amount').val();
        var install = $('#tblbillheaddetail-no_installment').val();
        if(amount != '' && install != '' && install != 0){
            amount = parseFloat(amount);
            install = parseFloat(install);
            if(!isNaN(amount) && !isNaN(install)){
                var inst = '';
                inst=amount/install;
                $('#tblbillheaddetail-installment_amount').val(inst.toFixed(2));
            } else {
                $('#tblbillheaddetail-installment_amount').val('');
            }
        }
        else{
            $('#tblbillheaddetail-installment_amount').val('');
        }
    }
    
";
$this->registerJs($script, View::POS_END, 'bill-head-detail-form');
?>