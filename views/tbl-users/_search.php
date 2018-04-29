<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblUsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_code') ?>

    <?= $form->field($model, 'is_active') ?>

    <?= $form->field($model, 'create_at') ?>

    <?= $form->field($model, 'is_delete') ?>

    <?= $form->field($model, 'delete_at') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
