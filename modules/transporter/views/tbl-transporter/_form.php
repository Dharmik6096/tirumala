<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$disable_ifsc = !empty($model->ifsc) && !empty($model->bank_code) ? true : false;
$readonly=$type=='create'?FALSE:TRUE;
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union',$readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'transporter_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'registration_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <p class="form-subtitle">Address Details</p>
        <hr class="hr10">
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'address')->textarea() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tbltransporter-union_code,tbltransporter-state_code', 'district_code', 'District',FALSE,$readonly); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tbltransporter-district_code', 'form-group col-sm-4', 'Sub District', 'sub_district_code', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tbltransporter-sub_district_code', 'form-group col-sm-4', 'Village','',$readonly); ?>
    </div>
    <div class="col-sm-3">  
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tbltransporter-village_code', 'form-group col-sm-4', 'Hamlet'); ?>
    </div>
    <div class="col-sm-3">  
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'phone_no')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <p class="form-subtitle">Contact Details</p>
        <hr class="hr10">
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'local_contact_person')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'mobile_no')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <p class="form-subtitle">Bank Details</p>
        <hr class="hr10">
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->bankdepended($model, $form, 'tbltransporter-district_code', 'bank_code', 'Bank'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tbltransporter-bank_code', '', 'Branch', 'branch_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'bank_account_no')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <!--<? = $form->field($model, 'ifsc')->textInput(['readonly' => $disable_ifsc]) ?>-->
        <?= $form->field($model, 'ifsc')->textInput(['readonly' => true]) ?>        
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'gstin')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'tds_per')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pan_no')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'beneficiary_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'agreement_no')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'declaration')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'security_cheque_no')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'security_amount')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
    $('#tbltransporter-bank_code').on('change',function(){
        $('#tbltransporter-ifsc').val('');
    });
    
   $('#tbltransporter-branch_code').on('change',function(){
            var id = $('#tbltransporter-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tbltransporter-ifsc').val(obj1.code);
//                                if(obj1.code!='')
//                                    $('#tbltransporter-ifsc').prop('readonly', true);
//                                else
//                                    $('#tbltransporter-ifsc').prop('readonly', false);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'union-select');

$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
