<?php

use yii\bootstrap5\ActiveForm;
use yii\jui\DatePicker;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

//$title = Yii::$app->label->title($type, 'Bmc');
$button = Yii::$app->label->button($type);
$readonly = $type == 'create' ? FALSE : TRUE;
//$this->title = Yii::t('app', $title);
$bmc_url = Url::to(['create']);
$dcs_url = Url::to(['tbl-dcs/create']);
$model->is_mcc = $model->isNewRecord ? 0 : $model->is_mcc;

$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$disabled = ($model->is_mcc == 1) ? TRUE : FALSE;
$milkType = $model->getMilkTypes();
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
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">BMC Details</h4>
        </div>
        <div class="col-sm-2" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <?= Html::activeHiddenInput($model, 'is_mcc') ?>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->union_mcc($model, $form, 'tbldcsbmc-union_code', 'mcc_plant_code', 'MCC'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('bmc_type', $model, $form, '', 'BMC Type', false, 'bmc_type_code'); ?>        
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('channel', $model, $form, '', 'Channel Type', false, 'x_col1'); ?>        
        </div>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_bmc');
        if (!empty($keyPattern)) {
            ?>
            <?php if (!$readonly && $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'bmc_code_ex')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
            <?php if (!$readonly && $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'bmc_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'local_name')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity'); ?>        
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('manufacture', $model, $form, 'form-group col-sm-12', 'Manufacturer'); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
        </div>
        <!--    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group padding-right-5 col-sm-3', 'Milk Quality Type', false, 'bmc_milk_type'); ?>
            </div> -->
        <!--<div class="clearfix"></div>-->
        <div class="col-sm-2">
            <?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tbldcsbmc-union_code,tbldcsbmc-state_code', 'district_code', 'District', FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tbldcsbmc-district_code', 'form-group col-sm-4', 'Sub District', 'sub_district_code'); ?>
        </div>
        <!--        <div class="clearfix"></div>-->
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tbldcsbmc-sub_district_code', 'form-group col-sm-4', 'Village', ''); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tbldcsbmc-village_code', 'form-group col-sm-4', 'Hamlet'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('billing_type', $model, $form, 'form-group', $model->getAttributeLabel('billing_type'), false, 'billing_type', false); ?>
        </div>
        <div class="col-sm-2">  
            <?= $form->field($model, 'sap_vendor_code')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('is_type', $model, $form, '', 'Antibiotic Check', false, 'antibiotic_check', false); ?>    
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'aadhaar_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2 mt15">
            <?= $form->field($model, 'rate_calculate_on_merge', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <div class="col-sm-2 mt15">
            <?= Yii::$app->controls->active($model, $form); ?>
        </div>
        <?= Html::hiddenInput('from_bmc', 0, ['id' => 'bmc']); ?>
    </div>
    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>

        <div class="clearfix"></div>

        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Bank Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-bank-details/_form', [
            'model' => $bankDetails,
            'form' => $form,
            'dist_field' => 'tbldcsbmc-district_code'
        ])
        ?>
    <?php } ?>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_weight_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_quality_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>

            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, str_replace(Url::base(), '', Url::previous())); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>