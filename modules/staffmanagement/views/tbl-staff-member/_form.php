<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'staff_member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aadhar_card_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_account_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_date')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flg_sentbox_entry')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ifsc')->textInput(['maxlength' => true]) ?>

    <?= Yii::$app->controls->active($model, $form); ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_mode')->textInput() ?>

    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'staff_member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_timestamp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tenure_from_date')->textInput() ?>

    <?= $form->field($model, 'tenure_to_date')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'blood_group_id')->textInput() ?>

    <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'caste_category_code')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'designation_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'district_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender_id')->textInput() ?>

    <?= $form->field($model, 'hamlet_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_center_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_district_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'village_code')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
