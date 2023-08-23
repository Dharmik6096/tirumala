<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblBillHeadDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bill-head-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bill_head_detail_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'bill_head_code') ?>

    <?= $form->field($model, 'payment_cycle_code') ?>

    <?= $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'is_installment') ?>

    <?php // echo $form->field($model, 'no_installment') ?>

    <?php // echo $form->field($model, 'is_active') ?>

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
