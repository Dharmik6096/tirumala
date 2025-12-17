<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use zainiafzan\widget\Dropzone;
use demogorgorn\ajax\AjaxSubmitButton;

$path = Yii::$app->params['complaint_dir_path'];
$button_type = '';
$urls = '';
if ($type == 'create') {
    $button_type = 'create';
    $urls = ['create'];
    $readonly = false;
} else if ($type == 'edit') {
    $button_type = 'update';
    $urls = ['update', 'id' => $model->complain_code];
    $readonly = true;
} else {
    $button_type = 'resolve';
    $urls = ['resolve-complain', 'id' => $model->complain_code];
    $readonly = true;
}

$size = '';
$attachment = '';
$attachment_code = '';
$disabled = $type == 'resolve' ? True : false;

$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'id' => 'complain-form',
        ]);
?>
<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('complain_type', $model, $form, '', $model->getAttributeLabel('complain_type_code'), $disabled, 'complain_type_code'); ?>
    </div>
    <div class="col-sm-2">       
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $disabled); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('location_type', $model, $form, '', $model->getAttributeLabel('location_type'), $disabled, 'location_type', FALSE, FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcomplain-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $disabled); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcomplain-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $disabled); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcomplain-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE, '', '', $disabled); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblcomplain-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), false, '', $disabled); ?>
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
        <?php
        if ($model->asset_code != '') {
            $model->asset_code = $model->asset_code . '##' . $model->serial_number;
        }
        ?>
        <?= Yii::$app->dropdown->asset_list($model, $form, 'tblcomplain-location_type,tblcomplain-plant_code,tblcomplain-bmc_code,tblcomplain-dcs_code,tblcomplain-complain_for,tblcomplain-complain_type_code', 'asset_code', $model->getAttributeLabel('asset_code'), FALSE, $disabled); ?>               
    </div>
    <div class="col-sm-2 default_hide">
        <?= $form->field($model, 'serial_number')->textInput(['readonly' => true, 'data-val' => $model->serial_number]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->complain_problem($model, $form, 'tblcomplain-complain_type_code', 'complain_problem_code', $model->getAttributeLabel('complain_problem_code')); ?>
    </div>
    <?php
    if ($type != 'resolve') {
        ?>
        <div class = "col-sm-3">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>
        <div class="col-sm-6 add-border">
            <div class="col-sm-4 default_hide">
                <?= Yii::$app->dropdown->dropdownStatic('entry_type_collection', $model, $form, 'form-group', $model->getAttributeLabel('collection_request_type'), $readonly, 'collection_request_type', false); ?>
            </div>
            <div class="col-sm-4 default_hide">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('d-m-Y'), FALSE, $readonly); ?>
            </div>
            <div class="col-sm-4 default_hide">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift', true, $readonly, 'from_shift'); ?>
            </div>
        </div>
        <div class="col-sm-2 mt10 disp_none">
            <?= $form->field($model, 'affects_data', ['checkboxTemplate' => "<div class='checkbox mb0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
        </div>
        <div class="col-sm-2 mt20 disp_none">
            <?= $form->field($model, 'physical_damage', ['checkboxTemplate' => "<div class='checkbox mt0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
        </div>
    <?php }
    ?>

    <?php
    if ($type == 'resolve') {
        ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('resolved_status', $model, $form, 'form-group', $model->getAttributeLabel('resolved_status')); ?>
        </div>
        <div class = "col-sm-2 mt20 default_hide">
            <?= $form->field($model, 'spare_required', ['checkboxTemplate' => "<div class='checkbox mt0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
        </div>
        <div class = "col-sm-2 default_hide">
            <?= $form->field($model, 'new_serial_no', ['options' => ['class' => 'form-group']])->dropDownList($dropdownSerialNo, ['prompt' => Yii::t('app', 'Select New Serial Number')])->label($model->getAttributeLabel('new_serial_no')); ?>
        </div>
        <div class = "col-sm-3">
            <?= $form->field($model, 'resolved_remarks')->textarea() ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Spare Detail</h4>
        </div>
        <div class="col-sm-2">
            <?= Html::activeHiddenInput($model, 'asset_code'); ?>
            <?php Yii::$app->dropdown->asset_bom_list($complain_spare, $form, 'tblcomplain-asset_code', 'spare_code', $complain_spare->getAttributeLabel('spare_code')); ?>
            <?php echo Html::hiddenInput('is_serial_number', '', ['id' => 'is_serial_number']); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->old_sr_no($complain_spare, $form, 'tblcomplain-asset_code,tblcomplain-serial_number,tblcomplainspare-spare_code', 'old_serial_no', $complain_spare->getAttributeLabel('old_serial_no')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('old_spare_status', $complain_spare, $form, 'form-group', $complain_spare->getAttributeLabel('old_spare_status')); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->new_sr_no($complain_spare, $form, 'tblcomplain-asset_code,tblcomplain-location_type,tblcomplain-plant_code,tblcomplain-bmc_code,tblcomplain-dcs_code,tblcomplainspare-spare_code', 'new_serial_no', $complain_spare->getAttributeLabel('new_serial_no')); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($complain_spare, 'qty')->textInput(['value' => 1, 'readonly' => TRUE]) ?>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main">
            <?=
            Html::a(Yii::t('app', 'Add'), 'javascript:void(0)', ['class' => 'btn btn-primary add-asset-record disabled no_pointer', 'id' => 'add_spare'])
            ?>
        </div>        
    </div>
    <?php
}
?>
<div class="clearfix"></div>

<div class="col-sm-12">
    <?php echo Html::hiddenInput('attachment', '', ['id' => 'attachment']); ?>
    <?=
    Dropzone::widget([
        'id' => 'mainDrop',
        'options' => [
            'url' => \yii\helpers\Url::to(['/complaint/tbl-complain/upload-file',
                'main' => 1,]),
            'addRemoveLinks' => true,
            'autoDiscover' => false,
            'maxFiles' => 1,
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
                        }
                        else
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');

                    }",
            'removedfile' => "function(file){
                        var file_str = $('#attachment').val();
                        var res = file_str.replace(file.name,''); 
                         $('#attachment').val(res);
                         this.options.maxFiles++;
                    }",
            'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
        ]
    ]);
    ?>
</div> 
<?php
if ($type == 'resolve') {
    ?>
    <div class="clearfix"></div>
    <div class="col-sm-12 QltyParamDiv">
        <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
            <thead>
                <tr>
                    <th>Spare</th>
                    <th>Old Serial No</th>
                    <th>Old Spare Status</th>
                    <th>New Serial No</th>
                    <th>Qty</th>
                </tr> 
            </thead>
            <tbody id="spare_list">

            </tbody>
        </table>
    </div>
    <?php
}
?>
<div class="clearfix"></div>
<div class="col-sm-2">
    <div class="form-group mt10">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', $button_type),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to($urls),
                'beforeSend' => new \yii\web\JsExpression('function(data){
                    if($("#tblcomplain-resolved_status").val() == "replace" && $("#tblcomplain-spare_required").is(":checked") && $("#spare_list tr").length <= 0){
                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Please add spare at least one</span></div></div>");
                                return false;
                            }
                            $("#loadercontent").show();
                            $("#pageloader").show();
                        }'),
                'success' => new \yii\web\JsExpression('function(data){                                   
                                                $("#pageloader").hide();
                                                $("#loadercontent").hide();
                                                var obj1 = $.parseJSON(data);
                                                if (obj1.status == "success"){
                                                    $("#importModal").modal("toggle");
                                                    $("#complain-form")[0].reset();
                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                } else {
                                                    $("#loadercontent").hide();
                                                    $("#pageloader").hide();
                                                    $(".help-block").text("");
                                                    $(".form-group").removeClass("has-error");
                                                    $(".error-summary").hide();
                                                    $(".error-summary li").remove();
                                                    $.each(obj1, function(key, val) {
                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                        var parent_div = $(".field-"+key).parent("div");
                                                        parent_div.find(".help-block").remove();
                                                        $("#"+key).closest(".form-group").append("<div class=\"help-block\">"+val+"</div>");
                                                        $("#"+key).closest(".form-group").addClass("has-error");   
                                                    });
                                                    $(".error-summary").show();
                                                    }
                                }'),
                'error' => new \yii\web\JsExpression('function(){
                                        $("#pageloader").hide();
                                        $("#loadercontent").hide();
                                }'),
            ],
            'options' => ['class' => 'btn btn-primary', 'id' => 'upload-btn', 'type' => 'submit'],
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
    $('.default_hide').hide();
    hideSectionManage($('#tblcomplain-location_type').val());
    var complainFor = $('#tblcomplain-complain_for').val();
    manageAsset(complainFor);
    $('#tblcomplain-location_type').on('change', function(){
        var location_type = $(this).val();
        hideSectionManage(location_type);
    });
    $('#tblcomplain-complain_type_code').on('change', function() {
        var complainType = $(this).val();
        $('#tblcomplain-location_type').val('').trigger('change');
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
        var location_type = $('#tblcomplain-location_type').val();
        var code = '';
        var dcs = false;
        if(location_type == 2){
            code = $('#tblcomplain-bmc_code').val();
        } else if (location_type == 1) {
            code = $('#tblcomplain-plant_code').val();
        } else {
            dcs = true;
            code = $('#tblcomplain-dcs_code').val();
        }
        $.ajax({
            type: 'post',
            url: '" . Url::to(['get-sr-number']) . "',
            data: {asset_code : asset_code, code: code, dcs: dcs},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success') {
                    $('#tblcomplain-serial_number').val(obj1.msg);
                    if(obj1.assetTypeCode){
                        $('.field-tblcomplain-collection_request_type, .field-tblcomplain-from_date, .field-tblcomplain-from_shift').parent('div').show();
                        $('.add-border').addClass('field-border');
                    }
                    else {
                        $('#tblcomplain-collection_request_type, #tblcomplain-from_shift').val('').trigger('change');
                        $('#tblcomplain-from_date').val('');
                        $('.field-tblcomplain-collection_request_type, .field-tblcomplain-from_date, .field-tblcomplain-from_shift').parent('div').hide();
                        $('.add-border').removeClass('field-border');
                    }
                }
            },
        });
    });

    function manageAsset(complain_for){
        if(complain_for == 'asset_complain'){
            $('.field-tblcomplain-asset_code').parent('div').show();
            $('.field-tblcomplain-serial_number').parent('div').show();
        } else if(complain_for == 'general_complain') {
            $('.field-tblcomplain-asset_code').parent('div').hide();
            $('.field-tblcomplain-serial_number').parent('div').hide();
       }
    }

    function hideSectionManage(type){
        if(type == '1'){
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').hide();
            $('.field-tblcomplain-bmc_code').parent('div').hide();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
            $('#tblcomplain-mcc_plant_code').val('').trigger('change');
            $('#tblcomplain-bmc_code').val('').trigger('change');
            $('#tblcomplain-dcs_code').val('').trigger('change');
        } else if(type == '2') {
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').show();
            $('.field-tblcomplain-bmc_code').parent('div').show();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
            $('#tblcomplain-dcs_code').val('').trigger('change');
        } else if(type == '3') {
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').show();
            $('.field-tblcomplain-bmc_code').parent('div').show();
            $('.field-tblcomplain-dcs_code').parent('div').show();
        } else {
            $('.field-tblcomplain-plant_code').parent('div').hide();
            $('.field-tblcomplain-mcc_plant_code').parent('div').hide();
            $('.field-tblcomplain-bmc_code').parent('div').hide();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
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
                    $('#tblcomplain-mobile_no').val(obj1.mobile_no);
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
    
    $('#tblcomplain-resolved_status').on('change', function(){
        var resolved_status = $(this).val();
        if(complainFor != 'general_complain'){
            hideSection(resolved_status);
        }
    });
    
    function hideSection(type){
        if(type == 'replace'){
            $('.field-tblcomplain-spare_required').parent('div').show();
            $('.field-tblcomplain-new_serial_no').parent('div').show();
        } else if(type) {
            $('.field-tblcomplain-spare_required').parent('div').hide();
            if($('#tblcomplain-spare_required').is(':checked')) {
                $('#tblcomplain-spare_required').trigger('click');
            }
            $('.field-tblcomplain-new_serial_no').parent('div').hide();
        }
    }
    
   $('#tblcomplain-spare_required').click(function(){
        serial_no_enable();
        spare_list();
    });

    function serial_no_enable(){
        if($('#tblcomplain-spare_required').is(':checked')) {
            $('.field-tblcomplain-new_serial_no').parent('div').hide();
        } else {
            $('.field-tblcomplain-new_serial_no').parent('div').show();
        }
    }
    $('.QltyParamDiv').hide();
    
    function spare_list(){
        if($('#tblcomplain-spare_required').is(':checked')) {
            $('.QltyParamDiv').show();
        } else {
            $('.QltyParamDiv').hide();

        }
    }
    
   var rowCount = 0;
   $('#add_spare').on('click', function(){
        var err = '';
        var spare_code = $('#tblcomplainspare-spare_code option:selected').val();
        var old_spare_status = $('#tblcomplainspare-old_spare_status option:selected').val();
        if(spare_code == ''){
            err += 'Spare can not be Blank.<br>';
        }
        if(old_spare_status == ''){
            err += 'Old spare status can not be Blank.<br>';
        }
        var tr_class_new_serial;
        var tr_class_old_serial;
        var tr_class;            
        var isSerialNumber = $('#is_serial_number').val();
        if(isSerialNumber == 'yes'){
            var spareCode = $('#tblcomplainspare-spare_code').val();
            var old_serial_number = $('#tblcomplainspare-old_serial_no').val();
            var new_serial_number = $('#tblcomplainspare-new_serial_no').val();
            if(old_serial_number == ''){
                err += 'Old serial number can not be Blank.<br>';
            }
            if(new_serial_number == ''){
                err += 'New serial number can not be Blank.<br>';
            }
            tr_class_old_serial = spareCode+'_'+old_serial_number;
            tr_class_new_serial = spareCode+'_'+new_serial_number;
            if($('.'+tr_class_old_serial).length > 0){
                err += 'Already exist spare and old serial number. Please select another spare and old serial number.<br>';
            }
            if($('.'+tr_class_new_serial).length > 0){
                err += 'Already exist spare and new serial number. Please select another spare and new serial number.<br>';
            }
            tr_class = tr_class_old_serial+' '+tr_class_new_serial;
        } else {
            var spareCode = $('#tblcomplainspare-spare_code').val();
            tr_class = spareCode;
            if($('.'+tr_class).length > 0){
                err += 'Already exist spare. Please select another spare.<br>';
            }
        }
        if(err == ''){
            var spare = $('#tblcomplainspare-spare_code option:selected').text();
            var old_serial_no = $('#tblcomplainspare-old_serial_no option:selected').val();
            var old_spare_status = $('#tblcomplainspare-old_spare_status option:selected').text();
            var new_serial_no = $('#tblcomplainspare-new_serial_no option:selected').val();
            var qty = $('#tblcomplainspare-qty').val();
            var append_data = '<tr class=\"'+tr_class+'\">';
            append_data += '<td>'+spare+'<input type=\'hidden\' name=\'tblcomplainspare['+rowCount+'][spare_code]\' value='+spare_code+'></td>';
            append_data += '<td>'+old_serial_no+'<input type=\'hidden\' name=\'tblcomplainspare['+rowCount+'][old_serial_no]\' value='+old_serial_no+'></td>';
            append_data += '<td>'+old_spare_status+'<input type=\'hidden\' name=\'tblcomplainspare['+rowCount+'][old_spare_status]\' value=\''+old_spare_status+'\'></td>';
            append_data += '<td>'+new_serial_no+'<input type=\'hidden\' name=\'tblcomplainspare['+rowCount+'][new_serial_no]\' value=\''+new_serial_no+'\'></td>';
            append_data += '<td>'+qty+'<input type=\'hidden\' name=\'tblcomplainspare['+rowCount+'][qty]\' value=\''+qty+'\'></td>';
            append_data += '</tr>';
            rowCount++;
            $('#spare_list').append(append_data);
            $('#tblcomplainspare-spare_code').val('').trigger('change');
            $('#tblcomplainspare-old_serial_no').val(null).trigger('change');
            $('#tblcomplainspare-old_spare_status').val(null).trigger('change');
            $('#tblcomplainspare-new_serial_no').val(null).trigger('change');
            $('#tblcomplainspare-qty').val('');
            $('.btn-save-txn').removeClass('disabled no_pointer');

        } else {
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+err+'</div></div>');
        }
    });
    
  $('#tblcomplainspare-spare_code').on('change', function(){
        addBtnEnable();
    });
    
  function addBtnEnable(){
    var type = $('#tblcomplainspare-spare_code').val();

    if(type != '') {
        $('.add-asset-record').removeClass('disabled no_pointer');
    } else {
        $('.add-asset-record').addClass('disabled no_pointer');
    }
  }
  
$('#tblcomplainspare-spare_code').on('change', function(){
    hideSerialno();
})

function hideSerialno(){
    var spare_code = $('#tblcomplainspare-spare_code').val(); 
    var asset_code = $('#tblcomplain-asset_code').val(); 
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/complaint/tbl-complain/spare-is-serial']) . "',
        data: {'spare_code' : spare_code, 'asset_code' : asset_code},
        success: function(data) {
            var obj1 = $.parseJSON(data);
            if(obj1.status == 'success'){
                if(obj1.is_serial_number == 0){
                    $('#is_serial_number').val('no');
                    $('.field-tblcomplainspare-old_serial_no').parent('div').hide(); 
                    $('.field-tblcomplainspare-new_serial_no').parent('div').hide(); 
                    $('.field-tblcomplainspare-qty').parent('div').show(); 
                    $('#tblcomplainspare-old_serial_no').val(''); 
                    $('#tblcomplainspare-new_serial_no').val('');
                } else {
                    $('#is_serial_number').val('yes');
                    $('.field-tblcomplainspare-old_serial_no').parent('div').show(); 
                    $('.field-tblcomplainspare-new_serial_no').parent('div').show(); 
                    $('.field-tblcomplainspare-qty').parent('div').hide(); 
                }
            }
        },
    });
}

";
$this->registerJs($script, View::POS_END, 'create-complain');
?>