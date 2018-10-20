<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACAlibration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-macalibration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'BMCCode')->textInput() ?>

    <?= $form->field($model, 'PPCode')->textInput() ?>

    <?= $form->field($model, 'dtdate')->textInput() ?>

    <?= $form->field($model, 'shift')->textInput() ?>

    <?= $form->field($model, 'CalibrationFat')->textInput() ?>

    <?= $form->field($model, 'CalibrationSnf')->textInput() ?>

    <?= $form->field($model, 'CalibrationWater')->textInput() ?>

    <?= $form->field($model, 'MilkType')->textInput() ?>

    <?= $form->field($model, 'updatedby')->textInput() ?>

    <?= $form->field($model, 'updateddate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
