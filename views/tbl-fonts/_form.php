<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblFonts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-fonts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'font_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'font_size')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
