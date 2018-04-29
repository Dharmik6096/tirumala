<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSubcenterMiscSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-dcs-subcenter-misc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dcs_miscellaneous_code') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'deleted_at') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'flg_sentbox_entry') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'miscellaneous_code') ?>

    <?php // echo $form->field($model, 'subcenter_code') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
