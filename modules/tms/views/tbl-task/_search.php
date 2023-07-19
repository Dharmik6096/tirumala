<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tms\models\TblTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'task_code') ?>

    <?= $form->field($model, 'task_type_code') ?>

    <?= $form->field($model, 'form_type_code') ?>

    <?= $form->field($model, 'task_performed_for') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'task_datetime') ?>

    <?php // echo $form->field($model, 'is_cancel') ?>

    <?php // echo $form->field($model, 'user_code') ?>

    <?php // echo $form->field($model, 'route_code') ?>

    <?php // echo $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'mcc_plant_code') ?>

    <?php // echo $form->field($model, 'plant_code') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <?php // echo $form->field($model, 'is_notified') ?>

    <?php // echo $form->field($model, 'notified_datetime') ?>

    <?php // echo $form->field($model, 'pick_datetime') ?>

    <?php // echo $form->field($model, 'response_datetime') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
