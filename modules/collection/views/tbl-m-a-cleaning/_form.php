<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACleaning */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-macleaning-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'BMCCode')->textInput() ?>

    <?= $form->field($model, 'PPCode')->textInput() ?>

    <?= $form->field($model, 'dtdate')->textInput() ?>

    <?= $form->field($model, 'shift')->textInput() ?>

    <?= $form->field($model, 'cleaningdatetime')->textInput() ?>

    <?= $form->field($model, 'CleaningCycles')->textInput() ?>

    <?= $form->field($model, 'Measuring')->textInput() ?>

    <?= $form->field($model, 'counter')->textInput() ?>

    <?= $form->field($model, 'updatedby')->textInput() ?>

    <?= $form->field($model, 'updateddate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
