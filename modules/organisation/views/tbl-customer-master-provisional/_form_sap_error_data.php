<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$model->ref_code = ($type != 'create' && empty($model->ref_code)) ? $model->customer_code_ex : $model->ref_code;
$nameWarning = 0;
$nameWarning = !empty($_POST['warning']) ? $_POST['warning'] : 0;
?>

<?php
$form = ActiveForm::begin([
    'options' => [],
    'validateOnBlur' => FALSE,
    'validateOnChange' => FALSE,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
]);
?>
<?php echo $form->errorSummary($model); ?>
<?= Html::hiddenInput('warning', $nameWarning, ['id' => 'warning']); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-6 padding_10_0 theme-box theme_border_right row">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Provisional Customer Details') ?></h4>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcustomermasterprovisional-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcustomermasterprovisional-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcustomermasterprovisional-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
        </div>
        <div class="col-sm-4 DCS">
            <?= Yii::$app->dropdown->all_routes($model, $form, 'tblcustomermasterprovisional-plant_code,tblcustomermasterprovisional-mcc_plant_code,tblcustomermasterprovisional-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'supervisor_employee_id')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'supervisor_employee_name')->textInput() ?>
        </div>
        <div class="col-sm-4 ">
            <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('customer_type'), $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'customer_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'aadhaar_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
        </div>
    </div>
    <div class="col-md-6 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Address Details</h4>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->state($model, $form, 'state_code', $model->getAttributeLabel('state_code'), FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('district_code', $model, $form, 'tblcustomermasterprovisional-state_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('district_code'), 'district_code', FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblcustomermasterprovisional-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('sub_district_code'), 'sub_district_code', FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblcustomermasterprovisional-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('village_code'), 'village_code', FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblcustomermasterprovisional-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('hamlet_code'), 'hamlet_code', FALSE); ?>
        </div>
    </div>
    <div class="col-md-12 padding_10_0 theme-box theme_border_top">
        <div class="clearfix"></div>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Bank Details</h4>
        </div>

        <div class="col-sm-2">
            <?= Yii::$app->dropdown->bankdepended($model, $form, 'tblcustomermasterprovisional-district_code', 'bank_code', 'Bank'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblcustomermasterprovisional-bank_code', '', 'Branch', 'branch_code'); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'bank_account_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'ifsc')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'beneficiary_name')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary apply-shortcut btn-login', 'name' => 'submitBtn', 'value' => 'save']) ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->cancel($model); ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php
$script = "
    var supervisorId = '$model->supervisor_employee_id';
    var supervisorName = '$model->supervisor_employee_name';
    if(supervisorId != '' && supervisorId != null){
        $('.field-tblcustomermasterprovisional-supervisor_employee_id').addClass('disabled no_pointer');
    }
    if(supervisorName != '' && supervisorName != null){
        $('.field-tblcustomermasterprovisional-supervisor_employee_name').addClass('disabled no_pointer');
    } 
    $('#tblcustomermasterprovisional-bank_account_no').on('change', function(){
        $('#warning').val(0);
    });
    $('#tblcustomermasterprovisional-branch_code').on('change', function(){
        $('#warning').val(0);
    });
    $('#tblcustomermasterprovisional-ifsc').on('change', function(){
        $('#warning').val(0);
    });
    
    $('#tblcustomermasterprovisional-bank_code').on('change',function(){
        $('#tblcustomermasterprovisional-ifsc').val('');
    });
    
    $('#tblcustomermasterprovisional-branch_code').on('change',function(){
            var id = $('#tblcustomermasterprovisional-branch_code').val();
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                data: 'id='+id,
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    $('#tblcustomermasterprovisional-ifsc').val(obj1.code);
                    if(obj1.code!='')
                        $('#tblcustomermasterprovisional-ifsc').prop('readonly', true);
                    else
                        $('#tblcustomermasterprovisional-ifsc').prop('readonly', false);
                },
                error:function(data){
                    //alert('Your data has not been submitted..Please try again');
                }
            });
    });
    $('#tblcustomermasterprovisional-route_code').on('change', function(e) {
        var module_code = $(this).val();
        var module_name = 'routeMapping';
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/details/tbl-contact-details/contact-details']) . "',
            data: 'module_code='+module_code+'&module_name='+module_name,
            success: function(response) {
                var obj1 = $.parseJSON(response);
                var data = obj1.data;
                if(data){
                    if(supervisorName != '' && supervisorName != null){
                        $('.field-tblcustomermasterprovisional-supervisor_employee_name').addClass('disabled no_pointer');
                    } else {
                        $('#tblcustomermasterprovisional-supervisor_employee_name').val(data.firstname);
                        if(data.firstname != '' && data.firstname != null){
                            $('.field-tblcustomermasterprovisional-supervisor_employee_name').addClass('disabled no_pointer');
                        }
                    }
                    if(supervisorId != ''  && supervisorId != null){
                        $('.field-tblcustomermasterprovisional-supervisor_employee_id').addClass('disabled no_pointer');
                    } else {
                        $('#tblcustomermasterprovisional-supervisor_employee_id').val(data.employee_code);
                        if(data.employee_code != '' && data.employee_code != null){
                            $('.field-tblcustomermasterprovisional-supervisor_employee_id').addClass('disabled no_pointer');
                        }
                    }
                }
            },
            error:function(data){
                //alert('Your data has not been submitted..Please try again');
            }
        });
    });
    $('#tblcustomermasterprovisional-pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
        return val.toUpperCase();
    });
   });
   
";
$this->registerJs($script, View::POS_END, 'provisional_customer_master_create');
?>