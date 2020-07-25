<?php

use yii\bootstrap\ActiveForm;

$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->state($model, $form, 'state', 'State', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->district($model, $form, 'tblblocks-state', 'district', 'District'); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblblocks-district', 'form-group col-sm-12', 'Sub District', 'sub_district_code'); ?>
    </div>
<!--    <div class="col-sm-2">
        <? = $form->field($model, 'block_code')->textInput(['maxlength' => true, 'readOnly' => $model->isNewRecord ? false : true]) ?>
    </div>-->
    <div class="col-sm-2">
        <?= $form->field($model, 'block_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
