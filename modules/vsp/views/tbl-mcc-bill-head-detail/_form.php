<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$form = ActiveForm::begin([
            'options' => ['id' => 'mcc-bill-head-detail-form'],
            'validateOnBlur' => false,
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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmccbillheaddetail-union_code', 'plant_code', TRUE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmccbillheaddetail-plant_code', 'mcc_plant_code', TRUE); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmccbillheaddetail-mcc_plant_code', 'bmc_code', TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', '', false, FALSE, true); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2 reset_field">
        <?= Yii::$app->dropdown->mccBillHead($model, $form, 'tblmccbillheaddetail-union_code,tblmccbillheaddetail-bmc_code', 'mcc_bill_head_code', $model->getAttributeLabel('mcc_bill_head_code')); ?>       
    </div>

    <div class="col-sm-1 number-validate reset_field">
        <?= $form->field($model, 'amount')->textInput() ?>       
    </div>
    <div class="col-sm-2 reset_field">
        <?= $form->field($model, 'no_installment')->textInput() ?>       
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'installment_amount')->textInput(['class' => 'form-control', 'min' => 0]) ?>       
    </div>

    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Save'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){
                                                $('.error-summary').hide();
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
                                                                    $("#mcc-bill-head-detail-form .reset_field input").val("");
                                                                    $("#mcc-bill-head-detail-form .reset_field select").val("");
                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                    $("#tblmccbillheaddetail-mcc_bill_head_code").val("");
                                                                    $("#tblmccbillheaddetail-mcc_bill_head_code").trigger("change");
                                                                    $("#tblmccbillheaddetail-mcc_bill_head_code").focus();},100);
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
                'options' => ['class' => 'btn-login btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel('', ['tbl-mcc-bill-head-detail/index']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
    $(document).ready(function(){
//        $('#tblmccbillheaddetail-no_installment').prop('disabled', true);
        $('#tblmccbillheaddetail-installment_amount').prop('disabled', true); 
    });
//     $('#tblmccbillheaddetail-bill_head_code').on('change',function(){
//     var bill_head_code= $(this).val();
//     if(bill_head_code !=''){
//        $.ajax({
//            type: 'post',
//            url: '" . Url::to(['/vsp/tbl-bill-head-detail/bill-head-type']) . "',
//            data: {'bill_head_code' : bill_head_code},            
//            success: function(data) {
//                var type = $.parseJSON(data);
//                if(type.status == 'success' && type.data.bill_head_type==0){
//                     $('#tblmccbillheaddetail-no_installment').prop('disabled', false);
//                }
//                 else {
//                     $('#tblmccbillheaddetail-no_installment').prop('disabled', true);
//                     $('#tblmccbillheaddetail-no_installment').val('');
//                     $('#tblmccbillheaddetail-installment_amount').val('');
//                 }
//            },
//        });
//      } else {
//            $('#tblmccbillheaddetail-no_installment').prop('disabled', true);
//            $('#tblmccbillheaddetail-no_installment').val('');
//            $('#tblmccbillheaddetail-installment_amount').val('');
//      }
//    });
  
    $('#tblmccbillheaddetail-no_installment').on('change',function(){
        dispDefBillHead();
    });
    $('#tblmccbillheaddetail-amount').on('change',function(){
        dispDefBillHead();
    });
    
    function dispDefBillHead(){
        var amount = $('#tblmccbillheaddetail-amount').val();
        var install = $('#tblmccbillheaddetail-no_installment').val();
        if(amount != '' && install != '' && install != 0){
            amount = parseFloat(amount);
            install = parseFloat(install);
            if(!isNaN(amount) && !isNaN(install)){
                var inst = '';
                inst=amount/install;
                $('#tblmccbillheaddetail-installment_amount').val(inst.toFixed(2));
            } else {
                $('#tblmccbillheaddetail-installment_amount').val('');
            }
        }
        else{
            $('#tblmccbillheaddetail-installment_amount').val('');
        }
    }
     $(document).on('change', '#tblmccbillheaddetail-transaction_date', function() {  
        reloadGrid();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/vsp/tbl-mcc-bill-head-detail/list-grid']) . "'+ '?' + $('#mcc-bill-head-detail-form').serialize();
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
$this->registerJs($script, View::POS_END, 'mcc-bill-head-detail-form');
?>