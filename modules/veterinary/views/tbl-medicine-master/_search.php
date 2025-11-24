<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\veterinary\models\TblMedicineMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-medicine-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'medicine_id') ?>

    <?= $form->field($model, 'medicine_name') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
