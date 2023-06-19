<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */
$readonly = ($type == 'edit' && $model->role_name == 'VLC_ADMIN' || $model->role_name == 'BMC_ADMIN' || $model->role_name == 'VLC_SUPERVISOR' || $model->role_name == 'BMC_SUPERVISOR') ? TRUE : FALSE;
?>

<?php
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
        <?= $form->field($model, 'role_name')->textInput(['maxlength' => true, 'readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
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


