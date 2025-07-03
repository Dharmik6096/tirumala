<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$form = ActiveForm::begin([
            'options' => ['id' => 'member-bill-head-transaction-form'],
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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbillheadtransaction-union_code', 'plant_code', TRUE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbillheadtransaction-plant_code', 'mcc_plant_code', TRUE); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbillheadtransaction-mcc_plant_code', 'bmc_code', TRUE); ?>
    </div>
    <div class="col-sm-2" style="display:none">
        <?= Html::activeTextInput($model, 'customer_type'); ?>
        <?= $form->field($model, 'customer_code')->textInput(); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', '', false, FALSE, true); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_code($model, $form, 'tblbillheadtransaction-bmc_code,tblbillheadtransaction-customer_type', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE); ?>
    </div>
    <div class="clearfix"></div>
    <?php
    echo Html::hiddenInput('head_for', 'MEMBER', ['id' => 'head_for']);
    ?>
    <div class="col-sm-2 reset_field">
        <?= Yii::$app->dropdown->billHead($model, $form, 'tblbillheadtransaction-union_code,tblbillheadtransaction-customer_type,tblbillheadtransaction-dcs_code,head_for', 'bill_head_code', $model->getAttributeLabel('bill_head_code')); ?>       
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'member_code')->textInput()->label('Code') ?>
    </div>
    <div class="col-sm-2 reset_field">
        <?= $form->field($model, 'customer_name')->textInput(['readonly' => true])->label('Name') ?>
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
                    'url' => Url::to(['create-member-bill-detail']),
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
                                                                    $("#member-bill-head-transaction-form .reset_field input").val("");
                                                                    $("#member-bill-head-transaction-form .reset_field select").val("");
                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblbillheadtransaction-bill_head_code").focus();},100);
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
            <?= Yii::$app->controls->cancel('', ['tbl-bill-head-transaction/index']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
    $(document).ready(function(){
//        $('#tblbillheadtransaction-no_installment').prop('disabled', true);
        $('#tblbillheadtransaction-installment_amount').prop('disabled', true); 
    });
    
    $(document).on('change', '#tblbillheadtransaction-member_code', function() {  
        $('#tblbillheadtransaction-customer_code').val('');
            $('#tblbillheadtransaction-customer_name').val('');
        setMemberCode();
    });
       
    function setMemberCode(){
        var dcs = $('#tblbillheadtransaction-dcs_code').val();
        var code = $('#tblbillheadtransaction-member_code').val();
        if(dcs != '' && dcs != null && dcs != undefined && code != ''){
            var member_code = dcs.concat(code);
            $('#tblbillheadtransaction-customer_code').val(member_code);
             $.ajax({
            type: 'post',
            url:'" . Url::to(['validate-member']) . "',
            data: {'member_code':member_code},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success')
                {
                    $('#tblbillheadtransaction-customer_name').val(obj.member_details.member_name);
                }else{
                 bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Please enter valid Member Code(Last 4 digit).') . "</span></div></div>', function(result){
                 setTimeout(function(){
                 $('#tblbillheadtransaction-member_code').focus();},100);
                    }); 
                    $('#tblbillheadtransaction-member_code').val('');
                    $('#tblbillheadtransaction-customer_name').val('');
                }
            },
            error:function(data){
		
	    }
	});
        }
    }
  
    $('#tblbillheadtransaction-no_installment').on('change',function(){
        dispDefBillHead();
    });
    $('#tblbillheadtransaction-amount').on('change',function(){
        dispDefBillHead();
    });
    
    function dispDefBillHead(){
        var amount = $('#tblbillheadtransaction-amount').val();
        var install = $('#tblbillheadtransaction-no_installment').val();
        if(amount != '' && install != '' && install != 0){
            amount = parseFloat(amount);
            install = parseFloat(install);
            if(!isNaN(amount) && !isNaN(install)){
                var inst = '';
                inst=amount/install;
                $('#tblbillheadtransaction-installment_amount').val(inst.toFixed(2));
            } else {
                $('#tblbillheadtransaction-installment_amount').val('');
            }
        }
        else{
            $('#tblbillheadtransaction-installment_amount').val('');
        }
    }
     $(document).on('change', '#tblbillheadtransaction-transaction_date', function() {  
        reloadGrid();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/vsp/tbl-bill-head-transaction/member-list-grid']) . "'+ '?' + $('#member-bill-head-transaction-form').serialize();
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
$this->registerJs($script, View::POS_END, 'member-bill-head-transaction-form');
?>