<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
    'validateOnBlur' => false,
    'validateOnChange' => FALSE,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
    'fieldConfig' => []
]);

?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>

    <div class="col-sm-2">
        <?= Html::hiddenInput('has_form', 1, ['id' => 'has_form']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('task_form_type', $model, $form, 'tblformtype-union_code,has_form', '', $model->getAttributeLabel('task_type_code'), 'task_type_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'form_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>