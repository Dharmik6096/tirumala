<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use zainiafzan\widget\Dropzone;
use yii\helpers\Html;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>
<?php
$url = Url::to(['/general/tbl-banner/remove', 'model' => $model]);
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'id' => 'banner',
        ]);
?>
<?php
echo $form->errorSummary($model);
?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('user_login_type', $applicability_model, $form, '', 'Login Type', FALSE, 'login_type', FALSE, TRUE, TRUE, FALSE, TRUE) ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', FALSE, date('Y-m-d'), FALSE, true); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', FALSE, date('Y-m-d'), FALSE, true); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('banner_tap_operation', $model, $form, '', $model->getAttributeLabel('tap_operation'), FALSE, 'tap_operation'); ?>
    </div>
    <div class="col-sm-2 tap_event_internal disp_none">
        <?= Yii::$app->dropdown->dropdownStatic('banner_tap_event', $model, $form, '', $model->getAttributeLabel('tap_event'), FALSE, 'tap_event'); ?>
    </div>
    <div class="col-sm-2 tap_event disp_none">
        <?= $form->field($model, 'tap_event')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'title')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textarea() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'seq_no')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="alert alert-success warning-single-box">* Banner should be upload aspected ratio is 16:9 Or pixels(H X V) 1366 X 768.</div>

    <div class="col-sm-12">
        <?php echo Html::hiddenInput('attachment', '', ['id' => 'attachment']); ?>
        <?=
        Dropzone::widget([
            'id' => 'mainDrop',
            'options' => [
                'acceptedMimeTypes' => ".jpg,.jpeg,.png,.mp4",
                'url' => Url::to(['/general/tbl-banner/banner-upload',
                    'main' => 1,]),
                'addRemoveLinks' => true,
                'autoDiscover' => false,
                'maxFiles' => 1,
                'maxFilesize' => 10,
            ],
            'clientEvents' => [
                'success' => "function( file, response ){
                        var data=$.parseJSON(response);
                        if(data.status=='success')
                        { 
                            var new_attachment = $('#attachment').val();
                            $('.dz-filename').text(data.msg);
                            $('.dz-details img').attr('alt',data.msg);
                            if(new_attachment == ''){
                                $('#attachment').val(data.msg);
                            } else {
                                $('#attachment').val(new_attachment+','+data.msg);
                            }                            
                            this.options.maxFiles--;
                        } else {
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                        }
                    }",
                'removedfile' => "function(file){
                        var name = file.name;
                        var val = $('#attachment').val();
                        var old_val = $('#old_attachment').val();
                        $.ajax({
                            type: 'POST',
                            'url': '{$url}',
                            data: {'id':name,'value':val},
                            success: function(data) {                                        
                            var obj1 = $.parseJSON(data);
                        },
                            error:function(data){
                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>File Not Removed Due to Error</span></div></div>');
                            }
                });
                    $('#attachment').val('');
                    this.options.maxFiles++;
                }",
                'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
            ]
        ]);
        ?>
    </div> 
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'create'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){
                                                 var attachmentValue = $('#attachment').val();
                                                    if(attachmentValue == ''){
                                                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please select File.</span></div></div>');
                                                        return false;
                                                    } 
                                                    $('#loadercontent').show();
                                                    $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "success"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                }else{
//                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+"</span></div></div>");
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
$(document).ready(function() {

    setTimeout(function() {
        $('#tblbanner-tap_operation').trigger('change');
    }, 100);
    $('#tblbanner-tap_operation').on('change', function() {
        var tap_operation = $(this).val();
        if (tap_operation == 'internal') {
            $('.tap_event_internal').show().find('select').prop('disabled', false);
            $('.tap_event').hide().find('input').prop('disabled', true);
        } else {
            $('.tap_event').show().find('input').prop('disabled', false);
            $('.tap_event_internal').hide().find('select').prop('disabled', true);

        }
    });
    

});
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>