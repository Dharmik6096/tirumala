<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use zainiafzan\widget\Dropzone;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['/complaint/tbl-complain/remove']);
$path = Yii::$app->params['complaint_dir_path'];
$size = '';
$attachment = '';
$attachment_code = '';
if (!empty($model['attachment'])) {
    file_exists($path . $model['attachment']->attachment) ? $size = filesize($path . $model['attachment']->attachment) : $size = '';
    $attachmentData = $model['attachment'];
    $attachment = $attachmentData->attachment;
    $attachment_code = $attachmentData->attachment_code;
}
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('complain_type', $model, $form, '', $model->getAttributeLabel('complain_type_code'), false, 'complain_type_code'); ?>
        <?php // echo Html::hiddenInput('TblComplain[complain_for]', '', ['id' => 'complain_for']); ?>
    </div>
    <div class="col-sm-2">       
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('location_type', $model, $form, '', $model->getAttributeLabel('location_type'), false, 'location_type', FALSE, FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcomplain-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcomplain-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcomplain-mcc_plant_code', 'bmc_code', Yii::t('app', 'bmc_code'), FALSE); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblcomplain-bmc_code', 'dcs_code', Yii::t('app', 'dcs_code')); ?>
    </div>
    <div class="col-sm-2 contact_dis">
        <?= $form->field($model, 'contact_person')->textInput(['data-val' => $model->contact_person]) ?>
    </div>
    <div class="col-sm-2 number-validate mobile_dis">
        <?= $form->field($model, 'mobile_no')->textInput(['data-val' => $model->mobile_no]) ?>
    </div>

    <div class="col-sm-3 disp_none">
        <?= Yii::$app->dropdown->dropdownStatic('complain_for', $model, $form, 'form-group', $model->getAttributeLabel('complain_for')); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->asset_list($model, $form, 'tblcomplain-location_type,tblcomplain-plant_code,tblcomplain-bmc_code,tblcomplain-dcs_code,tblcomplain-complain_for', 'asset_code', $model->getAttributeLabel('asset_code'), FALSE, '', FALSE, TRUE); ?>               
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'serial_number')->textInput(['readonly' => true, 'data-val' => $model->serial_number]) ?>
    </div>
    <!--    <div class="col-sm-2 disp_none">
            <? $form->field($model, 'complain_type_code')->textInput(['data-val' => $model->complain_type_code]) ?>
        </div>-->
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->complain_problem($model, $form, 'tblcomplain-complain_type_code', 'complain_problem_code', $model->getAttributeLabel('complain_problem_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="col-sm-2 mt10">
        <?= $form->field($model, 'affects_data', ['checkboxTemplate' => "<div class='checkbox mb0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    </div>
    <div class="col-sm-2 mt20">
        <?= $form->field($model, 'physical_damage', ['checkboxTemplate' => "<div class='checkbox mt0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-12">
        <?php echo Html::hiddenInput('TblAttachment[attachment_code]', $attachment_code, ['id' => 'attachment_code']); ?>
        <?php echo Html::hiddenInput('TblAttachment[old_attachment]', $attachment, ['id' => 'old_attachment']); ?>
        <?php echo Html::hiddenInput('TblAttachment[attachment]', $attachment, ['id' => 'attachment']); ?>
        <?=
        Dropzone::widget([
            'id' => 'mainDrop',
            'options' => [
                'url' => \yii\helpers\Url::to(['/complaint/tbl-complain/upload-file',
                    'main' => 1,]),
                'addRemoveLinks' => true,
                'autoDiscover' => false,
                'maxFiles' => 1,
                'init' => new JsExpression("function(file){
                        if('$attachment' != '' && '{$size}' != ''){
                            var data = '$path'+'$attachment';
                            var mockFile = {
                                name: '$attachment',
                                size: '{$size}',
                            };
                            mockFile.isMock = true;

                            // Tell eveyone this file was accepted.
                            mockFile.status = Dropzone.ADDED;
                            mockFile.accepted = true;
                            this.emit('addedfile', mockFile);
                            this.emit('success', mockFile);
                            this.emit('complete', mockFile);
                            this.options.maxFiles--;
    //                        var myDropzone = $('#mainDrop').dropzone;
    //                        console.log(myDropzone);
                    }
                   }"),
            ],
            'clientEvents' => [
                'success' => "function( file, response ){
                                            var data=$.parseJSON(response);
                                            if(data.status=='success')
                                            { 
                                                $('#attachment').val(data.msg);
                                                $('#upload-btn').attr('disabled',false);
                                            }
                                            else
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                                
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
                                                        if(name == val){
                                                            $('#attachment').val('');
                                                            this.options.maxFiles++;
                                                        }
                                           }",
                'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
            ]
        ]);
        ?>
    </div> 
    <div class="col-sm-2">
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
//    var _csrf_token = yii.getCsrfParam() ? yii.getCsrfToken() : '';
    $('.default_hide').hide();
    $('#tblcomplain-location_type').on('change', function(){
        var location_type = $(this).val();
        hideSectionManage(location_type);
    });
    $('#tblcomplain-complain_type_code').on('change', function() {
        var complainType = $(this).val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['get-complain-for']) . "',
            data: {'complain_type' : complainType},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success') {
                    $('#tblcomplain-complain_for').val(obj1.msg.complain_for).trigger('change');
                    $('.field-tblcomplain-asset_code').parent('div').show();
                    $('.field-tblcomplain-serial_number').parent('div').show();
                    if(obj1.msg.complain_for != 'asset_complain') {
                        $('#tblcomplain-asset_code').attr('disabled',true);
                        $('.field-tblcomplain-asset_code').parent('div').hide();
                        $('.field-tblcomplain-serial_number').parent('div').hide();
                        $('#tblcomplain-asset_code').val('').trigger('change');
                    }
                }
            },
        });
    });

 $('#tblcomplain-asset_code').on('change', function() {
        var asset_code = $(this).val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['get-sr-number']) . "',
            data: {asset_code : asset_code},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success') {
                    $('#tblcomplain-serial_number').val(obj1.msg);
                }
            },
        });
    });
    
    function hideSectionManage(type){
        if(type == '1'){
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').hide();
            $('.field-tblcomplain-bmc_code').parent('div').hide();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
        } else if(type == '2') {
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').show();
            $('.field-tblcomplain-bmc_code').parent('div').show();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
        } else if(type == '3') {
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').show();
            $('.field-tblcomplain-bmc_code').parent('div').show();
            $('.field-tblcomplain-dcs_code').parent('div').show();
        }
    }
    
    $('#tblcomplain-plant_code').on('change',function(){
        setContactDetails();
    });
    
    $('#tblcomplain-bmc_code').on('change',function(){
        setContactDetails();
    });
    
    $('#tblcomplain-dcs_code').on('change',function(){
        setContactDetails();
    });
    
    function setContactDetails(){
        var module_code = $('#tblcomplain-dcs_code').val();
        var module_name = 'society';
        var old_module_code =  '" . $model->dcs_code . "';
        if(module_code == '' || module_code == undefined) {
            module_code = $('#tblcomplain-mcc_plant_code').val();
            module_name = 'mccPlant';
            old_module_code =  '" . $model->mcc_plant_code . "';
        }
        if(module_code == '' || module_code == undefined) {
            module_code = $('#tblcomplain-plant_code').val();
            module_name = 'plant';
            old_module_code =  '" . $model->plant_code . "';
        }
        if(module_code != '' && module_code != undefined) {
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/details/tbl-contact-details/get-contact-details']) . "',
                data: 'module_code='+module_code+'&module_name='+module_name,
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    $('#tblcomplain-contact_person').val(obj1.contact_person);
                     if(obj1.contact_person != '') {
                           $('.contact_dis').addClass('disabled');                   
                        }else{
                           $('.contact_dis').removeClass('disabled'); 
                        }
                    $('#tblcomplain-mobile_no').val(obj1.mobile_no);
                     if(obj1.mobile_no != '') {
                           $('.mobile_dis').addClass('disabled');                   
                        }else{
                           $('.mobile_dis').removeClass('disabled'); 
                        }
                    if(old_module_code == module_code) {
                        if($('#tblcomplain-contact_person').attr('data-val') != '') {
                            $('#tblcomplain-contact_person').val($('#tblcomplain-contact_person').attr('data-val'));
                        }
                        if($('#tblcomplain-mobile_no').attr('data-val') != '') {
                            $('#tblcomplain-mobile_no').val($('#tblcomplain-mobile_no').attr('data-val'));
                        }
                    }
                },
                error:function(data){
                    //alert('Your data has not been submitted..Please try again');
                }
            });
        }
    }

";
$this->registerJs($script, View::POS_END, 'create-complain');
?>