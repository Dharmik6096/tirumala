<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkCollectionConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-collection-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'accept_milk') ?>

    <?= $form->field($model, 'based_on') ?>

    <?= $form->field($model, 'based_on_disp') ?>

    <?= $form->field($model, 'can_per_ltr') ?>

    <?php // echo $form->field($model, 'can_warning_per') ?>

    <?php // echo $form->field($model, 'collection_mode') ?>

    <?php // echo $form->field($model, 'collection_quantity_mode') ?>

    <?php // echo $form->field($model, 'bmc_collection_quantity_mode') ?>

    <?php // echo $form->field($model, 'local_milk_sale_quantity_mode') ?>

    <?php // echo $form->field($model, 'sample_milk_quantity_mode') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'default_snf') ?>

    <?php // echo $form->field($model, 'default_snf_value') ?>

    <?php // echo $form->field($model, 'from_machine_clr') ?>

    <?php // echo $form->field($model, 'based_on_local_sale') ?>

    <?php // echo $form->field($model, 'no_disp_local_sale') ?>

    <?php // echo $form->field($model, 'per_local_sale') ?>

    <?php // echo $form->field($model, 'input_clr') ?>

    <?php // echo $form->field($model, 'lr1_for_clr') ?>

    <?php // echo $form->field($model, 'lr2_for_clr') ?>

    <?php // echo $form->field($model, 'ltr_to_kg') ?>

    <?php // echo $form->field($model, 'multi_entry_diff_milk_type') ?>

    <?php // echo $form->field($model, 'multi_entry_same_milk_type') ?>

    <?php // echo $form->field($model, 'no') ?>

    <?php // echo $form->field($model, 'no_disp') ?>

    <?php // echo $form->field($model, 'sample_milk_size') ?>

    <?php // echo $form->field($model, 'seperate_can') ?>

    <?php // echo $form->field($model, 'shift_code') ?>

    <?php // echo $form->field($model, 'shift_code_disp') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'variation_in_fat') ?>

    <?php // echo $form->field($model, 'variation_in_fat_block') ?>

    <?php // echo $form->field($model, 'variation_in_qty') ?>

    <?php // echo $form->field($model, 'variation_in_qty_block') ?>

    <?php // echo $form->field($model, 'variation_in_snf') ?>

    <?php // echo $form->field($model, 'variation_in_snf_block') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <?php // echo $form->field($model, 'weight_setting') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
