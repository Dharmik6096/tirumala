<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-meeting-agenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meeting_agenda_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'detailed_agenda')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flg_sentbox_entry')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'meeting_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subject_line')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_timestamp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meeting_type_code')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
