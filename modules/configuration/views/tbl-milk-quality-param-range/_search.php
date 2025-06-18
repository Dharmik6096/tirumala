<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkQualityParamRangeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-quality-param-range-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'milk_quality_param_range_code') ?>

    <?= $form->field($model, 'process_name') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'org_code') ?>

    <?= $form->field($model, 'org_type') ?>

    <?php // echo $form->field($model, 'animal_type_code') ?>

    <?php // echo $form->field($model, 'min_fat') ?>

    <?php // echo $form->field($model, 'max_fat') ?>

    <?php // echo $form->field($model, 'min_snf') ?>

    <?php // echo $form->field($model, 'max_snf') ?>

    <?php // echo $form->field($model, 'min_clr') ?>

    <?php // echo $form->field($model, 'max_clr') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'x_col1') ?>

    <?php // echo $form->field($model, 'x_col2') ?>

    <?php // echo $form->field($model, 'x_col3') ?>

    <?php // echo $form->field($model, 'x_col4') ?>

    <?php // echo $form->field($model, 'x_col5') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
