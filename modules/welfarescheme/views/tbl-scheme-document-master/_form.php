<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('doc_group', $model, $form, '', $model->getAttributeLabel('doc_group'), false, 'doc_group', FALSE, FALSE, FALSE); ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'doc_name')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('doc_ext', $model, $form, '', $model->getAttributeLabel('doc_ext'), false, 'doc_ext', FALSE, FALSE, FALSE); ?>
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

