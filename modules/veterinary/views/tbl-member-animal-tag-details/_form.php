<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\veterinary\models\TblMemberAnimalTagDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-member-animal-tag-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tag_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'animal_type_id')->textInput() ?>

    <?= $form->field($model, 'gender_id')->textInput() ?>

    <?= $form->field($model, 'breed_id')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'no_of_calving')->textInput() ?>

    <?= $form->field($model, 'last_date_of_calving')->textInput() ?>

    <?= $form->field($model, 'pregnancy_status')->textInput() ?>

    <?= $form->field($model, 'pregnancy_month')->textInput() ?>

    <?= $form->field($model, 'pregnancy_month_on_date')->textInput() ?>

    <?= $form->field($model, 'milking_status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
