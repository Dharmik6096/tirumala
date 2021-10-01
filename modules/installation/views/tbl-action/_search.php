<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\TblActionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-action-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'action_code') ?>

    <?= $form->field($model, 'action_name') ?>

    <?= $form->field($model, 'menu_level') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'parent_code') ?>

    <?php // echo $form->field($model, 'action_type') ?>

    <?php // echo $form->field($model, 'action_for') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
