<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblUnionCreditLimitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-union-credit-limit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'union_credit_limit_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'credit_type') ?>

    <?= $form->field($model, 'credit_value') ?>

    <?= $form->field($model, 'wef_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
