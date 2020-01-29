<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\TblBulkNotification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bulk-notification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'plant_code')->textInput() ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'member_code')->textInput() ?>

    <?= $form->field($model, 'app_type')->textInput() ?>

    <?= $form->field($model, 'login_type')->textInput() ?>

    <?= $form->field($model, 'wef_date')->textInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'message')->textInput() ?>

    <?= $form->field($model, 'campaign_name')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'receiver_type')->textInput() ?>

    <?= $form->field($model, 'content_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'entry_datetime')->textInput() ?>

    <?= $form->field($model, 'pickup_datetime')->textInput() ?>

    <?= $form->field($model, 'response_datetime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
