<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\veterinary\models\TblMemberAnimalTagDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-member-animal-tag-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'member_animal_tag_id') ?>

    <?= $form->field($model, 'dcs_code') ?>

    <?= $form->field($model, 'member_code') ?>

    <?= $form->field($model, 'mobile_no') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'tag_no') ?>

    <?php // echo $form->field($model, 'animal_type_id') ?>

    <?php // echo $form->field($model, 'gender_id') ?>

    <?php // echo $form->field($model, 'breed_id') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'no_of_calving') ?>

    <?php // echo $form->field($model, 'last_date_of_calving') ?>

    <?php // echo $form->field($model, 'pregnancy_status') ?>

    <?php // echo $form->field($model, 'pregnancy_month') ?>

    <?php // echo $form->field($model, 'pregnancy_month_on_date') ?>

    <?php // echo $form->field($model, 'milking_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
