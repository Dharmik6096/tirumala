<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$class = $type == 'edit' ? 'disabled' : '';
$readonly = $type == 'edit' ? false : true;

$form = ActiveForm::begin([
            'options' => ['id' => 'staff-designation-form'],
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?= $form->field($model, 'designation_name', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('designation_type', $model, $form, 'form-group', $model->getAttributeLabel('designation_type'), false, 'designation_type', false); ?>
    </div>
    <div class="col-sm-6 mt25">
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

