<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\complain\models\TblComplainSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-complaint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'complaint_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'dcs_code') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'complaint_type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'resolve_date') ?>

    <?php // echo $form->field($model, 'resolve_remarks') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'delete_at') ?>

    <?php // echo $form->field($model, 'delete_by') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
