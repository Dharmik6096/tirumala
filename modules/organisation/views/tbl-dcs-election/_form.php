
<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?php echo $form->errorSummary($model); ?>
<div class="row">

    <div class="col-sm-2">
        <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $model->dcsCode->dcs_name]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'election_date', 'form-group col-sm-2',FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'tenure_from', 'form-group col-sm-2',FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'tenure_to', 'form-group col-sm-2',FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea(['maxlength' => true]) ?>
    </div>
    <?= Html::activeHiddenInput($model, 'dcs_code') ?>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>