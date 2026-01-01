<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
            'options' => ['id' => 'approval-master-from'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Approval Stages Master</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields no_padding_input">
            <?= Yii::$app->dropdown->depend_dropdown('approval_process', $model, $form, 'tblapprovalstages-union_code', '', $model->getAttributeLabel('process_name')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->dropdownStatic('approval_mode', $model, $form, '', $model->getAttributeLabel('approval_mode'), FALSE, 'approval_mode') ?> 
        </div>
        <div class="col-sm-4 create_fields">
            <?= $form->field($model, 'remarks')->textInput() ?>
        </div>
        <div class="col-sm-1 Button disabled mb25 ml15 padding_top_20">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Approval Stages') ?></button>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Approval Stages Details</h4>
        </div>
        <?= Html::activeHiddenInput($model, 'approval_stages_code'); ?>
        <?= Html::hiddenInput('change_event', '0', ['id' => 'change_event']); ?>
        <div class="col-sm-2"> 
            <?= Yii::$app->dropdown->approval_level($txModel, $form, 'tblapprovalstages-union_code,tblapprovalstages-process_name,change_event', 'level', 'Level', FALSE); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('approval_mode', $txModel, $form, '', $txModel->getAttributeLabel('approval_mode'), FALSE, 'approval_mode') ?> 
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('approval_type', $txModel, $form, '', $txModel->getAttributeLabel('approval_type'), FALSE, 'approval_type') ?> 
        </div>
        <div class="col-sm-2 reset_field login_type_dd">
            <?= Yii::$app->dropdown->dropdownStatic('route_login_type', $txModel, $form, '', 'Login Type', false, 'login_type'); ?>
        </div>
        <div class="col-sm-2 reset_field login_type_dd">
            <?= Yii::$app->dropdown->dropdown('department', $txModel, $form, 'col-sm-3 form-group', $model->getAttributeLabel('department'), false, 'department'); ?>
        </div>
        <div class="col-sm-2 reset_field user_dd">
            <?= Yii::$app->dropdown->dropdown('user', $txModel, $form, 'col-sm-3 form-group', $txModel->getAttributeLabel('user_code'), false, 'user_code'); ?>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Add'),
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
//                                                                    $(".create_fields input").prop("disabled", true);
//                                                                    $(".create_fields").removeClass("disabled");
//                                                                    $(".create_fields select").prop("disabled", true);                                                                  
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                     
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                     $("#tblapprovalstages-approval_stages_code").val(data.pk_code);
                                                                    reloadGrid();
                                                                    $("#approval-master-from .reset_field input").val("");
                                                                    $("#approval-master-from .reset_field select").val("");
                                                                    $("#approval-master-from .reset_field textarea").val("");
                                                                    $("#tblapprovalstagesdetail-approval_mode").trigger("change");
                                                                    $("#tblapprovalstagesdetail-approval_mode").trigger("select2:select");
                                                                    $("#tblapprovalstagesdetail-approval_mode").trigger("change");
                                                                    
                                                                    $("#tblapprovalstagesdetail-approval_type").trigger("change");
                                                                    $("#tblapprovalstagesdetail-approval_type").trigger("select2:select");
                                                                    $("#tblapprovalstagesdetail-approval_type").trigger("change");
                                                                    
                                                                    $("#tblapprovalstagesdetail-login_type").trigger("change");
                                                                    $("#tblapprovalstagesdetail-login_type").trigger("select2:select");
                                                                    $("#tblapprovalstagesdetail-login_type").trigger("change");
                                                                    
                                                                    $("#tblapprovalstagesdetail-user_code").trigger("change");
                                                                    $("#tblapprovalstagesdetail-user_code").trigger("select2:select");
                                                                    $("#tblapprovalstagesdetail-user_code").trigger("change");
                                                                  
                                                                    $("#change_event").val("1");
                                                                    $("#change_event").trigger("change");
                                                                   
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
    var type = `$type`;
    $('#tblapprovalstages-process_name').change(function() {
        if(type == 'create'){
            approvalMode();
        }
    });
    
    function approvalMode(){
        var process_name = $('#tblapprovalstages-process_name').val();
        $('.field-tblapprovalstages-approval_mode').removeClass('no_pointer');
        $('#tblapprovalstages-approval_mode').val('').trigger('change');
        if(process_name !='' && (process_name =='member' || process_name =='society'|| process_name =='tbl_shift_time_exceed'|| process_name =='tbl_customer_master_provisional')) {
           $('#tblapprovalstages-approval_mode').val('strict').trigger('change');
           $('.field-tblapprovalstages-approval_mode').addClass('no_pointer');
        }
    }
";
$this->registerJs($script, View::POS_END, 'approval-stages');
?>