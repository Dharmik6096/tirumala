<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?= $form->errorSummary($model); ?>
<div class="row">

    <div class="col-sm-2">
        <?= $form->field($model, 'complain_type')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('complain_escalation_name', $model, $form, '', $model->getAttributeLabel('complain_escalation_code'), false, 'complain_escalation_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('complain_for', $model, $form, '', $model->getAttributeLabel('complain_for'), false, 'complain_for', FALSE, FALSE, FALSE); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
