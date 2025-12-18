<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use zainiafzan\widget\Dropzone;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;

$title = Yii::$app->label->title($type, 'Bulk Notification');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readonly = $type == 'create' ? FALSE : TRUE;
$eipl_code = Yii::$app->session->get('eiplCode');
$class = $type == 'create' ? '' : 'disable_div';
?>

<?php
$form = ActiveForm::begin([
            'id' => 'role-form',
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ])
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2 <?= $class ?> ">
        <?= Yii::$app->dropdown->dropdownStatic('notification_type', $model, $form, 'form-group', $model->getAttributeLabel('notification_type'), false, 'notification_type', false); ?>
    </div>
    <div class="col-sm-2 app_type">
        <?= Yii::$app->dropdown->dropdown('app_type', $model, $form, '', TRUE, false, 'app_type'); ?>
    </div>
    <div class="col-sm-2 receiver_type">
        <?= Yii::$app->dropdown->dropdownStatic('receiver_type', $model, $form, '', $model->getAttributeLabel('receiver_type'), false, 'receiver_type', false); ?>  
    </div>
    <div class="col-sm-2 login_type">
        <?= Yii::$app->dropdown->dropdownStatic('login_type', $model, $form, '', $model->getAttributeLabel('login_type'), false, 'login_type', false); ?>  
    </div>
    <div class="col-sm-2 reset_field department">
        <?= Yii::$app->dropdown->dropdown('department', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('department'), false, 'department'); ?>
    </div>
    <div class="col-sm-2 union_dd">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2 plant_dd">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbulknotification-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2 mcc_dd">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbulknotification-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>      
    <div class="col-sm-2 bmc_dd">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbulknotification-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblbulknotification-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), true); ?>         
    </div>  
    <div class="col-sm-2 app_type">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblbulknotification-dcs_code', '', Yii::t('app', 'Member')); ?>
    </div>
    <div class="col-sm-2 payment_cycle_dd">
        <?php
        $where = json_encode(['data_lock_member' => 1, 'billing_lock_member' => 0]);
        echo Html::hiddenInput('customer_type', 'DCS', ['id' => 'customer_type']);
        echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
        echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
        ?>
        <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblbulknotification-union_code,tblbulknotification-bmc_code,customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2 f_date">
        <?= Yii::$app->controls->valid_date($model, $form, 'from_date'); ?>
    </div>
    <div class="col-sm-2 shift f_shift">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('from_shift_code'), false, 'from_shift_code');
        ?>
    </div> 
    <div class="col-sm-2 t_date">
        <?php
        echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, $model->getAttributeLabel('to_date'));
        ?>
    </div>  
    <div class="col-sm-2 shift t_shift">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('to_shift_code'), false, 'to_shift_code');
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, date('Y-m-d'), false, true); ?>
    </div>
    <div class="col-sm-2 campaign_name">
        <?= $form->field($model, 'campaign_name')->textInput() ?>
    </div>
    <div class="col-sm-2 title_s">
        <?= $form->field($model, 'title')->textInput() ?>
    </div>
    <div class="col-sm-2 mt15 auto_scrol_s">
        <?= $form->field($model, 'auto_scrolling', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>

    <div class="col-sm-6">
        <?= $form->field($model, 'message')->textarea(['maxlength' => 255]) ?>
    </div>
    <?php echo Html::hiddenInput('TblFtpTxnLog[file_name]', '', ['id' => 'file_name']); ?>

    <div class="col-sm-12 import-area">
        <?=
        Dropzone::widget([
            'id' => 'mainDrop',
            'options' => [
                'acceptedMimeTypes' => ".jpg,.pdf,.jpeg,.png",
                'url' => \yii\helpers\Url::to(['/sms/tbl-bulk-notification/import-file']),
                'addRemoveLinks' => true,
                'autoDiscover' => false,
                'maxFiles' => 1,
//                'maxFilesize' => 2,
            //  'maxTotalSize' => 0.0009,
            ],
            'clientEvents' => [
                'success' => "function( file, response ){
                                            var data=$.parseJSON(response);
                                            if(data.status=='success')
                                            { 
                                                $('#file_name').val(data.msg);                                               
//                                                $('#upload-btn').attr('disabled',false);
                                            } else {
                                                $(file.previewElement).remove();
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                            }
                                                
                                        }",
                'removedfile' => "function(file){
                                var file_str = $('#file_name').val();
                                var res = file_str.replace(file.name,''); 
                                $('#file_name').val(res);
                           
                            }",
                'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
            ]
        ]);
        ?>
    </div>
    <div class="clearfix"></div>
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
                                                 var errMsg = '';
                                                    var notificationType= $('#tblbulknotification-notification_type').val();                                
                                                    if ('$eipl_code' != 'AMULAMCS') {
                                                        if((notificationType=='2' || notificationType=='4' || notificationType=='3' ) && ($('#file_name').val())==''){                                       
                                                            errMsg += 'Please Attach File.';
                                                        }
                                                    } else {
                                                        if ((notificationType == '2' || notificationType=='8') && ($('#file_name').val())=='') {
                                                            errMsg += 'Please Attach File.';
                                                        }
                                                    }

                                                    if(errMsg != ''){
                                                         bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+errMsg+'</div></div>');
                                                         return false;
                                                    }
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
                                                                     
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $(".panel-body").scrollTop(0);
                                                                   bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblbulknotification-message").focus();},100);
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
                                                                        if(false){
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

            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php
$script = " 
    
function resetDateShiftFields() {
    $('.f_date, .t_date, .f_shift, .t_shift, .auto_scrol_s').hide();
    $('#tblbulknotification-from_date, #tblbulknotification-to_date').val('');
    $('#tblbulknotification-from_shift, #tblbulknotification-to_shift').val('').trigger('select2:select');
}
    
$(document).ready(function () {
    $('.import-area, .payment_cycle_dd, .login_type, .department').hide();
    resetDateShiftFields();
    
    $(document).on('change', '#tblbulknotification-notification_type', function() {
        hideShowFields();
    });
    
    $(document).on('change', '#tblbulknotification-login_type', function() {
        hideShowDepartment();
    });
    
      function hideShowFields(){
        var type = $('#tblbulknotification-notification_type').val();
        if(type == '1'){
            $('.receiver_type, .app_type, .login_type').show();
            $('.import-area, .payment_cycle_dd').hide();
            $('#tblbulknotification-login_type').val('MEMBER').trigger('change').trigger('select2:select');
            resetDateShiftFields();
        }else if(type == '2' || type == '4' || type == '5' || type == '6'|| type == '7' || type == '8'){
            $('.receiver_type, .import-area, .payment_cycle_dd').show();
            $('.app_type, .login_type, .department').hide();
            $('#tblbulknotification-login_type').val('').trigger('change').trigger('select2:select');
            resetDateShiftFields();
            if (type == '4') {
                $('#tblbulknotification-login_type').val('DCS').trigger('change').trigger('select2:select');
                $('#tblbulknotification-department').val('').trigger('change').trigger('select2:select');
            }
        }else if(type == '3'){
            $('.import-area, .f_date, .t_date, .f_shift, .t_shift, .auto_scrol_s').show();
            $('.app_type, .login_type, .receiver_type, .payment_cycle_dd, .department').hide();
            $('#tblbulknotification-login_type').val('').trigger('change').trigger('select2:select');
        }
         hideShowDepartment();
    }
});

      function hideShowDepartment() {
        var notificationType = $('#tblbulknotification-notification_type').val();
        var loginType = $('#tblbulknotification-login_type').val();

        if (notificationType == '1' && loginType != 'MEMBER' && loginType != 'ALL') {
            $('.department').show();
        } else {
            $('#tblbulknotification-department').val('');
            $('.department').hide();
        }
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>