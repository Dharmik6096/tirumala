<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\DpuStationDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dpu-station-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'station_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flag_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flag_value')->textInput() ?>

    <?= $form->field($model, 'other_value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transferred_datetime')->textInput() ?>

    <?= $form->field($model, 'transferred_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'download_datetime')->textInput() ?>

    <?= $form->field($model, 'download_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ref_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dpu_header')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
