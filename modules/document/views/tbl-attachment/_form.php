<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\TblAttachment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-attachment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'doc_id')->textInput() ?>

    <?= $form->field($model, 'module_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'module_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attachment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attachment')->textInput() ?>

    <?= $form->field($model, 'lat_long')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thumbnail')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
