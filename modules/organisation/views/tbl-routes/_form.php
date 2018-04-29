<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
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
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form,'union_code','Union'); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('bmc', $model, $form, 'tblroutes-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'BMC'); ?>
    </div>
   <div class="col-sm-3">
        <?= $form->field($model, 'route_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'route_length_kms')->textInput(['maxlength' => true, 'class' => 'form-control number-validate']) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('vehicle_type_code', $model, $form, 'form-group col-sm-3', 'Vehicle'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'capacity')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?=
        $form->field($model, 'start_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',])->label('Start Time (24 Hrs)');
        ?>
    </div>
    <div class="col-sm-3">
        <?=
        $form->field($model, 'return_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',])->label('Return Time (24 Hrs)')
        ?>
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