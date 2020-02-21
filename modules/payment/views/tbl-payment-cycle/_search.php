<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblPaymentCycleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-payment-cycle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'payment_cycle_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'interval_value') ?>

    <?= $form->field($model, 'from_date') ?>

    <?= $form->field($model, 'from_shift') ?>

    <?php // echo $form->field($model, 'to_date') ?>

    <?php // echo $form->field($model, 'to_shift') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
