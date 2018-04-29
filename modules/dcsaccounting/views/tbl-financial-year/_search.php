<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblFinancialYearSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-financial-year-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'ending_date') ?>

    <?php // echo $form->field($model, 'flg_sentbox_entry') ?>

    <?php // echo $form->field($model, 'is_active')->checkbox() ?>

    <?php // echo $form->field($model, 'is_delete')->checkbox() ?>

    <?php // echo $form->field($model, 'starting_date') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
