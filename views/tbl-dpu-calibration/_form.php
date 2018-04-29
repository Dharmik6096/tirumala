<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblDpuCalibration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-dpu-calibration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'shift')->textInput() ?>

    <?= $form->field($model, 'milk_type_code')->textInput() ?>

    <?= $form->field($model, 'fat')->textInput() ?>

    <?= $form->field($model, 'snf')->textInput() ?>

    <?= $form->field($model, 'water')->textInput() ?>

    <?= $form->field($model, 'cycle')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
