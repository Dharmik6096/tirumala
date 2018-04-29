<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\complain\models\TblComplainActivitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-complaint-activity-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'complaint_code') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'remarks') ?>

    <?= $form->field($model, 'issue_type') ?>

    <?php // echo $form->field($model, 'contact_person') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
