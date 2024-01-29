<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblEiplAppFeedbackMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-eipl-app-feedback-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_code')->textInput() ?>

    <?= $form->field($model, 'plant_code')->textInput() ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'member_code')->textInput() ?>

    <?= $form->field($model, 'eipl_app_feedback_item_code')->textInput() ?>

    <?= $form->field($model, 'feedback_message')->textInput() ?>

    <?= $form->field($model, 'feedback_message_datetime')->textInput() ?>

    <?= $form->field($model, 'user_type')->textInput() ?>

    <?= $form->field($model, 'feedback_status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
