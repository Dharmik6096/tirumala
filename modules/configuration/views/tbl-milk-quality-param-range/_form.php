<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkQualityParamRange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-quality-param-range-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'process_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'animal_type_code')->textInput() ?>

    <?= $form->field($model, 'min_fat')->textInput() ?>

    <?= $form->field($model, 'max_fat')->textInput() ?>

    <?= $form->field($model, 'min_snf')->textInput() ?>

    <?= $form->field($model, 'max_snf')->textInput() ?>

    <?= $form->field($model, 'min_clr')->textInput() ?>

    <?= $form->field($model, 'max_clr')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'x_col1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col5')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
