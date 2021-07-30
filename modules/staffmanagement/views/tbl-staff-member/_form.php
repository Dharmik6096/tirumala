<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$class = $type == 'edit' ? 'disableDiv' : '';
$readonly = $type == 'edit' ? true : false;
$disable = $type == 'edit' ? (!empty($model->tenure_to_date) ? TRUE : FALSE) : true;

$form = ActiveForm::begin([
            'options' => ['id' => 'staff-member-form'],
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-6 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Staff Member Details</h4>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-4 number-validate">
            <?= $form->field($model, 'ex_staff_member_code')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'staff_member_name', ['options' => ['class' => 'form-group disable_enab']])->textInput()->label(Yii::t('app', 'Name')) ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->date($model, $form, 'birth_date'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('blood-group', $model, $form, '', 'Blood Group', FALSE, 'blood_group_code'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('gender', $model, $form, '', 'Gender'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('qualification', $model, $form, '', 'Qualification'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('caste-category', $model, $form, '', 'Caste/Category'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('designation_code', $model, $form, 'col-sm-3 form-group ' . $class, $model->getAttributeLabel('designation_code'), $readonly); ?>
        </div>
    </div>
    <div class="col-md-6 padding_10_0 theme-box theme_border_left">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Member Address Details</h4>
        </div>
        <?= $form->field($model, 'address', ['options' => ['class' => 'form-group col-sm-4 disable_enab']])->textarea() ?>
        <div class="col-sm-4">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>
        <div class="disable_enab">    
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblstaffmember-union_code,tblstaffmember-state_code', 'district_code', 'District'); ?>
            </div>
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblstaffmember-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Sub District'); ?>
            </div>
        </div>  
        <div class="col-sm-4">
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblstaffmember-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Village'); ?>
        </div>

        <div class="col-sm-4">
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblstaffmember-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Hamlet'); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'email_id')->textInput() ?>
        </div>
    </div>
    <div class="col-md-12 padding_10_0 theme-box">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Member Bank Details</h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'tenure_from_date', 'form-group col-sm-2 ' . $class, FALSE, FALSE, $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'tenure_to_date', 'form-group col-sm-2 ' . $class, FALSE, FALSE, $disable); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('is_on_role', $model, $form, 'form-group', $model->getAttributeLabel('is_on_role'), false, 'is_on_role', false); ?>
        </div>
        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'uan_no')->textInput() ?>
        </div>
        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'esic_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'pf_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('payment_mode_member', $model, $form, 'form-group', $model->getAttributeLabel('payment_mode'), false, 'payment_mode', false); ?>
        </div>

        <?= $form->field($model, 'aadhar_card_no', ['options' => ['class' => 'form-group col-sm-2 disable_enab']])->textInput(['maxlength' => 12]) ?>
        <?= $form->field($model, 'pan_no', ['options' => ['class' => 'form-group col-sm-2 disable_enab']])->textInput(['maxlength' => true]) ?>
        <div id="bank-detail">  
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->bankdepended($model, $form, 'tblstaffmember-district_code', 'bank_code', 'Bank'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblstaffmember-bank_code', '', 'Branch', 'branch_code'); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'bank_account_no')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'ifsc')->textInput(['readonly' => true]) ?>        
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-sm-12 shortcut-main margin-top-10" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
    $( document ).ready(function() {
       $('#tblstaffmember-bank_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
          bankdiv();
          calculateIFSC();
        });
    });
    
    $('#tblstaffmember-branch_code').on('change',function(){
      calculateIFSC();
    });

    function calculateIFSC(){
        var id = $('#tblstaffmember-branch_code').val();
            if(id != null && id != ''){  
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['get-ifsc-code']) . "',    
                    data: 'id='+id,
                    success: function(data) {
                            var obj1 = $.parseJSON(data);
                            $('#tblstaffmember-ifsc').val(obj1.code);
                            if(obj1.code!='')
                                $('#tblstaffmember-ifsc').prop('readonly', true);
                            else
                                $('#tblstaffmember-ifsc').prop('readonly', false);
                    },
                    error:function(data){
                                //alert('Your data has not been submitted..Please try again');
                            }
                });
            }    
    }  
    $('#tblstaffmember-payment_mode').on('change',function(){
      bankdiv();
    });
    function bankdiv(){
        var pay_type = $('#tblstaffmember-payment_mode').val();
        if(pay_type==0 && pay_type != null && pay_type != ''){
            $('#bank-detail').find('input:text').val('');
            $('#bank-detail').find('select').val('');
            $('#tblstaffmember-bank_code').val('');
            $('#tblstaffmember-bank_code').trigger('change');
            $('#tblstaffmember-bank_code').trigger('select2:select');
            $('#bank-detail *').attr('disabled', true);
        }else{
            $('#bank-detail *').removeAttr('disabled');
        }
    } 
     $('#tblstaffmember-pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
        return val.toUpperCase();
    });
   });
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
