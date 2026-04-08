<?php

use app\components\ActiveForm;

$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="panel-subheading">
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'project_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>     
        <div class="col-sm-2 mt25">
            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model, 'btn-login'); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>