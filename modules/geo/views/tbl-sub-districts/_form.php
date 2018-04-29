<?php

use yii\bootstrap\ActiveForm;

$nameWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
}
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

<?= $form->errorSummary($model); ?>

<?php Yii::$app->warning->hiddenfields($nameWarning, ''); ?>
<div class="row">
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->state($model, $form, 'state', 'State'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->district($model, $form, 'tblsubdistricts-state', 'district_code', 'District'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'sub_district_code')->textInput(['maxlength' => true, 'readOnly' => $model->isNewRecord ? false : true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'sub_district_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
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
