<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblEiplAppFeedbackMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-eipl-app-feedback-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'user_code') ?>

    <?= $form->field($model, 'plant_code') ?>

    <?= $form->field($model, 'mcc_code') ?>

    <?= $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'member_code') ?>

    <?php // echo $form->field($model, 'feedback_item_id') ?>

    <?php // echo $form->field($model, 'feedback_message') ?>

    <?php // echo $form->field($model, 'feedback_message_datetime') ?>

    <?php // echo $form->field($model, 'user_type') ?>

    <?php // echo $form->field($model, 'feedback_status') ?>

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
