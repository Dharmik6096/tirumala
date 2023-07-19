<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tms\models\TblTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_type_code')->textInput() ?>

    <?= $form->field($model, 'form_type_code')->textInput() ?>

    <?= $form->field($model, 'task_performed_for')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'task_datetime')->textInput() ?>

    <?= $form->field($model, 'is_cancel')->textInput() ?>

    <?= $form->field($model, 'user_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bmc_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_notified')->textInput() ?>

    <?= $form->field($model, 'notified_datetime')->textInput() ?>

    <?= $form->field($model, 'pick_datetime')->textInput() ?>

    <?= $form->field($model, 'response_datetime')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
