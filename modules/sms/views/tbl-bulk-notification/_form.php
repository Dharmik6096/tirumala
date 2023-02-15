<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use zainiafzan\widget\Dropzone;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;

$title = Yii::$app->label->title($type, 'Bulk Notification');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'role-form',
            'validateOnBlur' => false,
        ])
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('notification_type', $model, $form, 'form-group', $model->getAttributeLabel('notification_type'), FALSE, 'notification_type', false, true); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('receiver_type', $model, $form, '', $model->getAttributeLabel('receiver_type'), false, 'receiver_type', false); ?>  
    </div>
    <div class="col-sm-3 login_type">
        <?= Yii::$app->dropdown->dropdownStatic('user_login_type', $model, $form, '', $model->getAttributeLabel('login_type'), false, 'login_type', false); ?>  
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
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, date('Y-m-d'), false, true); ?>
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
                'acceptedMimeTypes' => ".pdf",
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
                                                    if((notificationType=='2' || notificationType=='4') && ($('#file_name').val())==''){                                                                                  
                                                        errMsg += 'Please Attach File.';
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
    $('.import-area').hide();
    $('.plant_dd').hide();
    $('.mcc_dd').hide();
    $('.bmc_dd').hide();
    $(document).on('change', '#tblbulknotification-notification_type', function() {  
          hideShowFields();
    });
    
      function hideShowFields(){
        var type = $('#tblbulknotification-notification_type').val();
        if(type == '1'){
            $('.app_type').show();
            $('.login_type').show();
            $('.import-area').hide();
            $('.plant_dd').hide();
            $('.mcc_dd').hide();
            $('.bmc_dd').hide();
            $('#tblbulknotification-plant_code').val('');
            $('#tblbulknotification-plant_code').trigger('change');
            $('#tblbulknotification-plant_code').trigger('select2:select');
            $('#tblbulknotification-mcc_plant_code').val('');
            $('#tblbulknotification-mcc_plant_code').trigger('select2:select');
            $('#tblbulknotification-bmc_code').val('');
            $('#tblbulknotification-bmc_code').trigger('select2:select');
        }else if(type == '4'){
            $('.import-area').show();
//            $('.union_dd').show();
            $('.plant_dd').show();
            $('.mcc_dd').show();
            $('.bmc_dd').show();
            $('.app_type').hide();
            $('.login_type').hide();
            $('#tblbulknotification-login_type').val('vsp');
            $('#tblbulknotification-login_type').trigger('change');
            $('#tblbulknotification-login_type').trigger('select2:select');
        }
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>