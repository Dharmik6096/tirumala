<?php

use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin([
            'validateOnBlur' => TRUE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>


<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= $form->field($model, 'state_code')->textInput(['maxlength' => true, 'readOnly' => $model->isNewRecord ? false : true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'state_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
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