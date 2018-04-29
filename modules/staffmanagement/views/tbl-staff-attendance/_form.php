<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffAttendance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-attendance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flg_sentbox_entry')->textInput(['maxlength' => true]) ?>

    <?= Yii::$app->controls->active($model, $form); ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'lwp_date')->textInput() ?>

    <?= $form->field($model, 'lwp_type')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary_processed')->textInput() ?>

    <?= $form->field($model, 'sync_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_timestamp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'staff_member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_center_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
