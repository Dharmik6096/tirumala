<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\TblAndroidInstallation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-android-installation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'android_installation_id')->textInput() ?>

    <?= $form->field($model, 'organization_code')->textInput() ?>

    <?= $form->field($model, 'organization_type')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
