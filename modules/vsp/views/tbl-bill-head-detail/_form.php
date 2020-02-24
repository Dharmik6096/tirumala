<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$form = ActiveForm::begin([
            'options' => ['id' => 'bill-head-detail-form'],
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
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbillheaddetail-union_code', 'plant_code', TRUE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbillheaddetail-plant_code', 'mcc_plant_code', TRUE); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbillheaddetail-mcc_plant_code', 'bmc_code', TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_type($model, $form, 'tblbillheaddetail-bmc_code', 'customer_type', TRUE, FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_code($model, $form, 'tblbillheaddetail-bmc_code,tblbillheaddetail-customer_type', 'customer_code', TRUE, FALSE); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-sm-2">
        <?php
        $where = json_encode(['data_lock_bmc' => 0]);
        echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
        echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
        ?>
        <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblbillheaddetail-union_code,tblbillheaddetail-bmc_code,tblbillheaddetail-customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2 reset_field">
        <?= Yii::$app->dropdown->billHead($model, $form, 'tblbillheaddetail-union_code,tblbillheaddetail-customer_type,tblbillheaddetail-customer_code', 'bill_head_code', $model->getAttributeLabel('bill_head_code')); ?>       
    </div>
    <div class="col-sm-2 number-validate reset_field">
        <?= $form->field($model, 'amount')->textInput() ?>       
    </div>
    <div class="col-sm-2 reset_field">
        <?= $form->field($model, 'no_installment')->textInput() ?>       
    </div>
    <div class="col-sm-2 reset_field">
        <?= $form->field($model, 'installment_amount')->textInput(['class' => 'form-control', 'min' => 0]) ?>       
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Save'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
//                                                                  $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $("#bill-head-detail-form .reset_field input").val("");
                                                                    $("#bill-head-detail-form .reset_field select").val("");
                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblbillheaddetail-bill_head_code").focus();},100);
                                                                    });
                                                                }else{
                                                                
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
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
     $(document).on('change', '#tblbillheaddetail-bmc_code', function() {  
        reloadGrid();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/vsp/tbl-bill-head-detail/list-grid']) . "'+ '?' + $('#bill-head-detail-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet').html(data);
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    
";
$this->registerJs($script, View::POS_END, 'bill-head-detail-form');
?>