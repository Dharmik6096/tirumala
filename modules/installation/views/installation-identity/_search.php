<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\InstallationIdentitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="installation-identity-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'originating_org_id') ?>

    <?= $form->field($model, 'originating_org_type') ?>

    <?= $form->field($model, 'organization_code') ?>

    <?= $form->field($model, 'organization_type') ?>

    <?php // echo $form->field($model, 'installed') ?>

    <?php // echo $form->field($model, 'db_path') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'flg_sentbox_entry') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
