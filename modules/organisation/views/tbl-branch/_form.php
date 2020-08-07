<?php

use yii\bootstrap\ActiveForm;

$nameWarning = 0;
$readonly = $type == 'create' ? FALSE : TRUE;
if (!empty($_POST) && !empty($_POST['warning'])) {
    $nameWarning = $_POST['warning'];
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
<?php Yii::$app->warning->hiddenfields($nameWarning,''); ?>

<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-3', 'Bank'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'ifsc')->textInput(['maxlength' => 11]) ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->bankdistrict($model, $form, 'tblbranch-state_code,tblbranch-bank_code', 'district_code', 'District'); ?>
    </div>
    <?php //Yii::$app->dropdown->dropdown('state_code', $model, $form, 'form-group col-sm-3','State');    ?>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblbranch-district_code', 'form-group padding-right-5 col-sm-2', 'Sub District'); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblbranch-sub_district_code', 'form-group padding-right-5 col-sm-2', 'Village'); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblbranch-village_code', 'form-group padding-right-5 col-sm-2', 'Hamlet'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'address')->textarea() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
    </div>
    <div class="col-sm-3">
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
