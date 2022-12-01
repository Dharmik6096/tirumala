<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeCriteria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-scheme-criteria-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'scheme_id')->textInput() ?>

    <?= $form->field($model, 'wef_date')->textInput() ?>

    <?= $form->field($model, 'min_pouring_day')->textInput() ?>

    <?= $form->field($model, 'min_pouring_qty')->textInput() ?>

    <?= $form->field($model, 'scheme_value')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
