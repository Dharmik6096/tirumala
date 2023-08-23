<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = isset($title) ? $title : 'Vendor Payment Process : Step 1';
$post_url = isset($post_url) ? $post_url : Url::to(['create']);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    //'action' => ['list-payment'],
                    //'method' => 'GET',
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row">
            <div class="col-sm-3" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppayment-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppayment-plant_code', 'mcc_plant_code', TRUE); ?>
                <?php
                $where = json_encode(['data_lock_bmc' => 1, 'billing_lock_bmc' => 0]);
                echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                echo Html::hiddenInput('member_billing_lock_check', 1, ['id' => 'member_billing_lock_check']);
                ?>
            </div>      
            <div id="single-bmc">
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'bmc_code', 'BMC *'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-bmc_code', 'customer_type', 'Customer Type *', FALSE); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-bmc_code,tblvsppayment-customer_type,applicable_for,data_lock_bmc,member_billing_lock_check', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code') . ' *', FALSE, FALSE); ?>
                </div>
            </div>
            <div id="multiple-bmc">
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'p_bmc_code', 'BMC *', TRUE); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-p_bmc_code', 'p_customer_type', 'Customer Type *', FALSE); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-p_bmc_code,tblvsppayment-p_customer_type,applicable_for,data_lock_bmc,member_billing_lock_check', 'p_payment_cycle_code', $model->getAttributeLabel('payment_cycle_code') . ' *', FALSE, FALSE); ?>
                </div>
            </div>
            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Next'),
                        'id' => 'vendor-payment',
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => $post_url,
                            'beforeSend' => new JsExpression("function(data){  
                                        $('#loadercontent').show();
                                        $('#pageloader').show();
                                    }"),
                            'success' => new JsExpression('function(data){ 
                                                    $(".help-block").text("");
                                                    $(".form-group").removeClass("has-error");
                                                    if (data.status == "success"){
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        window.location=data.url;
                                                    } else if (data.status == "displayConfirmPopup"){
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        bootbox.confirm({
                                                            message: "<div class=\"bg-danger\"><i class=\"fa fa-question-circle\"></i></div><span>"+data.msg+"</span>",
                                                            buttons: {
                                                                confirm: {
                                                                    label: "' . Yii::t('app', 'Yes') . '",
                                                                    className: "btn-primary"
                                                                },
                                                                cancel: {
                                                                    label: "' . Yii::t('app', 'No') . '",
                                                                    className: "btn-danger"
                                                                }
                                                            },
                                                            callback: function (result) {
                                                                if(result){
                                                                    window.location=data.url_regenerate;
                                                                } else {
                                                                    window.location=data.url;
                                                                }
                                                            }
                                                        });
                                                    } else if (data.status == "displayPopup"){
                                                        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+data.msg+"</span>");
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                    } else {
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        var cnt=0;
                                                        $.each(data, function(key, val) {
                                                            var parent_div = $("#"+key).parent("div");
                                                            parent_div.find(".help-block").remove();
                                                            $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                            $("#"+key).closest(".form-group").addClass("has-error");                                          
                                                        });
                                                    }
                                     }'),
                        ],
                        'options' => ['class' => 'btn btn-primary',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php
$script = "
     $('#single-bmc').hide();
     $('#multiple-bmc').hide();
  /*    $(document).on('click', '.saveData', function(){
        $('#pageloader').show();
        $('#loadercontent').show();
        $('form#w0').on('afterValidate', function () {
            var len = $('form#w0').find('.has-error').length;
            if (len >= 1) {
                $('#pageloader').hide();
                $('#loadercontent').hide();
            } else {
                $('#pageloader').show();
                $('#loadercontent').show();
            }
        });
    })

$('#tblvsppayment-customer_type').on('change',function(){
     var customer_type= $(this).val();
    var where_data_lock='';     
    if(customer_type =='DCS'){
       where_data_lock={'data_lock_bmc':1,'billing_lock_bmc':0,'billing_lock_member':1};
     }else{
         where_data_lock={'data_lock_bmc':1,'billing_lock_bmc':0};
     }
      $('#data_lock_bmc').val(JSON.stringify(where_data_lock));
});     

  $('#tblvsppayment-payment_cycle_code').on('change',function(){
     var payment_cycle_code= $(this).val();
     if(payment_cycle_code !=''){
     var union_code= $('#tblvsppayment-union_code').val();
     var bmc_code= $('#tblvsppayment-bmc_code').val();
     var customer_type= $('#tblvsppayment-customer_type').val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-vsp-payment/check-payment-processed']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'customer_type' : customer_type,'union_code':union_code},            
            success: function(data) {
                var data = $.parseJSON(data);
                var message = data.message;
               if(message != ''){
                    bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-info-circle\'></i></div><span>'+message+'</span>');
               }   
            }
        });
      }
}); */
$('#tblvsppayment-mcc_plant_code').on('change',function(){
     var mcc_plant_code= $(this).val();
     if(mcc_plant_code !='' && mcc_plant_code != null){
            $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-vsp-payment/check-mcc-type']) . "',
            data: {'mcc_plant_code' : mcc_plant_code},            
            success: function(data) {
                var data = $.parseJSON(data);
                var multiple_bmc = data.multiple_bmc;
               if(multiple_bmc == '1'){
                 $('#single-bmc').hide();
                 $('#multiple-bmc').show();
               }else{
                 $('#multiple-bmc').hide();
                 $('#single-bmc').show();
               }   
            }
        });
      }
});
";
$this->registerJs($script, View::POS_END, 'check-payment-cycle-processed');
?>