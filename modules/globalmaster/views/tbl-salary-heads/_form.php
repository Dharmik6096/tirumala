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
            'options' => ['id' => 'salary-head-form'],
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?= $form->field($model, 'salary_head_name', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true, 'class' => 'form-control']) ?>    <div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdownStatic('type', $model, $form, 'form-group', $model->getAttributeLabel('salary_head_type'), false, 'salary_head_type', false); ?>
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

