<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblTankerRateAutoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-tanker-rate-auto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'wef_date') ?>

    <?= $form->field($model, 'animal_type') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'fat') ?>

    <?php // echo $form->field($model, 'flg_sentbox_entry') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'ratetype') ?>

    <?php // echo $form->field($model, 'rtpl') ?>

    <?php // echo $form->field($model, 'snf') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'animal_type_code') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'purchase_code') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
