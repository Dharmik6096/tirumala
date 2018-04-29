<?php

use yii\bootstrap\ActiveForm;

$nameWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
}

$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="panel-subheading">
    <?php echo $form->errorSummary($model); ?>
    <?= Yii::$app->warning->hiddenfields($nameWarning,''); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'district_code')->textInput(['maxlength' => true, 'readOnly' => ($model->isNewRecord) ? false : true,]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'district_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->controls->local($model, $form); ?>
        </div>
        <div class="col-sm-3">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
        </div>       
        <div class="col-sm-3 mt25">
            <?= Yii::$app->controls->active($model, $form); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>