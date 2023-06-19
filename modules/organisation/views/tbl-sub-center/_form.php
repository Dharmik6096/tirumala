<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$title = Yii::$app->label->title($type, 'sub center');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
$milkType = $model->getMilkTypes();
$nameWarning = 0;
$codeWarning = 0;
$readonly = $type == 'create' ? FALSE : TRUE;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
if ($model->isNewRecord) {
    $disable = '';
} else
    $disable = ' disabled';
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
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
        <?php Yii::$app->dropdown->federation($model, $form, 'federation_code', 'Federation'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union($model, $form, 'tblsubcenter-federation_code', 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblsubcenter-union_code','','Society'); ?>        
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'sub_center_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <?php //$form->field($model, 'dcs_code', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList(\app\components\GeneralFunctions::getActiveDcs(), ['prompt' => 'Select DCS']);  ?>
    <?php //Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group col-sm-3','Milk Type');  ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'is_bmc')->dropDownList([0 => 'No BMC', 1 => 'Non Cluster BMC', 2 => ' Cluster BMC', 3 => 'Pours to BMC'], ['prompt' => 'Select BMC']) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dcsdestinationtype($model, $form, 'tblsubcenter-is_bmc,tblsubcenter-union_code', 'destination_code', 'Destination Type'); ?>
    </div>
    <?= Html::activeHiddenInput($model, 'destination_type') ?>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblsubcenter-union_code,tblsubcenter-state_code', 'district_code', 'District'); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblsubcenter-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Sub District'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dcsvillage($model, $form, 'tblsubcenter-dcs_code,tblsubcenter-sub_district_code', 'village_code', 'Village'); ?>
    </div>
    <?php //Yii::$app->dropdown->depend_dropdown('village_code',$model, $form, 'tblsubcenter-sub_district_code','form-group col-sm-2 padding-right-5 padding-left-0','Village'); ?>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblsubcenter-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Hamlet'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('route', $model, $form, 'tblsubcenter-union_code','','Route'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'address')->textArea() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <strong class="form-subtitle">Other Detail</strong>
        <hr>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person_email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person_mobile_no')->textInput(['maxlength' => 10, 'class' => 'form-control check_mobile_length']) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <strong class="form-subtitle">Bank Detail</strong>
        <hr>
    </div>
    <?php //Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-3','Bank');  ?>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->bankdepended($model, $form, 'tblsubcenter-district_code', 'bank_code', 'Bank'); ?>
    </div>
    <?php // $form->field($model, 'bank_code', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList(\app\components\GeneralFunctions::getActiveBank(), ['prompt' => 'Select Bank']);  ?>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblsubcenter-bank_code', '', 'Branch','branch_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'bank_account_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'ifsc')->textInput(['maxlength' => true, 'readonly' => !empty($model->ifsc) ? true : false]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'upi_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form, 'form-group col-sm-2'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#tblsubcenter-branch_code').on('change',function(){
            var id = $('#tblsubcenter-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',    
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblsubcenter-ifsc').val(obj1.code);
                                if(obj1.code!='')
                                    $('#tblsubcenter-ifsc').prop('readonly', true);
                                else
                                    $('#tblsubcenter-ifsc').prop('readonly', false);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'branch-code');

$script = "

   $('#tblsubcenter-destination_code').change(function() {
        if($('#tblsubcenter-is_bmc').val()==3)
           $('#tblsubcenter-destination_type').val(0)
        else {
            var dcs_code=$('#tblsubcenter-destination_code option:selected').text().split('-')[1]=='MCC'?1:2;
            $('#tblsubcenter-destination_type').val(dcs_code);
        }
   });
";
$this->registerJs($script, View::POS_END, 'destination-type');
$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
