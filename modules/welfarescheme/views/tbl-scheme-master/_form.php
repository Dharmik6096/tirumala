<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'disabled';
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'scheme_name')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('applicable_for', $model, $form, '', $model->getAttributeLabel('applicable_for'), false, 'applicable_for', FALSE, FALSE, FALSE); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'start_date', '', false, false, $readonly); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'end_date', '', false, false, false); ?>
    </div>

    <?php if ($type == 'create') { ?>
        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'min_pouring_day')->textInput(['maxlength' => true]) ?>  
        </div>

        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'min_pouring_qty')->textInput(['maxlength' => true]) ?>  
        </div>

        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'scheme_value')->textInput(['maxlength' => true]) ?>  
        </div>

    <?php } ?>

    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textArea(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>  
    </div>
</div>
<?php ActiveForm::end(); ?>
