<?php

use yii\bootstrap5\ActiveForm;

$nameWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
}

$readonly = $type == 'create' ? FALSE : TRUE;
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="panel-subheading">
    <?php echo $form->errorSummary($model); ?>
    <?= Yii::$app->warning->hiddenfields($nameWarning,''); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'district_code')->textInput(['maxlength' => true, 'readOnly' => ($model->isNewRecord) ? false : true,]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'district_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->local($model, $form); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>       
        <div class="col-sm-2 mt15">
            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>