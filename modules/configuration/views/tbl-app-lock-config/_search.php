<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblAppLockConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-app-lock-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'config_code') ?>

    <?= $form->field($model, 'config_name') ?>

    <?= $form->field($model, 'config_key') ?>

    <?= $form->field($model, 'config_for') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
