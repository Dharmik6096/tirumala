<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$milkType = $model->getMilkTypes();
if (!empty($model->milk_type)) {
    $model->milk_type_code = explode(',', $model->milk_type);
    foreach ($model['milk_type_code'] as $key => $row) {
        $selected[$row] = ['selected' => 'selected'];
    }
    $milkType['selected'] = $selected;
}
$nameWarning = 0;
$codeWarning = 0;
$bankWarning = 0;
$readonly = $type == 'create' ? FALSE : TRUE;
if (!empty($_POST) && !empty($_POST['warning']) && !empty($_POST['code_warning'])) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$bankWarning = !empty($_POST['bank_ac_warning']) ? $_POST['bank_ac_warning'] : 0;
if ($model->isNewRecord) {
    $disabled = false;
} else {
    $disabled = true;
}
$model->destination_type = 0;
$model->is_bmc = !empty($model->is_bmc) ? $model->is_bmc : 0;
$bmcDisable = $model->is_bmc == 1 ? 'disabled' : '';
$model->destination_code = 0;
//$model->route_code = 0;
$summary_model = $type == 'create' ? [$model] : $model;
$address = explode(",", $model->address);
$model->street1 = $address[0];
if (isset($address[1])) {
    $model->street2 = $address[1];
}

$vendor = ['EIPL' => 'EIPL', 'BIPL' => 'BIPL', 'PROMPT' => 'PROMPT'];
($type == 'edit') ? $disabled = true : $disabled = false;
//var_dump($bmc);exit;
//$disable = !empty($model->bmc_code) ? TRUE : FALSE;
$vendorDisable = $type == 'create' ? FALSE : TRUE;
if ($type == 'edit') {
    $vendorVal = Yii::$app->general->getforeignkey($model->societyVendors, 'vendor_code');
    $vendorDisable = !empty($vendorVal) ? TRUE : FALSE;
}
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
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<?= Html::hiddenInput('bank_ac_warning', $bankWarning, ['id' => 'bank_ac_warning']); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Society Details') ?></h4>
        </div>
        <div class="col-sm-2" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcsprovisional-union_code', 'plant_code', true, false, '', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcsprovisional-plant_code', 'mcc_plant_code', true, false, '', $readonly); ?>
        </div>
        <?php if ($showIsBMC == 1) { ?>
            <?= Html::activeTextInput($model, 'is_bmc') ?>
        <?php } ?>
        <?= Html::activeHiddenInput($model, 'destination_type') ?>
        <?= Html::activeHiddenInput($model, 'destination_code') ?>
        <div class="col-sm-2 <?= $bmcDisable ?>">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcsprovisional-mcc_plant_code', 'bmc_code', true, false, '', '', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->all_routes($model, $form, 'tbldcsprovisional-plant_code,tbldcsprovisional-mcc_plant_code,tbldcsprovisional-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'supervisor_employee_id')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'supervisor_employee_name')->textInput() ?>
        </div>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_dcs');
        if (!empty($keyPattern)) {
            ?>
            <?php if ($readonly || $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'dcs_code_ex')->textInput() ?>
                </div>
            <?php } ?>
            <?php if ($readonly || $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput() ?>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="col-sm-2">
            <?= $form->field($model, 'dcs_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->local($model, $form); ?>
        </div>
        <!-- <div class="clearfix"></div> -->
        <?php //Yii::$app->dropdown->ismilk($model, $form, 'milk_type_code', 'Milk Type');      ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'dcs_short_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->local($model, $form, 'local_short_name'); ?>
        </div>

        <div class="col-sm-2">
            <?php
            $model->vendor = $model->vendor_code;
            ?>
            <?= $form->field($model, 'vendor')->dropdownList($vendor, ['prompt' => 'Select Vendor']); ?>
        </div>
        <!--<div class="col-sm-2">-->
        <?= Yii::$app->dropdown->dropdownStatic('dpu_type', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('dpu_type'), false); ?>
        <!--</div>-->
        <?php /*
        if ($type == 'create') { ?>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->memberRateChart($model, $form, 'tbldcsprovisional-union_code', 'rate_chart_member', $model->getAttributeLabel('rate_chart_member')); ?>
            </div>
        <?php } */
        ?>

        <div class="col-sm-2">
            <?= $form->field($model, 'registration_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
        </div>
        <?php
        /* echo $form->field($model, 'registration_date', ['options' => ['class' => 'form-group col-sm-2']])->widget(DatePicker::className(), [
          'model' => $model,
          'attribute' => 'registration_date',
          'dateFormat' => 'dd-MM-yyyy',
          'clientOptions' => [ 'readonly' => true, 'value' => date('Y-m-d')],
          'options' => ['class' => 'form-control',]
          ]); */
        ?>
        <?php //Yii::$app->dropdown->depend_dropdown('route_code',$model, $form, 'tbldcsprovisional-union_code','form-group col-sm-2 padding-right-5 padding-left-0','Route');  ?>
        <!--    <div class="col-sm-2">
        <?php //$form->field($model, 'tin_no')->textInput(['maxlength' => true])   ?>
            </div>
            <div class="col-sm-2">
        <?php //$form->field($model, 'service_tax')->textInput(['maxlength' => true])   ?>
            </div>-->
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('dcs_type_code', $model, $form, 'form-group col-sm-2', Yii::t('app', 'Society Type')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('organisation_type', $model, $form, 'form-group col-sm-2', 'Organisation Type'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('scheme_type', $model, $form, 'form-group col-sm-2', 'Scheme Type'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'effective_date', 'form-group col-sm-2'); ?>
        </div>
        <!-- <div class="clearfix"></div> -->
        <?php
        /* echo $form->field($model, 'effective_date', ['options' => ['class' => 'form-group col-sm-2']])->widget(DatePicker::className(), [
          'model' => $model,
          'attribute' => 'effective_date',
          'dateFormat' => 'dd-MM-yyyy',
          'clientOptions' => [ 'readonly' => true, 'value' => date('Y-m-d')],
          'options' => ['class' => 'form-control',]
          ]); */
        ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'voter_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'fssi')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'fssi_expiry_date', '', FALSE, date('Y-m-d')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('gender', $model, $form, '', $model->getAttributeLabel('gender')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'dob'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'sap_vendor_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'secretory_info')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('is_type', $model, $form, '', 'Antibiotic Check', false, 'antibiotic_check', false); ?>    
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('collection', $model, $form, '', $model->getAttributeLabel('x_col2'), false, 'x_col2', false); ?>    
        </div>
        <div class="col-sm-2 number-validate">  
            <?= $form->field($model, 'ts_code_m')->textInput() ?>
        </div>
        <div class="col-sm-2 number-validate">  
            <?= $form->field($model, 'ts_code_e')->textInput() ?>
        </div>
        <div class='pull-left col-sm-4'>
            <?= Yii::t('app', 'Allow multiple collection entry for shift') ?><br/>
            <?= $form->field($model, 'same_milk_type', ['options' => ['class' => 'form-group col-sm-4 padding-left-0'], 'checkboxTemplate' => "<div class='checkbox' >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            <?= $form->field($model, 'diff_milk_type', ['options' => ['class' => 'form-group col-sm-4'], 'checkboxTemplate' => '<div class="checkbox" >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox(); ?>
        </div>
        <?php if ($showIsBMC == 0) { ?>
            <div class="col-sm-2 mt15">
                <?= $form->field($model, 'is_bmc', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-12 padding_10_0 theme-box">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Address Details</h4>
        </div>
        <!--    <div class="col-sm-2">
                <?php // $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
            </div>-->
        <div class="col-sm-2">
            <div class="col-sm-12">
                <?= $form->field($model, 'street1')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-12">
                <?= $form->field($model, 'street2')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
        </div>

        <?php
        //Yii::$app->dropdown->state($model, $form, 'state_code', 'State');
        ?>
        <?php // if($type=='create') { ?>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>
        <div class="col-sm-2" id="district_section">
            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tbldcsprovisional-union_code,tbldcsprovisional-state_code', 'district_code', Yii::t('app', 'District'), FALSE); ?>
        </div>
        <!--    <div class="col-sm-3">
        <?php //Yii::$app->dropdown->district($model, $form, 'tbldcsprovisional-state_code', 'district_code', 'District');    ?>
            </div>-->
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tbldcsprovisional-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', Yii::t('app', 'Sub District'), ''); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tbldcsprovisional-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', Yii::t('app', 'Village'), ''); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('block_code', $model, $form, 'tbldcsprovisional-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Block'); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tbldcsprovisional-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', Yii::t('app', 'Hamlet')); ?>
        </div>
        <?php // }    ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'aadhaar_no')->textInput() ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'is_aadhar_verify', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
    </div>
    <div class="col-md-12 padding_10_0 theme-box theme_border_top">
        <!-- <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Address Details</h4>
        </div> -->

        <div class="clearfix"></div>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Contact Details</h4>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'firstname')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'lastname')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'surname')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'email')->textInput() ?>
            </div>
            <!--<div class="col-sm-2">
                <?php // $form->field($model, 'local_contact_person')->textInput() ?>
            </div>-->
            <div class="col-sm-2">
                <?= $form->field($model, 'local_firstname')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'local_lastname')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'local_surname')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
            </div>
            <div class="col-sm-2">
                <?= Html::activeHiddenInput($model, 'detail_code', ['value' => $model->detail_code]) ?>
                <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Bank Details</h4>
            </div>
            <?php if (!empty($dist)) { ?>
                <?= Html::hiddenInput('union-dist', $dist, ['id' => 'tbldcsprovisional-district_code']) ?>
            <?php } ?>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->bankdepended($model, $form, 'tbldcsprovisional-district_code', 'bank_code', 'Bank'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tbldcsprovisional-bank_code', '', 'Branch', 'branch_code'); ?>                        
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
            <div class="col-sm-2 mt10">
                <?= $form->field($model, 'is_bank_verify', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            </div>
            <!--<div class="col-sm-2">
            <?= $form->field($model, 'is_default', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
            </div>-->
        <?= Yii::$app->dropdown->dropdownStatic('is_dispatch_mandate', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('is_dispatch_mandate'), false); ?>
        <!--</div>-->
        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'allow_multi_family_member', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <!--<div class="col-sm-3">-->
        <?php // $form->field($model, 'is_dispatch_mandate', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox();   ?>
        <!--</div>-->
        <!--        <div class="col-sm-2 mt10">
        <?= $form->field($model, 'is_weight_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
                </div>-->
        <!--        <div class="col-sm-2 mt10">
        <?= $form->field($model, 'is_quality_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
                </div>-->
        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'credit_sale_allow', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <?php
        if ($type == 'edit') {
            $model->milk_type_auto = $model->default_milk_type == 7 ? 1 : 0;
        }
        ?>

        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'milk_type_auto', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>

        <?php // if ($type == 'create') {    ?>
        <!--        <div class="col-sm-3">
        <?= Yii::$app->controls->active($model, $form); ?>
                </div>-->
        <?php // }   ?>
        <?= Html::hiddenInput('bmc', '', ['id' => 'bmc-data']); ?>
        <!-- <div class="clearfix"></div> -->
        <?php // if ($type == 'create') {     ?>
        <?php if ($model->provisional_from != 'mobile_update') { ?>
            <div class="col-sm-2 mt10">
                <?= $form->field($model, 'auto_member_create', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            </div>
        <?php } ?>
        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'is_chiller', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-2">
            <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
        </div>
        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'cutoff', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Lower Milk Type', FALSE, 'lower_milk_type'); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'cutoff_val')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'morning_kms')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'evening_kms')->textInput() ?>
        </div>
    </div>
    <div class="col-sm-2 mt10">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="col-sm-2 mt10">
        <?= $form->field($model, 'is_security_cheque', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt10 security_cheque">
        <?= $form->field($model, 'cheque_number')->textInput() ?>   
    </div>
    <div class="col-sm-2 mt10 number-validate security_cheque">
        <?= $form->field($model, 'cheque_amount')->textInput() ?>   
    </div>
    <div class="col-sm-2 mt10 security_cheque">
        <?= $form->field($model, 'cheque_bank')->textInput() ?>   
    </div>
    <div class="col-sm-2 mt10 security_cheque">
        <?= Yii::$app->controls->date($model, $form, 'security_return_date'); ?>
    </div>
    <div class="col-sm-2 mt10 number-validate security_cheque">
        <?= $form->field($model, 'security_return_amt')->textInput() ?>   
    </div>
    <div class="col-sm-2 mt10 security_cheque">
        <?= Yii::$app->dropdown->dropdownStatic('security_return_mode', $model, $form, '', $model->getAttributeLabel('security_return_mode'), false); ?>
    </div>
    <?= Html::hiddenInput('bmc', '', ['id' => 'bmc-data']); ?>
    <div class="row">
        <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?= Html::submitButton($type == 'create' ? Yii::t('app', 'NEXT') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary apply-shortcut', 'name' => 'submitBtn', 'value' => 'save']) ?>
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
        $('.field-tbldcsprovisional-supervisor_employee_id').addClass('disabled no_pointer');
    }
    if(supervisorName != '' && supervisorName != null){
        $('.field-tbldcsprovisional-supervisor_employee_name').addClass('disabled no_pointer');
    } 
    var delay=2000;
    securityCheque($('#tbldcsprovisional-is_security_cheque').prop('checked'));	
    $('#tbldcsprovisional-is_security_cheque').on('change', function() {
        securityCheque($(this).prop('checked'));
    });
    
    $('#tbldcsprovisional-bank_account_no').on('change', function(){
        $('#bank_ac_warning').val(0);
    });
    $('#tbldcsprovisional-branch_code').on('change', function(){
        $('#bank_ac_warning').val(0);
    });
    $('#tbldcsprovisional-ifsc').on('change', function(){
        $('#bank_ac_warning').val(0);
    });
   $('#tbldcsprovisional-branch_code').on('change',function(){
            var id = $('#tbldcsprovisional-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tbldcsprovisional-ifsc').val(obj1.code);
                                if(obj1.code!='')
                                    $('#tbldcsprovisional-ifsc').prop('readonly', true);
                                else
                                    $('#tbldcsprovisional-ifsc').prop('readonly', false);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
    milktypedisabled();
    $('#tbldcsprovisional-milk_type_auto').click(function(){
        milktypedisabled();
    });

    function securityCheque(check_value) {
        $('.security_cheque').hide();
        if(check_value == true){
            $('.security_cheque').show();
        }
    }

    function milktypedisabled(){
        if($('#tbldcsprovisional-milk_type_auto').is(':checked')) {
            $('#tbldcsprovisional-milk_type_code').parent('div').addClass('disabled');
            $('#tbldcsprovisional-cutoff').parent('div').addClass('disabledDiv');
            $('#tbldcsprovisional-cutoff').prop('checked',false);
            $('#tbldcsprovisional-lower_milk_type').val('');
            $('#tbldcsprovisional-lower_milk_type').trigger('change');
            $('#tbldcsprovisional-lower_milk_type').trigger('select2:select');
            $('#tbldcsprovisional-cutoff_val').val('');
        } else {
            $('#tbldcsprovisional-milk_type_code').parent('div').removeClass('disabled');
            cutoffdisabled();
        }
    }
    $('#tbldcsprovisional-pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
        return val.toUpperCase();
    });
   });
   
    $('#tbldcsprovisional-cutoff').parent('div').addClass('disabledDiv');
    $('#tbldcsprovisional-lower_milk_type').parent('div').addClass('disabledDiv');
    $('#tbldcsprovisional-cutoff_val').parent('div').addClass('disabledDiv');
    
    cutoffdisabled();
    cutoffValDisabled();

    $('#tbldcsprovisional-milk_type_code').click(function(){
        cutoffdisabled();  
        lowerMilkTypeVal();  
    });
    
    function lowerMilkTypeVal(){
        $('#tbldcsprovisional-lower_milk_type option').removeAttr('disabled'); 
        var milktype = $('#tbldcsprovisional-milk_type_code').val();
        var remove = 1;
        if(milktype.length == 2) {
            if(!Object.values(milktype).includes('2')) {
                remove = 2;
            } else if(!Object.values(milktype).includes('3')) {
                remove = 3;
            }
            $('#tbldcsprovisional-lower_milk_type option[value=\''+remove+'\']').prop('disabled', true); 
            var select2Instance = $('#tbldcsprovisional-lower_milk_type').data('select2');
            var resetOptions = select2Instance.options.options;
            $('#tbldcsprovisional-lower_milk_type').select2('destroy').select2(resetOptions);
//            $('#tbldcsprovisional-lower_milk_type').trigger('select2:select');
        }
    }
    function cutoffdisabled(){
        var lenCheck = $('#tbldcsprovisional-milk_type_code').val();
        var len = 0;
        if(lenCheck != null && lenCheck != undefined){
            len = lenCheck.length;
        }
        if(len == 2){
            $('#tbldcsprovisional-cutoff').parent('div').removeClass('disabledDiv');
        }else{
            $('#tbldcsprovisional-cutoff').parent('div').addClass('disabledDiv');
            $('#tbldcsprovisional-cutoff').prop('checked',false);
            $('#tbldcsprovisional-lower_milk_type').val('');
            $('#tbldcsprovisional-lower_milk_type').trigger('change');
            $('#tbldcsprovisional-lower_milk_type').trigger('select2:select');
            $('#tbldcsprovisional-cutoff_val').val('');
        }
    }

    $('#tbldcsprovisional-cutoff').click(function(){
        cutoffValDisabled();  
        lowerMilkTypeVal();  
    });

    function cutoffValDisabled(){
        if($('#tbldcsprovisional-cutoff').is(':checked')) {
            $('#tbldcsprovisional-lower_milk_type').parent('div').removeClass('disabledDiv');
            $('#tbldcsprovisional-cutoff_val').parent('div').removeClass('disabledDiv');
        } else {
            $('#tbldcsprovisional-cutoff_val').val('');
            $('#tbldcsprovisional-lower_milk_type').val('');
            $('#tbldcsprovisional-lower_milk_type').trigger('select2:select');
            $('#tbldcsprovisional-lower_milk_type').parent('div').addClass('disabledDiv');
            $('#tbldcsprovisional-cutoff_val').parent('div').addClass('disabledDiv');
        }
    }
    $('#tbldcsprovisional-bank_code').on('change',function(){
        $('#tbldcsprovisional-ifsc').val('');
    });
    $('#tbldcsprovisional-route_code').on('change', function(e) {
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
                        $('.field-tbldcsprovisional-supervisor_employee_name').addClass('disabled no_pointer');
                    } else {
                        $('#tbldcsprovisional-supervisor_employee_name').val(data.firstname);
                        if(data.firstname != '' && data.firstname != null){
                            $('.field-tbldcsprovisional-supervisor_employee_name').addClass('disabled no_pointer');
                        }
                    }
                    if(supervisorId != ''  && supervisorId != null){
                        $('.field-tbldcsprovisional-supervisor_employee_id').addClass('disabled no_pointer');
                    } else {
                        $('#tbldcsprovisional-supervisor_employee_id').val(data.employee_code);
                        if(data.employee_code != '' && data.employee_code != null){
                        alert('vivek');
                            $('.field-tbldcsprovisional-supervisor_employee_id').addClass('disabled no_pointer');
                        }
                    }
                }                               
            },
            error:function(data){
                //alert('Your data has not been submitted..Please try again');
            }
        });
    });
    enableDisableField();
    $('#tbldcsprovisional-is_aadhar_verify,#tbldcsprovisional-is_bank_verify').on('click',function(){
        enableDisableField();
    });
    function enableDisableField(){
        $('.field-tbldcsprovisional-aadhaar_no').removeClass('disabled no_pointer');
        $('.field-tbldcsprovisional-bank_code').removeClass('disabled no_pointer');
        $('.field-tbldcsprovisional-branch_code').removeClass('disabled no_pointer');
        $('.field-tbldcsprovisional-bank_account_no').removeClass('disabled no_pointer');

        if ($('#tbldcsprovisional-is_aadhar_verify').is(':checked')) {
            $('.field-tbldcsprovisional-aadhaar_no').addClass('disabled no_pointer');
        }
        if ($('#tbldcsprovisional-is_bank_verify').is(':checked')) {
            $('.field-tbldcsprovisional-bank_code').addClass('disabled no_pointer');
            $('.field-tbldcsprovisional-branch_code').addClass('disabled no_pointer');
            $('.field-tbldcsprovisional-bank_account_no').addClass('disabled no_pointer');
        }
    }
";
    $this->registerJs($script, View::POS_END, 'union-select');
    