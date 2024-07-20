<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'indent-master-from'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom hide_help_block">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Indent Master</h4>
        </div>
        <div class="col-sm-1 create_fields">
            <?= Yii::$app->dropdown->dropdownStatic('indent_type', $model, $form, 'form-group', $model->getAttributeLabel('indent_type'), false, 'indent_type', false); ?>
        </div>
        <div class="col-sm-2 create_fields warehouse_div">
            <?= Html::hiddenInput('indent_type', '4', ['id' => 'indent_type']); ?>
            <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'indent_type', '', $model->getAttributeLabel('warehouse_code'), 'warehouse_code', false); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblindentmaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblindentmaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblindentmaster-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>  
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'indent_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-1 Button disabled mb25 ml15 padding_top_20">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Indent') ?></button>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Indent Master Details</h4>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('customer_type', $model, $form, '', $model->getAttributeLabel('customer_type'), false, 'customer_type', FALSE, FALSE, FALSE); ?>
        </div>
        <div class="col-sm-2 reset_field" id="dcs_code">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblindentmaster-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
        </div>
        <div class="col-sm-2 reset_field" id="customer_code">
            <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblindentmaster-dcs_code', '', $model->getAttributeLabel('customer_code'), 'customer_code', FALSE); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->indent_product($model, $form, 'tblindentmaster-union_code,tblindentmaster-indent_type', 'product_code', Yii::t('app', 'Product')); ?>
        </div>
        <div class="col-sm-1 reset_field qty-validate">
            <?= $form->field($model, 'qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'rate')->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'amount')->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                $isIndentCreateCreditLimitCheck = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_indent_create_credit_limit_check', 'PORTAL') ?: 0;
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Add'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['create-other']),
                        'beforeSend' => new JsExpression("function(){
                                $('#loadercontent').show();
                                $('#pageloader').show();
                                var ctype = $('#tblindentmaster-customer_type').val();
                                if(ctype == '1'){
                                    var isIndentCreateCreditLimitCheck = $isIndentCreateCreditLimitCheck;
                                    var formSelector = '#indent-master-from';
                                    var creditCheckUrl = '" . Url::to(['get-available-credit']) . "';
                                    var submitUrl = '" . Url::to(['create-other']) . "';
                                    handleFormSubmissionWithCreditCheck(formSelector, creditCheckUrl, submitUrl, isIndentCreateCreditLimitCheck);
                                    return false;
                                }
                            }
                        "),
                        'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
//                                                                    $(".create_fields input").prop("disabled", true);
//                                                                    $(".create_fields").removeClass("disabled");
//                                                                    $(".create_fields select").prop("disabled", true);                                                                  
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                     
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $("#indent-master-from .reset_field input").val("");
                                                                    $("#indent-master-from .reset_field select").val("");
                                                                    $("#indent-master-from .reset_field textarea").val("");
                                                                    $("#tblindentmaster-dcs_code").trigger("change");
                                                                    $("#tblindentmaster-dcs_code").trigger("select2:select");
                                                                    $("#tblindentmaster-member_code").trigger("change");
                                                                    $("#blindentmaster-member_code").trigger("select2:select");
                                                                    $("#tblindentmaster-product_code").trigger("change");
                                                                    $("#tblindentmaster-product_code").trigger("select2:select");
                                                                    $(".panel-body").scrollTop(0);
                                                                   bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblbmccollection-dcs").focus();},100);
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
                                                                        if(key != "tblmilkcollection-date_time_of_collection"){
                                                                        var parent_div = $("#"+key).parent("div");
                                                                        parent_div.find(".help-block").remove();
                                                                        $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                                        $("#"+key).closest(".form-group").addClass("has-error");   
                                                                   }
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
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$script = "
function handleFormSubmissionWithCreditCheck(formSelector, creditCheckUrl, submitUrl, isIndentCreateCreditLimitCheck) {
    var amount = parseFloat($('#tblindentmaster-amount').val());
    var indentDate = $('#tblindentmaster-indent_date').val();
    var bmcCode = $('#tblindentmaster-bmc_code').val();
    var unionCode = $('#tblindentmaster-union_code').val();
    var customerCode = $('#tblindentmaster-customer_code').val();

    if (isIndentCreateCreditLimitCheck != 0) {
        $.ajax({
            url: creditCheckUrl,
            type: 'POST',
            data: {
                indent_date: indentDate,
                bmc_code: bmcCode,
                union_code: unionCode,
                customer_code: customerCode
            },
            success: function(data) {
                var data = $.parseJSON(data);
                var creditLimit = parseFloat(data.credit);

                if (amount > creditLimit) {
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                    if (isIndentCreateCreditLimitCheck == 1) {
                        bootbox.confirm({
                            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Amount exceeds current credit limit of ' + creditLimit + '. Are you sure you want to proceed with ' + amount + '?</span></div></div>',
                            buttons: {
                                cancel: {
                                    label: 'No',
                                    className: 'btn-danger'
                                },
                                confirm: {
                                    label: 'Yes',
                                    className: 'btn-primary'
                                }
                            },
                            callback: function(result) {
                                if (result) {
                                    submitForm(formSelector, submitUrl);
                                }
                            }
                        });
                    } else if (isIndentCreateCreditLimitCheck == 2) {
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Amount exceeds current credit limit of ' + creditLimit + '. Cannot proceed.</span></div></div>');
                        return false;
                    }
                } else {
                    submitForm(formSelector, submitUrl);
                }
            },
            error: function() {
                alert('Error fetching credit limit.');
            }
        });
    } else {
        submitForm(formSelector, submitUrl);
    }
}

function submitForm(formSelector, submitUrl) {
    $('#loadercontent').show();
    $('#pageloader').show();
    $.ajax({
        url: submitUrl,
        type: 'POST',
        data: $(formSelector).serialize(),
        success: function(data){
            var data = $.parseJSON(data);
            $('#loadercontent').hide();
            $('#pageloader').hide();
            if (data.status == 'success'){ 
                $('.help-block').text('');
                $('.form-group').removeClass('has-error');         
                $('.DisableAferAdd').addClass('disabledDiv');                                                                  
                $('.error-summary').hide();
                $('.error-summary li').remove();
                reloadGrid();
                $(formSelector + ' .reset_field input').val('');
                $(formSelector + ' .reset_field select').val('');
                $(formSelector + ' .reset_field textarea').val('');
                $('#tblindentmaster-dcs_code').trigger('change');
                $('#tblindentmaster-dcs_code').trigger('select2:select');
                $('#tblindentmaster-member_code').trigger('change');
                $('#blindentmaster-member_code').trigger('select2:select');
                $('#tblindentmaster-product_code').trigger('change');
                $('#tblindentmaster-product_code').trigger('select2:select');
                $('.panel-body').scrollTop(0);
                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>', function(result){
                setTimeout(function(){
                        $('#tblbmccollection-dcs').focus();},100);
                });
            } else {
                $('.help-block').text('');
                $('.form-group').removeClass('has-error');
                $('.error-summary').hide();
                $('.error-summary li').remove();
                $.each(data, function(key, val) {
                    $('.error-summary ul').append('<li>'+val+'</li>');
                    if(key != 'tblmilkcollection-date_time_of_collection'){
                        var parent_div = $('#'+key).parent('div');
                        parent_div.find('.help-block').remove();
                        $('#'+key).after('<div class=\'help-block\'>'+val+'</div>');
                        $('#'+key).closest('.form-group').addClass('has-error');   
                    }
                });
                $('.error-summary').show();
            }
        },
        error: function() {
            $('#loadercontent').hide();
            $('#pageloader').hide();
            bootbox.alert('Error submitting form.');
        }
    });
}

$('#tblindentmaster-customer_type').on('change', function(){
        $('#tblindentmaster-customer_code').val('');
        $('#tblindentmaster-customer_code').trigger('change');
        
        if($(this).val()=='0'){
            $('#dcs_code').show(); 
            $('#customer_code').hide();
        }else if($(this).val()=='1'){
            $('#dcs_code').show();
            $('#customer_code').show(); 
        }else{
            $('#dcs_code').hide(); 
            $('#customer_code').hide(); 
        }
    });
";
$this->registerJs($script, View::POS_END, 'dpu-station-detail-index');
?>
        
