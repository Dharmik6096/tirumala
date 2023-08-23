<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblApprovalStagesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-approval-stages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'approval_stages_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'process_name') ?>

    <?= $form->field($model, 'approval_mode') ?>

    <?= $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
